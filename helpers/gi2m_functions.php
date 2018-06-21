<?php 

/*
	Funtion: checkPluginDataBase
	Description: this function check current database version against the current plugin database update
*/
function checkPluginDataBase($db_version,$installed_version)
{  		
      if ( $installed_version != $db_version ) 
      { return true; }
      else
      { return false; }
} 

/*
	Funtion: updatePluginDataBase
	Description: this function create the database structures
*/
function updatePluginDataBase()
{
	  global $wpdb;
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  $charset_collate = $wpdb->get_charset_collate();   
    
      $table_name = $wpdb->prefix . "gi2m_membership_status"; 
      $sql = "CREATE TABLE $table_name (personal_id NVARCHAR(20),
										membership NVARCHAR(15),				  
										firstname NVARCHAR(30),
										lastname NVARCHAR(30),
										status NVARCHAR(15)) $charset_collate;";
    
    $result = maybe_create_table($table_name, $sql); 
}

?>