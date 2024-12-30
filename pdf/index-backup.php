<?php
include '../model/oop.php';
$obj = new Controller();
include '../model/Bill.php';
$bill = new Bill();
include '../model/FormateHelper.php';
$formater = new FormateHelper();
//======= Object Created from Class ======
$custom_details = "";
$i = 0;
$cu_type = "CUSTOMER ID";
$type = isset($_GET['flag']) ? $_GET['flag'] : "RECEIPT";
$img = "foot.png";
if ($type == "RECEIPT") {
    $img = "foot2.png";
}
$start = isset($_GET['start']) ? $_GET['start'] : 0;
$date = date('d.m.Y');
$month = date('F');
$prefix = date('Ym');
$html = "";

$i = 1;
$sum = 0;
$mainbody = "";
$diff = 0;
$tr = '';
$custom_details = '<tr><td style="color:#E9EAEC;">0</td><td ></td><td></td><td></td><td></td></tr>';
foreach ($obj->view_all_by_cond("tbl_agent", "ag_status='1' limit $start,50") as $detailsid) {
    $tr = '';
    $diff = $bill->get_customer_dues($detailsid['ag_id']) - $detailsid['taka'];
    $cat_id = $detailsid['bill_cat'];
    $cat = "";
    if ($cat_id == 1) {
        $cat = "Monthly";
    } else if ($cat_id == 2) {
        $cat = "Half Yearly";
    } else if ($cat_id == 3) {
        $cat = "Yearly";
    }
    if ($diff == 0) {
        $tr = $custom_details;
    } else if ($diff > 0) {
        $tr = '<tr>
					<td align="center">2</td>
					<td >Previous due</td>
					<td>--</td>
					<td>--</td>
					<td align="center">' . number_format($diff, 2, ".", ",") . '</td>
				</tr>';
    } else if ($diff < 0) {
        $tr = '<tr>
					<td align="center">2</td>
					<td >Advanced payment</td>
					<td>--</td>
					<td>--</td>
					<td align="center">' . number_format($diff * (-1), 2, ".", ",") . '</td>
				</tr>';
    }
    $tr = $tr . $custom_details;

    $mainbody .= '
	<div width"100%" style="height=""297mm">
	<div style="width:100%;height:30px;">
		<img src="./img/header.png" width="100%"/>
	</div>
	<div width"100%" style="padding-left:12%;font-size:12px;margin-top:-75px;">
<p>To</p>
<p  style="font-size:16px;"><b>' . $detailsid['ag_name'] . '</b></p>
' . $detailsid['ag_office_address'] . '
</div>
<div style="width:100%;display:inline-block;margin-top:25px;color:white;font-size:14px;">
<div width="27%" height="40px" style="text-align:center;color:#000;background-color:#FFF;display:inline-block;padding:25px;float:left;border-right:3px solid white;" >
' . $cu_type . '<br>' . $detailsid['cus_id'] . '
</div>
<div width="60%" height="42px" style="background-color:#FFF; color:#000;display:inline-block;padding:25px;float:left;text-align:center;" >
<div width="33.33%" height="42px" style="background-color:#FFF;display:inline-block;color:#000;float:left;" >
Total Due:<br>' . number_format( $bill->get_customer_dues($detailsid['ag_id']), 2, ".", ",") . ' BDT
</div>
<div width="33.33%" height="42px" style="background-color:#FFF;display:inline-block;float:left;" >
' . $type . ' No.<br>' . $prefix . $detailsid['ag_id'] . '
</div>
<div width="33.33%" height="42px" style="background-color:#FFF;color:#000;display:inline-block;float:left;" >
Date:<br>
' . $date . '
</div>
</div>
</div>
<br />
<br />

<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
<thead>
	<tr>
		<td width="7%">Sl.</td>
		<td width="43%">Description</td>
		<td width="10%">Month</td>
		<td width="15%">Speed</td>
		<td width="25%">Bill Amount</td>
	</tr>
	
</thead>
<tbody>
<!-- ITEMS HERE start here value comes from top -->
	<tr>
		<td align="center">1</td>
		<td align="left">Bandwidth Charge - ' . $cat . ' </td>
		<td align="center"> ' . $month . '</td>
		<td align="center">' . $detailsid['mb'] . '</td>
		<td align="center">' . number_format($detailsid['taka'], 2, ".", ",") . '</td>
	</tr>
	' . $tr . '
<!-- END ITEMS HERE -->
</tbody>
</table>
<div style="width:100%;display:inline-block;margin-top:100px;margin-bottom:50mm;color:white;font-size:14px;">
<div width="60%" height="40px" style="text-align:center;font-size:32px;font-weight:900;display:inline-block;padding:5px;float:left;border-right:3px solid white;" ></div>
<div width="38%" style="display:inline-block;float:left;text-align:center;" >
<div  style="background-color:#CCC;display:inline-block;color:#000;float:left;padding:5px;" >
Total : ' . number_format( $bill->get_customer_dues($detailsid['ag_id']), 2, ".", ",") . ' BDT
</div>
<div  style="text-align:left;display:inline-block;float:left;color:black;padding-top:10px;" >
In word:<br>
' . $formater -> convert_number_to_words( $bill->get_customer_dues($detailsid['ag_id'])) . '
</div>

</div>
</div>

</div>
	';
}

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
background-color: #eaecef;
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
	<img src="./img/' . $img . '" width="100%"/>
</div>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
' . $mainbody . '
</body>
</html>
';
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================

define('_MPDF_PATH', './');
include("./mpdf.php");

$mpdf = new mPDF('c', 'A4', '', '', 0, 0, 0, 0, 0, 0);
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle($type . " | Developed By BSD");
$mpdf->SetAuthor("MD Furkanul Islam.");
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);


$mpdf->Output();
exit;

exit;

?>