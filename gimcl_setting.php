<?php
/*
 * Plugin Name: GI2M MyCourseLog
 * Version: 1.0.0
 * Plugin URI: http://www.b4aconsulting.com/
 * Description: This plugin is a record the all courses taken by one member. Additional let you search all course that one member has taken or all members that has taken some course in particular
 * Author: Ghetto Interative Lab
 * Author URI: http://www.b4aconsulting.com
 *
 * Text Domain: GI_MyCourseLog
 * Domain Path: /lang/
*/
if(! defined('WPINC'))
{
	die;
}

global $wpdb;
define( 'GIMCL_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'GIMCL_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'GIMCL_TBL_MEMBER', "{$wpdb->prefix}gimcl_members" );
define( 'GIMCL_TBL_MEMBER_EDU', "{$wpdb->prefix}gimcl_member_education" );

function gimcl_installer() 
{
    require_once GIMCL_PLUGIN_DIR_PATH . 'helpers/gimcl_dbinit.php';
	GIMCL_DBInit::install_db();
}

register_activation_hook(__FILE__,'gimcl_installer');

require_once GIMCL_PLUGIN_DIR_PATH. 'helpers/gimcl_initialize.php';

function gimcl_initialize_components() 
{
	$mp_master = new GIMCL_Initialize;
	$mp_master->run();
}

gimcl_initialize_components();