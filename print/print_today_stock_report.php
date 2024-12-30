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


if ($action == 'search') {

    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    $todayStock = $obj->view_all_by_cond("vw_sell_purchase_item", "entry_date BETWEEN '$startDate' AND '$endDate' AND delivery_status != '0' order by entry_date");

    $header = 'Between ' . date('d-M-Y', strtotime($startDate)) . ' To ' . date('d-M-Y', strtotime($endDate)) . '';

} else if ($action == 'dtoken') {
    $date = $_GET['date'];
    $todayStock = $obj->view_all_by_cond("vw_sell_purchase_item", "`entry_date` = CURDATE() AND delivery_status != '0'");
    $header = 'of Today (' . date('d-m-Y') . ') ';
} else {

    $alPurchaseData = $obj->view_all("vw_purchase");
    $header = ".";
}


?>

<div class="row" style="margin:15px; 0">
    <button type="submit" class="btn btn-primary bg-teal-800 btn-block"
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
        <table class="table table-responsive table-bordered table-hover table-striped">
            <thead>
            <tr style="font-size:12px;">
                <th class="col-md-1">Date</th>
                <th class="col-md-2">Product Name</th>
                <th class="col-md-1">Purchase Unit</th>
                <th class="col-md-1">Purchase Price</th>
                <th class="col-md-1">Total Purchase</th>
                <th class="col-md-1">Sell Unit</th>
                <th class="col-md-1">Sell Price</th>
                <th class="col-md-1">Total Sell</th>
                <th class="col-md-2">Customer / Supplier</th>
            </tr>
            </thead>

            <tbody>
            <?php

            foreach ($todayStock as $stockItem) {

                $bill_sell_array = explode('_', $stockItem['bill_or_sell_id']);

                ?>
                <tr>
                    <td class="text-center"><?php echo date('d-M-y', strtotime($stockItem['entry_date'])); ?></td>
                    <td class="text-center"><?php echo $stockItem['product_name']; ?></td>
                    <?php if ($stockItem['purchase_sell_flag'] == '1') { ?>
                        <td class="text-center"><?php echo $stockItem['qty']; ?></td>
                        <td class="text-center"><?php echo $stockItem['price']; ?></td>
                        <td class="text-center"><?php echo $stockItem['total_amount']; ?></td>
                        <td colspan="3"></td>
                    <?php } else { ?>
                        <td colspan="3"></td><!-- For show Sell unit -->
                        <td class="text-center"><?php echo $stockItem['qty']; ?></td>
                        <td class="text-center"><?php echo $stockItem['price']; ?></td>
                        <td class="text-center"><?php echo $stockItem['total_amount']; ?></td>
                    <?php } ?>
                    <td class="text-center"><?php echo $stockItem['supplier_customer_name']; ?></td>
                </tr>
            <?php } ?>
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
        //document.body.innerHTML = originalContents;
    }

    printDiv('purchase_print');
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