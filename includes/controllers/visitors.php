<?php
class visitors extends database {
    public static $return = array();
    public static $userData = array();
    public static $list = array();
    public static $viewData = array();
    public static $visitorsList = array();
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
        if ((isset($_REQUEST['open'])) || (isset($_REQUEST['edit']))) {
            if (isset($_REQUEST['id'])) {
                $id = $_REQUEST['id'];
                self::$viewData = self::listOne($id);
            }
        } else if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            $add = self::create($_POST, get_current_user_id());

            if ($add) {
                //send email
                self::$message = "Action Performed Successfully";
            } else {
                self::$error_message = "there was an error performing this action";
            }
        }
        self::$list = self::getList();
        if (isset($_REQUEST['done'])) {
            self::$message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            self::$error_message = $_REQUEST['error'];
        }

		include_once(LH_PLUGIN_DIR."includes/pages/visitors/manage.php");
    }

    public static function getvisitorsList($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        self::$return = self::$successResponse;
        self::$return['data'] = self::getList();
    }

    public static function read_api_component($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $id = $request['visitors_id'];
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

    private static function read_component($id) {
        self::$viewData = self::listOne($id);
    }

    public static function get_visitors_suggest_api($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $return = array();
        $search = $request['term'];

        $data = self::query("SELECT * FROM ".table_name_prefix."visitors WHERE `last_name` LIKE '%".$search."%' OR `first_name` LIKE '%".$search."%' OR `phone_number` LIKE '%".$search."%' OR `email` LIKE '%".$search."%'", false, "list");

        for ($i = 0; $i < count($data); $i++) {
            $return[$i]['id'] = $data[$i]['ref'];
            $return[$i]['label'] = $data[$i]['last_name']." ".$data[$i]['first_name'];
        }

        return $return;
    }

    public static function create_api_component($request) {
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
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $add = self::create($parameters, self::$userData['ID']);
        if ($add) {
            self::$successResponse['additional_message'] = "visitors saved successfully";
            self::$return = self::$successResponse;
            self::$return['ID'] = $add;
        } else {
            self::$BadReques['additional_message'] = "there was an error performing this action";
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    private static function create($array, $user) {
        $array['added_by'] = $user;
        return self::insert(table_name_prefix."visitors", $array);
    }

    static function modifyOne($tag, $value, $id, $ref="ref") {
        return self::updateOne(table_name_prefix."visitors", $tag, $value, $id, $ref);
    }
    
    static function getList($start=false, $limit=false, $order="last_name", $dir="ASC", $type="list") {
        return self::lists(table_name_prefix."visitors", $start, $limit, $order, $dir, false, $type);
    }

    static function getSingle($name, $tag="last_name", $ref="ref") {
        return self::getOneField(table_name_prefix."visitors", $name, $ref, $tag);
    }

    static function listOne($id) {
        return self::getOne(table_name_prefix."visitors", $id, "ref");
    }

    static function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'ref', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."visitors", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."visitors` (
            `ref` INT NOT NULL AUTO_INCREMENT,
            `last_name` VARCHAR(50) NOT NULL,
            `first_name` VARCHAR(50) NOT NULL,
            `phone_number` VARCHAR(15) NOT NULL,
            `email` VARCHAR(255) NULL,
            `address` VARCHAR(255) NULL,
            `whom_to_see` VARCHAR(255) NULL,
            `resason` TEXT NULL,
            `added_by` INT NOT NULL,
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`),
            UNIQUE KEY (`email`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."visitors`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."visitors`";

        $this->query($query);
    }
}
?>