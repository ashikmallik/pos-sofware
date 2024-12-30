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

    $allPurchaseQty  = $obj->details_by_cond("vw_purchase_material_stock", "`product_id`=$itemId");

    $allUsedQty = $obj->details_by_cond("vw_used_material", "`product_id`=$itemId");

    $purchaseQty = isset($allPurchaseQty['total_purchase_qty']) ? $allPurchaseQty['total_purchase_qty'] : 0 ;

    $usedQty = isset($allUsedQty['qty']) ? $allUsedQty['qty'] : 0 ;

    $stock = $purchaseQty - $usedQty ;

    //    $orderRequest = $stock - $qty;

    echo json_encode(['stock_qty' => $stock] );

}