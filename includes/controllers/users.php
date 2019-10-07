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
            $return = $user;
            $return->token = self::getToken($user->ID);

            $return->permission = $capabilityArray[$roles];
        }

        return $return;
    }

    public function getDetails($request) {
        $headers = $request->get_headers();
        $get_params = $request->get_params();
        //$auth = self::authenticate();

        return $headers;
    }

    private function getToken ($id) {
        global $wpdb;
        $token = self::createRandomPassword(5).$id.self::createRandomPassword(5);
        // $wpdb->update(
        //     $wpdb->prefix."users",
        //     array("user_token" => $token),
        //     array("ID" => $id)
        // );
        return $token;
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