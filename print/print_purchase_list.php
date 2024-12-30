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

    $alPurchaseData = $obj->view_all_by_cond("vw_purchase", "entry_date BETWEEN '$startDate' AND '$endDate' order by entry_date");
    $header = "Between $startDate to $endDate .";
} else if ($action == 'dtoken') {
    $date = $_GET['date'];
    $alPurchaseData = $obj->view_all_by_cond("vw_purchase", "`entry_date` = '$date'");
    $header = "of  $date .";
} else {

    $alPurchaseData = $obj->view_all_ordered_by("vw_purchase", "`vw_purchase`.`bill_id` DESC");

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
            <tr>
                <th class="text-center col-md-1">SL</th>
                <th class="text-center col-md-2">Supplier</th>
                <th class="text-center col-md-1">Total Qty (item)</th>
                <th class="text-center col-md-1">Total Price</th>
                <th class="text-center col-md-1">Payment</th>
                <th class="text-center col-md-1">Dues</th>
                <th class="text-center col-md-2">Date</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $i = 0;
            $sumOfTotalPrice = 0;
            $sumOfPayment = 0;
            $sumOfDue = 0;
            foreach ($alPurchaseData as $purchase) {
                $i++;
                $purchase_id = $purchase['bill_id'];
                ?>
                <tr>
                    <td class="text-center">
                        <strong><?php echo $i; ?></strong><br>
                    </td>
                    <td class="text-center" style="padding-top:2px">
                        <small><?php echo isset($purchase['supplier_name']) ? $purchase['supplier_name'] : null; ?></small> Id: <?php echo isset($purchase['supplier']) ? $purchase['supplier'] : NULL; ?>
                    </td>
                    <td class="text-center"><?php echo isset($purchase['total_qty']) ? number_format($purchase['total_qty']) : NULL; ?></td>
                    <td class="text-center"><?php $sumOfTotalPrice += $purchase['total_price']; echo isset($purchase['total_price']) ? number_format($purchase['total_price']) . ' TK.' : NULL; ?></td>
                    <td class="text-center"><?php $sumOfPayment += $purchase['payment_recieved']; echo isset($purchase['payment_recieved']) ? number_format($purchase['payment_recieved']) . ' TK.' : NULL; ?></td>
                    <td class="text-center"><?php $sumOfDue += $purchase['due_to_company']; echo isset($purchase['due_to_company']) ? number_format($purchase['due_to_company']) . ' TK.' : NULL; ?></td>
                    <td class="text-center"><?php echo isset($purchase['entry_date']) ? date('d-M-y', strtotime($purchase['entry_date'])) : NULL; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <th colspan="3" class="text-center col-md-4">Total</th>
                <th class="text-center col-md-1"><?php echo number_format($sumOfTotalPrice); ?></th>
                <th class="text-center col-md-1"><?php echo number_format($sumOfPayment); ?></th>
                <th class="text-center col-md-1"><?php echo number_format($sumOfDue);  ?></th>
                <th colspan="2" class="col-md-5">Taka</th>

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