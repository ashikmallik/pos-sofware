<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$customer_cat = 5; // for accounts

$productId=isset($_GET['product'])?$_GET['product']:0;
$balanceBroughtDown = 0;

if (isset($_GET['search'])) {
    $startDate = date('Y-m-d', strtotime($_GET['startDate']));
    $endDate = date('Y-m-d', strtotime($_GET['endDate']));
    $todayStock = $obj->view_all_by_cond("vw_sell_purchase_item", "product_id=$productId and entry_date BETWEEN '$startDate' AND '$endDate' order by created_at ASC");
    $header = 'Between ' . date('d-M-Y', strtotime($startDate)) . ' To ' . date('d-M-Y', strtotime($endDate)) . '';
    
    
    //previous
    $prevStock = $obj->view_all_by_cond("vw_sell_purchase_item", "product_id=$productId and entry_date < '$startDate' order by created_at ASC");
    foreach ($prevStock as $prevStockItem) {
        $bill_sell_id = explode('_', $prevStockItem['bill_or_sell_id']);
        $balanceBroughtDown += (($bill_sell_id[0]=='p')?$prevStockItem['qty']:0) - (($bill_sell_id[0]=='s')?$prevStockItem['qty']:0);
    }

} else {

    $todayStock = $obj->view_all_by_cond("vw_sell_purchase_item", "product_id=$productId order by created_at ASC");
    $header = '';
}
$productData = $obj->details_by_cond("tbl_item_with_price", "`item_id` = '$productId'");

 $stock=$totalpurchase=$totalSell=0;
//  $stock += $balanceBroughtDown;

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">



<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-8">
        <h4><strong>View product Ladger <?php echo $header; ?> for   <?php echo @$productData['item_name']; ?> </strong></h4>
    </div>
</div>

<div class="col-md-12 bg-slate-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="GET">
        <input type="hidden" name="q" value="view_product_ledger"/>
        <input type="hidden"  name="product" value="<?php echo $productId; ?>"/>
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
                    <th class="col-md-1">Customer / Supplier</th>
                    <th class="col-md-1">Type</th>
                    <th class="col-md-1">Total Purchase</th>
                    <th class="col-md-1">Total Sell</th>
                    <th class="col-md-1">Unit</th>
                    <th class="col-md-1">Qty</th>
                    <th class="col-md-1">Available</th>
                    <th class="col-md-1"></th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center"> </td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">Previous</td>
                        <td class="text-center"><?php echo $balanceBroughtDown; ?></td>
                        <td class="text-center"></td>
                    </tr>
                <?php
                
                foreach ($todayStock as $stockItem) {
                    $bill_sell_id = explode('_', $stockItem['bill_or_sell_id']);
                    $puccheaseId=($bill_sell_id[0]=='p')?$bill_sell_id[1]:0;
                    $sellId=($bill_sell_id[0]=='s')?$bill_sell_id[1]:0;
                    $type = ($bill_sell_id[0]=='p')?'Purchase':'Sell';
                    $totalpurchase += $pAmount =($bill_sell_id[0]=='p')?$stockItem['total_amount']:0;
                    $totalSell += $sAmount =($bill_sell_id[0]=='s')?$stockItem['total_amount']:0;
                    $stock += (($bill_sell_id[0]=='p')?$stockItem['qty']:0)-(($bill_sell_id[0]=='s')?$stockItem['qty']:0);
                    ?>
                    
                    <tr>
                        <td class="text-center"><?php echo date('d-M-y', strtotime($stockItem['entry_date'])); ?></td>
                       
                        <td class="text-center"><?php echo $stockItem['supplier_customer_name']; ?><br> <a href="?q=customer_ledger&customerId=<?php echo $stockItem['supplier_customer']; ?>" ><?php echo $stockItem['supplier_customer']; ?> </a></td>
                        <td class="text-center"><?php echo $type; ?></td>
                        <td class="text-center"><?php echo $pAmount; ?></td>
                        <td class="text-center"><?php echo $sAmount; ?></td>
                        <td class="text-center"><?php echo $stockItem['price']; ?></td>
                        <td class="text-center"><?php echo $stockItem['qty']; ?></td>
                        <td class="text-center"><?php echo $stock; ?></td>
                        <td class="text-center">
                            <?php if ($bill_sell_id[0]=='p') { ?>
                                <a class="btn btn-sm btn-default" target="_blank" href="pdf/bill.php?billId=<?php echo $puccheaseId; ?>"> <span class="glyphicon glyphicon-print"></span> Print</a>
                            <?php } else { ?>
                                <a class="btn btn-sm btn-default" target="_blank"  href="pdf/invoice.php?invoiceId=<?php echo $sellId; ?>"> <span class="glyphicon glyphicon-print"></span> Print </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="text-center"></th>
                    <th class="text-center "></th>
                     <th class="text-center ">Total</th>
                    <th class="text-center "><?php  echo number_format($totalpurchase); ?></th>
                    <th class="text-center "><?php  echo number_format($totalSell); ?></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class=""><?php echo $stock; ?></th>
                    <th class="text-center "></th>
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
                    text: 'Print Product History For  <?php echo @$productData['item_name']; ?>',
                    footer: true,
                    title: function () {
                        return "Print Product History <?php echo $header; ?> For <?php echo @$productData['item_name']; ?>"
                    },
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
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
