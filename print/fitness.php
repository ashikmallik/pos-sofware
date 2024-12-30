<?php
include '../model/Controller.php';
include '../model/FormateHelper.php';
require '../lib/fpdf.php';

$formater = new FormateHelper();
$obj = new Controller();
$date =date('d-m-Y H:i:s');
$date2 =date('d/m/Y');
$month =date('M');
$prefix =date('mY');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;
$day=date('d');
$terms=isset($_GET['key']) ? $_GET['key']:NULL;
$type=isset($_GET['flag']) ? $_GET['flag']:NULL;
// Instantiation of inherited class
$i=0;
class PDF extends FPDF
{
	//Page header
	function Header()
	{
		//Logo
		$this->Image('pad.png',15,0,185,"C");
		$this->Ln();
	}

	//Page footer
	function Footer()
	{
		$this->Image('footpad.png',15,280,185,"C");
		 $this->SetY(-8);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

//Instanciation of inherited class
$pdf=new PDF('P','mm','A4');
$pdf->AliasNbPages();


//$pdf = new FPDF('P','mm','A4');

$pdf->SetTitle("ALL ".$type." PDF");
$pdf->SetAuthor('BSD');
$header=array("Sl.","Description of Goods","Image","Qty","Unit/price","Price");
$pdf->AddPage();
	$pdf->SetFont('Times','',15);
	$pdf->SetX(15);
	//for color
	$pdf->SetFillColor(234,234,234);
	//for quotation 
	$pdf->Ln(20);
    // information section
	$cell_width = 70;
	$pdf->SetX(15);
	$pdf->SetFont('Times','B',24);
	$pdf->Cell(50,10,"Quotation - ",1,0,'LR');
	$pdf->SetFont('Times','',12);
	$pdf->Cell(0,10,"Stream & Sau\nna Equipments- Tylo Brand- Made in Sweden",1,1,'LR');
	
	$pdf->SetFont('Times','',10);
	$pdf->SetY(42);
	$pdf->SetX(15);
	$pdf->MultiCell(65,5,"TO: \n Hello  dfgdfg dfgdfg dfgdfg dfgdfg  dfgdfg fgdfg gf fgf fgfgf fgf fgfg fgf fgfg f fs sdfsdf dfsdfsdf df Dear",0,1);
	$pdf->SetXY($pdf->GetX() + $cell_width, 42);
	$pdf->MultiCell(60,5,"Contact: \n fjsld ifhs ldkf hlsk d fhls kdfhn sldf kls d",0,1);
	$pdf->SetXY($pdf->GetX() + $cell_width*2-10,42);
	$pdf->MultiCell(60,5,"Ref: TF?BSD/fsfsdfsd \nDate:".$date2."\nRevised No.:",0,1);
	$pdf->Ln(10);
	
	
	
	//image set every page
	$pdf->SetFont('Times','',15);
	$pdf->SetTopMargin(25);
	//image for header
	
	$pdf->SetFont('Times','',10);
	$pdf->Ln(5);
    $w = array(80,25, 40);
    // Header
	$pdf->SetX(15);
    $pdf->Cell(10,7,$header[0],1,0,'L',true);
    $pdf->Cell(65,7,$header[1],1,0,'C',true);
    $pdf->Cell(35,7,$header[2],1,0,'C',true);
    $pdf->Cell(10,7,$header[3],1,0,'C',true);
    $pdf->Cell(30,7,$header[4],1,0,'C',true);
    $pdf->Cell(30,7,$header[5],1,0,'C',true);
    $pdf->Ln();
    // Data
	
foreach ($obj->view_all_by_cond("tbl_agent","ag_status='1' and pay_status='1'") as $value){	
	$i++;
	$pdf->SetX(15);
	$pdf->Cell(10,25,$i,'LR',0,"C");
	$pdf->Cell(65,25,$month."hgfs sd fhksjdh fhksd f",0,0,'L');
	$pdf->Cell(35,25,"",0,0,'C');
	$pdf->Image('logo.png',100,$pdf->GETY(),25,"C");
	$pdf->Cell(10,25,"08",'LR',0,'C');
	$pdf->Cell(30,25,"54654",'LR',0,'C');
	$pdf->Cell(30,25,"dhsfgksdh",'LR',0,'C');
	$pdf->SetX(15);
	$pdf->Cell(180,25,"",1,1,'R');
}
	$pdf->SetX(15);
	$pdf->Cell(150,7,"TOTAL(BDT)",1,0,'R');
	$pdf->Cell(30,7,"52"."/-",1,0,'R');
	$pdf->Ln();
    // Closing line
	//Authorization part
	$pdf->Ln(20);
	$pdf->SetX(40);
	$pdf->Cell(120,4,"-----------------------------------",0,1,"R");
	$pdf->SetX(55);
	$pdf->Cell(100,4,"Authorized Signature",0,1,"R");
	$pdf->Ln();
	$pdf->SetX(55);
	$pdf->Cell(100,4,$date,0,1,"R");

$pdf->Output();
?>