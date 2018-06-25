<?php

class GI_UMembership
{
	/*
		Funtion: Generate
		Description: this function generate the printable user membership card
	*/
	public function generate()
	{
		check_ajax_referer( 'gims_myvalidator', 'nonce' );
		
		if(isset($_POST['action'])) 
		{
			$current_value = $_POST['dato_prueba'];
			echo json_encode(["resultado" => "Mi Mensaje: $current_value"]);
			
			wp_die();	
		}
		
		// require_once( '/helpers/gi_dbmanager.php' );
		// $default_avatar = "../wp-content/plugins/SPIA_M2SYS/image/avatar.png";
		// $respond = "OK";
		// echo $respond;
		//wp_die();	
		/* $data_query = "SELECT 
							profile_id,
							firstname,
							lastname,
							motherlastname,
							marriedsurname,		
							suitability_number,
							sectional,
							(select description from $table_catalog where table_name = 'TBL_SECTIONAL' and code = sectional) AS sectional_des,
							college_code,
							(select description from $table_catalog where table_name = 'TBL_COLLEGE' and code = college_code) AS college_des,
							member_type,
							(select description from $table_catalog where table_name = 'TBL_MEMBERTYPE' and code = member_type) AS member_type_des,
							member_status,
							(select description from $table_catalog where table_name = 'TBL_MEMBERSTS' and code = member_status) AS member_status_des,
							profile_avatar,
							member_guid
						FROM $table_name WHERE `member_guid` = '$memberCode'"; */					

		/* while ($row = mysqli_fetch_array($result))
		{
			$firstname = $row['firstname'];
			$lastname = $row['lastname'];
			$suitability_number = $row['suitability_number'];
			$college_des = $row['college_des'];
			$profile_avatar = $row['profile_avatar'];
			
			$field_memberGUID = $row['member_guid'];
			$encrypted_txt = encrypt_decrypt('encrypt', $field_memberGUID);
			$QR_Profile = "www.spia.org.pa/perfil_socio.php?guid=$encrypted_txt";
			
			$full_name = $firstname.' '.$lastname;
			
			if(isset($profile_avatar) && $profile_avatar !='')
			{
				$default_avatar = $profile_avatar;
			}
			
			$respond = "<div class='row'>
							<div class='col-sm-5'>
								<div class='row' style='padding-top:77px;padding-left:54px;'>
									<img id='content_photo' width='115' height='180' alt='preview' src='$default_avatar'/>
								</div>
							</div>
							<div class='col-sm-7' style='text-align:center;'>
								<div class='row' style='padding-top:60px;'>$full_name</div>
								<div class='row' style='padding-bottom:4px;'></div>
								<div class='row'><strong>Colegio:</strong>$college_des</div>
								<div class='row' style='padding-bottom:4px;'></div>
								<div class='row'><strong>Numero de Idoneidad:</strong>$suitability_number</div>
								<div class='row' style='padding-bottom:4px;'></div>
								<div class='row' style='padding-top:22px;'>
								<img id='QR_code' alt='preview' 
												  src='../wp-content/plugins/SPIA_M2SYS/getQRCode.php?data=$QR_Profile'/>
								</div>
							</div>
							<input type='hidden' id='hf_guidGC' name='hf_guidGC' value='$memberCode'/>
						</div>";
		} */
	}
}

?>