<?php
session_start();
if (!isset($_GET['invoiceId']) || empty($_GET['invoiceId'])) {
    echo '<h4>Sorry ! Wrong Url .</h4>';
    die();
}

include '../model/Controller.php';
include '../model/FormateHelper.php';

$obj = new Controller();
$formater = new FormateHelper();
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$userdetails = $obj->details_by_cond("_createuser", "UserId='$userid'");
$username = $userdetails['FullName'];
$custom_details = "";
$i = 0;
$date = date('d.m.Y');
$month = date('F');
$prefix = date('Ym');
$html = "";

// ==================== GET Initialize ==================

$invoiceId = isset($_GET['invoiceId']) ? $_GET['invoiceId'] : null;
// $invoiceId =9341;

$invoiceData = $obj->details_by_cond("vw_sell", "sell_id='$invoiceId'");
$invoiceExtraData = $obj->details_by_cond("tbl_sell_invoice", "sell_id='$invoiceId'");
$total_payable = $invoiceData['total_price'];
$labour_name = @$invoiceData['labour_name'];
$remark = @$invoiceData['remark'];

$i = 1;
$sum = 0;
$mainbody = "";
$total_due = 0;
$total_taka = 0;
$total_amount = 0;
$payment = 0;
$discount = 0;
$dueText = '';
$sabek = 0;
$sorboMot = 0;



$customerId = $invoiceData['customer'];
//$customerPersonalData =$obj->view_all("tbl_customer");
$sellId = $invoiceData['sell_id'];
$sellData = $obj->details_by_cond('vw_sell', "sell_id = $sellId");
//$salesmanId = $sellData['salesman'];
//$salesID = $obj->details_by_cond('salesman', "salesman_id = $salesmanId");


$sellDate = $invoiceData['entry_date'];

$sellItemData = $obj->view_all_by_cond('vw_sell_item', "sell_id = $sellId  ORDER BY `vw_sell_item`.`discount_exist` DESC");

$discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$customerId'");

isset($discountData) ? $discount = $discountData['amount'] : $discount = 0;

$customerData = $obj->details_by_cond("tbl_customer", "cus_id='$customerId'");
$name = $customerData['cus_name'];
$address = $customerData['cus_address'];
$phone = $customerData['cus_mobile_no'];


//////////////////////////////////////////
$totalamounsell = $invoiceData['total_price'];
// $payment = $invoiceData['payment_recieved'];
$payment = $obj->get_sum_data('tbl_account', 'acc_amount', " purchase_or_sell_id='s_$sellId' AND acc_type = 3");
$due = $invoiceData['due_to_company'];
$less_amount = $invoiceData['less_amount'];


$type_from_invoice = $invoiceExtraData['type'];
if($type_from_invoice == 7)
{
    $current= $invoiceExtraData['old_previous_advance_due']+$due;
    $previous= $invoiceExtraData['old_previous_advance_due'];



}elseif($type_from_invoice == 8)
{
    $current= $invoiceExtraData['old_previous_advance_due']+$due;
    $previous = $invoiceExtraData['old_previous_advance_due'];

}else{
 
    $current = '';
    $previous = '';
}

if($current < 0){
        // $ttext =  '<div class="total bangla"> Total Advance    : ' . number_format(abs($current),2) . ' Tk  </div>';

        $ttext =  '<tr>
        <th colspan="6" style="font-size:14px; padding:5px; text-align:right">Total Advance</th>
        <th style="font-size:14px; text-align:right;padding:5px;">' . number_format(abs($current),2) . '</th>
        </tr>';
    
}elseif($current > 0){
        // $ttext =  '<div class="total bangla"> Total Due    : ' . number_format(abs($current),2) . ' Tk  </div>';

        $ttext =  '<tr>
        <th colspan="6" style="font-size:14px; padding:5px; text-align:right">Total Due</th>
        <th style="font-size:14px; text-align:right;padding:5px;">' . number_format(abs($current),2) . '</th>
        </tr>';

}else{
    $ttext='';
}

