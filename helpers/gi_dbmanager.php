<?php

class GI_DBManager
{
	public function Get_MembershipData($original_value)
	{
		$new_value = $original_value . '|-||';
		return $new_value;
	}
	
	public function Get_MembershipCardData($memberID)
	{
		try 
		{
			global $wpdb;
			
			$table_name = $wpdb->prefix . 'gi2m_membership_status';
			//$table_name = $wpdb->prefix . 'gims_membership_status';
			$result = $wpdb->get_results( "SELECT firstname,lastname,status,personal_id FROM $table_name WHERE personal_id = '$memberID' ", 'ARRAY_A' );

			return $result;
		} 
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
	}	
}

?>