<?php
/*
Plugin Name: LekkiHill HMS
Plugin URI: https://lekkihill.com/
Description: Custom plugin to manage clinical operations of the Lekki Hill hospital.
Version: 1.0.0
Author: Linnkstec
Author URI: https://linnkstec.com/
License: GPLv2 or later
Text Domain: akismet
*/
global $wpdb;

define( 'LH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define(  "table_name_prefix", $wpdb->prefix."lekkihill_", true );
if (defined('WP_CONTENT_DIR') && !defined('WP_INCLUDE_DIR')){
    define('WP_INCLUDE_DIR', str_replace('wp-content', 'wp-includes', WP_CONTENT_DIR));
 }
//common functions and utilities
require_once LH_PLUGIN_DIR . 'includes/controllers/common.php';
$common = new common;

require_once LH_PLUGIN_DIR . 'includes\utilities\pdf\tcpdf.php';
$pdf = new TCPDF("P", "mm", "A4", true, 'UTF-8', false);

// create new PDF document
$pdf = new TCPDF("P", "mm", "A4", true, 'UTF-8', false);
//database
require_once LH_PLUGIN_DIR . 'includes/database/main.php';
$database = new database;
$db       = $database->connect();

//classes and functions
require_once LH_PLUGIN_DIR . 'includes/controllers/patient.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/billing.php';
require_once LH_PLUGIN_DIR . 'includes/controllers/inventory.php';
$patient    = new patient;
$billing    = new billing;
$inventory  = new inventory;

//main functions
require_once LH_PLUGIN_DIR . 'includes/lh-functions.php';

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
        //add amin menu on initialization
        add_action( 'admin_menu', array( "main", 'lh_add_menu' ) );
		//initialize the imported CDN based script
		add_action( 'admin_enqueue_scripts', array( "main", 'admin_styles_and_script' ));
        
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