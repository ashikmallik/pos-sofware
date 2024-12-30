<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

//===================Add Function===================
$total_amount = 0;
$due = 0;
$sell_cat = 3; // for accounts
$sell_product_to_customer = 23;
$sell_item_add = '';
$sell_id = '';

$data = $obj->details_by_cond("tbl_customer", "id!=0 ORDER BY id DESC");
if (($data['id'] + 1) < 10) {
    $STD = "CUS0000";
} else if (($data['id'] + 1) < 100) {
    $STD = "CUS000";
} else if (($data['id'] + 1) < 1000) {
    $STD = "CUS00";
} else if (($data['id'] + 1) < 10000) {
    $STD = "CUS0";
} else {
    $STD = "CUS";
}
$STD .= $data['id'] + 1;

if (isset($_POST['add_sell'])) {
    extract($_POST);
    $i = 0;
    $sellArray = array();
    $total_amount = 0;
    $total_qty = 0;
    $total_discount = 0;
    $total_amount_without_discount = 0;
    $only_total_discount = 0;
    $payment_method_type = 1;




    //due cal 
    // ===================================================================================================



    function duecustomer($obj, $customerId)
    {

        $supplierOrCustomerTransaction = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$customerId'");



        $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved", "supplier_customer='$customerId'");

        $givePaymentToCustomer = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = 12 AND cus_or_sup_id='$customerId'");
        
        $labortransporcostForCustomer = $obj->get_sum_data('tbl_account', 'acc_amount', " cus_or_sup_id='$customerId' AND (acc_type = 201 OR acc_type = 202)");

        $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$customerId'");
        $sales_return = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type =28 AND cus_or_sup_id='$customerId'");

        $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$customerId'");

        $discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$customerId'");

        isset($discountData) ? $discount = $discountData['amount'] : $discount = 0;

        isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0;

        isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0;

        $total_due_withoutret = ($supplierOrCustomerTransaction['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance) + $givePaymentToCustomer+(($labortransporcostForCustomer > 0)?$labortransporcostForCustomer:0);
        if ($sales_return > 0) {
            $total_due = $total_due_withoutret - $sales_return;
        } else {
            $total_due = $total_due_withoutret;
        }
        return $total_due;
    }


      $newcustdue = duecustomer($obj, $customer_id);


    // ======================================================================================================

    foreach ($product_id as $id) { // at first configure / arrange the array for db insert
        $itm_price = !empty($price[$i]) ? $price[$i] : 0;
        $item_price = !empty($price[$i]) ? $price[$i] : 0;
        $com_price_val = !empty($com_price[$i]) ? $com_price[$i] : 0;
        $price_item_total = !empty($total[$i]) ? $total[$i] : 0;
        $given_price = $item_price;

        $discount_existing = (!empty($discount[$i])) ? $discount[$i] : 0;

        $total_qty += $item_qty = !empty($qty[$i]) ? $qty[$i] : 0;

        if (isset($_POST['less_amount']) && !empty($_POST['less_amount']) || !empty($discount_existing)) {
            $item_price_u = ($item_price * $discount[$i]) / 100;
            $less_unit = ($_POST['less_amount'] * 100) / $total_price_amount;

            $item_price = $item_price - (($item_price - $item_price_u) * $less_unit / 100) - $item_price_u;
        }

        //$total = $item_price * $item_qty;

        //$total_amount = $total + $total_amount;
        if ($discount_existing != 0) {
            $only_discount = $_POST['total_amount_wd'][$i] * $discount_existing / 100;
            $only_total_discount = $only_discount + $only_total_discount;
        }
        

        $sellArray[$i] = array(
            'product_id' => $id,
            'price' => $itm_price,
            'price_val' => $price_item_total,
            'qty' => $item_qty,
            'total' => $total,
            'discount' => $discount_existing,
            'given_price' => $given_price,
            'com_price' => $com_price_val,
        );
        $i++;
    }

    $total_amount=round($total_amount);
    
    $transportcost= (!empty($transportCost) ? $transportCost : 0);
    $laborcost = (!empty($laborCost) ? $laborCost : 0);
    $trlaCost = $transportcost+$laborcost;
    $paid_amount = (!empty($paid_amount) ? $paid_amount : 0);
    
    
    
    $due = $total_price - $paid_amount;

    if ((isset($retailer_name) && !empty($retailer_name)) || (isset($retailer_phone) && !empty($retailer_phone))) {

        $form_data_tbl_customer = array(
            'cus_name' => str_replace("'", '', $retailer_name),
            'cus_mobile_no' => isset($retailer_phone) ? str_replace("'", '', $retailer_phone) : null,
            'cus_address' => isset($retailer_address) ? str_replace("'", '', $retailer_address) : null,
            'cus_email' => "",
            'cus_comments' => $retailer_comments,
            'cus_id' => $STD,
            'cus_status' => '1',
            'cus_company' => "",
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $customer_inserted_id = $obj->insert_by_condition("tbl_customer", $form_data_tbl_customer, " ");
        $customer_inserted_data = $obj->details_by_cond("tbl_customer", "id = '$customer_inserted_id'");
        $customer_id = $customer_inserted_data['cus_id'];
    }

    $form_sell_add = array( // array for sell table that id will be used for sell item
        'customer'          => $customer_id,
        'total_price'       => $total_price,
        'total_comission_earn' => $total_comm,
        'payment_recieved'  => (($paid_amount-$trlaCost) > 0)?$paid_amount-$trlaCost:0,
        'due_to_company'    => $total_price -((($paid_amount-$trlaCost) > 0)?$paid_amount-$trlaCost:0),
        'total_qty'         => $total_qty,
        'entry_by'          => $userid,
        'delivery_status'   => 0,
        'less_amount'       => isset($_POST['less_amount']) ? $_POST['less_amount'] : 0,
        'transportcost'     => $transportcost,
        'laborcost'         => $laborcost,
        'entry_date'        => date('Y-m-d'),
        'update_by'         => $userid,
        'labour_name'       => $labour_name,
        'remark'            => $remark
    );
    $sell_id = $obj->insert_by_condition("tbl_sell", $form_sell_add, " ");
    ///////////////////////////////////////////////////////////////////////////
    // $previous_advance = 0;
    // $previous_due = 0;
    // $type = 0;
    // $invoiceData = $obj->details_by_cond("vw_sell", "sell_id='$sell_id'");
    // $customerId = $invoiceData['customer'];
    // $supplierOrCustomerTransaction2 = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$customerId'");



    //                         $supplierOrCustomerRecieved2 = $obj->details_by_cond("vw_supplier_customer_total_recieved", "supplier_customer='$customerId'");

    //                         $givePaymentToCustomer2 = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 12 AND cus_or_sup_id='$customerId'");

    //                         $supplierOrCustomerOpeningDue2 = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$customerId'");
    //                         $sales_return = $obj->get_sum_data('tbl_account','acc_amount', "acc_type =28 AND cus_or_sup_id='$customerId'");
    //                         $supplierOrCustomerOpeningAdvance2 = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$customerId'");

    //                         $discountData2 = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$customerId'");

    //                         isset($discountData2) ? $discountst = $discountData2['amount'] : $discountst = 0;

    //                         isset($supplierOrCustomerOpeningDue2) ? $openingDueBalance = $supplierOrCustomerOpeningDue2['opening_due'] : $openingDueBalance = 0;

    //                         isset($supplierOrCustomerOpeningAdvance2) ? $openingAdvance = $supplierOrCustomerOpeningAdvance2['opening_due'] : $openingAdvance = 0;

    //                         $total_due_withoutret = ($supplierOrCustomerTransaction2['total_price'] - $supplierOrCustomerRecieved2['total_recieved'] - $discountst + $openingDueBalance - $openingAdvance)+ $givePaymentToCustomer2;
    //                         if($sales_return > 0){
    //                         $total_due2 = $total_due_withoutret - $sales_return;
    //                         }
    //                         else
    //                         {
    //                             $total_due2 = $total_due_withoutret;
    //                         }


    // if($newcustdue < 0){
    //     $previous_advance = $newcustdue;
    //     $type = 7;
    // }else{
    //     $previous_due = $newcustdue;
    //     $type = 8;
    // }
    //////////////////////////////////////////////////////////////////////////
    if ($newcustdue < 0) {
        $type = 7;
        $form_invoice_data = array( // array for sell table that id will be used for sell item
            'sell_id' => $sell_id,
            'ref_no' => $_POST['refno'],
            'delivery_date' => !empty($_POST['dalivery_date']) ? date('Y-m-d', strtotime($_POST['dalivery_date'])) : '',
            'delivery_challan' => $_POST['dalivery_challan'],
            'delivery_address' => $_POST['dalivery_address'],
            'contact_ref' => $_POST['contact_ref'],
            'work_order_no' => $_POST['work_order'],
            'unit' => $_POST['unit'],
            'old_previous_advance_due' => $newcustdue,
            'type' => $type,
        );
    } elseif ($newcustdue > 0) {
        $type = 8;
        $form_invoice_data = array( // array for sell table that id will be used for sell item
            'sell_id' => $sell_id,
            'ref_no' => $_POST['refno'],
            'delivery_date' => !empty($_POST['dalivery_date']) ? date('Y-m-d', strtotime($_POST['dalivery_date'])) : '',
            'delivery_challan' => $_POST['dalivery_challan'],
            'delivery_address' => $_POST['dalivery_address'],
            'contact_ref' => $_POST['contact_ref'],
            'work_order_no' => $_POST['work_order'],
            'unit' => $_POST['unit'],
            'old_previous_advance_due' => $newcustdue,
            'type' => $type,
        );
    } else {
        $form_invoice_data = array( // array for sell table that id will be used for sell item
            'sell_id' => $sell_id,
            'ref_no' => $_POST['refno'],
            'delivery_date' => !empty($_POST['dalivery_date']) ? date('Y-m-d', strtotime($_POST['dalivery_date'])) : '',
            'delivery_challan' => $_POST['dalivery_challan'],
            'delivery_address' => $_POST['dalivery_address'],
            'contact_ref' => $_POST['contact_ref'],
            'work_order_no' => $_POST['work_order'],
            'unit' => $_POST['unit'],
            'old_previous_advance_due' => 0,
            'type' => 0,
        );
    }
    //   echo $newcustdue;

    //     exit;
    $invoice_id = $obj->insert_by_condition('tbl_sell_invoice', $form_invoice_data, '');

    // var_dump($_POST);exit;

    $customer_data = $obj->details_by_cond("tbl_customer", "cus_id = '$customer_id'");

    $customer_name = $customer_data['cus_name'];

    $form_tbl_accounts = array(
        'acc_description' => "Total Amount of Sell Product " . $customer_name . " (" . $customer_id . ") And Sell id : $sell_id",
        'acc_amount' => $total_price,
        'acc_type' => $sell_product_to_customer,
        'purchase_or_sell_id' => 's_' . $sell_id,
        'cus_or_sup_id' => $customer_id,
        'acc_head' => 0,
        'payment_method' => $payment_method_type,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    
    $form_tbl_accounts = array(
        'acc_description' => "Total Amount of Sell Product " . $customer_name . " (" . $customer_id . ") And Sell id : $sell_id",
        'acc_amount' => $total_price,
        'acc_type' => $sell_product_to_customer,
        'purchase_or_sell_id' => 's_' . $sell_id,
        'cus_or_sup_id' => $customer_id,
        'acc_head' => 0,
        'payment_method' => $payment_method_type,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    
    if($laborcost > 0){
        $labourCost_accounts=[
        'acc_description' => "Labor Cost for " . $customer_name . " (" . $customer_id . ") And Sell id : $sell_id",
        'acc_amount' => $laborcost,
        'acc_type' => 201,
        'purchase_or_sell_id' => 's_l_' . $sell_id,
        'cus_or_sup_id' => $customer_id,
        'acc_head' => 0,
        'payment_method' => $payment_method_type,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
        ];
       $obj->insert_by_condition("tbl_account", $labourCost_accounts, " ");
    }
    
    if($transportcost > 0){
        $transportCost_accounts=[
        'acc_description' => "Transport Cost for  " . $customer_name . " (" . $customer_id . ") And Sell id : $sell_id",
        'acc_amount' => $transportcost,
        'acc_type' => 202,
        'purchase_or_sell_id' => 's_t_' . $sell_id,
        'cus_or_sup_id' => $customer_id,
        'acc_head' => 0,
        'payment_method' => $payment_method_type,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
        ];
       $obj->insert_by_condition("tbl_account", $transportCost_accounts, " ");
    }
    
    

    if (!empty($paid_amount) && ($paid_amount != 0)) { // if paid amount is filled then account part will work

        if ($payment_method == 'bank') {
            $payment_method_type = 0;
            $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
            $total_balance = $total_balance_Data['balance'];
            $form_data_for_bank = array(
                'account_no' => $account_no,
                'chq_no' => $_POST['chq_no'],
                'description' => "Company Receipt form Customer " . $customer_name . " (" . $customer_id . ") And Sell id : $sell_id",
                'credit' => 0,
                'debit' => $paid_amount,
                'balance' => ($total_balance + $paid_amount),
                'diposited_by' => (empty($diposited_by) ? $customer_name : $diposited_by),
                'entry_by' => $userid,
                'entry_date' => date('Y-m-d'),
                'update_by' => $userid
            );
            $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
        }

        $form_tbl_accounts = array(
            'acc_description' => "Company Receipt from Customer " . $customer_name . " (" . $customer_id . ") And Sell id : $sell_id",
            'acc_amount' => $paid_amount,
            'acc_type' => $sell_cat,
            'purchase_or_sell_id' => 's_' . $sell_id,
            'cus_or_sup_id' => $customer_id,
            'acc_head' => 0,
            'payment_method' => $payment_method_type,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    }

    if ($sell_id) { // per item saved one by one in sell item database table

        foreach ($sellArray as $singleSell) {

            $form_sell_item = array(
                'customer' => $customer_id,
                'sell_no' => $sell_id,
                'product_id' => $singleSell['product_id'],
                'price' => $singleSell['price'],
                'commission_per_unit' => $singleSell['com_price'],
                'qty' => $singleSell['qty'],
                'total_amount' => $singleSell['price_val'],
                'discount_exist' => $singleSell['discount'],
                'delivery_status' => 0,
                'update_date' => date('Y-m-d'),
                'update_by' => $userid
            );
            $sell_item_add = $obj->insert_by_condition(" tbl_sell_item", $form_sell_item, " ");

            //            $form_sell_print = array(
            //                    'sell_item_id' => $sell_item_add,
            //                    'sell_id' => $sell_id ,
            //                    'product_id' => $singleSell['product_id'] ,
            //                    'price' => $singleSell['given_price']
            //            );
            //            $sell_item_print_add = $obj->insert_by_condition(" tbl_sell_print", $form_sell_print, " ");
        }
    }

    if (!empty($_POST['installment'])) {
        $form_installment_item = array(
            'cus_id' => $customer_id,
            'sell_id' => $sell_id,
            'installment_month' => $_POST['installment'],
            'total_installment' => $due,
            'installment_due' => $due,
            'punishment' => 0,
            'date' => date('Y-m-d'),
        );

        $installmentid = $obj->insert_by_condition("installments", $form_installment_item, " ");

        $form_installment_tre_item = array(
            'installment_id' => $installmentid,
            'installment_payment' => 0,
            'is_late' => 0,
            'date' => date('Y-m-d'),
        );

        for ($i = 1; $i <= $_POST['installment']; $i++) {
            $obj->insert_by_condition("installment_transaction", $form_installment_tre_item, " ");
        }
    }

    if ($sell_id) {

        //SMS
        $email = $cus_email;
        $mobile = $cus_mobile;

        $dqty = 0;
        $name = "";
        for ($i = 0; $i < count($item_names); $i++) {
            $dqty +=  $qty[$i];
            $name .= $item_names[$i] . ', ';
        }

        if ($installment > 0) {
            $installments = "and Installment " . $installment;
        } else {
            $installments = "";
        }



        global $notification;
        $total_amount += $trlaCost;
        // $newcustdue = -$newcustdue;
            if($newcustdue < 0){
                $advance_due = " Advance ".abs($newcustdue);
               
                
                if($total_amount < abs($newcustdue)){
					$totalbillsm= $total_amount;
				}else{
					$totalbillsm= $total_amount-abs($newcustdue);
				}
                
            }else{
                $advance_due = " Due ". abs($newcustdue);
               

                $totalbillsm= $total_amount+abs($newcustdue);
            }
            
            $totalAdvDue = (($total_amount + $newcustdue) - $paid_amount);
            if($totalAdvDue < 0){
                 $total_advance_due = " Advance Balance ".abs($totalAdvDue);
            }else{
                 $total_advance_due =" Due Balance ".abs($totalAdvDue);
            }

             $subject = "New Purchese Products Information";
             $body = "Dear Customer, Your purchase amount " . ($total_amount) . " Taka, Previous " .$advance_due. "  Taka, Total Bill  " . $totalbillsm. " Taka, Paid Amount " . ($paid_amount) . " Taka  And  ".$total_advance_due ;



        if (!empty($retailer_phone)) {
            //sms 
            $notification .=  $obj->smsSend($retailer_phone, $body);
        } else {
            if (!empty($mobile)) {
                //sms 
                $notification .=  $obj->smsSend($mobile, $body);
            }
        }



        if (!empty($email)) {
            //email
            $notification .= $obj->emailSend($email, $body, $subject);
        }


?>
        <script>
            window.location = "?q=add_sell";
            window.open('pdf/invoice.php?invoiceId=<?php echo $sell_id ?>', '_blank');
        </script>
<?php
    } else {
        $notification = '<div class="alert alert-danger">Insert Failed</div>';
    }
}

?>
<!--===================end Function===================-->
<style>
    .delete_row {
        margin-top: 3px;
    }

    .alert {
        margin-bottom: 0px !important;
        padding: 6px !important;
    }

    .alert-info {
        color: #0b4a69 !important;
    }
</style>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<script>
    function numbersOnly(e) // Numeric Validation
    {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) {
            if ((unicode < 2534 || unicode > 2543) && (unicode < 46 || unicode > 57)) {
                return false;
            } else if (unicode == 47) {
                return false;
            }
        }
    }
</script>

<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<span style="color: red; font-size: 20px;text-align:left;"></span>

<div class="col-md-12 bg-teal-800 text-center" style="margin-bottom:5px;">
    <h4>Welcome to New Sell Item Page</h4>
</div>

<div class="row" style="padding:10px; font-size: 12px;">
    <form class="form-horizontal" role="form" method="post">
        <div class="col-md-12" style="font-size: 12px;">

            <div class="row">
                <div class="col-md-10" style="margin-top:15px;">
                    <div class="form-group">
                        <div id="customer_info">
                            <label class="control-label col-sm-4" for="sup_id">Customer Id:</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="customer_id" id="status" data-live-search="true">
                                    <option></option>
                                    <?php
                                    $i = '0';
                                    foreach ($obj->view_all("tbl_customer") as $customer) {
                                        $i++;
                                    ?>
                                        <option value="<?php echo isset($customer['cus_id']) ? $customer['cus_id'] : NULL; ?>"><?php echo isset($customer['cus_company']) ? $customer['cus_company'] : NULL; ?>
                                            - <?php echo isset($customer['cus_id']) ? $customer['cus_id'] : NULL; ?> - (<?php echo $customer['cus_name']; ?>)</option>
                                    <?php } ?>
                                </select>
                            </div>
                           
                        </div>
                        <div id="retailer_info" style="display: none">
                            <div class="col-sm-4 col-md-offset-3">
                                <input type="text" class="form-control" name="retailer_name" disabled placeholder="Retailer Name">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="retailer_phone" disabled placeholder="Retailer Phone">
                            </div>
                            <div class="col-sm-12"></div>
                            <div class="col-sm-4 col-md-offset-3">
                                <textarea type="text" class="form-control" name="retailer_address" disabled placeholder="Retailer Address"></textarea>
                            </div>
                            <div class="col-sm-3">
                                <textarea class="form-control" name="retailer_comments" disabled placeholder="Retailer Comments"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-sm btn-primary" id="retailer_trigger">Random Retailer</button>
                        </div>
                    </div>
                     <div style="text-align:center" id="customer_dueADv"></div>
                </div>
            </div>
            <?php

            ?>
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <label class="control-label col-sm-3  pull-left" for="sup_id">Item Name : </label>
                        <div class="col-sm-9">
                            <select class="form-control" required="required" name="item_name" id="status" data-live-search="true">
                                <option></option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all("tbl_item_with_price") as $item) {
                                    print_r($item);
                                    $i++; ?>
                                    <option data-item="<?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?>" data-unit="<?php echo isset($item['unit']) ? $item['unit'] : NULL; ?>" data-price="<?php echo isset($item['item_price']) ? $item['item_price'] : NULL; ?>" value="<?php echo isset($item['item_id']) ? $item['item_id'] : NULL; ?>"><?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?>
                                        - <?php echo isset($item['item_price']) ? $item['item_price'] . ' tk' : NULL; ?></option>
                                <?php } ?>
                            </select>
                            <b><small class="text-primary" id="do_number_details"></small></b>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <table class="table" style="margin-bottom:0px;  ">
                        <thead>
                            <tr>
                                <th class="col-md-1 text-center">SL</th>
                                <th class="col-md-3 text-center">Product</th>
                                <th class="col-md-1 text-center">Qty</th>
                                <th class="col-md-1 text-center">Unite Price</th>
                                <th class="col-md-2 text-center">Commission (Per unit)</th>
                                <th class="col-md-2 text-center">Total</th>
                                <th class="col-md-1 text-center">Action</th>
                                <th class="col-md-2 text-center">Notice</th>
                                <th class="col-md-1 text-center">Discount %</th>
                            </tr>
                        </thead>
                        <tbody id="orderTable">

                            <tr id="row_1"></tr>

                        </tbody>

                        <hr>
                        <tr id="total_row" class="hidden bg-success">
                            <td colspan="3" class="text-center" style="padding-right: 0px !important;">
                                <div class="col-md-3">
                                    <p style="margin-top:6px"><b>Receipt Amount </b></p>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <select class="btn btn-default" name="payment_method">
                                                <option value="cash" selected="selected"> Cash</option>
                                                <option value="bank"> Bank</option>
                                            </select>
                                        </div><!-- btn-group -->
                                        <input type="text" style="height:32px" onkeypress="return numbersOnly(event)" name="paid_amount" class="form-control">
                                        <span class="input-group-addon">TK</span>
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-addon">Installment In</div>
                                        <input type="number" id="installment" name="installment" class="form-control" placeholder="15 Days">
                                        <div class="input-group-addon">15 Days</div>
                                    </div>
                                </div>
                            </td>
                            <td colspan="2" class="text-center">
                                <div class="input-group">
                                    <div class="input-group-addon">Total Qty</div>
                                    <input type="text" id="total_quantity" class="form-control" placeholder="Total Qty">
                                </div>
                            </td>
                            <td colspan="3" class="text-center">
                                <div class="input-group">
                                    <div class="input-group-addon">Total Price</div>
                                    <input type="text" id="total_price" name="total_price" class="form-control" placeholder="Total Price">
                                    <input type="hidden" id="total_price_without_less" name="total_price_amount" class="form-control" placeholder="Total Price">
                                    <input id="total_comm" name="total_comm" type="hidden" class="form-control">
                                    <div class="input-group-addon">TK</div>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-addon">Less amount</div>
                                    <input type="text" id="less_amount" name="less_amount" class="form-control" placeholder="Less Amount">
                                    <div class="input-group-addon">TK</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <div id="bank_info" style="display: none;" class="col-md-12 pull-right">
                        <div class="col-md-7">
                            <label class="control-label col-sm-4" for="damageRate">Bank Account</label>
                            <div class="col-sm-8">
                                <select class="form-control" disabled required="required" name="account_no" id="status">
                                    <option></option>
                                    <?php
                                    $i = '0';
                                    foreach ($obj->view_all("bank_registration") as $value) {
                                        $i++;
                                    ?>
                                        <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>
                                            --<?php echo isset($value['bank_name']) ? $value['bank_name'] : NULL; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 form-group">
                            <label class="control-label col-sm-5">Check No</label>
                            <div class="col-sm-7">
                                <input type="text" name="chq_no" placeholder="Check No" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label class="control-label col-sm-4" for="diposited_by">Depositor Name</label>
                            <div class="col-sm-8">
                                <input type="text" disabled name="diposited_by" placeholder="Default take Customer name" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                
                 <div  class="col-md-12 pull-right">
                        <div class="col-md-6">
                            <label class="control-label col-sm-4" for="damageRate">Ref. No.</label>
                            <div class="col-sm-8">
                                <input type="text" name="refno" placeholder="Ref. No." class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label col-sm-4">Delivery Date</label>
                            <div class="col-sm-8">
                                <input type="text" name="dalivery_date" placeholder="Delivery Date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label col-sm-4" >Delivery Challan No.</label>
                            <div class="col-sm-8">
                                <input type="text" name="dalivery_challan" placeholder="Delivery Challan No." class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label col-sm-4">Delivery Address</label>
                            <div class="col-sm-8">
                                <input type="text" name="dalivery_address" placeholder="Delivery Address" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label col-sm-4" f>Contact Ref No.</label>
                            <div class="col-sm-8">
                                <input type="text" name="contact_ref" placeholder="Contact Ref No." class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label class="control-label col-sm-4">Work Order No.</label>
                            <div class="col-sm-8">
                                <input type="text" name="work_order" placeholder="Work Order No." class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="control-label col-sm-4">Unit</label>
                            <div class="col-sm-8">
                                <input type="text" name="unit" placeholder="Unit" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12 " style="margin-top:10px">
                        <div class="col-md-6">
                            <label class="control-label col-sm-4" >Transport Cost</label>
                            <div class="col-sm-8">
                                <input type="text" onkeypress="return numbersOnly(event)" id="transportCost"  value="0" name="transportCost" placeholder="Transport Cost" class="form-control">
                            </div>
                        </div>
                     </div>    
                    <div class="col-md-12 " style="margin-top:10px">   
                        <div class="col-md-6">
                            <label class="control-label col-sm-4" >Labour  Cost</label>
                            <div class="col-sm-8">
                                <input type="text"  onkeypress="return numbersOnly(event)" id="laborCost" value="0" name="laborCost" placeholder="Labour  Cost" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label col-sm-4" >Labour  Name</label>
                            <div class="col-sm-8">
                                <input type="text"  name="labour_name" placeholder="Labour  Name" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 " style="margin-top:10px">
                         <label class="control-label col-sm-2" >Remark</label>
                            <div class="col-sm-8">
                                <input type="text"  name="remark" placeholder="Remark" class="form-control">
                            </div>
                    </div>
                    
            </div>
        </div>
        <span hidden="hidden" id="hiddeninput"></span>
        <div class="col-md-12" style="margin-top:30px;">
            <div class="col-md-2 col-md-offset-5">
                <div class="text-center">
                    <button type="submit" class="btn btn-block btn-success" id="add_sell" name="add_sell">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        $('input[name="dalivery_date"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });

        $('select[name="customer_id"]').selectpicker();
        $('select[name="item_name"]').selectpicker();

        var row = 1;
        var crow = 1;

        $('select[name="customer_id"]').on('change', function(e) {
            row = 1;
            $('#orderTable').html('<tr id="row_1"></tr>');
            $('table tr#total_row').addClass('hidden');
        });

        $('form').submit(function(submitEvent) {

            if (!$('select[name="customer_id"]').find(':selected').val()) {
                if (!$('input[name="retailer_name"]').val()) {
                    submitEvent.preventDefault();
                    alert('Sorry Must Have Customer Name');
                }
            }

            $('#orderTable').each(function() {
                var qtyErrorFlag = true;
                var stockErrorFlag = true;
                var emptyFieldCount = 0;
                var stockErrorCount = 0;

                $(this).find('input#qty').each(function() {
                    if (!$(this).val() || $(this).val() == '0') {
                        qtyErrorFlag = true;
                        emptyFieldCount++;
                    }
                });

                $(this).find('div.noticeTab').each(function() {
                    if ($(this).children().hasClass("alert-danger")) {
                        stockErrorFlag = true;
                        stockErrorCount++;
                    }
                });

                if (qtyErrorFlag == false) {
                    submitEvent.preventDefault();
                    alert('Sorry Must Provide all Qty. ' + emptyFieldCount + ' Qty field is empty.');

                } else if (stockErrorFlag == false) {
                    submitEvent.preventDefault();
                    alert('Sorry Must Clear this  ' + stockErrorCount + ' Stock unavailable error');
                }
            });
        });

        function itemAlreadyExist(itemId) {
            var itemExist = false;

            $('#orderTable').find('tr').each(function() {

                $(this).find('input[name="product_id[]"]').each(function() {
                    if ($(this).val() == itemId) {
                        itemExist = true;
                    }
                });
            });
            return itemExist;
        }

        var total_price_without_less = '';

        function totalPriceCalculate() { // return total price

            var totalPrice = 0;
            $('#orderTable').each(function() {

                $(this).find('input#total').each(function() {
                    if (!!$(this).val()) {
                        totalPrice += parseInt($(this).val());
                    }
                });
            });

            $('input#total_price_without_less').val(totalPrice);
            total_price_without_less = totalPrice;
            // return totalPrice - total_less;
            var remainTotalPrice = totalPrice - total_less;
            var lCost =  parseInt($('input#laborCost').val());
            var tCost =  parseInt($('input#transportCost').val());
                  
           return remainTotalPrice+(lCost>0?lCost:0)+(tCost>0?tCost:0);
            
            
        }

        function totalQtyCalculate() { // return total qty
            var totalQty = 0;
            $('#orderTable').each(function() {

                $(this).find('input#qty').each(function() {
                    if (!!$(this).val()) {
                        totalQty += parseFloat($(this).val());
                    }
                });
            });
            return totalQty;
        }
        function calculateRowTotal(row) {
            var price = parseFloat($(row).find('input#price').val()) || 0;
            var qty = parseFloat($(row).find('input#qty').val()) || 0;
            var comPrice = parseFloat($(row).find('input#com_price').val()) || 0;
    
            var unitTotal = price - comPrice;
            var total = (unitTotal * qty);// Subtract com_price from total
            return total;
            }
            
        function totalCommCalculate() { // return total qty
            var totalComm = 0;
            $('#orderTable').each(function () {
                $(this).find('input#com_price').each(function () {
                    if (!!$(this).val()) {
                        totalComm += parseFloat($(this).val());
                    }
                });
            });
            return totalComm;
        }

        function addRow(message) {

            $("tr#row_" + row).html(message);
            $('#orderTable').append('<tr id="row_' + (row + 1) + '"></tr>');
            row++;
            crow++;
        }

        function showItemDoDetails(itemId) {
            $.get('ajax_action/ajax_check_stock.php', {
                'item': itemId
            }, function(result) {
                $('small#do_number_details').html('Total Stock Qty is : ' + result.stock_qty + '');

            }, 'json');
        }
        
        $("#orderTable").on('keyup', 'input#qty, input#price, input#com_price', function () {
                var row = $(this).closest('tr');
                var total = calculateRowTotal(row);
                row.find('input#total').val(total);
        
                $('input#total_price').val(totalPriceCalculate());
                $('input#total_quantity').val(totalQtyCalculate());
                $('input#total_comm').val(totalCommCalculate());
        });
        
        
      

        $('select[name="item_name"]').on('change', function(e) { // add new row when new item selected

            var itemName = $(this).find(':selected').data('item');
            var itemPrice = $(this).find(':selected').data('price');
            var itemId = $(this).find(':selected').val();
            var itemUnit = $(this).find(':selected').data('unit');
            if (!itemAlreadyExist(itemId)) {
                showItemDoDetails(itemId);

                addRow('<td class="text-center">' + crow + '</td><td><input readonly type="text" id="item_name" name="item_names[]" value="' + itemName + '" class="form-control"></td>' +
                    '<input type="hidden" id="product_id" name="product_id[]" value="' + itemId + '" class="form-control">' +
                    '<td><input type="text" onkeypress="return numbersOnly(event)"  id="qty" name="qty[]" class="form-control"></td>' +
                    '<td><input type="text" onkeypress="return numbersOnly(event)"  id="price" name="price[]" value="' + itemPrice + '" class="form-control"></td>' +
                    '<td><input type="text" id="com_price"  name="com_price[]" placeholder="Commission" class="form-control"></td>' +
                    '<td><input type="text" readonly onkeypress="return numbersOnly(event)"   id="total" name="total[]" class="form-control"></td>' +
                    '<td><a id="delete_row' + itemId + '" data-id class="delete_row btn btn-danger btn-sm">Remove</a></td>' +
                    '<td><div class="noticeTab" id="errrMsg' + itemId + '"><div class="alert alert-info">Please add Qty</div></td>' +
                    '<td><input type="text" onkeypress="return numbersOnly(event)"  id="discount" name="discount[]" class="form-control"><input type="hidden"  id="total_amount_wd" name="total_amount_wd[]"></td>');

                $('table tr#total_row').removeClass('hidden');
            } else {
                alert("This DO Already Exist");
            }

        });

        $("#orderTable").on('click', '.delete_row', function() { // delete entire row when press remove button
            crow = crow - 1;
            if (crow == 1) {
                $('select[name="item_name"]').attr('required', 'required');
            } else {
                $('select[name="item_name"]').removeAttr('required');
            }

            $('select[name="item_name"]').val('');
            $(this).parent().parent().remove();
            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());

        });

        $("#orderTable").on('keyup', 'input#qty', function() { // total price and total field updated while give new qty

            var price = $(this).parent().parent().find('td > input#price').val();
            var total_amount_wd = $(this).parent().parent().find('td > input#total_amount_wd').val();
            console.log(total_amount_wd);
            var qty = $(this).val();
            var itemId = $(this).parent().parent().find('input#product_id').val();
            var url = 'ajax_action/ajax_check_stock.php';
            var getData = {
                'item': itemId
            };
            if (qty) {
                if (qty != '0') {
                    $.get(url, getData, function(data) {
                        if (data.stock_qty >= qty) {
                            $('td div#errrMsg' + itemId).html('<div class="alert alert-success">You Can Proceed</div>');
                            // $("#add_sell").attr("disabled", false);

                        } else {

                            // $('td div#errrMsg' + itemId).html('<div class="alert alert-danger"><strong>Sorry! ' + Math.abs(data.stock_qty - qty) + ' pc short</strong></div>');
                            // $("#add_sell").attr("disabled", true);
                            alert("Sorry Qty is Not Available!");
                            $('td a#delete_row' + itemId)[0].click();
                        }
                    }, 'json');
                } else {
                    $('td div#errrMsg' + itemId).html('<div class="alert alert-info">Please add Qty</div>');
                }
            }
            var total = price * qty;

            $(this).parent()
                .parent()
                .find('td > input#total')
                .val(total);

            $(this).parent()
                .parent()
                .find('td > input#total_amount_wd')
                .val(total);

            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());
        });

        $("#orderTable").on('keyup', 'input#price', function() { // total price and total field updated while update price
            var price = $(this).val();
            var qty = $(this).parent().parent().find('td > input#qty').val();
            var total_amount_wd = $(this).parent().parent().find('td > input#total_amount_wd').val();
            var total = price * qty;
            $(this).parent()
                .parent()
                .find('td > input#total')
                .val(total);

            $(this).parent()
                .parent()
                .find('td > input#total_amount_wd')
                .val(total);

            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());
        });

        var total_less = '';

        $("#total_row").on('keyup', 'input#less_amount', function() { // total price and total field updated while update price
            total_less = $(this).val();
            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());
        });

        $('tr td input#total_price').on('click', function() {

            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());

        })

        $("#orderTable").on('keyup', 'input#discount', function() { // total discount field updated while update price

            var discount = $(this).val();

            var qty = $(this).parent().parent().find('td > input#qty').val();
            if (!qty) {
                alert("Please fill the qty first before discount")
            }
            var price = $(this).parent().parent().find('td > input#price').val();

            var total = price * qty;

            var discountAmount = total - total * discount / 100;

            $(this).parent()
                .parent()
                .find('td > input#total')
                .val(discountAmount);
            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());
        });

        $('tr td input#total_price').on('click', function() {

            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());

        })




        $('select[name="customer_id"]').on('change', function() {
            var cuid = this.value
            cusidcheck = cuid;
            
          

            $.get('ajax_action/ajax_creditlimit_check.php', {
                'cusId': cuid
            }, function(result) {

                console.log(result);
                
                
                
                total_due = result.total_due;
                customer = result.customerdetails;
                
                
                $('#customer_dueADv').html((total_due > 0)?  'Total Due ' +total_due : 'Total Advance ' +total_due);

                var frominput = '';
                frominput += " <input name='cus_email' value='" + customer.cus_email + "' /> ";
                frominput += " <input name='cus_mobile' value='" + customer.cus_mobile_no + "' /> ";
                frominput += " <input name='customer_total_due' value='" + total_due + "' /> ";
                $('#hiddeninput input').remove();
                $('#hiddeninput').append(frominput);

                if (cuid.slice(0, 2) == 'D-') {
                    gettimitedcredit = result.totalbalance;
                    $('small#creditlimits').html('Dealar  Remaining  Credit Balance : ' + result.totalbalance + '');
                } else {
                    $('small#creditlimits').html('');
                }
            }, 'json');
        });




        $('select[name="payment_method"').on('change', function() { // banking section will show when click bank
            if (this.value == 'bank') {
                $('#bank_info select[name="account_no"]').removeAttr('disabled');
                $('#bank_info input[name="diposited_by"]').removeAttr('disabled');
                $('#bank_info').show();
            } else {
                $('#bank_info select[name="account_no"]').attr('disabled', 'disabled');
                $('#bank_info input[name="diposited_by"]').attr('disabled', 'disabled');
                $('#bank_info').hide();
            }
        });

        $('button#retailer_trigger').on('click', function(e) {

            e.preventDefault();

            $('#customer_info').toggle();
            $('#retailer_info').toggle();

            if ($("input[name='customer_id']").prop('disabled') == true) {
                $("input[name='customer_id']").prop("disabled", false);
            } else {
                $("input[name='customer_id']").prop("disabled", true);
            }

            if ($("input[name='retailer_name']").prop('disabled') == true) {
                $("input[name='retailer_name']").prop("disabled", false);
            } else {
                $("input[name='retailer_name']").prop("disabled", true);
            }

            if ($("input[name='retailer_phone']").prop('disabled') == true) {
                $("input[name='retailer_phone']").prop("disabled", false);
            } else {
                $("input[name='retailer_phone']").prop("disabled", true);
            }

            if ($("textarea[name='retailer_address']").prop('disabled') == true) {
                $("textarea[name='retailer_address']").prop("disabled", false);
            } else {
                $("textarea[name='retailer_address']").prop("disabled", true);
            }

            if ($("textarea[name='retailer_comments']").prop('disabled') == true) {
                $("textarea[name='retailer_comments']").prop("disabled", false);
            } else {
                $("textarea[name='retailer_comments']").prop("disabled", true);
            }
        });


       $("#transportCost").on('keyup', function() { // total price and total field updated while update price
            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());
        });
        $("#laborCost").on('keyup', function() { // total price and total field updated while update price
            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());
        });


    });
</script>