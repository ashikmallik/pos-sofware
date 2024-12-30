<?php
include '../model/oop.php';
include '../model/Bill.php';
include '../model/FormateHelper.php';
$obj = new Controller();
$bill = new Bill();
$formater = new FormateHelper();


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

//End Dues calculations
//=====================start==============================

if(isset($_GET['zonePrint'])){
    $pdfData =  $obj->view_all_by_cond("tbl_agent", "ag_status='1' AND zone = ".$_GET['zonePrint'].""); // ****** Need Closer Look ****
}else{
    $pdfData =  $obj->view_all_by_cond("tbl_agent", "ag_status='1' limit $start,50");
}

//=======================end============================
$i = 1;
$sum = 0;
$mainbody = "";
$diff = 0;
$tr = '';
$custom_details = '<tr><td style="color:#E9EAEC;">0</td><td ></td><td></td><td></td><td></td></tr>';
foreach ($pdfData as $detailsid) {

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
        $dueAmount = 'Due : </span>'.number_format($diff, 2, ".", ",");
        $tr = '<tr>
					<td align="center">2</td>
					<td align="center">Previous due</td>
					<td>--</td>
					<td>--</td>
					<td align="center">' . number_format($diff, 2, ".", ",") . '</td>
				</tr>';
    } else if ($diff < 0) {
        $dueAmount = 'Advance : </span>'.number_format($diff * (-1), 2, ".", ",");
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
                                
                <div width = "19%" class="leftAlign token">
                    <div width = "100%" class="heading text-center">
                        <img class="tokenHeader" src="./img/logoForToken.png"/>
                    </div>
                    <h6 class="text-bold text-center"> For Office Use</h6>
                    <div class="tokenContent">
                            <p><span class ="text-bold">Name :</span>' . $detailsid['ag_name'] . '</p>
                            <p><span class ="text-bold">IP :</span>' . $detailsid['ip'] . '</p>
                            <p><span class ="text-bold">Charge :</span>' . number_format($detailsid['taka'], 2, ".", ",") . '</p>
                            <p><span class ="text-bold">' .$dueAmount. '</p>
                            <p><span class ="text-bold">Total :</span>' . number_format($bill->get_customer_dues($detailsid['ag_id']), 2, ".", ",") . ' BDT</p>
                            <p>Comments: </p>
                            <p></p>
                            <p></p>
                            <div class="text-center">
                                <span style="display:block"> Signature</span>
                            </div>
                    </div>
                </div>
                
                <div width = "80%" class="rightAlign">
                    <div width = "100%" class="heading text-center">
                        <img class="header" src="./img/header.png"/>
                    </div>
                
                    <div width="100%" class ="toUser leftAlign">                    
                        <p>To</p>
                        <p class="text-bold">' . $detailsid['ag_name'] . '</p> ' . $detailsid['ag_office_address'] . '
                    </div>
                                    
                    <div width="100%" class="pan">
                        <table class="table infoTable table-bordered">
                            <th>
                                <tr>
                                    <td class="col-md-4 text-center"><span class ="text-bold">IP :</span>' . $detailsid['ip'] . '</td>
                                    <td class="col-md-4 text-center"><span class ="text-bold">Total Due :</span>' . number_format($bill->get_customer_dues($detailsid['ag_id']), 2, ".", ",") . ' BDT</td>
                                    <td class="col-md-4 text-center"><span class ="text-bold">' . $type . ' No :</span>' . $prefix . $detailsid['ag_id'] . '</td>
                                    <td class="col-md-4 text-center"><span class ="text-bold">Date :</span>' . $date . '</td>
                                </tr>
                            </th>
                        </table>
                    </div>
                    
                    <table width="100%" class="table-bordered items table">
                        <thead>
                            <tr class="bg-grey">
                                <td width="7%">Sl.</td>
                                <td width="43%">Description</td>
                                <td width="10%">Month</td>
                                <td width="15%">Speed</td>
                                <td width="25%">Bill Amount</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center">1</td>
                                <td align="center">Bandwidth Charge - ' . $cat . ' </td>
                                <td align="center"> ' . $month . '</td>
                                <td align="center">' . $detailsid['mb'] . '</td>
                                <td align="center">' . number_format($detailsid['taka'], 2, ".", ",") . '</td>
                            </tr>
                            ' . $tr . '
                        </tbody>
                        <!-- END ITEMS HERE -->
                    </table>
                    <div width = "100%"  class="totalSection">
                        <div width="50%" class="leftAlign">
                        
                            <img class="pull-left termsCondition" src="./img/termsCondition.png"/>
    
                        </div>
                        
                        <div width = "50%" class="rightAlign">
                            <div class="rightAlign">
                                <div width = "78%" style="float:right;">
                                    <div class="total">
                                        Total : ' . number_format($bill->get_customer_dues($detailsid['ag_id']), 2, ".", ",") . ' BDT
                                    </div>
                                    <div class="inWord">
                                        In Word : 
    ' . $formater -> convert_number_to_words($bill->get_customer_dues($detailsid['ag_id'])) . '
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div width="100%" class = "text-center">
                            <hr style = "margin:3px;">
                            <p class="text-muted text-bold" style="font-size: 7pt;">Powered By Bangladesh Software Development (BSD)</p>
                        </div>
                    </div>
                </div>
                <div width="100%">
                <img src="./img/divider.png"/>
                </div>
                ';

}// foreach loop ends here

define('_MPDF_PATH', './');
include("./mpdf.php");

$mpdf = new mPDF('c', 'A4', '', '', 0, 0, 0, 0, 0, 0);
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle($type . " | Developed By BSD");
$mpdf->SetAuthor("BSD");
$mpdf->SetDisplayMode('fullpage');
//$mpdf->WriteHTML($html);

$xhtml =
    '<html>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- External CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        </head>
        <body>
            <div class="contain">
                ' . $mainbody . '
            </div><!-- Contain -->
            
        </body>
    </html>';

$mpdf->WriteHTML($xhtml);

$mpdf->Output();

exit;

?>
