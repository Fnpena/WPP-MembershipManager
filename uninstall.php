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
    $option_name = 'variable_name';
    delete_option($option_name);
}

function gi2m_remove_database() 
{
    global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}table_name"); 
}


?>