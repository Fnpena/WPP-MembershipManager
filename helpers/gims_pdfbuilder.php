<?php
require(GI_PLUGIN_DIR_PATH.'lib/FPDF/fpdf.php');

class myPDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        //$this->Image('logo.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',14);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,10,'Listado de Miembros',0,0,'C');
        // Line break
        $this->Ln(12);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

class GIMS_PDFBuiilder extends myPDF
{
    public function build($PDF_Filename)
    {
        // Instanciation of inherited class
        $pdf = new myPDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times','',12);


        $directory = GI_PLUGIN_DIR_PATH.'image/resources/';

        //get all files in specified directory
        //$files = glob($directory . "*");
        $files = array_diff(scandir($directory), array('.', '..','base'));
        //$files = scandir($directory);
        
         $x = 0;
         $counter = 0;
         $item_per_page = 4;
         $total_files = count($files);
         $remain_items = $total_files;
         $rounds_num = round($total_files/3) < 3 ? 1 : round($total_files/3);

        for($i = 0; $i < $rounds_num; $i++)
        {
            if($counter>0)
            {
                $pdf->AddPage();
            }

            if($remain_items>0)
            {
                //$pdf->Cell(0,10,'Current FileName:'.$files[$x+2],0,1,'C');
                $pdf->Cell(0,0,$pdf->Image($directory.$files[$x+2],60,30,100,70),1,1,'C');
                $remain_items --;
                $x++;
            }
            if($remain_items>0)
            {
                //$pdf->Cell(0,10,'Current FileName:'.$files[$x+2],0,1,'C');
                $pdf->Cell(0,0,$pdf->Image($directory.$files[$x+2],60,110,100,70),1,1,'C');
                $remain_items --;
                $x++;
            }

            if($remain_items>0)
            {
                //$pdf->Cell(0,10,'Current FileName:'.$files[$x+2],0,1,'C');
                $pdf->Cell(0,0,$pdf->Image($directory.$files[$x+2],60,190,100,70),1,1,'C');
                $remain_items --;
                $x++;
            }


            /* if($remain_items>0)
            {
                //$pdf->Cell(0,10,'Current FileName:'.$files[$x+2],0,1,'C');
                $pdf->Cell(0,0,$pdf->Image('./image/'.$files[$x+2],75,205,100,65),1,1,'C');
                $remain_items --;
                $x++;
            } */

            $counter++;
        }
        
        $pdf->Output('F',$PDF_Filename);
    }
}
?>