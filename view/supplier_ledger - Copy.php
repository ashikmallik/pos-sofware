<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$supplierId = isset($_GET['supplierId']) ? $_GET['supplierId'] : null;

$supplierPersonalData = $obj->details_by_cond("tbl_supplier", "`supplier_id` = '$supplierId'");

$expenseType = 1;
$purchasePaymentType = 2;
$supplierPurchaseIndividualPaymentType = 6;
$giveCashToCustomer = 12;
$loanGiveToPersonType = 13;
$CompanyRepayHisLoanType = 16;
$CompanyBackSecurityMoneyToCustomerType = 18;
$CompanyGiveSecurityMoneyToSupplierType = 19;
$CompanyGivePaymentEmployeeType = 21;
$ReceiveCashFromSupplier = 11;
$CustomerDue = 8;
$SupplierAdvance = 9;
$PurchaseProductBill = 22;
$PurchaseReturn = 27;
$SupplierDiscount= 30;

if (isset($supplierPersonalData) && !empty($supplierPersonalData)) {
    $supplierTotalSecMoneyBackData = $obj->details_selected_field_by_cond('tbl_security_money_transaction', 'SUM(amount) as total_back_amount', 'pay_receive = 0 AND supplier_id = ' . $supplierPersonalData['id']);
    $supplierTotalSecMoneyData = $obj->details_selected_field_by_cond('tbl_security_money_transaction', 'SUM(amount) as total_amount', 'pay_receive = 1 AND supplier_id = ' . $supplierPersonalData['id']);
    $supplierTotalSecMoneyBack = isset($supplierTotalSecMoneyBackData['total_back_amount']) ? $supplierTotalSecMoneyBackData['total_back_amount'] : 0;
    $total_security_money = $supplierTotalSecMoneyBack;
}

$supplierName = $supplierPersonalData['supplier_company'];

$supplierOrCustomerTransaction = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$supplierId'");
$supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved", "supplier_customer='$supplierId'");

$supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$supplierId'");

$supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$supplierId'");

$discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$supplierId'");

isset($discountData) ? $discount = $discountData['amount'] : $discount = 0;
isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0;
isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0;

