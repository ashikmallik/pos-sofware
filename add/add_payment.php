<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

//===================Add Function===================
$action = (isset($_GET['action'])) ? $_GET['action'] : null;

$paymentAction = (isset($_GET['payment_action'])) ? $_GET['payment_action'] : null;
$preDesc = "";

if ($action == 'cAd') {
    $person = (isset($_GET['customer_id'])) ? $_GET['customer_id'] : null;
    $personData = $obj->details_by_cond('tbl_customer', "cus_id = '$person'");
    $personName = $personData['cus_company'];
    $email = $personData['cus_email'];
    $mobile = $personData['cus_mobile_no'];
    $redirect = "customer_ledger&customerId=$person";
    $redirectMemoPrint = "customer_memo.php?customerId=$person";
    $total_due_sell = $obj->get_sum_data('tbl_sell', 'due_to_company', "customer = '$person'");
    $all_sells = $obj->view_all_by_cond('tbl_sell', "customer = '$person'");

    if ($paymentAction == 'receive_payment_from_customer') {
        $accType = 5;
        $preDesc = "Company Receipt Amount from Customer";
    } else if ($paymentAction == 'give_payment_to_customer') {
        $accType = 12;
        $preDesc = "Give Payment to Customer";
    } else {
        $accType = 5;
    }
} else if ($action == 'sAd') {
    $person = (isset($_GET['sup_id'])) ? $_GET['sup_id'] : null;
    $personData = $obj->details_by_cond('tbl_supplier', "supplier_id = '$person'");
    $personName = $personData['supplier_company'];
    $email = $personData['supplier_email'];
    $mobile = $personData['supplier_mobile_no'];
    $redirect = "supplier_ledger&supplierId=$person";
    $redirectMemoPrint = "supplier_memo.php?supplierId=$person";

    if ($paymentAction == 'give_payment_to_supplier') {
        $accType = 6;
        $preDesc = "Give Payment to Supplier";
    } else if ($paymentAction == 'receive_payment_from_supplier') {
        $accType = 11;
        $preDesc = "Receipt Amount from Supplier";
    } else {
        $accType = 6;
    }
} else {
    $person = null;
}


