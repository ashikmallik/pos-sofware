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

    $billId = isset($_GET['billId']) ? $_GET['billId'] : NULL;
    $purchaseData = $obj->details_by_cond('tbl_purchase', "bill_id = '$billId'");

    $purchaseItemData = $obj->view_all_by_cond('vw_purchase_item', "bill_id = '$billId'");

    $supplierData = $obj->details_by_cond('tbl_supplier', "supplier_id = '" . $purchaseData['supplier'] . "'");

    ?>
    <!DOCTYPE html>
    <html>
    <head>

        <title>Show Item</title>

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"/>

        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

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
            .table{
                font-family: helvetica;
                font-size: 14px;
                letter-spacing: 0.05em;
            }
        </style>

    </head>
    <body>
    <div class="container">
        <div class="row" style="padding:10px; font-size: 12px;">
            <div class="col-sm-12 bg-success" style="padding:10px; margin-bottom:10px; font-size: 12px;">
                <h4 class="text-center">Sell Item for Supplier <?php echo ucwords($supplierData['supplier_name']); ?></h4>
            </div>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-slate-700">
                                <tr>
                                    <th>Sl</th>
                                    <th>Product Name</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Comission (Per Unit)</th>
                                    <th>Total Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 1;
                                $total_qty = 0;
                                $total_price = 0;
                                foreach($purchaseItemData as $purchaseItem){

                                    $total_qty += $purchaseItem['qty'];
                                    $total_price += $purchaseItem['total_amount'];
                                    ?>

                                    <tr>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $purchaseItem['product_name'];?></td>
                                        <td><?php echo number_format($purchaseItem['price']);?> taka </td>
                                        <td><?php echo number_format($purchaseItem['qty']);?> pcs </td>
                                        <td><?php echo number_format($purchaseItem['commission_per_unit']);?> taka </td>
                                        <td><?php echo number_format($purchaseItem['total_amount']);?> taka </td>
                                    </tr>

                                    <?php $i++;  } ?>
                                <tr class="bg-grey-600" style="font-size:16px;">
                                    <td class="text-center" colspan="3"> Total </td>
                                    
                                    <td><?php echo number_format($total_qty);?> pcs </td>
                                    <td class="text-center" colspan=""></td>
                                    <td><?php echo number_format($total_price);?> taka </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="button" id="close" onclick="parent.$.magnificPopup.close()" class="btn btn-default">Close</button>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php
}else{
    header("location: ../include/login.php");
}
?>