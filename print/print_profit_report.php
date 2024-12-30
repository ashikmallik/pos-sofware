<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
//$date        = date('Y-m-d');
$Month = date('m', strtotime($date));
$Year = date('Y', strtotime($date));
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$action = isset($_GET['action']) ? $_GET['action'] : null;

if ($action == 'searched') {
    $dateYear = date('Y',strtotime($_GET['monthDate']));
    $dateMonth = date('m',strtotime($_GET['monthDate']));
    $monthDate = $_GET['monthDate'];
    $header = "of  ".date('M Y',strtotime($monthDate))." .";

} else {

    $dateYear = date('Y');
    $dateMonth = date('m');
    $monthDate = date('Y-m-d');
    $header = "of this Month";
}


?>

<div class="row" style="margin:15px; 0">
    <button type="submit" class="btn btn-success bg-slate-700 btn-block"
            onclick="printDiv('purchase_print')">Click to Print Below Statement
    </button>
</div>

<div class="row" id="purchase_print">
    <div class="col-md-12">
        <div class="text-center" style="margin-bottom:5px;">
            <b>All Purchase list <?php echo $header; ?></b>
        </div>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="bg-teal-800">
                <tr>
                    <th class="col-md-1 text-center">SL</th>
                    <th class="col-md-1 text-center">Date</th>
                    <th class="col-md-2 text-center">Item</th>
                    <th class="col-md-1 text-center">Sell Qty</th>
                    <th class="col-md-1 text-center">Purchase Price</th>
                    <th class="col-md-1 text-center">Sell Price</th>
                    <th class="col-md-1 text-center">Unit Profit</th>
                    <th class="col-md-1 text-center">Nit Profit</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $max = 31;
                $total_qty = 0;
                $total_profit = 0;
                for ($day = 1; $day <= $max; $day++) {

                    $total_short = 0;
                    $pre_date = $dateYear . '-' . $dateMonth . '-' . $day;
                    $date = date('Y-m-d', strtotime($pre_date));

                    $sell_item_daily = $obj->view_all_by_cond("vw_sell_purchase_item_daily", "purchase_sell_flag = 0 AND entry_date = '$date'");

                    if (!$sell_item_daily) {
                        continue;
                    }

                    foreach ($sell_item_daily as $sell_item) { $i++;?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td class="text-center"><?php echo date('d-M-Y', strtotime($date)); ?></td>
                            <td class="text-center">
                                <?php echo $sell_item['product_name']; ?>
                            </td>

                            <td class="text-center">
                                <?php $total_qty += $sell_item['qty']; echo $sell_item['qty']; ?>
                            </td>

                            <td>
                                <?php $purchase_price = $obj->details_by_cond("vw_purchase_stock_item", "product_id = '".$sell_item['product_id']."'");
                                echo isset($purchase_price['avg_purchase_price']) ? number_format($purchase_price['avg_purchase_price'],1) : 0;
                                ?>
                            </td>
                            <td class="text-center">
                                <?php $sell_price = $obj->details_by_cond("vw_sell_stock_item", "product_id = '".$sell_item['product_id']."'");
                                echo isset($sell_price['avg_sell_price']) ? number_format($sell_price['avg_sell_price'],1) : 0;
                                ?>
                            </td>
                            <td>
                                <?php $unit_profit = $sell_price['avg_sell_price'] - $purchase_price['avg_purchase_price']; echo number_format($unit_profit ,1); ?>
                            </td>
                            <td>
                                <?php $total_profit += $profit = $sell_item['qty']*($sell_price['avg_sell_price'] - $purchase_price['avg_purchase_price']); echo number_format($profit,1); ?>
                            </td>
                        </tr>
                    <?php }
                    ?>

                    <?php
                }
                ?>
                <tr>
                    <td colspan="3" class="text-center">
                        <strong>Total Qty</strong>
                    </td>
                    <td class="text-center">
                        <strong><?php echo $total_qty; ?> pcs</strong>
                    </td>

                    <td>

                    </td>
                    <td class="text-center" colspan="2">
                        <strong>And Profit</strong>
                    </td>
                    <td>
                        <strong><?php echo $total_profit; ?> taka</strong>
                    </td>

                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- here end table -->

    <script>
        $(document).ready(function () {
            //$('#monthly_tbl').dataTable();
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
            $("tbody tr").dblclick(function () {
                _id = this.id;
                if (_id != "0")
                    window.location = "?q=view_customer_payment_individual&token2=" + _id;
            });
        });
    </script>
    <!-- here end table -->