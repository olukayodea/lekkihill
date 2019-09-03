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
define(  "table_name_prefix", $wpdb->prefix, true );
//common functions
require_once LH_PLUGIN_DIR . 'includes/common.php';
$common = new common;

//database
require_once LH_PLUGIN_DIR . 'includes/database/main.php';
$database = new database;
$db       = $database->connect();

//classes and functions
require_once LH_PLUGIN_DIR . 'includes/patient.php';
require_once LH_PLUGIN_DIR . 'includes/billing.php';
require_once LH_PLUGIN_DIR . 'includes/inventory.php';
$patient    = new patient;
$billing    = new billing;
$inventory  = new inventory;

//main functions
require_once LH_PLUGIN_DIR . 'includes/lh-functions.php';

class mainClass extends main {
    function __construct() {
        //add amin menu on initialization
        add_action( 'admin_menu', array( "main", 'lh_add_menu' ) );
        
        //registration hooks
        register_activation_hook( __FILE__, array( $this, 'lh_install' ) );
        register_deactivation_hook( __FILE__, array( $this, 'lh_deactivate' ) );
        register_uninstall_hook( __FILE__, array( $this, 'lh_uninstall' ) );
    }
}
new mainClass;
?>