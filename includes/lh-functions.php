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
        //url = https://lekkihill.com/wp-json/api/billing;
        register_rest_route( 'api', 'billing',array(
            'methods'  => 'POST',
            'callback' => array("billing",'create_api_component')
        ));

        //PUT billing route
        //update component
        //url = https://lekkihill.com/wp-json/api/billing;
        register_rest_route( 'api', 'billing',array(
            'methods'  => 'PUT',
            'callback' => array("billing",'create_api_component')
        ));
        //change component status
        //url = https://lekkihill.com/wp-json/api/billing/status;
        register_rest_route( 'api', 'billing/status',array(
            'methods'  => 'PUT',
            'callback' => array("billing",'change_api_component_status')
        ));

        //GET billing route
        //list all component
        //url = https://lekkihill.com/wp-json/api/billing/list;
        register_rest_route( 'api', 'billing/list',array(
            'methods'  => 'GET',
            'callback' => array("billing",'list_api_component')
        ));
        //get one component
        //url = https://lekkihill.com/wp-json/api/billing/ID;
        register_rest_route( 'api', 'billing/(?P<component_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("billing",'read_api_component')
        ));

        //DELETE billing route
        //delete component
        //url = https://lekkihill.com/wp-json/api/billing/ID;
        register_rest_route( 'api', 'billing/(?P<component_id>\d+)',array(
            'methods'  => 'DELETE',
            'callback' => array("billing",'delete_api_component')
        ));


        //GET Patient route
        //search component
        //url = https://lekkihill.com/wp-json/api/patient/search;
        register_rest_route( 'api', 'patient/search',array(
            'methods'  => 'GET',
            'callback' => array("patient",'get_patient_suggest_api')
        ));
        //get patient data
        //url = https://lekkihill.com/wp-json/api/patient/search/1;
        register_rest_route( 'api', 'patient/(?P<patient_id>\d+)',array(
            'methods'  => 'GET',
            'callback' => array("patient",'read_api_component')
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
    public function  lh_add_menu() {
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
            "Create New Invoice",
            "Create Invoice",
            "mamange_accounts",
            "lh-billing-create",
            array("billing",'create')
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

    function lh_install () {
        //setup tables
        $users                  = new users;
        $inventory              = new inventory;
        $inventory_used         = new inventory_used;
        $inventory_count        = new inventory_count;
        $inventory_category     = new inventory_category;

        $patient                = new patient;
        $billing                = new billing;
        $billing_component      = new billing_component;

        $appointments           = new appointments;
        $appointments_history   = new appointments_history;

        // $users->initialize_table();
        $inventory->initialize_table();
        $inventory_used->initialize_table();
        $inventory_count->initialize_table();
        $inventory_category->initialize_table();
        $patient->initialize_table();
        $billing->initialize_table();
        $billing_component->initialize_table();
        $appointments->initialize_table();
        $appointments_history->initialize_table();
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

    public function lh_deactivate() {
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
        $appointments           = new appointments;
        $appointments_history   = new appointments_history;

        //$users->clear_table();
        // $inventory->delete_table();
        // $inventory_used->delete_table();
        // $inventory_count->delete_table();
        // $inventory_category->delete_table();
        // $patient->delete_table();
        // $billing->delete_table();
        // $billing_component->delete_table();
        // $appointments->delete_table();
        // $appointments_history->delete_table();
    }

    public function lh_uninstall() {
        $inventory          = new inventory;
        $inventory_used     = new inventory_used;
        $inventory_count    = new inventory_count;
        $inventory_category = new inventory_category;
        $patient                = new patient;
        $billing                = new billing;
        $billing_component      = new billing_component;
        $appointments           = new appointments;
        $appointments_history   = new appointments_history;
        
        $inventory->delete_table();
        $inventory_used->delete_table();
        $inventory_count->delete_table();
        $inventory_category->delete_table();
        $patient->delete_table();
        $billing->delete_table();
        $billing_component->delete_table();
        $appointments->delete_table();
        $appointments_history->delete_table();
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
	function admin_styles_and_script() {
		wp_enqueue_script( 'load-fa', 'https://kit.fontawesome.com/f905a65f30.js' );
		wp_enqueue_style( 'load-select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css' );
		wp_enqueue_script( 'load-select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js' );
		wp_enqueue_style( 'load-datatables-css', 'https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css' );
        wp_enqueue_script( 'load-datatables-js', 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js' );
        wp_enqueue_script( 'editable-select-js', 'https://rawgit.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js' );
		wp_enqueue_style( 'editable-select-css', 'https://rawgit.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css' );
        
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