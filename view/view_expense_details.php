<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;


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
<div class="row">
	<div class="col-md-1 col-md-offset-10" >
		<button type="submit" class="btn btn-primary btn-lg pull-right" onclick="printDiv('month_table')"  >Print Statement</button>
	</div>
</div>
<div id="month_table" class="row" style="padding:10px; font-size: 12px;">  
	<h2 class="text-center">All Expense Information of <?=isset($_GET['ex_name'])?$_GET['ex_name']:NULL?></h2>
    <div class="col-md-8 col-md-offset-2">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="example4">
                <thead> 
                    <tr>
                        <th>#</th>                      
                        <th>Date</th>                      
                        <th>Description</th>
                        <th class="text-center">Amount</th> 
                    </tr>
                </thead>
                   <?php
                    $i='0';
					$acc_head=isset($_GET['ex_id'])?$_GET['ex_id']:NULL;
					$dateTo=isset($_GET['dateTo'])?$_GET['dateTo']:NULL;
					$dateFrom=isset($_GET['dateFrom'])?$_GET['dateFrom']:NULL;
					$total_expense=0;
					$sql="acc_head='$acc_head' AND acc_type='1' AND MONTH(entry_date)='$dateFrom' AND YEAR(entry_date)='$dateTo' ORDER BY entry_date ASC";
                    foreach ($obj->view_all_by_cond("vw_account",$sql) as $value){
                        $i++;  
						$total_expense+=isset($value['acc_amount'])?$value['acc_amount']:0;
                    ?>
                    <tr>
						<td><?php echo $i;?></td>
						<td><?php echo date("d-m-Y", strtotime(isset($value['entry_date'])?$value['entry_date']:"2016-02-1"));?></td>
						<td><?php echo isset($value['acc_description'])?$value['acc_description']:NULL;?></td>
						<td class="text-right"><?php echo number_format(isset($value['acc_amount'])?$value['acc_amount']:0, 2, '.', '');?></td>
                    </tr>
                    <?php
                    }
                    ?> 
					<tr style='height:33px;'>
						<td colspan="3" style='font-weight:300;font-size:18px;text-align: right;'>Total Expense</td>
						<td style='font-weight:300;font-size:18px;text-align: right;'><?=number_format($total_expense, 2, '.', '')?></td>
					</tr>
                </table>
            </div>
    </div>
</div>
