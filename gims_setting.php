<?php
/*
 * Plugin Name: GI2M MyMembershipStatus
 * Version: 1.0
 * Plugin URI: http://www.b4aconsulting.com/
 * Description: This component has current state of all record membership.
 * Author: Ghetto Interative Lab
 * Author URI: http://www.b4aconsulting.com/
 *
 * Text Domain: GI_MyMembershipStatus
 * Domain Path: /lang/
 */
if(! defined('WPINC'))
{
	die;
} 

global $wpdb;
//define( 'BC_REALPATH_BASENAME_PLUGIN', dirname( plugin_basename( __FILE__ ) ) . '/' );
define( 'GI_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
//define( 'BC_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'GIMS_TABLE', "{$wpdb->prefix}gims_membership_status" );

function gi_installer() 
{
    require_once GI_PLUGIN_DIR_PATH . 'helpers/gi_dbinit.php';
	GI_DBInit::install_db();
}

register_activation_hook(__FILE__,'gi_installer');

require_once GI_PLUGIN_DIR_PATH. 'helpers/gi_initialize.php';

function initialize_components() 
{
	$mp_master = new GI_Initialize;
	$mp_master->run();
}

initialize_components();
?>