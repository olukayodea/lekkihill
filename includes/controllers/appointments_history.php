<?php
class appointments_history extends appointments {
    public function create($array) {
        return self::insert(table_name_prefix."appointments_history", $array);
    }

    function getList($start=false, $limit=false, $order="appointment_id", $dir="ASC", $type="list") {
        return self::lists(table_name_prefix."appointments_history", $start, $limit, $order, $dir, "`status` != 'DELETED'", $type);
    }

    function getSingle($name, $tag="appointment_id", $ref="ref") {
        return self::getOneField(table_name_prefix."appointments_history", $name, $ref, $tag);
    }

    function listOne($id) {
        return self::getOne(table_name_prefix."appointments_history", $id, "ref");
    }

    function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'sku', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."appointments_history", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."appointments_history` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `appointment_id` INT NOT NULL, 
            `user_id` INT NOT NULL, 
            `message` VARCHAR(1000) NOT NULL,
            `last_modify` INT NOT NULL, 
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."appointments_history`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."appointments_history`";

        $this->query($query);
    }
}
?>