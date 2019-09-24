<?php
class main {
    //get all the  menus  and  submenu
    function  lh_add_menu() {
        add_menu_page(
            "Patients Record",
            "Patients Record",
            "manage_patient",
            "lh-manage-patient",
            array(__CLASS__,'cpc_create'),
            "dashicons-admin-home",'2.2.9'
        );

        add_submenu_page(
            "lh-manage-patient",
            "Search Patients",
            "Search Patients",
            "manage_patient",
            "lh-manage-patient",
            array(__CLASS__,'cpc_create')
        );

        add_submenu_page(
            "lh-manage-patient",
            "Add Patients",
            "Add Patients",
            "manage_patient",
            "lh-add-patient",
            array(__CLASS__,'cpc_create')
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
            "Appointments",
            "Appointments",
            "manage_patient",
            "lh-manage-appointments",
            array(__CLASS__,'cpc_create'),
            "dashicons-schedule",'2.2.9'
        );

        add_submenu_page(
            "lh-manage-appointments",
            "Search Patients",
            "Search Patients",
            "manage_patient",
            "lh-manage-appointments",
            array(__CLASS__,'cpc_create')
        );

        add_submenu_page(
            "lh-manage-appointments",
            "Book Appointment",
            "Book Appointment",
            "manage_patient",
            "lh-book-appointments",
            array(__CLASS__,'cpc_create')
        );

        add_submenu_page(
            "lh-manage-appointments",
            "Today",
            "Today",
            "manage_patient",
            "lh-today-appointments",
            array(__CLASS__,'cpc_create')
        );

        add_submenu_page(
            "lh-manage-appointments",
            "Upcoming",
            "Upcoming",
            "manage_patient",
            "lh-comming-appointments",
            array(__CLASS__,'cpc_create')
        );

        add_submenu_page(
            "lh-manage-appointments",
            "Past",
            "Past",
            "manage_patient",
            "lh-past-appointments",
            array(__CLASS__,'cpc_create')
        );

        add_menu_page(
            "Billing and Accounts",
            "Finance",
            "mamange_accounts",
            "lh-billing",
            array("billing",'search'),
            "dashicons-money",'2.2.9'
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
            "mamange_accounts",
            "lh-billing-report",
            array("billing",'report')
        );

        add_menu_page(
            "Inventory",
            "Inventory",
            "manage_inventory",
            "lh-inventory",
            array("inventory",'manage'),
            "dashicons-media-spreadsheet",'2.2.9'
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
    }

    function lh_install () {
        //setup tables
        $inventory  = new inventory;
        $inventory_used = new inventory_used;
        $inventory_count = new inventory_count;
        $inventory_category = new inventory_category;
        $patient    = new patient;
        $billing    = new billing;
        $billing_component = new billing_component;

        $inventory->initialize_table();
        $inventory_used->initialize_table();
        $inventory_count->initialize_table();
        $inventory_category->initialize_table();
        $patient->initialize_table();
        $billing->initialize_table();
        $billing_component->initialize_table();
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
        $lekkihill_admin->add_cap( 'manage_inventory' );
        $lekkihill_admin->add_cap( 'mamange_accounts' );
        $lekkihill_admin->add_cap( 'manage_patient_report' );
        $lekkihill_admin->add_cap( 'mamange_accounts_report' );
        $lekkihill_admin->add_cap( 'manage_inventory_report' );
        $lekkihill_admin->add_cap( 'manage_inventory_category' );

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
        $administrator->add_cap( 'manage_inventory' );
        $administrator->add_cap( 'mamange_accounts' );
        $administrator->add_cap( 'manage_patient_report' );
        $administrator->add_cap( 'mamange_accounts_report' );
        $administrator->add_cap( 'manage_inventory_report' );
        $administrator->add_cap( 'manage_inventory_category' );
    }

    public function lh_deactivate() {
        self::remove_cap();
        self::remove_role();

        $inventory          = new inventory;
        $inventory_used     = new inventory_used;
        $inventory_count    = new inventory_count;
        $inventory_category = new inventory_category;
        $patient            = new patient;
        $billing            = new billing;
        $billing_component  = new billing_component;

        //$inventory->delete_table();
        //$inventory_used->delete_table();
        //$inventory_count->delete_table();
        //$inventory_category->delete_table();
        $patient->delete_table();
        $billing->delete_table();
        $billing_component->delete_table();
    }

    public function lh_uninstall() {
        $inventory          = new inventory;
        $inventory_used     = new inventory_used;
        $inventory_count    = new inventory_count;
        $inventory_category = new inventory_category;
        $patient            = new patient;
        $billing            = new billing;
        $billing_component  = new billing_component;
        
        $inventory->delete_table();
        $inventory_used->delete_table();
        $inventory_count->delete_table();
        $inventory_category->delete_table();
        $patient->delete_table();
        $billing->delete_table();
        $billing_component->delete_table();
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
		wp_enqueue_script( 'load-datepicker-js', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js' );
		wp_enqueue_style( 'load-datepicker-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
	}
}
?>