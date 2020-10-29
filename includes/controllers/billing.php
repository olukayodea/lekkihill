<?php
class billing extends database {
    public static $logged_in_user;
    public static $return = array();
    public static $userData = array();
    public static $list = array();
    public static $list_component = array();
    public static $list_invoice = array();
    public static $viewData = array();
    public static $balance;
    public static $tag;
    public static $id;
    public static $url;
    public static $message;
    public static $error_message;
    public static $successResponse = array("status" => 200, "message" => "OK");
    public static $notFound = array("status" => 404, "message" => "Not Found");
    public static $NotModified = array("status" => 304, "message" => "Not Modified");
    public static $Unauthorized = array("status" => 401, "message" => "Unauthorized");
    public static $NotAcceptable = array("status" => 406, "message" => "Not Acceptable");
    public static $BadReques = array("status" => 400, "message" => "Bad Reques");
    public static $internalServerError = array("status" => 500, "message" => "Internal Server Error");
    
    public static $userToken;
    
    public static function search() {
        self::$userToken = users::getToken( get_current_user_id(), FALSE );
        self::$logged_in_user = get_userdata( get_current_user_id() );
        if (isset($_REQUEST['done'])) {
            self::$message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            self::$error_message = $_REQUEST['error'];
        }

        if (isset($_POST['submitPatient'])) {
            if ($_POST['patient_id'] > 0) {
                self::$tag = patient::getSingle( $_POST['patient_id'] )." ".patient::getSingle( $_POST['patient_id'], "first_name" )."'s Records";
                self::$id = $_POST['patient_id'];
                self::listPatients();

                self::$url = '&submitPatient&patient_id='.$_POST['patient_id'];
            } else {
                self::$tag = "All Pending Invoices";
                self::$error_message = "The patient can not be retrieved";
                self::list_invoice_pending();
            }
        } else if (isset($_POST['submitFilter'])) {
            self::$tag = ucfirst( strtolower( $_POST['status'] ) )." record(s) between ".$_POST['from_date']." and ".$_POST['to_date'];
            invoice::$status = $_POST['status'];
            invoice::$from_date = $_POST['from_date'];
            invoice::$to_date = $_POST['to_date'];
            invoice::$patient_id = $_POST['patient_id'];
            self::filterPatients();

            self::$url = '&submitFilter&from_date='.$_POST['from_date'].'&to_date='.$_POST['to_date'].'&status='.$_POST['status'];

            if ($_POST['patient_id'] > 0) {
                self::$url .= '&patient_id='.$_POST['patient_id'];
            }
        } else {
            self::$tag = "All Pending Invoices";
            self::list_invoice_pending();
        }

        include_once(LH_PLUGIN_DIR."/includes/pages/billings/search.php");
    }

    public static function PrintInvoice() {
        if (isset($_REQUEST['submitPatient'])) {
            if ($_REQUEST['patient_id'] > 0) {
                self::$tag = patient::getSingle( $_REQUEST['patient_id'] )." ".patient::getSingle( $_REQUEST['patient_id'], "first_name" )."'s Records";
                self::$id = $_REQUEST['patient_id'];
                self::listPatients();
            } else {
                self::$tag = "All Pending Invoices";
                self::list_invoice_pending();
            }
        } else if (isset($_REQUEST['submitFilter'])) {
            self::$tag = ucfirst( strtolower( $_POST['status'] ) )." record(s) between ".$_REQUEST['from_date']." and ".$_REQUEST['to_date'];
            invoice::$status = $_REQUEST['status'];
            invoice::$from_date = $_REQUEST['from_date'];
            invoice::$to_date = $_REQUEST['to_date'];
            invoice::$patient_id = $_REQUEST['patient_id'];
            self::filterPatients();
        } else {
            self::$tag = "All Pending Invoices";
            self::list_invoice_pending();
        }
        include_once(LH_PLUGIN_DIR."/includes/pages/billings/managePrint.php");
    }

