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
    
      $table_name = $wpdb->prefix . 'gi2m_master_catalog';
      $sql = "CREATE TABLE $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            code CHAR(3) NOT NULL,
            description NVARCHAR(100) NOT NULL,
            table_name NVARCHAR(15) NOT NULL,
            status CHAR(1) NOT NULL,
            UNIQUE KEY id (id))$charset_collate;";

      $result = maybe_create_table($table_name, $sql); 
    
      $table_name = $wpdb->prefix . "gi2m_members"; 
      $sql = "CREATE TABLE $table_name (
			  id INT NOT NULL AUTO_INCREMENT,
			  user_id INT NOT NULL,			 
			  membership NVARCHAR(15),				  
			  firstname NVARCHAR(30),
			  lastname NVARCHAR(30),
			  avatar NVARCHAR(250),
			  blood_type NVARCHAR(2),
			  sex NVARCHAR(1),
			  personal_id NVARCHAR(20),
			  birthdate NVARCHAR(10),
			  primary_contact NVARCHAR(20),
			  secondary_contact NVARCHAR(20),
			  membership_type CHAR(1),
			  register_date NVARCHAR(10),
			  last_update datetime,
              is_private CHAR(1),
			  status CHAR(2),
			  secret_key NVARCHAR(100),
			  UNIQUE KEY id (id)) $charset_collate;";
    
    $result = maybe_create_table($table_name, $sql); 
	
	$table_name = $wpdb->prefix . 'gi2m_member_education';
	$sql = "CREATE TABLE $table_name(
				id INT NOT NULL AUTO_INCREMENT,
				member_id INT NOT NULL,
				education_lvl CHAR(2) NOT NULL,
				description NVARCHAR(100) NOT NULL,
				aux_code NVARCHAR(30),
				institution NVARCHAR(100),
				year NVARCHAR(9) NOT NULL,
				status CHAR(1) NOT NULL,
				UNIQUE KEY id (id))$charset_collate;";
				
	$result = maybe_create_table($table_name, $sql);

}

/*
	Funtion: memberPreRegistration
	Description: 
*/
function memberPreRegistration($user_id)
{
	global $wpdb;
	$sql = "INSERT INTO {$wpdb->prefix}gi2m_members (`user_id`,`status`,`membership_type`) VALUES ({$user_id},'00','1')";
	$wpdb->query($sql);
}

?>