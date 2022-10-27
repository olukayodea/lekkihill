<?php
class appointments extends database {
    public static $logged_in_user;
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

    public static $userToken;
    
    public static function manage() {
        self::$userToken = users::getToken( get_current_user_id(), FALSE );
        self::$logged_in_user = get_userdata( get_current_user_id() );
        if (isset($_REQUEST['new'])) {
            if (isset($_REQUEST['id'])) {
                $id = $_REQUEST['id'];
                $data = patient::listOne($id);
                self::$viewData['names'] = $data['last_name']." ".$data['first_name'];
                self::$viewData['phone'] = $data['phone_number'];
                self::$viewData['email'] = $data['email'];
                self::$viewData['patient_id'] = $data['ref'];

            }
        } else if (isset($_REQUEST['open'])) {
            if (isset($_REQUEST['id'])) {
                $id = $_REQUEST['id'];
                self::$viewData = self::listOne($id);
            }

            if (isset($_POST['submit'])) {
                $update['next_appointment'] = $_POST['next_appointment'];
                $update['status'] = $_POST['status'];
                $update['names'] = self::$viewData['names'];
                $update['id'] = $id;

                if (self::updateAppointment($update)) {
                    self::$message = "Appointments Updated Successfully";
                    wp_redirect( admin_url('admin.php?page=lh-manage-appointments&done='.self::$message) );
                } else {
                    self::$error_message = "there was an error performing this action";
                    wp_redirect( admin_url('admin.php?page=lh-manage-appointments&error='.self::$error_message) );
                }
            } else if (isset($_POST['cancel'])) {
                $update['status'] = "CANCELLED";
                $update['names'] = self::$viewData['names'];
                $update['id'] = $id;

                if (self::updateAppointment($update)) {
                    self::$message = "Appointments ".ucfirst(strtolower( $update['status'] ))." Successfully";
                    wp_redirect( admin_url('admin.php?page=lh-manage-appointments&done='.self::$message) );
                } else {
                    self::$error_message = "there was an error performing this action";
                    wp_redirect( admin_url('admin.php?page=lh-manage-appointments&error='.self::$error_message) );
                }
            }
        } else if (isset($_REQUEST['remove'])) {
            $remove = self::removeNew($_REQUEST['id']);
            if ($remove) {
                if ($remove === true)  {
                    self::$message = "Appointments removed Successfully";
                } else {
                    self::$error_message = "you can not delete this appointment again";
                }
            } else {
                self::$error_message = "there was an error performing this action";
            }
        } else if (isset($_POST['submit'])) {
            unset($_POST['randomTex']);
            unset($_POST['submit']);
            $add = self::create($_POST);

            if ($add) {
                $array['appointment_id'] = $add;
                $array['last_modify'] = get_current_user_id();
                $array['message'] = "Scheduled appointment with ".$_POST['names'];

                appointments_history::create($array);

                self::$message = "Appointments Setup Successfully";
            } else {
                self::$error_message = "there was an error performing this action";
            }
        } else {
            patient::getPatientList();
        }
        self::$list = self::getSortedList("NEW", "status");
        if (isset($_REQUEST['done'])) {
            self::$message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            self::$error_message = $_REQUEST['error'];
        }

		include_once(LH_PLUGIN_DIR."includes/pages/appointments/manage.php");
    }

    public static function manage_new_api($request) {
        self::$list = self::getSortedList("NEW", "status");
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
        self::$return['data'] = self::formatResult( self::$list );

        return self::$return;
    }

    public static function today() {
        $from = date("Y-m-d 00:00:00");
        $to = date("Y-m-d 23:59:59");
        self::$list = self::query("SELECT * FROM ".table_name_prefix."appointments WHERE `status` = 'SCHEDULED' AND `next_appointment` BETWEEN '".$from."' AND '".$to."' ORDER BY `next_appointment` ASC", false, "list");
    }

    public static function manage_today() {
        self::today();
		include_once(LH_PLUGIN_DIR."includes/pages/appointments/today.php");
    }

    public static function manage_today_api($request) {
        self::today();
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
        self::$return['data'] = self::formatResult( self::$list );

        return self::$return;
    }

    public static function upcoming() {
        $to = date("Y-m-d H:i:s", time());
        self::$list = self::query("SELECT * FROM ".table_name_prefix."appointments WHERE `status` = 'SCHEDULED' AND `next_appointment` > '".$to."' ORDER BY `next_appointment` ASC", false, "list");
    }

    public static function manage_upcoming() {
        self::upcoming();
		include_once(LH_PLUGIN_DIR."includes/pages/appointments/upcoming.php");
    }

    public static function manage_upcoming_api($request) {
        self::upcoming();
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
        self::$return['data'] = self::formatResult( self::$list );

        return self::$return;
    }

    private function past() {
        $to = date("Y-m-d H:i:s", time());
        self::$list = self::query("SELECT * FROM ".table_name_prefix."appointments WHERE `status` = 'SCHEDULED' AND `next_appointment` < '".$to."' ORDER BY `next_appointment` DESC", false, "list");
    }

    public static function manage_past() {
        self::past();
		include_once(LH_PLUGIN_DIR."includes/pages/appointments/past.php");
    }

    public static function manage_past_api($request) {
        self::past();
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
        self::$return['data'] = self::formatResult( self::$list );

        return self::$return;
    }

