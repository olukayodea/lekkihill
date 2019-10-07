<?php
class appointments extends database {
    public function manage() {
        if (isset($_REQUEST['open'])) {
            if (isset($_REQUEST['id'])) {
                $id = $_REQUEST['id'];
                $data = self::listOne($id);
            }
        } else if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            $add = self::create($_POST);

            if ($add) {
                $array['appointment_id'] = $add;
                $array['last_modify'] = get_current_user_id();
                $array['message'] = "Scheduled appointment with ".$_POST['names'];

                appointments_history::create($array);

                $message = "Appointments Setup Successfully";
            } else {
                $error_message = "there was an error performing this action";
            }
        }
        $list = self::getSortedList("NEW", "status");
		include_once(LH_PLUGIN_DIR."includes\pages\appointments\manage.php");
    }

    private function create($array) {
        return self::insert(table_name_prefix."appointments", $array);
    }
    
    function getList($start=false, $limit=false, $order="names", $dir="ASC", $type="list") {
        return self::list(table_name_prefix."appointments", $start, $limit, $order, $dir, "`status` != 'DELETED'", $type);
    }

    function getSingle($name, $tag="names", $ref="ref") {
        return self::getOneField(table_name_prefix."appointments", $name, $ref, $tag);
    }

    function listOne($id) {
        return self::getOne(table_name_prefix."appointments", $id, "ref");
    }

    function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'ref', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."appointments", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."appointments` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `names` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `phone` VARCHAR(20) NOT NULL,
            `procedure` VARCHAR(50) NOT NULL,
            `message` VARCHAR(1000) NOT NULL,
            `next_appointment` datetime NOT NULL,
            `status` varchar(20) NOT NULL DEFAULT 'NEW',
            `last_modify` INT NOT NULL, 
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $this->query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."appointments`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."appointments`";

        $this->query($query);
    }
}
?>