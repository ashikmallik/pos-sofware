<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customer_cat = 5; // for accounts

if (isset($_POST['search'])) {
    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));

    $todayDelivery = $obj->view_all_by_cond("tbl_delivery_item", "delivery_date BETWEEN '$startDate' AND '$endDate'");
    $todayStock = $obj->view_all_by_cond("vw_sell_purchase_item", "purchase_sell_flag=0 AND entry_date BETWEEN '$startDate' AND '$endDate' order by entry_date");

    $print = 'action=search&startDate=' . $startDate . '&endDate=' . $endDate . '';
    $header = 'Between ' . date('d-M-Y', strtotime($startDate)) . ' To ' . date('d-M-Y', strtotime($endDate)) . '';
} else {
    $date = date('Y-m-d');
    $todayDelivery = $obj->view_all_by_cond("tbl_delivery_item", "delivery_date='$date'");

    $header = 'of Today (' . date('d-m-Y') . ') ';
} ?>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js"></script>


<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-8">
        <h4><strong>Delivery Report <?php echo $header; ?> List </strong></h4>
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
                    <input class="form-control" required="required" type="text" name="startDate" value="<?php if (isset($_POST['startDate'])){echo $_POST['startDate'];}?>" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="endDate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" required="required" name="endDate" value="<?php if (isset($_POST['endDate'])){echo $_POST['endDate'];}?>" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default"><span
                        class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="example">
                <thead class="bg-teal-800">
                <tr style="font-size:12px;">
                    <th class="col-md-1">Serial</th>
                    <th class="col-md-1">Customer Name</th>
                    <th class="col-md-1">Sell ID</th>
                    <th class="col-md-3 text-center">Product Name</th>
                    <th class="col-md-1">Delivery Qty</th>
                    <th class="col-md-1">Delivery Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total_delivey = 0;
                $i=0;
                foreach ($todayDelivery as $delivery) {
                    $i++;
                    $sell_data = $obj->details_by_cond('tbl_item_with_price','item_id='.$delivery['product_id']);
                    $customer = $obj->details_by_cond('vw_sell','sell_id='.$delivery['sell_no']);
                    $total_delivey += $delivery['qty'];
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td class=""><?php echo $customer['customer_name'] ?></td>
                        <td class="text-center"><?php echo $delivery['sell_no']; ?></td>
                        <td class=""><?php echo $sell_data['item_name']; ?></td>
                        <td class="text-right"><?php echo $delivery['qty']; ?></td>
                        <td class="text-center"><?php echo date('d-m-Y',strtotime($delivery['delivery_date'])) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                <th class="col-md-1">Total</th>
                <th class="col-md-1"></th>
                <th class="col-md-1"></th>
                <th class="col-md-3"></th>
                <th class="col-md-1 text-right"><?php echo $total_delivey?></th>
                <th class="col-md-1"></th>
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

    $(document).ready(function() {
        $("#example").dataTable().fnDestroy();
        $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print Delivery Repory',
                    title: function () {
                        return "Delivery Report <?php echo $header; ?>"
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
