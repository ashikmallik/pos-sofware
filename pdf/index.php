<?php

header('Location: ../?q=main');

include '../model/Controller.php';
include '../model/FormateHelper.php';

$formater = new FormateHelper();
$obj = new Controller();

$custom_details = "";
$i = 0;
$date = date('d.m.Y');
$month = date('F');
$prefix = date('Ym');
$html = "";


// ==================== GET Initialize ==================

$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($action) {
    if ($action == 'purchase') {
        $pdfData = $obj->view_all_by_cond("vw_purchase", "bill_id='$id'");

    } else if ($action == 'sell') {

        $pdfData = $obj->view_all_by_cond("vw_sell", "sell_id='$id'");
    }
}

$i = 1;
$sum = 0;
$mainbody = "";
$total_due = 0;
$total_taka = 0;
$payment = 0;
$discount = 0;
$dueText = '';
$due = 0;
$sabek = 0;
$sorboMot = 0;
foreach ($pdfData as $details) {
    $i++;

    if ($action == 'purchase') {
        $supplierId = $details['suplier'];

        $supplierData = $obj->details_by_cond("tbl_supplier", "supplier_id='$supplierId'");
        $name = $supplierData['supplier_name'];
        $address = $supplierData['supplier_address'];
        $phone = $supplierData['supplier_mobile_no'];

        $supplierOrCustomerTransection = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$supplierId'");
        $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved","supplier_customer='$supplierId'");

        $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance","supplier_customer='$supplierId'");
        
        $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance","supplier_customer='$supplierId'");

        $discountData = $obj->details_selected_field_by_cond("discount","sum(`amount`) as amount", "cus_or_sup_id='$supplierId'");

        isset($discountData) ? $discount = $discountData['amount'] : $discount = 0 ;
        isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0 ;
        
        isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0 ;

        $total_due = ($supplierOrCustomerTransection['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance);

        $payment = $details['payment_amount'];

        $number = $details['bill_id'];

        $item = "বিল ";

        $due = $details['due'];

    } else if ($action == 'sell') {
        $customerId = $details['customer'];

        $customerData = $obj->details_by_cond("tbl_customer", "cus_id='$customerId'");
        $name = $customerData['cus_name'];
        $address = $customerData['cus_address'];
        $phone = $customerData['cus_mobile_no'];

        $supplierOrCustomerTransection = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$customerId'");
        $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved","supplier_customer='$customerId'");

        $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance","supplier_customer='$customerId'");
        
        $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance","supplier_customer='$customerId'");
        
        $discountData = $obj->details_selected_field_by_cond("discount","sum(`amount`) as amount", "cus_or_sup_id='$customerId'");

        isset($discountData) ? $discount = $discountData['amount'] : $discount = 0 ;
       
        isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0 ;
        
        isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0 ;

        $total_due = ($supplierOrCustomerTransection['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance);

        $payment = $details['payment_recieved'];

        $number = $details['sell_id'];

        $item = "মেমো ";

        $due = $details['due_to_company'];
    }

    if ($details['redA_qty'] != '0' && $details['redArate'] != '0.00') {
        $total_taka += $total_redA = ($details['redA_qty'] * $details['redArate']);
        $redA = '<tr>                   
                   <td style="font-size:14px; text-align:center" class = " bangla"> লাল  ডিম A</td>
                   <td style="font-size:14px; text-align:center">' . number_format($details['redA_qty']) . '</td>
                   <td style="font-size:14px; text-align:center" class = " bangla">' . ($details['redArate']) . ' টা./পিস</td>
                   <td style="font-size:14px; text-align:center">' . number_format($total_redA) . '</td>
                 </tr>';
    } else {
        $redA = '';
    }

    if ($details['redB_qty'] != '0' && $details['redBrate'] != '0.00') {
        $total_taka += $total_redB = ($details['redB_qty'] * $details['redBrate']);
        $redB = '<tr>                   
                   <td style="font-size:14px; text-align:center" class = " bangla"> লাল  ডিম B</td>
                   <td style="font-size:14px; text-align:center">' . number_format($details['redB_qty']) . '</td>
                   <td style="font-size:14px; text-align:center" class = " bangla">' . ($details['redBrate']) . ' টা./পিস</td>
                   <td style="font-size:14px; text-align:center">' . number_format($total_redB) . '</td>)
                 </tr>';
    } else {
        $redB = '';
    }

    if ($details['redC_qty'] != '0' && $details['redCrate'] != '0.00') {
        $total_taka += $total_redC = ($details['redC_qty'] * $details['redCrate']);
        $redC = '<tr>                   
                   <td style="font-size:14px; text-align:center" class = " bangla"> লাল  ডিম C</td>
                   <td style="font-size:14px; text-align:center">' . number_format($details['redC_qty']) . '</td>
                   <td style="font-size:14px; text-align:center" class = " bangla">' . ($details['redCrate']) . ' টা./পিস</td>
                   <td style="font-size:14px; text-align:center">' . number_format($total_redC) . '</td>
                 </tr>';
    } else {
        $redC = '';
    }
    if ($details['whiteA_qty'] != '0' && $details['whiteArate'] != '0.00') {
        $total_taka += $total_whiteA = ($details['whiteA_qty'] * $details['whiteArate']);
        $whiteA = '<tr>                  
                   <td style="font-size:14px; text-align:center" class = " bangla">  সাদা   ডিম A</td>
                   <td style="font-size:14px; text-align:center">' . number_format($details['whiteA_qty']) . '</td>
                   <td style="font-size:14px; text-align:center" class = " bangla">' . ($details['whiteArate']) . ' টা./পিস</td>
                   <td style="font-size:14px; text-align:center">' . number_format($total_whiteA) . '</td>
                 </tr>';
    } else {
        $whiteA = '';
    }

    if ($details['whiteB_qty'] != '0' && $details['whiteBrate'] != '0.00') {
        $total_taka += $total_whiteB = ($details['whiteB_qty'] * $details['whiteBrate']);
        $whiteB = '<tr>                   
                   <td style="font-size:14px; text-align:center" class = " bangla"> সাদা   ডিম B</td>
                   <td style="font-size:14px; text-align:center">' . number_format($details['whiteB_qty']) . '</td>
                   <td style="font-size:14px; text-align:center" class = " bangla">' . ($details['whiteBrate']) . ' টা./পিস</td>
                   <td style="font-size:14px; text-align:center">' . number_format($total_whiteB) . '</td>
                 </tr>';
    } else {
        $whiteB = '';
    }

    if ($details['whiteC_qty'] != '0' && $details['whiteCrate'] != '0.00') {
        $total_taka += $total_whiteC = ($details['whiteC_qty'] * $details['whiteCrate']);
        $whiteC = '<tr>                 
                   <td style="font-size:14px; text-align:center" class = " bangla"> সাদা   ডিম C</td>
                   <td style="font-size:14px; text-align:center">' . number_format($details['whiteC_qty']) . '</td>
                   <td style="font-size:14px; text-align:center" class = " bangla">' . ($details['whiteCrate']) . ' টা./পিস</td>
                   <td style="font-size:14px; text-align:center">' . number_format($total_whiteC) . '</td>
                 </tr>';
    } else {
        $whiteC = '';
    }
    
    
    if ($details['duckEgg_qty'] != '0' && $details['duckEggRate'] != '0.00') {
        $total_taka += $total_duckEgg = ($details['duckEgg_qty'] * $details['duckEggRate']);
        $total_amount += $details['duckEgg_qty'];
        $duckEgg = '<tr>                 
                   <td style="font-size:14px; text-align:center" class = " bangla"> হাসের ডিম </td>
                   <td style="font-size:14px; text-align:center">' . number_format($details['duckEgg_qty']) . '</td>
                   <td style="font-size:14px; text-align:center" class = " bangla">' . ($details['duckEggRate']) . ' টা./পিস</td>
                   <td style="font-size:14px; text-align:center">' . number_format($total_duckEgg) . '</td>
                 </tr>';
    } else {
        $duckEgg = '';
    }
    
    if ($details['birdEgg_qty'] != '0' && $details['birdEggRate'] != '0.00') {
        $total_taka += $total_birdEgg = ($details['birdEgg_qty'] * $details['birdEggRate']);
        $total_amount += $details['birdEgg_qty'];
        $birdEgg = '<tr>                 
                   <td style="font-size:14px; text-align:center" class = " bangla"> পাখির ডিম </td>
                   <td style="font-size:14px; text-align:center">' . number_format($details['birdEgg_qty']) . '</td>
                   <td style="font-size:14px; text-align:center" class = " bangla">' . ($details['birdEggRate']) . ' টা./পিস</td>
                   <td style="font-size:14px; text-align:center">' . number_format($total_birdEgg) . '</td>
                 </tr>';
    } else {
        $birdEgg = '';
    }


    if ($details['damage_qty'] != '0' && $details['damageRate'] != '0.00') {
        $total_taka += $total_damage = ($details['damage_qty'] * $details['damageRate']);
        $total_amount += $details['damage_qty'];
        $damage = '<tr>
                   <td style="font-size:14px; text-align:center" class = " bangla"> নষ্ট  ডিম </td>
                   <td style="font-size:14px; text-align:center">' . number_format($details['damage_qty']) . '</td>
                   <td style="font-size:14px; text-align:center" class = " bangla">' . ($details['damageRate']) . ' টা./পিস</td>
                   <td style="font-size:14px; text-align:center">' . number_format($total_damage) . '</td>
                 </tr>';
    } else {
        $damage = '';
    }

    $sabek = $total_due - $due;
    $advance_or_due = ($sabek < 0)?'(অগ্রিম)': '';
    $sorboMot = $sabek + $total_taka;

    $presentDue = $sorboMot - $payment;

    if($presentDue < 0){
        $dueText = '<div class="total bangla">
                        অগ্রিম    : ' . number_format(abs($presentDue)) . ' টাকা
                    </div>';
    }else{
        $dueText = '<div class="total bangla">
                        বাকি   : ' . number_format($presentDue) . ' টাকা
                    </div>';
    }

    $mainbody .= '              
                <div width = "100%">
                    <div class="heading text-center">
                        <img class="header" src="./img/header.png"/>
                    </div>
                
                    <div width="100%" class ="numberDate">                    
                        <p width="74%" class="bangla leftAlign">'.$item.' নং : ' . $number . '</p>
                        <div width="23%" class="rightAlign">
                            <p width="34%" class="text-center bangla leftAlign"> তারিখ :</p>
                            <p width="64%" class="text-center rightAlign"> ' . date('d-m-Y', strtotime($details['entry_date'])) . '</p>
                        </div> 
                    </div>
                    
                    <div width="100%">                    
                        <div class="border-grey-800 ">
                            <p style="padding-left:5px;" width="58%" class="leftAlign"><span class = 
                            "bangla"> নাম  </span>: ' . $name . '</p>
                            <p style="padding-right:5px;" width="38%" class="rightAlign"><span class = "bangla">  
                            মোবাইল   </span>: ' . $phone . '</p>
                        </div>
                        <p style="padding-left:5px;" class="border-grey-800 "><span class = "bangla"> 
                  ঠিকানা  </span>: ' . $address . '</p>
                    </div>
                    
                    <table width="100%" class="table-bordered items table" style="margin-top:10px;">
                        <thead>
                            <tr class="bg-grey">
                                <td class="bangla" width="37%"> বিবরণ </td>
                                <td class="bangla" width="15%"> পরিমাণ </td>
                                <td class="bangla" width="15%"> দর </td>
                                <td class="bangla" width="25%">  টাকা </td>
                            </tr>
                        </thead>
                        <tbody id="invoice">
                            ' . $redA . '
                            ' . $redB . '
                            ' . $redC . '
                            ' . $whiteA . '
                            ' . $whiteB . '
                            ' . $whiteC . '
                            ' . $duckEgg . '
                            ' . $birdEgg . '
                            ' . $damage . '
                           <tr>
                               <td colspan="3" style="font-size:14px; text-align:center" class = " bangla"> মোট  টাকা </td>
                               <td style="font-size:14px; text-align:center" class = " bangla">' . number_format($total_taka) . '   টাকা</td>
                               </tr>
                        </tbody>
                    </table>
                    <div width = "100%"  class="totalSection">
                        <div width="70%" class="leftAlign">
                        </div>
                        <div width = "30%" class="rightAlign">
                            <div class="total bangla">
                                সাবেক '.$advance_or_due.' : ' . number_format(abs($sabek)) . ' টাকা
                            </div>
                            <div class="total bangla">
                                 সর্বমোট  : ' . number_format(abs($sorboMot)) . ' টাকা
                            </div>
                            <div class="total bangla">
                                মোট  পেমেন্ট : ' . number_format($payment) . ' টাকা
                            </div>
                            '.$dueText.'
                        </div>
                        <div width="100%" class = "footer text-center">
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

$mpdf = new mPDF('', 'A4', '', 'nikosh', 0, 0, 0, 0, 0, 0, 0);
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle($type . " | Developed By BSD");
$mpdf->SetAuthor("BSD");
$mpdf->SetDisplayMode('fullpage');

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
