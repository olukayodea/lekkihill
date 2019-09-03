<?php
class inventory extends database {
        public function initialize_table() {
            //create database
            $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."inventory` (
                `ref` INT NOT NULL AUTO_INCREMENT, 
                `parent_id` INT NOT NULL, 
                `category_title` VARCHAR(50) NOT NULL,
                `status` varchar(20) NOT NULL DEFAULT 'INACTIVE',
                `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`ref`)
            ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

            $this->query($query);
        }

        public function clear_table() {
            //clear database
            $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."inventory`";

            $this->query($query);
        }

        public function delete_table() {
            //clear database
            $query = "DROP TABLE `".DB_NAME."`.`".table_name_prefix."inventory`";

            $this->query($query);
        }
}
?>