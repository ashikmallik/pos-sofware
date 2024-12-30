 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script>
        $(function() {
          $( ".datepicker" ).datepicker();
        });
    </script>
    
    
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



 <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
        <b><?php echo isset($notification)? $notification :NULL; ?></b>
 </div>
<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
         <b>Customer Payment Report</b>
    </div>               
</div>
 <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
        <b><?php echo isset($notification)? $notification :NULL; ?></b>
 </div>

<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">   
        <form  action="" method="POST">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleInputEmail1">&nbsp;Customer Name</label>
                    <select required="" name="customername" class="form-control" id="country-selector"  placeholder="Customer Name" autofocus="autofocus" autocorrect="off" autocomplete="off">
                        <?php 
                            foreach ($obj->view_all("tbl_customer_info") as $value_info){
                                    if(!empty($value_info)){
                                            extract($value_info);
                                    }                        
                                          ?>  
                            <option value="" selected="selected">Select Country</option>
                            <option value="<?php echo isset($value_info['id'])?$value_info['id']:NULL; ?>" data-alternative-spellings="AF"><?php echo isset($value_info['pax_f_name'])?$value_info['pax_f_name']:NULL; ?>-<?php echo isset($value_info['pax_l_name'])?$value_info['pax_l_name']:NULL; ?>-<?php echo isset($value_info['cus_id'])?$value_info['cus_id']:NULL; ?></option>
                        <?php 
                        }
                        ?>
                    </select>
                 </div>
            </div>
            <div class="col-md-3">
                <div class="form-group" >
                    <label >Form Date</label>
                    <input style="color: black; padding-bottom: 7px;" id="new_flight_date" class="datepicker" type="text" placeholder="Date" name="dateform">

                 </div>              
            </div>
            <div class="col-md-3">
                 <div class="form-group">
                    <label >To Date</label>
                    <input style="color: black; padding-bottom: 7px;" id="old_flight_date" class="datepicker" type="text" placeholder="Date" name="dateto">

                 </div>              
            </div>
            <div class="col-md-2" style="margin-top: 28px;">
               <button type="submit"  name="search" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</button> 
            </div>
        </form>                  
</div>

 <?php 
    if(isset($_POST['search'])){
      extract($_POST);
?>
<?php 
    if($customername && $dateform && $dateto){
  ?>          
<div class="row" style="padding:10px; font-size: 12px;">
        <?php
        
        $total=0;
        $serviceamount1=0;
         
     
       foreach ($obj->view_all_by_cond("tbl_ticket_sale","t_cus_id='$customername'") as $details1){
            extract($details1);
            $serviceamount1+=($details1['t_charge']);          
            }
             
       $dateform1 = date('Y-m-d',  strtotime($dateform));
       $dateto1 = date('Y-m-d',  strtotime($dateto));
       
       foreach ($obj->view_all_by_cond("tbl_account","cus_id='$customername' order by acc_id") as $customer_info){
       extract($customer_info);
       $total+=$customer_info['acc_amount'];
       }
            
       $dueamount=$serviceamount1-$total;
?>
    <div class="col-md-4">
       <div class="form-group">
            <label>Ticket Bye Amount</label>
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
            <input value="<?PHP echo $dueamount; ?>" type="text" name="amount" class="form-control" id="ResponsiveTitle"  readonly="" >
       </div>  
    </div>
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
                        foreach ($obj->view_all_by_cond("vw_account","cus_id='$customername' and (entry_date BETWEEN '$dateform1' and '$dateto1') order by acc_id") as $customer_info){
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
                                    <a class="btn btn-xs btn-info" style="margin-top: 2px;" href="?q=edit_customer_payment&token=<?php echo isset ($customer_info['acc_id'])?$customer_info['acc_id']:NULL?>">
                                       <span class="glyphicon glyphicon-edit"</span>
                                    </a> 
                                    <?php 
                                    }} 
                                    foreach ($acc as $per){if($per=='delete'){
                                    ?>                             
                                    <a href="?q=view_customer_payment&dltoken=<?php echo isset($customer_info['acc_id'])? $customer_info['acc_id'] :NULL; ?>" class="btn btn-xs btn-danger" style="margin-left: 5px;">
                                       <span class="glyphicon glyphicon-remove"></span>
                                    </a> 
                                    <?php }} ?>                                                                                                                                    
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
</div>
<?php
    }
    
    else{
    ?>    
       <div class="row" style="padding:10px; font-size: 12px;">
        <?php
        
        $total=0;
         $serviceamount1=0;
         
             
       foreach ($obj->view_all_by_cond("tbl_ticket_sale","t_cus_id='$customername'") as $details1){
            extract($details1);
            $serviceamount1+=($details1['t_charge']);          
            }
       
              
       foreach ($obj->view_all_by_cond("tbl_account","cus_id='$customername' order by acc_id") as $customer_info){
       extract($customer_info);
       $total+=$customer_info['acc_amount'];
       }
       
       
      $dueamount=$serviceamount1-$total;
?>
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
            <input value="<?PHP echo $dueamount; ?>" type="text" name="amount" class="form-control" id="ResponsiveTitle"  readonly="" >
       </div>  
    </div>
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
                        foreach ($obj->view_all_by_cond("vw_account","cus_id='$customername' order by acc_id") as $customer_info){
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
                                    <a class="btn btn-xs btn-info" style="margin-top: 2px;" href="?q=edit_customer_payment&token=<?php echo isset ($customer_info['acc_id'])?$customer_info['acc_id']:NULL?>">
                                       <span class="glyphicon glyphicon-edit"</span>
                                    </a> 
                                    <?php 
                                    }} 
                                    foreach ($acc as $per){if($per=='delete'){
                                    ?>                             
                                    <a href="?q=view_customer_payment&dltoken=<?php echo isset($customer_info['acc_id'])? $customer_info['acc_id'] :NULL; ?>" class="btn btn-xs btn-danger" style="margin-left: 5px;">
                                       <span class="glyphicon glyphicon-remove"></span>
                                    </a> 
                                    <?php }} ?>                                                                                                                                    
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
</div>
<?php
    }
?>

<?php 
    }
?>
