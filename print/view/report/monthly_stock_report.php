<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customer_cat = 5; // for accounts


if (isset($_POST['search'])) {
    extract($_POST);
    $monthlyStock = $obj->view_all_by_cond("vw_sell_purchase_item_daily", "MONTH(entry_date)='$dateMonth' and YEAR
    (entry_date)='$dateYear' order by entry_date");

    $print = 'action=monthly&monthDate='.date('Y-m-d', strtotime("1.$dateMonth.$dateYear")).'';
    $header = date('M Y', strtotime("1.$dateMonth.$dateYear"));

}else if(isset($_GET['dtoken'])){
    $date = $_GET['dtoken'];
    $monthlyStock = $obj->view_all_by_cond("vw_sell_purchase_item_daily", "MONTH(entry_date)=MONTH('$date') and YEAR
    (entry_date)= YEAR('$date') order by entry_date");

    $print = 'action=monthly&monthDate='.$date.'';
    $header = date('M Y', strtotime($date));

} else {
    $monthlyStock = $obj->view_all_by_cond("vw_sell_purchase_item_daily", "month(entry_date) = month(CURDATE()) AND YEAR
    (entry_date)= YEAR(CURDATE())order by entry_date");
    $print = 'action=monthly&monthDate='.date('Y-m-d').'';
    $header = date('M Y');
}

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>


<div class="col-md-12 bg-teal-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View All <?php echo $header; ?> Stock List </strong></h4>
    </div>

    <div class="col-md-6" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>
            <a href="?q=print_monthly_stock&<?php echo $print ?>" target="_blank" class="btn btn-primary btn-sm pull-right">Print Monthly Stock
                List</a>
        <?php } ?>
    </div>
</div>

<div class="col-md-12 bg-teal-800" style="margin-bottom: 20px;padding:5px 0px">
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
                <label class="control-label col-sm-4"  style="padding-top:5px" for="damageRate">Select Year</label>
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

<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-slate-800">
                <tr style="font-size:12px;">
                    <th class="col-md-1">Date</th>
                    <th class="col-md-3">Product Name</th>
                    <th class="col-md-1">Purchase Unit</th>
                    <th class="col-md-1">Purchase Price</th>
                    <th class="col-md-1">Total Purchase</th>
                    <th class="col-md-1">Sell Unit</th>
                    <th class="col-md-1">Sell Price</th>
                    <th class="col-md-1">Total Sell</th>
                    <th class="col-md-1">Customer / Supplier</th>
                    <th class="col-md-1">Action</th>
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
                foreach ($monthlyStock as $stockItem) {
                    $bill_sell_array = explode('_', $stockItem['bill_or_sell_id']);

                    ?>
                    <tr>
                        <td class="text-center"><?php echo date('d-M-y', strtotime($stockItem['entry_date'])); ?></td>
                        <td class="text-center"><?php echo $stockItem['product_name']; ?></td>
                        <?php if ($stockItem['purchase_sell_flag'] == '1') {
                            $total_purchase_unit = $stockItem['qty'] + $total_purchase_unit;
                            $total_purchase_price = $stockItem['price'] + $total_purchase_price;
                            $total_amount_price = $stockItem['total_amount'] + $total_amount_price;

                            ?> <!-- For show purchase unit -->
                            <td class="text-center"><?php echo $stockItem['qty']; ?></td>
                            <td class="text-center"><?php echo $stockItem['price']; ?></td>
                            <td class="text-center"><?php echo $stockItem['total_amount']; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        <?php } else {
                            $total_sell_unit = $stockItem['qty'] + $total_sell_unit;
                            $total_sell_price = $stockItem['price'] + $total_sell_price;
                            $total_sell = $stockItem['total_amount'] + $total_sell;
                            ?>
                            <td></td><!-- For show Sell unit -->
                            <td></td>
                            <td></td>
                            <td class="text-center"><?php echo $stockItem['qty']; ?><br></td>
                            <td class="text-center"><?php echo $stockItem['price']; ?></td>
                            <td class="text-center"><?php echo $stockItem['total_amount']; ?></td>
                        <?php } ?>
                        <td class="text-center"><?php echo $stockItem['supplier_customer_name']; ?></td>
                        <td class="text-center">
                            <?php if ($stockItem['purchase_sell_flag'] == '1') { ?>
                                <a class="btn btn-sm btn-default" target="_blank"
                                   href="pdf/bill.php?billId=<?php echo $bill_sell_array[1]; ?>">
                                    <span class="glyphicon glyphicon-print"></span> Print
                                </a>
                            <?php } else { ?>
                                <a class="btn btn-sm btn-default" target="_blank"
                                   href="pdf/invoice.php?invoiceId=<?php echo $bill_sell_array[1]; ?>">
                                    <span class="glyphicon glyphicon-print"></span> Print
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-center "><?php  ?></th>
                    <th class="text-center "><?php echo number_format($total_purchase_unit); ?></th>
                    <th class="text-center "><?php echo number_format($total_purchase_price);  ?></th>
                    <th class="text-center "><?php echo number_format($total_amount_price);  ?></th>
                    <th class="text-center "><?php echo number_format($total_sell_unit);  ?></th>
                    <th class="text-center "><?php echo number_format($total_sell_price);  ?></th>
                    <th colspan="2" class=""><?php echo number_format($total_sell);  ?></th>
                    <th colspan="2" class="">Taka</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $(document).ready(function () {
            $('select').selectpicker();

        });

    });
</script>