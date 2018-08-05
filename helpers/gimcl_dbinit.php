<?php
/**
 * @link       http://misitioweb.com
 * @since      1.0.0
 * @package    GI_MyCourseLog
 * @subpackage GI_MyCourseLog/helpers
 */

/**
 * This class define all the needed for plugin activation
 *
 * @since      1.0.0
 * @package    GI_MyCourseLog
 * @subpackage GI_MyCourseLog/helpers
 * @author     Franklin P. Rojas
 */
class GIMCL_DBInit 
{
	/**
	 * Static method init with plugin activation to
	 * create the required database elements
	 *
	 * @since 1.0.0
     * @access public static
	 */
	public static function install_db() 
	{
		//Define Current Version for plugin and database
		$new_options=array('plugin_version'=>'1.0','db_version'=>'1.0');
		//Verify If Exist Prior Version of the component
		$previous_options = get_option('wpp_gimcl_options');

		//If not exist previous version create save de current options instead
		if($previous_options == '' || $previous_options == null)
		{
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$charset_collate = $wpdb->get_charset_collate();   
			
			$sql = "CREATE TABLE ".GIMCL_TBL_MEMBER." (
			  id INT NOT NULL AUTO_INCREMENT,	 
			  personal_id NVARCHAR(20),
			  membership NVARCHAR(15),				  
			  firstname NVARCHAR(30),
			  lastname NVARCHAR(30),
			  register_date NVARCHAR(10),
			  last_update datetime,
			  status CHAR(2),
			  UNIQUE KEY id (id)) $charset_collate;";

			$result = maybe_create_table(GIMCL_TBL_MEMBER, $sql); 

			$sql = "CREATE TABLE ".GIMCL_TBL_MEMBER_EDU." (
				id INT NOT NULL AUTO_INCREMENT,
				member_id INT NOT NULL,
				education_lvl CHAR(2) NOT NULL,
				description NVARCHAR(100) NOT NULL,
				aux_code NVARCHAR(30),
				institution NVARCHAR(100),
				year NVARCHAR(9),
                duration NVARCHAR(3),
				status CHAR(1),
                register_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				UNIQUE KEY id (id))$charset_collate;";
				
			$result = maybe_create_table(GIMCL_TBL_MEMBER_EDU, $sql);

			add_option('wpp_gimcl_options',$new_options);
		 }

		/*else
		{
			//Check if the current db version and the installed db version are different
			if($new_options['db_version'] != $previous_options['db_version'])
			{
				global $wpdb;
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				$charset_collate = $wpdb->get_charset_collate();   

				$sql = "CREATE TABLE ".GIMS_TABLE. "personal_id NVARCHAR(20),
											membership NVARCHAR(15),				  
											firstname NVARCHAR(30),
											lastname NVARCHAR(30),
											status NVARCHAR(15)) $charset_collate;";

				$result = maybe_create_table(GIMS_TABLE, $sql); 
				update_option( 'wpp_gimcl_options', $new_options );
			}
		}*/
	}
}
?>