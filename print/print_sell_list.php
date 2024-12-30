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

    $allSellData = $obj->view_all_by_cond("vw_sell", "entry_date BETWEEN '$startDate' AND '$endDate' order by entry_date");
    $header = "Between $startDate to $endDate .";
} else if ($action == 'dtoken') {
    $date = $_GET['date'];
    $allSellData = $obj->view_all_by_cond("vw_sell", "`entry_date` = '$date'");
    $header = "of  $date .";
} else {

    $allSellData = $obj->view_all_ordered_by("vw_sell", "`vw_sell`.`sell_id` DESC");
    $header = ".";
}

//=====================start==============================


//=======================end============================

?>

<div class="row" style="margin:15px; 0">
    <button type="submit" class="btn btn-success bg-grey-800 btn-block"
            onclick="printDiv('purchase_print')">Click to Print Below Statement
    </button>
</div>

<div class="row" id="purchase_print">
    <div class="col-md-12">
        <div class="text-center" style="margin-bottom:5px;">
            <h3>All Sell list <?php echo $header; ?></h3>
        </div>
    </div>
        <div class="col-md-12">
            <table class="table table-responsive table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-2">Customer</th>
                    <th class="text-center col-md-1">Total Qty (item)</th>
                    <th class="text-center col-md-1">Total Bill</th>
                    <th class="text-center col-md-1">Payment</th>
                    <th class="text-center col-md-1">Dues</th>
                    <th class="text-center col-md-1">Bill Date</th>
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
                    ?>
                    <tr>
                        <td>
                            <strong><?php echo $i; ?></strong>&nbsp;
                            <?php if($sell['delivery_status'] == 1){ echo '<span class="glyphicon glyphicon-ok"></span>';}?>
                        </td>
                        <td class="text-center" style="padding-top:2px">
                            <small style="padding-bottom:2px;"><?php echo isset($sell['customer_name']) ? $sell['customer_name'] : null; ?></small>(<?php echo isset($sell['customer']) ? $sell['customer'] : NULL; ?>)
                        </td>
                        <td class="text-center" style="padding-top:14px;"><?php echo isset($sell['total_qty']) ? number_format($sell['total_qty']) . ' pcs ' : NULL; ?></td>
                        <td class="text-center"><?php $sumOfTotalPrice += $sell['total_price']; echo isset($sell['total_price']) ? number_format($sell['total_price']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php $sumOfPayment += $sell['payment_recieved']; echo isset($sell['payment_recieved']) ? number_format($sell['payment_recieved']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php $sumOfDue += $sell['due_to_company']; echo isset($sell['due_to_company']) ? number_format($sell['due_to_company']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($sell['entry_date']) ? date('d-M-y', strtotime($sell['entry_date'])) : NULL; ?></td>

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