    public static function createAPI( WP_REST_Request $request) {
        $parameters = $request->get_params();
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $add = self::create($parameters);

        if ($add) {
            $array['appointment_id'] = $add;
            $array['last_modify'] = get_current_user_id();
            $array['message'] = "Created appointment for ".$parameters['names'];

            appointments_history::create($array);
            self::$return = self::$successResponse;
        } else {
            self::$return = self::$internalServerError;
        }
        return self::$return;
    }

    public static function updateAppointmentAPI($request) {
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
        $add = self::updateAppointment($parameters);
        if ($add) {
            self::$return = self::$successResponse;
        } else {
            self::$return = self::$internalServerError;
        }
        return self::$return;
    }

    public static function removeNewAPI($request) {
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
        $id = $request['appointment_id'];
        if (intval($id) > 0) {
            $remove = self::createRandomPassword($id);
            if ($remove) {
                if ($remove === true)  {
                    self::$return = self::$successResponse;
                } else {
                    self::$NotAcceptable['additional_message'] = "you can not delete this appointment again";
                    self::$return = self::$NotAcceptable;
                }
            } else {
                self::$return = self::$internalServerError;
            }
        } else {
            self::$BadReques['additional_message'] = "ID invalid";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    private static function create($array) {
        if (isset($array['next_appointment_date'])) {
            $array['next_appointment'] = $array['next_appointment_date'];
            unset($array['next_appointment_date']);
        } else {
            $array['next_appointment'] = $array['appointment_date']." ".$array['appointment_time'];
            unset($array['appointment_date']);
            unset($array['appointment_time']);
        }
        $return = self::insert(table_name_prefix."appointments", $array);
        $checkExist = patient::listOne($array['email'], "email");

        if ($checkExist) {
            self::modifyOne( "patient_id", $checkExist['ref'], $return );
        }
        return $return;
    }

    private static function removeNew($id) {
        $data = self::listOne($id);

        if ($data['status'] == "NEW") {
            if (self::delete(table_name_prefix."appointments", $id)) {
                return true;
            } else {
                return false;
            }
        } else {
            return "NONE";
        }
    }
    
    private static function updateAppointment($data) {
        $update['next_appointment'] = $data['next_appointment'];
        $update['status'] = $data['status'];
        $where['ref'] = $data['id'];
        if (self::edit($update, $where)) {
            $array['appointment_id'] = $data['id'];
            $array['last_modify'] = get_current_user_id();
            $array['message'] = ucfirst(strtolower($data['status']))." appointment with ".$data['names'];

            appointments_history::create($array);
            return true;
        } else {
            return false;
        }
    }

    private static function edit($array, $where) {
        return self::update(table_name_prefix."appointments", $array, $where);
    }

    public static function modifyOne($tag, $value, $id, $ref="ref") {
        return self::updateOne(table_name_prefix."appointments", $tag, $value, $id, $ref);
    }
    
    public static function getList($start=false, $limit=false, $order="names", $dir="ASC", $type="list") {
        return self::lists(table_name_prefix."appointments", $start, $limit, $order, $dir, "`status` != 'DELETED'", $type);
    }

    public static function getSingle($name, $tag="names", $ref="ref") {
        return self::getOneField(table_name_prefix."appointments", $name, $ref, $tag);
    }

    public static function listOne($id, $ref="ref") {
        return self::getOne(table_name_prefix."appointments", $id, $ref);
    }

    public static function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'ref', $dir = "DESC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."appointments", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    public static function formatResult($data, $single=false) {
        if ($single == false) {
            for ($i = 0; $i < count($data); $i++) {
                $data[$i] = self::clean($data[$i]);
            }
        } else {
            $data = self::clean($data);
        }
        return $data;
    }

    public static function clean($data) {
        $user['id'] = $data['last_modify'];
        $user['user_login'] = users::getSingle( $data['last_modify'] );
        $user['user_nicename'] = users::getSingle( $data['last_modify'], "user_nicename" );
        $user['user_email'] = users::getSingle( $data['last_modify'], "user_email" );
        $data['last_modify'] = $user;

        $patient_id['id'] = $data['patient_id'];
        $patient_id['first_name'] = patient::getSingle( $data['patient_id'], "first_name" );
        $patient_id['last_name'] = patient::getSingle( $data['patient_id'], "last_name" );
        $patient_id['email'] = patient::getSingle( $data['patient_id'], "email" );
        $patient_id['phone_number'] = patient::getSingle( $data['patient_id'], "phone_number" );
        $patient_id['age'] = patient::getSingle( $data['patient_id'], "age" );
        $patient_id['sex'] = patient::getSingle( $data['patient_id'], "sex" );
        $data['patient_id'] = $patient_id;
        
        return $data;
    }
    
    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."appointments` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `names` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `phone` VARCHAR(20) NOT NULL,
            `location` VARCHAR(20) NOT NULL,
            `procedure` VARCHAR(50) NOT NULL,
            `message` VARCHAR(1000) NOT NULL,
            `next_appointment` datetime NOT NULL,
            `status` varchar(20) NOT NULL DEFAULT 'NEW',
            `patient_id` INT NOT NULL, 
            `last_modify` INT NOT NULL, 
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."appointments`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."appointments`";

        $this->query($query);
    }
}
?>