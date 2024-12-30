<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customer_cat = 5; // for accounts

if(isset($_POST['search'])){

    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));

    $todaySell = $obj->view_all_by_cond("vw_sell", "entry_date BETWEEN '$startDate' AND '$endDate' order by entry_date");
    $print = 'action=search&startDate='.$startDate.'&endDate='.$endDate.'';
    $header = 'Between '.date('d-M-Y', strtotime($startDate)).' To '.date('d-M-Y', strtotime($endDate)).'';
}else if(isset($_GET['dtoken'])){

    $date = $_GET['dtoken'];
    $todaySell = $obj->view_all_by_cond("vw_sell", "`entry_date` = '$date'");
    $print = 'action=dtoken&date='.$date.'';
    $header = 'of '.date('d-M-Y', strtotime($date));
} else {

    $todaySell = $obj->view_all_by_cond("vw_sell", "`entry_date` = CURDATE()");
    $print = 'action=dtoken&date='.date('Y-m-d').'';
    $header = 'of Today ('.date('d-m-Y').') ';
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js"></script>


<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-8">
        <h4><strong>View All Sell <?php echo $header;?> List </strong></h4>
    </div>

    <div class="col-md-4" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>
            <a href="?q=print_sell&<?php echo $print; ?>" target="_blank" class="btn btn-primary btn-sm pull-right">Print Sell List</a>
        <?php } ?>
    </div>
</div>

<div class="col-md-12 bg-slate-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateMonth">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" required="required" type="text" name="startDate">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="damageRate">Select End Date</label>
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
</div>

<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-teal-800">
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-2">Supplier</th>
                    <th class="text-center col-md-1">Total Qty (item)</th>
                    <th class="text-center col-md-1">Total Price</th>
                    <th class="text-center col-md-1">Payment</th>
                    <th class="text-center col-md-1">Dues</th>
                    <th class="text-center col-md-2">Date</th>
                    <th class="text-center col-md-3">Action</th>

                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $total_qty = 0;
                $total_price = 0;
                $total_payment = 0;
                $total_dues = 0;
                foreach ($todaySell as $sell) {
                    $i++;
                    $total_qty = $sell['total_qty'] + $total_qty;
                    $total_price = $sell['total_price'] + $total_price;
                    $total_payment = $sell['payment_recieved'] + $total_payment;
                    $total_dues = $sell['due_to_company'] + $total_dues;
                    $sell_id = $sell['sell_id'];
                    $iframButton = '<button href="view/view_sell_item.php?invoiceId=' . $sell_id . '" class="btn btn-info bg-slate-700 btn-xs open-popup-link" data-effect="mfp-zoom-in">' . number_format($sell['total_qty']) . ' pcs </a></button>'; ?>
                    <tr>
                        <td class="text-center">
                            <strong><?php echo $i; ?></strong><br>
                        </td>
                        <td class="text-center" style="padding-top:2px">
                            <strong="padding-bottom:2px;"><?php echo isset($sell['customer_name']) ? $sell['customer_name'] : null; ?></strong>
                            <br>
                            <a class="btn btn-xs btn-default" href="?q=customer_ledger&customerId=<?php echo isset
                            ($sell['customer']) ? $sell['customer'] : NULL; ?>"><?php echo isset($sell['customer']) ? $sell['customer'] : NULL; ?></a>
                        </td>
                        <td class="text-center"><?php echo isset($sell['total_qty']) ? $iframButton : NULL; ?></td>
                        <td class="text-center"><?php echo isset($sell['total_price']) ? number_format($sell['total_price']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($sell['payment_recieved']) ? number_format($sell['payment_recieved']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($sell['due_to_company']) ? number_format($sell['due_to_company']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($sell['entry_date']) ? date('d-M-y', strtotime($sell['entry_date'])) : NULL; ?></td>
                        <td class="text-center">
                            <div style="margin-top:5px">
                                <?php echo '<a type="button" href="?q=edit_sell&invoiceId=' . $sell['sell_id'] . '" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Edit</a>'; ?>
                                <a type="button" target="_blank" href="pdf/invoice.php?invoiceId=<?php echo $sell_id ?>"
                                   class="btn btn-success btn-xs">
                                    <span class="glyphicon glyphicon-print"></span> Print</a>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td><?php echo number_format($total_qty) ?></td>
                    <td><?php echo number_format($total_price) ?></td>
                    <td><?php echo number_format($total_payment) ?></td>
                    <td><?php echo number_format($total_dues) ?></td>
                    <td></td>
                    <td></td>
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

        $(document).on('mouseover', 'tbody tr td [data-toggle="tooltip"]', function () {
            $('tbody tr td [data-toggle="tooltip"]').tooltip();
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
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>