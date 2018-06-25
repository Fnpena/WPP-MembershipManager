<h1><?php echo get_admin_page_title();
 ?></h1>
<?php if(current_user_can('manage_options')){ 
$new_user = get_user_by('id',2);
var_dump($new_user);
if(isset($new_user))
{
	$user_role = $new_user->roles;
	if(in_array('subscriber',$user_role))
	{
		echo "<h2>SOY COMUN</h2>";
		// global $wpdb;
		// $sql = "INSERT INTO {$wpdb->prefix}gi2m_members (`profile_id`,`firstname`,`lasttname`,`member_status`,`member_type`) VALUES ({$user_id},{$new_user->roles},{$new_user->last_name},'00','1')";
		// $wpdb->query($sql);
	}
	else
	{
		echo "<h2>Mi rol".$user_role[0]."</h2>";
		echo "<h2>SOY ADMIN</h2>";
	}
}

?>
<div class="wrap">
    <p>
    HOLA MUNDO
    </p>
    <?php }else{ ?>
    <p>
    Acceso denegado
    </p>
</div>
<?php } ?>
