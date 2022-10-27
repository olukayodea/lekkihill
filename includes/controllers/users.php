<?php
class users extends database {
    public static $return = array();
    public static $userData = array();
    public static $userToken;
    public static $successResponse = array("status" => 200, "message" => "OK");
    public static $notFound = array("status" => 404, "message" => "Not Found");
    public static $NotModified = array("status" => 304, "message" => "Not Modified");
    public static $Unauthorized = array("status" => 401, "message" => "Unauthorized");
    public static $NotAcceptable = array("status" => 406, "message" => "Not Acceptable");
    public static $BadReques = array("status" => 400, "message" => "Bad Reques");
    public static $internalServerError = array("status" => 500, "message" => "Internal Server Error");
    
    public static function login($request) {
        global $capabilityArray;
        $parameters = $request->get_params();

        $creds['user_login'] = $parameters["username"];
        $creds['user_password'] =  $parameters["password"];
        $creds['remember'] = true;
        $user = wp_signon( $creds, false );

        if ( is_wp_error($user) ) {
            self::$return = self::$notFound;
            self::$return['additional_message'] = $user->get_error_message();
        } else {
            $roles = $user->roles[0];
            unset($user->data->user_pass);
            unset($user->data->user_activation_key);
            unset($user->data->user_status);
            unset($user->data->user_token);
            unset($user->cap_key);
            unset($user->allcaps);
            unset($user->filter);
            $data = $user;
            $data->token = self::getToken($user->ID);

            $data->permission = $capabilityArray[$roles];
            self::$return = self::$successResponse;
            self::$return['ID'] = $data->ID;
            self::$return['data'] = $data->data;
            self::$return['roles'] = $data->roles;
        }

        return self::$return;
    }

    public static function listUsers($request) {
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
        self::$return['data'] = self::getList();

        return self::$return;
    }

    public static function listAllUsers($request) {
        global $capabilityArray;
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
        
        $id = $request['user_id'];
        if (intval($id) > 0) {
            $auth = get_user_by("ID", $id);
            $roles = $auth->roles[0];
            unset($auth->data->user_pass);
            unset($auth->data->user_activation_key);
            unset($auth->data->user_status);
            unset($auth->data->user_token);
            unset($auth->cap_key);
            unset($auth->allcaps);
            unset($auth->filter);
            $data = $auth;

            $data->permission = $capabilityArray[$roles];
            $return['status'] = 200;
            $return['message'] = "OK";
            $return['ID'] = $data->ID;
            $return['data'] = $data->data;
            $return['roles'] = $data->roles;

            return  $return;
        } else {
            self::$BadReques['additional_message'] = "ID invalid";
            self::$return = self::$BadReques;

            return self::$return;
        }
    }

    public static function getLoggedInUser() {
        get_userdata( get_current_user_id() );

    }

    public static function getDetails($request) {
        global $capabilityArray;
        $headers = $request->get_headers();
        $id = self::authenticate($headers);

        //print_r($headers);
        //print_r($get_params);
        if ($id['status'] != 200) {
            return $id;
        } else {
            $auth = get_user_by("ID", $id['ID']);
            $roles = $auth->roles[0];
            unset($auth->data->user_pass);
            unset($auth->data->user_activation_key);
            unset($auth->data->user_status);
            unset($auth->data->user_token);
            unset($auth->cap_key);
            unset($auth->allcaps);
            unset($auth->filter);
            $data = $auth;
            $data->token = self::getToken($auth->ID, FALSE);

            $data->permission = $capabilityArray[$roles];
            $return['status'] = 200;
            $return['message'] = "OK";
            $return['ID'] = $data->ID;
            $return['data'] = $data->data;
            $return['roles'] = $data->roles;

            return  $return;
        }
    }

    private static function generateToken($id) {
        global $wpdb;
        $token = self::createRandomPassword(5).$id.self::createRandomPassword(5);
        $wpdb->update(
            $wpdb->prefix."users",
            array("user_token" => $token),
            array("ID" => $id)
        );
        return $token;
    }

    public static function getToken ($id, $login=TRUE) {
        if ($login === TRUE) {
            $token = self::generateToken($id);
        } else {
            $token = self::getSingle($id, "user_token", "ID");
            if ($token == "") {
                self::generateToken($id);
                return self::generateToken($id);
            }
        }
        return $token;
    }

