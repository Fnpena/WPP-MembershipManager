<?php
/**
 * @link       http://misitioweb.com
 * @since      1.0.0
 * @package    GI_MyMembershipStatus
 * @subpackage GI_MyMembershipStatus/helpers
 */

/**
 * This class define all the needed for plugin activation
 *
 * @since      1.0.0
 * @package    GI_MyMembershipStatus
 * @subpackage GI_MyMembershipStatus/helpers
 * @author     Franklin P. Rojas
 */
class GI_DBInit 
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
		$previous_options = get_option('wpp_gims_options');

		//If not exist previous version create save de current options instead
		if($previous_options == '' || $previous_options == null)
		{
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$charset_collate = $wpdb->get_charset_collate();   
			
			$sql = "CREATE TABLE ".GIMS_TABLE." (personal_id NVARCHAR(20),
										membership NVARCHAR(15),				  
										firstname NVARCHAR(30),
										lastname NVARCHAR(30),
										status NVARCHAR(15)) $charset_collate;";

			$result = maybe_create_table(GIMS_TABLE, $sql); 
			add_option('wpp_gims_options',$new_options);
		 }
		else
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
				update_option( 'wpp_gims_options', $new_options );
			}
		}
	}
}
?>