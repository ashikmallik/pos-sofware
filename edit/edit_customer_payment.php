<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add      = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;
$token = isset($_GET['token'])? $_GET['token']:NULL;

$details = $obj->details_by_cond("vw_account","acc_id='$token'");

extract($details);

    if(isset($_POST['update'])){
        extract($_POST);

   $form_data=array(         
          'acc_amount' => $amount,
           
          'entry_by' => $userid,       
          'entry_date' => $date_time,
          'update_by' => $userid                       
   );
    $branch_id=$obj->Update_data("tbl_account",$form_data,"where acc_id='$token'");
   
    if($branch_id){
        
        ?>
<script>
   window.location="?q=view_customer_payment";
 </script>
<?php                
    }
    else{
            echo $notification = 'Update Failed';
    }             
    }
?>

<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification)? $notification :NULL; ?></b>
</div>
<div class="row" style="padding:10px; font-size: 12px;">
    <form role="form" enctype="multipart/form-data" method="post">    
            <div class="row" style="padding:10px; font-size: 12px;">
                <div class="col-md-6">                       
                   <div class="form-group">
                        <label>Payment Amount</label>
                        <input value="<?php echo $details['acc_amount']? $details['acc_amount']:NULL; ?>" type="text" name="amount" class="form-control" id="ResponsiveTitle"  >
                   </div>                                                                             
                </div>
                <div class="col-md-6"></div>
            </div>
            <div class="row" style="padding: 5px 0px 15px 25px; font-size: 12px;">
              <button type="submit" class="btn btn-success" name="update">Submit</button> 
            </div>
    </form>
</div>
<hr></hr>