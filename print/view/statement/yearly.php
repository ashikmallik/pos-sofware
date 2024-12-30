<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
$date =date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;
$total=0;
$total1=0;
$total2=0;
$total3=0;
$total4=0;
$total5=0;



?>


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
                    <label class="col-sm-6" >Year</label>
                    <select class="form-control" required="required" name="dateto" id="status">
                        <option  value="">--select--</option>                                  
                        <option  value="2015">2015</option>
                        <option  value="2016">2016</option>
                        <option  value="2017">2017</option>
                        <option  value="2018">2018</option>
                        <option  value="2019">2019</option>
                        <option  value="2020">2020</option>
                        <option  value="2021">2021</option>
                        <option  value="2022">2022</option>
                        <option  value="2023">2023</option>
                        <option  value="2024">2024</option>
                        <option  value="2025">2025</option>                                
                    </select>
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
<div class="row">
	<div class="col-md-1 col-md-offset-10" >
		<button type="submit" class="btn btn-primary btn-lg pull-right" onclick="printDiv('year_table')"  >Print Statement</button>
	</div>
</div>
<div class="row" id="year_table">  
	<div class="col-md-11" >
		<h2 style="text-align:center;">The Yearly Statement of <?=$dateto?></h2>
	</div>

 <div class="col-md-11" >
	<table class="table table-responsive table-bordered table-hover table-striped">
		<thead> 
			<tr>
				<th>#</th>
				<th>Month</th> 
				<th>Opening Balance</th>
				<th>Customer Payment</th>                                             
				<th>Others Payment</th>                                             
				<th>Connection Charge</th>                                             
				<th>Total</th>                                             
				<th>Expense Statement</th>                                             
				<th>Closing Balance</th>                                             																   
			</tr>
		</thead> 
		<tbody>
			<?php
				$max=12;
				$i=0;
				$invoice=0;
				$year_total=0;
				$year_expense=0;
				$month_digit=date('n',strtotime(($date)));
				$year_digit=date('Y',strtotime(($date)));
				if($year_digit==$dateto){
					$max=$month_digit;
				}
				for($j=1; $j<=$max; $j++){
				$other_totals=0;    
				$agent_totals=0;    
				$connection_totals=0;    
				$expensive_totals=0;    
				$i++;     
				//Other calculate
				foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$j' and YEAR(entry_date)='$dateto' and acc_type='2' and cus_id='0' and agent_id='0' ") as $month_info){
				extract($month_info);                    
				$other_totals+=$month_info['acc_amount'];
				  }
				//Agent bill calculate
				foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$j' and YEAR(entry_date)='$dateto' and acc_type='3' and cus_id='0' and agent_id!='0' ") as $month_info){
				extract($month_info);                    
				$agent_totals+=$month_info['acc_amount'];
				  }
				 //Connection charge
				 foreach ($obj->view_all_by_cond("tbl_agent","MONTH(entry_date)='$j' and YEAR(entry_date)='$dateto' ") as $month_info){
				extract($month_info);                    
				$connection_totals+=$month_info['connect_charge'];
				  } 
				 //Expensive calculation
				 foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$j' and YEAR(entry_date)='$dateto' and acc_type='1' and cus_id='0' and agent_id='0' ") as $month_info){
				extract($month_info);                    
				$expensive_totals+=$month_info['acc_amount'];
				  }
				?>
			<tr>
				<td><?php echo $i;  ?></td>
				<td style="text-align: center;">
					<?php 
					if($j==1){
						echo 'January';
					}
					else if($j==2){
						echo 'February';
					}
					else if($j==3){
						echo 'March';
					}
					else if($j==4){
						echo 'April';
					}
					else if($j==5){
						echo 'May';
					}
					else if($j==6){
						echo 'June';
					}
					else if($j==7){
						echo 'July';
					}
					else if($j==8){
						echo 'August';
					}
					else if($j==9){
						echo 'September';
					}
					else if($j==10){
						echo 'October';
					}
					else if($j==11){
						echo 'November';
					}
					else if($j==12){
						echo 'December';
					}
					?>
				</td>
				<td style="text-align: right;">
					<?=$invoice?>
				</td>

   				<td style="text-align: right;">
					<?php echo $agent_totals ?>
				</td>
				<td style="text-align: right;">
					<?php echo $other_totals ?>
				</td> 
   				<td style="text-align: right;">
					<?php echo $connection_totals ?>
				</td>
				<td style="text-align: right;"><b>
					<?php 
					$temp_total=$other_totals+$agent_totals+$connection_totals+$invoice; 
					$year_total+=$temp_total;
					echo $temp_total;
					?>
					</b>
				</td>
 				<td style="text-align: right;">
					<?php 
						$year_expense=$expensive_totals;
						echo $expensive_totals;
					?>
				</td>
 				<td style="text-align: right;"><b>
					<?php
					$profit_month=($other_totals+$agent_totals+$connection_totals+$invoice)-$expensive_totals;
					echo $profit_month;
					$invoice=$profit_month;
					?>
					</b>
				</td>                                                            
			</tr>
			<?php 
			 }
			
			?>
		  <tr>                          
			  <td colspan="6" style="text-align: right;">Total Income:</td>
			  <td colspan="3" style="text-align: right;"><?=$year_total?></td>                                   
		  </tr>
		  <tr>                          
			  <td colspan="6" style="text-align: right;">Total Expense:</td>
			  <td colspan="3" style="text-align: right;"><?=$year_expense?></td>                                   
		  </tr>
		  <tr>                          
			  <td colspan="6" style="text-align: right;">Yearly Profit:</td>
			  <td colspan="3" style="text-align: right;"><?PHP echo $year_total-$year_expense; ?></td>                                   
		  </tr>    
			
		  <tr>
			  <?php
				$totalw=$formater->convert_number_to_words($year_total-$year_expense);
			  ?>
			  
			  <td colspan="9" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $totalw; ?></span></span></td>                          
		  </tr> 	
		</tbody>
	</table>
</div>
</div>

<!-- end new update part -->

<?php 
    }
?>
<script>

function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
