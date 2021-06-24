<?php
class clinic extends database {
    public static $id;
    public static $logged_in_user;
    public static $return = array();
    public static $userData = array();
    public static $patientData = array();
    public static $list = array();
    public static $medicationList = array();
    public static $inventoryList = array();
    public static $viewData = array();
    public static $appointmentData = array();
    public static $vitalsData = array();
    public static $allVitalsData = array();
    public static $message;
    public static $error_message;
    public static $successResponse = array("status" => 200, "message" => "OK");
    public static $notFound = array("status" => 404, "message" => "Not Found");
    public static $NotModified = array("status" => 304, "message" => "Not Modified");
    public static $Unauthorized = array("status" => 401, "message" => "Unauthorized");
    public static $NotAcceptable = array("status" => 406, "message" => "Not Acceptable");
    public static $BadReques = array("status" => 400, "message" => "Bad Reques");
    public static $internalServerError = array("status" => 500, "message" => "Internal Server Error");

    public static $showPatient = false;
    public static $userToken;

    public static function manageDoctorPrint() {
        self::$id = $_REQUEST['id'];

        self::$patientData['details'] = patient::listOne(self::$id);
        self::$patientData['details']['patientId'] = patient::patienrNumber( self::$id );
        self::get_note( self::$id );
        self::$patientData['notes'] = self::$allVitalsData;
		include_once(LH_PLUGIN_DIR."includes/pages/clinic/manageDoctorPrint.php");
    }

    public static function managePrint() {
        self::$id = $_REQUEST['id'];

        self::$patientData['details'] = patient::listOne(self::$id);
        self::$patientData['details']['patientId'] = patient::patienrNumber( self::$id );
        self::recent_vital( self::$id );
        self::recent_continuation( self::$id );
        self::recent_post_op( self::$id );
        self::recent_medication( self::$id );
        self::recent_fluid_balance( self::$id );
		include_once(LH_PLUGIN_DIR."includes/pages/clinic/managePrint.php");
    }

    public static function manageMedication() {
        self::$userToken = users::getToken( get_current_user_id(), FALSE );
        self::$logged_in_user = get_userdata( get_current_user_id() );
        $id = $_REQUEST['id'];


        if (isset($_POST['submit'])) {

            $_POST['added_by'] = get_current_user_id();
            unset($_POST['submit']);

            if (self::sell_medication($_POST)) {
                self::$message = "Medication dispensed successfully";
                self::$viewData = array();
            } else {
                self::$error_message = "there was an error performing this action";
            }
        }

        if (isset($_REQUEST['patient'])) {
            self::$viewData = patient::listOne($id);
            billing::get_due_invoice($id, false);

            self::medicationHistory($id);
        }
        billing::list_component();
        self::$inventoryList = inventory::getSortedList(get_option("lh-medicationCategory"), "category_id", "status", "ACTIVE");
		include_once(LH_PLUGIN_DIR."includes/pages/clinic/manage_medication.php");
    }

    public static function manage() {
        self::$userToken = users::getToken( get_current_user_id(), FALSE );
        self::$logged_in_user = get_userdata( get_current_user_id() );
        appointments::today();

        $id = $_REQUEST['id'];
        if (isset($_REQUEST['appointment'])) {
            self::$appointmentData = appointments::listOne($id);
            billing::get_due_invoice(false, self::$appointmentData['email']);
            if (self::$appointmentData['patient_id'] > 0) {
                self::$viewData = patient::listOne( self::$appointmentData['patient_id'] );
                self::$showPatient = true;
                billing::get_due_invoice(self::$appointmentData['patient_id'], false);
            }
        }
        if (isset($_POST['payInvoice'])) {
            unset($_POST['payInvoice']);
            if (invoice::payInvoice($_POST)){
                self::$message = "Payment Successful";
                self::$viewData = array();
            } else {
                self::$error_message = "there was an error performing this action";
            }
        } else if (isset($_POST['submit_vitals'])) {
            unset($_POST['submit_vitals']);
            $_POST['patient_id'] = $id;
            $_POST['added_by'] = get_current_user_id();
            
            if (self::add_vitals($_POST)) {
                self::$message = "Vitals saved successfully";
                self::$viewData = array();
            } else {
                self::$error_message = "there was an error performing this action";
            }
        } else if (isset($_POST['patient_id'])) {
            unset($_POST['postPayment']);
            $_POST['added_by'] = get_current_user_id();
            $_POST['due_date'] = date("Y-m-d");

            $_POST['billing_component'][] = array("id"=>$_POST['id'],"cost"=>$_POST['cost'],"type"=>"component","quantity"=>$_POST['quantity'],"description"=>$_POST['description']);

            if (invoice::postPayment($_POST)) {
                self::$message = "Payment Successful";
                self::$viewData = array();
            } else {
                self::$error_message = "there was an error performing this action";
            }
            $id = $_POST['patient_id'];
        }

        if (isset($_REQUEST['patient'])) {
            self::$viewData = patient::listOne($id);
            billing::get_due_invoice($id, false);
        }

        self::get_vital($id);
        self::recent_vital($id);

        billing::list_component();
        self::$inventoryList = inventory::getSortedList(get_option("lh-medicationCategory"), "category_id", "status", "ACTIVE");
        self::$vitalsData['respiratory'] = "";
        self::$vitalsData['temprature'] = "";
        self::$vitalsData['pulse'] = "";
        self::$vitalsData['bp_sys'] = "";
        self::$vitalsData['bp_dia'] = "";
		include_once(LH_PLUGIN_DIR."includes/pages/clinic/manage.php");
    }

