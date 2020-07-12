<?php
class clinic_fluid_balance extends clinic {
    static function create($array) {
        return self::insert(table_name_prefix."clinic_fluid_balance", $array);
    }

    static function modifyOne($tag, $value, $id, $ref="ref") {
        return self::updateOne(table_name_prefix."clinic_fluid_balance", $tag, $value, $id, $ref);
    }
    
    static function getList($start=false, $limit=false, $order="ref", $dir="DESC", $type="list") {
        return self::lists(table_name_prefix."clinic_fluid_balance", $start, $limit, $order, $dir, false, $type);
    }

    static function getSingle($name, $tag="patient_id", $ref="ref") {
        return self::getOneField(table_name_prefix."clinic_fluid_balance", $name, $ref, $tag);
    }

    static function listOne($id) {
        return self::getOne(table_name_prefix."clinic_fluid_balance", $id, "ref");
    }

    static function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'ref', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."clinic_fluid_balance", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
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
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."clinic_fluid_balance` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `patient_id` INT NOT NULL, 
            `iv_fluid` varchar(500) NULL,
            `amount` DOUBLE NOT NULL, 
            `oral_fluid` DOUBLE NOT NULL, 
            `ng_tube_feeding` DOUBLE NOT NULL, 
            `vomit` DOUBLE NOT NULL, 
            `urine` DOUBLE NOT NULL, 
            `drains` DOUBLE NOT NULL, 
            `ng_tube_drainage` DOUBLE NOT NULL, 
            `report_date` varchar(20) NULL,
            `report_time` varchar(10) NULL,
            `added_by` INT NOT NULL, 
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."clinic_fluid_balance`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."clinic_fluid_balance`";

        $this->query($query);
    }
}
?>