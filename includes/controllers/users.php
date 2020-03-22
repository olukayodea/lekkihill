<?php
class users extends database {
    public static $return = array();
    public static $userData = array();
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
            $return['status'] = "200";
            $return['message'] = "OK";
            $return['ID'] = $data->ID;
            $return['data'] = $data->data;
            $return['roles'] = $data->roles;

            return  $return;
        } else {
            self::$BadReques['additional_message'] = "ID invalid";
            self::$return = self::$BadReques;
        }

        return $return;
    }

    public static function getDetails($request) {
        global $capabilityArray;
        $headers = $request->get_headers();
        $id = self::authenticate($headers);

        //print_r($headers);
        //print_r($get_params);
        if ($id['status'] != "200") {
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
            $return['status'] = "200";
            $return['message'] = "OK";
            $return['ID'] = $data->ID;
            $return['data'] = $data->data;
            $return['roles'] = $data->roles;

            return  $return;
        }
    }

    private static function getToken ($id, $login=TRUE) {
        if ($login === TRUE) {
            global $wpdb;
            $token = self::createRandomPassword(5).$id.self::createRandomPassword(5);
            $wpdb->update(
                $wpdb->prefix."users",
                array("user_token" => $token),
                array("ID" => $id)
            );
        } else {
            $token = self::getSingle($id, "user_token", "ID");
        }
        return $token;
    }

    function getSingle($name, $tag="user_login", $ref="ID") {
        global $wpdb;
        return self::getOneField($wpdb->prefix."users", $name, $ref, $tag);
    }

    function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'sku', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."users", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    function getList($type="list") {
        global $wpdb;
        return self::query("SELECT `user_login`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name` FROM `".$wpdb->prefix."users`", false, $type);
    }

    function listOne($id, $ref="ID") {
        global $wpdb;
        return self::getOne($wpdb->prefix."users", $id, $ref);
    }

    public function initialize_table() {
        global $wpdb;
        //create database
        $query = "ALTER TABLE `".DB_NAME."`.`".$wpdb->prefix."users` ADD `user_token` VARCHAR(500) NOT NULL AFTER `user_pass`";
        $this->query($query);
    }

    public function clear_table() {
        global $wpdb;
        //clear database
        $query = "ALTER TABLE `".DB_NAME."`.`".$wpdb->prefix."users` DROP `user_token`";

        $this->query($query);
    }
}
?>