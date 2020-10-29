<?php
class invoice extends billing {
    public static $invoice;
    public static $patient_id;
    public static $status = "ALL";
    public static $from_date;
    public static $to_date;
    public static function create($array) {
        $invData['amount'] = $invData['due'] = 0;
        $invData['patient_id'] = $array['patient_id'];
        if (isset($array['due_date'])) {
            $invData['due_date'] = $array['due_date'];
        }
        self::$invoice = self::insert(table_name_prefix."invoice", $invData);

        $data['invoice_id'] = self::$invoice;
        $data['patient_id'] = $array['patient_id'];
        $data['added_by'] = $array['added_by'];

        $amount = 0;

        for ($i = 0; $i < count($array['billing_component']); $i++) {
            $data['billing_component_id'] = $array['billing_component'][$i]['id'];
            $data['cost'] = $array['billing_component'][$i]['cost'];
            $data['quantity'] = $array['billing_component'][$i]['quantity'];
            $data['type'] = $array['billing_component'][$i]['type'];
            $data['description'] = $array['billing_component'][$i]['description'];


            $amount = $amount+$data['quantity']*$data['cost'];
            billing::create($data);
        }

        self::updateOne(table_name_prefix."invoice", "amount", $amount, self::$invoice);
        self::updateOne(table_name_prefix."invoice", "due", $amount, self::$invoice);
        //semd email

        return self::$invoice;
    }

    public static function postPayment( $array ) {
        $add = self::create($array);
        $data['data'][] = array("ref" => $add, "amount" => $array['billing_component'][0]['quantity']*$array['billing_component'][0]['cost']);

        return self::payInvoice($data);
    }

    public static function invoiceNumber($id) {
        return "INV".(10000+$id);
    }

    public static function api_pay_invoice($request) {
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

        $add = self::payInvoice($parameters);
        if ($add) {
            self::$return = self::$successResponse;
        } else {
            self::$return = self::$BadReques;
        }
        return self::$return;
    }


    public static function api_post_payment($request) {
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

        $data['patient_id'] = $parameters['patient_id'];
        $data['due_date'] = date("Y-m-d");
        $data['added_by'] = self::$userData['ID'];

        $data['billing_component'][] = array("id"=>$parameters['component_id'],"cost"=>$parameters['cost'],"quantity"=>$parameters['quantity'],"description"=>$parameters['description']);

        $add = self::postPayment($data);
        if ($add) {
            self::$return = self::$successResponse;
        } else {
            self::$return = self::$BadReques;
        }
        return self::$return;
    }

    public static function payInvoice($data) {
        foreach($data['data'] as $details) {
            if (self::query("UPDATE ".table_name_prefix."invoice SET `due` = (`due`-".$details['amount'].") WHERE `ref` = ".$details['ref'])) {
                self::modifyOne("status", "PARTIALLY-PAID", $details['ref']);
                self::modifyOneBill("status", "PARTIALLY-PAID", $details['ref'], "invoice_id");

                $getOne = self::listOne($details['ref']);

                if ($getOne['due'] <= 0) {
                    self::modifyOne("status", "PAID", $details['ref']);
                    self::modifyOneBill("status", "PAID", $details['ref'], "invoice_id");
                }
            }
        }

        //send reciept

        return true;
    }

    public static function getPending() {
        return self::query("SELECT * FROM ".table_name_prefix."invoice WHERE `status` != 'PAID'", false, "list");
    }

    static function modifyOne($tag, $value, $id, $ref="ref") {
        return self::updateOne(table_name_prefix."invoice", $tag, $value, $id, $ref);
    }

    public static function getList($start=false, $limit=false, $order="title", $dir="ASC", $type="list") {
        return self::lists(table_name_prefix."invoice", $start, $limit, $order, $dir, "`status` != 'DELETED'", $type);
    }

    public static function getSingle($name, $tag="title", $ref="ref") {
        return self::getOneField(table_name_prefix."invoice", $name, $ref, $tag);
    }

    public static function listOne($id) {
        return self::getOne(table_name_prefix."invoice", $id, "ref");
    }

    public static function getSortedList($id, $tag, $tag2 = false, $id2 = false, $tag3 = false, $id3 = false, $order = 'ref', $dir = "ASC", $logic = "AND", $start = false, $limit = false) {
        return self::sortAll(table_name_prefix."invoice", $id, $tag, $tag2, $id2, $tag3, $id3, $order, $dir, $logic, $start, $limit);
    }

    public static function filer( ) {
        if (self::$status != "ALL") {
            $tag = " AND `status` = '".self::$status."'";
        }
        if (self::$patient_id > 0) {
            $tag .= " AND `patient_id` = '".self::$patient_id."'";
        }

        return self::query("SELECT * FROM `".DB_NAME."`.`".table_name_prefix."invoice` WHERE `due_date` BETWEEN '".self::$from_date."' AND '".self::$to_date."'".$tag, false, "list");
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
        $patient_id['id'] = $data['patient_id'];
        $patient_id['first_name'] = patient::getSingle( $data['patient_id'], "first_name" );
        $patient_id['last_name'] = patient::getSingle( $data['patient_id'], "last_name" );
        $patient_id['email'] = patient::getSingle( $data['patient_id'], "email" );
        $patient_id['phone_number'] = patient::getSingle( $data['patient_id'], "phone_number" );
        $data['patient_id'] = $patient_id;
        
        $amount['value'] = $data['amount'];
        $amount['label'] = "&#8358; ".number_format( $data['amount'] );
        $data['amount'] = $amount;

        $due['value'] = $data['due'];
        $due['label'] = "&#8358; ".number_format( $data['due'] );
        $data['due'] = $due;

        $data['invoice_data'] = billing::formatResult( billing::getSortedList($data['ref'], "invoice_id") );
        
        return $data;
    }

    public function initialize_table() {
        //create database
        $query = "CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".table_name_prefix."invoice` (
            `ref` INT NOT NULL AUTO_INCREMENT, 
            `patient_id` INT NOT NULL, 
            `amount` DOUBLE NOT NULL, 
            `due` DOUBLE NOT NULL, 
            `due_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `status` varchar(20) NOT NULL DEFAULT 'UN-PAID',
            `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`ref`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        self::query($query);
    }

    public function clear_table() {
        //clear database
        $query = "TRUNCATE `".DB_NAME."`.`".table_name_prefix."invoice`";

        self::query($query);
    }

    public function delete_table() {
        //clear database
        $query = "DROP TABLE IF EXISTS `".DB_NAME."`.`".table_name_prefix."invoice`";

        self::query($query);
    }
}
?>