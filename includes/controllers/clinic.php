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

    public function manage() {
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

    private function add_vitals($array) {
        $add = vitals::create($array);
        if ($add) {
            self::$message = "Patient's vitals saved successfully";
        } else {
            self::$error_message = "there was an error performing this action";
        }

        return $add;
    }

    private function get_vital($id) {
        self::$allVitalsData = vitals::getSortedList($id, "patient_id");
        return true;
    }

    private function recent_vital ($id) {
        self::$vitalsData = vitals::getSortedList($id, "patient_id", false, false, false, false, "ref", "DESC", "AND", false, 1)[0];
        return true;
    }

    public function api_add_vitals($request) {
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

    public function api_get_vital($request) {
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

    public function api_recent_vital ($request) {
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
}
//other clinic classes and functions
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic_post_op.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic_fluid_balance.php';
$clinic_post_op = new clinic_post_op;
$clinic_fluid_balance = new clinic_fluid_balance;
?>