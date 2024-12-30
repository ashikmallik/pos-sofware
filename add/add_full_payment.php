<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
$date =date('Y-m-d');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;

$token = isset($_GET['token1'])? $_GET['token1']:NULL;
$amount = isset($_GET['amount'])? $_GET['amount']:"0";

//====================Add Full payment Funcyion=========================
//if(isset ($_POST['submit'])){
//       extract($_POST);
       
       $form_data = array(
           
          'agent_id' => $token,
          'acc_amount' => $amount,
          'acc_type' => '3',
          'acc_description' =>"Bill Collection",
          'entry_by' => $userid,       
          'entry_date' => $date_time,
          'update_by' => $userid
           );
       $service_add=$obj->insert_by_condition("tbl_account", $form_data, " ");
       
      
              
       if($service_add){                      
           ?>
           
            <script>               
              window.location="?q=view_due_payment&msg=<span style='color:red;'>Paid success";
            </script>   
			<?php                    
       }
       else{
          ?>
            <script>               
              window.location="?q=view_due_payment&msg=<span style='color:red;'>Something worng, please try again</span>";
            </script>   
			<?php 
       }
   
        
//------------------------------------------------------
        
       $details = $obj->details_by_cond("tbl_agent","ag_id='$token'");
            extract($details);

            $bday1=$details['entry_date'];


            $bday = new DateTime($bday1);
            $today = new DateTime(date('Y-m-d', time())); // for testing purposes
            $diff = $today->diff($bday);
//            printf('%d years, %d month, %d days', $diff->y, $diff->m, $diff->d);
            
        
        $total=0;      
        $serviceamount1=0;
       
       
         foreach ($obj->view_all_by_cond("tbl_agent","ag_id='$token'") as $details1){
            extract($details1);
            $serviceamount1+=($details1['taka']);
            
            }
            if($diff->m!=0){
            $serviceamount2=($serviceamount1*$diff->y*12)+($serviceamount1*$diff->m);
            }
            else{
               $serviceamount2=$serviceamount1;
            }
            foreach ($obj->view_all_by_cond("vw_account","agent_id='$token' order by acc_id") as $customer_info){
            extract($customer_info);
            $total+=$customer_info['acc_amount'];
            }       
       $dueamount=$serviceamount2-$total;
//      -----------------------------------------------------
//      
// ========== Delete Function Start =================
$dltoken = isset($_GET['dltoken'])? $_GET['dltoken']:NULL;
if(!empty($dltoken)){

$dele = $obj->Delete_data("tbl_account","acc_id='$dltoken'");

if(!$dele)
    {$notification = 'Delete Successfull';}
else
    {$notification = 'Delete Failed';} 
}
// ========== Delete Function End =================


?>

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
 <!--           
 <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
        <b><?php echo isset($notification)? $notification :NULL; ?></b>
 </div>
<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
         <b>View Agent Payment Information</b>
    </div>               
</div>
 <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
        <b><?php echo isset($notification)? $notification :NULL; ?></b>
 </div>

<div class="row" style="padding:10px; font-size: 12px;" >   
    <div class="col-md-4">
       <div class="form-group">
            <label>Service Amount</label>
            <input value="<?php echo $serviceamount1 ?>" type="text" name="amount" class="form-control" id="ResponsiveTitle"  readonly="" >
       </div>  
    </div>
    <div class="col-md-4">        
       <div class="form-group">
            <label>Pay Amount</label>
            <input value="<?PHP echo $total; ?>" type="text" name="amount" class="form-control" id="ResponsiveTitle" readonly="" >
       </div>  
    </div>
    <div class="col-md-4">
       <div class="form-group">
            <label>Due Amount</label>
            <input value="<?php echo $dueamount ?>" type="text" name="amount" class="form-control" id="ResponsiveTitle"  readonly="" >
       </div>  
    </div>