    private static function sell_medication($array) {
        $add = patient_medication::create($array);
        if ($add) {
            self::$message = "Medication dispensed successfully";
        } else {
            self::$error_message = "there was an error performing this action";
        }
        return $add;
    } 

    private static function add_vitals($array) {
        $add = vitals::create($array);
        if ($add) {
            self::$message = "Patient's vitals saved successfully";
        } else {
            self::$error_message = "there was an error performing this action";
        }

        return $add;
    }

    private static function medicationHistory($id) {
        self::$medicationList = patient_medication::getSortedList($id, "patient_id");
    }

    public function medicationHistory_api($request) {
        /**
         * API authentication
         */
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $id = $request['patient_id'];
        self::medicationHistory($id, "patient_id");
        self::$return = self::$successResponse;
        self::$return['data'] = self::formatResult( self::$medicationList );

        return self::$return;
    }

    private static function add_medication($array) {
        return clinic_medication::create($array);
    }

    private static function add_post_op($array) {
        return clinic_post_op::create($array);
    }

    private static function add_continuation($array) {
        return clinic_continuation_sheet::create($array);
    }

    private static function add_notes($array) {
        return clinic_doctors_report::create($array);
    }

    private static function add_fluid_balanceion($array) {
        return clinic_fluid_balance::create($array);
    }

    private static function get_vital($id) {
        self::$allVitalsData = vitals::getSortedList($id, "patient_id");
        return true;
    }

    private static function get_note($id) {
        self::$allVitalsData = clinic_doctors_report::getSortedList($id, "patient_id");
        return true;
    }

    private static function get_continuation($id) {
        self::$allVitalsData = clinic_continuation_sheet::getSortedList($id, "patient_id");
        return true;
    }

    private static function get_post_op($id) {
        self::$allVitalsData = clinic_post_op::getSortedList($id, "patient_id");
        return true;
    }

    private static function get_medication($id) {
        self::$patientData['medication'] = self::$allVitalsData = clinic_medication::getSortedList($id, "patient_id");
        return true;
    }

    private static function get_fluid_balance($id) {
        self::$allVitalsData = clinic_fluid_balance::getSortedList($id, "patient_id");
        return true;
    }
    
    private static function recent_vital ($id) {
        self::$patientData['vitals'] = self::$vitalsData = vitals::getSortedList($id, "patient_id", false, false, false, false, "ref", "DESC", "AND", false, 1)[0];
        return true;
    }
    
    private static function recent_continuation ($id) {
        self::$patientData['continuation'] = self::$vitalsData = clinic_continuation_sheet::getSortedList($id, "patient_id", false, false, false, false, "ref", "DESC", "AND", false, 1)[0];
        return true;
    }
    
    private static function recent_post_op ($id) {
        self::$patientData['post_op'] = self::$vitalsData = clinic_post_op::getSortedList($id, "patient_id", false, false, false, false, "ref", "DESC", "AND", false, 1)[0];
        return true;
    }
    
