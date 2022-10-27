<?php
class invoiceLog extends database {
    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."invoice_log` (
            `ref` INT NOT NULL AUTO_INCREMENT,
            `invoice_id` INT NOT NULL, 
            `amount` VARCHAR(6) NOT NULL,
            `create_by` INT NOT NULL,
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."invoice_log`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."invoice_log`";

        $this->query($query);
    }
}
?>