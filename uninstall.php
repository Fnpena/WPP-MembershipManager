<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

gi2m_remove_components();
gi2m_remove_variable();
gi2m_remove_database();


function gi2m_remove_components() 
{
}

function gi2m_remove_variable() 
{
    $option_name = 'wpp_gimcl_options';
    delete_option($option_name);
}

function gi2m_remove_database() 
{
    global $wpdb;
<<<<<<< HEAD
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}gimcl_member_education"); 
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}gimcl_members"); 
=======
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}gi2m_member_education"); 
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}gi2m_members"); 
>>>>>>> 689ea104d6953f5d71e0847d10aeeb9911c81e9e
}


?>