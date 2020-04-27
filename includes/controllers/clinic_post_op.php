<?php
class clinic_post_op extends clinic {
    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."clinic_post_op` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `patient_id` INT NOT NULL, 
            `surgery` varchar(500) NULL,
            `surgery_category` varchar(500) NULL,
            `indication` text NULL,
            `surgeon` INT NOT NULL, 
            `asst_surgeon` INT NOT NULL, 
            `per_op_nurse` INT NOT NULL, 
            `circulating_nurse` INT NOT NULL, 
            `anaesthesia` varchar(500) NULL,
            `anaesthesia_time` varchar(500) NULL,
            `knife_on_skin` varchar(500) NULL,
            `infiltration_time` varchar(500) NULL,
            `liposuction_time` varchar(500) NULL,
            `end_of_surgery` varchar(500) NULL,
            `procedure` text NULL,
            `amt_of_fat_right` varchar(500) NULL,
            `amt_of_fat_left` varchar(500) NULL,
            `amt_of_fat_other` varchar(500) NULL,
            `ebl` text NULL,
            `plan` text NULL,
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