$total_due_a = ($supplierOrCustomerTransaction['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount - $openingDueBalance + $openingAdvance);

if (isset($_POST['adjust_amount'])) {

    if (!empty($_POST['payment']) && isset($_POST['payment'])) {
        //$purchaseData = $obj->details_by_cond('tbl_sell', "sell_id = ".$_POST['sellId']);

//        $totalPrice = $purchaseData['total_price'];
//        $previousPayment = $purchaseData['payment_recieved'];
//        $newPayment = $_POST['payment'] + $previousPayment;
//        $newDue = $totalPrice - $newPayment;
//
//        $form_purchase_update = array(
//            'payment_recieved' => $newPayment,
//            'due_to_company' => $newDue,
//            'update_by' => $userid
//        );
        //$obj->Update_data("tbl_sell", $form_purchase_update, "sell_id=".$_POST['sellId']);
        $acc_data = $obj->details_by_cond('tbl_account', "acc_id = ".$_POST['acc_id']);
        if ($_POST['action']==1){$adjust_amount= $acc_data['acc_amount'] + $_POST['payment'];}
        else{$adjust_amount= $acc_data['acc_amount'] - $_POST['payment'];}

        $form_tbl_accounts = array(
            'acc_amount' => $adjust_amount,
        );

        $obj->Update_data("tbl_account", $form_tbl_accounts, "acc_id=" . $_POST['acc_id']);
        //$tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    } ?>
    <script>
        window.location = 'supplier_ledger&supplierId='<?php echo $_GET['supplierId']?>;
    </script>
<?php }

if (isset($_POST['search'])) {
    extract($_POST);
    $startDate = date('Y-m-d', strtotime($startDate));
    $endDate = date('Y-m-d', strtotime($endDate));
    $allSupplierData = $obj->view_all_by_cond("vw_purchase", "`supplier` = '$supplierId' AND entry_date BETWEEN '$startDate' and '$endDate' ORDER BY bill_id DESC");
    $supplierPaymentData = $obj->view_all_by_cond("tbl_account", "`cus_or_sup_id` = '$supplierId'  AND entry_date BETWEEN '$startDate' and '$endDate' order by entry_date");
    $purchse_return = $obj->get_sum_data('tbl_return','total_return_price',"type=1 AND cus_or_sup_id='$supplierId' AND date BETWEEN '$startDate' and '$endDate'");
} else {
    $date = date('Y-m-d');
    $allSupplierData = $obj->view_all_by_cond("vw_purchase", "`supplier` = '$supplierId' AND MONTH(entry_date)=MONTH('$date') and YEAR (entry_date)= YEAR('$date') ORDER BY bill_id DESC");
    $supplierPaymentData = $obj->view_all_by_cond("tbl_account", "`cus_or_sup_id` = '$supplierId' AND MONTH(entry_date)=MONTH('$date') and YEAR
        (entry_date)= YEAR('$date') order by entry_date");
    $purchse_return = $obj->get_sum_data('tbl_return','total_return_price',"type=1 AND cus_or_sup_id='$supplierId' AND MONTH(date)=MONTH('$date') and YEAR(date)= YEAR('$date')");
} 

/*echo "<pre>";
    print_r($supplierPaymentData);
    echo "</pre>";*/
    ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet"/>

    <div class="col-md-12 bg-teal-800" style="margin-top:20px;">
        <div class="col-md-8">
            <h4><strong>View Supplier Ledger name <?php echo $supplierName ?>
            <?php
            if (isset($_POST['search'])) {
                echo date("d-M-Y", strtotime($_POST['startDate'])).' To ';
                echo date("d-M-Y", strtotime($_POST['endDate']));
            } else {
                    //echo 'This Months';
            } ?>
        </strong></h4>
    </div>
    <div class="col-md-4" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>

        <?php } ?>
    </div>
</div>
<div class="col-md-12 bg-teal" style="margin-bottom: 15px;padding:5px 0;">
    <div class="col-md-4"><p>Mobile : <?php echo $supplierPersonalData['supplier_mobile_no'] ?></p></div>
    <div class="col-md-4"><p>Address : <?php echo $supplierPersonalData['supplier_address'] ?></p></div>
    <div class="col-md-4"><p>Email : <?php echo $supplierPersonalData['supplier_email'] ?></p></div>
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
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable-btn">
                <thead class="bg-grey-800">
                    <tr>
                        <th class="text-center col-md-1">Print</th>
                        <th class="text-center col-md-1">Invoice No</th>
                        <th class="text-center col-md-1">Total Qty (item)</th>
                        <th class="text-center col-md-2">Total Price</th>
                        <th class="text-center col-md-1">Payment</th>
                        <th class="text-center col-md-1">Dues</th>
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
                    foreach ($allSupplierData as $supplier) {
                        $i++;
                        $total_qty = $supplier['total_qty'] + $total_qty;
                        $total_price = $supplier['total_price'] + $total_price;
                        $total_payment = $supplier['payment_recieved'] + $total_payment;
                        $total_due += $supplier['due_to_company'];
                        $bill_id = $supplier['bill_id'];

                        if ($bill_id < 10) {$STD = "0000";}
                        else if ($bill_id < 100) {$STD = "000";}
                        else if ($bill_id < 1000) {$STD = "00";}
                        else if ($bill_id < 10000) {$STD = "0";}
                        else {$STD = "";}
                        ?>
                        <tr>
                            <td class="text-center ">
                                <a type="button" target="_blank" href="pdf/bill.php?billId=<?php echo $bill_id ?>" class="btn bg-grey-800 btn-primary btn-xs"><span class="glyphicon glyphicon-print"></span>
                                </a>
                            </td>
                            <td class="text-center"><?php echo $STD.$bill_id;?></td>
                            <td class="text-center"><button href="view/view_purchase_item.php?billId=<?php echo $bill_id; ?>" class="btn btn-primary bg-slate-700 btn-xs open-popup-link" data-effect="mfp-zoom-in"><?php echo $supplier['total_qty'];?> pcs </a></button></td>
                            <td class="text-right"><?php echo isset($supplier['total_price']) ? number_format($supplier['total_price'], 2) . ' tk.' : NULL; ?></td>
                            <td class="text-right"><?php echo isset($supplier['payment_recieved']) ? number_format($supplier['payment_recieved'],2) . ' TK.' : NULL; ?></td>
                            <td class="text-right"><?php echo isset($supplier['due_to_company']) ? number_format($supplier['due_to_company'],2) . ' TK.' : NULL; ?></td>
                            <td class="text-center"><?php echo isset($supplier['entry_date']) ? date('d-M-y', strtotime($supplier['entry_date'])) : NULL; ?></td>

                            <td class="text-center">
                                <div class="btn-group" style="margin-top:5px">
                                    <?php echo '<a type="button" href="?q=edit_purchase&billId=' . $supplier['bill_id'] . '" class="btn btn-primary btn-xs">Edit</a>'; ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <th class="text-center">Total</th>
                    <th></th>
                    <th class="text-center"><button href="view/view_all_purchase_item.php?supid=<?php echo $_GET['supplierId'];?><?php if (isset($_POST['search'])){echo "&startDate=".$_POST['startDate']."&endDate=".$_POST['endDate'];}?>" class="btn btn-primary bg-slate-700 btn-xs open-popup-link" data-effect="mfp-zoom-in"><?php echo $total_qty;?> pcs </a></button></th>
                    <th class="text-right"><?php echo number_format($total_price,2);?> tk.</th>
                    <th class="text-right"><?php echo number_format($total_payment,2);?> tk.</th>
                    <th class="text-right"><?php echo number_format($total_due,2);?> tk.</th>
                    <th></th>
                    <th></th>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<hr>

<div class="col-md-12 bg-teal-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View Supplier Ledger
            <?php
            if (isset($_POST['search'])) {
                echo date("d-M-Y", strtotime($_POST['startDate'])).' To ';
                echo date("d-M-Y", strtotime($_POST['endDate']));
            } else {
                    //echo 'This Months';
            } ?>
        </strong></h4>
    </div>
    <div class="col-md-4" style="padding-top:5px;">

    </div>
</div>
<!-- all user show -->
<div id="print_supplier_ledger" class="row" style="font-size: 14px;">
    <?php if ($supplierPaymentData) { ?>

        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="datatable-btn2">
                    <thead class="bg-grey-800">
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
                        <?php
                        $sl = 0;
                        $balance = 0;
                        $total_credit = 0;
                        $total_debit = 0;
                        foreach ($supplierPaymentData as $supPay) {
                            $accType = $supPay['acc_type'];
                            $adjustmentData     = $obj->details_by_cond('tbl_adjustment', "acc_type = $accType OR acc_type = $expenseType AND person_id='$supplierId'");

                            $adjuistmentAmount  = !empty($adjustmentData['amount'])?$adjustmentData['amount']:0;
                            $adjuistmentStatus  = !empty($adjustmentData['status'])?$adjustmentData['status']:NULL;


                            $sl++;
                            $debit = 0;
                            $credit = 0;
                            if ($supPay['acc_type'] == $expenseType || $supPay['acc_type'] == $purchasePaymentType
                                || $supPay['acc_type'] == $supplierPurchaseIndividualPaymentType
                                || $supPay['acc_type'] == $giveCashToCustomer
                                || $supPay['acc_type'] == $loanGiveToPersonType
                                || $supPay['acc_type'] == $CompanyRepayHisLoanType
                                || $supPay['acc_type'] == $CompanyBackSecurityMoneyToCustomerType
                                || $supPay['acc_type'] == $CompanyGiveSecurityMoneyToSupplierType
                                || $supPay['acc_type'] == $CompanyGivePaymentEmployeeType
                                || $supPay['acc_type'] == $supplierPurchaseIndividualPaymentType
                                || $supPay['acc_type'] == $CustomerDue
                                || $supPay['acc_type'] == $SupplierAdvance
                                || $supPay['acc_type'] == $PurchaseReturn
                                || $supPay['acc_type'] == $SupplierDiscount
                            ) {$debit = $supPay['acc_amount'];

                                if($debit>0){
                                    if($adjuistmentStatus == 1){
                                        $debit += $adjuistmentAmount;
                                    }else {
                                        $debit -= $adjuistmentAmount;
                                    }
                                }
                                $balance += $debit;
                                $total_debit += $debit;

                            } else {
                                $credit = $supPay['acc_amount'];
                                if($credit>0){
                                    if($adjuistmentStatus == 1){
                                        $credit += $adjuistmentAmount;
                                    }else {
                                        $credit -= $adjuistmentAmount;
                                    }
                                }
                                $balance -= $credit;
                                $total_credit += $credit;
                            }      
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $sl; ?>
                                </td>
                                <td class="text-center">
                                    <small><?php echo isset($supPay['entry_date']) ? date('d-M-Y', strtotime($supPay['entry_date'])) : NULL; ?></small>
                                </td>
                                <td class="text-center">
                                    <?php echo !empty($supPay['purchase_or_sell_id']) && $supPay['acc_type'] != 2 ? str_replace('p_', '', $supPay['purchase_or_sell_id']) : 'N/A' ?>
                                </td>
                                <td class="">
                                    <?php
                                    if ($supPay['acc_type'] == 2) {echo 'Payment given with Purchase / Invoice';}
                                    else if ($supPay['acc_type'] == 3) {echo 'Payment Received with Sell /Bill';}
                                    else if ($supPay['acc_type'] == 5) {echo 'Customer Individual Payment';}
                                    else if ($supPay['acc_type'] == 6) {echo 'Supplier Individual Payment';}
                                    else if ($supPay['acc_type'] == 9) {echo 'Opening Advance Amount';}
                                    else if ($supPay['acc_type'] == 10) {echo 'Opening Due Amount';}
                                    else if ($supPay['acc_type'] == 22) {echo 'Total Amount of Purchase Product';}
                                    else if ($supPay['acc_type'] == 30) {echo 'Discount For Paid Amount.';}

                                    if ($supPay['acc_type'] != 9
                                        && $supPay['acc_type'] != 10
                                        && $supPay['acc_type'] != 22
                                        && $supPay['acc_type'] != 2
                                        && $supPay['acc_type'] != 30
                                    )

                                    {

                                        if($debit>0){
                                            if($adjuistmentStatus == 1){
                                                $debit += $adjuistmentAmount;
                                            }else {
                                                $debit -= $adjuistmentAmount;
                                            }
                                        }

                                        if($credit>0){
                                            if($adjuistmentStatus == 1){
                                                $credit += $adjuistmentAmount;
                                            }else {
                                                $credit -= $adjuistmentAmount;
                                            }
                                        }
                                        ?>
                                        <small><?php echo isset($supPay['acc_description']) ? $supPay['acc_description'] : NULL; ?>
                                    <?php } ?>
                                </td>
                                <td class="text-right">
                                    <small><?php echo number_format($debit,2) ?></small>
                                </td>
                                <td class="text-right">
                                    <small><?php echo number_format($credit,2) ?></small>
                                </td>
                                <td>
                                    <?php
                                    if(empty($supPay['purchase_or_sell_id'])){ ?>
                                        <a type="button" data-name = "<?php echo $supplierName; ?>" data-supplier = "<?php   ?>" data-accid = "<?php echo $supPay['acc_id'] ?>" data-toggle="modal" data-target="#addPriceModel" class="btn bg-teal btn-success btn-xs">Adjust</a>
                                    <?php } else{ echo '<a href="?q=adjustment&person_id='.$supplierId.'&acc_type='.$supPay['acc_type'].'" type="button" class="btn bg-teal btn-success btn-xs"> Adjust</a>';} ?>
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
                            <td class="text-right">
                                <small><?php if ($balance < 0){ echo number_format(abs($balance),2);} ?></small>
                            </td>
                            <td class="text-right">
                                <small><?php if ($balance > 0){ echo number_format(abs($balance),2);} ?></small>
                            </td>
                            <td>

                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <th class="col-md-1 text-center"></th>
                        <th class="col-md-2 text-center"></th>
                        <th class="col-md-1 text-center"></th>
                        <th class="col-md-3 text-center"></th>
                        <th class="col-md-2 text-right"><?php if ($total_credit > $total_debit){echo number_format($total_credit,2);}else{echo number_format($total_debit,2);}?></th>
                        <th class="col-md-2 text-right"><?php if ($total_credit < $total_debit){echo number_format($total_debit,2);}else{echo number_format($total_credit,2);}?></th>
                        <th class="col-md-2 text-center"></th>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php } else {
        echo '<div class="row"><div class="text-center"><h4>Sorry ! No Available Data</h4></div></div>';
    } ?>

    <!--<div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td class="text-center bg-info" colspan="5"><strong>Total Transaction</strong></td>
                    <td class="text-center bg-info">
                        <strong><?php echo number_format($supplierOrCustomerTransaction['total_price']) ?> tk</strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center bg-success" colspan="5"><strong>Opening Due</strong></td>
                    <td class="text-center bg-success"><strong><?php echo number_format($openingDueBalance) ?>
                            tk</strong></td>
                </tr>
                <tr>
                    <td class="text-center bg-info" colspan="5"><strong>Opening Advance</strong></td>
                    <td class="text-center bg-info"><strong><?php echo number_format($openingAdvance) ?> tk</strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center bg-success" colspan="5"><strong>Security Money</strong></td>
                    <td class="text-center bg-success"><strong><?php echo isset($total_security_money) ? number_format($total_security_money - $supplierTotalSecMoneyData['total_amount']):'0' ?> tk</strong> </td>
                </tr>
                <tr>
                    <td class="text-center bg-success" colspan="5"><strong>Total Pay Amount</strong></td>
                    <td class="text-center bg-success">
                        <strong><?php
                            if($supplierOrCustomerRecieved['total_recieved']>$purchse_return){
                                $total_received = $supplierOrCustomerRecieved['total_recieved']-$purchse_return;
                            }else{
                                $total_received = $supplierOrCustomerRecieved['total_recieved'];
                            }
                            echo number_format($total_received) ?> tk</strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center bg-success" colspan="5"><strong>Total Received Amount</strong></td>
                    <td class="text-center bg-success">
                        <strong><?php echo number_format($obj->get_sum_data('tbl_account','acc_amount', "acc_type = 11 AND cus_or_sup_id='$supplierId'"))?> tk</strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center bg-info" colspan="5"><strong>Total Discount</strong></td>
                    <td class="text-center bg-info"><strong><?php echo number_format($discount) ?> tk</strong></td>
                </tr>
                <tr>
                    <td class="text-center bg-success" colspan="5">
                        <strong>Total <?php echo ($total_due_a > 0) ? 'Advance' : 'Due'; ?></strong></td>
                    <td class="text-center bg-success"><strong><?php echo number_format(abs($total_due_a-$purchse_return)) ?> tk</strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>-->
</div>

<!-- Modal -->
<div id="addPriceModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Adjust Payments for this Purchase <b><span id="supplierNameModal" class="text-grey-800"> </span></b></h4>
            </div>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group" style="margin-top:20px;">
                            <label class="control-label col-sm-4" for="comments">Comments </label>
                            <div class="col-sm-6">
                                <select name="action">
                                    <option value="1">Add</option>
                                    <option value="0">Less</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" style="margin-bottom:20px;">
                            <label class="control-label col-sm-4" for="payment">Add Payments </label>
                            <div class="col-sm-6">
                                <input type="text" required onkeypress="return numbersOnly(event)" name="payment" placeholder="Insert payments" class="form-control">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="acc_id" value="">
                    <input type="hidden" name="purchaseId" value="">
                </div>
                <div class="modal-footer text-center">
                    <div class="row">
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" name="adjust_amount">Add Payment</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
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


        $('#datatable-payment').on('click', '[data-target="#addPriceModel"]', function () {
            var acc_id = $(this).data('accid');
            var supplierName = $(this).data('name');
            var purchaseId = $(this).data('purchase_id');

            $('div#addPriceModel input[name="acc_id"]').val(acc_id);
            $('div#addPriceModel input[name="purchaseId"]').val(purchaseId);
            $('div#addPriceModel span#supplierNameModal').html(supplierName);

        });
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
                    return "Customer Transection - <?php echo $supplierPersonalData['supplier_name'];?>"
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

    $(document).ready(function() {
        $("#datatable-btn2").dataTable().fnDestroy();
        $('#datatable-btn2').DataTable( {
            dom: 'Bfrtip',
            "paging": false,
            "ordering": false,
            buttons: [
            {
                extend: 'print',
                text: 'Print',
                footer: true,
                title: function () {
                    return "Supplier Ledger - <?php echo $supplierPersonalData['supplier_company'];?> <div class='col-md-4'>Mobile : <?php echo !empty($supplierPersonalData['supplier_mobile_no']) ? $supplierPersonalData['supplier_mobile_no'] : 'No Number'; ?></div><div class='col-md-4'>Address : <?php echo $supplierPersonalData['supplier_address'] ?></div><div class='col-md-4'>Date Period : <?php if (isset($_POST['startDate'])){echo $_POST['startDate'].' to '.$_POST['endDate'];}else { echo date('M');} ?></div>"
                },
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
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
</script>

<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>
