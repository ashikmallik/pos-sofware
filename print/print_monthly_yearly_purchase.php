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

if ($action == 'monthly') {
    $monthDate = $_GET['monthDate'];
    $alPurchaseData = $obj->view_all_by_cond("vw_purchase_dailly", "MONTH(entry_date)=MONTH('$monthDate') and YEAR
    (entry_date)= YEAR('$monthDate') order by entry_date");
    $header = "of  ".date('M Y',strtotime($monthDate))." .";

} else if ($action == 'yearly') {
    $yearDate = $_GET['yearDate'];
    $alPurchaseData = $obj->view_all_by_cond("vw_purchase_monthly", "YEAR(entry_date)='$yearDate' order by entry_date");
    $header = "of  ".date('Y',strtotime($yearDate))." .";
} else {

    $alPurchaseData = $obj->view_all_by_cond("vw_purchase_dailly", "MONTH(entry_date)=MONTH(CURDATE()) and YEAR
    (entry_date)= YEAR(CURDATE()) order by entry_date");
    $header = "of this year";
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
                <thead>
                <tr>
                    <th class="text-center">SL</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Total Qty</th>
                    <th class="text-center">Total Price</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                foreach ($alPurchaseData as $purchase) {
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td class="text-center">
                            <?php echo isset($purchase['entry_date']) ? date('d-M-y', strtotime($purchase['entry_date'])) : NULL; ?>
                        </td>
                        <td class="text-center"><?php echo isset($purchase['total_qty']) ? number_format($purchase['total_qty']) . ' pcs' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($purchase['total_price']) ? number_format($purchase['total_price']) . ' TK.' : NULL; ?></td>

                    </tr>
                    <?php
                }
                ?>
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