    public static function DownloadInvoice() {
        if (isset($_REQUEST['submitPatient'])) {
            if ($_REQUEST['patient_id'] > 0) {
                self::$tag = patient::getSingle( $_REQUEST['patient_id'] )." ".patient::getSingle( $_REQUEST['patient_id'], "first_name" )."'s Records";
                self::$id = $_REQUEST['patient_id'];
                self::listPatients();
            } else {
                self::$tag = "All Pending Invoices";
                self::list_invoice_pending();
            }
        } else if (isset($_REQUEST['submitFilter'])) {
            self::$tag = ucfirst( strtolower( $_POST['status'] ) )." record(s) between ".$_REQUEST['from_date']." and ".$_REQUEST['to_date'];
            invoice::$status = $_REQUEST['status'];
            invoice::$from_date = $_REQUEST['from_date'];
            invoice::$to_date = $_REQUEST['to_date'];
            invoice::$patient_id = $_REQUEST['patient_id'];
            self::filterPatients();
        } else {
            self::$tag = "All Pending Invoices";
            self::list_invoice_pending();
        }
        
        $username_temp = str_replace(" ","_", self::$tag);
        $username_temp = str_replace("-","_", $username_temp);
        $username_temp = str_replace(",","_", $username_temp);
        $username_temp = strtolower($username_temp);
        $filename = $username_temp."_" . date('Ymd') . ".csv";

        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $out = fopen("php://output", 'w');

        $row_list = array();
        $header = array();
        $count = 1;

        $header[] = "";
        $header[] = "Invoice";
        $header[] = "Patient";
        $header[] = "Amount";
        $header[] = "Pending";
        $header[] = "Status";
        $header[] = "Created";
        $header[] = "Last Modified";

        fputcsv($out, $header, ',', '"');
        foreach (self::$list as $row) {
            $row_list[] = $count;
            $row_list[] = invoice::invoiceNumber( $row['ref'] );
            $row_list[] = patient::getSingle( $row['patient_id'] )." ".patient::getSingle( $row['patient_id'], "first_name" );
            $row_list[] = "&#8358; ".number_format( $row['amount'], 2 );
            $row_list[] = "&#8358; ".number_format( $row['due'], 2 );
            $row_list[] = $row['status'];
            $row_list[] = $row['create_time'];
            $row_list[] = $row['modify_time'];

            array_walk($row_list, array('self', 'cleanData') );
            fputcsv($out, array_values($row_list), ',', '"');

            $count++;
            unset($row_list);
        }
        fclose($out);
        exit;
    }

    public static function list_patient_api( $request ) {
        self::listPatients();
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
        self::$return = self::$successResponse;
        self::$return['data'] = invoice::formatResult( self::$list );

        return self::$return;
    }

    private static function listPatients() {
        self::$list = invoice::getSortedList(self::$id, "patient_id");
    }

    public static function filter_patient_api( $request ) {
        if ($request['status'] == "") {
            invoice::$status = "ALL";
        } else {
            invoice::$status = $request['status'];
        }
        invoice::$from_date = $request['from_date'];
        invoice::$to_date = $request['to_date'];
        invoice::$patient_id = $request['patient_id'];
        self::filterPatients();
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
        self::$return = self::$successResponse;
        self::$return['data'] = invoice::formatResult( self::$list );

        return self::$return;
    }

    private static function filterPatients() {
        self::$list = invoice::filer();
    }

    public static function invoice() {
        self::$userToken = users::getToken( get_current_user_id(), FALSE );
        self::list_component();
        self::$logged_in_user = get_userdata( get_current_user_id() );
        if (isset($_REQUEST['done'])) {
            self::$message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            self::$error_message = $_REQUEST['error'];
        }

        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            self::read_invoice($id);
            self::$tag = "Manage Invoice";
        } else {
            self::$tag = "Add Invoice";
        }

        if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            unset($_POST['component']);

            $_POST['billing_component'] = array_values($_POST['billing_component']);
            $_POST['added_by'] = get_current_user_id();

