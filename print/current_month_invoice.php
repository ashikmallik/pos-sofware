<?php
include '../model/Controller.php';
include '../model/FormateHelper.php';
require '../lib/fpdf.php';

$formater = new FormateHelper();
$obj = new Controller();
$date =date('d-m-Y');
$month =date('M');
$prefix =date('mY');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;
$day=date('d');
$terms=isset($_GET['key']) ? $_GET['key']:NULL;
$type=isset($_GET['flag']) ? $_GET['flag']:NULL;
// Instantiation of inherited class
$i=0;
$pdf = new FPDF('P','mm','A4');

$pdf->SetTitle("ALL ".$type." PDF");
$pdf->SetAuthor('BSD');
$header=array("Description","Month","Amount");

foreach ($obj->view_all_by_cond("tbl_agent","ag_status='1' and pay_status='1'") as $value){	
	$i++;
	$pdf->AddPage();
	$pdf->SetFont('Times','',15);
	$pdf->Image('logo.png',40,25,50,"C");
	$pdf->SetFont('Times','',15);
	$pdf->SetX(15);
	$pdf->SetFillColor(234,234,234);
	$pdf->Cell(60,10,"MONTHLY BILL \t",0,0,"R");
	$pdf->SetFont('Times','B',24);
	$pdf->Cell(110,10,"\t ".$type." ",0,1,"R");
	//Table for Receipt
	$pdf->SetFont('Times','B',12);
	$pdf->Ln(10);
	$pdf->SetX(110);
	$pdf->Cell(35,7,"".$type."#",1,0,'C',true);
    $pdf->Cell(35,7,"DATE",1,0,'C',true);
	$pdf->Ln();
	$pdf->SetFont('Times','',12);
	$pdf->SetX(110);
	$pdf->Cell(35,7,$prefix.$i,1,0,'C');
    $pdf->Cell(35,7,$date,1,0,'C');
	$pdf->Ln();
	$pdf->SetFont('Times','B',12);
	$pdf->SetX(110);
	$pdf->Cell(35,7,"CUSTOMER ID",1,0,'C',true);
    $pdf->Cell(35,7,"TERMS",1,0,'C',true);
	$pdf->Ln();
	$pdf->SetFont('Times','',12);
	$pdf->SetX(110);
	$pdf->Cell(35,7,$value['cus_id'],1,0,'C');
    $pdf->Cell(35,7,$terms,1,0,'C');
	//set billing info
	$pdf->Ln(20);
	$pdf->SetX(33);
	$pdf->Cell(70,7,"BILL TO ",1,0,'L',true);
	$pdf->Ln(10);
	$pdf->SetX(33);
	$pdf->Cell(45,2,"Name: ".$value['ag_name'],10,5,"L");
	$pdf->Ln(5);
	$pdf->SetX(33);
	$pdf->Cell(45,2,"Address: ".$value['ag_office_address'],10,5,"L");
	$pdf->Ln(5);
	$pdf->SetX(33);
	$pdf->Cell(45,2,"Mobile: ".$value['ag_mobile_no'],10,5,"L");
	$pdf->Ln(5);
	$pdf->SetX(33);
	$pdf->Cell(45,2,"Email: ".$value['ag_email'],10,5,"L");


	//Billing table
	$pdf->Ln(5);
    $w = array(80,25, 40);
    // Header
	$pdf->SetX(33);
    $pdf->Cell($w[0],7,$header[0],1,0,'L',true);
    $pdf->Cell($w[1],7,$header[1],1,0,'C',true);
    $pdf->Cell($w[2],7,$header[2],1,0,'C',true);
    $pdf->Ln();
    // Data

	$pdf->SetX(33);
	$pdf->Cell($w[0],7,"Monthly Bill",'LR');
	$pdf->Cell($w[1],7,$month,0,0,'C');
	$pdf->Cell($w[2],7,number_format((float)$value['taka'], 2, '.', ''),'LR',0,'R');
	$pdf->Ln();
	$pdf->SetX(33);
	$pdf->Cell($w[0],70,"",'LR');
	$pdf->Cell($w[1],70,"",'LR');
	$pdf->Cell($w[2],70,"",'LR');
	$pdf->Ln();
	$pdf->SetX(33);
	$pdf->Cell(105,7,"TOTAL(BDT)",1,0,'R');
	$pdf->Cell($w[2],7,number_format((float)$value['taka'], 2, '.', '')."/-",1,0,'R');
	$pdf->Ln();
    // Closing line
	$pdf->SetX(33);
    $pdf->Cell(array_sum($w),0,'','T');
	$pdf->Ln(2);
	$pdf->SetX(33);
	$pdf->SetFont('Times','B',16);
	$pdf->Cell(98,10,"Satellite Vision Cable Network Pvt. Ltd.",100,1,"R");
	$pdf->SetFont('Times','',12);
	$pdf->SetX(33);
	$pdf->Cell(85,4,"Head Office Address:",0,"R");
	$pdf->Ln();
	$pdf->SetX(40);
	$pdf->Cell(85,5,"H#7, R#35, Sec#7",0,"R");
	$pdf->Ln();
	$pdf->SetX(40);
	$pdf->Cell(85,5,"Mob#01745131423",0,"R");
	$pdf->Ln();
	$pdf->SetX(40);
	$pdf->Cell(85,5,"Tel#0258957018",0,"R");
	$pdf->Ln(8);
	$pdf->SetX(33);
	$pdf->Cell(33,4,"Branch Office 11:",0,0,"R");
	$pdf->Cell(120,4,"-----------------------------------",0,1,"R");
	$pdf->SetX(40);
	$pdf->Cell(38,4,"H#47, R#18, Sec#11",0,0,"R");
	$pdf->Cell(100,4,"Authorized Signature",0,1,"R");
	$pdf->SetX(40);
	$pdf->Cell(85,4,"Mob#01924576303",0,"R");
	$pdf->Ln(8);
	$pdf->SetX(33);
	$pdf->Cell(85,4,"Branch Office 12:",0,"R");
	$pdf->Ln();
	$pdf->SetX(40);
	$pdf->Cell(85,4,"H#10, R#14, Sec#12",0,"R");
	$pdf->Ln();
	$pdf->SetX(40);
	$pdf->Cell(85,4,"Mob#01952784",0,"R");
	$pdf->Ln();
}
$pdf->Output();
?>