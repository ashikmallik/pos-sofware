<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;



$billId = isset($_GET['billId']) ? $_GET['billId'] : null;

$purchaseData = $obj->details_by_cond('tbl_purchase', "bill_id = $billId");

$purchaseItemData = $obj->view_all_by_cond('vw_purchase_item', 'bill_id = ' . $purchaseData['bill_id'] . ' ORDER BY `vw_purchase_item`.`id` ASC');

$supplier_data = $obj->details_by_cond('tbl_supplier', "supplier_id = '" . $purchaseData['supplier'] . "'");

?>
<!--===================end Function===================-->


<div class="col-md-12 bg-slate-800 text-center" style="margin-bottom:25px;">
    <h4 class="col-md-10">View purchase Item Details for supplier <?php echo $supplier_data['supplier_name']; ?></h4>
    <div class="col-md-2" style="margin-top:5px;">
        <a class="btn btn-sm btn-success" target="_blank" href="pdf/bill.php?billId=<?php echo $billId ?>">
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
                foreach ($purchaseItemData as $purchaseItem) {
                    $rowNum++;
                    ?>

                    <tr id="row_1">
                        <td>
                            <?php echo $rowNum; ?>
                        </td>
                        <td>
                            <?php echo isset($purchaseItem['product_name']) ? $purchaseItem['product_name'] : null; ?>
                        </td>
                        <td>
                            <?php echo isset($purchaseItem['price']) ? $purchaseItem['price'] : null; ?>
                        </td>
                        <td class="text-center">
                            <?php echo isset($purchaseItem['qty']) ? $purchaseItem['qty'] : null; ?>
                        </td>
                        <td>
                            <?php echo isset($purchaseItem['total_amount']) ? $purchaseItem['total_amount'] : null; ?>
                        </td>
                        <td>
                            <?php echo isset($purchaseItem['entry_date']) ? date('d-M-y', strtotime($purchaseItem['entry_date'])) : null; ?>
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
                    <a class="btn btn-sm btn-primary" href="?q=view_all_purchase">
                        View All Purchase <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </div>
            </div>
            <hr>
        </div>
    </div>
</div>

