<?php
session_start();
if (!isset($_GET['supplierId']) || empty($_GET['supplierId'])) {
    echo '<h4>Sorry ! Wrong Url .</h4>';
    die();
}

include '../model/Controller.php';
include '../model/FormateHelper.php';

$formater = new FormateHelper();
$obj = new Controller();


$supplierId = (isset($_GET['supplierId'])) ? $_GET['supplierId'] : null;
$accountsId = (isset($_GET['accounts_info'])) ? $_GET['accounts_info'] : null;
$discountId = (isset($_GET['discount_info'])) ? $_GET['discount_info'] : null;


$custom_details = "";
$i = 0;
$date = date('d.m.Y');
$month = date('F');
$prefix = date('Ym');
$html = "";


// ==================== GET Initialize ==================

$i = 1;
$sum = 0;
$mainbody = "";
$total_due = 0;
$total_taka = 0;
$total_amount = 0;
$payment = 0;
$discount = 0;
$sabek = 0;
$sorboMot = 0;
$discountAmount = 0;


$supplierData = $obj->details_by_cond("tbl_supplier", "supplier_id='$supplierId'");

if (isset($discountId) && !empty($discountId)) {
    $discountData = $obj->details_by_cond("discount", "id='$discountId'");
}
if (isset($accountsId) && !empty($accountsId)) {
    $accountsData = $obj->details_by_cond("tbl_account", "acc_id='$accountsId' AND (acc_type ='6' OR  acc_type ='11')  ");
}

$name = $supplierData['supplier_name'];
$address = $supplierData['supplier_address'];
$phone = $supplierData['supplier_mobile_no'];
$supplierOrCustomerTransection = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer = '$supplierId'");

$supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved", "supplier_customer='$supplierId'");

$supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$supplierId'");

$supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$supplierId'");

isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0;

isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0;

$total_transaction = $supplierOrCustomerTransection['total_price'];

// $total_due = ($supplierOrCustomerTransection['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance);


//supplierduecalfunction
    function suplierdue($obj,$supplierId){
            $receiveCashFromSupplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 11 AND cus_or_sup_id='$supplierId'");

            $purchse_return = $obj->get_sum_data('tbl_return','total_return_price',"type=1 AND cus_or_sup_id='$supplierId'");
                
            $discountData = $obj->details_selected_field_by_cond("discount","sum(`amount`) as amount", "cus_or_sup_id='$supplierId'");
            isset($discountData) ? $discountt = $discountData['amount'] : $discountt = 0 ;
            
            //add
            $mpurchase_payment = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 2 AND cus_or_sup_id='$supplierId'");
            $msupplier_individual_payment = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 6 AND cus_or_sup_id='$supplierId'");
            $msupplier_advance = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 9 AND cus_or_sup_id='$supplierId'");
            $mcompany_provide_s_money_to_supplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 19 AND cus_or_sup_id='$supplierId'");

            //minuse
            $msupplier_due = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 10 AND cus_or_sup_id='$supplierId'");
            $msupplier_back_s_money_to_company = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 20 AND cus_or_sup_id='$supplierId'");
            $mpurchase_product_from_supplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 22 AND cus_or_sup_id='$supplierId'");
            
            
                $total_due =( $msupplier_due + $msupplier_back_s_money_to_company + $mpurchase_product_from_supplier +$receiveCashFromSupplier) - ($mpurchase_payment+$msupplier_individual_payment +$msupplier_advance +$mcompany_provide_s_money_to_supplier+$purchse_return+$discountt);
                return $total_due ;
    }
    $total_due =suplierdue($obj,$supplierId);


    

if($accountsData['acc_type'] == 6){

    $paymentCause = 'Give Payment To Supplier';
}else if($accountsData['acc_type'] == 11){

    $paymentCause = 'Receive Payment From Supplier';
}else if($accountsData['acc_type'] == 5){

    $paymentCause = 'receive Payment From Customer';
}else if($accountsData['acc_type'] == 12){

    $paymentCause = 'Give Payment To Customer';
}


