<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
$date =date('Y-m-d');
//$date        = date('Y-m-d');
$Month=date('m',strtotime($date));
$Year=date('Y',strtotime($date));
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
                    <input style="color: black;" id="new_flight_date" class="datepicker" type="date" placeholder="Date" name="dateform" required>

                 </div>              
            </div>
            <div class="col-md-5">
                 <div class="form-group">
                    <label class="col-sm-6" >To Date</label>
                    <input style="color: black;" id="old_flight_date" class="datepicker" type="date" placeholder="Date" name="dateto" required>

                 </div>              
            </div>
            <div class="col-md-2" style="margin-top: 30px;">
               <button type="submit"  name="search" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</button> 
            </div>
        </form>                  
</div>
<div class="row" style="padding:10px; font-size: 12px;"> 
<?php
$flag=isset($_GET['key'])?"1":"0";

    if(isset($_POST['search'])){
      extract($_POST);
	  $flag="1";
?>
<div class="col-md-1 col-md-offset-11" >
		<button type="submit" class="btn btn-primary pull-right" onclick="printDiv('month_print')"  >Print Statement</button>
	</div>
<div class="row" id="month_print">

	<div class="col-md-11" >
		<h2 style="text-align:center;">The Statement Between <?=$dateform?> to <?=$dateto?></h2>
	</div>
		<div class="col-md-12">
            <table class="table table-responsive table-bordered table-hover table-striped" id="monthly_tbl">
                <thead> 
                    <tr>
                        <th>Sl</th>
                        <th>Date</th> 
                        <th>Particular</th>
                        <th>User</th>                                             
                        <th>Credit</th>                                             
                        <th>Debit</th>                                             
                        <th>Balance</th>                                                                                                                   
                    </tr>
                </thead> 
				<tbody>
					<?php
					$i=0;
					$totalin1=0;
					$balance=0;
					$debit_total=0;
					$credit_total=0;
					foreach ($obj->view_all_by_cond("vw_account","entry_date BETWEEN '$dateform' and '$dateto' ORDER BY `vw_account`.`entry_date` ASC") as $customer_info){
					extract($customer_info);
					$i++;
					$totalin1+=$customer_info['acc_amount'];
					
					
					?>
					<tr id="<?php
					$cus_id=isset($customer_info['agent_id'])?$customer_info['agent_id']:NULL;
					echo $cus_id;?>">
						   <td><?php echo $i;  ?></td>
						   <td><?php echo date("d-m-Y", strtotime(isset($customer_info['entry_date'])?$customer_info['entry_date']:"2016-02-1")); ?></td>
						   <td style="text-align: right;"><?php 
						   $details3 = $obj->details_by_cond("tbl_agent","ag_id=$cus_id");
						   echo isset($details3['ip'])?"IP Or User Id: ".$details3['ip']." - ":""; 
						   echo isset($customer_info['acc_description'])?$customer_info['acc_description']:NULL; 
						   
						   
						   ?></td>
						   <td style="text-align: right;"><?php echo isset($customer_info['FullName'])?$customer_info['FullName']:NULL; ?></td>
						   <?php
						    $get_acc_type=isset($customer_info['acc_type'])?$customer_info['acc_type']:NULL;
						    $amount=isset($customer_info['acc_amount'])?$customer_info['acc_amount']:NULL;
							$debit=0;
							$credit=0;
							
							if($get_acc_type==1){
								$debit=$amount;
								$balance-=$debit;
								$debit_total+=$debit;
							}else{
								$credit=$amount;
								$balance+=$credit;
								$credit_total+=$credit;
							}
							
						   ?>
						   <td style="text-align: right;"><?php echo $credit; ?></td>
						   <td style="text-align: right;"><?php echo $debit; ?></td>
						   <td style="text-align: right;"><?php echo $balance; ?></td>
														
					</tr>
					<?php 
					 }
					?>

					  <?php
						$word=$formater->convert_number_to_words($balance);
					  ?>
                         
				  </tr> 
				</tbody>
				<tfoot> 
					<tr style="font-size:18px;font-weight:900;text-align:right">
						<td colspan="4">Total</td>
						<td><?PHP echo $credit_total; ?></td>
						<td><?PHP echo $debit_total; ?></td>
						<td><?PHP echo $credit_total-$debit_total; ?></td>
					</tr>
                    <tr>
                        <th>Sl</th>
                        <th>Date</th> 
                        <th>Particular</th>
                        <th>User</th>                                             
                        <th>Credit</th>                                             
                        <th>Debit</th>                                             
                        <th>Balance</th>                                                                                                                   
                    </tr>
                </tfoot> 
			</table>
		</div>
		<div class="col-md-12" style="padding:10px;text-align:center;background-color:rgba(0, 166, 255, 0.68);font-size:18px;">Total:
			<?PHP echo $balance; ?></b>
		</div>
		<div class="col-md-12" style="padding:10px;text-align:center;background-color:rgba(0, 166, 255, 0.68);font-size:18px;">
			<b><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: #3D2424;"><?PHP echo $word; ?></span></span></b>
		</div>
	</div>
	
<?php 
    }
	//entry_date BETWEEN '$dateform1' and '$dateto1' and acc_type='1'
