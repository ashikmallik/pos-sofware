<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;

   if(isset ($_POST['submit'])){
       extract($_POST);
       $form_data = array(
          'account_name' => $account_name,         
          'account_no' => $account_no,
          'bank_name' => $bank_name,
		  'branch_name' => $branch_name,
           'entry_by' => $userid,
          'entry_date' =>date('Y-m-d'),
          'update_by' => $userid
           );

       $service_add=$obj->insert_by_condition("bank_registration", $form_data, " ");

       if (!empty($opening_balance)){
           $bank_added = $obj->details_by_cond('bank_registration','a_id='.$service_add);

           $form_data = array(
               'account_no' => $bank_added['account_no'],
               'description' => 'Bank Opening Balance',
               'credit' => 0,
               'debit' => $opening_balance,
               'balance' => $opening_balance,
               'diposited_by' => '',
               'entry_by' => $userid,
               'entry_date' => date('Y-m-d'),
               'update_by' => $userid
           );
           $service_add = $obj->insert_by_condition("bank_account", $form_data, " ");

           $form_tbl_accounts = array(
               'acc_description' => "Bank Opening Balance",
               'acc_amount' => $opening_balance,
               'acc_type' => 26,
               'purchase_or_sell_id' => '',
               'cus_or_sup_id' => 0,
               'acc_head' => 0,
               'payment_method' => 0,
               'entry_by' => $userid,
               'entry_date' => date('Y-m-d'),
               'update_by' => $userid
           );
           $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
       }
       
       if($service_add){                      
           ?>
            <script>
                window.location="?q=view_bank";
            </script>   
<?php                    
       }
       else{
           echo $notification = 'Insert Failed';
       }
   }
?>
<!--===================end Function===================-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    
function numbersOnly(e) // Numeric Validation 
{
    var unicode=e.charCode? e.charCode : e.keyCode
    if (unicode!=8)
    {
        if ((unicode<2534||unicode>2543)&&(unicode<48||unicode>57))
        {
            return false;                       
        }
    }
}
</script>

<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification)? $notification :NULL; ?></b>
</div>
<span style="color: red; font-size: 20px;text-align:left;"></span>
<div class="row" style="padding:10px; font-size: 12px;">
    <div class="bg-grey-800" style="padding:5px;">
        <h4 class="text-center">Please Enter New Bank Information</h4>
    </div>

          <form role="form" enctype="multipart/form-data" method="post">    
                <div class="row" style="padding:10px; font-size: 12px;">

                    <div class="col-md-6 col-md-offset-3">
                             
                       <div class="form-group">
                            <label>Account's Name</label>
                            <input type="text" name="account_name" class="form-control" id="ResponsiveTitle" required="required" >
                       </div>                                            
                       <div class="form-group">
                            <label>Account No</label>
                            <input type="text" name="account_no" class="form-control" id="ResponsiveTitle" required="required" >
                       </div>
                       <div class="form-group">
                            <label>Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" id="ResponsiveDetelis" rows="6"/>
                       </div>
                                            
                       <div class="form-group">
                            <label>Branch Name</label>
                            <input type="text" class="form-control" name="branch_name" id="ResponsiveDetelis" rows="6"/>
                       </div>
                                        
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" required="required" name="status" id="status">
                                <option value="">---Status---</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>                       
                        </div>

                        <div class="form-group">
                            <label>Opening balance</label>
                            <input type="text" class="form-control" name="opening_balance" id="ResponsiveDetelis" />
                        </div>
                        
                        <div class="row" style="text-align: center; padding: 5px 0px 15px 25px; font-size: 12px;">
                            <button type="submit" class="btn btn-lg btn-success pull-left" name="submit">Submit</button> 
                        </div>                                                                                 
                        
                    </div>
                 
				</div>
		</form>
</div>