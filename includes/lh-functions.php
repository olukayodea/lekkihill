<?php
class main {
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

        //PUT billing route
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
    public static function  lh_add_menu() {
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

        add_submenu_page(
            "lh-manage-patient",
            "Reports",
            "Reports",
            "manage_patient_report",
            "lh-patient-billing",
            array(__CLASS__,'cpc_create')
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
            "Retrieve Records",
            "Retrieve Records",
            "manage_clinic",
            "lh-manage-clinic",
            array('clinic','manage')
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
        $billing                = new billing;
        $billing_component      = new billing_component;
        $invoice                = new invoice;

        $appointments           = new appointments;
        $appointments_history   = new appointments_history;
        $vitals                 = new vitals;
        
        $clinic_post_op         = new clinic_post_op;
        $clinic_fluid_balance   = new clinic_fluid_balance;
        $clinic_continuation_sheet  = new clinic_continuation_sheet;
        $clinic_medication      =  new clinic_medication;

        $users->initialize_table();
        $inventory->initialize_table();
        $inventory_used->initialize_table();
        $inventory_count->initialize_table();
        $inventory_category->initialize_table();
        $patient->initialize_table();
        $billing->initialize_table();
        $billing_component->initialize_table();
        $invoice->initialize_table();
        $appointments->initialize_table();
        $appointments_history->initialize_table();
        $vitals->initialize_table();
        $clinic_post_op->initialize_table();
        $clinic_fluid_balance->initialize_table();
        $clinic_continuation_sheet->initialize_table();
        $clinic_medication->initialize_table();

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
        $lekkihill_admin->add_cap( 'manage_inventory' );
        $lekkihill_admin->add_cap( 'mamange_accounts' );
        $lekkihill_admin->add_cap( 'manage_settings' );
        $lekkihill_admin->add_cap( 'manage_patient_report' );
        $lekkihill_admin->add_cap( 'mamange_accounts_report' );
        $lekkihill_admin->add_cap( 'manage_inventory_report' );
        $lekkihill_admin->add_cap( 'manage_inventory_category' );

        //add doctors
		add_role(
			'lekki_hill_doctor',
			__( 'LekkiHill Doctor' ),
			array(
				'read'		=> true
			)
        );
        
        $lekkihill_admin = get_role( "lekki_hill_doctor" );
        $lekkihill_admin->add_cap( 'manage_patient' );
        $lekkihill_admin->add_cap( 'manage_clinic' );
        $lekkihill_admin->add_cap( 'manage_inventory' );
        $lekkihill_admin->add_cap( 'mamange_accounts' );
        $lekkihill_admin->add_cap( 'manage_patient_report' );

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
        $lekki_hill_accounts->add_cap( 'mamange_accounts' );
        $lekki_hill_accounts->add_cap( 'mamange_accounts_report' );
        
        //add inventory
		add_role(
			'lekki_hill_inventory',
			__( 'LekkiHill Inventory' ),
			array(
				'read'		=> true
			)
        );
        
        $lekki_hill_inventory = get_role( "lekki_hill_inventory" );
        $lekki_hill_inventory->add_cap( 'manage_inventory' );
        $lekki_hill_inventory->add_cap( 'manage_inventory_report' );

		//add roles to admin
		$administrator		= get_role('administrator');
        $administrator->add_cap( 'manage_patient' );
        $administrator->add_cap( 'manage_clinic' );
        $administrator->add_cap( 'manage_inventory' );
        $administrator->add_cap( 'mamange_accounts' );
        $administrator->add_cap( 'manage_settings' );
        $administrator->add_cap( 'manage_patient_report' );
        $administrator->add_cap( 'mamange_accounts_report' );
        $administrator->add_cap( 'manage_inventory_report' );
        $administrator->add_cap( 'manage_inventory_category' );
    }

    public static function lh_deactivate() {
        self::remove_cap();
        self::remove_role();

        $users                  = new users;
        $inventory              = new inventory;
        $inventory_used         = new inventory_used;
        $inventory_count        = new inventory_count;
        $inventory_category     = new inventory_category;
        $patient                = new patient;
        $billing                = new billing;
        $billing_component      = new billing_component;
        $invoice                = new invoice;
        $appointments           = new appointments;
        $appointments_history   = new appointments_history;
        $vitals                 = new vitals;
        $clinic_post_op         = new clinic_post_op;
        $clinic_fluid_balance   = new clinic_fluid_balance;
        $clinic_continuation_sheet  = new clinic_continuation_sheet;
        $clinic_medication      = new clinic_medication;

        $users->clear_table();
        // $inventory->delete_table();
        // $inventory_used->delete_table();
        // $inventory_count->delete_table();
        // $inventory_category->delete_table();
        // $patient->delete_table();
        // $billing->delete_table();
        // $billing_component->delete_table();
        // $invoice->delete_table();
        // $appointments->delete_table();
        // $appointments_history->delete_table();
        // $vitals->delete_table();
        // $clinic_post_op->delete_table();
        $clinic_fluid_balance->delete_table();
        // $clinic_continuation_sheet->delete_table();
        $clinic_medication->delete_table();
    }

    public static function lh_uninstall() {
        $inventory          = new inventory;
        $inventory_used     = new inventory_used;
        $inventory_count    = new inventory_count;
        $inventory_category = new inventory_category;
        $patient                = new patient;
        $billing                = new billing;
        $billing_component      = new billing_component;
        $invoice                = new invoice;
        $appointments           = new appointments;
        $appointments_history   = new appointments_history;
        $vitals                 = new vitals;
        $clinic_post_op         = new clinic_post_op;
        $clinic_fluid_balance   = new clinic_fluid_balance;
        $clinic_continuation_sheet = new clinic_continuation_sheet;
        $clinic_medication      = new clinic_medication;
        
        $inventory->delete_table();
        $inventory_used->delete_table();
        $inventory_count->delete_table();
        $inventory_category->delete_table();
        $patient->delete_table();
        $billing->delete_table();
        $billing_component->delete_table();
        $invoice->delete_table();
        $appointments->delete_table();
        $appointments_history->delete_table();
        $vitals->delete_table();
        $clinic_post_op->delete_table();
        $clinic_fluid_balance->delete_table();
        $clinic_continuation_sheet->delete_table();
        $clinic_medication->delete_table();
    }

    // Remove the plugin-specific custom capability
    private function remove_cap() {
        $roles = get_editable_roles();
        foreach ($GLOBALS['wp_roles']->role_objects as $key => $role) {
            if (isset($roles[$key]) && $role->has_cap('manage_patient')) {
                $role->remove_cap('manage_patient');
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
        
		wp_enqueue_style( 'load-main-css', plugins_url( 'css/main.css' , __FILE__ ) );
		wp_enqueue_style( 'load-datepicker-css', plugins_url( 'css/jquery.datetimepicker.css' , __FILE__ ) );
        wp_enqueue_script( 'load-datepicker-js', plugins_url( 'js/jquery.datetimepicker.js' , __FILE__ ));
        wp_enqueue_script('suggest');

        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-autocomplete' );
    }
    
	//add settings link to the pluginPage
	function lh_plugin_link( $actions, $plugin_file ) {
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