//Previous due calcualtion
$recievedTaka = $obj->details_by_cond('vw_supplier_customer_total_recieved', "supplier_customer = '$person'");
$totalTransactionTaka = $obj->details_by_cond('vw_supplier_customer_total_transection', "supplier_customer = 
'$person'");

$supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$person'");

$supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$person'");

$discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$person'");
isset($discountData) ? $discountmd = $discountData['amount'] : $discountmd = 0;
isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0;

isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0;

$givePaymentToCustomer = $obj->get_sum_data('tbl_account','acc_amount', "(acc_type = 12 OR acc_type = 11 ) AND cus_or_sup_id='$person'");

// $total_due = ($totalTransactionTaka['total_price'] - $recievedTaka['total_recieved'] - $discountmd + $openingDueBalance - $openingAdvance)+$givePaymentToCustomer;

//End


    //Customer Due Advance ..
    function customerdueadvance($obj,$customerId){
         $supplierOrCustomerTransaction = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$customerId'");
                            
        $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved", "supplier_customer='$customerId'");
    
        $givePaymentToCustomer = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 12 AND cus_or_sup_id='$customerId'");
        
        $labortransporcostForCustomer = $obj->get_sum_data('tbl_account', 'acc_amount', " cus_or_sup_id='$customerId' AND (acc_type = 201 OR acc_type = 202)");
    
        $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$customerId'");
        
        $sales_return = $obj->get_sum_data('tbl_account','acc_amount', "acc_type =28 AND cus_or_sup_id='$customerId'");
        $sales_return = $sales_return?$sales_return:0;
         
        $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$customerId'");
    
        $discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$customerId'");
    
        isset($discountData) ? $discount = $discountData['amount'] : $discount = 0;
    
        isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0;
    
        isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0;
       
    
        $total_due = ($supplierOrCustomerTransaction['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance)+ $givePaymentToCustomer-$sales_return+(($labortransporcostForCustomer > 0)?$labortransporcostForCustomer:0);
    
        return $total_due;
    }
    
    
    if ($action == 'cAd') {
    $total_due =customerdueadvance($obj,$person);
    }
    
    
    
    //Only supplier uecal function  
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
    if ($action == 'sAd') {
    $total_due =suplierdue($obj,$person);
    }
    
    
if (isset($_POST['addPayment'])) {
    extract($_POST);
    if($payment_method == 0){
        $mobile_banking_name = 'Bank';
    }elseif($payment_method == 3){
        $mobile_banking_name = 'Bkash';
    }
    elseif($payment_method == 4){
        $mobile_banking_name = 'Nagad';
    }
    else{
        $mobile_banking_name = 'Rocket';
    }
    /*echo "<pre>";
    print_r($_POST);        
    echo "</pre>";*/
    $payment_method_type = 1;

    // if ($payment_method == 'bank') {
        $payment_method_type = 0;
        $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
        $total_balance = $total_balance_Data['balance'];

        if ($action == 'sAd') {
            $balance_calculate = ($total_balance - $payment);
        } else {
            $balance_calculate = ($total_balance + $payment);
        }

        $form_data_for_bank = array(
            'mobile_banking_name' => $mobile_banking_name,
            'type' => $payment_method,
            'account_no' => $account_no,
            'description' =>  $preDesc . ' (' . $personName . ') ' . $description,
            // 'credit' => ($action == 'sAd') ? $payment : 0,
            // 'debit' => ($action == 'cAd') ? $payment : 0,
            'balance' => $balance_calculate,
            'withdraw_by' => (isset($withdraw_by) ? (empty($withdraw_by) ? $personName : $withdraw_by) : null),
            'diposited_by' => (isset($diposited_by) ? (empty($diposited_by) ? $personName : $diposited_by) : null),
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        
        
        //new added for debit credit
         if ($action == 'sAd') {
            if ($paymentAction == 'receive_payment_from_supplier') {
                   $form_data_for_bank['debit'] = $payment;
            } else if ($paymentAction == 'give_payment_to_supplier') {
                   $form_data_for_bank['credit'] = $payment;
            }
        } else {
            if ($paymentAction == 'receive_payment_from_customer') {
                  $form_data_for_bank['debit'] = $payment;
            } else if ($paymentAction == 'give_payment_to_customer') {
                   $form_data_for_bank['credit'] = $payment;
            }
        }
        

        $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
    // }
    if ($payment_method == 'commission') {
        $payment_method_type = 2;
    }

    if ($action == 'sAd') {
        $all_purchases = $obj->view_all_by_cond('tbl_purchase', "supplier = '$person'");
        $total_due_purchases = $obj->get_sum_data('tbl_purchase', 'due_to_company', "supplier = '$person'");
        $payment_adjust_amount = $payment;
        foreach ($all_purchases as $all_purchase) {
            if ($total_due_purchases > 0 && $all_purchase['due_to_company'] > 0) {
                if ($all_purchase['due_to_company'] < $payment_adjust_amount) {
                    $sell_due_to_company = $all_purchase['bill_id'];
                    $payment_adjust_amount = $payment_adjust_amount - $all_purchase['due_to_company'];
                    $form_update = array(
                        'due_to_company' => 0,
                        'payment_recieved' => $all_purchase['total_price']
                    );

                    $obj->Update_data('tbl_purchase', $form_update, "bill_id = '$sell_due_to_company' ");
                } elseif ($all_purchase['due_to_company'] >= $payment_adjust_amount) {
                    $sell_due_to_company = $all_purchase['bill_id'];
                    echo $new_payment_adjust_amount = $all_purchase['due_to_company'] - $payment_adjust_amount;
                    $form_update = array(
                        'due_to_company' => $new_payment_adjust_amount,
                        'payment_recieved' => $all_purchase['total_price'] - $new_payment_adjust_amount
                    );

                    $obj->Update_data('tbl_purchase', $form_update, "bill_id = '$sell_due_to_company' ");
                    $payment_adjust_amount = 0;
                }
            }
        }
    } else {
        $payment_adjust_amount = $payment;
        foreach ($all_sells as $all_sell) {

            if ($total_due_sell > 0 && $all_sell['due_to_company'] > 0) {
                if ($all_sell['due_to_company'] < $payment_adjust_amount) {

                    $sell_due_to_company = $all_sell['sell_id'];
                    $payment_adjust_amount = $payment_adjust_amount - $all_sell['due_to_company'];
                    $form_update = array(
                        'due_to_company' => 0,
                        'payment_recieved' => $all_sell['total_price']
                    );

                    $obj->Update_data('tbl_sell', $form_update, "sell_id = '$sell_due_to_company' ");
                } elseif ($all_sell['due_to_company'] >= $payment_adjust_amount) {

                    $all_sell['sell_id'];
                    $sell_due_to_company = $all_sell['sell_id'];
                    $new_payment_adjust_amount = $all_sell['due_to_company'] - $payment_adjust_amount;
                    $form_update = array(
                        'due_to_company' => $new_payment_adjust_amount,
                        'payment_recieved' => $all_sell['total_price'] - $new_payment_adjust_amount
                    );

                    $obj->Update_data('tbl_sell', $form_update, "sell_id = '$sell_due_to_company' ");
                    $payment_adjust_amount = 0;
                }
            }
        }
    }

    $form_tbl_accounts = array(
        'acc_description' => $preDesc . ' (' . $personName . ') ' . $description,
        'acc_amount' => $payment,
        'acc_type' => $accType,
        'cus_or_sup_id' => $person,
        'payment_method' => $payment_method_type,
        'acc_head' => 0,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");

    if ($action == 'sAd') {
        if (!empty($discount) && $discount != 0) {
            $form_tbl_accounts_discount = array(
                'acc_description' => "Discount For Paid Amount. Supplier " . $personName,
                'acc_amount' => $discount,
                'acc_type' => 30,
                'purchase_or_sell_id' => '',
                'cus_or_sup_id' => $person,
                'acc_head' => 0,
                'payment_method' => 1,
                'entry_by' => $userid,
                'entry_date' => date('Y-m-d'),
                'update_by' => $userid
            );
             $obj->insert_by_condition("tbl_account", $form_tbl_accounts_discount, " ");
        }
    } else {
        if (!empty($discount) && $discount != 0) {
            $form_tbl_accounts_discount = array(
                'acc_description' => "Discount For Received Amount. Customer " . $personData['cus_company'],
                'acc_amount' => $discount,
                'acc_type' => 29,
                'purchase_or_sell_id' => '',
                'cus_or_sup_id' => $person,
                'acc_head' => 0,
                'payment_method' => 1,
                'entry_by' => $userid,
                'entry_date' => date('Y-m-d'),
                'update_by' => $userid
            );
            $obj->insert_by_condition("tbl_account", $form_tbl_accounts_discount, " ");
        }
    }

    $tbl_discounts_add = '';
    if (isset($discount) && !empty($discount)) {

        $form_tbl_discounts = array(
            'cus_or_sup_id' => $person,
            'amount' => $discount,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d')
        );
        $tbl_discounts_add = $obj->insert_by_condition("discount", $form_tbl_discounts, " ");
    }



    //SMS

    if ($_GET['payment_action'] == "receive_payment_from_customer") {


        if ($total_due > 0) {
            $remainingDue = "Total Due Balance " . ($total_due - ($payment + $discount)) . " Taka";
        } else {

            $remainingDue = "Total Advance Balance " . number_format(abs($total_due - ($payment + $discount))) . " Taka";
        }

        if (!empty($discount)) {
            $discountd = " and your discount is " . $discount . " Taka";
        } else {
            $discountd = " ";
        }



        // $description ;
        // $payment_method ;
        $body = "You have paid by  $payment_method  $payment taka now  " . $remainingDue . $discountd;
    } elseif ($_GET['payment_action'] == "give_payment_to_customer") {

        $rDue = "Total  Balance is " .  number_format(abs(($total_due + ($payment + $discount)))) . " Taka";


        $body = "Your advance payment has been return by $payment_method $payment Taka .Now  " . $rDue . $discountd;
    } elseif ($_GET['payment_action'] == "give_payment_to_supplier") {


        //    $totalpredue  = abs($due_all)  ;

        if (!empty($discount)) {
            $discountd = " and You have given Discount  " . $discount . " Taka";
        } else {
            $discountd = " ";
        }



        if ($total_due > 0) {

            if (abs($total_due) >=  $payment) {
                $paymentmsg = " and  Remaining Due Amount " . (abs($total_due) - ($payment+$discount));
            } else {
                $paymentmsg = " and Advance Blance " . (($payment+$discount)- abs($total_due));
            }
            $totalpredue = " Due Was " . abs($total_due);


            $body = "Your Due payment has paid.Previous $totalpredue in Paid amount  $payment $discountd  $paymentmsg ";
        } else {
            $paymentmsg = " and Advance Blance " . abs(($payment+$discount) +  $total_due);
            $totalpredue = " Advance Was " . abs($total_due);

            $body = "Your have recived payment.Previous $totalpredue  takal in Paid amount  $payment  $paymentmsg";
        }

    } elseif ($_GET['payment_action'] == "receive_payment_from_supplier") {
        $body = "We recived amount  $payment taka from your.Thanks";

    } else {
        $body = " ";
    }

    global $notification;
    $subject = "Payment Information";


    if (!empty($mobile)) {
        //sms 
        $notification .=  $obj->smsSend($mobile, $body);
    }
    if (!empty($email)) {
        //email
        $notification .= $obj->emailSend($email, $body, $subject);
    }


?>
    <script>
        window.location = "?q=<?php echo $redirect; ?>";
        window.open('pdf/<?php echo $redirectMemoPrint . '&accounts_info=' . $tbl_accounts_add . '&discount_info=' . $tbl_discounts_add; ?>', '_blank');
    </script>
<?php } ?>
<!--===================end Function===================-->

<script>
    function numbersOnly(e) // Numeric Validation
    {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) {
            if ((unicode < 2534 || unicode > 2543) && (unicode < 48 || unicode > 57)) {
                return false;
            }
        }
    }
</script>

<div class="col-md-8 col-md-offset-2">
    <?php if (isset($notification)) {
        echo $notification;
    } ?>
</div>

<span style="color: red; font-size: 20px;text-align:left;"></span>
<div class="row" style="padding:10px; font-size: 12px;">
    <form role="form" enctype="multipart/form-data" method="post">
        <div class="row" style="padding:10px; font-size: 12px;">
            <div class="col-md-6 col-md-offset-3 bg-teal-700 text-center" style="margin-bottom:5px;">
                <h4><?php echo ucwords(str_replace('_', ' ', $paymentAction)); ?> </h4>
            </div>
            <div class="col-md-6 col-md-offset-3 bg-slate-700 text-center" style="margin-bottom:5px;">
                <h5><?php echo ($action == 'cAd') ? 'Customer ' : 'Supplier ';
                    echo $personName; ?></h5>
            </div>
            <div class="col-md-6 col-md-offset-3 bg-grey-700 text-center" style="margin-bottom:5px;">
                <h5>Total <?php echo ($total_due < 0) ? 'Advance ' : 'Due ';
                            echo number_format(abs($total_due)) . ' taka'; ?>
                </h5>
            </div>
            <div class="col-md-6 col-md-offset-3 padding_10_px">
                <div class="form-group">
                    <label>Amount of Taka</label>
                    <div class="input-group">
                        <div class="input-group-btn">
                            <select class="btn btn-default" name="payment_method">
                                <option value="1" selected="selected"> Cash</option>
                                <option value="0"> Bank</option>
                                <option value="3"> Bkash</option>
                                <option value="4"> Nagod</option>
                                <option value="5"> Rocket</option>
                                <?php if ($action == 'sAd' && $paymentAction == 'give_payment_to_supplier') { ?>
                                    <option value="commission"> Commission</option>
                                <?php } ?>
                            </select>
                        </div><!-- btn-group -->
                        <input type="text" style="height:32px" name="payment" class="form-control" onkeypress="return numbersOnly(event)" placeholder="Amount">
                        <span class="input-group-addon">TK</span>
                    </div>
                </div>
                <div id="bank_info" style="display: none;">
                    <div class="form-group">
                        <label for="account_no">Bank Account No</label>
                        <div>
                            <select class="form-control" disabled required="required" name="account_no" id="status">
                                <option></option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all_by_cond("bank_registration","type=2") as $value) {
                                    $i++;  ?><option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>
                                        --<?php echo isset($value['bank_name']) ? $value['bank_name'] : NULL; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <?php if ($action == 'sAd') { ?>
                        <div class="form-group">
                            <label for="withdraw_by">Withdrawal Name</label>
                            <input type="text" disabled name="withdraw_by" placeholder="Default take Supplier name" class="form-control">
                        </div>
                    <?php
                    } else { ?>
                        <div class="form-group">
                            <label>Depositor Name</label>
                            <input type="text" disabled name="diposited_by" placeholder="Default take Customer name" class="form-control">
                        </div>
                    <?php } ?>
                </div>
                   <div id="baksh_info" style="display: none;">
                    <div class="form-group">
                        <label for="account_no">Bkash Account No</label>
                        <div>
                            <select class="form-control" name="account_no" id="status">
                                <option value="">Select Bkash Account</option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all_by_cond("bank_registration","type=3") as $value) {
                                    $i++;  ?>
                                    <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>--Bkash</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Withdraw Name</label>
                        <input type="text" name="withdraw_by" placeholder="Default take Customer name" class="form-control">
                    </div>
                </div>
                
                <div id="nagad_info" style="display: none;">
                    <div class="form-group">
                        <label for="account_no">Nagad Account No</label>
                        <div>
                            <select class="form-control" name="account_no" id="status">
                                <option value="">Select Nagad Account</option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all_by_cond("bank_registration","type=4") as $value) {
                                    $i++;  ?>
                                    <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>--Nagad</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

            
                    <div class="form-group">
                        <label>Withdraw Name</label>
                        <input type="text" name="withdraw_by" placeholder="Default take Customer name" class="form-control">
                    </div>
                </div>
                
                <div id="rocket_info" style="display: none;">
                    <div class="form-group">
                        <label for="account_no">Rocket Account No</label>
                        <div>
                            <select class="form-control" name="account_no" id="status">
                                <option value="">Select Rocket Account</option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all_by_cond("bank_registration","type=5") as $value) {
                                    $i++;  ?>
                                    <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>--Rocket</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label>Withdraw Name</label>
                        <input type="text" name="withdraw_by" placeholder="Default take Customer name" class="form-control">
                    </div>
                </div>

<?php 
 if ($_GET['payment_action'] == "receive_payment_from_customer" || $_GET['payment_action'] == "give_payment_to_supplier") {
     ?>
                <div class="form-group" id="discount">
                    <label>Discount</label>
                    <input type="text" name="discount" class="form-control" onkeypress="return numbersOnly(event)" placeholder="Discount">
                </div>
<?php
 }
  ?>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" placeholder="Description" rows="4"></textarea>
                </div>

                <div class="row text-center">
                    <button type="submit" class="btn btn-success " name="addPayment">Submit</button>
                </div>
            </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // $('select[name="payment_method"').on('change', function() {
        //     if (this.value == 'bank') {
        //         $('#bank_info select[name="account_no"]').removeAttr('disabled');
        //         $('#bank_info input[name="withdraw_by"]').removeAttr('disabled');
        //         $('#bank_info input[name="diposited_by"]').removeAttr('disabled');
        //         $('#bank_info').show();
        //     } else if (this.value == 'commission') {
        //         $('#bank_info select[name="account_no"]').attr('disabled', 'disabled');
        //         $('#bank_info input[name="withdraw_by"]').attr('disabled', 'disabled');
        //         $('#bank_info input[name="diposited_by"]').attr('disabled', 'disabled');
        //         $('#bank_info').hide();
        //         $('#discount').hide();
        //     } else {
        //         $('#bank_info select[name="account_no"]').attr('disabled', 'disabled');
        //         $('#bank_info input[name="withdraw_by"]').attr('disabled', 'disabled');
        //         $('#bank_info input[name="diposited_by"]').attr('disabled', 'disabled');
        //         $('#discount').show();
        //         $('#bank_info').hide();
        //     }
        // });
        $('select[name="payment_method"]').on('change', function () {
    const method = this.value;

    // Hide all info sections and disable their inputs
    $('#bank_info, #baksh_info, #nagad_info, #rocket_info').each(function () {
        $(this).find('select[name="account_no"], input[name="diposited_by"]').attr('disabled', 'disabled');
        $(this).hide();
    });

    // Show and enable the appropriate section based on the selected payment method
    if (method === '0') { // Bank
        $('#bank_info select[name="account_no"]').removeAttr('disabled');
        $('#bank_info input[name="diposited_by"]').removeAttr('disabled');
        $('#bank_info').show();
    } else if (method === '3') { // Bkash
        $('#baksh_info select[name="account_no"]').removeAttr('disabled');
        $('#baksh_info input[name="diposited_by"]').removeAttr('disabled');
        $('#baksh_info').show();
    } else if (method === '4') { // Nagad
        $('#nagad_info select[name="account_no"]').removeAttr('disabled');
        $('#nagad_info input[name="diposited_by"]').removeAttr('disabled');
        $('#nagad_info').show();
    } else if (method === '5') { // Rocket
        $('#rocket_info select[name="account_no"]').removeAttr('disabled');
        $('#rocket_info input[name="diposited_by"]').removeAttr('disabled');
        $('#rocket_info').show();
    }
});
    })
</script>