<?php
class inventory extends database {
    public function manage() {
        if (isset($_REQUEST['done'])) {
            $message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            $error_message = $_REQUEST['error'];
        }

        if (isset($_GET['changeStatus'])) {
            if ($_GET['changeStatus'] == "INACTIVE") {
                $tag = "ACTIVE";
                $msg = "activated";
            } else if ($_GET['changeStatus'] == "ACTIVE") {
                $tag = "INACTIVE";
                $msg = "deactivated";
            }

            $update = self::updateOne(table_name_prefix."inventory", "status", $tag, $_GET['id']);
            if ($update) {
                $message = "Inventory item ".$msg." successfully";
                self::returnUrl("done", $message);
            } else {
                $error_message = "there was an error performing this action";
                self::returnUrl("error", $error_message);
            }
        } else if (isset($_GET['remove'])) {
            $update = self::updateOne(table_name_prefix."inventory", "status", "DELETED", $_GET['id']);
            if ($update) {
                self::delete(table_name_prefix."inventory_count", $_GET['id'], "inventory_id");
                $message = "Inventory item deleted successfully";
                self::returnUrl("done", $message);
            } else {
                $error_message = "there was an error performing this action";
                self::returnUrl("error", $error_message);
            }
        }

        if (isset($_POST['save'])) {
            unset($_POST['save']);
            unset($_POST['return']);
            $add = self::create($_POST);
            if ($add) {
                $message = "Inventory item modified successfully";
                self::returnUrl("done", $message);
            } else {
                $error_message = "there was an error performing this action";
                self::returnUrl("error", $error_message);
            }
        }
        $list =  self::getList();
		include_once(LH_PLUGIN_DIR."includes/pages/inventory/list.php");
    }

    public function add() {
        if (isset($_REQUEST['done'])) {
            $message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            $error_message = $_REQUEST['error'];
        }

        $list =  inventory_category::getSortedList("ACTIVE", "status");
        if (inventory_category::getCount()) {
            $url = "";
            $tag = "Add to Inventory";
            if (isset($_REQUEST['id'])) {
                $id = $_REQUEST['id'];
                $data = self::listOne($id);   
                $url = admin_url('admin.php?page=lh-inventory');
                $tag = "Modify Inventory Item";
            }

            if (isset($_POST['save'])) {
                unset($_POST['save']);
                unset($_POST['return']);
                $add = self::create($_POST);
                if ($add) {
                    $array['inventory_id'] = $add;
                    $array['inventory_added'] = $_POST['inventory_added'];
                    $array['inventory_before_added'] = 0;
                    $array['added_by'] = get_current_user_id();
                    inventory_count::create($array);
                    $message = "Inventory item saved successfully";
                    self::returnUrl("done", $message);
                } else {
                    $error_message = "there was an error performing this action";
                    self::returnUrl("error", $error_message);
                }
            }

            include_once(LH_PLUGIN_DIR."includes/pages/inventory/add.php");
        } else {
            echo '<div class="wrap">';
            echo '<h2>Add Inventory Item</h2>';
            echo '<div class="error"><p>You must create at least one category first. <a href="'.admin_url('cclh-category-inventory').'">click here</a> to create</a></p></div>';
            echo '</div>';
        }
    }

