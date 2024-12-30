<?php

session_start();

$user_id = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$FullName = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : NULL;
$UserName = isset($_SESSION['UserName']) ? $_SESSION['UserName'] : NULL;
$PhotoPath = isset($_SESSION['PhotoPath']) ? $_SESSION['PhotoPath'] : NULL;
$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;

if (!empty($_SESSION['UserId'])) {

    include '../model/Controller.php';
    include '../model/FormateHelper.php';

    $formater = new FormateHelper();
    $obj = new Controller();

    $stockId = isset($_GET['stockId']) ? $_GET['stockId'] : NULL;

    $productInfo = $obj->details_by_cond('tbl_item_with_price', "item_id = '$stockId'");
//
    $purchaseSaleItemData = $obj->view_all_by_cond('vw_sell_purchase_item', "product_id = '$stockId'  ORDER BY `vw_sell_purchase_item`.`entry_date` ASC");


    ?>
    <!DOCTYPE html>
    <html>
    <head>

        <title>Stock Item</title>

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"/>

        <style>
            .bg-slate-700 {
                background-color: #455a64;
                border-color: #455a64;
                color: #fff;
            }

            .bg-grey-600 {
                background-color: #545455 !important;
                border-color: #666;
                color: #fff !important;
            }

            .table {
                font-family: helvetica;
                font-size: 14px;
                letter-spacing: 0.05em;
            }
        </style>

    </head>
    <body>
    <div class="container">
        <div class="row" style="padding:10px 0; font-size: 12px;">
            <div class="col-sm-12 bg-slate-700" style="padding:10px; margin-bottom:10px; font-size: 12px;">
                <h4 class="text-center"> Sell and Purchase History of <?php echo ucwords($productInfo['item_name']); ?></h4>
            </div>
            <div class="col-sm-12">
                <table class="table table-striped table-bordered">
                    <thead class="bg-grey-600">
                        <tr style="font-size:12px;">
                            <th class="col-md-1" rowspan="2">Sl</th>
                            <th class="col-md-1" rowspan="2">Date</th>
                            <th class="col-md-2 text-center" colspan="3">Receipts / Purchase</th>
                            <th class="col-md-2 text-center" colspan="2">Chalan / Sell</th>
                            <th class="col-md-1" rowspan="2">Present Stock</th>
                            <th class="col-md-2" rowspan="2">Customer/Supplier</th>
                        </tr>
                        <tr style="font-size:12px;">
                            <th>Unit</th>
                            <th>Purchase No</th>
                            <th>Total</th>
                            <th>Unit</th>
                            <th>Sale No</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $present_stock = 0;
                    $previous_stock = 0;
                    $total_reciept = 0;
                    $total_chalan = 0;
                    $total_receipt = 0;
                    $total_purchase = 0;
                    foreach ($purchaseSaleItemData as $stockItem) {

                        $previous_stock = $present_stock;
                        $total_sell = 0;
                        $bill_sell_array = explode('_', $stockItem['bill_or_sell_id']);

                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i; ?></td>
                            <td class="text-center"><?php echo date('d-M-y', strtotime($stockItem['entry_date'])); ?></td>

                            <?php if($bill_sell_array[0] == 'p') { ?>
                                <td class="text-center"><?php echo $total_purchase = $stockItem['qty']; ?></td>
                                <td class="text-center"><a target="_blank" href="../index.php?q=view_single_purchase&billId=<?php echo $bill_sell_array[1]; ?>" class="btn btn-primary bg-slate-700 btn-xs">Purchase <?php echo $bill_sell_array[1]; ?></a></td>
                                <td class="text-center"><?php echo $total_receipt = $total_receipt + $total_purchase ; ?></td>
                                <td colspan="2"></td>
                            <?php } else{ ?>
                                <td colspan="3"></td>
                                <td class="text-center"><?php $total_chalan += $stockItem['qty']; echo $stockItem['qty']; ?></td>
                                <td class="text-center"><a target="_blank" href="../index.php?q=view_single_sell&sellId=<?php echo $bill_sell_array[1]; ?>" class="btn btn-primary bg-slate-700 btn-xs">Sell Id <?php echo $bill_sell_array[1]; ?></a></td>

                            <?php } ?>

                            <td class="text-center"><strong><?php $present_stock = $total_receipt - $total_chalan; echo $present_stock; ?></strong></td>
                            <td class="text-center"><?php echo $stockItem['supplier_customer_name']; ?></td>
                        </tr>
                        <?php $i++;
                    } ?>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-default btn-block">Press Close to exit the page</button>
            <hr>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="button" id="close" onclick="parent.$.magnificPopup.close()" class="btn btn-default">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php
} else {
    header("location: ../include/login.php");
}
?>