    private static function recent_medication ($id) {
        self::$patientData['medication'] = self::$vitalsData = clinic_medication::getSortedList($id, "patient_id", false, false, false, false, "ref", "DESC", "AND", false, 1)[0];
        return true;
    }
    
    private static function recent_fluid_balance ($id) {
        self::$patientData['fluid_balance'] = self::$vitalsData = clinic_fluid_balance::getSortedList($id, "patient_id", false, false, false, false, "ref", "DESC", "AND", false, 1)[0];
        return true;
    }

    public static function api_add_note($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $parameters = $request->get_params();
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $parameters['added_by'] = self::$userData['ID'];

        $add = self::add_notes($parameters);
        if ($add) {
            self::$return = self::$successResponse;
        } else {
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function api_add_continuation($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $parameters = $request->get_params();
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $parameters['added_by'] = self::$userData['ID'];

        $add = self::add_continuation($parameters);
        if ($add) {
            self::$return = self::$successResponse;
        } else {
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function api_add_medication($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $parameters = $request->get_params();
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $parameters['added_by'] = self::$userData['ID'];

        $add = self::add_medication($parameters);
        if ($add) {
            self::$return = self::$successResponse;
        } else {
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function api_add_post_op($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $parameters = $request->get_params();
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $parameters['added_by'] = self::$userData['ID'];

        $add = self::add_post_op($parameters);
        if ($add) {
            self::$return = self::$successResponse;
        } else {
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function api_add_fluid_balance($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $parameters = $request->get_params();
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $parameters['added_by'] = self::$userData['ID'];

        $add = self::add_fluid_balanceion($parameters);
        if ($add) {
            self::$return = self::$successResponse;
        } else {
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function api_get_continuation($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['patient_id'];
        if (intval($id) > 0) {
            if (self::get_continuation($id)) {
                self::$return = self::$successResponse;
                self::$return['data'] = clinic_continuation_sheet::formatResult( self::$allVitalsData );
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing Patient ID";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function api_get_medication($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['patient_id'];
        if (intval($id) > 0) {
            if (self::get_medication($id)) {
                self::$return = self::$successResponse;
                self::$return['data'] = clinic_medication::formatResult( self::$allVitalsData );
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing Patient ID";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function api_get_post_op($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['patient_id'];
        if (intval($id) > 0) {
            if (self::get_post_op($id)) {
                self::$return = self::$successResponse;
                self::$return['data'] = clinic_post_op::formatResult( self::$allVitalsData );
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing Patient ID";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function api_get_fluid_balance($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['patient_id'];
        if (intval($id) > 0) {
            if (self::get_fluid_balance($id)) {
                self::$return = self::$successResponse;
                self::$return['data'] = clinic_fluid_balance::formatResult( self::$allVitalsData );
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing Patient ID";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function api_add_vitals($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $parameters = $request->get_params();
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $parameters['added_by'] = self::$userData['ID'];

        $add = self::add_vitals($parameters);
        if ($add) {
            self::$return = self::$successResponse;
        } else {
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    public static function api_sell_medication($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $parameters = $request->get_params();
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $parameters['added_by'] = self::$userData['ID'];

        $add = self::sell_medication($parameters);
        if ($add) {
            self::$return = self::$successResponse;
        } else {
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    public static function api_get_notes($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['patient_id'];
        if (intval($id) > 0) {
            if (self::get_note($id)) {
                self::$return = self::$successResponse;
                self::$return['data'] = vitals::formatResult( self::$allVitalsData );
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing Patient ID";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function api_get_vital($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['patient_id'];
        if (intval($id) > 0) {
            if (self::get_vital($id)) {
                self::$return = self::$successResponse;
                self::$return['data'] = vitals::formatResult( self::$allVitalsData );
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing Patient ID";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function api_recent_vital ($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['patient_id'];
        if (intval($id) > 0) {
            if (self::recent_vital($id)) {
                self::$return = self::$successResponse;
                self::$return['data'] = vitals::formatResult( self::$vitalsData, true );
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing Patient ID";
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    public static function api_recent_continuation ($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['patient_id'];
        if (intval($id) > 0) {
            if (self::recent_continuation($id)) {
                self::$return = self::$successResponse;
                self::$return['data'] = vitals::formatResult( self::$vitalsData, true );
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing Patient ID";
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    public static function api_recent_post_op ($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['patient_id'];
        if (intval($id) > 0) {
            if (self::recent_post_op($id)) {
                self::$return = self::$successResponse;
                self::$return['data'] = vitals::formatResult( self::$vitalsData, true );
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing Patient ID";
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    public static function api_recent_medication ($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['patient_id'];
        if (intval($id) > 0) {
            if (self::recent_medication($id)) {
                self::$return = self::$successResponse;
                self::$return['data'] = vitals::formatResult( self::$vitalsData, true );
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing Patient ID";
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    public static function api_recent_fluid_balance ($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['patient_id'];
        if (intval($id) > 0) {
            if (self::recent_fluid_balance($id)) {
                self::$return = self::$successResponse;
                self::$return['data'] = vitals::formatResult( self::$vitalsData, true );
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing Patient ID";
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    private static function cleanMessage($meesgae, $int=false) {
        return ("" != trim($meesgae)) ? nl2br($meesgae) : "Not Available";
    } 

    private static function getAdminName($id) {
        $user_info = get_userdata($id);
        $first_name = $user_info->first_name;
        $last_name = $user_info->last_name;

        return self::cleanMessage($first_name." ".$last_name);
    }

    public static function print_view() {
        if (isset($_REQUEST['id'])) {
            global $pdf;
            
            self::$id = $_REQUEST['id'];

            self::$patientData['details'] = patient::listOne(self::$id);
            self::$patientData['details']['patientId'] = patient::patienrNumber( self::$id );
            self::recent_vital( self::$id );
            self::recent_continuation( self::$id );
            self::recent_post_op( self::$id );
            self::recent_medication( self::$id );
            self::recent_fluid_balance( self::$id );
                              
            $data = self::$patientData;
            // set document information
            $pdf->SetCreator('LekkiHill');
            $pdf->SetAuthor('LekkiHill');
            $pdf->SetTitle('Patient Report');
            $pdf->SetSubject('Patient Report');
            $pdf->SetKeywords('LekkiHill, PDF, Patient, Report');
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            
            $pdf->SetDefaultMonospacedFont("courier");

            $pdf->SetFont('dejavusans', '', 10);

            // add a page
            $pdf->AddPage();
            
            $pdf->Ln();

            // create some HTML content

            $html = '<small class="right">Print Date: '.date('l jS \of F Y h:i:s A').'</small><br>
            <table class=\'widefat striped fixed\'>
                <tr>
                    <td class="buttom"><img src="https://lekkihill.com/wp-content/uploads/2020/05/new-lekki-logo-e1590591179900.png" width="150"></td>
                    <td class="buttom">
                        17, Omorinre Johnson Street,<br>
                        Lekki Phase 1,<br>
                        Lagos, Nigeria<br>
                        +234 802 237 3339<br>
                        info@lekkihill.com<br>
                    </td>
                </tr>
            </table>
            <h2>Patient\'s Details</h2>
            <table class="widefat striped fixed">
                <tbody>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Patient ID</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['patientId'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Last Name</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['last_name'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Other Names</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['first_name'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Age</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['age'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Sex</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['sex'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Phone Number</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['phone_number'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Email</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['email'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Address</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['address'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Next of Kin</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['next_of_Kin'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Next of Kin Contact</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['next_of_contact'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Next of Kin Address</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['next_of_address'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Allergies</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['allergies'].'</i></td>
                </tr>
                </tbody>
            </table>';
            if ((isset($_REQUEST['vitals'])) || (isset($_REQUEST['all']))) {
                $html .= '<h2>Vitals</h2>
                <table class="widefat striped fixed">
                    <tbody>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Weight (Kg)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['vitals']['weight']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Height (cm)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['vitals']['height']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>BMI (kg/m²)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['vitals']['bmi']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>SpO2 (%)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['vitals']['spo2']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Respiratory (cpm)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['vitals']['respiratory']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Temperature (⁰C)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['vitals']['temprature']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Pulse Rate (bpm)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['vitals']['pulse']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Blood Pressure-SYS (mmHg)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['vitals']['bp_sys']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Blood Pressure-DIA (mmHg)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['vitals']['bp_dia']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::getAdminName(
                            $data['vitals']['added_by']
                            ).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['vitals']['create_time']).'</i></td>
                    </tr>
                    </tbody>
                </table>';
            }
            if ((isset($_REQUEST['continuationSheet'])) || (isset($_REQUEST['all']))) {
                $html .= '<h2>Clinic Continuation</h2>
                <p id="summary_c"><i>'.self::cleanMessage($data['continuation']['notes']).'</i></p>
                <small id="summary_added_by"><i>'.self::getAdminName($data['continuation']['added_by']).'</i></small>
                <small id="summary_c_create_time"><i>'.self::cleanMessage($data['continuation']['create_time']).'</i></small><br>';
            }
            if ((isset($_REQUEST['operativeNote'])) || (isset($_REQUEST['all']))) {
                $html .= '<h2>Post Operative Note</h2>
                <table class="widefat striped fixed">
                    <tbody>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Surgery</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['surgery']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Category</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['surgery_category']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Indication</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['indication']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Surgeon</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['surgeon']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Assistant Surgeon</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['asst_surgeon']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Peri Op Nurse</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['per_op_nurse']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Circulating Nurse</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['circulating_nurse']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Anaestdesia</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['anaesthesia']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Anaestdesia Time</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['anaesthesia_time']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Knife on Skin</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['knife_on_skin']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Infiltration Time</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['infiltration_time']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Liposuction Time</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['liposuction_time']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>End of Surgery</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['end_of_surgery']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Procedure</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['procedure']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Transfered Fat (Right Buttock)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['amt_of_fat_right']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Transfered Fat (Right Buttock)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['amt_of_fat_left']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Transfered Fat (Otder Areas)</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['amt_of_fat_other']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>EBL</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['ebl']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Plan</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['plan']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::getAdminName($data['post_op']['added_by']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['post_op']['modify_time']).'</i></td>
                    </tr>
                    </tbody>
                </table>';
            }
            if ((isset($_REQUEST['medication'])) || (isset($_REQUEST['all']))) {
                $html .= '<h2>Medication</h2>
                <table class="widefat striped fixed">
                    <tbody>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Route</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['medication']['route']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Medication</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['medication']['medication']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Dose</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['medication']['dose']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Frequency</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['medication']['frequency']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Report Date</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage(trim($data['medication']['report_date']." : ".$data['medication']['report_time']), " : ").'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::getAdminName($data['medication']['added_by']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['medication']['create_time']).'</i></td>
                    </tr>
                    </tbody>
                </table>';
            }
            if ((isset($_REQUEST['massage'])) || (isset($_REQUEST['all']))) {
                $html .= '<h2>Massage Register</h2>';
            }
            if ((isset($_REQUEST['fluidBalance'])) || (isset($_REQUEST['all']))) {
                $html .= '<h2>Fluid Balance</h2>
                <table class="widefat striped fixed">
                    <tbody>
                    <tr>
                        <td class="manage-column column-columnname" scope="col" colspan="2"><strong>Intake (ML)</strong></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong> of IV Fluid</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['fluid_balance']['iv_fluid']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Amount</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['fluid_balance']['amount']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Oral Fluid</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['fluid_balance']['oral_fluid']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>NG Tube Feeding</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['fluid_balance']['ng_tube_feeding']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col" colspan="2"><strong>Output (ML)</strong></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Vomit</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['fluid_balance']['vomit']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Urine</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['fluid_balance']['urine']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Drains</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['fluid_balance']['drains']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>NG Tube Drainage</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['fluid_balance']['ng_tube_drainage']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Report Date</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage(trim($data['fluid_balance']['report_date']." : ".$data['fluid_balance']['report_time']), " : ").'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Added By</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::getAdminName($data['fluid_balance']['added_by']).'</i></td>
                    </tr>
                    <tr>
                        <td class="manage-column column-columnname" scope="col"><strong>Added On</strong></td>
                        <td class="manage-column column-columnname" scope="col" id="summary_fb_iv_fluid"><i>'.self::cleanMessage($data['fluid_balance']['create_time']).'</i></td>
                    </tr>
                    </tbody>
                </table>';
            }
            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output($data['details']['patientId']."_".$data['details']['last_name'].'.pdf', 'I');
        }
    }

    public static function print_doctor_view() {
        if (isset($_REQUEST['id'])) {
            global $pdf;
            
            self::$id = $_REQUEST['id'];
            self::$patientData['details'] = patient::listOne(self::$id);
            self::$patientData['details']['patientId'] = patient::patienrNumber( self::$id );
            self::get_note( self::$id );
            self::$patientData['notes'] = self::$allVitalsData;
                              
            $data = self::$patientData;
            // set document information
            $pdf->SetCreator('LekkiHill');
            $pdf->SetAuthor('LekkiHill');
            $pdf->SetTitle('Patient Report');
            $pdf->SetSubject('Patient Report');
            $pdf->SetKeywords('LekkiHill, PDF, Patient, Report');
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            
            $pdf->SetDefaultMonospacedFont("courier");

            $pdf->SetFont('dejavusans', '', 10);

            // add a page
            $pdf->AddPage();
            
            $pdf->Ln();

            // create some HTML content

            $html = '<small class="right">Print Date: '.date('l jS \of F Y h:i:s A').'</small><br>
            <table class=\'widefat striped fixed\'>
                <tr>
                    <td class="buttom"><img src="https://lekkihill.com/wp-content/uploads/2020/05/new-lekki-logo-e1590591179900.png" width="150"></td>
                    <td class="buttom">
                        17, Omorinre Johnson Street,<br>
                        Lekki Phase 1,<br>
                        Lagos, Nigeria<br>
                        +234 802 237 3339<br>
                        info@lekkihill.com<br>
                    </td>
                </tr>
            </table>
            <h2>Doctor\'s Notes</h2>
            <table class=\'widefat striped fixed\'>
                <tbody>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Patient ID</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['patientId'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Last Name</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['last_name'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Other Names</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['first_name'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Age</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['age'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Sex</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['sex'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Phone Number</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['phone_number'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Email</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['email'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Address</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['address'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Next of Kin</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['next_of_Kin'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Next of Kin Contact</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['next_of_contact'].'</i></td>
                </tr>
                <tr>
                    <td class="manage-column column-columnname" scope="col"><strong>Next of Kin Address</strong></td>
                    <td class="manage-column column-columnname" scope="col"><i>'.$data['details']['next_of_address'].'</i></td>
                </tr>
                <tr>
                    <td scope="col"><h2>Notes</strong></h2>
                </tr>';
                foreach($data['notes'] as $row) {
                    $user_info = get_userdata($row['added_by']);
                    $html .= '<tr>
                    <td colspan="2" class="manage-column column-columnname" scope="col">
                        <span class="notes">'.$row['report'].'</span><br>
                        <small>Added by<br>
                        '.$user_info->first_name." ".$user_info->last_name.'</small><br>
                        <small>Added On<br>
                        '.$row['create_time'].'</small><br><br>
                    </td>
                </tr>';
                }
                $html .= '</tbody>
            </table> 
            ';
            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output($data['details']['patientId']."_".$data['details']['last_name'].'.pdf', 'I');
        }
    }

    public static function formatResult($data, $single=false) {
        if ($single == false) {
            for ($i = 0; $i < count($data); $i++) {
                $data[$i] = self::clean($data[$i]);
            }
        } else {
            $data = self::clean($data);
        }
        return $data;
    }

    public static function clean($data) {
        $patient_id['id'] = $data['patient_id'];
        $patient_id['first_name'] = patient::getSingle( $data['patient_id'], "first_name" );
        $patient_id['last_name'] = patient::getSingle( $data['patient_id'], "last_name" );
        $patient_id['email'] = patient::getSingle( $data['patient_id'], "email" );
        $patient_id['phone_number'] = patient::getSingle( $data['patient_id'], "phone_number" );
        $data['patient_id'] = $patient_id;
        $medication_id['ref'] = $data['medication_id'];
        $medication_id['name'] = inventory::getSingle( $data['medication_id'] );
        $data['medication'] = $medication_id;
        unset($data['medication_id']);
        return $data;
    }
}

//other clinic classes and functions
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic_post_op.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic_fluid_balance.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic_continuation_sheet.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic_medication.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic_doctors_report.php';
$clinic_post_op = new clinic_post_op;
$clinic_fluid_balance = new clinic_fluid_balance;
$clinic_continuation_sheet = new clinic_continuation_sheet;
$clinic_medication =  new clinic_medication;
$clinic_doctors_report = new clinic_doctors_report;
?>