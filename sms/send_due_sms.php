<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$notification = "";
$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;
//taking month and years
$day = date('M-Y');

if( isset($_GET['cid']) && !empty( $_GET['cid']) ){
    $cid = $_GET['cid'];

    
    if($cid == 4){
    
        if($ty =="SA"){
            $customerPersonalData =$obj->view_all("tbl_customer");
        }else{
            $customerPersonalData = $obj->view_all_by_cond("tbl_customer", "cus_branch_id='$user_branch_id'");
        }
    }else{

        if($ty =="SA"){
            $customerPersonalData =$obj->view_all_by_cond("tbl_customer","customer_category='$cid' ");
        }else{
            $customerPersonalData = $obj->view_all_by_cond("tbl_customer", "cus_branch_id='$user_branch_id' AND customer_category='$cid' ");
        }

    }
    
    $smsb = $obj->details_by_cond("sms", "status='1'");


}else{


	die();
}




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


                    $sl = 0;
                    $balance = 0;
                    // $total_credit = 0;
                    // $total_debit = 0;
                    $total_due = 0;
                    global $notification;
                    $smsArray =[];
                    $smsI=0;

                    foreach ($customerPersonalData as $customer) {
                        $cusId =  $customer['cus_id'];

                        $customerPaymentData = $obj->view_all_by_cond("tbl_account", "`cus_or_sup_id` = '$cusId' order by entry_date");


                        $total_credit = 0;
                        $total_debit = 0;

                        foreach ($customerPaymentData as $cusPay) {

                            $customerName = $customer['cus_name'];
                            $mobile = $customer['cus_mobile_no'];
                            $customercredit = $customer['cus_credit'];
                            $customerId   = $customer['cus_id'];

                            $entry_person=$cusPay['entry_by'];
                            $data = $obj->details_by_cond("vw_user_info", "UserId='$entry_person'");
                            $name=$data['FullName'];
                            $sl++;
                            $debit = 0;
                            $credit = 0;

                        
                            if ($cusPay['cus_or_sup_id'] == $cusId) {
                                if ($cusPay['acc_type'] == $expenseType || $cusPay['acc_type'] == $purchasePaymentType
                                    || $cusPay['acc_type'] == $supplierPurchaseIndividualPaymentType
                                    || $cusPay['acc_type'] == $giveCashToCustomer
                                    || $cusPay['acc_type'] == $loanGiveToPersonType
                                    || $cusPay['acc_type'] == $CompanyRepayHisLoanType
                                    || $cusPay['acc_type'] == $CompanyBackSecurityMoneyToCustomerType
                                    || $cusPay['acc_type'] == $CompanyGiveSecurityMoneyToSupplierType
                                    || $cusPay['acc_type'] == $CompanyGivePaymentEmployeeType
                                    || $cusPay['acc_type'] == $supplierPurchaseIndividualPaymentType
                                   // || $cusPay['acc_type'] == $CustomerDue
                                    || $cusPay['acc_type'] == $SupplierAdvance
                                    || $cusPay['acc_type'] == $SellProductBill
                                ) {
                                    $debit = $cusPay['acc_amount'];
                                    $balance += $debit;
                                    $total_debit += $debit;
                                } else {
                                    $credit = $cusPay['acc_amount'];
                                    $balance -= $credit;
                                    $total_credit += $credit;
                                }
                            }
                        }


                        $total_due = $total_debit - $total_credit;

                        if($total_due >0){                         
                            $body = $smsb['smshead'].$total_due .$smsb['smsbody'];
                            if (!empty($mobile)) {
                                //  $notification .=  $obj->smsSend($mobile,$body);
                                 $smsArray[$smsI++] = ["to" => $mobile, "message" => $body];
                             }       
                            
                        }
                         
                    }
                    $obj->sms_send($smsArray);

?>

<script>
    window.location="?q=due_sms";
</script>
