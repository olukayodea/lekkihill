<?php
class billing_component extends billing {
    public function create($array) {
        $replace = array();
        
        $array['created_by'] = get_current_user_id();
        $array['last_modified_by'] = get_current_user_id();
        
        $replace[] = "title";
        $replace[] = "cost";
        $replace[] = "last_modified_by";
        $replace[] = "status";
        
        if ($array['ref'] == 0) {
            unset($array['ref']);
        }
        return self::replace(table_name_prefix."billing_component", $array, $replace);
    }

    public function getCount() {
        $query = "SELECT COUNT(`ref`) FROM ".table_name_prefix."billing_component WHERE `status` = 'ACTIVE'";
        return self::query($query, false, "getCol");
    }

    function getList($start=false, $limit=false, $order="title", $dir="ASC", $type="list") {
        return self::list(table_name_prefix."billing_component", $start, $limit, $order, $dir, "`status` != 'DELETED'", $type);
    }

    function getSingle($name, $tag="title", $ref="ref") {
        return self::getOneField(table_name_prefix."billing_component", $name, $ref, $tag);
    }

    function listOne($id) {
        return self::getOne(table_name_prefix."billing_component", $id, "ref");
    }

    function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'title', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."billing_component", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."billing_component` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `title` VARCHAR(50) NOT NULL,
            `cost` DOUBLE NOT NULL, 
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
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."billing_component`";

        self::query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."billing_component`";

        self::query($query);
    }
}
?>