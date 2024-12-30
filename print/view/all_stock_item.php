<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;


$allStockItemData  = $obj->view_all_ordered_by("vw_sell_purchase_stock", "`vw_sell_purchase_stock`.`total_purchase_qty` DESC");

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

<?php echo isset($notification) ? $notification : NULL; ?>
<div class="col-md-12 bg-primary" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>All Stock Item</strong></h4>
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
                    <th class="text-center col-md-2">Item Name</th>
                    <th class="text-center col-md-1">Total Purchase</th>
                    <th class="text-center col-md-1">Avg Purchase Price</th>
                    <th class="text-center col-md-1">Total Sell</th>
                    <th class="text-center col-md-1">Avg Sell Price</th>
                    <th class="text-center col-md-1">Stock</th>
                    <th class="text-center col-md-1">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $total_purchase = 0;
                $total_avg_purchase_price =0;
                $total_sell_price = 0;
                $total_avg_sell_price = 0;
                $total_stock = 0;
                foreach ($allStockItemData as $stock) {
                    $i++;
                    $total_purchase = $stock['total_purchase_qty'] + $total_purchase;
                    $total_avg_purchase_price = $stock['avg_purchase_price'] + $total_avg_purchase_price;
                    $total_sell_price = $stock['total_sell_qty'] + $total_sell_price;
                    $total_avg_sell_price = $stock['avg_sell_price'] + $total_avg_sell_price;

                    ?>
                    <tr>
                        <td class="text-center">
                            <?php echo $i; ?>
                        </td>

                        <td class="text-center">
                            <?php echo isset($stock['product_name']) ? $stock['product_name'] : NULL; ?>
                        </td>

                        <td class="text-center"><?php isset($stock['total_purchase_qty']) ? $purchase_qty = $stock['total_purchase_qty'] : $purchase_qty = 0;
                            echo number_format($purchase_qty) ?>
                        </td>

                        <td class="text-center"><?php isset($stock['avg_purchase_price']) ? $avg_purchase_price = $stock['avg_purchase_price'] : $avg_purchase_price = 0;
                            echo number_format($avg_purchase_price, 2) ?>
                        </td>

                        <td class="text-center"><?php isset($stock['total_sell_qty']) ? $sell_qty = $stock['total_sell_qty'] : $sell_qty = 0;
                            echo number_format($sell_qty) ?>
                        </td>

                        <td class="text-center"><?php isset($stock['avg_sell_price']) ? $avg_sell_price = $stock['avg_sell_price'] : $avg_sell_price = 0;
                            echo number_format($avg_sell_price, 2) ?>
                        </td>

                        <?php $stockQty = $purchase_qty - $sell_qty;
                        $total_stock = $stockQty + $total_stock;
                        ?>
                        <td class="text-center">
                            <strong class="<?php echo ($stockQty <= 5)? 'label label-danger': '';?>"><?php echo $stockQty ?></strong>
                        </td>

                        <td class="text-center">
                            <?php echo '<button href="view/view_stock_item_details.php?stockId=' . $stock['product_id'] . '" class="btn btn-info bg-slate-700 btn-xs open-popup-link" data-effect="mfp-zoom-in">Show Details </a></button>'; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-center "><?php  ?></th>
                    <th class="text-center "><?php echo number_format($total_purchase); ?></th>
                    <th class="text-center "><?php echo number_format($total_avg_purchase_price);  ?></th>
                    <th class="text-center "><?php echo number_format($total_sell_price);  ?></th>
                    <th class="text-center "><?php echo number_format($total_avg_sell_price);  ?></th>
                    <th class="text-center "><?php echo number_format($total_stock);  ?></th>
                    <th colspan="2" class=""><?php  ?></th>
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
                <h4>Add Payments for this Sell and Customer <b><span id="customerNameModal"
                                                                     class="text-grey-800"> </span></b></h4>
            </div>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group" style="margin-bottom:20px;">
                            <label class="control-label col-sm-4" for="payment">Add Payments </label>
                            <div class="col-sm-6">
                                <input type="text" onkeypress="return numbersOnly(event)" name="payment"
                                       placeholder="Insert payments" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" style="margin-top:20px;">
                            <label class="control-label col-sm-4" for="comments">Comments </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="comments" placeholder="Payment Description"
                                          rows="3"></textarea>
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
    });
</script>