            $add = invoice::create($_POST);
            if ($add) {
                self::$message = "Invoice created successfully";
                self::$viewData = array();
            } else {
                self::$error_message = "there was an error performing this action";
            }
        }
        self::list_invoice_pending();
        include_once(LH_PLUGIN_DIR."/includes/pages/billings/invoice.php");
    }

    public static function component() {
        if (isset($_REQUEST['done'])) {
            self::$message = $_REQUEST['done'];
        } else if (isset($_REQUEST['error'])) {
            self::$error_message = $_REQUEST['error'];
        }

        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            self::read_component($id);
            self::$tag = "Modify Component";
        } else {
            self::$tag = "Add Component";
        }

        if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            
            $_POST['created_by'] = get_current_user_id();
            $_POST['last_modified_by'] = get_current_user_id();

            $add = billing_component::create($_POST);
            if ($add) {
                self::$message = "Component saved successfully";
                self::$viewData = array();
            } else {
                self::$error_message = "there was an error performing this action";
            }
        } else if (isset($_GET['changeStatus'])) {
            self::change_component_status($_GET['id'], $_GET['changeStatus']);
        } else if (isset($_GET['remove'])) {
            self::delete_component( $_GET['id']);
        }
        self::list_component();
        include_once(LH_PLUGIN_DIR."/includes/pages/billings/component.php");
    }

    public static function create_api_component($request) {
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
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $parameters['status'] = "ACTIVE";
        $parameters['created_by'] = self::$userData['ID'];
        $parameters['last_modified_by'] = self::$userData['ID'];

        $add = billing_component::create($parameters);
        if ($add) {
            self::$successResponse['additional_message'] = "Component saved successfully";
            self::$return = self::$successResponse;
            self::$return['ID'] = $add;
        } else {
            self::$BadReques['additional_message'] = "there was an error performing this action";
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    public static function read_api_component($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $id = $request['component_id'];
        if (intval($id) > 0) {
            self::read_component($id);
            self::$return = self::$successResponse;
            self::$return['data'] = billing_component::formatResult( self::$viewData, true );
        } else {
            self::$BadReques['additional_message'] = "ID invalid";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    private static function read_component($id) {
        self::$viewData = billing_component::listOne($id);
    }

    public static function update_api_component() {
    }

    public static function delete_api_component($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $id = $request['component_id'];
        if (intval($id) > 0) {
            if (self::delete_component($id)) {
            self::$BadReques['additional_message'] = self::$message;
            self::$return = self::$successResponse;
            } else {
                self::$BadReques['additional_message'] = self::$error_message;
                self::$return = self::$BadReques;
            }
        } else {
            self::$BadReques['additional_message'] = "Missing billing component ID";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    private static function delete_component($id) {
        $update = self::updateOne(table_name_prefix."billing_component", "status", "DELETED", $id);
        if ($update) {
            self::$message = "Category deleted successfully";
            return true;
        } else {
            self::$error_message = "there was an error performing this action";
            return false;
        }
    }

    public static function change_api_component_status($request) {
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
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $id = $parameters['id'];
        $status = $parameters['status']; 

        if (self::change_component_status($id, $status)) {
            self::$BadReques['additional_message'] = self::$message;
            self::$return = self::$successResponse;
        } else {
            self::$BadReques['additional_message'] = self::$error_message;
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    private static function change_component_status($id, $status) {
        if ($status == "INACTIVE") {
            $tag = "ACTIVE";
            $msg = "activated";
        } else if ($status == "ACTIVE") {
            $tag = "INACTIVE";
            $msg = "deactivated";
        }

        $update = self::updateOne(table_name_prefix."billing_component", "status", $tag, $id);
        if ($update) {
            self::$message = "Category ".$msg." successfully";
            return true;
        } else {
            self::$error_message = "there was an error performing this action";
            return false;
        }
    }

    public static function list_api_component($request) {
        self::list_component();
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
        self::$return = self::$successResponse;
        self::$return['data'] = billing_component::formatResult( self::$list_component );

        return self::$return;
    }

    public static function list_component() {
        self::$list_component =  billing_component::getList();
    }

    public static function create_api_invoice($request) {
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
        if (!$parameters) {
            self::$BadReques['additional_message'] = "some input values are missing";
            self::$return = self::$BadReques;
            return self::$return;
        }
        $parameters['added_by'] = self::$userData['ID'];

        $add = invoice::create($parameters);
        if ($add) {
            self::$successResponse['additional_message'] = "Invoice saved successfully";
            self::$return = self::$successResponse;
            self::$return['ID'] = $add;
        } else {
            self::$BadReques['additional_message'] = "there was an error performing this action";
            self::$return = self::$BadReques;
        }

        return self::$return;
    }

    public static function list_api_invoice_pending($request) {
        self::list_invoice_pending();
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
        self::$return = self::$successResponse;
        self::$return['data'] = invoice::formatResult( self::$list );

        return self::$return;
    }

    private static function list_invoice_pending () {
        self::$list = invoice::getPending();
    }

    public static function read_api_invoice($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }
        $id = $request['invoice_id'];
        if (intval($id) > 0) {
            self::read_invoice($id);
            self::$return = self::$successResponse;
            self::$return['data'] = invoice::formatResult( self::$viewData, true );
        } else {
            self::$BadReques['additional_message'] = "ID invalid";
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    private static function read_invoice($id) {
        self::$viewData = invoice::listOne($id);
    }

    public static function get_api_due_invoice($request) {
        $auth = self::validateSession($request);
        if ($auth['status'] == 200) {
            unset($auth['status']);
            unset($auth['message']);
            self::$userData = $auth;
        } else {
            return $auth;
        }

        if (isset($request['user_id'])) {
            $id = $request['user_id'];
            $email = false;
        } else if (isset($request['email'])) {
            $id = false;
            $email = $request['email'];
        }

        self::get_due_invoice($id, $email);
        self::$return = self::$successResponse;
        $cost['value'] = self::$balance;
        $cost['label'] = "&#8358; ".number_format( self::$balance );
        self::$return['total_amount'] = $cost;
        self::$return['data'] = invoice::formatResult( self::$list_invoice );
        return self::$return;
    }

    public static function get_due_invoice($id=false, $email=false) {
        if ($id !== false) {
            $tag = '`patient_id` = '.$id.' AND ';
        } else if ($email !== false) {
            $tag = "`patient_id` = (SELECT `ref` FROM `wp_lekkihill_patient` WHERE `email` = '".$email."' ) AND ";
        }

        self::$balance = self::query("SELECT SUM(`due`) FROM `wp_lekkihill_invoice` WHERE ". $tag ."`status` != 'PAID'", false, "getCol");
        self::$list_invoice = self::query("SELECT * FROM `wp_lekkihill_invoice` WHERE ". $tag ."`status` != 'PAID'", false, "list");
    }

    public static function report() {

    }

    public static function create($array) {
        return self::insert(table_name_prefix."billing", $array);
    }

    static function modifyOneBill($tag, $value, $id, $ref="ref") {
        return self::updateOne(table_name_prefix."billing", $tag, $value, $id, $ref);
    }

    public static function listOne($id) {
        return self::getOne(table_name_prefix."billing", $id, "ref");
    }

    public static function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'ref', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."billing", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    public static function formatResult($data, $single=false) {
        if ($single == false) {
            for ($i = 0; $i < count($data); $i++) {
                $data[$i] = self::clean($data[$i]);
            }
        } else {
            $data = self::clean($data);
        }
        return $data;
    }

    public static function clean($data) {

        $added_by['id'] = $data['created_by'];
        $added_by['user_login'] = users::getSingle( $data['added_by'] );
        $added_by['user_nicename'] = users::getSingle( $data['added_by'], "user_nicename" );
        $added_by['user_email'] = users::getSingle( $data['added_by'], "user_email" );
        $data['created_by'] = $added_by;

        $patient_id['id'] = $data['patient_id'];
        $patient_id['first_name'] = patient::getSingle( $data['patient_id'], "first_name" );
        $patient_id['last_name'] = patient::getSingle( $data['patient_id'], "last_name" );
        $patient_id['email'] = patient::getSingle( $data['patient_id'], "email" );
        $patient_id['phone_number'] = patient::getSingle( $data['patient_id'], "phone_number" );
        $data['patient_id'] = $patient_id;

        $billing_component['id'] = $data['billing_component_id'];
        $billing_component['name'] = billing_component::getSingle( $data['billing_component_id'] );
        $data['billing_component'] = $billing_component;

        $cost['value'] = $data['cost'];
        $cost['label'] = "&#8358; ".number_format( $data['cost'] );
        $data['cost'] = $cost;
        
        unset( $data['patient_id'] );
        unset( $data['added_by'] );
        unset( $data['billing_component_id'] );
        return $data;
    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."billing` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `invoice_id` INT NOT NULL, 
            `billing_component_id` INT NOT NULL, 
            `type` varchar(20) NOT NULL DEFAULT 'component',
            `quantity` INT NOT NULL, 
            `description` varchar(500) NULL,
            `patient_id` INT NOT NULL, 
            `cost` DOUBLE NOT NULL, 
            `status` varchar(20) NOT NULL DEFAULT 'NEW',
            `added_by` INT NOT NULL, 
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