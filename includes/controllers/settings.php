<?php
class settings extends database {
    public static $return = array();
    public static $userData = array();
    public static $list = array();
    public static $consultationFee_cost;
    public static $consultationFee_component_id;
    public static $registrationFee_cost;
    public static $registrationFee_component_id;
    public static $lowInventoryCount;
    public static $medicationCategory;
    public static $alertGroup;
    public static $message;
    public static $error_message;
    public static $successResponse = array("status" => 200, "message" => "OK");
    public static $notFound = array("status" => 404, "message" => "Not Found");
    public static $NotModified = array("status" => 304, "message" => "Not Modified");
    public static $Unauthorized = array("status" => 401, "message" => "Unauthorized");
    public static $NotAcceptable = array("status" => 406, "message" => "Not Acceptable");
    public static $BadReques = array("status" => 400, "message" => "Bad Reques");
    public static $internalServerError = array("status" => 500, "message" => "Internal Server Error");

    public static function manage() {
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
        self::$list = inventory_category::getSortedList("ACTIVE", "status");

        include_once(LH_PLUGIN_DIR."includes/pages/settings/manage.php");
    }

    public static function get_settings_api($request) {
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
        self::$return['data']['lowInventoryCount'] = self::$lowInventoryCount;
        self::$return['data']['alertGroup'] = self::$alertGroup;

        return self::$return;
    }

    private function getSettings() {
        self::$consultationFee_cost = get_option("lh-consultationFee-cost");
        self::$consultationFee_component_id = get_option("lh-consultationFee-component-id");
        self::$registrationFee_cost = get_option("lh-registrationFee-cost");
        self::$registrationFee_component_id = get_option("lh-registrationFee-component-id");
        self::$medicationCategory = get_option("lh-medicationCategory");
        self::$lowInventoryCount = get_option("lh-lowInventoryCount");
        self::$alertGroup = get_option("lh-alertGroup");
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

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."settings` (
            `ref` INT NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(50) NOT NULL,
            `value` TEXT NULL,
            `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."settings`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."settings`";

        $this->query($query);
    }
}
?>