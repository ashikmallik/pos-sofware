<?php
session_start();
if (!isset($_GET['billId']) || empty($_GET['billId'])) {
    echo '<h4>Sorry ! Wrong Url .</h4>';
    die();
}

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
$billId = isset($_GET['billId']) ? $_GET['billId'] : null;
$billData = $obj->details_by_cond("vw_purchase", "bill_id='$billId'");
$billDataPurchase = $obj->details_by_cond("tbl_purchase", "bill_id='$billId'");

$i = 1;
$sum = 0;
$mainbody = "";
$total_due = 0;
$total_taka = 0;
$total_taka_before_commission = 0;
$total_amount = 0;
$payment = 0;
$discount = 0;
$dueText = '';
$sabek = 0;
$sorboMot = 0;
$totalQty = 0;
$totalDiscount = 0;

    $supplierId = $billData['supplier'];

    $billId = $billData['bill_id'];

    $purchaseItemData = $obj->view_all_by_cond('vw_purchase_item', "bill_id = $billId");
    $purchaseData = $obj->details_by_cond('tbl_purchase', "bill_id = $billId");

    $discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$supplierId'");

    isset($discountData) ? $discount = $discountData['amount'] : $discount = 0;

    $supplierData = $obj->details_by_cond("tbl_supplier", "supplier_id='$supplierId'");
    $name = $supplierData['supplier_name'];
    $address = $supplierData['supplier_address'];
    $phone = $supplierData['supplier_mobile_no'];

    $supplierOrCustomerTransection = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer = '$supplierId'");

    $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved", "supplier_customer='$supplierId'");

    $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$supplierId'");

    $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$supplierId'");

    isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0;

    isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0;

    $total_due = ($supplierOrCustomerTransection['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance);

    $payment = $billData['payment_recieved'];

    $due = $billData['due_to_company'];

    $invoiceData = '';
    $i = 1;
    $billRow = '';
    $totalItemPrice = 0;
    $totalUnit_comission = 0;
    foreach ($purchaseItemData as $purchaseItem) {
        $unit = isset($purchaseItem['unit'])? $purchaseItem['unit']: NULL;
        $totalQty += $purchaseItem['qty'];
        $perItemPrice = $purchaseItem['price']*$purchaseItem['qty'];
        $total_taka_before_commission += $perItemPrice;
        $perItemDiscount = ($perItemPrice*($purchaseData['discount']/100));
        $totalDiscount += $perItemDiscount;
        $totalUnit_comission += number_format($purchaseItem['commission_per_unit'],2);
        $perItemTotalPrice = number_format($perItemPrice-$perItemDiscount,2);
        $total_taka += ($purchaseItem['total_amount']-$perItemDiscount);

        $billRow.= '<tr>
                   <td style="font-size:12px; text-align:right">' . $i++ . '</td>
                   <td style="font-size:12px; text-align:right">' . $purchaseItem['product_name'] . '</td>
                   <td style="font-size:12px; text-align:right" >' . $purchaseItem['qty'] .' '.$unit.' </td>
                   <td style="font-size:12px; text-align:right">' . number_format($purchaseItem['price'],2) . '</td>
                   <td style="font-size:12px; text-align:right">' . number_format($purchaseItem['commission_per_unit'],2) . ' tk</td>
                   <td style="font-size:12px; text-align:right">' . number_format($perItemDiscount, 2) . '</td>
                   <td style="font-size:12px; text-align:right">' . number_format($purchaseItem['total_amount'],2) . ' tk</td>
                 </tr>';
    }
    $billRow.= '<tr>
                    <td></td>
                    <td style="font-weight:bold;">Total</td>
                    <td style="font-weight:bold;font-size:12px; text-align:right">'.$totalQty.'</td>
                    <td></td>
                    <td style=" text-align:right">'.number_format($totalUnit_comission,2).' tk</td>
                    <td style="font-weight:bold;font-size:12px; text-align:right">'.number_format($totalDiscount,2).'</td>
                    <td style="font-weight:bold;font-size:12px; text-align:right">'.number_format($total_taka,2).' tk</td>
                </tr>';

    $commissionedTaka = $total_taka_before_commission - $total_taka;
    

    if($billDataPurchase['less_amount'] > 0){


    $total_taka -= $billDataPurchase['less_amount'];
    $totalRowPrint.= 'Less '.number_format($billDataPurchase['less_amount'],2).' Tk ';
    }

    $sabek = $total_due - $due;
    $advance_or_due = ($sabek < 0) ? ' Advance' : ' Due';
    $sorboMot = $sabek + $total_taka;

    $presentDue = $sorboMot - $payment;

    if ($billId < 10) {$STD = "0000";}
    else if ($billId < 100) {$STD = "000";}
    else if ($billId < 1000) {$STD = "00";}
    else if ($billId < 10000) {$STD = "0";}
    else {$STD = "";}

    if($presentDue < 0){
        $dueText = '<div class="total bangla">
                      Total Advance    : ' . number_format(abs($presentDue-$billDataPurchase['less_amount'])) . ' Taka
                    </div>';
    }else{
        $dueText = '<div class="total bangla">
                       Total Due   : ' . number_format($presentDue-$billDataPurchase['less_amount']) . ' Taka
                    </div>';
    }

    $mainbody .= '              
                <div width="100%">
                    <div class="heading text-center">
                        <img class="header" src="./logobig.png" />
                    </div>
                
                    <div width="100%">
                        <div class="text-center">
                            <h3 class="bangla">Purchase Bill </h3>
                        </div>
                    </div>
                
                    <div width="100%" class="numberDate">
                        <table width="100%" class="infoTable table table-bordered">
                            <tr class="bg-info">
                                <td width="25%" class="bangla"> Supplier Id: </td>
                                <td width="42%">' . $supplierId.' </td>
                                <td width="20%" class="bangla">Invoice Date : </td>
                                <td width="30%"> ' . date('d-m-Y', strtotime($billData['entry_date'])) . ' </td>
                            </tr>
                            <tr class="bg-info">
                                <td width="25%" class="bangla"> Ref No : </td>
                                <td width="42%"><strong></strong>
                                <td width="20%" class="bangla"> Invoice No : </td>
                                <td width="30%" style="font-weight:bold"> '.$STD.$billId.'</td>
                            </tr>
                            <tr class="bg-info">
                                <td width="25%" class="bangla"> Delivery Date : </td>
                                <td width="42%"><strong></strong>
                                <td width="20%" class="bangla"> Sales Contact : </td>
                                <td width="30%" style="font-weight:bold">  </td>
                            </tr>
                            <tr class="bg-info">
                                <td width="25%" class="bangla"> Delivery Chalan : </td>
                                <td width="42%"><strong></strong>
                                <td width="20%" class="bangla"> Contact Ref : </td>
                                <td width="30%" style="font-weight:bold">' .$phone. '</td>
                            </tr>
                            <tr class="bg-info">
                                <td width="25%" class="bangla"> Work Order No : </td>
                                <td colspan="3" width="42%"><strong></strong>
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
                                <td colspan="3" width="92%"></td>
                            </tr>
                        </table>
                    </div>
                
                    <div width="100%">
                        
                    </div>
                
                    <table width="100%" class="table-bordered items table" style="margin-top:10px;">
                        <thead>
                            <tr class="bg-grey">
                                <td width="5%"> SL </td>
                                <td width="40%"> Product Description </td>
                                <td width="15%"> Quantity </td>
                                <td width="13%"> Rate </td>
                                <td width="13%">Comission(per unit)</td>
                                <td width="12%"> Discount </td>
                                <td width="15%"> Total </td>
                            </tr>
                        </thead>
                        <tbody id="invoice">
                            ' . $billRow . '
                            <tr>
                            <th colspan="4" style="font-size:12px; padding:5px; text-align:right">'.$totalRowPrint.' Net Payable</th>
                            <th colspan="2" style="font-size:14px; text-align:center;padding:5px;">' . number_format($total_taka, 2) . ' Tk</th>
                               
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center" style="font-size:14px;">Payable Amount in word</td>
                                <td colspan="5" class="text-center" style="font-size:14px;"> Taka -' . $formater->convert_number_to_words($total_taka) . ' Only</td>
                            </tr>
                        </tbody>
                        <!-- END ITEMS HERE -->
                    </table>
                    
                </div>
                
                <!--<div width = "100%"  class="">
                        <div width="50%" class="leftAlign"></div>
                        
                        <div width = "50%" class="rightAlign">
                             <div class="total bangla">
                                Previous '.$advance_or_due.' : ' . number_format(abs($sabek)) . ' Taka
                            </div>
                            <div class="total bangla">
                                 Total  : ' . number_format(abs($sorboMot-$billDataPurchase['less_amount'])) . ' Taka
                            </div>
                            <div class="total bangla">
                                Total Payment : ' . number_format($payment) . ' Taka
                            </div>
                            '.$dueText.'
                           
                        </div>
                </div>-->
                
                <div width="100%" class="footer text-center">
                    <div width="100%" class="totalSection">
                        <div width="30%" style="float:left;"> &nbsp;</div>
                        <div width="35%" style="float:left; font-size:12px;"></div>
                        <div width="35%" style="float:left;"> &nbsp;</div>
                    </div>
                    <div width="100%" class="totalSection">
                        <!--<img src="./img/footer.jpg" />-->
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

$mpdf->WriteHTML($xhtml);
$mpdf->Output();

exit;
?>
