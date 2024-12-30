<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$notification = "";
$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;
//taking month and years
$day = date('M-Y');

if( isset($_GET['cid']) && !empty( $_GET['cid']) ){
    $cid = $_GET['cid'];
    
     $smsb = $obj->details_by_cond("sms", "status='2'");

    
    if($cid == 1){
           $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='$cid' ");
    }elseif($cid == 2){
         $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='$cid' ");
    }elseif($cid == 3){
         $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='$cid' ");
    }elseif($cid == 5){
         $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='$cid' ");
    }elseif($cid == 6){
         $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='$cid' ");
    }elseif($cid == 7){
         $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='$cid' ");
    }else{
         $customerPersonalData =$obj->view_all("tbl_customer");

    }
    
   


}else{


	die();
}
foreach ($customerPersonalData as $customer) {
        $mobile = $customer['cus_mobile_no'];

        $body = $smsb['smsbody'];

        if (!empty($mobile)) {
            //sms 
             $notification .=  $obj->smsSend($mobile,$body);
         }       
        
    
     
}

?>
<script>
    window.location = "?q=occation";
</script>