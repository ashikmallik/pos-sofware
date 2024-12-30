<?php

include '../model/oop.php';
include '../model/Bill.php';
include '../model/FormateHelper.php';
$obj = new Controller();
$bill = new Bill();
$formater = new FormateHelper();

//======= Object Created from Class ======

$agent_id = isset($_GET['agent']) ? $_GET['agent'] : null;
$custom_details = "";
$cu_type = "CUSTOMER ID";
$img = "foot.png";
$date = date('d.m.Y');
$html = "";

$mainbody = "";
$tr = '';
$custom_details = '';
$detailsid = $obj->details_by_cond("tbl_agent", "ag_status='1' and ag_id='$agent_id'");
$dueConChargeRunningMonth = $obj->details_by_cond('tbl_due_opening_amount_and_con_charge', 'tbl_agent_id = ' . $agent_id);
$openingAmountAccData = $obj->details_by_cond('tbl_account', "agent_id = $agent_id AND acc_type = 5");
var_dump($openingAmountAccData);

$connectionChargeDue = isset($dueConChargeRunningMonth['connection_charge_due']) ? $dueConChargeRunningMonth['connection_charge_due'] : 0;
$runningMonthDue = isset($dueConChargeRunningMonth['running_month_due']) ? $dueConChargeRunningMonth['running_month_due'] : 0;

$connectionChargeTr = '<tr>
            <td align="center">1</td>
            <td align="center">Connection Charge </td>
            <td align="center">' . number_format($connectionChargeDue, 2, ".", ",") . '</td>
            <td align="center">' . number_format($detailsid['connect_charge'], 2, ".", ",") . '</td>
           </tr>';

$openingAmountTr = '<tr>
            <td align="center">1</td>
            <td align="center">Running Month Bill </td>
            <td align="center">' . number_format($runningMonthDue, 2, ".", ",") . '</td>
            <td align="center">' . number_format($openingAmountAccData['acc_amount'], 2, ".", ",") . '</td>
           </tr>';

$totalCash = intval($detailsid['connect_charge']) + intval($openingAmountAccData['acc_amount']);
$totalDue = intval($connectionChargeDue) + intval($runningMonthDue);

/*
* Zone Search for Zone name
*/

if (isset($detailsid['zone']) && !empty($detailsid['zone'])) {
    $zoneData = $obj->details_by_cond('tbl_zone', "zone_id =" . $detailsid['zone'] . "");
} else {
    $zoneData['zone_name'] = 'N/A';
}

$mainbody .= '
               <div height = "49%" style="padding-top:1%">
                <div width="100%" class="text-center">
                   <h4>Client Copy</h4>
                </div>
               <div width = "94%" style="margin:3%;" class="rightAlign">
                    <div width = "100%" style="margin-top:7px" class="heading text-center">
                        <img style="height:500px; width:auto;" src="./img/header.png"/>
                    </div>
                
                    <div width="100%" class ="toUser leftAlign">                    
                        <p>To</p>
                        <p><span class="text-bold">Name : </span>' . $detailsid['ag_name'] . '</p>
                        <p><span class="text-bold">Address : </span>' . $detailsid['ag_office_address'] . '</p>
                        <p><span class="text-bold">Mobile : </span>' . $detailsid['ag_mobile_no'] . '</p>
                    </div>
                                    
                    <div width="100%" class="pan">
                        <table class="table infoTable table-bordered">
                            <th>
                                <tr style="border:1px solid #ddd;">
                                    <td class="col-md-4"><span class ="text-bold"> No : </span>' . $detailsid['cus_id'] . '</td>
                                    <td class="col-md-4"><span class ="text-bold"> Date : </span>' . $date . '</td>
                                    <td class="col-md-4"><span class ="text-bold"> Speed :</span>' . str_replace('_', '.', $detailsid['mb']) . '</td>
                                </tr>
                            </th>
                        </table>
                    </div>
                    
                    <table width="100%" style="padding:3mm 0;" class="table-bordered items table">
                        <thead>
                            <tr class="bg-grey">
                                <td width="10%">Sl.</td>
                                <td width="40%">Description</td>
                                <td width="25%">Due Amount</td>
                                <td width="25%">Cash Amount</td>
                            </tr>
                        </thead>
                        <tbody>
                            ' . $connectionChargeTr . '
                            
                            ' . $openingAmountTr . '
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
                                        Total Cash: ' . number_format($totalCash, 2, ".", ",") . ' BDT
                                    </div>
                                    <div class="inWord">
                                        In Word : 
    ' . $formater->convert_number_to_words($bill->get_customer_dues($detailsid['ag_id'])) . ' Taka.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div width="100%" class="text-center">
                            <div width="90% rightAlign">
                                <span style="">Signature................................. </span>
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
            </div>
            <div height = "49%" style="padding-top:0%">
                <div width="100%" class="text-center">
                   <h4>Office Copy</h4>
                </div>
               <div width = "94%" style="margin:3%;" class="rightAlign">
                    <div width = "100%" style="margin-top:7px" class="heading text-center">
                        <img style="height:500px; width:auto;" src="./img/header.png"/>
                    </div>
                
                    <div width="100%" class ="toUser leftAlign">                    
                        <p>To</p>
                        <p><span class="text-bold">Name : </span>' . $detailsid['ag_name'] . '</p>
                        <p><span class="text-bold">Address : </span>' . $detailsid['ag_office_address'] . '</p>
                        <p><span class="text-bold">Mobile : </span>' . $detailsid['ag_mobile_no'] . '</p>
                    </div>
                                    
                    <div width="100%" class="pan">
                        <table class="table infoTable table-bordered">
                            <th>
                                <tr style="border:1px solid #ddd;">
                                    <td class="col-md-4"><span class ="text-bold"> No : </span>' . $detailsid['cus_id'] . '</td>
                                    <td class="col-md-4"><span class ="text-bold"> Date : </span>' . $date . '</td>
                                    <td class="col-md-4"><span class ="text-bold"> Speed :</span>' . str_replace('_', '.', $detailsid['mb']) . '</td>
                                </tr>
                            </th>
                        </table>
                    </div>
                    
                    <table width="100%" style="padding:3mm 0;" class="table-bordered items table">
                        <thead>
                            <tr class="bg-grey">
                                <td width="10%">Sl.</td>
                                <td width="40%">Description</td>
                                <td width="25%">Due Amount</td>
                                <td width="25%">Cash Amount</td>
                            </tr>
                        </thead>
                        <tbody>
                            ' . $connectionChargeTr . '
                            
                            ' . $openingAmountTr . '
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
                                        Total Cash: ' . number_format($totalCash, 2, ".", ",") . ' BDT
                                    </div>
                                    <div class="inWord">
                                        In Word : 
    ' . $formater->convert_number_to_words($bill->get_customer_dues($detailsid['ag_id'])) . ' Taka.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div width="100%" class="text-center">
                            <div width="90% rightAlign">
                                <span style="">Signature................................. </span>
                            </div>
                        </div>
                        <div width="100%" class = "text-center">
                            <hr style = "margin:3px;">
                            <p class="text-muted text-bold" style="font-size: 7pt;">Powered By Bangladesh Software Development (BSD)</p>
                        </div>
                    </div>
                </div>
            </div>
                ';

$html =
    '<html>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- External CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <style>
            td{
                height:30px;
            }
        </style>
        </head>
        <body>
            <div style="height:297mm; width: 210mm; margin: 4mm;">
                ' . $mainbody . '
            </div><!-- Contain -->
            
        </body>
    </html>';
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
$mpdf->SetAuthor("BSD.");
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);


$mpdf->Output();
exit;

exit;
?>
