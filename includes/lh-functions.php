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
            "lb-add-patient",
            array(__CLASS__,'cpc_create')
        );

        add_submenu_page(
            "lh-manage-patient",
            "Reports",
            "Reports",
            "manage_patient_report",
            "lb-patient-billing",
            array(__CLASS__,'cpc_create')
        );

        add_menu_page(
            "Billing and Accounts",
            "Billing and Accounts",
            "mamange_accounts",
            "lh-billing",
            array(__CLASS__,'cpc_create'),
            "dashicons-list-view",'2.2.9'
        );

        add_submenu_page(
            "lh-billing",
            "Reports",
            "Reports",
            "mamange_accounts_report",
            "lb-report-billing",
            array(__CLASS__,'cpc_create')
        );

        add_menu_page(
            "Manage Inventory",
            "Manage Inventory",
            "manage_inventory",
            "lh-inventory",
            array(__CLASS__,'cpc_create'),
            "dashicons-list-view",'2.2.9'
        );

        add_submenu_page(
            "lh-inventory",
            "List",
            "List",
            "manage_inventory",
            "lh-inventory",
            array(__CLASS__,'cpc_create')
        );

        add_submenu_page(
            "lh-inventory",
            "Add",
            "Add",
            "manage_inventory",
            "lb-add-inventory",
            array(__CLASS__,'cpc_create')
        );

        add_submenu_page(
            "lh-inventory",
            "Reports",
            "Reports",
            "manage_inventory_report",
            "lb-report-inventory",
            array(__CLASS__,'cpc_create')
        );
    }

    function lh_install () {
        //setup tables
        $inventory  = new inventory;
        global $patient;
        global $billing;

        $inventory->initialize_table();
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
    }

    public function lh_deactivate() {
        self::remove_cap();
        self::remove_role();
    }

    public function lh_uninstall() {
        global $inventory;
        $inventory->delete_table();
    }

    // Remove the plugin-specific custom capability
    private function remove_cap() {
        $roles = get_editable_roles();
        foreach ($GLOBALS['wp_roles']->role_objects as $key => $role) {
            if (isset($roles[$key]) && $role->has_cap('manage_patient')) {
                $role->remove_cap('manage_patient');
            }
            if (isset($roles[$key]) && $role->has_cap('manage_inventory')) {
                $role->remove_cap('manage_inventory');
            }
            if (isset($roles[$key]) && $role->has_cap('mamange_accounts')) {
                $role->remove_cap('mamange_accounts');
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
}
?>