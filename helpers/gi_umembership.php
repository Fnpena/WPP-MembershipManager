<?php
/*
 * this class manage the generation of the printable user identification cards.
 *
 * @link       https://b4aconsulting.com
 * @since      1.0.0
 *
 * @package    GI_MyMembershipStatus
 * @subpackage GI_MyMembershipStatus/helpers
 * @author     Franklin Peña Rojas
 */
/*
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                    Change Request Log
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
CR-19072018
Reason: Change Html Generation Mode of Id Card Model for Native PHP library generation logic aditional to that create the PDF result file with FPDF library to only return the PDF URL destination to display
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

class GI_UMembership
{
    /**
    * encrypted value of personal id member
    *
    * @since    1.0.0
    * @access   protected
    */
    //protected $encrypted_pid;
    
    /**
    * domain url value
    *
    * @since    1.2.1
    * @access   protected
    */
    protected $domain_url;
    
    /**
    * domain url value
    *
    * @since    1.2.1
    * @access   protected
    */
    protected $validator_url;
    
    /**
    * QRCode Generator Library url value
    *
    * @since    1.2.1
    * @access   protected
    */
    protected $qr_generator_url;
    
    public function __construct()
    {
        //TODO Add Debug Mode pass by constructor
        //PROD Mode
        $this->domain_url = $_SERVER['HTTP_HOST'];
        $this->validator_url = "%s/validator-coici/?gims-membership-validator=%s";
        $this->qr_generator_url = "%s/wp-content/plugins/GI_MyMembershipStatus/lib/EndroidQRCode/getQRCode.php?data=%s";
                    
        //DEBUG Mode
        //$this->domain_url =  "http://localhost/B4ADemoSite";
        //$this->validator_url = "%s/wp-admin/admin.php?page=gi2m-members?2sx=%s";
    }
    
    /*
    * this function build the pdf exportale file 
    *
    * @since    1.2.1
    * @access   public
    */
    public function generate()
    {
        //Validate if the function invocation origin is secure
        check_ajax_referer( 'gims_myvalidator', 'nonce' );
		
		if(isset($_POST['action'])) 
		{
            try
            {
                //Add Reference to encryption and database access classes
                require_once( 'gi_dbmanager.php' );
                require_once( 'gi_crypto.php' );
                require_once( 'gims_pdfbuilder.php' );

                $this->dbManager = new GI_DBManager;
                $this->dbCrypto = new GI_Crypto;
                $this->myPDF = new GIMS_PDFBuiilder;

                //Get the list of selected cards to generate
                $list_item = $_POST['requested_ids'];
                
                //Clean directory from previous executions
                self::delete_directory(GI_PLUGIN_DIR_PATH.'image/resources');     
                
                for($i = 0; $i < count($list_item) ; $i++)
                {
                    //Get Data from current member personal id
                    $db_response = $this->dbManager->Get_MembershipCardData($list_item[$i]);

                    foreach ( $db_response as $item )
                    {
                        extract($item,EXTR_OVERWRITE);

                        $encrypted_pid = $this->dbCrypto->Encrypt($personal_id);

                        //Custom Validator URL for member id
                        $customValidatorURL = sprintf($this->validator_url,$this->domain_url,$encrypted_pid);

                        //QRCode Generator URL to hide custom validator url
                        $QR_ImageSource = sprintf($this->qr_generator_url,$this->domain_url,$customValidatorURL);

                        //Custom prefix for image generated
                        $Prefix_IMG = sprintf("COICI%s",str_replace('-','',$personal_id));

                        //Build Custom QR Image Name
                        $CustomQR_URL = sprintf(GI_PLUGIN_DIR_PATH.'image/resources/base/%sQR.png',$Prefix_IMG);

                        
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_URL, $QR_ImageSource);
                        $data = curl_exec($ch);
                        curl_close($ch);
                        
                        //Take the QRcode generator url and save as image in server
                        file_put_contents($CustomQR_URL, $data);
                        
                        //Take the QRcode generator url and save as image in server
                        //file_put_contents($CustomQR_URL, file_get_contents($QR_ImageSource));

                        $base_template = @imagecreatefrompng(GI_PLUGIN_DIR_PATH.'image/Carnet-Template50.png');
                        $customQR = @imagecreatefrompng($CustomQR_URL);

                        //Set font color
                        $font_color = imagecolorallocate($im, 47, 47, 47);

                        //Add Member personal information to image base 
                        imagestring($base_template, 3, 5,  115,$firstname, $font_color);
                        imagestring($base_template, 3, 5,  135,$personal_id, $font_color);

                        //Add QRCode Image to fixed position in Base Template Image
                        imagecopy($base_template, $customQR, imagesx($base_template)-imagesx($customQR)-5, imagesy($base_template)-imagesy($customQR)-10, 0, 0, imagesx($customQR), imagesy($customQR));

                        //Save Image edited
                        imagepng($base_template,GI_PLUGIN_DIR_PATH.sprintf('image/resources/%sID.png',$Prefix_IMG));

                        //Destroy in memory image
                        imagedestroy($customQR);
                        imagedestroy($base_template);
                    }
                }

                $PDF_Filename = date('d').date('m').date('Y').date('h').date('i').'.pdf';
                $PDF_FilenamePath = GI_PLUGIN_DIR_PATH.'downloadable/'.$PDF_Filename;
                $PDF_displayPath = '../wp-content/plugins/GI_MyMembershipStatus/downloadable/'.$PDF_Filename;
                $this->myPDF->build($PDF_FilenamePath);
                
                //Return the response to the ajax caller endoced in json format
                echo json_encode(["response_code" => "OK","response_data" => "$PDF_displayPath"]);	

                
            }
            catch(Exception $e)
            {
                echo json_encode(["response_code" => "FAIL","response_data" => "$e"]);	
            }
            
			wp_die();	
        }
    }
    
       /*
    * this function clean the files inside one directory or one given file
    *
    * @since    1.2.1
    * @access   private
    */
    public static function delete_directory($dirname) {
     if (is_dir($dirname))
         $dir_handle = opendir($dirname);
     if (!$dir_handle)
     {}
     else
     {     
         while($file = readdir($dir_handle)) 
         {
           if ($file != "." && $file != "..") 
           {
                if (!is_dir($dirname."/".$file))
                     unlink($dirname."/".$file);
                else
                     self::delete_directory($dirname.'/'.$file);
           }
         }
     }
     closedir($dir_handle);
     //rmdir($dirname);
     //return true;
}
    
    /*
    * this function generate the printable user membership card in html + CSS
    *
    * @since    1.0.0
    * @access   private
    */
	private function generate_html()
	{
		check_ajax_referer( 'gims_myvalidator', 'nonce' );
		
		if(isset($_POST['action'])) 
		{
			require_once( 'gi_dbmanager.php' );
			require_once( 'gi_crypto.php' );
			
			$this->dbManager = new GI_DBManager;
			$this->dbCrypto = new GI_Crypto;
			
			$list_item = $_POST['requested_ids'];
			
			for($i = 0; $i < count($list_item) ; $i++)
			{
				$db_response = $this->dbManager->Get_MembershipCardData($list_item[$i]);
				
				//TODO: Move this process external function
				foreach ( $db_response as $item )
				{
					extract($item,EXTR_OVERWRITE);
					
					$encrypted_pid = $this->dbCrypto->Encrypt($personal_id);
					//$encrypted_pid = $personal_id;
                    
                    $domainName =  $_SERVER['HTTP_HOST'];
                    //$domainName =  "http://localhost/B4ADemoSite";
                    
					//$siteURL = "%s/wp-admin/admin.php?page=gi2m-members?2sx=%s"; // Intranet-Mode
                    $siteURL = "%s/validator-coici/?gims-membership-validator=%s";
					$statusVerifierURL = sprintf($siteURL,$domainName,$encrypted_pid);
                    
					$QR_ImageSource = "../wp-content/plugins/GI_MyMembershipStatus/lib/EndroidQRCode/getQRCode.php?data=$statusVerifierURL";
					
                    $label_name       = sprintf("<span class='lbl-cname'>%s</span>",$firstname);
                    $label_personalID = sprintf("<span class='lbl-cpersonalid'>%s</span>",$personal_id);
                    $GCUniqueId = sprintf("ZX%s",str_replace('-','',$personal_id));
                    
					$output = $output. "<div id='$GCUniqueId' class='GCTemplate row'>
								<div class='col-sm-7 center-member'>
									<div class='row'>$label_name</div>
									<div class='row'>$label_personalID</div>
								</div>
								<div class='col-sm-5'>
									<div class='row'>
										<img id='QR_code' alt='preview' src='$QR_ImageSource'/>
									</div>
								</div>
							</div><div class='row' style='height:10px;'></div>";
				}
			}
			
			echo json_encode(["response_code" => "OK","response_data" => "$output"]);
			
			wp_die();	
		}
	}
}
?>