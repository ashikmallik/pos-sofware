<?php
session_start();
/**
 * Created by mehedi
 * Date: 21/02/17
 * Reason: For return total due , its a time consuming process, so we process so page loading not wait for this
 */
$user_id = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$FullName = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : NULL;
$UserName = isset($_SESSION['UserName']) ? $_SESSION['UserName'] : NULL;
$PhotoPath = isset($_SESSION['PhotoPath']) ? $_SESSION['PhotoPath'] : NULL;
$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;

//========================================
include '../model/Controller.php';
include '../model/FormateHelper.php';

$formater = new FormateHelper();
$obj = new Controller();
//========================================

date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$notification = "";
//taking month and years
$day = date('M-Y');


function get_customer_dues($id)
{
    $obj = new Controller();
    $details = $obj->details_by_cond("tbl_agent", "ag_id='$id'");
    extract($details);
    $bday1 = $details['entry_date'];
    $bday = new DateTime($bday1);
    $today = new DateTime(date('Y-m-d', time())); // for testing purposes
    $diff = $today->diff($bday);
    //printf('%d years, %d month, %d days', $diff->y, $diff->m, $diff->d);
    $total = 0;
    $serviceamount1 = 0;
    foreach ($obj->view_all_by_cond("tbl_agent", "ag_id='$id'") as $details1) {
        extract($details1);
        $serviceamount1 += ($details1['taka']);
    }
    if ($diff->m != 0) {
        $serviceamount2 = ($serviceamount1 * $diff->y * 12) + ($serviceamount1 * $diff->m);
    }
    //else if($diff->m!=0 and $diff->d!=0){
    // $serviceamount2=($serviceamount1*$diff->y*12)+($serviceamount1*$diff->m)+$serviceamount1;
    //  }
    else {
        $serviceamount2 = 0;//$serviceamount1;
    }
    foreach ($obj->view_all_by_cond("vw_account", "agent_id='$id' order by acc_id") as $customer_info) {
        extract($customer_info);
        $total += $customer_info['acc_amount'];
    }
    $bonus = 0;
    foreach ($obj->view_all_by_cond("bonus", "customerID='$id' ") as $customer_bonus) {
        extract($customer_bonus);
        $bonus += $customer_bonus['amount'];
    }
    return $dueamount = $serviceamount2 - ($total + $bonus);
}

$allAgentData = $obj->view_all_by_cond("tbl_agent", "ag_status='1' and pay_status='1' AND 
        due_status='0'");

$total_due_amount = 0;
foreach ($allAgentData as $value) {
    $all_due = get_customer_dues(isset($value['ag_id']) ? $value['ag_id'] : NULL);
    $total_due_amount += $all_due;
}

$total_due_for_month = $obj -> view_selected_field_by_cond('tbl_agent','SUM(taka) AS `total_amount`','MONTH
(entry_date) != MONTH(CURRENT_DATE) AND ag_status=1');

echo json_encode(array("duePayment"=>$total_due_amount,"totalPayment"=>$total_due_for_month[0]['total_amount']));