if($previous < 0){
        $preAdv=abs($previous);
        $ptext =  '<tr>
        <th colspan="6" style="font-size:14px; padding:5px; text-align:right">Previous Advance</th>
        <th style="font-size:14px; text-align:right;padding:5px;">(-)' . number_format(abs($previous),2) . '</th>
        </tr>';
        
    
}elseif($previous > 0){
        $preDue=abs($previous);
        $ptext =  '<tr>
        <th colspan="6" style="font-size:14px; padding:5px; text-align:right">Previous Due</th>
        <th style="font-size:14px; text-align:right;padding:5px;">(+)' . number_format(abs($previous),2) . '</th>
        </tr>';

}else{
    $ptext='';
    $preDue=0;
    $preAdv=0;
}



$transportCost = $invoiceData['transportcost'];
$laborCost= $invoiceData['laborcost'];


if(($transportCost > 0) && ($laborCost > 0) ){
    $tClC=$transportCost+$laborCost;
    $tClCtext='<tr>
    <th></th>
    <th style="font-size:14px; padding:5px; text-align:left">Transport Cost ' . number_format(abs($transportCost),2) . '</th>
    <th style="font-size:14px; padding:5px; text-align:left">Labor Cost</th>
    <th style="font-size:14px; padding:5px; text-align:left;">' . number_format(abs($laborCost),2) . '</th>
    <th style="font-size:14px; padding:5px; text-align:right;">Cost Amount</th>
    <th style="font-size:14px; padding:5px; text-align:right;">(+) ' . number_format(abs($tClC),2) . '</th>
    </tr>';
}elseif($transportCost > 0){
   $tClC=$transportCost;
   $tClCtext='<tr>
    <th></th>
    <th style="font-size:14px; padding:5px; text-align:left">Transport Cost ' . number_format(abs($transportCost),2) . '</th>
    <th></th>
    <th></th>
    <th style="font-size:14px; padding:5px; text-align:right;">Cost Amount</th>
    <th style="font-size:14px; padding:5px; text-align:right;">(+) ' . number_format(abs($tClC),2) . '</th>
    </tr>';
}elseif($laborCost > 0){
   $tClC=$laborCost;
   $tClCtext='<tr>
    <th></th>
    <th style="font-size:14px; padding:5px; text-align:left">Labor Cost ' . number_format(abs($laborCost),2) . '</th>
    <th></th>
    <th></th>
    <th style="font-size:14px; padding:5px; text-align:right;">Cost Amount</th>
    <th style="font-size:14px; padding:5px; text-align:right;">(+) ' . number_format(abs($tClC),2) . '</th>
    </tr>';
}else{
    $tClC=0;
    $tClCtext='';
}


/////////////////////////////////////////////////////////////


$discountRow = '';
$invoiceData = '';
$invoiceRow = '';
$totalUnit_comission = 0;

$i = 1;
foreach ($sellItemData as $sellItem) {

    $unit = isset($sellItem['unit'])? $sellItem['unit']: NULL;

    $total_unit_price = ($sellItem['qty'] * $sellItem['price']);

    $total_unit_price_new = $sellItem['price'] * $sellItem['qty'];
    $totalUnit_comission += number_format($purchaseItem['commission_per_unit'],2);

    $total_taka += $total_unit_price_with_discount = $total_unit_price_new - ($total_unit_price_new * $sellItem['discount_exist'] / 100);

    $total_amount += $sellItem['qty'];

    if ($sellItem['discount_exist'] != '0') {
        $discountRow = '';
    } else {
        $discountRow = '';
    }

    if ($less_amount != '0') {
        $lessAmountRow = '<tr>
                    <th colspan="6" style="font-size:14px;text-align: right;font-weight: bold;">Less Amount &nbsp;</th>
                    <th style="font-size:14px;font-weight: bold; text-align:right;padding-right: 5px;">(-) ' . number_format($less_amount,2) . '</th>
                 </tr>';
    } else {
        $lessAmountRow = '';
    }

    $per_discount_amount = $total_unit_price_new *($sellItem['discount_exist'] / 100);
    $per_item_price_with_discount = $total_unit_price_new - $per_discount_amount;

    $invoiceRow .= '<tr>
                   <td style="font-size:16px; text-align:center">' . $i++ . '</td>
                   <td style="font-size:16px; text-align:center">' . $sellItem['product_name'] . '</td>
                   <td style="font-size:16px; text-align:center" >' . $sellItem['qty'] .' '.$unit.' </td>
                   <td style="font-size:16px; text-align:right">' . number_format($sellItem['price'],2) . '</td>
                   <td style="font-size:12px; text-align:right">' . number_format($sellItem['commission_per_unit'],2) . ' tk</td>
                   <td style="font-size:16px; text-align:right">' . number_format($per_discount_amount,2) . '</td>
                   <td style="font-size:16px; text-align:right;padding-right: 5px;">' . number_format($per_item_price_with_discount,2) . '</td>
                 </tr>
                 ' . $discountRow;

}

