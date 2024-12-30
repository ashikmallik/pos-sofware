<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$expenseType = 1;
$purchasePaymentType = 2;
$supplierPurchaseIndividualPaymentType = 6;
$giveCashToCustomer = 12;
$loanGiveToPersonType = 13;
$CompanyRepayHisLoanType = 16;
$CompanyBackSecurityMoneyToCustomerType = 18;
$CompanyGiveSecurityMoneyToSupplierType = 19;
$CompanyGivePaymentEmployeeType = 21;

$whereConditin = "acc_type ='$expenseType' OR 
acc_type ='$giveCashToCustomer' OR 
acc_type ='$purchasePaymentType' OR 
acc_type ='$supplierPurchaseIndividualPaymentType' OR 
acc_type ='$loanGiveToPersonType' OR 
acc_type ='$CompanyRepayHisLoanType' OR 
acc_type ='$CompanyBackSecurityMoneyToCustomerType' OR 
acc_type ='$CompanyGiveSecurityMoneyToSupplierType' OR 
acc_type ='$CompanyGivePaymentEmployeeType'";

$allExpenseData = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income","$whereConditin ORDER BY acc_id DESC");
$allHead = $obj->view_all_by_cond("tbl_ac_head_other_income","acc_status =1 ");

if(isset($_GET['deletExpenseId']) && !empty($_GET['deletExpenseId'])){

    $deleteId = $_GET['deletExpenseId'];
    $deletedAccData = $obj->details_by_cond('tbl_account', "acc_id = $deleteId");
    if($deletedAccData['acc_head'] != 0){
        $obj -> Delete_data('tbl_account',"acc_id = '$deleteId'");

        echo '<script>window.location  = "?q=view_expense";</script>';
    }
}
?>

<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; font-weight:bold;">
    <div class="col-md-6">
        <p>View Expense <?php if(isset($_GET['search'])) { }else {echo ' of This Month';}?></p>
    </div>
    <div class="col-md-6" style="">
        <?php if ($ty == 'SA') { ?>
            <a class="btn btn-primary btn-sm pull-right" href="?q=add_expense">ADD NEW EXPENSE <span class="glyphicon
        glyphicon-plus"></span></a>
        <?php } ?>
    </div>
</div>

<div class="col-md-12 bg-slate-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="?q=view_expense" method="GET">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateMonth">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" value="<?php echo isset($_GET['startDate']) ? $_GET['startDate'] :
                        null; ?>" required="required" type="text" name="startDate" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="damageRate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" value="<?php echo isset($_GET['endDate']) ? $_GET['endDate'] : null; ?>" class="form-control" required="required" name="endDate" autocomplete="off">
                </div>
            </div>
        </div>
        <input type="hidden" name="q" value="view_expense">
        <input type="hidden" name="action" value="search">
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default">
                <span class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>
<!-- all user show -->
<div id="div_all" class="row" style="padding:10px;font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-grey-800">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Head</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = '0';
                $totalExpense = 0;
                $sum_of_amount = 0;
                $amount = 0;
                foreach ($allHead as $head) {
                    $i++;
                    if(isset($_GET['search'])){
                        $startDate = date('Y-m-d', strtotime($_GET['startDate']));
                        $endDate = date('Y-m-d', strtotime($_GET['endDate']));
                        $amountall = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "acc_head=".$head['acc_id']." AND acc_type=1 AND entry_date 
    BETWEEN '$startDate' AND '$endDate' order by entry_date");
                        foreach ($amountall as $amount_single)
                        $amount = $amount_single['acc_amount'] + $amount;
                    }else{
                        $startDate = date('Y-m-d', strtotime('first day of this month'));
                        $endDate = date('Y-m-d');
                        $amountall = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "acc_head=".$head['acc_id']." AND acc_type=1 AND entry_date 
    BETWEEN '$startDate' AND '$endDate' order by entry_date");
                        foreach ($amountall as $amount_single)
                        $amount = $amount_single['acc_amount'] + $amount;
                    }

                    if ($amount==0){continue;}
                    $sum_of_amount = $amount + $sum_of_amount;
                    ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo date("d-m-Y", strtotime($head['entry_date']));?></td>
                        <td><a href="?q=view_income_details&acc_head=<?php echo $head['acc_id']?>&view=expense&startDate=<?= $startDate?>&endDate=<?= $endDate ?>" ><b><?php echo $head['acc_name']?></b></a></td>
                        <td><?php echo $head['acc_desc']?></td>
                        <td class="text-right"><?php echo number_format($amount); $amount=0;?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a class="btn btn-xs bg-teal btn-primary padding_2_10_px" href="?q=view_income_details&acc_head=<?php echo $head['acc_id']; ?>&view=expense&startDate=<?= $startDate?>&endDate=<?= $endDate ?>">Details</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3">total </th>
                    <th colspan="3"><?php echo $totalExpense;?> Taka</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function () {
        $('input[name="startDate"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });

        $('input[name="endDate"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });

        $(document).on('mouseover', 'tbody tr td [data-toggle="tooltip"]', function () {
            $('tbody tr td [data-toggle="tooltip"]').tooltip();
        });

    });
</script>
