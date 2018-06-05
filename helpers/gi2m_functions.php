<?php 

global $wpdb;

function checkPluginDataBase($db_version,$installed_version)
{  		
      if ( $installed_version != $db_version ) 
      {
            return true;
      }
      else
      {
            return false;
      }
} 

function updatePluginDataBase()
{
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  $charset_collate = $wpdb->get_charset_collate();   
    
      $table_name = $wpdb->prefix . 'gi2m_master_catalog';
      $sql = "CREATE TABLE $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            code CHAR(3) NOT NULL,
            description2 NVARCHAR(100) NOT NULL,
            table_name NVARCHAR(15) NOT NULL,
            status CHAR(1) NOT NULL,
            UNIQUE KEY id (id))$charset_collate;";

      $result = maybe_create_table($table_name, $sql); 
      //dbDelta( $sql );
    
      $table_name = $wpdb->prefix . "gi2m_members"; 
      $sql = "CREATE TABLE $table_name (
			  member_number INT NOT NULL AUTO_INCREMENT,
			  profile_id INT NOT NULL,			  
			  member_avatar NVARCHAR(250),
			  firstname NVARCHAR(30),
			  lastname NVARCHAR(30),
			  motherlastname NVARCHAR(30),
			  marriedsurname NVARCHAR(30),
			  blood_type NVARCHAR(2),
			  sex NVARCHAR(1),
			  id_card NVARCHAR(20),
			  birthdate NVARCHAR(10),
			  birthplace SMALLINT,
			  suitability_number NVARCHAR(15),
			  biographical_website NVARCHAR(100),
			  email_address NVARCHAR(60),
			  primary_contact NVARCHAR(20),
			  secondary_contact NVARCHAR(20),
			  personal_contact NVARCHAR(20),
			  address_region CHAR(2),
			  address_sub_region CHAR(3),
			  address_sub_division CHAR(3),
			  company_name NVARCHAR(100),
			  company_department NVARCHAR(100),
			  company_position NVARCHAR(50),
			  company_address NVARCHAR(150),
			  company_email NVARCHAR(100),
			  company_contact_number NVARCHAR(20),
			  college_code CHAR(2),
              sectional CHAR(3),
			  member_type CHAR(1),
			  terms_accepted CHAR(1),
              privacy_mode CHAR(1),
			  member_status CHAR(2),
			  guild_representation NVARCHAR(250),
			  initial_date NVARCHAR(10),
			  last_update datetime,
			  unique_guid NVARCHAR(100),
			  corporative_code NVARCHAR(20),
			  UNIQUE KEY id (member_number)) $charset_collate;";
    
    $result = maybe_create_table($table_name, $sql); 

}

?>