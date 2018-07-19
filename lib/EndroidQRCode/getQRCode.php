<?php 
require_once('QrCode.php');
use Endroid\QrCode\QrCode;

if(isset($_GET['data']))
{
//Fix: Escape 2nd Parameter
//$QR_URL = str_replace('?2','&',$_GET['data']); -> Only Intranet Mode
$QR_URL = $_GET['data']; 
$qrCode = new QrCode();
        $qrCode
            ->setText($QR_URL)
            ->setExtension('png')
            ->setSize(80)
            ->setPadding(5);
			$qrCode->render();
}
?>