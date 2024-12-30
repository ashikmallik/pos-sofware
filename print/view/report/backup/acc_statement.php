<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
$date =date('Y-m-d');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;



?>


<!------------------------------------------>

 <script src="auto_serach/js/jquery-ui.min.js"></script>
 <script src="auto_serach/js/jquery.select-to-autocomplete.js"></script>
 <script>
	  (function($){
	    $(function(){
	      $('select').selectToAutocomplete();
	      // $('form').submit(function(){
	      //   alert( $(this).serialize() );
	      //   return false;
	      // });
	    });
	  })(jQuery);
 </script>
	<link rel="stylesheet" href="auto_serach/js/jquery-ui.css">
  <style>
	  
    .ui-autocomplete {
      padding: 0;
      list-style: none;
      background-color: #fff;
      width: 218px;
      border: 1px solid #B0BECA;
      max-height: 350px;
      overflow-x: hidden;
    }
    .ui-autocomplete .ui-menu-item {
      border-top: 1px solid #B0BECA;
      display: block;
      padding: 4px 6px;
      color: #353D44;
      cursor: pointer;
    }
    .ui-autocomplete .ui-menu-item:first-child {
      border-top: none;
    }
    .ui-autocomplete .ui-menu-item.ui-state-focus {
      background-color: #D5E5F4;
      color: #161A1C;
    }
	</style>
<!------------------------------------------>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<!--<link rel="stylesheet" href="/resources/demos/style.css">-->
<script>
    $(function() {
      $( ".datepicker" ).datepicker();
    });
</script>



 <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
        <b><?php echo isset($notification)? $notification :NULL; ?></b>
 </div>
<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
         <b>Account Statement </b>
    </div>               
</div>
 <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
        <b><?php echo isset($notification)? $notification :NULL; ?></b>
 </div>
<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">   
        <form  action="" method="POST">
            <div class="col-md-5">
                 <div class="form-group">
                    <label class="col-sm-6" >Form Date</label>
                    <input style="color: black;" id="new_flight_date" class="datepicker" type="text" placeholder="Date" name="dateform">

                 </div>              
            </div>
            <div class="col-md-5">
                 <div class="form-group">
                    <label class="col-sm-6" >To Date</label>
                    <input style="color: black;" id="old_flight_date" class="datepicker" type="text" placeholder="Date" name="dateto">

                 </div>              
            </div>
            <div class="col-md-2" style="margin-top: 30px;">
               <button type="submit"  name="search" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</button> 
            </div>
        </form>                  
