<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$payment_cat = 8; // for accounts table cat

$allinstallment = $obj->view_all_ordered_by("installments", "`installments`.`id` DESC");

if (isset($_GET['deleteId']) && !empty($_GET['deleteId'])) {

    $deleteId = $_GET['deleteId'];
    $obj->Delete_data('installments', "id = '$deleteId'");

    ?>
    <script>
        window.location = '?q=all_installment';
    </script>
    <?php
}

if (isset($_POST['addPayment'])) {

    if(!empty($_POST['payment']) && isset($_POST['payment'])){
        $installmentData = $obj->details_by_cond('installments', "id = ".$_POST['installment_id']);
        $installmentTreData = $obj->details_by_cond('installment_transaction', "installment_payment=0 AND installment_id = ".$_POST['installment_id']);
        $installmentSellData = $obj->details_by_cond('tbl_sell', "sell_id = ".$installmentData['sell_id']);
        //$new_due = $installmentSellData['due_to_company']-$_POST['payment'];
        $installment_due = $installmentData['installment_due']-$_POST['payment'];


        $form_installment_update = array(
            'installment_due' => $installment_due,
        );
        $obj->Update_data("installments", $form_installment_update, "id=".$_POST['installment_id']);

        $form_installment_tre_update = array(
            'installment_payment' => $_POST['payment'],
        );
        //$obj->Update_data("installment_transaction", $form_installment_tre_update, "id=".$installmentTreData['id']);

        $form_installment_due = array(
            'due_to_company' => $installment_due,
            'payment_recieved' => $installmentSellData['total_price'] - $installment_due
        );

        $obj->Update_data("tbl_sell", $form_installment_due, "sell_id=".$installmentData['sell_id']);

        $form_tbl_accounts = array(
            'acc_description' => "Individual payment for Installment ",
            'acc_amount' => $_POST['payment'],
            'purchase_or_sell_id' => 'in_'.$_POST['sell_id'],
            'acc_type' => $payment_cat,
            'cus_or_sup_id' => $_POST['customerid'],
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );

        $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    }

    ?>
    <script>
        window.location = '?q=all_installment';
    </script>
    <?php
}
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js"></script>

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

<div class="col-md-12 bg-slate-600" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View All Installments</strong></h4>
    </div>
    <div class="col-md-6" style="padding-top:5px;">
            <a href="view/print_installment.php" target="_blank" class="btn btn-primary btn-sm pull-right">Print</a>
    </div>
</div>
<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-teal-800">
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-1">Customer & sell</th>
                    <th class="text-center col-md-1">Installment 15 Days</th>
                    <th class="text-center col-md-1">Installment Amount</th>
                    <th class="text-center col-md-1">Total Amount</th>
                    <th class="text-center col-md-1">Installment Due</th>
                    <th class="text-center col-md-1">Punishment</th>
                    <th class="text-center col-md-1">Date</th>
                    <th class="text-center col-md-2">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $sumOfTotalInstallment = 0;
                $sumOfTotalInstallmentAmount = 0;
                $sumOfTotalAmount = 0;
                $sumOfTotalDue = 0;
                $sumOfPayment = 0;
                $sumOfDue = 0;
                foreach ($allinstallment as $installment) {

                    $i++;
                    $totalInstallmentAmount = $installment['total_installment']/$installment['installment_month'];
                    $sumOfTotalInstallment = $totalInstallmentAmount + $sumOfTotalInstallment;
                    $sumOfTotalAmount = $installment['total_installment'] + $sumOfTotalAmount;
                    $sumOfTotalDue = $installment['installment_due'] + $sumOfTotalDue;
                    $installment_id = $installment['id'];
                    $iframButton = '<button href="view/view_sell_item.php?invoiceId=' . $installment['sell_id'] . '" class="btn btn-warning bg-teal-700 btn-xs open-popup-link" data-effect="mfp-zoom-in"> View Sell </a></button>'; ?>
                    <tr>
                        <td class="text-center">
                            <strong><?php echo $i; ?></strong><br>
                        </td>
                        <td class="text-center" style="padding-top:5px">
                            <a class="padding_5_px btn-xs btn-default" href="?q=customer_ledger&customerId=<?php echo $installment['cus_id']; ?>"><?php echo $installment['cus_id']; ?></a>
                            <?php echo isset($installment['sell_id']) ? $iframButton : NULL; ?>
                        </td>
                        <td class="text-center"><?php echo $installment['installment_month']?></td>
                        <td class="text-center"><?php echo $totalInstallmentAmount; ?></td>
                        <td class="text-center"><?php echo $installment['total_installment'] ?></td>
                        <td class="text-center"><?php echo $installment['installment_due'] ?></td>
                        <td class="text-center"><?php echo $installment['punishment'] ?></td>
                        <td class="text-center"><?php echo $installment['date'] ?></td>
                        <td class="text-center">
                            <div class="btn-group" style="margin-top:5px">
                                <?php if ($installment['installment_due']!=0){?>
                                <form action="" method="post">
                                    <input type="hidden" name="payment" value="<?php echo $installment['total_installment']/$installment['installment_month']; ?>">
                                    <input type="hidden" name="customerid" value="<?php echo $installment['cus_id']; ?>">
                                    <input type="hidden" name="installment_id" value="<?php echo $installment['id']; ?>">
                                    <input type="hidden" name="sell_id" value="<?php echo $installment['sell_id']; ?>">
                                    <input type="submit" name="addPayment" class="btn bg-teal btn-success btn-xs" value="Payment">
                                </form>
                                <?php } ?>
                                <a type="button" onclick="return confirm('Are you sure you want to delete this Purchase item?');"
                                   href="?q=all_installment&deleteId=<?php echo $installment['id']; ?>" class="btn btn-danger btn-xs">
                                    <span class="glyphicon glyphicon-trash"></span> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-center col-md-1">Total</th>
                        <th class="text-center col-md-1"></th>
                        <th class="text-center col-md-1"></th>
                        <th class="text-center col-md-1"><?php echo number_format($sumOfTotalInstallment); ?></th>
                        <th class="text-center col-md-1"><?php echo number_format($sumOfTotalAmount); ?></th>
                        <th class="text-center col-md-2"><?php echo number_format($sumOfTotalDue); ?></th>
                        <th class="col-md-2"></th>
                        <th class="col-md-2"></th>
                        <th class="col-md-2">Taka</th>

                    </tr>
                </tfoot>
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
                <h4>Add Payments for this Purchase and Supplier <b><span id="supplierNameModal" class="text-grey-800"> </span></b></h4>
            </div>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group" style="margin-bottom:20px;">
                            <label class="control-label col-sm-4" for="payment">Add Payments </label>
                            <div class="col-sm-6">
                                <input type="text" required onkeypress="return numbersOnly(event)" name="payment" placeholder="Insert payments" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" style="margin-top:20px;">
                            <label class="control-label col-sm-4" for="comments">Comments </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="comments" placeholder="Payment Description" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="supplierId" value="">
                    <input type="hidden" name="purchaseId" value="">
                </div>
                <div class="modal-footer text-center">
                    <div class="row">
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" name="addPayment">Add Payment</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


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

        $('#datatable').on('click', '[data-target="#addPriceModel"]', function () {
            var supplierId = $(this).data('supplier');
            var supplierName = $(this).data('name');
            var purchaseId = $(this).data('purchase_id');

            $('div#addPriceModel input[name="supplierId"]').val(supplierId);
            $('div#addPriceModel input[name="purchaseId"]').val(purchaseId);
            $('div#addPriceModel span#supplierNameModal').html(supplierName);

        });
    });
</script>