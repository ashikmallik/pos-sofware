<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customerId = isset($_GET['customerId']) ? $_GET['customerId'] : null;

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

$customerPersonalData = $obj->details_by_cond("tbl_customer", "`cus_id` = '$customerId'");

if (isset($customerPersonalData) && !empty($customerPersonalData)) {
    $customerTotalSecMoneyBackData = $obj->details_selected_field_by_cond('tbl_security_money_transaction', 'SUM(amount) as total_back_amount', 'pay_receive = 0 AND customer_id = ' . $customerPersonalData['id']);
    $customerTotalSecMoneyData = $obj->details_selected_field_by_cond('tbl_security_money_transaction', 'SUM(amount) as total_amount', 'pay_receive = 1 AND customer_id = ' . $customerPersonalData['id']);
    $customerTotalSecMoneyBack = isset($customerTotalSecMoneyBackData['total_back_amount']) ? $customerTotalSecMoneyBackData['total_back_amount'] : 0;
    $total_security_money = $customerTotalSecMoneyData['total_amount'] - $customerTotalSecMoneyBack;
}

$customerName = $customerPersonalData['cus_name'];

$supplierOrCustomerTransaction = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$customerId'");
$supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved", "supplier_customer='$customerId'");

$supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$customerId'");

$supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$customerId'");


$discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$customerId'");

isset($discountData) ? $discount = $discountData['amount'] : $discount = 0;

isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0;

isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0;

