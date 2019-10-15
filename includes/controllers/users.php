<?php
class users extends database {
    public function login($request) {
        global $capabilityArray;
        $parameters = $request->get_params();

        $creds['user_login'] = $parameters["username"];
        $creds['user_password'] =  $parameters["password"];
        $creds['remember'] = true;
        $user = wp_signon( $creds, false );

        if ( is_wp_error($user) ) {
            $return['status'] = "404";
            $return['message'] = "Not Found";
            $return['additional_message'] = $user->get_error_message();
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
            $return['status'] = "200";
            $return['message'] = "OK";
            $return['ID'] = $data->ID;
            $return['data'] = $data->data;
            $return['roles'] = $data->roles;
        }

        return $return;
    }

    public function getDetails($request) {
        global $capabilityArray;
        $headers = $request->get_headers();
        $get_params = $request->get_query_params();
        $id = self::authenticate($headers);

        if ($id['status'] == "401") {
            return $id;
        } else {
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
            $data->token = self::getToken($auth->ID);

            $data->permission = $capabilityArray[$roles];
            $return['status'] = "200";
            $return['message'] = "OK";
            $return['ID'] = $data->ID;
            $return['data'] = $data->data;
            $return['roles'] = $data->roles;

            return  $return;
        }
    }

    private function getToken ($id) {
        global $wpdb;
        $token = self::createRandomPassword(5).$id.self::createRandomPassword(5);
        $wpdb->update(
            $wpdb->prefix."users",
            array("user_token" => $token),
            array("ID" => $id)
        );
        return $token;
    }

    function getSingle($name, $tag="user_login", $ref="ID") {
        global $wpdb;
        return self::getOneField($wpdb->prefix."users", $name, $ref, $tag);
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