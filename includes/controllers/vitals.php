<?php
class vitals extends database {
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

    function create($array) {
        return self::insert(table_name_prefix."vitals", $array);
    }


    function modifyOne($tag, $value, $id, $ref="ref") {
        return self::updateOne(table_name_prefix."vitals", $tag, $value, $id, $ref);
    }
    
    function getList($start=false, $limit=false, $order="ref", $dir="DESC", $type="list") {
        return self::lists(table_name_prefix."vitals", $start, $limit, $order, $dir, false, $type);
    }

    function getSingle($name, $tag="patient_id", $ref="ref") {
        return self::getOneField(table_name_prefix."vitals", $name, $ref, $tag);
    }

    function listOne($id) {
        return self::getOne(table_name_prefix."vitals", $id, "ref");
    }

    function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'ref', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."vitals", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    public function formatResult($data, $single=false) {
        if ($single == false) {
            for ($i = 0; $i < count($data); $i++) {
                $data[$i] = self::clean($data[$i]);
            }
        } else {
            $data = self::clean($data);
        }
        return $data;
    }

    public function clean($data) {
        $added_by['id'] = $data['added_by'];
        $added_by['user_login'] = users::getSingle( $data['added_by'] );
        $added_by['user_nicename'] = users::getSingle( $data['added_by'], "user_nicename" );
        $added_by['user_email'] = users::getSingle( $data['added_by'], "user_email" );
        $data['added_by'] = $added_by;

        $patient_id['id'] = $data['patient_id'];
        $patient_id['first_name'] = patient::getSingle( $data['patient_id'], "first_name" );
        $patient_id['last_name'] = patient::getSingle( $data['patient_id'], "last_name" );
        $patient_id['email'] = patient::getSingle( $data['patient_id'], "email" );
        $patient_id['phone_number'] = patient::getSingle( $data['patient_id'], "phone_number" );
        $data['patient_id'] = $patient_id;
        
        return $data;
    }
    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."vitals` (
            `ref` INT NOT NULL AUTO_INCREMENT,
            `patient_id` INT NOT NULL,
            `weight` DOUBLE NOT NULL,
            `height` DOUBLE NOT NULL,
            `bmi` DOUBLE NOT NULL,
            `spo2` VARCHAR(15) NOT NULL,
            `respiratory` DOUBLE NOT NULL,
            `temprature` DOUBLE NOT NULL,
            `pulse` DOUBLE NOT NULL,
            `bp_sys` INT NOT NULL, 
            `bp_dia` INT NOT NULL, 
            `added_by` INT NOT NULL, 
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."vitals`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."vitals`";

        $this->query($query);
    }
}
?>