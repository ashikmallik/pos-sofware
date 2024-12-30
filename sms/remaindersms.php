<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$notification = "";
$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;
//taking month and years
$day = date('M-Y');

if( isset($_GET['installmentdue']) ){

    extract($_GET);
    $date = $_GET['date'];
    $installmentdue = $_GET['installmentdue'];
    $installment = $_GET['installment'];
    $mobile = $_GET['mobile'];

    // echo  $date ;

    $currentinstllmentday= date('d',strtotime($date));
    $currentinstllmentyear = date('Y',strtotime($date));
    $currentinstllmentmonth = date('m',strtotime($date));
    $totalmonth =($currentinstllmentmonth+$installment+1) ;
   
    if($totalmonth >12){
     $newyear =  $currentinstllmentyear+1;
     $month = $totalmonth  % 12;

     $nextdate = $currentinstllmentday.'-'.$month.'-'.$newyear;

    }else{
        $newyear =  $currentinstllmentyear;
        $month = $totalmonth  % 12;
   
        $nextdate = $currentinstllmentday.'-'.$month.'-'.$newyear;
    }

  



    $body = " Dear Customer Your Due amount $installmentdue Taka and Next installment Date  $nextdate ";
    // echo $body;

   
    if (!empty($mobile)) {
        //sms 
         $notification .=  $obj->smsSend($mobile,$body);
     }


};

?>
<script>
    window.location="?q=due_installment";
</script>