</div>
<div class="row" style="padding:10px; font-size: 12px;">                     
    <form role="form" enctype="multipart/form-data" method="post" action=" ">    
        <div class="row" style="padding:10px; font-size: 12px;">

            <div class="col-md-4">
               <div class="form-group">
                    <label class="col-sm-6" >Payment Date</label>
                     <input value="<?php echo $date ?>"  type="text"   id="ResponsiveTitle" readonly=""  >
               </div>
                
                <div class="form-group" style="display: none;">
                    <label class="col-sm-6" >Customer Name</label>
                     <input value="<?php echo $customername ?>" type="text" name="customer_id" class="form-control" id="ResponsiveTitle" readonly=""  >
               </div> 
            </div>
            <div class="col-md-6">
               <div class="form-group">
                   <label class="col-sm-5" style="text-align: right;">Payment Amount</label>
                   <input onkeypress="return numbersOnly(event)" style="height: 30px;"  class="col-sm-7" type="text" name="amount"  id="text"  required="">
               </div>  
            </div>
            <div class="col-md-2">
                <button style="padding: 0px !important;" type="submit" class="btn btn-success" name="submit">Add Payment</button>                  
            </div>
        </div>
    </form>
</div>

<div class="row" style="padding:10px; font-size: 12px;">         
    <div class="col-md-12">       
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>#</th>
                        <th>Date</th>                      
                        <th>Amount</th>                                             
                        <th>Received By</th>                                             
                        <th>Action</th>
                    </tr>
                </thead>                   
                        <?php
                        $i=0;
                        $totalin=0;
                        foreach ($obj->view_all_by_cond("vw_account","agent_id='$token' order by acc_id") as $customer_info){
                        extract($customer_info);
                        $i++;
                        $totalin+=$customer_info['acc_amount'];
                        ?>
                        <tr>
                            <td><?php echo $i;  ?></td>
                            <td><?php echo isset($customer_info['entry_date'])?$customer_info['entry_date']:NULL; ?></td>
                            <td style="text-align: right;"><?php echo isset($customer_info['acc_amount'])?$customer_info['acc_amount']:NULL; ?></td>
                            <td style="text-align: right;"><?php echo isset($customer_info['FullName'])?$customer_info['FullName']:NULL; ?>(<?php echo isset($customer_info['UserName'])?$customer_info['UserName']:NULL; ?>)</td>
                              
                            <td>                          
                                <div class="btn-group" > 
                                    <?php foreach ($acc as $per){if($per=='edit'){ ?>                                                                           
                                    <a class="btn btn-xs btn-info" style="margin-top: 2px;" href="?q=edit_agent_payment&token=<?php echo isset ($customer_info['acc_id'])?$customer_info['acc_id']:NULL?>">
                                       <span class="glyphicon glyphicon-edit"></span>
                                    </a> 
                                    <?php 
                                    }} 
                                    foreach ($acc as $per){if($per=='delete'){
                                    ?>                             
                                    <a href="?q=view_agent_payment&dltoken=<?php echo isset($customer_info['acc_id'])? $customer_info['acc_id'] :NULL; ?>" class="btn btn-xs btn-danger" style="margin-left: 5px;">
                                       <span class="glyphicon glyphicon-remove"></span>
                                    </a> 
                                    <?php }} ?>
                                    <a target="_blank" href="print/print_agent.php?token=<?php echo isset ($customer_info['acc_id'])?$customer_info['acc_id']:NULL?>" class="btn btn-xs " style="margin-left: 5px; background: green;">
                                        <span style="color: black;" class="glyphicon glyphicon-print"></span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                         }
                        ?>
                      <tr >                          
                          <td colspan="2" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $totalin; ?></td>                                   
                      </tr>    
                      <tr>
                          <?php
                            $word=$formater->convert_number_to_words($totalin);
                          ?>                          
                          <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
                      </tr>    

                
                </table>
            </div>
    </div>
</div> -->
