<?php
include '../model/Controller.php';
include '../model/FormateHelper.php';

$formater = new FormateHelper();
$obj = new Controller();
$custom_details="";
$i=0;
$type="Receipt";
$token = isset($_GET['token'])? $_GET['token'] :NULL;

$html="";
$account = $obj->details_by_cond('tbl_account',"acc_id='$token'");
$amount=$account['acc_amount'];
$acc_description=$account['acc_description'];
$entry_date=date("d.m.Y", strtotime($account['entry_date']));
$date =date('d.m.Y');
$month =date('F',strtotime($account['entry_date']));
$prefix =date('Ym',strtotime($account['entry_date']));
$id=$account['agent_id'];
$detailsid = $obj->details_by_cond('tbl_agent',"ag_id='$id'");

$i=1;
$sum=0;
$mainbody="";
$diff=0;
$tr='';
$custom_details ='<tr><td style="color:#E9EAEC;">0</td><td ></td><td></td><td></td><td></td></tr>';
	$tr='';
	$tr = $custom_details.$custom_details;
	
	$mainbody .='
	<div width"100%" style="height=""297mm">
	<div style="width:100%;height:30px;">
		<img src="./img/header.png" width="100%"/>
	</div>
	<div width"100%" style="padding-left:12%;font-size:12px;margin-top:-75px;">
<p>To</p>
<p  style="font-size:16px;"><b>'.$detailsid['ag_name'].'</b></p>
'.$detailsid['ag_office_address'].'
</div>
<div style="width:100%;display:inline-block;margin-top:25px;color:white;font-size:14px;">
<div width="27%" height="40px" style="text-align:center;font-size:32px;font-weight:900;background-color:#575757;display:inline-block;padding:25px;float:left;border-right:3px solid white;" >
'.$type.'
</div>
<div width="60%" height="42px" style="background-color:#575757;display:inline-block;padding:25px;float:left;text-align:center;" >
<div width="33.33%" height="42px" style="background-color:#575757;display:inline-block;float:left;" >
Total Due:<br>'.number_format($amount,2,".",",").' BDT
</div>
<div width="33.33%" height="42px" style="background-color:#575757;display:inline-block;float:left;" >
'.$type.' No.<br>'.$prefix.$token.'
</div>
<div width="33.33%" height="42px" style="background-color:#575757;display:inline-block;float:left;" >
Date:<br>
'.$entry_date.'
</div>
</div>
</div>
<br />
<br />

<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
<thead>
	<tr>
		<td width="7%">Sl.</td>
		<td width="48%">Description</td>
		<td width="10%">Month</td>
		<td width="10%">Speed</td>
		<td width="25%">Bill Amount</td>
	</tr>
	
</thead>
<tbody>
<!-- ITEMS HERE start here value comes from top -->
	<tr>
		<td align="center">1</td>
		<td align="left">'.$acc_description.'</td>
		<td align="center">'.$month.'</td>
		<td align="center">'.$detailsid['mb'].'</td>
		<td align="center">'.number_format($amount,2,".",",").'</td>
	</tr>
	'.$tr.'
<!-- END ITEMS HERE -->
</tbody>
</table>
<div style="width:100%;display:inline-block;margin-top:100px;margin-bottom:50mm;color:white;font-size:14px;">
<div width="60%" height="40px" style="text-align:center;font-size:32px;font-weight:900;display:inline-block;padding:5px;float:left;border-right:3px solid white;" ></div>
<div width="38%" style="display:inline-block;float:left;text-align:center;" >
<div  style="background-color:#575757;display:inline-block;float:left;padding:5px;" >
Total : '.number_format($amount,2,".",",").' BDT
</div>
<div  style="text-align:left;display:inline-block;float:left;color:black;padding-top:10px;" >
In word:<br>
'.$formater->convert_number_to_words($amount).'
</div>

</div>
</div>
</div>
	';


$html .= '
<html>
<head>
<style>
body {font-family: sans-serif;
	font-size: 10pt;
}
p {	margin: 0pt; }
table.items {
	border: 0.1mm solid #000000;
}
tbody td { 
vertical-align: top;
background-color: #E9EAEC;
 }
.items td {
	border: 1.4mm solid #FFFFFF;
	vertical-align:middle;
	font-size:14px;
	padding:15px;
	
}
table thead td { 
	text-align: center;
}
</style>
</head>
<body>

<!--mpdf
<htmlpageheader name="myheader">

</htmlpageheader>

<htmlpagefooter name="myfooter">
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
Page {PAGENO} of {nb}
</div>
<div style="width:100%;height:30px;">
	<img src="./img/foot.png" width="100%"/>
</div>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
'.$mainbody.'
</body>
</html>
';
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================

define('_MPDF_PATH','./');
include("./mpdf.php");

$mpdf=new mPDF('c','A4','','',0,0,0,0,0,0); 
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle($type." | Developed By BSD");
$mpdf->SetAuthor("MD Furkanul Islam.");
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);


$mpdf->Output(); exit;

exit;

?>