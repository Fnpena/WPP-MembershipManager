<h1><?php echo get_admin_page_title(); ?></h1>
$new_user = get_user_by('id',$user_id);
	//if(isset($new_user))
	//{
		$user_role = $new_user->roles;
		//if(in_array('subscriber',$user_role))
		//{
			global $wpdb;
			$sql = "INSERT INTO {$wpdb->prefix}gi2m_members (`profile_id`,`firstname`,`lasttname`,`member_status`,`member_type`) VALUES ({$user_id},{$new_user->roles},{$new_user->last_name},'00','1')";
			$wpdb->query($sql);
		//}
	//}