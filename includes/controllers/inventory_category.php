<?php
class inventory_category extends inventory {
    public static function create($array) {
        $replace = array();
        
        $replace[] = "title";
        $replace[] = "last_modified_by";
        $replace[] = "status";
        
        if ($array['ref'] == 0) {
            unset($array['ref']);
        }
        return self::replace(table_name_prefix."inventory_category", $array, $replace);
    }

    public static function getCount() {
        $query = "SELECT COUNT(`ref`) FROM ".table_name_prefix."inventory_category WHERE `status` = 'ACTIVE'";
        return self::query($query, false, "getCol");
    }

    public static function getList($start=false, $limit=false, $order="title", $dir="ASC", $type="list") {
        return self::lists(table_name_prefix."inventory_category", $start, $limit, $order, $dir, "`status` != 'DELETED'", $type);
    }

    public static function getSingle($name, $tag="title", $ref="ref") {
        return self::getOneField(table_name_prefix."inventory_category", $name, $ref, $tag);
    }

    public static function listOne($id) {
        return self::getOne(table_name_prefix."inventory_category", $id, "ref");
    }

    public static function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'title', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."inventory_category", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."inventory_category` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `title` VARCHAR(50) NOT NULL,
            `status` varchar(20) NOT NULL DEFAULT 'ACTIVE',
            `created_by` INT NOT NULL, 
            `last_modified_by` INT NOT NULL, 
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        self::query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."inventory_category`";

        self::query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."inventory_category`";

        self::query($query);
    }
}
?>