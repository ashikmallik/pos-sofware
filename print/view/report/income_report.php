<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
$date =date('Y-m-d');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;
$alldue1 =isset($_SESSION['alldue']) ? $_SESSION['alldue']:NULL;


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
 <?php
  $dateform="";
  $dateto="";
  $pre_dateform="";
  $pre_dateto="";

    if(isset($_POST['search'])){
	extract($_POST);
		$pre_date=date_format(date_create($dateto."-".$dateform."-1"),"Y/m/d");
		$pre_dateform=date('n', strtotime('-1 months', strtotime($pre_date)));
		$pre_dateto=date('Y', strtotime('-1 months', strtotime($pre_date)));
		
	}else{
		$pre_dateform=date("n",strtotime("-1 months"));
		$pre_dateto=date("Y",strtotime("-1 months"));
		
		$dateform=date("n");
		$dateto=date("Y");
	}
	//Get previous month total income from view tables 
	$get_all_income=$obj->get_all_income($pre_dateform,$pre_dateto);
	//Get previous month total Expense from view tables 
	$get_sum_expense=$obj->get_sum_expense($pre_dateform,$pre_dateto);
	$pre_grand_income=$get_all_income['amount']-$get_sum_expense['amount'];
	$dateObj   = DateTime::createFromFormat('!m', $dateform);
	$mon=$dateObj->format('F');
 ?>

<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">   
        <form  action="" method="POST">
            <div class="col-md-5">
                 <div class="form-group">
                    <label class="col-sm-6" >Month</label>
                    <select class="form-control" name="dateform" id="status" required>
                        <option value="<?=$dateform?>" selected><?=$mon?></option>                                  
                        <option  value="1">January</option>
                        <option  value="2">February</option>
                        <option  value="3">March</option>
                        <option  value="4">April</option>
                        <option  value="5">May</option>
                        <option  value="6">June</option>
                        <option  value="7">July</option>
                        <option  value="8">August</option>
                        <option  value="9">September</option>
                        <option  value="10">October</option>
                        <option  value="11">November</option>
                        <option  value="12">December</option>                                     
                    </select>
                 </div> 
                
            </div>            
            <div class="col-md-5">
                 <div class="form-group">
                    <label class="col-sm-6" >Year</label>
                    <select class="form-control" name="dateto" id="status" required>
                        <option  value="<?=$dateto?>" selected><?=$dateto?></option>                                  
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

<div class="row">
	<div class="col-md-1 col-md-offset-10" >
		<button type="submit" class="btn btn-primary btn-lg pull-right" onclick="printDiv('month_table')"  >Print Statement</button>
	</div>
</div>

<div class="row print" id="month_table">  
<h2 class="text-center">Monthly Income Sheet of 
<?php 
$dateObj   = DateTime::createFromFormat('!m', $dateform);
echo $dateObj->format('F')." ".$dateto;

?></h2>
	<div class="table-responsive">
	<table class="table table-bordered table-hover table-striped">
		<thead> 
			<tr>
				<th>SL. No.</th> 
				<th>Date</th> 
				<th>Bill Collection</th>
				<th>Connection Charge</th>                                             
				<th>Others Income</th>                                             
				<th class="text-center">Total</th>                                             
																				   
			</tr>
		</thead> 
		<tbody>
		<?php
		$total_income=0;
		$k=1;
			foreach ($obj->view_all_by_cond("vw_comb_income","MONTH(entry_date)='$dateform' and YEAR(entry_date)='$dateto' order by entry_date") as $customer_info){
				//initials variables all 
				$comb_bill="";
				$comb_bill_flag="";
				$bill_c_flag="";
				$c_charge_flag="";
				$others_flag="";
				$bill_c="-";
				$c_charge="-";
				$others="-";
				$row_total_income=0;
				// End initials 
				
					$comb_bill=$customer_info['comb_amount'];
					$comb_bill_flag=$customer_info['comb_flag'];
					if(strpos($comb_bill_flag,'@')!==false){
							$flag_array=explode('@', $comb_bill_flag);
							$bill_array=explode('@', $comb_bill);
							$cnt=sizeof($flag_array);
							$i=0;
							//print_r($flag_array);
							for($i=0;$i<$cnt;$i++){
								if($flag_array[$i]=="bill"){
									$bill_c=number_format($bill_array[$i], 2, '.', '');
								}else if($flag_array[$i]=="connection"){
									$c_charge=number_format($bill_array[$i], 2, '.', '');
								}else if($flag_array[$i]=="others"){
									$others=number_format($bill_array[$i], 2, '.', '');
								}
							}
							//print_r($bill_array);
							//print_r($flag_array);
							
					}else if($comb_bill_flag=="bill"){
						$bill_c=number_format($comb_bill, 2, '.', '');
					}else if($comb_bill_flag=="connection"){
						$c_charge=number_format($comb_bill, 2, '.', '');
					}else if($comb_bill_flag=="others"){
						$others=number_format($comb_bill, 2, '.', '');
					}
					//total income count
					if($others!="-"){
						$total_income+=$others;
						$row_total_income+=$others;
					}
					if($bill_c!="-"){
						$total_income+=$bill_c;
						$row_total_income+=$bill_c;
					}
					if($c_charge!="-"){
						$total_income+=$c_charge;
						$row_total_income+=$c_charge;
					}
					
			?>
			<tr>
				   <td><?php echo $k++;?></td>
				   <td ><?php echo date("d-m-Y", strtotime(isset($customer_info['entry_date'])?$customer_info['entry_date']:"2016-02-1")); ?></td>
				   <td style="text-align: right;"><?php echo $bill_c;  ?></td>
				   <td style="text-align: right;"><?php echo $c_charge;  ?></td>
				   <td style="text-align: right;"><?php echo $others;  ?></td>							
				   <td style="text-align: right;"><b><?php echo number_format($row_total_income, 2, '.', '');?></b></td>							
			</tr>
		<?php 
		 }
		?>
		<tr style='height:33px;'>
			<td colspan="5" style='font-weight:300;font-size:18px;text-align: right;'>Total Income</td>
			<td style='font-weight:300;font-size:18px;text-align: right;'><?php echo number_format($total_income, 2, '.', '')?></td>
		</tr>
		<tr style='height:33px;'>
			<td colspan="6" style='font-weight:600;font-size:18px;text-align:center;'>
				<span style="color:red;">In Word: </span>
				<?php echo $formater->convert_number_to_words($total_income)?>
				</td>
		</tr>
		</tbody>
	</table>
</div>
</div>

