<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customer_cat = 5; // for accounts

if (isset($_POST['search'])) {
    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));

    $todayStock = $obj->view_all_by_cond("vw_sell_purchase_item", "purchase_sell_flag=0 AND entry_date BETWEEN '$startDate' AND '$endDate' order by entry_date");

    $print = 'action=search&startDate=' . $startDate . '&endDate=' . $endDate . '';
    $header = 'Between ' . date('d-M-Y', strtotime($startDate)) . ' To ' . date('d-M-Y', strtotime($endDate)) . '';
} else {
    $todayStock = $obj->view_all_by_cond("vw_sell_purchase_item", "purchase_sell_flag=0 AND `entry_date` = CURDATE()");
    $print = 'action=dtoken&date=' . date('Y-m-d') . '';
    $header = 'of Today (' . date('d-m-Y') . ') ';
} ?>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js"></script>


<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-8">
        <h4><strong>Delivery Report <?php echo $header; ?> List </strong></h4>
    </div>

    <div class="col-md-4" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>
            <a href="?q=print_today_stock_report&<?php echo $print; ?>" target="_blank" class="btn btn-primary btn-sm pull-right">Print Stock Report</a>
        <?php } ?>
    </div>
</div>

<!--<div class="col-md-12 bg-slate-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="startDate">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" required="required" type="text" name="startDate">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="endDate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" required="required" name="endDate">
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default"><span
                        class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>-->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-teal-800">
                <tr style="font-size:12px;">
                    <th class="col-md-3">Product Name</th>
                    <th class="col-md-1">Sell Unit</th>
                    <th class="col-md-1">Sell ID</th>
                    <th class="col-md-1">Sell Delivery</th>
                    <th class="col-md-1">Remaining Delivery</th>
                    <th class="col-md-1">Total Sell</th>
                    <th class="col-md-1">Customer</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total_purchase_unit = 0;
                $total_purchase_price = 0;
                $total_amount_price =0;
                $total_sell_unit = 0;
                $total_sell_price = 0;
                $total_sell = 0;
                $total_remaining_delivery = 0;
                $total_delivey = 0;
                foreach ($todayStock as $stockItem) {

                    $bill_sell_array = explode('_', $stockItem['bill_or_sell_id']); ?>
                    <tr>
                        <td class="text-center"><?php echo $stockItem['product_name']; ?></td>
                        <?php
                            $total_sell_unit = $stockItem['qty'] + $total_sell_unit;
                            $total_sell_price = $stockItem['price'] + $total_sell_price;
                            $total_sell = $stockItem['total_amount'] + $total_sell;
                            ?>
                            <td class="text-center"><?php echo $stockItem['qty']; ?></td>
                            <td class="text-center"><button href="view/view_sell_item.php?invoiceId=<?php echo trim($stockItem['bill_or_sell_id'],'s_'); ?>" class="btn btn-warning bg-teal-700 btn-xs open-popup-link" data-effect="mfp-zoom-in"><?php echo $stockItem['bill_or_sell_id']; ?> </a></button></td>
                            <td class="text-center"><?php
                                $today_date = date('Y-m-d');
                                $delivery = $obj->get_sum_data('tbl_delivery_item','qty',"product_id=".$stockItem['product_id']." AND delivery_date='".$today_date."'");
                                $total_delivey = $delivery +$total_delivey;
                                ?><!--<button href="view/view_sell_delivery_item.php?invoiceId=<?php echo trim($stockItem['bill_or_sell_id'],'s_'); ?>" class="btn btn-warning bg-teal-700 btn-xs open-popup-link" data-effect="mfp-zoom-in"><?php echo $delivery; ?> </a></button>--><?php echo $delivery; ?></td>
                            <td class="text-center"><?php echo $remaining_delivery = $stockItem['qty']-$delivery;
                                $total_remaining_delivery += $remaining_delivery;
                                ?></td>
                            <td class="text-center"><?php echo $stockItem['total_amount']; ?></td>
                        <?php  ?>
                        <td class="text-center"><?php echo $stockItem['supplier_customer_name']; ?></td>

                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="text-center "><?php  ?></th>
                    <th class="text-center "><?php echo number_format($total_sell_unit);  ?></th>
                    <th class="text-center "><?php   ?></th>
                    <th class="text-center "><?php echo number_format($total_delivey);  ?></th>
                    <th class="text-center"><?php echo number_format($total_remaining_delivery);  ?></th>
                    <th class=""><?php   ?></th>
                    <th class="">Taka</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>

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
