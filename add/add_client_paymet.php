<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;



//===================Add Function===================
$total_amount=0;
$due=0;
   if(isset ($_POST['submit'])){
       extract($_POST);
	   $total_egg=$readA+$readB+$readC+$whiteA+$whiteB+$whiteC;
	   $total_amount=$total_egg*5;
	   $due=$total_amount-$paid_amount;
	 
 $acc_head="Bill payment";
       $form_data = array(
          'suplier' => $sup_id,
          'bill_no' => 0,
          'description' => str_replace("'", "", $description),
          'readA' => $readA,  
          'readB' => $readB, 
		   'readC' => $readC, 
		    'whiteA' => $whiteA, 
			'whiteB' => $whiteB, 
			 'whiteC' => $whiteC,  
			 'demage' => $damage,  
			 'total_egg' => $total_egg,  
			 'total_amount' => $total_amount,  
			 'payment_amount' => $paid_amount,  
			 'due' => $due,  
			 'status' => 1,  
           'entry_by' => $userid,       
          'entry_date' =>date('Y-m-d'),
          'update_by' => $userid
           );
		  
	   
       $service_add=$obj->insert_by_condition("purches", $form_data, " ");
	  
       if($service_add){                      
           ?>
            <script>
              window.location="?q=add_client_payment";
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
</p>
<div class="row" style="padding:10px; font-size: 12px;">
          <form role="form" enctype="multipart/form-data" method="post">    
                <div class="row" style="padding:10px; font-size: 12px;">
					
                    <div class="col-md-6">
                       <div class="form-group">
                           <label>Suplier Id</label>
                            <select class="form-control" required="required" name="sup_id" id="status">
                                 <option value="">select</option>
                                    <?php
                                        $i='0';
                                        foreach ($obj->view_all_by_cond("tbl_agent","catagori='2'") as $value){
                                            $i++;                                                              
                                    ?>
                                    <option  value="<?php echo isset($value['cus_id'])?$value['cus_id']:NULL;?>"><?php echo isset($value['cus_id'])?$value['cus_id']:NULL;?>--<?php echo isset($value['c_name'])?$value['c_name']:NULL;?></option>
                                     <?php
                                        }
                                        ?> 
                            </select>  
                       </div>                                            
                       <div class="form-group">
                            <label>Read A</label>
                            <input type="text" name="readA" class="form-control" id="ResponsiveTitle"  >
                       </div>
					    <div class="form-group">
                            <label>Read B</label>
                            <input type="text" name="readB" class="form-control" id="ResponsiveTitle" >
                       </div>
					    <div class="form-group">
                            <label>Read C</label>
                            <input type="text" name="readC" class="form-control" id="ResponsiveTitle">
                       </div>
					    <div class="form-group">
                            <label>White A</label>
                            <input type="text" name="whiteA" class="form-control" id="ResponsiveTitle"  >
                       </div>
					   <div class="form-group">
                            <label>White B</label>
                            <input type="text" name="whiteB" class="form-control" id="ResponsiveTitle"  >
                       </div>
					   <div class="form-group">
                            <label>White C</label>
                            <input type="text" name="whiteC" class="form-control" id="ResponsiveTitle" required="required" >
                       </div>
					    <div class="form-group">
                            <label>Damage</label>
                            <input type="text" name="damage" class="form-control" id="ResponsiveTitle" required="required" >
                       </div>
                       <div class="form-group">
                            <label>Total Egg</label>
                             <input type="text" class="form-control" name="total_egg" id="ResponsiveDetelis" rows="6"/>
                        </div>
						    <div class="form-group">
                            <label>Paid Amount</label>
                            <input type="text" name="paid_amount" class="form-control" id="ResponsiveTitle" required="required" >
                       </div>  
                                
                            <div class="form-group">
                            <label>Description</label>
                             <textarea class="form-control" name="description" id="ResponsiveDetelis" rows="6"></textarea>
                        </div>  
                      
                   
                        
                        <div class="row" style="text-align: center; padding: 5px 0px 15px 25px; font-size: 12px;">
                            <button type="submit" class="btn btn-lg btn-success pull-left" name="submit">Submit</button> 
                        </div>                                                                                 
                        
                    </div>
                 
				</div>
		</form>
</div>