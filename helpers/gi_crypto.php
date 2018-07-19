<?php

class GI_Crypto
{
		/*
			Funtion: Encrypt
			Description: this function encrypt or decrypt one string 
		*/
		public function Encrypt($string) 
		{
			$encrypt_method = "AES-256-CBC";
		    $secret_key = '5cio2KXVIII-Key';
			$secret_iv = '5cio2KXVIII-IV';
		
			$output = false;

			// hash
			$key = hash('sha256', $secret_key);
			
			// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
			$iv = substr(hash('sha256', $secret_iv), 0, 16);
			
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
			
			return $output;
		}
		
		/*
			Funtion: Decrypt
			Description: this function encrypt or decrypt one string 
		*/
		public function Decrypt($string) 
		{
			$encrypt_method = "AES-256-CBC";
			$secret_key = '5cio2KXVIII-Key';
			$secret_iv = '5cio2KXVIII-IV';
		
			$output = false;
			// hash
			$key = hash('sha256', $secret_key);
			
			// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
			$iv = substr(hash('sha256', $secret_iv), 0, 16);
            try
            {
			 $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
            }
            catch(Exception $e)
            { 
                return null;
            }
			return $output;
		}
}

?>