<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$purchase_cat = 2; // for accounts table cat

$allPurchaseData = $obj->view_all_by_cond("vw_purchase", "`vw_purchase`.`material`=1 ORDER BY `vw_purchase`.`bill_id` DESC");

if (isset($_GET['deletePurchaseId']) && !empty($_GET['deletePurchaseId'])) {

    $deleteId = $_GET['deletePurchaseId'];
    $obj->Delete_data('tbl_purchase_item', "bill_id = '$deleteId'");
    $obj->Delete_data('tbl_purchase', "bill_id = '$deleteId'");
    $obj->Delete_data('tbl_account', "purchase_or_sell_id = 'p_$deleteId'");
    ?>
    <script>
        window.location = '?q=view_all_purchase';
    </script>
    <?php }

if (isset($_POST['addPayment'])) {

    if(!empty($_POST['payment']) && isset($_POST['payment'])){
        $purchaseData = $obj->details_by_cond('tbl_purchase', "bill_id = ".$_POST['purchaseId']);

        $totalPrice = $purchaseData['total_price'];
        $previousPayment = $purchaseData['payment_recieved'];
        $newPayment = $_POST['payment'] + $previousPayment;
        $newDue = $totalPrice - $newPayment;

        $form_purchase_update = array(
            'payment_recieved' => $newPayment,
            'due_to_company' => $newDue,
            'update_by' => $userid
        );
        $obj->Update_data("tbl_purchase", $form_purchase_update, "bill_id=".$_POST['purchaseId']);

        $form_tbl_accounts = array(

            'acc_description' => "Individual payment for purchase (id = ".$_POST['purchaseId'].")." . $_POST['comments'],
            'acc_amount' => $_POST['payment'],
            'purchase_or_sell_id' => 'p_'.$_POST['purchaseId'],
            'acc_type' => $purchase_cat,
            'cus_or_sup_id' => $_POST['supplierId'],
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );

        $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    } ?>
    <script>
        window.location = '?q=view_all_purchase';
    </script>
    <?php } ?>

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
        <h4><strong>View All Material Purchase List</strong></h4>
    </div>
    <div class="col-md-6" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>
            <a class="btn btn-primary btn-sm pull-right" href="?q=add_purchase">ADD NEW <span class="glyphicon
        glyphicon-plus"></span></a>
            <a href="?q=print_purchase" target="_blank" class="btn btn-primary btn-sm pull-right">Print Purchase List</a>
        <?php } ?>
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
                    <th class="text-center col-md-2">Supplier</th>
                    <th class="text-center col-md-1">Total Qty (item)</th>
                    <th class="text-center col-md-1">Total Price</th>
                    <th class="text-center col-md-1">Payment</th>
                    <th class="text-center col-md-1">Dues</th>
                    <th class="text-center col-md-1">Date</th>
                    <th class="text-center col-md-4">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $sumOfTotalPrice = 0;
                $sumOfPayment = 0;
                $sumOfDue = 0;
                foreach ($allPurchaseData as $purchase) {

                    $i++;
                    $purchase_id = $purchase['bill_id'];
                    $iframButton = '<button href="view/view_purchase_item.php?billId=' . $purchase_id . '" class="btn btn-info bg-slate-700 btn-xs open-popup-link" data-effect="mfp-zoom-in">' . number_format($purchase['total_qty']) . ' pcs </a></button>'; ?>
                    <tr>
                        <td class="text-center">
                            <strong><?php echo $i; ?></strong><br>
                        </td>
                        <td class="" style="padding-top:5px">
                            <a class="padding_5_px btn-xs btn-default" href="?q=supplier_ledger&supplierId=<?php echo isset($purchase['supplier']) ? $purchase['supplier'] : NULL; ?>"><?php echo isset($purchase['supplier_name']) ? $purchase['supplier_name'] : null; ?></a>
                        </td>
                        <td class=""><?php echo isset($purchase['total_qty']) ? $iframButton : NULL; ?></td>
                        <td class="text-right"><?php $sumOfTotalPrice += $purchase['total_price']; echo isset($purchase['total_price']) ? number_format($purchase['total_price']) . ' TK.' : NULL; ?></td>
                        <td class="text-right"><?php $sumOfPayment += $purchase['payment_recieved']; echo isset($purchase['payment_recieved']) ? number_format($purchase['payment_recieved']) . ' TK.' : NULL; ?></td>
                        <td class="text-right"><?php $sumOfDue += $purchase['due_to_company']; echo isset($purchase['due_to_company']) ? number_format($purchase['due_to_company']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($purchase['entry_date']) ? date('d-M-y', strtotime($purchase['entry_date'])) : NULL; ?></td>
                        <td class="text-center">
                            <div class="btn-group" style="margin-top:5px">
                                <a type="button" data-name = "<?php echo isset ($purchase['supplier_name']) ? $purchase['supplier_name'] : null; ?>"
                                   data-supplier = "<?php echo isset($purchase['supplier']) ? $purchase['supplier'] : NULL; ?>"
                                   data-purchase_id = "<?php echo isset($purchase['bill_id']) ? $purchase['bill_id'] : NULL; ?>"
                                   data-toggle="modal" data-target="#addPriceModel" class="btn bg-teal btn-success btn-xs">
                                    <span class="glyphicon glyphicon-usd"></span> Payment
                                </a>
                                <?php echo '<a type="button" href="?q=edit_purchase&billId=' . $purchase['bill_id']  . '" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Edit</a>'; ?>
                                <a type="button" onclick="return confirm('Are you sure you want to delete this Purchase item?');"
                                   href="?q=view_all_purchase&deletePurchaseId=<?php echo $purchase['bill_id']; ?>" class="btn btn-danger btn-xs">
                                    <span class="glyphicon glyphicon-trash"></span> Delete
                                </a>
                                <a type="button" target="_blank" href="pdf/bill.php?billId=<?php echo $purchase_id ?>" class="btn bg-grey-800 btn-default btn-xs">
                                    <span class="glyphicon glyphicon-print"></span> Print</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-center col-md-4">Total</th>
                        <th class="text-right col-md-1"><?php echo number_format($sumOfTotalPrice); ?> TK.</th>
                        <th class="text-right col-md-1"><?php echo number_format($sumOfPayment); ?> TK.</th>
                        <th class="text-right col-md-1"><?php echo number_format($sumOfDue);  ?> TK.</th>
                        <th colspan="2" class="col-md-5"></th>
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

        $('#datatable').on('click', '[data-target="#returnModel"]', function () {
            var supplierId = $(this).data('supplier');
            var supplierName = $(this).data('name');
            var purchaseId = $(this).data('purchase_id');

            $('div#returnModel input[name="supplierId"]').val(supplierId);
            $('div#returnModel input[name="purchaseId"]').val(purchaseId);
            $('div#returnModel span#supplierNameModal').html(supplierName);

        });
    });
</script>