    public function manageStock() {
        if (isset($_REQUEST['done'])) {
            $message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            $error_message = $_REQUEST['error'];
        }

        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            $data = self::listOne($id);
            $data['quantity'] =  self::getBalance( $id );
            if (isset($_REQUEST['add'])) {
                $tag = "add";
                $label = 'min="1"';
            } else if (isset($_REQUEST['remove'])) {
                $tag = "remove";
                $label = 'max="'.$data['quantity'].'"';
            } else {
                $error = true;
                $error_message = 'there was an error while trying to complete this request. This request will not be completed. <a href="'.admin_url('admin.php?page=lh-inventory').'">click here</a> to go back</a>';
            }

            if (isset($_POST['submit'])) {
                unset($_POST['submit']);
                unset($_POST['return']);
                if ($_POST['dir'] != "add") {
                    $_POST['inventory_added'] = 0-$_POST['inventory_added'];
                }
                unset($_POST['dir']);
                $_POST['added_by'] = get_current_user_id();
                $add = inventory_count::create($_POST);
                if ($add) {
                    $message = "Inventory item updated successfully";
                    self::returnUrl("done", $message);
                } else {
                    $error_message = "there was an error performing this action";
                    self::returnUrl("error", $error_message);
                }
            }
            
            include_once(LH_PLUGIN_DIR."includes/pages/inventory/manageStock.php");
        } else {
            echo '<div class="wrap">';
            echo '<h2>Manage Inventory Item</h2>';
            echo '<div class="error"><p>There was an error processing this request. <a href="'.admin_url('admin.php?page=lh-inventory').'">click here</a> to go back</a></p></div>';
            echo '</div>';
        }
    }

    public function categories() {
        if (isset($_REQUEST['done'])) {
            $message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            $error_message = $_REQUEST['error'];
        }

        if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            $add = inventory_category::create($_POST);
            if ($add) {
                $message = "Category saved successfully";
                self::returnUrl("done", $message);
            } else {
                $error_message = "there was an error performing this action";
                self::returnUrl("error", $error_message);
            }
        }
        $list =  inventory_category::getList();
        include_once(LH_PLUGIN_DIR."includes/pages/inventory/categories.php");
    }

    public function view() {
        if (isset($_REQUEST['done'])) {
            $message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            $error_message = $_REQUEST['error'];
        }

        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            $data = self::listOne($id);
            $data['quantity'] =  self::getBalance( $id );
            
            $list = inventory_count::getSortedList($id, "inventory_id");
            include_once(LH_PLUGIN_DIR."includes/pages/inventory/view.php");
        } else {
            echo '<div class="wrap">';
            echo '<h2>Manage Inventory Item</h2>';
            echo '<div class="error"><p>There was an error processing this request. <a href="'.admin_url('admin.php?page=lh-inventory').'">click here</a> to go back</a></p></div>';
            echo '</div>';
        }
    }

    public function report() {
        $show = false;
        if (isset($_POST['save'])) {
            $result = self::processReport($_POST);
            if ($_POST['view'] == "search") {
                $msg = " for search query <strong>".$_POST['search']."</strong>";
            } else if ($_POST['view'] == "category") {
                $msg = " for category <strong>".inventory_category::getSingle($_POST['category'])."</strong>";
            } else if ($_POST['view'] == "user") {
                $msg = " added by <strong>".self::getuser($_POST['user'])."</strong>";
            }
            $tag = "Showing ".count($result)." record(s)".$msg." between ".$_POST['from']." to ".$_POST['to'];
            $show = true;
        }
        $users = get_users( [ 'role__in' => [ 'lekki_hill_inventory', 'lekki_hill_admin', 'administrator' ] ] );
        $list =  inventory_category::getList();
        include_once(LH_PLUGIN_DIR."includes/pages/inventory/report.php");
    }

    private function processReport( $array ) {
        if ($array['view'] == "search") {
            $tag = " AND (`wp_lekkihill_inventory`.`title` LIKE '%".$array['search']."%' OR `wp_lekkihill_inventory`.`sku` LIKE '%".$array['search']."%')";
        } else if ($array['view'] == "category") {
            $tag = " AND `wp_lekkihill_inventory`.`category_id` = ".$array['category'];
        } else if ($array['view'] == "user") {
            $tag = " AND `wp_lekkihill_inventory_count`.`added_by` = ".$array['user'];
        }

        echo $sql = "SELECT `wp_lekkihill_inventory`.`title`, `wp_lekkihill_inventory`.`sku`, `wp_lekkihill_inventory`.`category_id`, `wp_lekkihill_inventory_count`.`inventory_added`, `wp_lekkihill_inventory_count`.`inventory_before_added`, `wp_lekkihill_inventory_count`.`added_by`, `wp_lekkihill_inventory_count`.`create_time` FROM `wp_lekkihill_inventory`, `wp_lekkihill_inventory_count` WHERE `wp_lekkihill_inventory`.`ref` = `wp_lekkihill_inventory_count`.`inventory_id` AND DATE(`wp_lekkihill_inventory_count`.`create_time`) BETWEEN '".$array['from']." 00:00:00' AND '".$array['to']." 23:59:59'".$tag." ORDER BY `wp_lekkihill_inventory_count`.`create_time` DESC";
        return self::query($sql, false, "list");
    }

    private function create( $array ) {
        $replace = array();
        $array['sku'] = self::confirmSKU(self::createSKU($array['category_id']), $array['category_id']);;
        $array['created_by'] = get_current_user_id();
        $array['last_modified_by'] = get_current_user_id();
        
        unset($array['inventory_added']);
        if ($array['ref'] == 0) {
            unset($array['ref']);
        }

        $replace[] = "title";
        $replace[] = "category_id";
        $replace[] = "status";
        $replace[] = "last_modified_by";
        return self::replace(table_name_prefix."inventory", $array, $replace);
    }

    private function createSKU($id) {
        return strtoupper(substr(inventory_category::getSingle($id), 0, 3).rand(100000, 999999));
    }

    private function confirmSKU( $key, $id ) {
        if (self::checkExixst(table_name_prefix."inventory", "sku", $key, "sku") == 0) {
            return $key;
        } else {
            return self::confirmSKU(self::createSKU($id), $id);
        }

    }

    public function getBalance($id) {
        return intval(inventory_count::getCount($id)-inventory_used::getCount($id));
    }

    function getList($start=false, $limit=false, $order="title", $dir="ASC", $type="list") {
        return self::list(table_name_prefix."inventory", $start, $limit, $order, $dir, "`status` != 'DELETED'", $type);
    }

    function getSingle($name, $tag="title", $ref="ref") {
        return self::getOneField(table_name_prefix."inventory", $name, $ref, $tag);
    }

    function listOne($id) {
        return self::getOne(table_name_prefix."inventory", $id, "ref");
    }

    function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'sku', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."inventory", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."inventory` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `title` VARCHAR(255) NOT NULL,
            `sku` VARCHAR(50) NOT NULL,
            `category_id` INT NOT NULL, 
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
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."inventory`";

        self::query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."inventory`";

        self::query($query);
    }
}


//other inventory classes and functions
require_once LH_PLUGIN_DIR . 'includes/controllers/inventory_used.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/inventory_count.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/inventory_category.php';
$inventory_used = new inventory_used;
$inventory_count = new inventory_count;
$inventory_category = new inventory_category;
?>