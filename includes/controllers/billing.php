<?php
class billing extends database {
    protected $userData = array();
    public function search() {

    }

    public function component() {
        if (isset($_REQUEST['done'])) {
            $message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            $error_message = $_REQUEST['error'];
        }

        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            $data = billing_component::listOne($id);
            $tag = "Modify Component";
        } else {
            $tag = "Add Component";
        }

        if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            $add = billing_component::create($_POST);
            if ($add) {
                $message = "Component saved successfully";
                unset($data);
            } else {
                $error_message = "there was an error performing this action";
            }
        } else if (isset($_GET['changeStatus'])) {
            if ($_GET['changeStatus'] == "INACTIVE") {
                $tag = "ACTIVE";
                $msg = "activated";
            } else if ($_GET['changeStatus'] == "ACTIVE") {
                $tag = "INACTIVE";
                $msg = "deactivated";
            }

            $update = self::updateOne(table_name_prefix."billing_component", "status", $tag, $_GET['id']);
            if ($update) {
                $message = "Category ".$msg." successfully";
            } else {
                $error_message = "there was an error performing this action";
            }
        } else if (isset($_GET['remove'])) {
            $update = self::updateOne(table_name_prefix."billing_component", "status", "DELETED", $_GET['id']);
            if ($update) {
                $message = "Category deleted successfully";
            } else {
                $error_message = "there was an error performing this action";
            }
        }
        $list =  billing_component::getList();
        include_once(LH_PLUGIN_DIR."includes\pages\billings\component.php");
    }

    public function report() {

    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."billing` (
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
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."billing`";

        $this->query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."billing`";

        $this->query($query);
    }
}
//other inventory classes and functions
require_once LH_PLUGIN_DIR . 'includes/controllers/billing_component.php';
?>