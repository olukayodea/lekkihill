<?php
class settings extends database {
    public static $return = array();
    public static $userData = array();
    public static $consultationFee_cost;
    public static $consultationFee_component_id;
    public static $registrationFee_cost;
    public static $registrationFee_component_id;
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
                if ($key == "consultationFee") {
                    $valData = explode("_", $value);
                    self::setSettings("lh-consultationFee-cost", $valData[1]);
                    self::setSettings("lh-consultationFee-component-id", $valData[0]);
                } else if ($key == "registrationFee") {
                    $valData = explode("_", $value);
                    self::setSettings("lh-registrationFee-cost", $valData[1]);
                    self::setSettings("lh-registrationFee-component-id", $valData[0]);
                } else {
                    self::setSettings("lh-".$key, $value);
                }
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
        self::$return['data']['consultationFee_cost'] = self::$consultationFee_cost;
        self::$return['data']['consultationFee_component_id'] = self::$consultationFee_component_id;
        self::$return['data']['registrationFee_cost'] = self::$registrationFee_cost;
        self::$return['data']['registrationFee_component_id'] = self::$registrationFee_component_id;

        return self::$return;
    }

    private function getSettings() {
        self::$consultationFee_cost = get_option("lh-consultationFee-cost");
        self::$consultationFee_component_id = get_option("lh-consultationFee-component-id");
        self::$registrationFee_cost = get_option("lh-registrationFee-cost");
        self::$registrationFee_component_id = get_option("lh-registrationFee-component-id");
    }

    private function setSettings($field, $value) {
        update_option($field, $value);
    }

    public static function create_api( WP_REST_Request $request) {
        $parameters = $request->get_params();

        foreach($parameters as $key => $value) {
            $costData = billing_component::listOne($value);
            if ($key == "consultationFee_component") {
                self::setSettings("lh-consultationFee-cost", $costData['cost']);
                self::setSettings("lh-consultationFee-component-id", $costData['ref']);
            } else if ($key == "registrationFee_component") {
                self::setSettings("lh-registrationFee-cost", $costData['cost']);
                self::setSettings("lh-registrationFee-component-id", $costData['ref']);
            } else {
                self::setSettings("lh-".$key, $value);
            }
        }

        self::$return = self::$successResponse;
        return self::$return;
    }
}
?>