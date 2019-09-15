<?php
class inventory extends database {
    public function manage() {
        if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            if ($_POST['dir'] != "add") {
                $_POST['inventory_added'] = 0-$_POST['inventory_added'];
            }
            unset($_POST['dir']);
            $_POST['added_by'] = get_current_user_id();
            $add = inventory_count::create($_POST);
            if ($add) {
                $message = "Inventory item updated successfully";
            } else {
                $error_message = "there was an error performing this action";
            }
        } else if (isset($_POST['save'])) {
            unset($_POST['save']);
            $add = self::create($_POST);
            if ($add) {
                $message = "Inventory item modified successfully";
            } else {
                $error_message = "there was an error performing this action";
            }
        }
        $list =  self::getList();
		include_once(LH_PLUGIN_DIR."includes/pages/inventory/list.php");
    }

    public function add() {
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
                $add = self::create($_POST);
                if ($add) {
                    $array['inventory_id'] = $add;
                    $array['inventory_added'] = $_POST['inventory_added'];
                    $array['inventory_before_added'] = 0;
                    $array['added_by'] = get_current_user_id();
                    inventory_count::create($array);
                    $message = "Inventory item saved successfully";
                } else {
                    $error_message = "there was an error performing this action";
                }
            }
            include_once(LH_PLUGIN_DIR."includes/pages/inventory/add.php");
        } else {
            echo '<div class="wrap">';
            echo '<h2>Add Inventory Item</h2>';
            echo '<div class="error"><p>You must create at least one category first. <a href="'.admin_url('admin.php?page=lh-category-inventory').'">click here</a> to create</a></p></div>';
            echo '</div>';
        }
    }

    public function manageStock() {
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
            
            include_once(LH_PLUGIN_DIR."includes/pages/inventory/manageStock.php");
        } else {
            echo '<div class="wrap">';
            echo '<h2>Manage Inventory Item</h2>';
            echo '<div class="error"><p>There was an error processing this request. <a href="'.admin_url('admin.php?page=lh-inventory').'">click here</a> to go back</a></p></div>';
            echo '</div>';
        }
    }

    public function categories() {
        if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            $add = inventory_category::create($_POST);
            if ($add) {
                $message = "Category saved successfully";
            } else {
                $error_message = "there was an error performing this action";
            }
        }
        $list =  inventory_category::getList();
        include_once(LH_PLUGIN_DIR."includes/pages/inventory/categories.php");
    }

    public function report() {
    }

    private function create($array) {
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

    private function confirmSKU($key, $id) {
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