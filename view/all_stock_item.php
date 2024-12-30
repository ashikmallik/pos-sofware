<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$total_stock_price = 0;
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
    <div class="col-md-8">
        <h4><strong>All Stock Item</strong></h4>
    </div>

    <div class="col-md-4" style="padding-top:5px;">

    </div>

</div>
<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable-btn">
                <thead class="bg-teal-800">
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-2">Item Name</th>
                    <th class="text-center col-md-1">Total Purchase</th>
                    <th class="text-center col-md-1">Avg Purchase Price</th>
                    <th class="text-center col-md-1">Total Sell</th>
                    <th class="text-center col-md-1">Total Delevered</th>
                    <th class="text-center col-md-1">Avg Sell Price</th>
                    <th class="text-center col-md-1">Stock</th>
                    <th class="text-center col-md-1">Price</th>
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
                $total_delevered_all = 0 ;
                foreach ($allStockItemData as $stock) {
                    $i++;
                    $total_purchase = $stock['total_purchase_qty'] + $total_purchase;
                    $total_avg_purchase_price = $stock['avg_purchase_price'] + $total_avg_purchase_price;
                    $total_sell_price = $stock['total_sell_qty'] + $total_sell_price;
                    $total_avg_sell_price = $stock['avg_sell_price'] + $total_avg_sell_price;
                    $total_delevered  = $obj->get_sum_data("tbl_delivery_item","qty"," `product_id`='".$stock['product_id']."'");
                    $total_delevered_all = $total_delevered +$total_delevered_all;
                    $return_sell  = $obj->get_sum_data('tbl_return','return_qty','type=0 AND product_id='.$stock['product_id']);
                    ?>
                    <tr>
                        <td class="text-center">
                            <?php echo $i; ?>
                        </td>

                        <td class="">
                            <?php echo isset($stock['product_name']) ? $stock['product_name'] : NULL; ?>
                        </td>

                        <td class="text-right"><?php isset($stock['total_purchase_qty']) ? $purchase_qty = $stock['total_purchase_qty'] : $purchase_qty = 0;
                            echo number_format($purchase_qty) ?>
                        </td>

                        <td class="text-right"><?php isset($stock['avg_purchase_price']) ? $avg_purchase_price = $stock['avg_purchase_price'] : $avg_purchase_price = 0;
                            echo number_format($avg_purchase_price, 2) ?>
                        </td>

                        <td class="text-right"><?php isset($stock['total_sell_qty']) ? $sell_qty = $stock['total_sell_qty'] : $sell_qty = 0;
                            echo number_format($sell_qty-$return_sell) ?>
                        </td>
                        <td class="text-right"><?php isset($total_delevered) ? $total_delevered : $total_delevered = 0;
                            echo number_format($total_delevered-$return_sell) ?>
                        </td>

                        <td class="text-right"><?php isset($stock['avg_sell_price']) ? $avg_sell_price = $stock['avg_sell_price'] : $avg_sell_price = 0;
                            echo number_format($avg_sell_price, 2) ?>
                        </td>

                        <?php
                        $return_purchase  = $obj->get_sum_data('tbl_return','return_qty','type=1 AND product_id='.$stock['product_id']);
                        $stockQty = $purchase_qty - $total_delevered - $return_purchase + $return_sell;
                        $total_stock = $stockQty + $total_stock;
                        ?>
                        <td class="text-right">
                            <strong class="<?php echo ($stockQty <= 5)? 'label label-danger': '';?>"><?php echo number_format($stockQty,2)  ?></strong>
                        </td>
                        <td class="text-right">
                             <?php echo round($sprice = $stockQty*$avg_purchase_price,2) ;
                             $total_stock_price += $sprice;
                             ?>
                             
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-center "><?php  ?></th>
                    <th class="text-right "><?php echo number_format($total_purchase); ?></th>
                    <th class="text-right "><?php echo number_format($total_avg_purchase_price);  ?></th>
                    <th class="text-right "><?php echo number_format($total_sell_price);  ?></th>
                    <th class="text-right "><?php echo number_format($total_delevered_all);  ?></th>
                    <th class="text-right "><?php echo number_format($total_avg_sell_price);  ?></th>
                    <th class="text-right "><?php echo number_format($total_stock,2);  ?></th>
                    <th class="text-right "><?php echo number_format($total_stock_price);  ?></th>
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

    $(document).ready(function() {
        $("#datatable-btn").dataTable().fnDestroy();
        $('#datatable-btn').DataTable( {
            "ordering": false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print Stock Item',
                    footer: true,
                    title: function () {
                        return "All Stock Item <?php echo date('d, M Y')?>"
                    },
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
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