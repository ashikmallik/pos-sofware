<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customerId = isset($_GET['customerId']) ? $_GET['customerId'] : null;

$customerPersonalData = $obj->details_by_cond("tbl_customer", "`cus_id` = '$customerId'");

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
if (isset($_POST['search'])) {
    extract($_POST);
    if (empty($_POST['dateMonth'])){
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


<div class="col-md-12 bg-grey-800" style="margin-top:20px;">
    <div class="col-md-8">
        <h4><strong>View Ledger as Customer name <?php echo $customerName ?> of
                <?php
                if (isset($_POST['search'])) {
                    echo date("M, Y", strtotime("1." . $_POST['dateMonth'] . "." . $_POST['dateYear'] . ""));
                } else {
                    echo date("M, Y");
                }
                ?>
            </strong></h4>
    </div>
    <div class="col-md-4" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>
            <button type="submit" class="btn btn-primary btn-sm pull-right"
                    onclick="printDiv('div_all')">Print Ladger
            </button>
        <?php } ?>
    </div>
</div>
<div class="col-md-12 bg-teal" style="margin-bottom: 15px;padding:5px 0;">
    <div class="col-md-6"><p>Mobile
            : <?php echo !empty($customerPersonalData['cus_mobile_no']) ? $customerPersonalData['cus_mobile_no'] : 'No Number'; ?></p>
    </div>
    <div class="col-md-6"><p>Address : <?php echo $customerPersonalData['cus_address'] ?></p></div>
</div>

<div class="col-md-12 bg-teal-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-4" style="padding-top:5px">Select Month</label>
                <div class="col-sm-8">
                    <select class="form-control" name="dateMonth" >
                        <option></option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-4" style="padding-top:5px" for="damageRate">Select Year</label>
                <div class="col-sm-8">
                    <select class="form-control" required="required" name="dateYear"
                            id="status">
                        <option></option>
                        <option <?php echo (date('Y') == '2017') ? 'selected' : ''; ?> value="2017">2017</option>
                        <option <?php echo (date('Y') == '2018') ? 'selected' : ''; ?> value="2018">2018</option>
                        <option <?php echo (date('Y') == '2019') ? 'selected' : ''; ?> value="2019">2019</option>
                        <option <?php echo (date('Y') == '2020') ? 'selected' : ''; ?> value="2020">2020</option>
                        <option <?php echo (date('Y') == '2021') ? 'selected' : ''; ?> value="2021">2021</option>
                        <option <?php echo (date('Y') == '2022') ? 'selected' : ''; ?> value="2022">2022</option>
                        <option <?php echo (date('Y') == '2023') ? 'selected' : ''; ?> value="2023">2023</option>
                        <option <?php echo (date('Y') == '2024') ? 'selected' : ''; ?> value="2024">2024</option>
                        <option <?php echo (date('Y') == '2025') ? 'selected' : ''; ?> value="2025">2025</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-primary"><span
                        class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>

<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-slate-800">
                <tr>
                    <th class="text-center col-md-1">Print</th>
                    <th class="text-center col-md-1">Sell Id</th>
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
                foreach ($allCustomerData as $customer) {
                    $i++;
                    $bill_id = $customer['sell_id'];
                    ?>
                    <tr>
                        <td class="text-center ">
                            <a type="button" target="_blank" href="pdf/invoice.php?invoiceId=<?php echo $bill_id ?>"
                               class="btn bg-grey-800 btn-primary btn-xs"><span
                                        class="glyphicon glyphicon-print"></span>
                            </a>
                        </td>
                        <td class="text-center"><?php echo $bill_id; ?></td>
                        <td class="text-center">
                            <button href="view/view_sell_item.php?invoiceId=<?php echo $bill_id; ?>"
                                    class="btn btn-primary bg-slate-700 btn-xs open-popup-link"
                                    data-effect="mfp-zoom-in"><?php echo $customer['total_qty']; ?> pcs </a></button>
                        </td>
                        <td class="text-center"><?php echo isset($customer['total_price']) ? number_format($customer['total_price']) . ' tk.' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($customer['payment_recieved']) ? number_format($customer['payment_recieved']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($customer['due_to_company']) ? number_format($customer['due_to_company']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($customer['entry_date']) ? date('d-M-y', strtotime($customer['entry_date'])) : NULL; ?></td>

                        <td class="text-center">
                            <div class="btn-group" style="margin-top:5px">
                                <?php echo '<a type="button" href="?q=edit_sell&invoiceId=' . $customer['sell_id'] . '" class="btn btn-primary btn-xs">Edit</a>'; ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<hr>

<div class="col-md-12 bg-grey-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-8">
        <h4><strong>View Payment Ledger of
                <?php
                if (isset($_POST['search'])) {
                    echo date("M, Y", strtotime("1." . $_POST['dateMonth'] . "." . $_POST['dateYear'] . ""));
                } else {
                    echo 'This Months';
                }
                ?>
            </strong></h4>
    </div>
    <div class="col-md-4" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>
            <button type="submit" class="btn btn-primary btn-sm pull-right"
                    onclick="printDiv('print_cus_ledger')">Print Ladger
            </button>
        <?php } ?>
    </div>
</div>
<!-- all user show -->

<div id="print_cus_ledger" class="row" style="font-size: 14px;">
    <?php if ($customerPaymentData) { ?>
        <div class="col-md-12">
            <h5><strong>Customer name <?php echo $customerName ?> of
                    <?php
                    if (isset($_POST['search'])) {
                        echo date("M, Y", strtotime("1." . $_POST['dateMonth'] . "." . $_POST['dateYear'] . ""));
                    } else {
                        echo 'This Months';
                    }
                    ?>
                </strong></h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="datatable-payment">
                    <thead class="bg-teal-800">
                    <tr>
                        <th class="col-md-1 text-center">Sl</th>
                        <th class="col-md-2 text-center">Date</th>
                        <th class="col-md-1 text-center">Bill Id</th>
                        <th class="col-md-2 text-center">Type</th>
                        <th class="col-md-4 text-center">Description</th>
                        <th class="col-md-2 text-center">Amount(tk)</th>
                        <th class="col-md-2 text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sl = 0;
                    foreach ($customerPaymentData as $cusPay) {
                        $sl++;
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
                                if ($cusPay['acc_type'] == 2) {
                                    echo 'Payment given with Invoice';
                                } else if ($cusPay['acc_type'] == 3) {
                                    echo 'Payment Received with Bill';
                                } else if ($cusPay['acc_type'] == 5) {
                                    echo 'Customer Individual Payment';
                                } else if ($cusPay['acc_type'] == 6) {
                                    echo 'Supplier Individual Payment';
                                } else if ($cusPay['acc_type'] == 7) {
                                    echo 'Customer Opening Advance';
                                } else if ($cusPay['acc_type'] == 8) {
                                    echo 'Customer Opening Due';
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <small><?php echo isset($cusPay['acc_description']) ? $cusPay['acc_description'] : NULL; ?></small>
                            </td>
                            <td class="text-center">
                                <small><?php echo isset($cusPay['acc_amount']) ? number_format($cusPay['acc_amount']) . ' tk' : NULL;
                                    ?></small>
                            </td>
                            <td><?php
                                if(empty($cusPay['purchase_or_sell_id'])){ ?>
                                    <a type="button"
                                       data-name = "<?php echo $customerName; ?>"
                                       data-supplier = "<?php   ?>"
                                       data-accid = "<?php echo $cusPay['acc_id'] ?>"
                                       data-toggle="modal" data-target="#addPriceModel"
                                       class="btn bg-teal btn-success btn-xs">
                                        <span class="glyphicon glyphicon-usd"></span> Adjust
                                    </a>
                                <?php } else{ echo '<a href="?q=edit_sell&invoiceId='.trim($cusPay['purchase_or_sell_id'],'s_').'" type="button" class="btn bg-teal btn-success btn-xs"><span class="glyphicon glyphicon-usd"></span> Adjust</a>';} ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

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
                    <td class="text-center bg-success"><strong><?php echo number_format($openingDueBalance) ?>
                            tk</strong></td>
                </tr>
                <tr>
                    <td class="text-center bg-info" colspan="5"><strong>Opening Advance</strong></td>
                    <td class="text-center bg-info"><strong><?php echo number_format($openingAdvance) ?> tk</strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center bg-success" colspan="5"><strong>Total Received Payment</strong></td>
                    <td class="text-center bg-success">
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
</script>