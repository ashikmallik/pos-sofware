<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

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

$customerId = isset($_GET['customerId']) ? $_GET['customerId'] : null;

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
        if ($_POST['action']==1){
            $adjust_amount= $acc_data['acc_amount'] + $_POST['payment'];
        }else{
            $adjust_amount= $acc_data['acc_amount'] - $_POST['payment'];
        }

        $form_tbl_accounts = array(

            'acc_amount' => $adjust_amount,
        );

        $obj->Update_data("tbl_account", $form_tbl_accounts, "acc_id=" . $_POST['acc_id']);
        //$tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    }

    ?>
    <script>
        window.location = 'customer_ledger&customerId='<?php echo $_GET['customerId']?>;
    </script>
<?php
}
if (isset($_GET['dateMonth'])) {

    if (empty($_GET['dateMonth'])){
        $allCustomerData = $obj->view_all_by_cond("vw_sell", "`customer` = '$customerId' AND YEAR (entry_date)='$dateYear' order by entry_date");
        $customerPaymentData = $obj->view_all_by_cond("tbl_account", "`cus_or_sup_id` = '$customerId'  AND YEAR (entry_date)='$dateYear' order by entry_date");
    }else{
        $allCustomerData = $obj->view_all_by_cond("vw_sell", "`customer` = '$customerId' AND MONTH(entry_date)='$dateMonth' and YEAR
    (entry_date)='$dateYear' order by entry_date");
        $customerPaymentData = $obj->view_all_by_cond("tbl_account", "`cus_or_sup_id` = '$customerId'  AND MONTH(entry_date)='$dateMonth' and YEAR
    (entry_date)='$dateYear' order by entry_date");
    }

} else {
    $date = date('Y-m-d');
    $allCustomerData = $obj->view_all_by_cond("vw_sell", "`customer` = '$customerId' AND MONTH(entry_date)=MONTH('$date') and YEAR
    (entry_date)= YEAR('$date') order by entry_date");
    $customerPaymentData = $obj->view_all_by_cond("tbl_account", "`cus_or_sup_id` = '$customerId' AND MONTH(entry_date)=MONTH('$date') and YEAR
    (entry_date)= YEAR('$date') order by entry_date");
}

  ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet"/>

<hr>

<div class="col-md-12 bg-grey-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-8">
        <h4><strong>View Payment Ledger of
                <?php
                if (isset($_POST['search'])) {
                    echo date("M, Y", strtotime("1." . $_POST['dateMonth'] . "." . $_POST['dateYear'] . ""));
                } else { echo 'This Months'; } ?>
            </strong></h4>
    </div>
    <div class="col-md-4" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>
            <button type="submit" class="btn btn-primary btn-sm pull-right" onclick="printDiv('print_cus_ledger')">Print Ladger</button>
        <?php } ?>
    </div>
</div>
<!-- all user show -->

<div id="print_cus_ledger" class="row" style="font-size: 14px;">
    <?php if ($customerPaymentData) { ?>
        <h5><strong>Customer name <?php echo $customerPersonalData['cus_company'] ?>
                <?php
                                    if (isset($_POST['search'])) {
                                        echo date("M, Y", strtotime("1." . $_POST['dateMonth'] . "." . $_POST['dateYear'] . ""));
                                    } else {
                                        echo 'This Months';
                                    }
                ?>
            </strong></h5>
        <div class="col-md-4"><p>Mobile
                : <?php echo !empty($customerPersonalData['cus_mobile_no']) ? $customerPersonalData['cus_mobile_no'] : 'No Number'; ?></p>
        </div>
        <div class="col-md-4"><p>Address : <?php echo $customerPersonalData['cus_address'] ?></p></div>
        <div class="col-md-4"><p>Email : <?php echo $customerPersonalData['cus_email'] ?></p></div>
        <div class="col-md-12">

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="datatable-payment">
                    <thead class="bg-teal-800">
                    <tr>
                        <th class="col-md-1 text-center">Sl</th>
                        <th class="col-md-2 text-center">Date</th>
                        <th class="col-md-1 text-center">Bill Id</th>
                        <th class="col-md-3 text-center">Type</th>
                        <th class="col-md-2 text-center">Debit</th>
                        <th class="col-md-2 text-center">Credit</th>
                        <th class="col-md-2 text-center">Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sl = 0;
                    $balance = 0;
                    $total_credit = 0;
                    $total_debit = 0;
                    foreach ($customerPaymentData as $cusPay) {
                        $sl++;
                        $debit = 0;
                        $credit = 0;
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
                                <?php echo !empty($cusPay['purchase_or_sell_id']) ? $cusPay['purchase_or_sell_id'] : 'N/A' ?>
                            </td>
                            <td class="text-center">
                                <?php
                                if ($cusPay['acc_type'] == 2) {echo 'Payment given with Invoice';}
                                else if ($cusPay['acc_type'] == 3) {echo 'Payment Received with Bill';}
                                else if ($cusPay['acc_type'] == 5) {echo 'Customer Individual Payment';}
                                else if ($cusPay['acc_type'] == 6) {echo 'Supplier Individual Payment';}
                                else if ($cusPay['acc_type'] == 7) {echo 'Customer Opening Advancefdg';}
                                else if ($cusPay['acc_type'] == 8) {echo 'Customer Opening Due';}
                                else {echo 'N/A';}
                                ?>
                                <small><?php echo isset($cusPay['acc_description']) ? $cusPay['acc_description'] : NULL; ?></small>

                            </td>
                            <td class="text-center">
                                <small><?php echo $debit; ?></small>
                            </td>
                            <td class="text-center">
                                <small><?php echo $credit; ?></small>
                            </td>
                            <td><?php echo $balance?></td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    <?php } else {
        echo '<div class="row"><div class="text-center"><h4>Sorry ! No Available Data</h4></div></div>';
    } ?>
    <div class="col-md-12">
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
                    <td class="text-center bg-success"><strong><?php echo number_format($openingDueBalance) ?> tk</strong></td>
                </tr>
                <tr>
                    <td class="text-center bg-info" colspan="5"><strong>Opening Advance</strong></td>
                    <td class="text-center bg-info"><strong><?php echo number_format($openingAdvance) ?> tk</strong> </td>
                </tr>
                <tr>
                    <td class="text-center bg-success" colspan="5"><strong>Security Money</strong></td>
                    <td class="text-center bg-success"><strong><?php echo isset($total_security_money) ? number_format($total_security_money):'0' ?> tk</strong> </td>
                </tr>
                <tr>
                    <td class="text-center bg-info" colspan="5"><strong>Total Received Payment</strong></td>
                    <td class="text-center bg-info">
                        <strong><?php echo number_format($supplierOrCustomerRecieved['total_recieved']) ?> tk</strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center bg-success" colspan="5"><strong>Total Given Payment</strong></td>
                    <td class="text-center bg-success">
                        <strong><?php echo number_format($obj->get_sum_data('tbl_account','acc_amount', "acc_type = 12"))?> tk</strong>
                    </td>
                </tr>

                <tr>
                    <td class="text-center bg-info" colspan="5"><strong>Total Discount</strong></td>
                    <td class="text-center bg-info"><strong><?php echo number_format($discount) ?> tk</strong></td>
                </tr>
                <tr>
                    <td class="text-center bg-success" colspan="5">
                        <strong>Total <?php echo ($total_due < 0) ? 'Advance' : 'Due'; ?></strong></td>
                    <td class="text-center bg-success"><strong><?php echo number_format(abs($total_due)) ?> tk</strong>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="addPriceModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Adjust Payments for this Sell <b><span id="supplierNameModal" class="text-grey-800"> </span></b></h4>
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
        //document.body.innerHTML = originalContents;
    }
    printDiv('print_cus_ledger')
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
                        return "Customer Transection - <?php echo $customerPersonalData['cus_name'];?>"
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
