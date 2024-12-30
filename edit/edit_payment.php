<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add      = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;
$token = isset($_GET['token'])? $_GET['token']:NULL;

$details = $obj->details_by_cond("tbl_service","s_id='$token'");

extract($details);

    if(isset($_POST['update'])){
        extract($_POST);

   $form_data=array(
          's_name' => $Name,
          's_desc' => str_replace("'", "", $Details),           
          's_status' => $status,  
           
          'entry_by' => $userid,       
          'entry_date' => $date_time,
          'update_by' => $userid                         
   );
    $branch_id=$obj->Update_data("tbl_service",$form_data,"where s_id='$token'");
   
    if($branch_id){
        
        ?>
<script>
   window.location="?q=view_service";
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
                        <label>Service Name</label>
                        <input value="<?php echo $details['s_name']? $details['s_name']:NULL; ?>" type="text" name="Name" class="form-control" id="ResponsiveTitle"  >
                   </div>
                    
                    <div class="form-group">
                        <label>Service Details</label>
                         <textarea class="form-control" name="Details" id="ResponsiveDetelis" rows="6"><?php echo $details['s_desc']? $details['s_desc']:NULL; ?></textarea>
                     </div>

                    <div class="form-group">                                                         
                        <label>Status</label>
                        <select class="form-control" required="required" name="status" id="status">
                           <option <?php if($details['s_status']=='1') echo 'selected';  ?>  value="1">Active</option>
                           <option <?php if($details['s_status']=='0') echo 'selected'  ?> value="0">Inactive</option>
                        </select>                       
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