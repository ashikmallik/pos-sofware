<?php
session_start();

$userid = $user_id = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;

if (!empty($_SESSION['UserId'])) {

//========================================
    include '../model/Controller.php';
    include '../model/FormateHelper.php';

    $formater = new FormateHelper();
    $obj = new Controller();
//========================================
    date_default_timezone_set('Asia/Dhaka');
    $date = date('Y-m-d');

    $itemId = $_GET['item'];
    $supplier = $_GET['supplier'];

    $allStockItemData  = $obj->get_sum_data("vw_purchase_item", "qty","`product_id`=$itemId AND supplier='$supplier'");

    $allReturnData = $obj->get_sum_data("tbl_return", "return_qty","`product_id`=$itemId AND cus_or_sup_id='$supplier'");

    //    $orderRequest = $stock - $qty;

    echo json_encode(['total_purchase' => $allStockItemData-$allReturnData] );

}