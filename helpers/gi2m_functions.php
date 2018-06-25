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
    
      $table_name = $wpdb->prefix . "gims_membership_status"; 
      $sql = "CREATE TABLE $table_name (personal_id NVARCHAR(20),
										membership NVARCHAR(15),				  
										firstname NVARCHAR(30),
										lastname NVARCHAR(30),
										status NVARCHAR(15)) $charset_collate;";
    
    $result = maybe_create_table($table_name, $sql); 
	
	gims_install_data();
}

/*
	Funtion: gims_install_data
	Description: data dummy
*/
function gims_install_data() 
{
		global $wpdb;
		$table_name = $wpdb->prefix . 'gims_membership_status';
		
		$delete = $wpdb->query("TRUNCATE TABLE `".$table_name."`");
		
		$wpdb->insert($table_name, array( 'personal_id' => '01-111-1111', 'membership' => 'XXXXXX', 'firstname' => 'Joan','lastname' => 'Tess', 'status' => 'Activo'));
		$wpdb->insert($table_name, array( 'personal_id' => '02-222-2222', 'membership' => 'XXXXXX', 'firstname' => 'Juan','lastname' => 'Timp', 'status' => 'Activo'));
		$wpdb->insert($table_name, array( 'personal_id' => '03-111-3333', 'membership' => 'XXXXXX', 'firstname' => 'Pedro','lastname' => 'Kimp', 'status' => 'Activo'));
		$wpdb->insert($table_name, array( 'personal_id' => '04-444-4444', 'membership' => 'XXXXXX', 'firstname' => 'Sam','lastname' => 'Lamk', 'status' => 'Emerito'));
		$wpdb->insert($table_name, array( 'personal_id' => '05-555-5555', 'membership' => 'XXXXXX', 'firstname' => 'Zeta','lastname' => 'Laxx', 'status' => 'Supernumerario'));
		$wpdb->insert($table_name, array( 'personal_id' => '06-166-1661', 'membership' => 'XXXXXX', 'firstname' => 'John','lastname' => 'Soso', 'status' => 'Activo'));
		$wpdb->insert($table_name, array( 'personal_id' => '07-777-7777', 'membership' => 'XXXXXX', 'firstname' => 'Adis','lastname' => 'Rix', 'status' => 'Activo'));
		$wpdb->insert($table_name, array( 'personal_id' => '08-888-8888', 'membership' => 'XXXXXX', 'firstname' => 'Lana','lastname' => 'Rexx', 'status' => 'Supernumerario'));
		$wpdb->insert($table_name, array( 'personal_id' => '09-111-9999', 'membership' => 'XXXXXX', 'firstname' => 'Lim','lastname' => 'Tax', 'status' => 'Activo'));
		$wpdb->insert($table_name, array( 'personal_id' => '09-191-1199', 'membership' => 'XXXXXX', 'firstname' => 'Yim','lastname' => 'Tuzz', 'status' => 'Activo'));
		$wpdb->insert($table_name, array( 'personal_id' => '07-171-1177', 'membership' => 'XXXXXX', 'firstname' => 'Samy','lastname' => 'Alaxx', 'status' => 'Supernumerario'));
		$wpdb->insert($table_name, array( 'personal_id' => '01-111-1111', 'membership' => 'XXXXXX', 'firstname' => 'Jira','lastname' => 'Merry', 'status' => 'Emerito'));
		$wpdb->insert($table_name, array( 'personal_id' => '04-144-1441', 'membership' => 'XXXXXX', 'firstname' => 'Jill','lastname' => 'Jess', 'status' => 'Activo'));
		$wpdb->insert($table_name, array( 'personal_id' => '07-155-3331', 'membership' => 'XXXXXX', 'firstname' => 'Tazz','lastname' => 'Rex', 'status' => 'Supernumerario'));
}
?>