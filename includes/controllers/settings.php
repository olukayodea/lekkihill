<?php
class settings extends database {
    public static $return = array();
    public static $userData = array();
    public static $consultationFee;
    public static $message;
    public static $error_message;
    public static $successResponse = array("status" => 200, "message" => "OK");
    public static $notFound = array("status" => 404, "message" => "Not Found");
    public static $NotModified = array("status" => 304, "message" => "Not Modified");
    public static $Unauthorized = array("status" => 401, "message" => "Unauthorized");
    public static $NotAcceptable = array("status" => 406, "message" => "Not Acceptable");
    public static $BadReques = array("status" => 400, "message" => "Bad Reques");
    public static $internalServerError = array("status" => 500, "message" => "Internal Server Error");

    public function manage() {
        if (isset($_POST['submit'])) {
            unset($_POST['submit']);

            foreach($_POST as $key => $value) {
                self::setSettings("lh-".$key, $value);
            }

            self::$message = "Settings Saved successfully";
        }
        self::getSettings();
        billing::list_component();
        include_once(LH_PLUGIN_DIR."includes/pages/settings/manage.php");
    }

    public function get_settings_api($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        self::getSettings();

        self::$return = self::$successResponse;
        self::$return['data']['consultationFee'] = self::$consultationFee;

        return self::$return;
    }

    private function getSettings() {
        self::$consultationFee = get_option("lh-consultationFee");
    }

    private function setSettings($field, $value) {
        update_option($field, $value);
    }

    public static function create_api( WP_REST_Request $request) {
        $parameters = $request->get_params();

        foreach($parameters as $key => $value) {
            self::setSettings("lh-".$key, $value);
        }

        self::$return = self::$successResponse;
        return self::$return;
    }
}
?>