?>
<!-- here end table -->
<?php
if($flag=="0"){
?>
	<div class="col-md-1 col-md-offset-11" >
		<button type="submit" class="btn btn-primary pull-right" onclick="printDiv('month_print')"  >Print Statement</button>
	</div>
<div class="row" id="month_print">

	<div class="col-md-11" >
		<h2 style="text-align:center;">The Statement of Current Month</h2>
	</div>
		<div class="col-md-12">
            <table class="table table-responsive table-bordered table-hover table-striped" id="monthly_tbl">
                <thead> 
                    <tr>
                        <th>Sl</th>
                        <th>Date</th> 
                        <th>Particular</th>
                        <th>User</th>                                             
                        <th>Credit</th>                                             
                        <th>Debit</th>                                             
                        <th>Balance</th>                                                                                                                   
                    </tr>
                </thead>
				<tbody>
					<?php
					$i=0;
					$totalin1=0;
					$balance=0;
					$debit_total=0;
					$credit_total=0;
					foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$Month' and YEAR(entry_date)='$Year' ") as $customer_info){
					extract($customer_info);
					$i++;
					$totalin1+=$customer_info['acc_amount'];
					
					
					?>
					<tr id="<?php
					$cus_id=isset($customer_info['agent_id'])?$customer_info['agent_id']:NULL;
					echo $cus_id;?>">
						   <td><?php echo $i;  ?></td>
						   <td><?php echo date("d-m-Y", strtotime(isset($customer_info['entry_date'])?$customer_info['entry_date']:"2016-02-1")); ?></td>
						   <td style="text-align: right;"><?php 
						   $details3 = $obj->details_by_cond("tbl_agent","ag_id=$cus_id");
						   echo isset($details3['ip'])?"IP Or User Id: ".$details3['ip']." - ":""; 
						   echo isset($customer_info['acc_description'])?$customer_info['acc_description']:NULL; 
						   
						   
						   ?></td>
						   
						   <td style="text-align: right;"><?php echo isset($customer_info['FullName'])?$customer_info['FullName']:NULL; ?></td>
						   <?php
						    $get_acc_type=isset($customer_info['acc_type'])?$customer_info['acc_type']:NULL;
						    $amount=isset($customer_info['acc_amount'])?$customer_info['acc_amount']:NULL;
							$debit=0;
							$credit=0;
							
							if($get_acc_type==1){
								$debit=$amount;
								$balance-=$debit;
								$debit_total+=$debit;
							}else{
								$credit=$amount;
								$credit_total+=$credit;
								$balance+=$credit;
							}
							
						   ?>
						   <td style="text-align: right;"><?php echo $credit; ?></td>
						   <td style="text-align: right;"><?php echo $debit; ?></td>
						   <td style="text-align: right;"><?php echo $balance; ?></td>
														
					</tr>
					<?php 
					 }
					?>
					

					  <?php
						$word=$formater->convert_number_to_words($balance);
					  ?>
                         
				  </tr> 
				</tbody>
				<tfoot> 
					<tr style="font-size:18px;font-weight:900;text-align:right">
						<td colspan="4">Total</td>
						<td><?PHP echo $credit_total; ?></td>
						<td><?PHP echo $debit_total; ?></td>
						<td><?PHP echo $credit_total-$debit_total; ?></td>
					</tr>
                    <tr>
                        <th>Sl</th>
                        <th>Date</th> 
                        <th>Particular</th>
                        <th>User</th>                                             
                        <th>Credit</th>                                             
                        <th>Debit</th>                                             
                        <th>Balance</th>                                                                                                                   
                    </tr>
                </tfoot> 
			</table>
		</div>

		<div class="col-md-12" style="padding:10px;padding-left:50px;text-align:center;background-color:rgba(0, 166, 255, 0.68);font-size:18px;">Total:
			<?PHP echo $credit_total-$debit_total; ?></b>
		</div>
		<div class="col-md-12" style="padding:10px;text-align:center;background-color:rgba(0, 166, 255, 0.68);font-size:18px;">
		<b><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: #3D2424;"><?PHP echo $word; ?></span></span></b>
		</div>
		</div>
	<?php
		}
	?>
</div>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script>

$(document).ready(function(){
    //$('#monthly_tbl').dataTable();
});
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
<script>
$(document).ready(function(){
	$("tbody tr").dblclick(function(){
		_id=this.id;
		if(_id!="0")
			window.location="?q=view_customer_payment_individual&token2="+_id;
	});
});
</script>
<!-- here end table -->

	

