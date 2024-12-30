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
    $customer = $_GET['customer'];

    $allStockItemData_pre  = $obj->get_sum_data("vw_sell_item", "qty","`product_id`=$itemId AND customer='$customer'");
    $return_sell  = $obj->get_sum_data('tbl_return','return_qty',"type=0 AND product_id='$itemId' and cus_or_sup_id='$customer'");
   $allStockItemData = $allStockItemData_pre - $return_sell;
    //    $orderRequest = $stock - $qty;

    echo json_encode(['total_sell' => $allStockItemData] );

}