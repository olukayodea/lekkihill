<?php
class clinic_fluid_balance extends clinic {
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
            `report_date` varchar(500) NULL,
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