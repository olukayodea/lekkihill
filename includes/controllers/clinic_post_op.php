<?php
class clinic_post_op extends clinic {
    static function create($array) {
        return self::insert(table_name_prefix."clinic_post_op", $array);
    }


    static function modifyOne($tag, $value, $id, $ref="ref") {
        return self::updateOne(table_name_prefix."clinic_post_op", $tag, $value, $id, $ref);
    }
    
    static function getList($start=false, $limit=false, $order="ref", $dir="DESC", $type="list") {
        return self::lists(table_name_prefix."clinic_post_op", $start, $limit, $order, $dir, false, $type);
    }

    static function getSingle($name, $tag="patient_id", $ref="ref") {
        return self::getOneField(table_name_prefix."clinic_post_op", $name, $ref, $tag);
    }

    static function listOne($id) {
        return self::getOne(table_name_prefix."clinic_post_op", $id, "ref");
    }

    static function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'ref', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."clinic_post_op", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
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
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."clinic_post_op` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `patient_id` INT NOT NULL, 
            `surgery` varchar(500) NULL,
            `surgery_category` varchar(500) NULL,
            `indication` text NULL,
            `surgeon` varchar(500) NOT NULL, 
            `asst_surgeon` varchar(500) NOT NULL, 
            `per_op_nurse` varchar(500) NOT NULL, 
            `circulating_nurse` varchar(500) NOT NULL, 
            `anaesthesia` varchar(500) NULL,
            `anaesthesia_time` datetime NULL,
            `knife_on_skin` datetime NULL,
            `infiltration_time` datetime NULL,
            `liposuction_time` datetime NULL,
            `end_of_surgery` datetime NULL,
            `procedure` text NULL,
            `amt_of_fat_right` varchar(500) NULL,
            `amt_of_fat_left` varchar(500) NULL,
            `amt_of_fat_other` varchar(500) NULL,
            `ebl` text NULL,
            `plan` text NULL,
            `added_by` INT NOT NULL, 
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."clinic_post_op`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."clinic_post_op`";

        $this->query($query);
    }
}
?>