$sabek = $total_due - $due;
$advance_or_due = ($sabek < 0) ? ' Advance' : ' Due';
$sorboMot = $sabek + $total_taka;

$presentDue = $sorboMot - $payment;
if ($invoiceId < 10) {$STD = "0000";}
else if ($invoiceId < 100) {$STD = "000";}
else if ($invoiceId < 1000) {$STD = "00";}
else if ($invoiceId < 10000) {$STD = "0";}
else {$STD = "";}

if ($invoiceExtraData['delivery_date']!='0000-00-00'){$delivery_date = date('d/m/Y',strtotime($invoiceExtraData['delivery_date']));}else{$delivery_date = '';}

// if($presentDue < 0){
//         $dueText = '<div class="total bangla">
//                       Total Advance    : ' . number_format(abs($presentDue-$less_amount),2) . ' Tk
//                     </div>';
//     }else{
//         $dueText = '<div class="total bangla">
//                        Total Due   : ' . number_format($presentDue-$less_amount,2) . ' Tk
//                     </div>';
//     }




$tAmount =$total_taka+$tClC-$less_amount+$preDue-$preAdv;
$tAmount =($tAmount > 0)?$tAmount:0;
$rAdvdue=($total_taka+$tClC-$less_amount+$preDue-$preAdv)-$payment;

