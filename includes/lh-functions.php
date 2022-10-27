<?php
class main {
    public static function save_password( $user ) {
        users::add_app_token($_POST, $user);
    }

    //create the API route
    public static function apiRoutes() {
        //url = https://lekkihill.com/wp-json/api/users/login;
        register_rest_route( 'api', 'users/login',array(
            'methods'  => 'POST',
            'callback' => array("users",'login')
        ));
        //url = https://lekkihill.com/wp-json/api/users/ID;
        register_rest_route( 'api', 'users/(?P<user_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("users",'listAllUsers')
        ));
        //url = https://lekkihill.com/wp-json/api/users;
        register_rest_route( 'api', 'users',array(
            'methods'  => 'GET',
            'callback' => array("users",'listUsers')
        ));

        //GET inventory routes
        //url = https://lekkihill.com/wp-json/api/inventory/category/ID;
        register_rest_route( 'api', 'inventory/category/(?P<category_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("inventory",'apiListByCategory')
        ));
        //url = https://lekkihill.com/wp-json/api/inventory/category;
        register_rest_route( 'api', 'inventory/category',array(
            'methods'  => 'GET',
            'callback' => array("inventory",'apiGetCategoryList')
        ));
        //url = https://lekkihill.com/wp-json/api/inventory/ID;
        register_rest_route( 'api', 'inventory/(?P<category_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("inventory",'apiGetOne')
        ));
        //url = https://lekkihill.com/wp-json/api/inventory;
        register_rest_route( 'api', 'inventory',array(
            'methods'  => 'GET',
            'callback' => array("inventory",'apiGetList')
        ));
        //POST inventory routes
        //url = https://lekkihill.com/wp-json/api/inventory/search;
        register_rest_route( 'api', 'inventory/search',array(
            'methods'  => 'POST',
            'callback' => array("inventory",'apiSearch')
        ));
        //url = https://lekkihill.com/wp-json/api/inventory/category;
        register_rest_route( 'api', 'inventory/category',array(
            'methods'  => 'POST',
            'callback' => array("inventory",'apiCreateCategory')
        ));
        //url = https://lekkihill.com/wp-json/api/inventory;
        register_rest_route( 'api', 'inventory',array(
            'methods'  => 'POST',
            'callback' => array("inventory",'apiCreateInventory')
        ));

        

        //PUT inventory routes
        //url = https://lekkihill.com/wp-json/api/inventory/category;
        register_rest_route( 'api', 'inventory/category',array(
            'methods'  => 'PUT',
            'callback' => array("inventory",'apiCreateCategory')
        ));
        //url = https://lekkihill.com/wp-json/api/inventory/discount;
        register_rest_route( 'api', 'inventory/discount',array(
            'methods'  => 'PUT',
            'callback' => array("inventory",'apiUpdateDiscount')
        ));
        //url = https://lekkihill.com/wp-json/api/inventory;
        register_rest_route( 'api', 'inventory',array(
            'methods'  => 'PUT',
            'callback' => array("inventory",'apiCreateInventory')
        ));

        //DELETE inventory route
        //url = https://lekkihill.com/wp-json/api/inventory/category/ID;
        register_rest_route( 'api', 'inventory/category/(?P<category_id>\d+)',array(
            'methods'  => 'DELETE',
            'callback' => array("inventory",'apiDeleteCategory')
        ));
        
        //POST appointment routes
        //url = https://lekkihill.com/wp-json/api/appointment/add;
        register_rest_route( 'api', 'appointment/add',array(
            'methods'  => 'POST',
            'callback' => array("appointments",'createAPI')
        ));

        //GET appointment routes
        //url = https://lekkihill.com/wp-json/api/appointment/new;
        register_rest_route( 'api', 'appointment/new',array(
            'methods'  => 'GET',
            'callback' => array("appointments",'manage_new_api')
        ));
        //url = https://lekkihill.com/wp-json/api/appointment/today;
        register_rest_route( 'api', 'appointment/today',array(
            'methods'  => 'GET',
            'callback' => array("appointments",'manage_today_api')
        ));
        //url = https://lekkihill.com/wp-json/api/appointment/upcoming;
        register_rest_route( 'api', 'appointment/upcoming',array(
            'methods'  => 'GET',
            'callback' => array("appointments",'manage_upcoming_api')
        ));
        //url = https://lekkihill.com/wp-json/api/appointment/past;
        register_rest_route( 'api', 'appointment/past',array(
            'methods'  => 'GET',
            'callback' => array("appointments",'manage_past_api')
        ));

        //PUT appointment routes
        //url = https://lekkihill.com/wp-json/api/appointment/update;
        register_rest_route( 'api', 'appointment/update',array(
            'methods'  => 'PUT',
            'callback' => array("appointments",'updateAppointmentAPI')
        ));

        //DELETE appointment route
        //url = https://lekkihill.com/wp-json/api/appointment/removeNew/ID;
        register_rest_route( 'api', 'appointment/removeNew/(?P<appointment_id>\d+)',array(
            'methods'  => 'DELETE',
            'callback' => array("appointments",'removeNewAPI')
        ));


        //POST billing route
        //add new component
        //url = https://lekkihill.com/wp-json/api/billing/component;
        register_rest_route( 'api', 'billing/component',array(
            'methods'  => 'POST',
            'callback' => array("billing",'create_api_component')
        ));
        //add new invoice
        //url = https://lekkihill.com/wp-json/api/billing/invoice;
        register_rest_route( 'api', 'billing/invoice',array(
            'methods'  => 'POST',
            'callback' => array("billing",'create_api_invoice')
        ));
        //add new invoice
        //url = https://lekkihill.com/wp-json/api/billing/postPayment;
        register_rest_route( 'api', 'billing/postPayment',array(
            'methods'  => 'POST',
            'callback' => array("invoice",'api_post_payment')
        ));

        //PUT billing route
        //update component
        //url = https://lekkihill.com/wp-json/api/billing/payInvoice;
        register_rest_route( 'api', 'billing/payInvoice',array(
            'methods'  => 'PUT',
            'callback' => array("invoice",'api_pay_invoice')
        ));
        //update component
        //url = https://lekkihill.com/wp-json/api/billing/component;
        register_rest_route( 'api', 'billing/component',array(
            'methods'  => 'PUT',
            'callback' => array("billing",'create_api_component')
        ));
        //change component status
        //url = https://lekkihill.com/wp-json/api/billing/component/status;
        register_rest_route( 'api', 'billing/component/status',array(
            'methods'  => 'PUT',
            'callback' => array("billing",'change_api_component_status')
        ));

        //GET billing route
        //list all pending invoice
        //url = https://lekkihill.com/wp-json/api/billing/invoice/pending;
        register_rest_route( 'api', 'billing/invoice/pending',array(
            'methods'  => 'GET',
            'callback' => array("billing",'list_api_invoice_pending')
        ));
        //list all patients invoice
        //url = https://lekkihill.com/wp-json/api/billing/invoice/patients/ID;
        register_rest_route( 'api', 'billing/invoice/patients/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("billing",'list_patient_api')
        ));
        //filter all patients invoice
        //url = https://lekkihill.com/wp-json/api/billing/invoice/filter/from/to/status/id;
        register_rest_route( 'api', 'billing/invoice/filter/(?P<from_date>\S+)/(?P<to_date>\S+)/(?P<status>\S+)/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("billing",'filter_patient_api')
        ));
        //url = https://lekkihill.com/wp-json/api/billing/invoice/filter/from/to/id;
        register_rest_route( 'api', 'billing/invoice/filter/(?P<from_date>\S+)/(?P<to_date>\S+)/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("billing",'filter_patient_api')
        ));
        //url = https://lekkihill.com/wp-json/api/billing/invoice/filter/from/to/status;
        register_rest_route( 'api', 'billing/invoice/filter/(?P<from_date>\S+)/(?P<to_date>\S+)/(?P<status>\S+)',array(
            'methods'  => 'GET',
            'callback' => array("billing",'filter_patient_api')
        ));
        //url = https://lekkihill.com/wp-json/api/billing/invoice/filter/from/to;
        register_rest_route( 'api', 'billing/invoice/filter/(?P<from_date>\S+)/(?P<to_date>\S+)',array(
            'methods'  => 'GET',
            'callback' => array("billing",'filter_patient_api')
        ));
        //list all paid invoice
        //url = https://lekkihill.com/wp-json/api/billing/invoice/paid;
        register_rest_route( 'api', 'billing/invoice/paid',array(
            'methods'  => 'GET',
            'callback' => array("billing",'list_api_invoice_paid')
        ));
        //list all component
        //url = https://lekkihill.com/wp-json/api/billing/component/list;
        register_rest_route( 'api', 'billing/component/list',array(
            'methods'  => 'GET',
            'callback' => array("billing",'list_api_component')
        ));
        //get one component
        //url = https://lekkihill.com/wp-json/api/billing/component/ID;
        register_rest_route( 'api', 'billing/component/(?P<component_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("billing",'read_api_component')
        ));
        //get one invoice
        //url = https://lekkihill.com/wp-json/api/billing/invoice/ID;
        register_rest_route( 'api', 'billing/invoice/(?P<invoice_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("billing",'read_api_invoice')
        ));
        //get one outstanding
        //url = https://lekkihill.com/wp-json/api/billing/outstanding/id/ID;
        register_rest_route( 'api', 'billing/outstanding/id/(?P<user_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("billing",'get_api_due_invoice')
        ));
        //get one outstanding
        //url = https://lekkihill.com/wp-json/api/billing/outstanding/email/EMAIL;
        register_rest_route( 'api', 'billing/outstanding/email/(?P<email>\S+)',array(
            'methods'  => 'GET',
            'callback' => array("billing",'get_api_due_invoice')
        ));

        //DELETE billing route
        //delete component
        //url = https://lekkihill.com/wp-json/api/billing/component/ID;
        register_rest_route( 'api', 'billing/component/(?P<component_id>\d+)',array(
            'methods'  => 'DELETE',
            'callback' => array("billing",'delete_api_component')
        ));

        //POST Patient route
        //add new patient
        //url = https://lekkihill.com/wp-json/api/patient;
        register_rest_route( 'api', 'patient',array(
            'methods'  => 'POST',
            'callback' => array("patient",'create_api_component')
        ));
        //add new patient notes
        //url = https://lekkihill.com/wp-json/api/patient/notes;
        register_rest_route( 'api', 'patient/notes',array(
            'methods'  => 'POST',
            'callback' => array("clinic",'api_add_note')
        ));
        //sell or recommend drugs
        //url = https://lekkihill.com/wp-json/api/patient/drugs;
        register_rest_route( 'api', 'patient/drugs',array(
            'methods'  => 'POST',
            'callback' => array("clinic",'api_sell_medication')
        ));
        //add new patient vitals
        //url = https://lekkihill.com/wp-json/api/patient/vitals;
        register_rest_route( 'api', 'patient/vitals',array(
            'methods'  => 'POST',
            'callback' => array("clinic",'api_add_vitals')
        ));
        //add new patient continuation
        //url = https://lekkihill.com/wp-json/api/patient/continuation;
        register_rest_route( 'api', 'patient/continuation',array(
            'methods'  => 'POST',
            'callback' => array("clinic",'api_add_continuation')
        ));
        //add new patient postOp
        //url = https://lekkihill.com/wp-json/api/patient/postOp;
        register_rest_route( 'api', 'patient/postOp',array(
            'methods'  => 'POST',
            'callback' => array("clinic",'api_add_post_op')
        ));
        //add new patient medication
        //url = https://lekkihill.com/wp-json/api/patient/medication;
        register_rest_route( 'api', 'patient/medication',array(
            'methods'  => 'POST',
            'callback' => array("clinic",'api_add_medication')
        ));
        //add new patient fluid balance
        //url = https://lekkihill.com/wp-json/api/patient/fluidBalance;
        register_rest_route( 'api', 'patient/fluidBalance',array(
            'methods'  => 'POST',
            'callback' => array("clinic",'api_add_fluid_balance')
        ));
        
        //GET Patient route
        //search component
        //url = https://lekkihill.com/wp-json/api/patient/search;
        register_rest_route( 'api', 'patient/search',array(
            'methods'  => 'GET',
            'callback' => array("patient",'get_patient_suggest_api')
        ));
        //get patient data
        //url = https://lekkihill.com/wp-json/api/patient/1;
        register_rest_route( 'api', 'patient/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("patient",'read_api_component')
        ));
        //get patient data
        //url = https://lekkihill.com/wp-json/api/patient/1;
        register_rest_route( 'api', 'patient/drugs/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'medicationHistory_api')
        ));
        //get patient notes
        //url = https://lekkihill.com/wp-json/api/patient/notes/1;
        register_rest_route( 'api', 'patient/notes/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'api_get_notes')
        ));
        //get patient vitals
        //url = https://lekkihill.com/wp-json/api/patient/vitals/1;
        register_rest_route( 'api', 'patient/vitals/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'api_get_vital')
        ));
        //get patient continuation
        //url = https://lekkihill.com/wp-json/api/patient/continuation/1;
        register_rest_route( 'api', 'patient/continuation/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'api_get_continuation')
        ));
        //get patient post op
        //url = https://lekkihill.com/wp-json/api/patient/postOp/1;
        register_rest_route( 'api', 'patient/postOp/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'api_get_post_op')
        ));
        //get patient medications
        //url = https://lekkihill.com/wp-json/api/patient/medication/1;
        register_rest_route( 'api', 'patient/medication/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'api_get_medication')
        ));
        //get patient fluid balance
        //url = https://lekkihill.com/wp-json/api/patient/fluidBalance/1;
        register_rest_route( 'api', 'patient/fluidBalance/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'api_get_fluid_balance')
        ));
        //get patient most recent vitals
        //url = https://lekkihill.com/wp-json/api/patient/vitals/recent/1;
        register_rest_route( 'api', 'patient/vitals/recent/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'api_recent_vital')
        ));
        //get patient most recent continuation
        //url = https://lekkihill.com/wp-json/api/patient/continuation/recent/1;
        register_rest_route( 'api', 'patient/continuation/recent/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'api_recent_continuation')
        ));
        //get patient most recent post op
        //url = https://lekkihill.com/wp-json/api/patient/postOp/recent/1;
        register_rest_route( 'api', 'patient/postOp/recent/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'api_recent_post_op')
        ));
        //get patient most recent medications
        //url = https://lekkihill.com/wp-json/api/patient/medication/recent/1;
        register_rest_route( 'api', 'patient/medication/recent/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'api_recent_medication')
        ));
        //get patient most recent fluid balance
        //url = https://lekkihill.com/wp-json/api/patient/fluidBalance/recent/1;
        register_rest_route( 'api', 'patient/fluidBalance/recent/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("clinic",'api_recent_fluid_balance')
        ));

        //POST Visitors route
        //add new visitor
        //url = https://lekkihill.com/wp-json/api/visitors;
        register_rest_route( 'api', 'visitors',array(
            'methods'  => 'POST',
            'callback' => array("visitors",'create_api_component')
        ));

        //GET Visitors route
        //add new visitor
        //url = https://lekkihill.com/wp-json/api/visitors;
        register_rest_route( 'api', 'visitors',array(
            'methods'  => 'GET',
            'callback' => array("visitors",'getvisitorsList')
        ));

        //POST settings route
        //add/update component
        //url = https://lekkihill.com/wp-json/api/settings;
        register_rest_route( 'api', 'settings',array(
            'methods'  => 'POST',
            'callback' => array("settings",'create_api')
        ));
        //GET settings route
        //get settings
        //url = https://lekkihill.com/wp-json/api/settings;
        register_rest_route( 'api', 'settings',array(
            'methods'  => 'GET',
            'callback' => array("settings",'get_settings_api')
        ));
    }

    //get all the  menus  and  submenu
    public static function lh_add_menu() {
        add_menu_page(
            "Patients",
            "Patients",
            "manage_patient",
            "lh-manage-patient",
            array('patient','manage'),
            "dashicons-admin-home",'2.2.9',
            2
        );

        add_submenu_page(
            "lh-manage-patient",
            "Manage",
            "Manage",
            "manage_patient",
            "lh-manage-patient",
            array('patient','manage')
        );

        add_menu_page(
            "Clinic",
            "Clinic",
            "manage_clinic",
            "lh-manage-clinic",
            array('clinic','manage'),
            "dashicons-id",'2.2.9',
            1
        );

        add_submenu_page(
            "lh-manage-clinic",
            "Medications",
            "Medications",
            "manage_medication",
            "lh-manage-medication",
            array('clinic','manageMedication')
        );

        add_menu_page(
            "Visitors",
            "Visitors",
            "manage_visitors",
            "lh-manage-visitors",
            array('visitors','manage'),
            "dashicons-id",'2.2.9',
            7
        );

        add_menu_page(
            "Appointments",
            "Appointments",
            "manage_patient",
            "lh-manage-appointments",
            array("appointments",'manage'),
            "dashicons-schedule",'2.2.9',
            3
        );

        add_submenu_page(
            "lh-manage-appointments",
            "Book Appointment",
            "Book Appointment",
            "manage_patient",
            "lh-manage-appointments",
            array("appointments",'manage')
        );

        add_submenu_page(
            "lh-manage-appointments",
            "Today",
            "Today",
            "manage_patient",
            "lh-today-appointments",
            array("appointments",'manage_today')
        );

        add_submenu_page(
            "lh-manage-appointments",
            "Upcoming",
            "Upcoming",
            "manage_patient",
            "lh-comming-appointments",
            array("appointments",'manage_upcoming')
        );

        add_submenu_page(
            "lh-manage-appointments",
            "Past",
            "Past",
            "manage_patient",
            "lh-past-appointments",
            array("appointments",'manage_past')
        );

        add_menu_page(
            "Billing and Accounts",
            "Finance",
            "mamange_accounts",
            "lh-billing",
            array("billing",'search'),
            "dashicons-money",'2.2.9',
            4
        );

        add_submenu_page(
            "lh-billing",
            "Search Records",
            "Search",
            "mamange_accounts",
            "lh-billing",
            array("billing",'search')
        );

        add_submenu_page(
            "lh-billing",
            "Manage Invoice",
            "Manage Invoice",
            "mamange_accounts",
            "lh-billing-invoice",
            array("billing",'invoice')
        );

        add_submenu_page(
            "lh-billing",
            "Components",
            "Components",
            "mamange_accounts",
            "lh-billing-component",
            array("billing",'component')
        );

        add_submenu_page(
            "lh-billing",
            "Reports",
            "Reports",
            "mamange_accounts_report",
            "lh-billing-report",
            array("billing",'report')
        );

        add_menu_page(
            "Inventory",
            "Inventory",
            "manage_inventory",
            "lh-inventory",
            array("inventory",'manage'),
            "dashicons-media-spreadsheet",'2.2.9',
            5
        );

        add_submenu_page(
            "lh-inventory",
            "List Inventory",
            "List Inventory",
            "manage_inventory",
            "lh-inventory",
            array("inventory",'manage')
        );

        add_submenu_page(
            "lh-inventory",
            "Add Inventory",
            "Add Inventory",
            "manage_inventory",
            "lh-add-inventory",
            array("inventory",'add')
        );

        add_submenu_page(
            null,
            'Manage Inventory Item' , 
            'Manage Inventory Item' , 
            'manage_inventory', 
            "lh-add-inventory-stock",
            array("inventory",'manageStock')
        );

        add_submenu_page(
            null,
            'View Inventory Item' , 
            'View Inventory Item' , 
            'manage_inventory', 
            "lh-add-inventory-view",
            array("inventory",'view')
        );

        add_submenu_page(
            "lh-inventory",
            "Categories",
            "Categories",
            "manage_inventory_category",
            "lh-category-inventory",
            array("inventory",'categories')
        );

        add_submenu_page(
            "lh-inventory",
            "Reports",
            "Reports",
            "manage_inventory_report",
            "lh-report-inventory",
            array("inventory",'report')
        );

        add_menu_page(
            "LH-HMS Settings",
            "LH-HMS Settings",
            "manage_settings",
            "lh-settings",
            array("settings",'manage'),
            "dashicons-admin-tools",'2.2.9',
            6
        );
    }

    public static function lh_install () {
        //setup tables
        $users                  = new users;
        $inventory              = new inventory;
        $inventory_used         = new inventory_used;
        $inventory_count        = new inventory_count;
        $inventory_category     = new inventory_category;

        $patient                = new patient;
        $patient_medication     = new patient_medication;
        $visitors               = new visitors;
        $billing                = new billing;
        $billing_component      = new billing_component;
        $invoice                = new invoice;
        $invoiceLog             = new invoiceLog;

        $appointments           = new appointments;
        $appointments_history   = new appointments_history;
        $vitals                 = new vitals;
        
        $clinic_post_op         = new clinic_post_op;
        $clinic_fluid_balance   = new clinic_fluid_balance;
        $clinic_continuation_sheet  = new clinic_continuation_sheet;
        $clinic_medication      =  new clinic_medication;
        $clinic_doctors_report  = new clinic_doctors_report;
        $settings               = new settings;

        $users->initialize_table();
        $users->initialize_roles_table();
        $inventory->initialize_table();
        $inventory_used->initialize_table();
        $inventory_count->initialize_table();
        $inventory_category->initialize_table();
        $patient->initialize_table();
        $patient_medication->initialize_table();
        $visitors->initialize_table();
        $billing->initialize_table();
        $billing_component->initialize_table();
        $invoice->initialize_table();
        $invoiceLog->initialize_table();
        $appointments->initialize_table();
        $appointments_history->initialize_table();
        $vitals->initialize_table();
        $clinic_post_op->initialize_table();
        $clinic_fluid_balance->initialize_table();
        $clinic_continuation_sheet->initialize_table();
        $clinic_medication->initialize_table();
        $clinic_doctors_report->initialize_table();
        $settings->initialize_table();

        //add user roles
        //add admin
		add_role(
			'lekki_hill_admin',
			__( 'LekkiHill Admin' ),
			array(
				'read'		=> true
			)
        );
        
        $lekkihill_admin = get_role( "lekki_hill_admin" );
        $lekkihill_admin->add_cap( 'manage_patient' );
        $lekkihill_admin->add_cap( 'manage_clinic' );
        $lekkihill_admin->add_cap( 'manage_clinic_massage' );
        $lekkihill_admin->add_cap( 'manage_medication' );
        $lekkihill_admin->add_cap( 'manage_visitors' );
        $lekkihill_admin->add_cap( 'manage_inventory' );
        $lekkihill_admin->add_cap( 'mamange_accounts' );
        $lekkihill_admin->add_cap( 'manage_settings' );
        $lekkihill_admin->add_cap( 'manage_patient_report' );
        $lekkihill_admin->add_cap( 'mamange_accounts_report' );
        $lekkihill_admin->add_cap( 'manage_inventory_report' );
        $lekkihill_admin->add_cap( 'manage_inventory_category' );
        $lekkihill_admin->add_cap( 'manage_woocommerce' );
        $lekkihill_admin->add_cap( 'view_woocommerce_reports' );
        $lekkihill_admin->add_cap( 'manage_patient_records' );

        $users->createAdminType(
            "LekkiHill Admin", 
            "lekki_hill_admin",
            ["read" => 1,"write" => 1,"modify" => 1],
            ['manage_patient', 'manage_medication', 'manage_clinic', 'manage_clinic_massage', 'manage_inventory', 'manage_patient_report', 'manage_patient_records', 'manage_visitors', 'mamange_accounts', 'mamange_accounts_report', 'manage_inventory_report', 'manage_inventory_category', 'manage_settings', 'manage_woocommerce', 'view_woocommerce_reports' ]
         );

        //add doctors
		add_role(
			'lekki_hill_doctor',
			__( 'LekkiHill Doctor' ),
			array(
				'read'		=> true
			)
        );
        
        $lekkihill_doctor = get_role( "lekki_hill_doctor" );
        $lekkihill_doctor->add_cap( 'manage_patient' );
        $lekkihill_doctor->add_cap( 'manage_medication' );
        $lekkihill_doctor->add_cap( 'manage_clinic' );
        $lekkihill_doctor->add_cap( 'manage_clinic_massage' );
        $lekkihill_doctor->add_cap( 'manage_inventory' );
        $lekkihill_doctor->add_cap( 'manage_patient_report' );
        $lekkihill_doctor->add_cap( 'manage_patient_records' );
        $lekkihill_doctor->add_cap( 'manage_visitors' );
        $lekkihill_doctor->add_cap( 'mamange_accounts' );
        $lekkihill_doctor->add_cap( 'mamange_accounts_report' );
        $lekkihill_doctor->add_cap( 'manage_inventory_report' );
        $lekkihill_doctor->add_cap( 'manage_inventory_category' );

        $users->createAdminType(
             "LekkiHill Doctor", 
             "lekki_hill_doctor",
             ["read" => 1,"write" => 1,"modify" => 1],
             ['manage_patient', 'manage_medication', 'manage_clinic', 'manage_clinic_massage', 'manage_inventory', 'manage_patient_report', 'manage_patient_records', 'manage_visitors', 'mamange_accounts', 'mamange_accounts_report', 'manage_inventory_report', 'manage_inventory_category']
        );

        //add nurses
		add_role(
			'lekki_hill_nurses',
			__( 'LekkiHill Nurse' ),
			array(
				'read'		=> true
			)
        );
        
        $lekkihill_nurse = get_role( "lekki_hill_nurses" );
        $lekkihill_nurse->add_cap( 'manage_patient' );
        $lekkihill_nurse->add_cap( 'manage_medication' );
        $lekkihill_nurse->add_cap( 'manage_clinic' );
        $lekkihill_nurse->add_cap( 'manage_clinic_massage' );
        $lekkihill_nurse->add_cap( 'manage_inventory' );
        $lekkihill_nurse->add_cap( 'mamange_accounts' );
        $lekkihill_nurse->add_cap( 'manage_patient_report' );
        $lekkihill_nurse->add_cap( 'manage_woocommerce' );
        $lekkihill_nurse->add_cap( 'view_woocommerce_reports' );
        $lekkihill_nurse->add_cap( 'manage_patient_records' );
        
        $users->createAdminType(
            "LekkiHill Nurse", 
            "lekki_hill_nurses",
            ["read" => 1,"write" => 1,"modify" => 1],
            ['manage_patient', 'manage_medication', 'manage_clinic', 'manage_clinic_massage', 'manage_inventory', 'mamange_accounts', 'manage_patient_report', 'manage_woocommerce', 'view_woocommerce_reports', 'manage_patient_records']
        );

        //add nurses
		add_role(
			'lekki_hill_massage',
			__( 'LekkiHill Masseur' ),
			array(
				'read'		=> true
			)
        );
        
        $lekkihill_masseur = get_role( "lekki_hill_massage" );
        $lekkihill_masseur->add_cap( 'manage_clinic_massage' );
        $lekkihill_masseur->add_cap( 'manage_patient' );
        $lekkihill_masseur->add_cap( 'manage_medication' );
        $lekkihill_masseur->add_cap( 'manage_clinic' );
        $lekkihill_masseur->add_cap( 'manage_patient_report' );

        $users->createAdminType(
            "LekkiHill Masseur", 
            "lekki_hill_massage",
            ["read" => 1,"write" => 1,"modify" => 1],
            ['manage_clinic_massage', 'manage_patient', 'manage_medication', 'manage_clinic', 'manage_patient_report']
        );

        //add pharmacy
		add_role(
			'lekki_hill_pharmacy',
			__( 'LekkiHill Pharmacy' ),
			array(
				'read'		=> true
			)
        );
        
        $lekkihill_pharm = get_role( "lekki_hill_pharmacy" );
        $lekkihill_pharm->add_cap( 'manage_clinic' );
        $lekkihill_pharm->add_cap( 'manage_medication' );
        $lekkihill_pharm->add_cap( 'manage_inventory' );
        $lekkihill_pharm->add_cap( 'manage_woocommerce' );
        $lekkihill_pharm->add_cap( 'view_woocommerce_reports' );
		add_role(
			'lekki_hill_pharmacy_read_only',
			__( 'LekkiHill Pharmacy (Read-Only)' ),
			array(
				'read'		=> true
			)
        );
        
        $lekki_hill_pharmacy_read_only = get_role( "lekki_hill_pharmacy_read_only" );
        $lekki_hill_pharmacy_read_only->add_cap( 'manage_clinic' );
        $lekki_hill_pharmacy_read_only->add_cap( 'manage_medication' );
        $lekki_hill_pharmacy_read_only->add_cap( 'manage_inventory' );
        $lekki_hill_pharmacy_read_only->add_cap( 'manage_woocommerce' );
        $lekki_hill_pharmacy_read_only->add_cap( 'view_woocommerce_reports' );
		add_role(
			'lekki_hill_pharmacy_full',
			__( 'LekkiHill Pharmacy [Full)' ),
			array(
				'read'		=> true
			)
        );
        
        $lekki_hill_pharmacy_full = get_role( "lekki_hill_pharmacy_full" );
        $lekki_hill_pharmacy_full->add_cap( 'manage_clinic' );
        $lekki_hill_pharmacy_full->add_cap( 'manage_medication' );
        $lekki_hill_pharmacy_full->add_cap( 'manage_inventory' );
        $lekki_hill_pharmacy_full->add_cap( 'manage_woocommerce' );
        $lekki_hill_pharmacy_full->add_cap( 'view_woocommerce_reports' );

        $users->createAdminType(
            "LekkiHill Pharmacy [Full)", 
            "lekki_hill_pharmacy_full",
            ["read" => 1,"write" => 1,"modify" => 1],
            ['manage_clinic', 'manage_medication', 'manage_inventory', 'manage_woocommerce', 'view_woocommerce_reports']
         );
         $users->createAdminType(
             "LekkiHill Pharmacy", 
             "lekki_hill_pharmacy",
             ["read" => 1,"write" => 1,"modify" => 0],
             ['manage_clinic', 'manage_medication', 'manage_inventory', 'manage_woocommerce', 'view_woocommerce_reports']
          );
        $users->createAdminType(
            "LekkiHill Pharmacy (Read-Only)", 
            "lekki_hill_pharmacy_read_only",
            ["read" => 1,"write" => 0,"modify" => 0],
            ['manage_clinic', 'manage_medication', 'manage_inventory', 'manage_woocommerce', 'view_woocommerce_reports']
         );

        //add front desk
		add_role(
			'lekki_hill_front_desk',
			__( 'LekkiHill Front Desk' ),
			array(
				'read'		=> true
			)
        );
        
        $lekki_hill_front_desk = get_role( "lekki_hill_front_desk" );
        $lekki_hill_front_desk->add_cap( 'manage_patient' );
        $lekki_hill_front_desk->add_cap( 'manage_clinic' );
        $lekki_hill_front_desk->add_cap( 'manage_visitors' );
        $lekki_hill_front_desk->add_cap( 'manage_woocommerce' );

        $users->createAdminType(
            "LekkiHill Front Desk", 
            "lekki_hill_front_desk",
            ["read" => 1,"write" => 1,"modify" => 0],
            ['manage_patient', 'manage_clinic', 'manage_visitors', 'manage_woocommerce']
        );

        //add accounts
		add_role(
			'lekki_hill_accounts',
			__( 'LekkiHill Accounts' ),
			array(
				'read'		=> true
			)
        );
        
        $lekki_hill_accounts = get_role( "lekki_hill_accounts" );
        $lekki_hill_accounts->add_cap( 'manage_patient' );
        $lekki_hill_accounts->add_cap( 'manage_clinic' );
        $lekki_hill_accounts->add_cap( 'mamange_accounts' );
        $lekki_hill_accounts->add_cap( 'mamange_accounts_report' );
        $lekki_hill_accounts->add_cap( 'view_woocommerce_reports' );
		add_role(
			'lekki_hill_accounts_full',
			__( 'LekkiHill Accounts [Full)' ),
			array(
				'read'		=> true
			)
        );
        
        $lekki_hill_accounts_full = get_role( "lekki_hill_accounts_full" );
        $lekki_hill_accounts_full->add_cap( 'manage_patient' );
        $lekki_hill_accounts_full->add_cap( 'manage_clinic' );
        $lekki_hill_accounts_full->add_cap( 'mamange_accounts' );
        $lekki_hill_accounts_full->add_cap( 'mamange_accounts_report' );
        $lekki_hill_accounts_full->add_cap( 'view_woocommerce_reports' );
		add_role(
			'lekki_hill_accounts_read_only',
			__( 'LekkiHill Accounts [Read-Only)' ),
			array(
				'read'		=> true
			)
        );
        
        $lekki_hill_accounts_read_only = get_role( "lekki_hill_accounts_read_only" );
        $lekki_hill_accounts_read_only->add_cap( 'manage_patient' );
        $lekki_hill_accounts_read_only->add_cap( 'manage_clinic' );
        $lekki_hill_accounts_read_only->add_cap( 'mamange_accounts' );
        $lekki_hill_accounts_read_only->add_cap( 'mamange_accounts_report' );
        $lekki_hill_accounts_read_only->add_cap( 'view_woocommerce_reports' );

        $users->createAdminType(
            "LekkiHill Accounts [Full)", 
            "lekki_hill_accounts_full",
            ["read" => 1,"write" => 1,"modify" => 1],
            ['manage_patient', 'manage_clinic', 'mamange_accounts', 'mamange_accounts_report', 'view_woocommerce_reports']
         );
         $users->createAdminType(
             "LekkiHill Accounts", 
             "lekki_hill_accounts",
             ["read" => 1,"write" => 1,"modify" => 0],
             ['manage_patient', 'manage_clinic', 'mamange_accounts', 'mamange_accounts_report', 'view_woocommerce_reports']
          );
        $users->createAdminType(
            "LekkiHill Accounts (Read-Only)", 
            "lekki_hill_accounts_read_only",
            ["read" => 1,"write" => 0,"modify" => 0],
            ['manage_patient', 'manage_clinic', 'mamange_accounts', 'mamange_accounts_report', 'view_woocommerce_reports']
         );
        
        //add inventory
		add_role(
			'lekki_hill_inventory_full',
			__( 'LekkiHill Inventory [Full)' ),
			array(
				'read'		=> true,
				'modify'    => true,
				'write'		=> true
			)
        );
        
        $lekki_hill_inventory_full = get_role( "lekki_hill_inventory_full" );
        $lekki_hill_inventory_full->add_cap( 'manage_inventory' );
        $lekki_hill_inventory_full->add_cap( 'manage_inventory_report' );
        $lekki_hill_inventory_full->add_cap( 'manage_woocommerce' );

		add_role(
			'lekki_hill_inventory_read_only',
			__( 'LekkiHill Inventory' ),
			array(
				'read'		=> true
			)
        );
        
        $lekki_hill_inventory_read_only = get_role( "lekki_hill_inventory_read_only" );
        $lekki_hill_inventory_read_only->add_cap( 'manage_inventory' );
        $lekki_hill_inventory_read_only->add_cap( 'manage_inventory_report' );
        $lekki_hill_inventory_read_only->add_cap( 'manage_woocommerce' );

		add_role(
			'lekki_hill_inventory',
			__( 'LekkiHill Inventory (Read-Only)' ),
			array(
				'read'		=> true,
				'write'		=> true
			)
        );
        
        $lekki_hill_inventory = get_role( "lekki_hill_inventory" );
        $lekki_hill_inventory->add_cap( 'manage_inventory' );
        $lekki_hill_inventory->add_cap( 'manage_inventory_report' );
        $lekki_hill_inventory->add_cap( 'manage_woocommerce' );

        $users->createAdminType(
            "LekkiHill Inventory [Full)", 
            "lekki_hill_inventory_full",
            ["read" => 1,"write" => 1,"modify" => 1],
            ['manage_inventory', 'manage_inventory_report', 'manage_woocommerce']
         );
         $users->createAdminType(
             "LekkiHill Inventory", 
             "lekki_hill_inventory",
             ["read" => 1,"write" => 1,"modify" => 0],
             ['manage_inventory', 'manage_inventory_report', 'manage_woocommerce']
          );
        $users->createAdminType(
            "LekkiHill Inventory (Read-Only)", 
            "lekki_hill_inventory_read_only",
            ["read" => 1,"write" => 0,"modify" => 0],
            ['manage_inventory', 'manage_inventory_report', 'manage_woocommerce']
         );

		//add roles to admin
		$administrator		= get_role('administrator');
        $administrator->add_cap( 'manage_patient' );
        $administrator->add_cap( 'manage_clinic' );
        $administrator->add_cap( 'manage_visitors' );
        $administrator->add_cap( 'manage_medication' );
        $administrator->add_cap( 'manage_inventory' );
        $administrator->add_cap( 'mamange_accounts' );
        $administrator->add_cap( 'manage_settings' );
        $administrator->add_cap( 'manage_patient_report' );
        $administrator->add_cap( 'mamange_accounts_report' );
        $administrator->add_cap( 'manage_inventory_report' );
        $administrator->add_cap( 'manage_inventory_category' );
        $administrator->add_cap( 'manage_patient_records' );
    }

    public static function lh_deactivate() {
        self::remove_cap();
        self::remove_role();

        $users  = new users;

        $users->clear_table();
        $users->clear_role_table();
    }

    public static function lh_uninstall() {
        $inventory          = new inventory;
        $inventory_used     = new inventory_used;
        $inventory_count    = new inventory_count;
        $inventory_category = new inventory_category;
        $patient                = new patient;
        $patient_medication     = new patient_medication;
        $visitors               = new visitors;
        $billing                = new billing;
        $billing_component      = new billing_component;
        $invoice                = new invoice;
        $invoiceLog             = new invoiceLog;
        $appointments           = new appointments;
        $appointments_history   = new appointments_history;
        $vitals                 = new vitals;
        $clinic_post_op         = new clinic_post_op;
        $clinic_fluid_balance   = new clinic_fluid_balance;
        $clinic_continuation_sheet = new clinic_continuation_sheet;
        $clinic_medication      = new clinic_medication;
        $clinic_doctors_report  = new clinic_doctors_report;
        $settings               = new settings;
        
        $inventory->delete_table();
        $inventory_used->delete_table();
        $inventory_count->delete_table();
        $inventory_category->delete_table();
        $patient->delete_table();
        $patient_medication->delete_table();
        $visitors->delete_table();
        $billing->delete_table();
        $billing_component->delete_table();
        $invoice->delete_table();
        $invoiceLog->delete_table();
        $appointments->delete_table();
        $appointments_history->delete_table();
        $vitals->delete_table();
        $clinic_post_op->delete_table();
        $clinic_fluid_balance->delete_table();
        $clinic_continuation_sheet->delete_table();
        $clinic_medication->delete_table();
        $clinic_doctors_report->delete_table();
        $settings->delete_table();
    }

    // Remove the plugin-specific custom capability
    private function remove_cap() {
        $roles = get_editable_roles();
        foreach ($GLOBALS['wp_roles']->role_objects as $key => $role) {
            if (isset($roles[$key]) && $role->has_cap('manage_patient')) {
                $role->remove_cap('manage_patient');
            }
            if (isset($roles[$key]) && $role->has_cap('manage_medication')) {
                $role->remove_cap('manage_medication');
            }
            if (isset($roles[$key]) && $role->has_cap('manage_patient_report')) {
                $role->remove_cap('manage_patient_report');
            }
            if (isset($roles[$key]) && $role->has_cap('manage_inventory')) {
                $role->remove_cap('manage_inventory');
            }
            if (isset($roles[$key]) && $role->has_cap('manage_inventory_report')) {
                $role->remove_cap('manage_inventory_report');
            }
            if (isset($roles[$key]) && $role->has_cap('manage_inventory_category')) {
                $role->remove_cap('manage_inventory_category');
            }
            if (isset($roles[$key]) && $role->has_cap('mamange_accounts')) {
                $role->remove_cap('mamange_accounts');
            }
            if (isset($roles[$key]) && $role->has_cap('mamange_accounts_report')) {
                $role->remove_cap('mamange_accounts_report');
            }
            if (isset($roles[$key]) && $role->has_cap('manage_clinic_massage')) {
                $role->remove_cap('manage_clinic_massage');
            }
            if (isset($roles[$key]) && $role->has_cap('manage_patient_records')) {
                $role->remove_cap('manage_patient_records');
            }
        }
    }
	
	//remove plugin specific roles
	private function remove_role() {
		if ( get_role('lekki_hill_accounts') ){
			remove_role( 'lekki_hill_accounts' );
		}
		if ( get_role('lekki_hill_inventory') ){
			remove_role( 'lekki_hill_inventory' );
		}
		if ( get_role('lekki_hill_front_desk') ){
			remove_role( 'lekki_hill_front_desk' );
		}
		if ( get_role('lekki_hill_admin') ){
			remove_role( 'lekki_hill_admin' );
		}
		if ( get_role('lekki_hill_nurses') ){
			remove_role( 'lekki_hill_nurses' );
		}
		if ( get_role('lekki_hill_pharmacy') ){
			remove_role( 'lekki_hill_pharmacy' );
		}
    }
    
	//external scripts and CSS
	public static function admin_styles_and_script() {
		wp_enqueue_script( 'load-fa', 'https://kit.fontawesome.com/f905a65f30.js' );
		wp_enqueue_style( 'load-select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css' );
		wp_enqueue_script( 'load-select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js' );
		wp_enqueue_style( 'load-datatables-css', 'https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css' );
        wp_enqueue_script( 'load-datatables-js', 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js' );
        wp_enqueue_script( 'editable-select-js', 'https://rawgit.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js' );
        wp_enqueue_style( 'editable-select-css', 'https://rawgit.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css' );
        
        wp_enqueue_script( 'bootstrap-design-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js' );
        wp_enqueue_style( 'bootstrap-design-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );
        
		wp_enqueue_style( 'load-datepicker-css', plugins_url( 'css/jquery.datetimepicker.css' , __FILE__ ) );
        wp_enqueue_script( 'load-datepicker-js', plugins_url( 'js/jquery.datetimepicker.js' , __FILE__ ));
        wp_enqueue_script('suggest');

        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-autocomplete' );
    }
    
	//add settings link to the pluginPage
	public static function lh_plugin_link( $actions, $plugin_file ) {
		static $plugin;
		if (!isset($plugin))
		$plugin		=	plugin_basename(__FILE__);
		if ($plugin == $plugin_file) {
			$settings	=	array('settings' => '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=lh-settings') ) .'">Settings</a>');
			$actions	=	array_merge($settings, $actions);
		}
		
		return $actions;
	}
}
?>