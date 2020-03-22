<?php
class billing extends database {
    public static $return = array();
    public static $userData = array();
    public static $list = array();
    public static $viewData = array();
    public static $message;
    public static $error_message;
    public static $successResponse = array("status" => 200, "message" => "OK");
    public static $notFound = array("status" => 404, "message" => "Not Found");
    public static $NotModified = array("status" => 304, "message" => "Not Modified");
    public static $Unauthorized = array("status" => 401, "message" => "Unauthorized");
    public static $NotAcceptable = array("status" => 406, "message" => "Not Acceptable");
    public static $BadReques = array("status" => 400, "message" => "Bad Reques");
    public static $internalServerError = array("status" => 500, "message" => "Internal Server Error");
    public function search() {

    }

    public function component() {
        if (isset($_REQUEST['done'])) {
            self::$message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            self::$error_message = $_REQUEST['error'];
        }

        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            self::read_component($id);
            $tag = "Modify Component";
        } else {
            $tag = "Add Component";
        }

        if (isset($_POST['submit'])) {
            unset($_POST['submit']);

            $array['created_by'] = get_current_user_id();
            $array['last_modified_by'] = get_current_user_id();

            $add = billing_component::create($_POST);
            if ($add) {
                self::$message = "Component saved successfully";
                unset(self::$viewData);
            } else {
                self::$error_message = "there was an error performing this action";
            }
        } else if (isset($_GET['changeStatus'])) {
            self::change_component_status($_GET['id'], $_GET['changeStatus']);
        } else if (isset($_GET['remove'])) {
            self::delete_component( $_GET['id']);
        }
        self::list_component();
        include_once(LH_PLUGIN_DIR."/includes/pages/billings/component.php");
    }

    public function create_api_component($request) {
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
        $parameters = $request->get_params();
        $parameters['status'] = "ACTIVE";
        $parameters['created_by'] = self::$userData['ID'];
        $parameters['last_modified_by'] = self::$userData['ID'];

        $add = billing_component::create($parameters);
        if ($add) {
            self::$successResponse['additional_message'] = "Component saved successfully";
            self::$return = self::$successResponse;
            self::$return['ID'] = $add;
        } else {
            self::$BadReques['additional_message'] = "there was an error performing this action";
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    public function read_api_component($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $id = $request['component_id'];
        if (intval($id) > 0) {
            self::read_component($id);
            self::$return = self::$successResponse;
            self::$return['data'] = self::$viewData;
        } else {
            self::$BadReques['additional_message'] = "ID invalid";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    private function read_component($id) {
        self::$viewData = billing_component::listOne($id);
    }

    public function update_api_component() {
    }

    public function delete_api_component($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $id = $request['component_id'];
        if (intval($id) > 0) {
            if (self::delete_component($id)) {
            self::$BadReques['additional_message'] = self::$message;
            self::$return = self::$successResponse;
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing billing component ID";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    private function delete_component($id) {
        $update = self::updateOne(table_name_prefix."billing_component", "status", "DELETED", $id);
        if ($update) {
            self::$message = "Category deleted successfully";
            return true;
        } else {
            self::$error_message = "there was an error performing this action";
            return false;
        }
    }

    public function change_api_component_status($request) {
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
        $parameters = $request->get_params();
        $id = $parameters['id'];
        $status = $parameters['status']; 

        if (self::change_component_status($id, $status)) {
            self::$BadReques['additional_message'] = self::$message;
            self::$return = self::$successResponse;
        } else {
            self::$BadReques['additional_message'] = self::$error_message;
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    private function change_component_status($id, $status) {
        if ($status == "INACTIVE") {
            $tag = "ACTIVE";
            $msg = "activated";
        } else if ($status == "ACTIVE") {
            $tag = "INACTIVE";
            $msg = "deactivated";
        }

        $update = self::updateOne(table_name_prefix."billing_component", "status", $tag, $id);
        if ($update) {
            self::$message = "Category ".$msg." successfully";
            return true;
        } else {
            self::$error_message = "there was an error performing this action";
            return false;
        }
    }

    public function list_api_component($request) {
        self::list_component();
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
        self::$return = self::$successResponse;
        self::$return['data'] = self::$list;

        return self::$return;
    }

    public function list_component() {
        self::$list =  billing_component::getList();
    }

    public function report() {

    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."billing` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `parent_id` INT NOT NULL, 
            `category_title` VARCHAR(50) NOT NULL,
            `status` varchar(20) NOT NULL DEFAULT 'INACTIVE',
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."billing`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."billing`";

        $this->query($query);
    }
}
//other inventory classes and functions
require_once LH_PLUGIN_DIR . 'includes/controllers/billing_component.php';
?>