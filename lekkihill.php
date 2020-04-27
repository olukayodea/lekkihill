<?php
/*
Plugin Name: LekkiHill HMS
Plugin URI: https://lekkihill.com/
Description: Custom plugin to manage clinical operations of the Lekki Hill hospital.
Version: 1.0.0
Author: Linnkstec
Author URI: https://linnkstec.com/
License: GPLv2 or later
Text Domain: lekkihill.com
*/
global $wpdb;
//$key = "1524849520";
//$token = "MTUyNDg0OTUyMF8xMTI0MVJDTDhCMjk4QlcwTUhQ";

define( 'LH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define(  "table_name_prefix", $wpdb->prefix."lekkihill_" );
if (defined('WP_CONTENT_DIR') && !defined('WP_INCLUDE_DIR')){
    define('WP_INCLUDE_DIR', str_replace('wp-content', 'wp-includes', WP_CONTENT_DIR));
 }
//common functions and utilities
require_once LH_PLUGIN_DIR . 'includes/controllers/common.php';
$common = new common;

require_once LH_PLUGIN_DIR . 'includes/utilities/pdf/tcpdf.php';
$pdf = new TCPDF("P", "mm", "A4", true, 'UTF-8', false);

//database
require_once LH_PLUGIN_DIR . 'includes/database/main.php';
$database = new database;
$db       = $database->connect();

//classes and functions
require_once LH_PLUGIN_DIR . 'includes/controllers/settings.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/users.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/patient.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/clinic.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/billing.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/invoice.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/inventory.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/appointments.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/appointments_history.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/vitals.php';
$settings               = new settings;
$users                  = new users;
$patient                = new patient;
$clinic                 = new clinic;
$billing                = new billing;
$invoice                = new invoice;
$inventory              = new inventory;
$appointment            = new appointments;
$appointments_history   = new appointments_history;
$vitals                 = new vitals;

//main functions
require_once LH_PLUGIN_DIR . 'includes/lh-functions.php';

//list of capabilities and pages
$capabilityArray = array();
$capabilityArray['manage_patient'] = array(
    "Patients"=> array("Search", "Add"),
    "Appointments"=> array("Search", "Book Appointment","Today","Upcoming","Past")
);

$capabilityArray['manage_patient_report'] = array(
    "Patients"=> array("Reports")
);
$capabilityArray['manage_inventory'] = array(
    "Inventory"=> array("List Inventory","Add Inventory","List Inventory","Manage Inventory Item", "View Inventory Item")
);
$capabilityArray['manage_inventory_report'] = array(
    "Inventory"=> array("Reports")
);
$capabilityArray['manage_inventory_category'] = array(
    "Inventory"=> array("Categories")
);

$capabilityArray['mamange_accounts'] = array(
    "Finance"=> array("Search", "Create New Invoice", "Components")
);
$capabilityArray['mamange_accounts_report'] = array(
    "Finance"=> array("Reports")
);
$capabilityArray['administrator'] = array(
    "Patients"=> array("Search", "Add", "Reports"),
    "Appointments"=> array("Search", "Book Appointment","Today","Upcoming","Past"),
    "Inventory"=> array("List Inventory","Add Inventory","List Inventory","Manage Inventory Item", "View Inventory Item", "Reports", "Categories"),
    "Finance"=> array("Search", "Create New Invoice", "Components", "Reports")
);

class mainClass extends main {
    function __construct() {
		if(isset($_GET['downloadInventoryCSV'])) {
            require_once WP_INCLUDE_DIR . '/pluggable.php';

            inventory::downloadReport();
			exit;
		}
		if(isset($_GET['downloadInventoryPDF'])) {
            require_once WP_INCLUDE_DIR . '/pluggable.php';

            inventory::print_report();
			exit;
		}
		if(isset($_GET['downloadItemCSV'])) {
            require_once WP_INCLUDE_DIR . '/pluggable.php';

            inventory::downloadView();
			exit;
		}
		if(isset($_GET['downloadItemPDF'])) {
            require_once WP_INCLUDE_DIR . '/pluggable.php';

            inventory::print_view();
			exit;
        }
        //add REST API
        add_action('rest_api_init', array( "main", 'apiRoutes' ) );
        //add amin menu on initialization
        add_action( 'admin_menu', array( "main", 'lh_add_menu' ) );
		//initialize the imported CDN based script
        add_action( 'admin_enqueue_scripts', array( "main", 'admin_styles_and_script' ));
        //create additional links in plugin menu 
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( "main", 'lh_plugin_link'), 10, 5 );
        
        //registration hooks
        register_activation_hook( __FILE__, array( $this, 'lh_install' ) );
        register_deactivation_hook( __FILE__, array( $this, 'lh_deactivate' ) );
        register_uninstall_hook( __FILE__, array( $this, 'lh_uninstall' ) );
    }
}

function app_output_buffer() {
	ob_start();
} // soi_output_buffer

add_action('init', 'app_output_buffer');

new mainClass;
?>