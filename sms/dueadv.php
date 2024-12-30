<?php
session_start();

$userid = $user_id = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;

if (!empty($_SESSION['UserId'])) {

//========================================
    include '../model/Controller.php';
    include '../model/FormateHelper.php';

    $formater = new FormateHelper();
    $obj = new Controller();
//========================================

    date_default_timezone_set('Asia/Dhaka');
    $date = date('Y-m-d');
    $itemId = $_GET['cusid'];
    

$grand_advance = 0;
  

    foreach ($customerPersonalData as $customer) {
        
        $customerId   = $customer['cus_id'];

        $supplierOrCustomerTransaction = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$customerId'");

        $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved", "supplier_customer='$customerId'");

        $givePaymentToCustomer = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 12 AND cus_or_sup_id='$customerId'");
        
        $labortransporcostForCustomer = $obj->get_sum_data('tbl_account', 'acc_amount', " cus_or_sup_id='$customerId' AND (acc_type = 201 OR acc_type = 202)");

        $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$customerId'");
        $sales_return = $obj->get_sum_data('tbl_account','acc_amount', "acc_type =28 AND cus_or_sup_id='$customerId'");

        $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$customerId'");

        $discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$customerId'");

        $discount = isset($discountData)? $discountData['amount'] :  0;

        $openingDueBalance =  isset($supplierOrCustomerOpeningDue)?  $supplierOrCustomerOpeningDue['opening_due'] :  0;

        $openingAdvance = isset($supplierOrCustomerOpeningAdvance)? $supplierOrCustomerOpeningAdvance['opening_due'] :  0;
         
        $sales_return = ($sales_return > 0)? $sales_return :  0;

        $total_due = ($supplierOrCustomerTransaction['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance)+ $givePaymentToCustomer+(($labortransporcostForCustomer > 0)?$labortransporcostForCustomer:0)-$sales_return;
        

        if($total_due < 0){
            $text = "Total Advance ".abs($total_due);
        
        }elseif($total_due > 0){  
              $text = "Total Due ".abs($total_due);
        }else{
             $text = "";
        }

    }

    echo json_encode([$text] );

}