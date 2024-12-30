<?php

date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;


//===================Add Function===================

   if(isset ($_POST['submit'])){
       extract($_POST);
       
       $form_data = array(
           
          'acc_name' => $Name,
//          'acc_type' => $type,
          'acc_desc' => str_replace("'", "", $Details),           
          'acc_status' => $status,  
           
          'entry_by' => $userid,       
          'entry_date' => $date_time,
          'update_by' => $userid
           );
       $service_add=$obj->insert_by_condition("tbl_accounts_head", $form_data, " ");
       
       if($service_add){                      
           ?>
            <script>
              window.location="?q=view_account_head";
            </script>   
<?php                    
       }
       else{
           echo $notification = 'Insert Failed';
       }
   }
?>

<!--===================end Function===================-->


<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification)? $notification :NULL; ?></b>
</div>
<div class="row" style="padding:10px; font-size: 12px;">
          <form role="form" enctype="multipart/form-data" method="post">    
                <div class="row" style="padding:10px; font-size: 12px;">

                    <div class="col-md-6">
                       
                       <div class="form-group">
                            <label>Account Head Name</label>
                            <input type="text" name="Name" class="form-control" id="ResponsiveTitle"  >
                       </div>
<!--                         <div class="form-group">                                                         
                            <label>Account Head type</label>
                            <select class="form-control" required="required" name="type" id="status">
                                <option value="">------</option>
                                <option value="1">Expense</option>
                                <option value="2">Income</option>
                            </select>                       
                         </div>-->
                        <div class="form-group">
                            <label>Account Head Details</label>
                             <textarea class="form-control" name="Details" id="ResponsiveDetelis" rows="6"></textarea>
                         </div>
                                                                                    
                        <div class="form-group">                                                         
                            <label>Status</label>
                            <select class="form-control" required="required" name="status" id="status">
                                <option value="">Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>                       
                         </div>                       
                    </div>
                    <div class="col-md-6"></div>
                </div>

                <div class="row" style="padding: 5px 0px 15px 25px; font-size: 12px;">
                  <button type="submit" class="btn btn-success" name="submit">Submit</button> 
                </div>
        </form>
        </div>
<hr></hr>