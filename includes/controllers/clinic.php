<?php
class clinic extends database {
    public static $logged_in_user;
    public static $return = array();
    public static $userData = array();
    public static $list = array();
    public static $viewData = array();
    public static $appointmentData = array();
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
            if (self::$appointmentData['patient_id'] > 0) {
                self::$viewData = patient::listOne( self::$appointmentData['patient_id'] );
                self::$showPatient = true;
            }
        }
        if (isset($_REQUEST['patient'])) {
            self::$viewData = patient::listOne($id);
        }
		include_once(LH_PLUGIN_DIR."includes/pages/clinic/manage.php");
    }
}
?>