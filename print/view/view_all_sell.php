<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$sell_cat = 3; // for accounts table cat


$allSellData = $obj->view_all_ordered_by("vw_sell", "`vw_sell`.`sell_id` DESC");


if (isset($_GET['deleteSellId']) && !empty($_GET['deleteSellId'])) {

    $deleteId = $_GET['deleteSellId'];
    $obj->Delete_data('tbl_sell_item', "sell_no = '$deleteId'");
    $obj->Delete_data('tbl_sell', "sell_id = '$deleteId'");
    $obj->Delete_data('tbl_account', "purchase_or_sell_id = 's_$deleteId'");
    ?>
    <script>
        window.location = '?q=view_all_sell';
    </script>
    <?php }


if (isset($_POST['addPayment'])) {

    if(!empty($_POST['payment']) && isset($_POST['payment'])){
        $purchaseData = $obj->details_by_cond('tbl_sell', "sell_id = ".$_POST['sellId']);

        $totalPrice = $purchaseData['total_price'];
        $previousPayment = $purchaseData['payment_recieved'];
        $newPayment = $_POST['payment'] + $previousPayment;
        $newDue = $totalPrice - $newPayment;

        $form_purchase_update = array(
            'payment_recieved' => $newPayment,
            'due_to_company' => $newDue,
            'update_by' => $userid
        );
        $obj->Update_data("tbl_sell", $form_purchase_update, "sell_id=".$_POST['sellId']);

        $form_tbl_accounts = array(

            'acc_description' => "Individual payment for Sell (id = ".$_POST['sellId'].")." . $_POST['comments'],
            'acc_amount' => $_POST['payment'],
            'purchase_or_sell_id' => 's_'.$_POST['sellId'],
            'acc_type' => $sell_cat,
            'cus_or_sup_id' => $_POST['customerId'],
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );

        $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    }

    ?>
    <script>
        window.location = '?q=view_all_sell';
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
<?php echo isset($notification) ? $notification : NULL; ?>
<div class="col-md-12 bg-teal-600" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View All Sell List</strong></h4>
    </div>
    <div class="col-md-6" style="padding-top:5px;">
        <?php if ($ty == 'SA'){ ?>
            <a class="btn btn-success btn-sm pull-right" href="?q=add_sell">ADD NEW <span class="glyphicon
        glyphicon-plus"></span></a>
            <a href="?q=print_sell" target="_blank" class="btn btn-success btn-sm pull-right">Print Sell List</a>
        <?php } ?>
    </div>
</div>
<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-slate-800">
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-2">Customer</th>
                    <th class="text-center col-md-1">Total Qty</th>
                    <th class="text-center col-md-1">Total Bill</th>
                    <th class="text-center col-md-1">Payment</th>
                    <th class="text-center col-md-1">Dues</th>
                    <th class="text-center col-md-1">Bill Date</th>
                    <th class="text-center col-md-4">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $sumOfTotalPrice = 0;
                $sumOfPayment = 0;
                $sumOfDue = 0;

                foreach ($allSellData as $sell) {

                    $i++;
                    $sell_id = $sell['sell_id'];
                    $iframButton = '<button href="view/view_sell_item.php?invoiceId=' . $sell_id . '" class="btn btn-warning bg-teal-700 btn-xs open-popup-link" data-effect="mfp-zoom-in">' . number_format($sell['total_qty']) . ' pcs </a></button>'; ?>
                    <tr>
                        <td>
                            <strong><?php echo $i; ?></strong>&nbsp;
                        
                        </td>
                        <td class="text-center" style="padding-top:5px">
                            <a class="padding_5_px btn-xs btn-default" href="?q=customer_ledger&customerId=<?php echo isset
                            ($sell['customer']) ? $sell['customer'] : NULL; ?>"><?php echo isset($sell['customer_name']) ? $sell['customer_name'] : null; ?></a>
                        </td>
                        <td class="text-center" style="padding-top:14px;"><?php echo isset($sell['total_qty']) ? $iframButton : NULL; ?></td>
                        <td class="text-center"><?php $sumOfTotalPrice += $sell['total_price']; echo isset($sell['total_price']) ? number_format($sell['total_price']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php $sumOfPayment += $sell['payment_recieved']; echo isset($sell['payment_recieved']) ? number_format($sell['payment_recieved']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php $sumOfDue += $sell['due_to_company']; echo isset($sell['due_to_company']) ? number_format($sell['due_to_company']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($sell['entry_date']) ? date('d-M-y', strtotime($sell['entry_date'])) : NULL; ?></td>
                        <td class="text-center">
                            <div class="btn-group" style="margin-top:5px">
                                <a type="button"
                                   data-name = "<?php echo isset ($sell['customer_name']) ? $sell['customer_name'] : null; ?>"
                                   data-customer = "<?php echo isset($sell['customer']) ? $sell['customer'] : NULL; ?>"
                                   data-sell_id = "<?php echo isset($sell['sell_id']) ? $sell['sell_id'] : NULL; ?>"
                                   data-toggle="modal" data-target="#addPriceModel"
                                   class="btn bg-teal btn-success btn-xs">
                                    <span class="glyphicon glyphicon-usd"></span> Payment
                                </a>
                                <?php echo '<a type="button" href="?q=edit_sell&invoiceId=' . $sell['sell_id'] . '" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Edit</a>'; ?>
                                
                                <a type="button"
                                   onclick="return confirm('Are you sure you want to delete this Sell item?');"
                                   href="?q=view_all_sell&deleteSellId=<?php echo $sell['sell_id']; ?>"
                                   class="btn btn-danger btn-xs">
                                    <span class="glyphicon glyphicon-trash"></span> Delete
                                </a>
                                <a type="button" target="_blank" href="pdf/invoice.php?invoiceId=<?php echo $sell_id ?>"
                                   class="btn bg-grey-800 btn-default btn-xs">
                                    <span class="glyphicon glyphicon-print"></span> Print</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-center col-md-4">Total</th>
                        <th class="text-center col-md-1"><?php echo number_format($sumOfTotalPrice); ?></th>
                        <th class="text-center col-md-1"><?php echo number_format($sumOfPayment); ?></th>
                        <th class="text-center col-md-1"><?php echo number_format($sumOfDue);  ?></th>
                        <th colspan="2" class="col-md-5">Taka</th>

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
                <h4>Add Payments for this Sell and Customer <b><span id="customerNameModal" class="text-grey-800"> </span></b></h4>
            </div>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group" style="margin-bottom:20px;">
                            <label class="control-label col-sm-4" for="payment">Add Payments </label>
                            <div class="col-sm-6">
                                <input type="text" onkeypress="return numbersOnly(event)" name="payment" placeholder="Insert payments" class="form-control">
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
                    <input type="hidden" name="customerId" value="">
                    <input type="hidden" name="sellId" value="">
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
            var customerId = $(this).data('customer');
            var customerName = $(this).data('name');
            var sellId = $(this).data('sell_id');

            $('div#addPriceModel input[name="customerId"]').val(customerId);
            $('div#addPriceModel input[name="sellId"]').val(sellId);
            $('div#addPriceModel span#customerNameModal').html(customerName);

        });
    });
</script>