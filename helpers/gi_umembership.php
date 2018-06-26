<?php

class GI_UMembership
{
	public function request_card()
	{
		check_ajax_referer( 'gims_myvalidator', 'nonce' );
		
		if(isset($_POST['action'])) 
		{
			$list_item = $_POST['peticion'];
			
			$html_response = '';
			for($i = 0; $i < count($list_item) ; $i++)
			{
				$html_response = $html_response . $list_item[$i] . '||';
			} 
			echo json_encode(["resultado" => "Mi Mensaje: $html_response"]);
			
			wp_die();	
		}
	}
	
	/*
		Funtion: Generate
		Description: this function generate the printable user membership card
	*/
	public function generate()
	{
		check_ajax_referer( 'gims_myvalidator', 'nonce' );
		
		if(isset($_POST['action'])) 
		{
			require_once( 'gi_dbmanager.php' );
			$this->dbManager = new GI_DBManager;
			$list_item = $_POST['requested_ids'];
			
			for($i = 0; $i < count($list_item) ; $i++)
			{
				$db_response = $this->dbManager->Get_MembershipCardData($list_item[$i]);
				//$html_response = buildCard($db_response);
				//TODO: Move this process external function
				foreach ( $db_response as $item )
				{
					extract($item,EXTR_OVERWRITE);
				    $html_response = '<span>'.$personal_id.'|-|'.$firstname.'|-|'.$lastname.'|-|'.$status.'</span>';
				}
			}
			
			echo json_encode(["response_data" => "$html_response"]);
			
			wp_die();	
		}
	}
	
	public function buildCard($response_data)
	{
		try 
		{
			$output = '';
			foreach ( $response_data as $current_item )
			{
				extract($current_item,EXTR_OVERWRITE);
				$output = $personal_id.'|-|'.$firstname.'|-|'.$lastname.'|-|'.$status;
			}
			return $output;
		} 
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
		//$default_avatar = '';
		//$QR_Profile = 'Data Pendiente';
		//extract($card_data,EXTR_OVERWRITE);
		//$nombre_completo = $card_data['firstname'];
		//$cedula = '8-79-134';//$card_data['personal_id'];
		
		//return $nombre_completo;
		//<img id='QR_code' alt='preview' src='../wp-content/plugins/SPIA_M2SYS/getQRCode.php?data=$QR_Profile'/>
		/* $output = "<div class='row'>
							<div class='col-sm-5'>
								<div class='row' style='padding-top:77px;padding-left:54px;'>
									<img id='content_photo' width='115' height='180' alt='preview' src='$default_avatar'/>
								</div>
							</div>
							<div class='col-sm-7' style='text-align:center;'>
								<div class='row' style='padding-top:60px;'>$nombre_completo</div>
								<div class='row' style='padding-bottom:4px;'></div>
								
								<div class='row'><strong>Numero de cedula:</strong>$cedula</div>
								<div class='row' style='padding-bottom:4px;'></div>
								<div class='row' style='padding-top:22px;'>
								</div>
							</div>
							<input type='hidden' id='hf_guidGC' name='hf_guidGC' value='$cedula'/>
						</div>";
		return $output; */
	}
		//$wpdb->show_errors();
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
		} */
}

?>