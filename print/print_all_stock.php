<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customer_cat = 5; // for accounts

if (isset($_POST['search'])) {

    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));

    $todayStock = $obj->view_all_by_cond("vw_sell_purchase_item", "entry_date BETWEEN '$startDate' AND '$endDate' AND delivery_status != '0' order by entry_date");

    $print = 'action=search&startDate=' . $startDate . '&endDate=' . $endDate . '';
    $header = 'Between ' . date('d-M-Y', strtotime($startDate)) . ' To ' . date('d-M-Y', strtotime($endDate)) . '';
} else {

    $todayStock = $obj->view_all_by_cond("vw_sell_purchase_item", "`entry_date` = CURDATE() AND delivery_status != '0'");
    $print = 'action=dtoken&date=' . date('Y-m-d') . '';
    $header = 'of Today (' . date('d-m-Y') . ') ';
}
?>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js"></script>


<div class="row" style="margin:15px; 0">
    <button type="submit" class="btn btn-success bg-slate-700 btn-block"
            onclick="printDiv('all_stock_print')">Click to Print Below Statement
    </button>
</div>

<div id="div_all" class="row" style="font-size: 12px;" >
    <div class="col-md-12">
        <div class="table-responsive" id="all_stock_print">
            <h3 class="text-center">All Stock Report</h3>
            <table class="table table-bordered table-hover table-striped" id="">
                <thead class="bg-teal-800">
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
                foreach ($todayStock as $stockItem) {
                    $bill_sell_array = explode('_', $stockItem['bill_or_sell_id']);
                    ?>
                    <tr>
                        <td class="text-center"><?php echo date('d-M-y', strtotime($stockItem['entry_date'])); ?></td>
                        <td class="text-center"><?php echo $stockItem['product_name']; ?></td>
                        <?php if ($stockItem['purchase_sell_flag'] == '1') {
                            $total_purchase_unit = $stockItem['qty'] + $total_purchase_unit;
                            $total_purchase_price = $stockItem['price'] + $total_purchase_price;
                            $total_amount_price = $stockItem['total_amount'] + $total_amount_price;
                            ?>
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
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><?php echo $stockItem['qty']; ?></td>
                            <td class="text-center"><?php echo $stockItem['price']; ?></td>
                            <td class="text-center"><?php echo $stockItem['total_amount']; ?></td>
                        <?php } ?>
                        <td class="text-center"><?php echo $stockItem['supplier_customer_name']; ?></td>

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
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        //document.body.innerHTML = originalContents;
    }

    printDiv('all_stock_print');
</script>