</div>


 <?php 
    if(isset($_POST['search'])){
      extract($_POST);
?>

<div class="row" style="padding:10px; font-size: 12px;">         
    <div class="col-md-6"> 
        <p>Income Statement</p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>#</th>
                        <th>Date</th> 
                         <th>Received By</th>
                        <th>Amount</th>                                             
                                                                                           
                    </tr>
                </thead> 
                  <tr >                          
                      <td colspan="4" > Other Payment</td>                                                           
                  </tr>
                        <?php
                        $i=0;
                        $totalin1=0;
                        
                        $dateform1 = date('Y-m-d',  strtotime($dateform));
                        $dateto1 = date('Y-m-d',  strtotime($dateto));
                        
                        foreach ($obj->view_all_by_cond("vw_account","entry_date BETWEEN '$dateform1' and '$dateto1' and acc_type='2' and cus_id='0' and agent_id='0' order by acc_id") as $customer_info){
                        extract($customer_info);
                        $i++;
                        $totalin1+=$customer_info['acc_amount'];
                        
                        
                        ?>
                        <tr>
                               <td><?php echo $i;  ?></td>
                               <td><?php echo isset($customer_info['entry_date'])?$customer_info['entry_date']:NULL; ?></td>
                               <td style="text-align: right;"><?php echo isset($customer_info['FullName'])?$customer_info['FullName']:NULL; ?>(<?php echo isset($customer_info['UserName'])?$customer_info['UserName']:NULL; ?>)</td>
                               <td style="text-align: right;"><?php echo isset($customer_info['acc_amount'])?$customer_info['acc_amount']:NULL; ?></td>
                                                            
                        </tr>
                        <?php 
                         }
                        ?>
                      <tr >                          
                          <td colspan="3" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $totalin1; ?></td>                                   
                      </tr>    
                        
                      <tr>
                          <?php
                            $word=$formater->convert_number_to_words($totalin1);
                          ?>
                          
                          <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
                      </tr> 
                      <!------------------Customer------------------------->
                      
                      
                      
                      <tr >                          
                      <td colspan="4" > Customer Payment</td>                                                           
                  </tr>
                        <?php
                        $i=0;
                        $totalin2=0;
                        
                        $dateform1 = date('Y-m-d',  strtotime($dateform));
                        $dateto1 = date('Y-m-d',  strtotime($dateto));
                        
                        foreach ($obj->view_all_by_cond("vw_account","entry_date BETWEEN '$dateform1' and '$dateto1' and acc_type='3' and agent_id!='0' order by acc_id") as $agent_info){
                        extract($agent_info);
                        $i++;
                        $totalin2+=$agent_info['acc_amount'];
                        
                        
                        ?>
                        <tr>
                               <td><?php echo $i;  ?></td>
                               <td><?php echo isset($agent_info['entry_date'])?$agent_info['entry_date']:NULL; ?></td>
                               <td style="text-align: right;"><?php echo isset($agent_info['FullName'])?$agent_info['FullName']:NULL; ?>(<?php echo isset($customer_info['UserName'])?$customer_info['UserName']:NULL; ?>)</td>
                               <td style="text-align: right;"><?php echo isset($agent_info['acc_amount'])?$agent_info['acc_amount']:NULL; ?></td>
                                                            
                        </tr>
                        <?php 
                         }
                        ?>
                      <tr >                          
                          <td colspan="3" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $totalin2; ?></td>                                   
                      </tr>    
                        
                      <tr>
                          <?php
                            $word1=$formater->convert_number_to_words($totalin2);
                          ?>
                          
                          <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word1; ?></span></span></td>                          
                      </tr>
                                                                                                                                  
                      <!---------------------------Connection Charge--------------------------->
                       <tr >                          
                          <td colspan="4" >Connection Charge</td>                                                           
                      </tr> 
                      <?php
                        $i=0;
                        $totalin3=0;
                        
                        $dateform1 = date('Y-m-d',  strtotime($dateform));
                        $dateto1 = date('Y-m-d',  strtotime($dateto));
                        
                        foreach ($obj->view_all_by_cond("tbl_agent","entry_date BETWEEN '$dateform1' and '$dateto1' order by ag_id") as $customer_info){
                        extract($customer_info);
                        $i++;
                        $totalin3+=$customer_info['connect_charge'];
                                                
                        ?>
                        <tr>
                               <td><?php echo $i;  ?></td>
                               <td><?php echo isset($customer_info['entry_date'])?$customer_info['entry_date']:NULL; ?></td>
                               <td style="text-align: right;"><?php echo isset($customer_info['ag_name'])?$customer_info['ag_name']:NULL; ?></td>
                               <td style="text-align: right;"><?php echo isset($customer_info['connect_charge'])?$customer_info['connect_charge']:NULL; ?></td>
                                                            
                        </tr>
                        <?php 
                         }
                        ?>
                      <tr >                          
                          <td colspan="3" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $totalin3; ?></td>                                   
                      </tr>    
                      <tr>
                          <?php
                            $word2=$formater->convert_number_to_words($totalin3);
                          ?>
                          
                          <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word2; ?></span></span></td>                          
                      </tr>  
                      <!-----------------------end------------------------>
                      <tr >   
                          <?php
                          $grandtotal=0;
                          $grandtotal=$totalin1+$totalin2+$totalin3;
                          ?>
                          
                          <td colspan="3" style="text-align: right;">Grand Total:</td>
                          <td style="text-align: right;"><?PHP echo $grandtotal; ?></td>                                   
                      </tr>    
                      <tr>
                          <?php
                            $word=$formater->convert_number_to_words($grandtotal);
                          ?>
                          
                          <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
                      </tr> 
                
                </table>
            </div>
    </div>
    <div class="col-md-6">
         <p>Expense Statement</p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>#</th>
                        <th>Date</th> 
                        <th>Received By</th>  
                        <th>Amount</th>                                             
                                                                                          
                    </tr>
                </thead>                   
                        <?php
                        $i=0;
                        $totalin3=0;
                        
                        $dateform1 = date('Y-m-d',  strtotime($dateform));
                        $dateto1 = date('Y-m-d',  strtotime($dateto));
                        
                        foreach ($obj->view_all_by_cond("vw_account","entry_date BETWEEN '$dateform1' and '$dateto1' and acc_type='1' order by acc_id") as $customer_info){
                        extract($customer_info);
                        $i++;
                        $totalin3+=$customer_info['acc_amount'];
                        
                        
                        ?>
                        <tr>
                               <td><?php echo $i;  ?></td>
                               <td><?php echo isset($customer_info['entry_date'])?$customer_info['entry_date']:NULL; ?></td>
                               <td style="text-align: right;"><?php echo isset($customer_info['FullName'])?$customer_info['FullName']:NULL; ?>(<?php echo isset($customer_info['UserName'])?$customer_info['UserName']:NULL; ?>)</td>                                                     
                               <td style="text-align: right;"><?php echo isset($customer_info['acc_amount'])?$customer_info['acc_amount']:NULL; ?></td>
                          </tr>
                        <?php 
                         }
                        ?>
                      <tr >                          
                          <td colspan="3" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $totalin3; ?></td>                                   
                      </tr>    
                      <tr>
                          <?php
                            $word=$formater->convert_number_to_words($totalin3);
                          ?>
                          
                          <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
                      </tr>    

                
                </table>
            </div>
    </div>
    <div class="col-md-12">
         <table class="table table-bordered table-hover table-striped" id="example">
            <thead> 
               <tr >  
                   <?php
                          $netbenifit=0;
                          $netbenifit=$grandtotal-$totalin3;
                          ?>
                    <td colspan="3" style="text-align: right;">Net Benefit:</td>
                    <td style="text-align: right;"><?PHP echo $netbenifit; ?></td>                                   
                </tr>    
                <tr>
                    <?php
                      $word=$formater->convert_number_to_words($netbenifit);
                    ?>

                    <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
                </tr>
            </thead>
         </table>
    </div>   
</div>
<?php 
    }
?>
