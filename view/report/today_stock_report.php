<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customer_cat = 5; // for accounts

if (isset($_POST['search'])) {

    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));

    $todayStock = $obj->view_all_by_cond("vw_sell_purchase_item", "entry_date BETWEEN '$startDate' AND '$endDate' order by entry_date");

    $print = 'action=search&startDate=' . $startDate . '&endDate=' . $endDate . '';
    $header = 'Between ' . date('d-M-Y', strtotime($startDate)) . ' To ' . date('d-M-Y', strtotime($endDate)) . '';
} else {

    $todayStock = $obj->view_all_by_cond("vw_sell_purchase_item", "`entry_date` = CURDATE()");
    $print = 'action=dtoken&date=' . date('Y-m-d') . '';
    $header = 'of Today (' . date('d-m-Y') . ') ';
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js"></script>


<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-8">
        <h4><strong>View All Stock <?php echo $header; ?> List </strong></h4>
    </div>

    <div class="col-md-4" style="padding-top:5px;">

    </div>
</div>

<div class="col-md-12 bg-slate-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="startDate">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" required="required" type="text" name="startDate" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="endDate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" required="required" name="endDate" autocomplete="off">
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
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable-btn">
                <thead class="bg-teal-800">
                <tr style="font-size:12px;">
                    <th class="col-md-1">Date</th>
                    <th class="col-md-3">Product Name</th>
                    <th class="col-md-1">Purchase Unit</th>
                    <th class="col-md-1">Purchase Price</th>
                    <th class="col-md-1">Total Purchase</th>
                    <th class="col-md-1">Sell Qty</th>
                    <th class="col-md-1">Delivery Qty</th>
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
                $total_delivey = 0;
                foreach ($todayStock as $stockItem) {
                    $bill_sell_array = explode('_', $stockItem['bill_or_sell_id']);
                    // $today_date = date('Y-m-d');
                    // $delivery = $obj->get_sum_data('tbl_delivery_item','qty',"product_id=".$stockItem['product_id']." AND sell_no='".trim($stockItem['bill_or_sell_id'], 's_')."' AND delivery_date='".$today_date."'");
                    $today_date = date('Y-m-d', strtotime($stockItem['entry_date']));
                    $delivery = $obj->get_sum_data('tbl_delivery_item','qty',"product_id=".$stockItem['product_id']." AND delivery_date='".$today_date."'");
                    // if ($delivery==0){
                    //     continue;
                    // }
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
                            <td></td>
                        <?php } elseif($stockItem['purchase_sell_flag'] == '0') {
                            $total_sell_unit = $stockItem['qty'] + $total_sell_unit;
                            $total_sell_price = $stockItem['price'] + $total_sell_price;
                            $total_sell = $stockItem['total_amount'] + $total_sell;
                            ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><?php echo $stockItem['qty']; ?></td>
                            <td class="text-center"><?php
                                echo $delivery ;
                                $total_delivey = $delivery +$total_delivey;
                                ?></td>
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
                    <th class="text-center "><?php echo number_format($total_delivey);  ?></th>
                    <th class="text-center "><?php echo number_format($total_sell_price);  ?></th>
                    <th colspan="2" class=""><?php echo number_format($total_sell);  ?></th>
                    <th colspan="" class="">Taka</th>
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
    });

    $(document).ready(function() {
        $("#datatable-btn").dataTable().fnDestroy();
        $('#datatable-btn').DataTable( {
            "ordering": false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print Stock Report',
                    footer: true,
                    title: function () {
                        return "Print Stock Report <?php echo $header; ?>"
                    },
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9]
                    },
                    customize: function (win) {
                        $(win.document.body).css('font-size', '12px');
                        $(win.document.body).find('h1').addClass('text-center').css('font-size', '20px');
                        $(win.document.body).find('table').addClass('container').css('font-size', 'inherit');
                        $(win.document.body).find('table').removeClass('table-bordered');
                    }
                }
            ]
        } );
    } );

</script>

<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>
