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

    $invoiceId = isset($_GET['invoiceId']) ? $_GET['invoiceId'] : NULL;
    $AllItems = $obj->view_all('tbl_item_with_price');
    $saleData = $obj->details_by_cond('tbl_sell', "sell_id = '$invoiceId'");
    if (isset($_GET['dateMonth'])){
        $dateMonth = $_GET['dateMonth'];
        $dateYear = $_GET['dateYear'];
    }else{
        $dateMonth = date('m');
        $dateYear = date('Y');
    }
    $saleItemData = $obj->view_all_by_cond('vw_sell_item', "customer='".$_GET['cusid']."' AND MONTH(entry_date)=".$dateMonth." AND YEAR(entry_date)=".$dateYear." ");
    $customerData = $obj->details_by_cond('tbl_customer', "cus_id = '" . $_GET['cusid'] . "'");

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
                <h4 class="text-center">Sell Item for Customer <?php echo ucwords($customerData['cus_name']); ?></h4>
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
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 1;
                                $total_qty = 0;
                                $total_price = 0;
                                foreach($AllItems as $Item){
                                    $sellItemData = $obj->view_all_by_cond("vw_sell_item", "sell_id=$invoiceId");
                                    $unit = isset($sellItemData[0]['unit'])? $sellItemData[0]['unit']: NULL;

                                    $total_price_sum = $obj->get_avg_data('vw_sell_item','price',"product_id=".$Item['item_id']." AND customer='".$_GET['cusid']."' AND MONTH(entry_date)=".$dateMonth." AND YEAR(entry_date)=".$dateYear." ");
                                    $total_qty_sum = $obj->get_sum_data('vw_sell_item','qty',"product_id=".$Item['item_id']." AND customer='".$_GET['cusid']."' AND MONTH(entry_date)=".$dateMonth." AND YEAR(entry_date)=".$dateYear." ");
                                    $total_price = ($total_price_sum*$total_qty_sum) + $total_price;
                                    $total_qty = $total_qty_sum+$total_qty ;
                                ?>

                                    <tr>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $Item['item_name'];?></td>
                                        <td><?php echo $total_price_sum;?> taka </td>
                                        <td><?php echo number_format($total_qty_sum).' '.$unit;?></td>
                                        <td><?php echo number_format($total_price_sum*$total_qty_sum);?> taka </td>
                                    </tr>

                                <?php $i++;  } ?>
                                    <tr class="bg-grey-600" style="font-size:16px;">
                                        <td class="text-center" colspan="3"> Total </td>
                                        <td><?php echo number_format($total_qty).' '.$unit;?></td>
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