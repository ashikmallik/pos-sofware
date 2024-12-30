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
$totalDiscountRate = 0; // For new Discount Rate column

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
foreach ($purchaseItemData as $purchaseItem) {
    $unit = isset($purchaseItem['unit']) ? $purchaseItem['unit'] : NULL;
    $totalQty += $purchaseItem['qty'];
    $perItemPrice = $purchaseItem['price'] * $purchaseItem['qty'];
    $total_taka_before_commission += $perItemPrice;
    $perItemDiscount = $purchaseData['discount']; // Use the discount rate as a percentage
    $totalDiscount += $perItemDiscount;

    $discountRate = $purchaseItem['price'] - ($purchaseItem['price'] * ($purchaseData['discount'] / 100)); // Calculate Discount Rate
    $totalDiscountRate += $discountRate * $purchaseItem['qty']; // Add Discount Rate column total

    $perItemTotalPrice = !empty($discountRate) 
        ? number_format($discountRate * $purchaseItem['qty'], 2) 
        : number_format($perItemPrice, 2);
    $total_taka += $discountRate * $purchaseItem['qty'];

    $billRow .= '<tr>
                   <td style="font-size:12px; text-align:right">' . $i++ . '</td>
                   <td style="font-size:12px; text-align:right">' . $purchaseItem['product_name'] . '</td>
                   <td style="font-size:12px; text-align:right">' . $purchaseItem['qty'] . ' ' . $unit . ' </td>
                   <td style="font-size:12px; text-align:right">' . number_format($purchaseItem['price'], 2) . '</td>
                   <td style="font-size:12px; text-align:right">' . number_format($perItemDiscount, 2) . '%</td>
                   <td style="font-size:12px; text-align:right">' . number_format($discountRate, 2) . '</td>
                   <td style="font-size:12px; text-align:right">' . $perItemTotalPrice . ' tk</td>
                 </tr>';
}
$billRow .= '<tr>
                    <td></td>
                    <td style="font-weight:bold;">Total</td>
                    <td style="font-weight:bold;font-size:12px; text-align:right">' . $totalQty . '</td>
                    <td></td>
                    <td style="font-weight:bold;font-size:12px; text-align:right">' . number_format($totalDiscount, 2) . '</td>
                    <td style="font-weight:bold;font-size:12px; text-align:right">' . number_format($totalDiscountRate, 2) . '</td>
                    <td style="font-weight:bold;font-size:12px; text-align:right">' . number_format($total_taka, 2) . ' tk</td>
                </tr>';

$commissionedTaka = $total_taka_before_commission - $total_taka;

if ($billDataPurchase['less_amount'] > 0) {
    $total_taka -= $billDataPurchase['less_amount'];
    $totalRowPrint .= 'Less ' . number_format($billDataPurchase['less_amount'], 2) . ' Tk ';
}

$sabek = $total_due - $due;
$advance_or_due = ($sabek < 0) ? ' Advance' : ' Due';
$sorboMot = $sabek + $total_taka;

$presentDue = $sorboMot - $payment;

if ($billId < 10) {
    $STD = "0000";
} else if ($billId < 100) {
    $STD = "000";
} else if ($billId < 1000) {
    $STD = "00";
} else if ($billId < 10000) {
    $STD = "0";
} else {
    $STD = "";
}

if ($presentDue < 0) {
    $dueText = '<div class="total bangla">
                      Total Advance    : ' . number_format(abs($presentDue - $billDataPurchase['less_amount'])) . ' Taka
                    </div>';
} else {
    $dueText = '<div class="total bangla">
                       Total Due   : ' . number_format($presentDue - $billDataPurchase['less_amount']) . ' Taka
                    </div>';
}

