<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;


$sellId = isset($_GET['sellId']) ? $_GET['sellId'] : null;

$sellData = $obj->details_by_cond('tbl_sell', "sell_id = $sellId");

$sellItemData = $obj->view_all_by_cond('vw_sell_item', 'sell_id = ' . $sellData['sell_id'] . ' ORDER BY `vw_sell_item`.`id` ASC');

$customer_data = $obj->details_by_cond('tbl_customer', "cus_id = '" . $sellData['customer'] . "'");

?>
<!--===================end Function===================-->

<div class="col-md-12 bg-slate-800 text-center" style="margin-bottom:25px;">
    <h4 class="col-md-10">View Sell Item Details for Customer <?php echo $customer_data['cus_name']; ?></h4>
    <div class="col-md-2" style="margin-top:5px;">
        <a class="btn btn-sm btn-success" target="_blank" href="pdf/invoice.php?invoiceId=<?php echo $sellId ?>">
            <span class="glyphicon glyphicon-print"></span> Print Invoice
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-10  col-md-offset-1" style="font-size: 12px;">
        <div class="col-md-12">
            <table class="table table-bordered" style="margin-bottom:0px;">
                <thead>
                <tr>
                    <th class="col-md-1 text-center">SL</th>
                    <th class="col-md-3 text-center">Product</th>
                    <th class="col-md-2 text-center">Unit Price</th>
                    <th class="col-md-2 text-center">Unit Qty</th>
                    <th class="col-md-2 text-center">Total</th>
                    <th class="col-md-2 text-center">Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $rowNum = 0;
                $total_amount = 0;
                foreach ($sellItemData as $sellItem) {
                    $rowNum++;
                    ?>

                    <tr id="row_1">
                        <td>
                            <?php echo $rowNum; ?>
                        </td>
                        <td>
                            <?php echo isset($sellItem['product_name']) ? $sellItem['product_name'] : null; ?>
                        </td>
                        <td>
                            <?php echo isset($sellItem['price']) ? $sellItem['price'] : null; ?>
                        </td>
                        <td class="text-center">
                            <?php echo isset($sellItem['qty']) ? $sellItem['qty'] : null; ?>
                        </td>
                        <td>
                            <?php echo isset($sellItem['total_amount']) ? $sellItem['total_amount'] : null; ?>
                        </td>
                        <td>
                            <?php echo isset($sellItem['entry_date']) ? date('d-M-y', strtotime($sellItem['entry_date'])) : null; ?>
                        </td>

                    </tr>
                <?php }
                ?>
                </tbody>

            </table>
            <hr>
            <div class="row">
                <div class="col-md-6 text-center">
                    <a class="btn btn-sm btn-primary" href="?q=all_stock_item">
                        <span class="glyphicon glyphicon-chevron-left"></span> Back To All Stock
                    </a>
                </div>
                <div class="col-md-6 text-center">
                    <a class="btn btn-sm btn-primary" href="?q=view_all_sell">
                        View All Sell <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </div>
            </div>
            <hr>
        </div>
    </div>
</div>

