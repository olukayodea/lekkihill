<?php
class inventory_count extends inventory {
    public function create($array) {
        return self::insert(table_name_prefix."inventory_count", $array);
    }

    public function getCount($id) {
        $query = "SELECT SUM(`inventory_added`) FROM ".table_name_prefix."inventory_count WHERE `inventory_id` = :id";
        $prepare[':id'] = $id;

        return self::query($query,  $prepare, "getCol");
    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."inventory_count` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `inventory_id` INT NOT NULL, 
            `inventory_added` INT NOT NULL, 
            `inventory_before_added` INT NOT NULL, 
            `added_by` INT NOT NULL, 
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        self::query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."inventory_count`";

        self::query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."inventory_count`";

        self::query($query);
    }
}
?>