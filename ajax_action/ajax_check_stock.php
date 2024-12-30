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

    $allStockItemData  = $obj->details_by_cond("vw_sell_purchase_stock", "`product_id`=$itemId");

    $delevery_product = $obj->get_sum_data("tbl_delivery_item","qty"," `product_id`='$itemId'");

    $return_purchase  = $obj->get_sum_data('tbl_return','return_qty','type=1 AND product_id='.$itemId);
    $return_sell  = $obj->get_sum_data('tbl_return','return_qty','type=0 AND product_id='.$itemId);

    $sellButNotDeleverdData = $obj->details_selected_field_by_cond("tbl_sell_item", "SUM(qty) as total_qty", "`product_id`=$itemId AND  delivery_status = 0");
    $sellButNotDeleverdQty = $sellButNotDeleverdData['total_qty'];

    $purchaseQty = isset($allStockItemData['total_purchase_qty']) ? $allStockItemData['total_purchase_qty'] : 0 ;

    $deleveryQty = isset($allStockItemData['total_sell_qty']) ? $allStockItemData['total_sell_qty'] : 0 ;

    $stock = $purchaseQty - $delevery_product - $return_purchase + $return_sell;

    //    $orderRequest = $stock - $qty;
    
    
    // echo "p=$purchaseQty, s=$deleveryQty, d=$delevery_product,  sr=$return_sell , pr=$return_purchase ";

    echo json_encode(['stock_qty' => $stock] );

}