$total_due = ($supplierOrCustomerTransaction['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance);

$total_due_a = ($supplierOrCustomerTransaction['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance);

if (isset($_POST['adjust'])) {
    extract($_POST);
    $amount = (isset($_POST['amount']))?$_POST['amount']:0;
    if($adjustment_type == 1){
        $amount = $prev_amount + $amount;
    }
    if($adjustment_type == 0){
        $amount = $prev_amount - $amount;
    }
    $adjustmentData = array(         
        'acc_amount' => $amount
    );
    $adjustment_amount = $obj->Update_data("tbl_account", $adjustmentData, "acc_id = '$acc_id'");
    if ($adjustment_amount) {
        ?>
        <script>
            window.location = 'index.php?q=customer_ledger&customerId=<?= $_GET["customerId"]?>';
        </script>
        <?php
    }
}

if (isset($_POST['search'])) {
    extract($_POST);
    if (!empty($_POST['startDate'])){
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));
        $allCustomerData = $obj->view_all_by_cond("vw_sell", "`customer` = '$customerId' AND entry_date BETWEEN '$startDate' and '$endDate' order by sell_id DESC");
        $customerPaymentData = $obj->view_all_by_cond("tbl_account", "`cus_or_sup_id` = '$customerId'  AND entry_date BETWEEN '$startDate' and '$endDate' order by entry_date");
        $sales_return = $obj->get_sum_data('tbl_return','total_return_price',"type=0 AND cus_or_sup_id='$customerId' AND date BETWEEN '$startDate' and '$endDate'");
    }
} else {
    $startDate = date('Y-m-01');
    $date = date('Y-m-d');
    $allCustomerData = $obj->view_all_by_cond("vw_sell", "`customer` = '$customerId' AND MONTH(entry_date)=MONTH('$date') and YEAR (entry_date)= YEAR('$date') order by sell_id DESC");
    $customerPaymentData = $obj->view_all_by_cond("tbl_account", "`cus_or_sup_id` = '$customerId' AND MONTH(entry_date)=MONTH('$date') and YEAR
        (entry_date)= YEAR('$date') order by entry_date");
    $sales_return = $obj->get_sum_data('tbl_return','total_return_price',"type=0 AND cus_or_sup_id='$customerId' AND MONTH(date)=MONTH('$date') and YEAR(date)= YEAR('$date')");
}
$balance_bd = $obj->view_all_by_cond("tbl_account", "cus_or_sup_id='$customerId' AND entry_date < '$startDate'");
$balance_of_bd = 0;
$total_debit_bd = 0;
$total_credit_bd = 0;
foreach ($balance_bd as $cusPay) {
                            $sl++;
                            $debit_bd = 0;
                            $credit_bd = 0;
                            if ($cusPay['acc_type'] == $expenseType || $cusPay['acc_type'] == $purchasePaymentType
                                || $cusPay['acc_type'] == $supplierPurchaseIndividualPaymentType
                                || $cusPay['acc_type'] == $giveCashToCustomer
                                || $cusPay['acc_type'] == 201
                                || $cusPay['acc_type'] == 202
                                || $cusPay['acc_type'] == $loanGiveToPersonType
                                || $cusPay['acc_type'] == $CompanyRepayHisLoanType
                                || $cusPay['acc_type'] == $CompanyBackSecurityMoneyToCustomerType
                                || $cusPay['acc_type'] == $CompanyGiveSecurityMoneyToSupplierType
                                || $cusPay['acc_type'] == $CompanyGivePaymentEmployeeType
                                || $cusPay['acc_type'] == $supplierPurchaseIndividualPaymentType
                                || $cusPay['acc_type'] == $CustomerDue
                                || $cusPay['acc_type'] == $SupplierAdvance
                                || $cusPay['acc_type'] == $SellProductBill
                            ) {
                                $debit_bd = $cusPay['acc_amount'];
                                $balance_of_bd += $debit_bd;
                                $total_debit_bd += $debit_bd;
                            } else {
                                $credit_bd = $cusPay['acc_amount'];
                                $balance_of_bd -= $credit_bd;
                                $total_credit_bd += $credit_bd;
                            }
                            } 
?>


<style>
    .delete_row {
        margin-top: 3px;
    }


    .radio-inline{
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .radio-inline input{
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .checkmark {
      position: absolute;
      top: 0;
      left: 0;
      height: 25px;
      width: 25px;
      color:#4e4747;
      border-radius: 50%;
  }

  .radio-inline:hover input ~ .checkmark {
      color: #1984a1;;
  }

  .radio-inline input:checked ~ .checkmark {
      color: #2196F3;
  }

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet"/>

<div class="col-md-12 bg-grey-800" style="margin-top:20px;">
    <div class="col-md-8">
        <h4><strong>View Customer Ledger name <?php echo $customerPersonalData['cus_company'] ?>
        <?php
        if (isset($_POST['search'])) {
            echo date("d-M-Y", strtotime($_POST['startDate'])).' To ';
            echo date("d-M-Y", strtotime($_POST['endDate']));
        } else {
                   // echo 'This Months';
        } ?>
    </strong></h4>
</div>
<div class="col-md-4" style="padding-top:5px;">
  
</div>
</div>
<div class="col-md-12 bg-teal" style="margin-bottom: 15px;padding:5px 0;">
    <div class="col-md-4"><p>Mobile : <?php echo !empty($customerPersonalData['cus_mobile_no']) ? $customerPersonalData['cus_mobile_no'] : 'No Number'; ?></p>
    </div>
    <div class="col-md-4"><p>Address : <?php echo $customerPersonalData['cus_address'] ?></p></div>
    <div class="col-md-4"><p>Email : <?php echo $customerPersonalData['cus_email'] ?></p></div>
</div>

<div class="col-md-12 bg-teal-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateMonth">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" required="required" type="text" name="startDate" value="<?php echo isset($_POST['startDate'])? $_POST['startDate']:''; ?>" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="damageRate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" required="required" name="endDate" value="<?php echo isset($_POST['endDate'])? $_POST['endDate']:''; ?>" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default"><span class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>

<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-10" style="margin-bottom: 10px;">
                <h3 style="font-weight: bold; color: gray;">Terget Seal : <?php echo number_format($customerPersonalData['target'],2) ?> TK</h3>
            </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable-btn">
                <thead class="bg-slate-800">
                    <tr>
                        <th class="text-center col-md-1">Print</th>
                        <th class="text-center col-md-1">Invoice Id</th>
                        <th class="text-center col-md-1">Total Qty</th>
                        <th class="text-center col-md-2">Total Price</th>
                        <th class="text-center col-md-2">Total Comission</th>
                        <th class="text-center col-md-1">Payment</th>
                        
                        <th class="text-center col-md-1">Day</th>
                        <th class="text-center col-md-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $total_qty = 0;
                    $total_price = 0;
                    $total_payment = 0;
                    $total_due = 0;
                    $total_comm = 0;
                    foreach ($allCustomerData as $customer) {
                        $sellId = $customer['sell_id'];
                        $sellItemData = $obj->view_all_by_cond("vw_sell_item", "sell_id=$sellId");
                        $unit = isset($sellItemData[0]['unit'])? $sellItemData[0]['unit']: NULL;
                        $i++;
                        $total_comm += $customer['total_comission_earn'];
                        $total_qty = $customer['total_qty'] + $total_qty;
                        $total_price = $customer['total_price'] + $total_price;
                        $total_payment = $customer['payment_recieved'] + $total_payment;
                        $total_due = $customer['due_to_company'] + $total_due;
                        $bill_id = $customer['sell_id'];

                        if ($bill_id < 10) {$STD = "0000";}
                        else if ($bill_id < 100) {$STD = "000";}
                        else if ($bill_id < 1000) {$STD = "00";}
                        else if ($bill_id < 10000) {$STD = "0";}
                        else {$STD = "";}
                        ?>
                        <tr>
                            <td class="text-center ">
                                <a type="button" target="_blank" href="pdf/invoice.php?invoiceId=<?php echo $bill_id ?>" class="btn bg-grey-800 btn-primary btn-xs"><span class="glyphicon glyphicon-print"></span>
                                </a>
                            </td>
                            <td class="text-center"><?php echo $STD.$bill_id; ?></td>
                            <td class="text-center">
                                <button href="view/view_sell_item.php?invoiceId=<?php echo $bill_id; ?>" class="btn btn-primary bg-slate-700 btn-xs open-popup-link" data-effect="mfp-zoom-in"><?php echo $customer['total_qty']." ".$unit;?> </a></button>
                            </td>
                            <td class="text-right"><?php echo isset($customer['total_price']) ? number_format($customer['total_price'],2) . ' tk.' : NULL; ?></td>
                            <td class="text-right"><?php echo isset($customer['total_comission_earn']) ? number_format($customer['total_comission_earn'], 2) . ' tk.' : NULL; ?></td>
                            <td class="text-right"><?php echo isset($customer['payment_recieved']) ? number_format($customer['payment_recieved'],2) . ' TK.' : NULL; ?></td>
                            <!--<td class="text-right"><?php echo isset($customer['due_to_company']) ? number_format($customer['due_to_company'],2) . ' TK.' : NULL; ?></td>-->
                            <td class="text-center"><?php echo isset($customer['entry_date']) ? date('d-M-y', strtotime($customer['entry_date'])) : NULL; ?></td>

                            <td class="text-center">
                                <div class="btn-group" style="margin-top:5px">
                                    <?php echo '<a type="button" href="?q=edit_sell&invoiceId=' . $customer['sell_id'] . '" class="btn btn-primary btn-xs">Edit</a>'; ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot class="bg-success">
                    <th class="text-center"><b>Total</b></th>
                    <th></th>
                    <th class="text-center"><button href="view/view_all_sell_item.php?billId=<?php echo $bill_id; ?>&cusid=<?php echo $_GET['customerId'];?><?php if(isset($_POST['dateMonth'])){echo '&dateMonth='.$_POST['dateMonth'].'&dateYear='.$_POST['dateYear'];}?>" class="btn btn-primary bg-slate-700 btn-xs open-popup-link" data-effect="mfp-zoom-in"><?php echo $total_qty;?></a></button></th>
                    <th class="text-right"><?php echo number_format($total_price,2);?> tk.</th>
                    <th class="text-right"><?php echo number_format($total_comm,2);?> tk.</th>
                    <th class="text-right"><?php echo number_format($total_payment,2);?> tk.</th>
                    <!--<th class="text-right"><?php echo number_format($total_due,2);?> tk.</th>-->
                    <th></th>
                    <th></th>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<hr>

<div class="col-md-12 bg-grey-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-8">
        <h4><strong>View Customer Ledger
            <?php
            if (isset($_POST['search'])) {
                echo date("d-M-Y", strtotime($_POST['startDate'])).' To ';
                echo date("d-M-Y", strtotime($_POST['endDate']));
            } else {
                       // echo 'This Months';
            }
            ?>
        </strong></h4>
    </div>
    <div class="col-md-4" style="padding-top:5px;">

    </div>
</div>
<!-- all user show -->

<div id="print_cus_ledger" class="row" style="font-size: 14px;">
    
        <div class="col-md-12">
            <h5></h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="datatable-btn2">
                    <thead class="bg-teal-800">
                        <tr>
                            <th class="col-md-1 text-center">Sl</th>
                            <th class="col-md-2 text-center">Date</th>
                            <th class="col-md-1 text-center">Invoice No</th>
                            <th class="col-md-3 text-center">Description</th>
                            <th class="col-md-2 text-center">Debit</th>
                            <th class="col-md-2 text-center">Credit</th>
                            <th class="col-md-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($balance_of_bd!=0){
                $balance = $balance_of_bd;
                ?>
                <tr>
                    <td class="text-center">
                        ---
                    </td>
                    <td class="text-center">
                     ---
                    </td>
                    <td class="">
                    ---
                    </td>
                    <td class="">
                        <b>Balance Bd</b>
                    </td>
                    <td class="text-right">
                        <?php echo isset($total_debit_bd) && $total_debit_bd>0 ? number_format($total_debit_bd) . ' tk' : 0; ?>

                    </td>
                    <td class="text-right">
                        <?php echo isset($total_credit_bd) && $total_credit_bd>0? number_format($total_credit_bd) . ' tk' : 0; ?>
                    </td>
                    <td class="text-right">
                        <?php echo isset($balance_of_bd) ? number_format($balance_of_bd) . ' tk' : 0; ?>
                    </td>
                </tr>
            <?php }else{
                $balance = 0;
            } ?>
            <?php 
    
    if ($customerPaymentData) { ?>
                        <?php
                        $sl = 0;
                      //  $balance = 0;
                        $total_credit = 0;
                        $total_debit = 0;
                        foreach ($customerPaymentData as $cusPay) {
                            $sl++;
                            $debit = 0;
                            $credit = 0;
                            if ($cusPay['acc_type'] == $expenseType || $cusPay['acc_type'] == $purchasePaymentType
                                || $cusPay['acc_type'] == $supplierPurchaseIndividualPaymentType
                                || $cusPay['acc_type'] == $giveCashToCustomer
                                || $cusPay['acc_type'] == 201
                                || $cusPay['acc_type'] == 202
                                || $cusPay['acc_type'] == $loanGiveToPersonType
                                || $cusPay['acc_type'] == $CompanyRepayHisLoanType
                                || $cusPay['acc_type'] == $CompanyBackSecurityMoneyToCustomerType
                                || $cusPay['acc_type'] == $CompanyGiveSecurityMoneyToSupplierType
                                || $cusPay['acc_type'] == $CompanyGivePaymentEmployeeType
                                || $cusPay['acc_type'] == $supplierPurchaseIndividualPaymentType
                                || $cusPay['acc_type'] == $CustomerDue
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
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $sl; ?>
                                </td>
                                <td class="text-center">
                                    <small><?php echo isset($cusPay['entry_date']) ? date('d-M-Y', strtotime($cusPay['entry_date'])) : NULL; ?></small>
                                </td>
                                <td class="text-center">
                                    <?php echo !empty($cusPay['purchase_or_sell_id']) && $cusPay['acc_type'] != 3 ? str_replace('s_','',$cusPay['purchase_or_sell_id']) : 'N/A' ?>
                                </td>
                                <td class="">
                                    <?php
                                    if ($cusPay['acc_type'] == 2) {echo 'Payment given with Invoice';}
                                    else if ($cusPay['acc_type'] == 3 && $cusPay['payment_method']=1) {echo 'Receipt Amount from Customer';}
                                    else if ($cusPay['acc_type'] == 3 && $cusPay['payment_method']=0) {echo 'Receipt Amount from Customer (Bank)';}
                                    else if ($cusPay['acc_type'] == 5) {echo 'Customer Individual Payment';}
                                    else if ($cusPay['acc_type'] == 6) {echo 'Supplier Individual Payment';}
                                    else if ($cusPay['acc_type'] == 7) {echo 'Opening Advance Amount';}
                                    else if ($cusPay['acc_type'] == 8) {echo 'Opening Due Amount';}
                                    else if ($cusPay['acc_type'] == 23) {echo 'Total Amount of Sell Product';}
                                    else if ($cusPay['acc_type'] == 29) {echo 'Discount For Received Amount.';}

                                    if ($cusPay['acc_type'] != 8
                                        && $cusPay['acc_type'] != 3
                                        && $cusPay['acc_type'] != 7
                                        && $cusPay['acc_type'] != 23
                                        && $cusPay['acc_type'] != 29
                                    ){?>
                                        <small><?php echo isset($cusPay['acc_description']) ? $cusPay['acc_description'] : NULL; ?></small>
                                    <?php } ?>
                                </td>
                                <td class="text-right">
                                    <small><?php echo number_format($debit,2); ?></small>
                                </td>
                                <td class="text-right">
                                    <small><?php echo number_format($credit,2); ?></small>
                                </td>
                                <td>
                                    <?php
                                    if($cusPay['acc_type'] == '5' || $cusPay['acc_type'] == '6'){ ?>
                                        <button type="button" data-amount="<?= $cusPay['acc_amount']?>" data-accid = "<?=$cusPay['acc_id']?>" data-toggle="modal"data-target="#adjustment-modal" class="btn bg-teal btn-success btn-xs">Adjust
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-center">
                                Closing Balance
                            </td>
                            <td class="text-right"><small><?php if ($balance < 0){ echo number_format(abs($balance),2);} ?></small></td>
                            <td class="text-right"><small><?php if ($balance > 0){ echo number_format(abs($balance),2);} ?></small></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <?php
                    $total_debit = $total_debit + $total_debit_bd;
                    $total_credit = $total_credit + $total_credit_bd;
                    ?>
                    <tfoot>
                        <th class="col-md-1 text-center"></th>
                        <th class="col-md-2 text-center"></th>
                        <th class="col-md-1 text-center"></th>
                        <th class="col-md-3 text-center"></th>
                        <th class="col-md-2 text-right"><?php if ($total_credit > $total_debit){echo number_format($total_credit,2);}else{echo number_format($total_debit,2);}?></th>
                        <th class="col-md-2 text-right"><?php if ($total_credit < $total_debit){echo number_format($total_debit,2);}else{echo number_format($total_credit,2);}?></th>
                        <th class="col-md-2 text-center"></th>
                    </tfoot>
                  <?php } else {
                        echo '</tbody>';
                    } ?>    
                </table>
            </div>
        </div>
  

</div>

<!-- Modal -->
<div class="modal fade" id="adjustment-modal">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; text-align: center;">
                <b><?php echo isset($notification) ? $notification : NULL; ?></b>
            </div>

            <div class="col-md-12 bg-slate-800 text-center" style="margin-bottom:5px;">
                <h4>Welcome to Adjustment Page</h4>
            </div>

            <div class="row" style="padding:10px; font-size: 12px;">
                <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                    <div class="col-md-10  col-md-offset-1" style="font-size: 12px;">
                        <input type="hidden" name="acc_id" value="">
                        <div class="col-md-12" style="margin-top:15px;margin-bottom:10px;">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="prev_amount">Previous Amount:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" onkeypress="return numbersOnly(event)" type="text" name="prev_amount" value="" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-top:15px;margin-bottom:10px;">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="amount">Adjustment Amount:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" onkeypress="return numbersOnly(event)" type="text" name="amount" placeholder="Amount" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label col-sm-4" for="adjustment_type" id="adjustment_type">Adjustment Type:
                                </label>
                                <div class="col-sm-8">
                                    <label class="radio-inline">
                                      <input type="radio" name="adjustment_type" id="adjustment_type" value="1" required="required"> <span class="checkmark glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                                  </label>
                                  <label class="radio-inline">
                                    <input type="radio" name="adjustment_type" id="adjustment_type" value="0" required="required"> <span class="checkmark glyphicon glyphicon-minus-sign" aria-hidden="true" onkeypress="return numbersOnly(event)"></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-top:15px;margin-bottom:10px;">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="amount">New Amount:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" onkeypress="return numbersOnly(event)" type="text" name="new_amount" disabled />
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-md-12" style="margin-top:30px;">
                        <div class="col-md-3 col-md-offset-6">
                            <div class="text-center">
                                <button type="submit" class="btn btn-block btn-success" name="adjust">Adjust</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<script>
    $(document).ready(function () {
        $('select').selectpicker();

        $(document).on('click', '.open-popup-link', function () {
            $(this).magnificPopup({
                type: 'iframe',
                iframe: {
                    markup: '<div class="col-md-12">' +
                    '<div class="mfp-iframe-scaler" >' +
                    '<div class="mfp-close"></div>' +
                    '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                    '</div>' +
                    '</div>'
                },
                callbacks: {
                    beforeOpen: function () {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    },
                },
                enableEscapeKey: false,
                midClick: true

            }).magnificPopup('open');

        });
    });
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        window.location = '?q=customer_ledger&customerId=<?php echo $_GET['customerId']?>';
        document.body.innerHTML = originalContents;
    }
</script>

<script>
    $(document).ready(function () {
        $(document).on('click', '.open-popup-link', function () {
            $(this).magnificPopup({
                type: 'iframe',
                iframe: {
                    markup: '<div class="col-md-12">' +
                    '<div class="mfp-iframe-scaler" >' +
                    '<div class="mfp-close"></div>' +
                    '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                    '</div>' +
                    '</div>'
                },
                callbacks: {
                    beforeOpen: function () {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    },
                },
                enableEscapeKey: false,
                midClick: true

            }).magnificPopup('open');

        });
        $('#adjustment-modal').on('show.bs.modal', function(e) {
            var acc_id = $(e.relatedTarget).data('accid');
            var amount = $(e.relatedTarget).data('amount');
            //console.log(amount);
            $(e.currentTarget).find('input[name="acc_id"]').val(acc_id);
            $(e.currentTarget).find('input[name="prev_amount"]').val(amount);
        });

        $('input[name="adjustment_type"]').on('click', function(){
            console.log(this.value);
            var prev_amount     = parseInt($('input[name="prev_amount"]').val());
            var adjust_amount   = parseInt($('input[name="amount"]').val());
            //console.log(adjust_amount);
            if(adjust_amount>0){
                if (this.value == 1) {
                    $('input[name="new_amount"]').val(prev_amount + adjust_amount);
                }
                if (this.value == 0) {
                    $('input[name="new_amount"]').val(prev_amount - adjust_amount);
                }
            }
        });
        $('#myModal').on('hidden.bs.modal', function () {
            //location.reload('true');
        })
    });

    $(document).ready(function() {
        $("#datatable-btn").dataTable().fnDestroy();
        $('#datatable-btn').DataTable( {
            dom: 'Bfrtip',
            buttons: [
            {
                extend: 'print',
                text: 'Print',
                footer: true,
                title: function () {
                    return "<img height='150px' src='./pdf/img/header.png'> Customer Ledger - <?php echo $customerPersonalData['cus_company'];?>"
                },
                exportOptions: {
                    columns: [1,2,3,4,5,6]
                },
                customize: function (win) {
                    $(win.document.body).css('font-size', '12px');
                    $(win.document.body).find('h1').addClass('text-center').css('font-size', '20px');
                    $(win.document.body).find('table').addClass('container').css('font-size', 'inherit');
                    $(win.document.body).find('table').removeClass('table-bordered');
                }
            }
            ]
        } );
    } );

    $(document).ready(function() {
        $("#datatable-btn2").dataTable().fnDestroy();
        $('#datatable-btn2').DataTable( {
            dom: 'Bfrtip',
            'ordering':false,
            'paging':false,
            buttons: [
            {
                extend: 'print',
                text: 'Print',
                footer: true,
                title: function () {
                    return "<img height='150px' src='./pdf/img/header.png'> Customer Transection - <?php echo $customerPersonalData['cus_company'];?> <div class='col-md-4'>Mobile : <?php echo !empty($customerPersonalData['cus_mobile_no']) ? $customerPersonalData['cus_mobile_no'] : 'No Number'; ?></div><div class='col-md-4'>Address : <?php echo $customerPersonalData['cus_address'] ?></div><div class='col-md-4'>Date Period : <?php if (isset($_POST['startDate'])){echo $_POST['startDate'].' to '.$_POST['endDate'];}else { echo date('M');} ?></div>"
                },
                exportOptions: {
                    columns: [1,2,3,4,5,6]
                },
                customize: function (win) {
                    $(win.document.body).css('font-size', '12px');
                    $(win.document.body).find('h1').addClass('text-center').css('font-size', '20px');
                    $(win.document.body).find('table').addClass('container').css('font-size', 'inherit');
                    $(win.document.body).find('table').removeClass('table-bordered');
                }
            }
            ]
        } );
    } );
    function numbersOnly(e){
        var unicode=e.charCode? e.charCode : e.keyCode
        if (unicode!=8 && e.key !='.'){
            if ((unicode<2534||unicode>2543)&&(unicode<48||unicode>57)){
                return false;
            }
        }
    }
</script>

<script>
    $(document).ready(function () {
        $('input[name="startDate"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });

        $('input[name="endDate"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });

        $(document).on('mouseover', 'tbody tr td [data-toggle="tooltip"]', function () {
            $('tbody tr td [data-toggle="tooltip"]').tooltip();
        });

    });
</script>

<script type="application/javascript"
src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="application/javascript"
src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>