<?php
class inventory extends database {
    public static $return = array();
    public static $userData = array();
    public static $successResponse = array("status" => "200", "message" => "OK");
    public static $notFound = array("status" => "404", "message" => "Not Found");
    public static $Unauthorized = array("status" => "401", "message" => "Unauthorized");
    public static $BadReques = array("status" => "400", "message" => "Bad Reques");
    public static $internalServerError = array("status" => "500", "message" => "Internal Server Error");

    public function apiCreateCategory($request) {
        /**
         * API authentication
         */
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $parameters = $request->get_params();
        $parameters['created_by'] = self::$userData['ID'];
        $parameters['last_modified_by'] = self::$userData['ID'];
        $add = inventory_category::create($parameters);
        if ($add) {
            self::$return = self::$successResponse;
            self::$return['ID'] = $add;
        } else {self::$BadReques['additional_message'] = "there was an error performing this action";
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    public function apiDeleteCategory($request) {
        /**
         * API authentication
         */
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $id = $request['category_id'];
        if (intval($id) > 0) {
            $update = self::updateOne(table_name_prefix."inventory_category", "status", "DELETED", $id);
            if ($update) {
                self::$successResponse['additional_message'] = "Category deleted successfully";
                self::$return = self::$successResponse;
            } else {
                self::$internalServerError['additional_message'] = "there was an error performing this action";
                self::$return = self::$internalServerError;
            }
        } else {
            self::$BadReques['additional_message'] = "ID invalid";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public function apiCreateInventory($request) {
        /**
         * API authentication
         */
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $parameters = $request->get_params();
        $parameters['created_by'] = self::$userData['ID'];
        $parameters['last_modified_by'] = self::$userData['ID'];
        $add = self::create($_POST);
        if ($add) {
            self::$return = self::$successResponse;
            self::$return['ID'] = $add;
        } else {self::$BadReques['additional_message'] = "there was an error performing this action";
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    public function apiSearch($request) {
        /**
         * API authentication
         */
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $parameters = $request->get_params();

        if ((!isset($parameters['from']) || (strtotime($parameters['from']) > time()))) {
            self::$BadReques['additional_message'] = "'from' field is missing or  contains a time in the future";
            self::$return = self::$BadReques;
        } else if (($parameters['view'] == "search") && (!isset($parameters['search']) || ($parameters['search'] == ""))) {
            self::$BadReques['additional_message'] = "'search' field is missing or empty";
            self::$return = self::$BadReques;
        } else if (($parameters['view'] == "category") && (!isset($parameters['category']) || (intval($parameters['category']) < 1))) {
            self::$BadReques['additional_message'] = "'category' field is missing or not a number";
            self::$return = self::$BadReques;
        } else if (($parameters['view'] == "user") && (!isset($parameters['user']) || (intval($parameters['user']) < 1))) {
            self::$BadReques['additional_message'] = "'user' field is missing or not a number";
            self::$return = self::$BadReques;
        } else {
            if (!isset($parameters['to'])) {
                $parameters['to'] = date("Y-m-d", time());
            }
            self::$return = self::$successResponse;
            self::$return['data'] = self::processReport($parameters);
        }

        return self::$return;
    }

    public function apiGetOne($request) {
        /**
         * API authentication
         */
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $id = $request['category_id'];
        if (intval($id) > 0) {
            self::$return = self::$successResponse;
            self::$return['data'] = self::processView($id);
        } else {
            self::$BadReques['additional_message'] = "ID invalid";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public function apiListByCategory($request) {
        /**
         * API authentication
         */
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $id = $request['category_id'];
        if (intval($id) > 0) {
            self::$return = self::$successResponse;
            self::$return['data'] = self::listByCategory($id);
        } else {
            self::$BadReques['additional_message'] = "ID invalid";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public function apiGetCategoryList($request) {
        /**
         * API authentication
         */
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        $return = self::$successResponse;
        $return['data'] = inventory_category::getList();

        return $return;
    }
    
    public function apiGetList($request) {
        /**
         * API authentication
         */
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        
        $return = self::$successResponse;
        $return['data'] = self::getList();

        return $return;
    }

    public function apiAdd($request) {
        /**
         * API authentication
         */
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $return = self::$successResponse;
        $return['data'] = self::getList();

        return $return;
    }

    public function apiManage($request) {
        /**
         * API authentication
         */
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $return = self::$successResponse;
        $return['data'] = self::getList();

        return $return;
    }

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
            echo '<div class="error"><p>You must create at least one category first. <a href="'.admin_url('admin.php?page=lh-category-inventory').'">click here</a> to create</a></p></div>';
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

    private function listByCategory($id) {
        $data = self::getSortedList($id, "category_id");
        return $data;
    }

    public function categories() {
        if (isset($_REQUEST['done'])) {
            $message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            $error_message = $_REQUEST['error'];
        }

        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            $data = inventory_category::listOne($id);
            $tag = "Modify Category";
        } else {
            $tag = "Add Category";
        }

        if (isset($_POST['submit'])) {
            unset($_POST['submit']);

            $_POST['created_by'] = get_current_user_id();
            $_POST['last_modified_by'] = get_current_user_id();
            $add = inventory_category::create($_POST);
            if ($add) {
                $message = "Category saved successfully";
                self::returnUrl("done", $message);
            } else {
                $error_message = "there was an error performing this action";
                self::returnUrl("error", $error_message);
            }
        } else if (isset($_GET['changeStatus'])) {
            if ($_GET['changeStatus'] == "INACTIVE") {
                $tag = "ACTIVE";
                $msg = "activated";
            } else if ($_GET['changeStatus'] == "ACTIVE") {
                $tag = "INACTIVE";
                $msg = "deactivated";
            }

            $update = self::updateOne(table_name_prefix."inventory_category", "status", $tag, $_GET['id']);
            if ($update) {
                $message = "Category ".$msg." successfully";
            } else {
                $error_message = "there was an error performing this action";
            }
        } else if (isset($_GET['remove'])) {
            $update = self::updateOne(table_name_prefix."inventory_category", "status", "DELETED", $_GET['id']);
            if ($update) {
                $message = "Category deleted successfully";
            } else {
                $error_message = "there was an error performing this action";
            }
        }
        $list =  inventory_category::getList();
        include_once(LH_PLUGIN_DIR."includes/pages/inventory/categories.php");
    }

    private function processView($id) {
        $data = self::listOne($id);
        $data['quantity'] =  self::getBalance( $id );
        
        $list = inventory_count::getSortedList($id, "inventory_id");

        return array( "data" => $data, "inventory_activity" => $list );
    }

    public function view() {
        if (isset($_REQUEST['done'])) {
            $message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            $error_message = $_REQUEST['error'];
        }

        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];

            $return = self::processView($id);
            $data = $return['data'];
            $inventory_activity = $return['inventory_activity'];

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

    public function print_view() {
        if (isset($_REQUEST['id'])) {
            global $pdf;
            $id = $_REQUEST['id'];
            $return = self::processView($id);
            $data = $return['data'];
            $list = $return['list'];
                              
            // set document information
            $pdf->SetCreator('LekkiHill');
            $pdf->SetAuthor('LekkiHill');
            $pdf->SetTitle('Inventory Report');
            $pdf->SetSubject('Inventory Report');
            $pdf->SetKeywords('LekkiHill, PDF, Inventory, Report');
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            
            $pdf->SetDefaultMonospacedFont("courier");

            $pdf->SetFont('dejavusans', '', 10);
                        
            // define barcode style
            $style = array(
                'position' => 'C',
            );

            // add a page
            $pdf->AddPage();
            
            $pdf->write1DBarcode($data['sku'], 'C39', '', '', '', 18, 0.4, $style, 'N');

            $pdf->Ln();

            // create some HTML content
            $html = '<h2>'.$data['title'].'</h2>
            <table width="100%" border="0">
            <tbody>
            <tr class="striped">
              <td width="25%">SKU</td>
              <td>'.$data['sku'].'</td>
            </tr>
            <tr>
              <td>Category</td>
              <td>'.inventory_category::getSingle( $data['category_id'] ).'</td>
             </tr>
            <tr>
              <td>Cost </td>
              <td>'.'&#8358; '.number_format($data['cost'], 2).'</td>
            </tr>
            <tr>
              <td>Quantity </td>
              <td>'.$data['quantity'].'</td>
            </tr>
            <tr>
              <td>Status</td>
              <td>'.$data['status'].'</td>
            </tr>
            <tr>
              <td>Created By</td>
              <td>'.self::getuser( $data['created_by'] ).'</td>
            </tr>
            <tr>
              <td>Created At</td>
              <td>'.$data['create_time'].'</td>
            </tr>
            <tr>
              <td>Last Modified by</td>
              <td>'.self::getuser( $data['last_modified_by'] ).'</td>
            </tr>
            <tr>
              <td>Modified At</td>
              <td>'.$data['modify_time'].'</td>
            </tr>
            </tbody>
          </table>
        <h3>History</h3>
        <table class="striped" id="datatable_list" border="1">
        <thead>
            <tr>
            <td>#</td>
            <td>Date</td>
            <td>Quantity Left</td>
            <td>Amount Added/Removed</td>
            <td>Total Quantity</td>
            <td>Added By</td>
            </tr>
        </thead>
        <tbody>';
            $count = 1;
            for ($i = 0;  $i < count($list); $i++) {
            $html .= '<tr>
                <td>'.$count.'</td>
                <td>'.$list[$i]['create_time'].'</td>
                <td>'.number_format( $list[$i]['inventory_before_added'] ).'</td>
                <td>'.($list[$i]['inventory_added'] < 0 ? "(".number_format( abs( $list[$i]['inventory_added'] ) ).")" : number_format( abs( $list[$i]['inventory_added'] ) ) ).'</td>
                <td>'.number_format( $list[$i]['inventory_before_added']+$list[$i]['inventory_added'] ).'</td>
                <td>'.self::getuser( $list[$i]['added_by'] ).'</td>
                </tr>';
                $count++;
            }
            $html .= '</tbody>
            </table>';
            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output('example_006.pdf', 'I');
        }
    }

    public function  print_report() {
        global $pdf;
        $result = self::processReport($_GET);
                
        // set document information
        $pdf->SetCreator('LekkiHill');
        $pdf->SetAuthor('LekkiHill');
        $pdf->SetTitle('Inventory Report');
        $pdf->SetSubject('Inventory Report');
        $pdf->SetKeywords('LekkiHill, PDF, Inventory, Report');

        $pdf->SetDefaultMonospacedFont("courier");

        $pdf->SetFont('dejavusans', '', 10);

        // add a page
        $pdf->AddPage();

        // create some HTML content

        $html = '<h2>Inventory Report:</h2>
        <p>Printed By: <strong>'.self::getuser( get_current_user_id() ).'</strong> | Print Date: <strong>'. date('l jS \of F Y h:i:s A').'</strong></p>
        <table class=“striped” id="datatable_list" border="1">
            <thead>
                <tr>
                <td>#</td>
                <td>Date</td>
                <td>Item</td>
                <td>SKU</td>
                <td>Cost</td>
                <td>Quantity Left</td>
                <td>Amount Added/Removed</td>
                <td>Total Quantity</td>
                <td>Added By</td>
                </tr>
            </thead>
            <tbody>';
            $html .= $count = 1;
            for ($i = 0;  $i < count($result); $i++) {
                $html .= '
                <tr>
                    <td>'.$count.'</td>
                    <td>'.$result[$i]['create_time'].'</td>
                    <td>'.$result[$i]['title'].'</td>
                    <td>'.$result[$i]['sku'].'</td>
                    <td>'."&#8358; ".number_format($result[$i]['cost'], 2).'</td>
                    <td>'.number_format( $result[$i]['inventory_before_added'] ).'</td>
                    <td>'.($result[$i]['inventory_added'] < 0 ? "(".number_format( abs( $result[$i]['inventory_added'] ) ).")" : number_format( abs( $result[$i]['inventory_added'] ) ) ).'</td>
                    <td>'.number_format( $result[$i]['inventory_before_added']+$result[$i]['inventory_added'] ).'</td>
                    <td>'.self::getuser( $result[$i]['added_by'] ).'</td>
                </tr>';
                $count++;
            }
            $html .= '</tbody>
            </table>';

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('example_006.pdf', 'I');
    }

    public function downloadView() {
        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            $return = self::processView($id);
            $data = $return['data'];
            $list = $return['list'];
            // filename for download
            $filename = $data['title']."_" . date('Ymd') . ".csv";

            header('Content-Description: File Transfer');
            header('Content-Encoding: UTF-8');
            header('Content-type: text/csv; charset=UTF-8');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            $out = fopen("php://output", 'w');

            $row_list = array();
            $header = array();
            $count = 1;

            $header[] = "";
            $header[] = "Date";
            $header[] = "Quantity Left";
            $header[] = "Amount Added_Remove";
            $header[] = "Total Quantoty";
            $header[] = "Added By";

            fputcsv($out, $header, ',', '"');
            foreach ($list as $row) {
                $row_list[] = $count;
                $row_list[] = $row['create_time'];
                $row_list[] = number_format( $row['inventory_before_added'] );;
                $row_list[] = ($row['inventory_added'] < 0 ? "(".number_format( abs( $row['inventory_added'] ) ).")" : number_format( abs( $row['inventory_added'] ) ) );
                $row_list[] = number_format( $row['inventory_before_added']+$row['inventory_added'] );
                $row_list[] = self::getuser( $row['added_by'] );

                array_walk($row_list, array('self', 'cleanData') );
                fputcsv($out, array_values($row_list), ',', '"');

                $count++;
                unset($row_list);
            }
            fclose($out);
            exit;
        }
    }

    public function downloadReport() {
        $result = self::processReport($_GET);

        // filename for download
        $filename = "inventory_" . date('Ymd') . ".csv";

        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $out = fopen("php://output", 'w');

        $row_list = array();
        $header = array();
        $count = 1;

        $header[] = "";
        $header[] = "Date";
        $header[] = "Item";
        $header[] = "SKU";
        $header[] = "Quantity Left";
        $header[] = "Amount Added_Remove";
        $header[] = "Total Quantoty";
        $header[] = "Added By";

        fputcsv($out, $header, ',', '"');
        foreach ($result as $row) {
            $row_list[] = $count;
            $row_list[] = $row['create_time'];
            $row_list[] = $row['title'];
            $row_list[] = $row['sku'];
            $row_list[] = number_format( $row['inventory_before_added'] );;
            $row_list[] = ($row['inventory_added'] < 0 ? "(".number_format( abs( $row['inventory_added'] ) ).")" : number_format( abs( $row['inventory_added'] ) ) );
            $row_list[] = number_format( $row['inventory_before_added']+$row['inventory_added'] );
            $row_list[] = self::getuser( $row['added_by'] );

            array_walk($row_list, array('self', 'cleanData') );
            fputcsv($out, array_values($row_list), ',', '"');

            $count++;
            unset($row_list);
        }
        fclose($out);
        exit;
    }

    private function processReport( $array ) {
        if ($array['view'] == "search") {
            $tag = " AND (`wp_lekkihill_inventory`.`title` LIKE '%".$array['search']."%' OR `wp_lekkihill_inventory`.`sku` LIKE '%".$array['search']."%')";
        } else if ($array['view'] == "category") {
            $tag = " AND `wp_lekkihill_inventory`.`category_id` = ".$array['category'];
        } else if ($array['view'] == "user") {
            $tag = " AND `wp_lekkihill_inventory_count`.`added_by` = ".$array['user'];
        }

        $sql = "SELECT `wp_lekkihill_inventory`.`ref`,`wp_lekkihill_inventory`.`title`, `wp_lekkihill_inventory`.`sku`, `wp_lekkihill_inventory`.`category_id`, `wp_lekkihill_inventory_count`.`inventory_added`, `wp_lekkihill_inventory_count`.`inventory_before_added`, `wp_lekkihill_inventory_count`.`added_by`, `wp_lekkihill_inventory_count`.`create_time` FROM `wp_lekkihill_inventory`, `wp_lekkihill_inventory_count` WHERE `wp_lekkihill_inventory`.`ref` = `wp_lekkihill_inventory_count`.`inventory_id` AND DATE(`wp_lekkihill_inventory_count`.`create_time`) BETWEEN '".$array['from']." 00:00:00' AND '".$array['to']." 23:59:59'".$tag." ORDER BY `wp_lekkihill_inventory_count`.`create_time` DESC";
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
        return self::lists(table_name_prefix."inventory", $start, $limit, $order, $dir, "`status` != 'DELETED'", $type);
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
            `cost` DOUBLE NOT NULL, 
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