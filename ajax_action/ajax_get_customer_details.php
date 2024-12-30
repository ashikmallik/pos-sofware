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

    if (isset($_GET['cusid'])) {

        $cusid = $_GET['cusid'];
        $cutomer =$obj->get_single(" SELECT * FROM  tbl_customer WHERE cus_id = '$cusid' ");


        echo json_encode(['cutomer' => $cutomer]);

    }elseif(isset($_GET['supid'])) {

        $supid = $_GET['supid'];
        $supplier =$obj->get_single(" SELECT * FROM  tbl_supplier WHERE supplier_id = '$supid' ");
        
        echo json_encode(['supplier' => $supplier]);
    }else{
        echo json_encode(['supplier' =>'notfound']);
    }

}
?>