if (isset($accountsData) && !empty($accountsData)) {
    $accountsRow = '<tr>
                        <td style="font-size:14px; text-align:center">1</td>
                        <td style="font-size:12px; text-align:center">'.ucwords($accountsData['acc_description']) . '</td>
                        <td style="font-size:14px; text-align:center" >' . number_format($accountsData['acc_amount']) . ' </td>
                    </tr>';
    $accountsInWord = '<h5 class="text-center" style="font-size:12px;font-weight: bold">' . $paymentCause . ' in Word : ' . $formater->convert_number_to_words($accountsData['acc_amount']) . ' Only</h5>';
}else{
    $accountsRow = '';
    $accountsInWord = '';
}



if (isset($discountData) && !empty($discountData)) {
    $discountRow = '<tr>
                        <td style="font-size:14px; text-align:center">2</td>
                        <td style="font-size:12px; text-align:center"> Give Discount to Supplier</td>
                        <td style="font-size:14px; text-align:center" >' . number_format($discountData['amount']) . ' </td>
                    </tr>';
    $discountInWord = '<h5 class="text-center" style="font-size:12px;font-weight: bold">Discount Amount in Word : ' . $formater->convert_number_to_words($discountData['amount']) . ' Only</h5>';
}else{
    $discountRow = '';
    $discountInWord = '';
}


if ($total_due < 0) {
    $dueText = '<div class="total bangla">
                      Total Advance  : ' . number_format(abs($total_due)) . ' Taka
                    </div>';
} else {
    $dueText = '<div class="total bangla">
                       Total Due   : ' . number_format($total_due) . ' Taka
                    </div>';
}


$mainbody .= '              
                <div width="100%">
                    <div class="heading text-center">
                        <img class="header" src="./img/header.png" />
                    </div>
                
                    <div width="100%">
                        <div class="text-center bg-success">
                            <h3 style="font-size:14px; font-weight: bold ">Supplier Payment Info Receipt </h3>
                        </div>
                    </div>
                
                    <div width="100%" class="numberDate">
                        <table width="100%" class="infoTable table table-bordered">
                            <tr class="bg-info">
                                <td width="25%"> Name: </td>
                                <td colspan="3" width="92%">' . $name . '</td>
                            </tr>
                            <tr class="bg-info">
                                <td width="20%"> Phone : </td>
                                <td width="30%" style="font-weight:bold"> ' . $phone . ' </td>
                                <td width="18%"> Date : </td>
                                <td width="30%"> ' . date('d-m-Y') . ' </td>
                            </tr>
                            <tr class="bg-warning">
                                <td width="25%"> Address :</td>
                                <td colspan="3" width="92%">' . $address . '</td>
                            </tr>
                
                        </table>
                    </div>
                
                    <div width="100%">
                        <h5>Attention: </h5>
                    </div>
                
                    <table width="100%" class="table-bordered items table" style="margin-top:10px;">
                        <thead>
                            <tr class="bg-grey">
                                <td width="10%"> SL </td>
                                <td width="65%"> Payment Description </td>
                                <td width="20%"> Taka </td>
                            </tr>
                        </thead>
                        <tbody id="invoice">
                             '.$accountsRow.'
                             '.$discountRow.'
                            
                        </tbody>
                        
                        <!-- END ITEMS HERE -->
                    </table>
                                        
                </div>
                 <div width = "100%"  class="border-grey-800">
                        '.$accountsInWord.'
                        '.$discountInWord.'
                </div>
                <hr>
                
                <div width = "100%"  class="">
                        <div width="50%" class="leftAlign"></div>
                        
                        <div width = "50%" class="rightAlign">
                             
                            <div class="total bangla">
                                 Total Transaction : ' . number_format($total_transaction) . ' Taka
                            </div>
                            ' . $dueText . '
                           
                        </div>
                </div>
                
                <div width="100%" class="footer text-center">
                    <div width="100%" class="totalSection">
                        <div width="30%" style="float:left;"> &nbsp;</div>
                        <div width="35%" style="float:left; font-size:12px;">' . $_SESSION['FullName'] . '</div>
                        <div width="35%" style="float:left;"> &nbsp;</div>
                    </div>
                    <div width="100%" class="totalSection">
                        <div width="30%" style="float:left;">&nbsp;<span class="border-top">Received By</span></div>
                        <div width="35%" style="float:left;"><span class="border-top">Prepared By</span></div>
                        <div width="35%" style="float:left;"><span class="border-top">Authorized By</span></div>
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
