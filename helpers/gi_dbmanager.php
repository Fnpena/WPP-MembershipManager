<?php

class GI_DBManager
{
	public function Get_MembershipData()
	{
		return null;
	}
	
	public function Get_MembershipCardData($memberID)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'gims_membership_status';
		
		$result = $wpdb->get_results( "SELECT firstname,lastname,status,personal_id FROM { $table_name } WHERE personal_id = '{ $memberID }' ", 'ARRAY_A' );

		return $result;
	}	
}

?>