$mainbody .= '
    <div width="100%">
        <div class="heading text-center">
            <img class="header" src="./img/header.jpg" />
        </div>
    
        <div width="100%">
            <div class="text-center">
                <h3 class="bangla" style="font-size: 18px; font-weight: bold;">Purchase Bill</h3>
            </div>
        </div>
 
        <div width="100%" class="numberDate">
            <table width="100%" class="infoTable table table-bordered" style="font-size: 18px; border: 3px solid black; 
                  border-collapse: collapse;">
                <tr class="bg-info">
                    <td width="25%" class="bangla" style="padding: 8px; text-align: left;"> Supplier Id: </td>
                    <td width="42%" style="padding: 8px;">' . $supplierId . ' </td>
                    <td width="20%" class="bangla" style="padding: 8px; text-align: left;">Invoice Date : </td>
                    <td width="30%" style="padding: 8px;"> ' . date('d-m-Y', strtotime($billData['entry_date'])) . ' </td>
                </tr>
                <tr class="bg-info">
                    <td width="25%" class="bangla" style="padding: 8px; text-align: left;"> Ref No : </td>
                    <td width="42%" style="padding: 8px;"></td>
                    <td width="20%" class="bangla" style="padding: 8px; text-align: left;"> Invoice No : </td>
                    <td width="30%" style="padding: 8px; font-weight: bold;"> ' . $STD . $billId . '</td>
                </tr>
                <tr class="bg-info">
                    <td width="25%" class="bangla" style="padding: 8px; text-align: left;"> Delivery Date : </td>
                    <td width="42%" style="padding: 8px;"></td>
                    <td width="20%" class="bangla" style="padding: 8px; text-align: left;"> Sales Contact : </td>
                    <td width="30%" style="padding: 8px; font-weight: bold;">  </td>
                </tr>
                <tr class="bg-info">
                    <td width="25%" class="bangla" style="padding: 8px; text-align: left;"> Delivery Chalan : </td>
                    <td width="42%" style="padding: 8px;"></td>
                    <td width="20%" class="bangla" style="padding: 8px; text-align: left;"> Contact Ref : </td>
                    <td width="30%" style="padding: 8px; font-weight: bold;">' . $phone . '</td>
                </tr>
                <tr class="bg-info">
                    <td width="25%" class="bangla" style="padding: 8px; text-align: left;"> Work Order No : </td>
                    <td colspan="3" width="42%" style="padding: 8px;"></td>
                </tr>
                <tr class="bg-warning">
                    <td width="25%" class="bangla" style="padding: 8px; text-align: left;"> Customer Name & Address :</td>
                    <td colspan="3" width="92%" style="padding: 8px;">' . $name . '<br>' . $address . '</td>
                </tr>
                <tr class="bg-warning">
                    <td width="25%" class="bangla" style="padding: 8px; text-align: left;"> Contact No :</td>
                    <td colspan="3" width="92%" style="padding: 8px;">' . $phone . '</td>
                </tr>
                <tr class="bg-warning">
                    <td width="25%" class="bangla" style="padding: 8px; text-align: left;"> Delivery Address :</td>
                    <td colspan="3" width="92%" style="padding: 8px;"></td>
                </tr>
            </table>
        </div>
    
        <table width="100%" class="table-bordered items table" style="margin-top:10px;">
            <thead>
                <tr class="bg-grey" style="background-color: #f2f2f2;">
                    <td width="5%" style="padding: 8px; text-align: center;"> SL </td>
                    <td width="40%" style="padding: 8px; text-align: left;"> Product Description </td>
                    <td width="15%" style="padding: 8px; text-align: right;"> Quantity </td>
                    <td width="13%" style="padding: 8px; text-align: right;"> Rate </td>
                    <td width="12%" style="padding: 8px; text-align: right;"> Discount </td>
                    <td width="10%" style="padding: 8px; text-align: right;"> Discount Rate </td>
                    <td width="15%" style="padding: 8px; text-align: right;"> Total </td>
                </tr>
            </thead>
            <tbody id="invoice">
                ' . $billRow . '
                <tr>
                    <th colspan="4" style="font-size: 12px; padding: 8px; text-align: right; background-color: #f9f9f9; border-top: 2px solid #000;">' . $totalRowPrint . ' Net Payable</th>
                    <th colspan="3" style="font-size: 14px; padding: 8px; text-align: center; background-color: #f9f9f9; border-top: 2px solid #000;">' . number_format($total_taka, 2) . ' Tk</th>
                </tr>
            </tbody>
        </table>
    
        <div class="total bangla" style="margin-top: 10px; font-size: 12px;">
            <p>
                Sorbo Mot (Total): <strong>' . number_format($sorboMot, 2) . ' Taka</strong><br>
                Paid: <strong>' . number_format($payment, 2) . ' Taka</strong><br>
                Remaining' . $advance_or_due . ': <strong>' . number_format(abs($presentDue), 2) . ' Taka</strong><br>
            </p>
            ' . $dueText . '
        </div>
    
        <div style="margin-top: 20px; text-align: center;">
            <p style="font-size: 14px;">Thank you for your business!</p>
            <br><br>
            <p style="font-size: 14px;">Signature</p>
        </div>
    </div>

    <div class="footer text-center" style="font-size: 10px; margin-top: 20px;">
        <p style="font-size: 12px;">Software Developed by <strong>Bangladesh Software Development (BSD)</strong> | Contact: 01759080279</p>
    </div>
    
    <div width="50%" style="display: block; margin-left: auto; margin-right: auto;">
        <div class="heading text-center">
            <img class="header" src="./img/bn.png" />
        </div>
    </div>
';


       define('_MPDF_PATH', './');
include("./mpdf.php");

$mpdf = new mPDF('', 'A4', '', 'nikosh', 0, 0, 0, 0, 0, 0, 0);
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle($type . " | Developed By BSD");
$mpdf->SetAuthor("BSD");
$mpdf->SetDisplayMode('fullpage');

$xhtml = '<html lang="bn">
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