<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

if (isset($_POST['search'])) {
    extract($_POST);
    $print = 'action=searched&monthDate=' . date('Y-m-d', strtotime("1.$dateMonth.$dateYear")) . '';
    $header = date('M Y', strtotime("1.$dateMonth.$dateYear"));
} else {

    $dateYear = date('Y');
    $dateMonth = date('m');
    $print = 'action=default&monthDate=' . date('Y-m-d') . '';
    $header = date('M Y');
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View Profit for <?php echo $header; ?> <span id="show_profit"></span> </strong></h4>
    </div>

    <div class="col-md-6" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>
            <a href="?q=print_profit_report&<?php echo $print ?>" target="_blank"
               class="btn btn-primary btn-sm pull-right">Print Profit List</a>
        <?php } ?>
    </div>
</div>

<div class="col-md-12 bg-slate-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-4" style="padding-top:5px" for="dateMonth">Select Month</label>
                <div class="col-sm-8">
                    <select class="form-control" required="required" name="dateMonth" id="status">
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
                    <select class="form-control" required="required" name="dateYear" id="status">
                        <option></option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                        <option value="2031">2031</option>
                        <option value="2032">2032</option>
                        <option value="2033">2033</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-primary"><span class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search</button>
        </div>
    </form>
</div>


<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
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
                                <?php $unit_profit = ($sell_price['avg_sell_price'] - $purchase_price['avg_purchase_price']) * $sell_item['qty']; echo number_format($unit_profit ,1); ?>
                            </td>
                            <td>
                                <?php $total_profit += $profit = ($sell_price['avg_sell_price'] - $purchase_price['avg_purchase_price']) * $sell_item['qty']; echo number_format($profit,1); ?>
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
                    <td class="text-center" colspan="1">

                    </td>
                    <td class="text-center" colspan="2">
                        <strong>And Profit</strong>
                    </td>
                    <td class="text-center">
                        <strong><?php echo $total_profit; ?> taka</strong>
                    </td>

                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        $(document).ready(function () {
            $('select').selectpicker();
        });

        $('#show_profit').html(' As Total <?php echo number_format($total_profit) ?> taka');

    });
</script>