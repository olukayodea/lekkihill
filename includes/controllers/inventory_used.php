<?php
class inventory_used extends inventory {
    public static function getCount($id) {
        $query = "SELECT SUM(`inventory_used`) FROM ".table_name_prefix."inventory_used WHERE `inventory_id` = :id";
        $prepare[':id'] = $id;

        return self::query($query,  $prepare, "getCol");
    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."inventory_used` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `inventory_id` INT NOT NULL, 
            `inventory_used` INT NOT NULL, 
            `doctor_id` INT NOT NULL, 
            `patient_id` INT NOT NULL, 
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."inventory_used`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."inventory_used`";

        $this->query($query);
    }
}
?>