    static function getSingle($name, $tag="user_login", $ref="ID") {
        global $wpdb;
        return self::getOneField($wpdb->prefix."users", $name, $ref, $tag);
    }

    static function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'sku', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."users", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    static function getList($type="list") {
        global $wpdb;
        return self::query("SELECT `user_login`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name` FROM `".$wpdb->prefix."users`", false, $type);
    }

    static function listOne($id, $ref="ID") {
        global $wpdb;
        return self::getOne($wpdb->prefix."users", $id, $ref);
    }
    
    public function createAdminType($role, $slug, $access, $capability = []) {
        $value_array = array('title' => $role, 'slug' => $slug, 'read' => intval($access['read']), 'write' => intval($access['write']), 'modify' => intval($access['modify']), 'pages' => implode(",", $capability));

        $id = $this->insert(table_name_prefix."roles", $value_array);
        
        if ($id) {
            return true;
        } else {
            return false;
        }
    }

    static function add_app_token($data, $user_id) {
        global $wpdb;

        $user = get_userdata($user_id);
        $role = (array) $user->roles;

        return self::query("UPDATE `".DB_NAME."`.`".$wpdb->prefix."users` SET `app_token` = '".md5(sha1($data['pass1']))."', `user_role` = '".$role[0]."' WHERE `ID` = ".$user_id);
    }

    public function initialize_table() {
        global $wpdb;
        //create database
        $check = "SHOW COLUMNS FROM `".DB_NAME."`.`".$wpdb->prefix."users` LIKE 'user_token';";
        if (!$this->query($check, false, 'list')) {
            $query = "ALTER TABLE `".DB_NAME."`.`".$wpdb->prefix."users` ADD `user_token` VARCHAR(500) NOT NULL AFTER `user_pass`";
            $this->query($query);
        }
        $check = "SHOW COLUMNS FROM `".DB_NAME."`.`".$wpdb->prefix."users` LIKE 'app_token';";
        if (!$this->query($check, false, 'list')) {
            $query = "ALTER TABLE `".DB_NAME."`.`".$wpdb->prefix."users` ADD `app_token` VARCHAR(500) NOT NULL AFTER `user_pass`";
            $this->query($query);
        }
        $check = "SHOW COLUMNS FROM `".DB_NAME."`.`".$wpdb->prefix."users` LIKE 'auth_token_expire';";
        if (!$this->query($check, false, 'list')) {
            $query = "ALTER TABLE `".DB_NAME."`.`".$wpdb->prefix."users` ADD `auth_token_expire` VARCHAR(50) NOT NULL AFTER `app_token`";
            $this->query($query);
        }
        $check = "SHOW COLUMNS FROM `".DB_NAME."`.`".$wpdb->prefix."users` LIKE 'user_role';";
        if (!$this->query($check, false, 'list')) {
            $query = "ALTER TABLE `".DB_NAME."`.`".$wpdb->prefix."users` ADD `user_role` VARCHAR(50) NOT NULL AFTER `auth_token_expire`";
            $this->query($query);
        }
        
        $check = "SHOW COLUMNS FROM `".DB_NAME."`.`".$wpdb->prefix."users` LIKE 'app_status';";
        if (!$this->query($check, false, 'list')) {
            $query = "ALTER TABLE `".DB_NAME."`.`".$wpdb->prefix."users` ADD `app_status` INT NOT NULL DEFAULT '0' AFTER `user_status`";
            $this->query($query);
        }
    }

    public function clear_table() {
        global $wpdb;
        //clear database
        // $query = "
        // ALTER TABLE `".DB_NAME."`.`".$wpdb->prefix."users` DROP `user_token`;
        // ALTER TABLE `".DB_NAME."`.`".$wpdb->prefix."users` DROP `user_role`;";

        // $this->query($query);
    }


    public function initialize_roles_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."roles` (
            `ref` INT NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(50) NOT NULL,
            `slug` VARCHAR(50) NOT NULL,
            `read` INT NOT NULL,
            `write` INT NOT NULL,
            `modify` INT NOT NULL,
            `pages` TEXT NULL,
            `createTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_role_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."roles`";

        $this->query($query);
    }

    public function delete_role_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."roles`";

        $this->query($query);
    }
}
?>