<?php
class clinic extends database {
    public static $logged_in_user;
    public static $return = array();
    public static $userData = array();
    public static $list = array();
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
        if (isset($_REQUEST['patient'])) {
            self::$viewData = patient::listOne($id);
            billing::get_due_invoice($id, false);
        }
        if (isset($_POST['submit_vitals'])) {
            unset($_POST['submit_vitals']);
            $_POST['patient_id'] = $id;
            $_POST['added_by'] = get_current_user_id();
            
            if (self::add_vitals($_POST)) {
                self::$message = "Vitals saved successfully";
                self::$viewData = array();
            } else {
                self::$error_message = "there was an error performing this action";
            }
        }

        self::get_vital($id);
        self::recent_vital($id);
        self::$vitalsData['respiratory'] = "";
        self::$vitalsData['temprature'] = "";
        self::$vitalsData['pulse'] = "";
        self::$vitalsData['bp_sys'] = "";
        self::$vitalsData['bp_dia'] = "";
		include_once(LH_PLUGIN_DIR."includes/pages/clinic/manage.php");
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

    private static function add_medication($array) {
        return clinic_medication::create($array);
    }

    private static function add_post_op($array) {
        return clinic_post_op::create($array);
    }

    private static function add_continuation($array) {
        return clinic_continuation_sheet::create($array);
    }

    private static function add_fluid_balanceion($array) {
        return clinic_fluid_balance::create($array);
    }

    private static function get_vital($id) {
        self::$allVitalsData = vitals::getSortedList($id, "patient_id");
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
        self::$allVitalsData = clinic_medication::getSortedList($id, "patient_id");
        return true;
    }

    private static function get_fluid_balance($id) {
        self::$allVitalsData = clinic_fluid_balance::getSortedList($id, "patient_id");
        return true;
    }
    
    private static function recent_vital ($id) {
        self::$vitalsData = vitals::getSortedList($id, "patient_id", false, false, false, false, "ref", "DESC", "AND", false, 1)[0];
        return true;
    }
    
    private static function recent_continuation ($id) {
        self::$vitalsData = clinic_continuation_sheet::getSortedList($id, "patient_id", false, false, false, false, "ref", "DESC", "AND", false, 1)[0];
        return true;
    }
    
    private static function recent_post_op ($id) {
        self::$vitalsData = clinic_post_op::getSortedList($id, "patient_id", false, false, false, false, "ref", "DESC", "AND", false, 1)[0];
        return true;
    }
    
    private static function recent_medication ($id) {
        self::$vitalsData = clinic_medication::getSortedList($id, "patient_id", false, false, false, false, "ref", "DESC", "AND", false, 1)[0];
        return true;
    }
    
    private static function recent_fluid_balance ($id) {
        self::$vitalsData = clinic_fluid_balance::getSortedList($id, "patient_id", false, false, false, false, "ref", "DESC", "AND", false, 1)[0];
        return true;
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
}
//other clinic classes and functions
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic_post_op.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic_fluid_balance.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic_continuation_sheet.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic_medication.php';
$clinic_post_op = new clinic_post_op;
$clinic_fluid_balance = new clinic_fluid_balance;
$clinic_continuation_sheet = new clinic_continuation_sheet;
$clinic_medication =  new clinic_medication;
?>