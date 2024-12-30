<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add      = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;
$token = isset($_GET['token'])? $_GET['token']:NULL;

$details = $obj->details_by_cond("bank_registration","a_id='$token'");

extract($details);

    if(isset($_POST['update'])){
        extract($_POST);

   $form_data=array(
           
         'account_name' => $account_name,         
          'account_no' => $account_no,
          'bank_name' => $bank_name,
		  'branch_name' => $branch_name,
           'entry_by' => $userid,       
          'entry_date' =>date('Y-m-d'),
          'update_by' => $userid                   
   );
    $branch_id=$obj->Update_data("bank_registration",$form_data,"where a_id='$token'");
   
    if($branch_id){
        
        ?>
<script>
   window.location="?q=view_bank";
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
                             <label>Account's Name</label>
                             <input value="<?php echo $details['account_name']? $details['account_name']:NULL; ?>" type="text" name="account_name" class="form-control" id="ResponsiveTitle"  >
                        </div>                                                                 
                       <div class="form-group">
                             <label>Account No</label>
                             <input value="<?php echo $details['account_no']? $details['account_no']:NULL; ?>" type="text" name="account_no" class="form-control" id="ResponsiveTitle"  >
                        </div>
                
                                            
                        <div class="form-group">
                            <label>Bank Name</label>
                            <input value="<?php echo $details['bank_name']? $details['bank_name']:NULL; ?>" type="text" name="bank_name" class="form-control" id="ResponsiveTitle"  >
                       </div>   
                         
                        <div class="form-group">
                            <label>Branch Name</label>
                            <input value="<?php echo $details['branch_name']? $details['branch_name']:NULL; ?>" type="text" name="branch_name" class="form-control" id="ResponsiveTitle" required="required" >
                       </div>                                                                                                         
                                      
                        
                        <div class="row" style="text-align: center; padding: 5px 0px 15px 25px; font-size: 12px;">
                            <button type="submit" class="btn btn-success" name="update">Update</button> 
                        </div>                                                                                  
                                             
                    </div>
                    <div class="col-md-6"></div>
                </div>
        </form>
</div>
<hr></hr>