if($rAdvdue > 0){
    $rAdvTxt = '
     <tr>
        <th colspan="6" style="font-size:14px; padding:5px; text-align:right"> Total Due</th>
        <th style="font-size:14px; text-align:right;padding:5px;">' . number_format($rAdvdue,2) . '</th>
    </tr>';
}elseif($rAdvdue < 0){
    $rDueTxt = '
     <tr>
        <th colspan="6" style="font-size:14px; padding:5px; text-align:right"> Total Advance</th>
        <th style="font-size:14px; text-align:right;padding:5px;">' . number_format(abs($rAdvdue),2) . '</th>
    </tr>';
}else{
    $rAdvTxt = '';
    $rDueTxt = '';
}
    $mainbody .= '              
                <div width="100%">
                    <div class="heading text-center">
                        <img class="header" style="height:500px; width:auto;" src="./logobig.png" />
                    </div>
                
                    <div width="100%">
                        <div class="text-center">
                            <h3 class="">Invoice </h3>
                        </div>
                    </div>
                
                    <div width="100%" class="numberDate">
                        <table width="100%" class="infoTable table table-bordered">
                            <tr class="bg-info">
                                <td width="25%" class="bangla"> Customer Id: </td>
                                <td width="42%">' . $customerId.' </td>
                                <td width="20%" class="bangla">Invoice Date : </td>
                                <td width="30%"> ' . date('d-m-Y', strtotime($sellDate)) . ' </td>
                            </tr>
                            <tr class="bg-info">
                                <td width="25%" class="bangla"> Ref No : </td>
                                <td width="42%">'.$invoiceExtraData['ref_no'].'</td>
                                <td width="20%" class="bangla"> Invoice No : </td>
                                <td width="30%" style="font-weight:bold"> '.$STD.$invoiceId.'</td>
                            </tr>
                            <tr class="bg-info">
                                <td width="25%" class="bangla"> Delivery Date : </td>
                                <td width="42%">'.$delivery_date.'</td>
                                <td width="20%" class="bangla"> Sales Contact : </td>
                                <td width="30%" style="font-weight:bold">  </td>
                            </tr>
                            <tr class="bg-info">
                                <td width="25%" class="bangla"> Delivery Chalan : </td>
                                <td width="42%">'.$invoiceExtraData['delivery_challan'].'</td>
                                <td width="20%" class="bangla"> Contact Ref : </td>
                                <td width="30%" style="">'.$invoiceExtraData['contact_ref'].'</td>
                            </tr>
                            <tr class="bg-info">
                                <td width="25%" class="bangla"> Work Order No : </td>
                                <td colspan="3" width="42%">'.$invoiceExtraData['work_order_no'].'</td>
                            </tr>
                            <tr class="bg-warning">
                                <td width="25%" class="bangla"> Customer Name & Address :</td>
                                <td colspan="3" width="92%">' .$name .'<br>'. $address . '</td>
                            </tr>
                            <tr class="bg-warning">
                                <td width="25%" class="bangla"> Contact No :</td>
                                <td colspan="3" width="92%">' .$phone . '</td>
                            </tr>
                            <tr class="bg-warning">
                                <td width="25%" class="bangla"> Delivery Address :</td>
                                <td colspan="3" width="92%">'.$invoiceExtraData['delivery_address'].'</td>
                            </tr>
                
                        </table>
                    </div>
                
                    <div width="100%">
                        Salesman Id : '.$salesID['sman_id'].'
                    </div>
                
                    <table width="100%" class="table-bordered items table" style="margin-top:10px;">
                        <thead>
                            <tr class="bg-grey">
                                <td width="10%"> SL </td>
                                <td width="47%"> Product Description </td>
                                <td width="15%"> Qty </td>
                                <td width="25%"> Rate </td>
                                <td width="25%">Comission(per unit)</td>
                                <td width="25%"> Discount</td>
                                <td width="25%"> Amount (tk)</td>
                            </tr>
                        </thead>
                        <tbody id="invoice">
                            ' . $invoiceRow . '
                            <tr>
                                <th colspan="6" style="font-size:14px; padding:5px; text-align:right"> Total</th>
                                <th style="font-size:14px; text-align:right;padding:5px;">' . number_format($total_taka,2) . '</th>
                            </tr>
                            '.$lessAmountRow.'
                             '.$tClCtext.'
                             '.$ptext.'
                             <tr> <th colspan="7" ><hr style="padding:0;margin:0;"></th> </tr>
                            <tr>
                                <th colspan="3" style="font-size:14px; padding:5px;"> Labour Name: '.$labour_name.'</th>
                                <th colspan="2" style="font-size:14px; padding:5px;"> Remarks:'.$remark.' </th>
                                <th colspan="1" style="font-size:14px; padding:5px; text-align:right"> Total Amount</th>
                                <th style="font-size:14px; text-align:right;padding:5px;">' . number_format($tAmount,2) . '</th>
                            </tr>
                            
                             
                            <tr>
                                <th colspan="6" style="font-size:14px; padding:5px; text-align:right"> Payment</th>
                                <th style="font-size:14px; text-align:right;padding:5px;">(-)' . number_format($payment,2) . '</th>
                            </tr>
                            
                            '.$rAdvTxt.$rDueTxt.' 
                            
                            <tr>
                                <td colspan="3" style="font-size:14px;padding-left: 5px;">Total Amount in word</td>
                                <td colspan="4" style="font-size:14px;padding-left: 5px;"> Taka -' . $formater->convert_number_to_words($tAmount) . ' Only</td>
                            </tr>
                        </tbody>
                        <!-- END ITEMS HERE -->
                    </table>
                </div>
      
               <div width="100%" class="footer text-center">
                <div style="margin-left:-40px;">'.$username.'</div>
                    <div width="100%" class="totalSection">
                        <div width="30%" style="float:left;">&nbsp;<span class="border-top">Received By</span></div>
                        <div width="35%" style="float:left;"><span class="border-top">Prepared By</span></div>
                        <div width="35%" style="float:left;"><span class="border-top">Authorized By</span></div>
                    </div>
                    <div width="100%" class="totalSection">
                        <!--<img  src="./img/footer.jpg" />-->
                    </div>
                    
                    <hr>
                    <p class="text-muted text-bold" style="font-size: 7pt;">Powered By Bangladesh Software Development (BSD)</p>
                </div>
                ';



define('_MPDF_PATH', './');
include("./mpdf.php");

$mpdf = new mPDF('', 'A4', '', 'nikosh', 0, 0, 0, 0, 0, 0, 0);
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle($type . " | Developed By BSD");
$mpdf->SetAuthor("BSD");
$mpdf->SetDisplayMode('fullpage');

$xhtml = '<html>
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

//echo $xhtml;
//
$mpdf->WriteHTML($xhtml);
$mpdf->Output();


exit;
?>
