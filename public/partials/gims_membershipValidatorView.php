<?php 
        /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        Class: gims_membershipValidatorView
        Function: 

        +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

		global $wpdb;

        $validator_msg_type = 'panel-danger';
        $validator_msg_icon = '<i class="fa fa-times-circle" style="font-size:200px;color:#f2dede;"></i>';
        $validator_msg_info = 'Se produjo un error durante la validacion. Contactar soporte tecnico.';
		
		$sql = "SELECT personal_id,
					   firstname,		  
					   status
				  FROM {$wpdb->prefix}gims_membership_status";

		//Aditional validation search parameter from QR
		if(isset($_GET['gims-membership-validator']))
		{
            
             //Add QueryString Decryption protocol
			require_once( GI_PLUGIN_DIR_PATH.'helpers/gi_crypto.php' );
			$dbCrypto = new GI_Crypto;
            
            $decrypted_pid = $dbCrypto->Decrypt($_GET['gims-membership-validator']);
            
            if(isset($decrypted_pid) && $decrypted_pid!=null && $decrypted_pid!='')
			     $sql .= " WHERE personal_id = '" . esc_sql( $decrypted_pid ). "' ";
            
            try
            {
                $result = $wpdb->get_row( $sql );

                if(isset($result))
                {
                    if($result->status == 'ACTIVO' || $result->status == 'EMERITO' || $result->status == 'ESTUDIANTE')
                    {
                        $validator_msg_type = 'panel-success';
                        $validator_msg_icon = '<i class="fa fa-check-circle" style="font-size:200px;color:#dff0d8;"></i>';
                        $validator_msg_info = sprintf('Miembro: %s',$result->firstname);
                    }
                    else
                    {
                        $validator_msg_type = 'panel-warning';
                        $validator_msg_icon = '<i class="fa fa-exclamation-triangle" style="font-size:200px;color:#fcf8e3;"></i>';
                        $validator_msg_info = sprintf('Miembro: %s',$result->firstname);
                    }
                }
            }
            catch( Exception $e)
            {

            }
		}
?>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<div class="wrap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="panel <?php echo $validator_msg_type ?>" style="width:400px;text-align:center;">
      <div class="panel-heading">Validador de Miembros COICI</div>
      <div class="panel-body">
          <?php echo $validator_msg_icon ?>
          <p><?php echo $validator_msg_info ?></p>
      </div>
    </div>
</div>