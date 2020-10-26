<?php
class clinic_doctors_report extends clinic {
    static function create($array) {
        $array['recommended'] = serialize($array['recommended']);
        return self::insert(table_name_prefix."clinic_doctors_report", $array);
    }

    static function modifyOne($tag, $value, $id, $ref="ref") {
        return self::updateOne(table_name_prefix."clinic_doctors_report", $tag, $value, $id, $ref);
    }
    
    static function getList($start=false, $limit=false, $order="ref", $dir="DESC", $type="list") {
        return self::lists(table_name_prefix."clinic_doctors_report", $start, $limit, $order, $dir, false, $type);
    }

    static function getSingle($name, $tag="patient_id", $ref="ref") {
        return self::getOneField(table_name_prefix."clinic_doctors_report", $name, $ref, $tag);
    }

    static function listOne($id) {
        return self::getOne(table_name_prefix."clinic_doctors_report", $id, "ref");
    }

    static function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'ref', $dir = "DESC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."clinic_doctors_report", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
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
        $data['recommended'] = unserialize($data['recommended']);
        
        return $data;
    }
    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."clinic_doctors_report` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `patient_id` INT NOT NULL, 
            `report` TEXT NOT NULL,
            `recommended` TEXT NULL, 
            `added_by` INT NOT NULL, 
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."clinic_doctors_report`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."clinic_doctors_report`";

        $this->query($query);
    }
}
?>