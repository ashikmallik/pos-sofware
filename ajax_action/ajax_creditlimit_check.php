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

    $cusId = $_GET['cusId'];

    $expenseType = 1;
    $purchasePaymentType = 2;
    $supplierPurchaseIndividualPaymentType = 6;
    $giveCashToCustomer = 12;
    $loanGiveToPersonType = 13;
    $CompanyRepayHisLoanType = 16;
    $CompanyBackSecurityMoneyToCustomerType = 18;
    $CompanyGiveSecurityMoneyToSupplierType = 19;
    $CompanyGivePaymentEmployeeType = 21;
    $CustomerDue = 8;
    $SupplierAdvance = 9;
    $SellProductBill = 23;
    $grandtotoaldue = 0;
    $sl = 0;
    $balance = 0;
    $total_due = 0;
    $customerId =null;
    $total_credit = 0;
    $total_debit = 0;
    $totalbalance = 0;

    // echo $cusId.'__';
    //  $cusId = '"+ cuid +" ';
    //  echo  $cusId ;
    //  if(isset($cusId)){
//      echo $cusId;
    //  }

    //  var_dump($cusId);

    if ($ty =="SA") {
        $customerPaymentData = $obj->view_all_by_cond("tbl_account", "`cus_or_sup_id` = '$cusId' order by entry_date");
        $customerdata = $obj->get_data('*', 'tbl_customer', "`cus_id`='$cusId' ");
    } else {
        // $customerPaymentData = $obj->view_all_by_cond("tbl_account", "`cus_or_sup_id` = '$cusId' AND branch_id='$user_branch_id'");
        $customerPaymentData = $obj->view_all_by_cond("tbl_account", "`cus_or_sup_id` = '$cusId' order by entry_date");
        $customerdata = $obj->get_data('*', 'tbl_customer', "`cus_id`='$cusId' ");
    }


    // var_dump($customerPaymentData);


    foreach ($customerPaymentData as $cusPay) {
        $customerId   = $cusPay['cus_or_sup_id'];
        // $customercredit = $customerdata['cus_credit'];
        
       

        $entry_person=$cusPay['entry_by'];
        $data = $obj->details_by_cond("vw_user_info", "UserId='$entry_person'");
        $name=$data['FullName'];
        $sl++;
        $debit = 0;
        $credit = 0;

        // echo $cusPay['acc_amount'];

    
        if ($cusPay['cus_or_sup_id'] == $cusId) {
        }
        if ($cusPay['acc_type'] == $expenseType || $cusPay['acc_type'] == $purchasePaymentType
                || $cusPay['acc_type'] == $supplierPurchaseIndividualPaymentType
                || $cusPay['acc_type'] == $giveCashToCustomer
                || $cusPay['acc_type'] == $loanGiveToPersonType
                || $cusPay['acc_type'] == $CompanyRepayHisLoanType
                || $cusPay['acc_type'] == $CompanyBackSecurityMoneyToCustomerType
                || $cusPay['acc_type'] == $CompanyGiveSecurityMoneyToSupplierType
                || $cusPay['acc_type'] == $CompanyGivePaymentEmployeeType
                || $cusPay['acc_type'] == $supplierPurchaseIndividualPaymentType
                || $cusPay['acc_type'] == $CustomerDue
                || $cusPay['acc_type'] == $SupplierAdvance
                || $cusPay['acc_type'] == $SellProductBill
                || $cusPay['acc_type'] == 201
                || $cusPay['acc_type'] == 202
            ) {
            $debit = $cusPay['acc_amount'];
            // echo $debit;
            // $balance += $debit;
            $total_debit += $debit;
        } else {
            $credit = $cusPay['acc_amount'];
            // $balance -= $credit;
            $total_credit += $credit;
        }
    }




    $total_due = $total_debit - $total_credit;
    //  echo $total_due;
    //  echo $total_due;

    $dealar = explode("-", $customerId);
    if ($dealar[0] == 'D') {

        // echo $customercredit.' ' ;
        // echo $total_due.' ' ;
        $totalbalance =  $customercredit - $total_due;
    }

    // echo json_encode(['sadasd']);
    // echo $totalbalance ;
    echo json_encode(['totalbalance' => $totalbalance,'customerdetails' => $customerdata,'total_due' => $total_due,]);

}

?>