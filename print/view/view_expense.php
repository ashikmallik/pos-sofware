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


if(isset($_GET['deletExpenseId']) && !empty($_GET['deletExpenseId'])){

    $deleteId = $_GET['deletExpenseId'];
    $deletedAccData = $obj->details_by_cond('tbl_account', "acc_id = $deleteId");
    if($deletedAccData['acc_head'] != 0){
        $obj -> Delete_data('tbl_account',"acc_id = '$deleteId'");

        echo '<script>window.location  = "?q=view_expense";</script>';
    }

}

// ==========  Function End =================

?>

<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="col-md-12 bg-slate-800"
     style="margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; font-weight:bold;">
    <div class="col-md-6">
        <p>View Expense</p>
    </div>
    <div class="col-md-6" style="">
        <?php if ($ty == 'SA') { ?>
            <a class="btn btn-primary btn-sm pull-right" href="?q=add_expense">ADD NEW EXPENSE <span class="glyphicon
        glyphicon-plus"></span></a>
        <?php } ?>
    </div>
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
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = '0';
                $totalExpense = 0;
                foreach ($allExpenseData as $value) {
                    $i++;
                    $totalExpense += $value['acc_amount'];
                    ?>
                    <tr>

                        <td><?php echo $i;?></td>
                        <td><?php echo date("d-m-Y", strtotime(isset($value['entry_date'])?$value['entry_date']:"2016-02-1"));?></td>
                        <td><?php echo isset($value['acc_name'])?$value['acc_name']:NULL;?></td>
                        <td><?php echo isset($value['acc_amount'])?number_format($value['acc_amount']).' tk':NULL;
                            ?></td>
                        <td><?php echo isset($value['acc_description'])?$value['acc_description']:NULL;?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a class="btn btn-xs bg-teal btn-primary padding_2_10_px"
                                    <?php echo ($value['acc_head'] == 0) ? 'disabled' : '';  ?> href="?q=edit_expense&expenseId=<?php echo isset ($value['acc_id']) ? $value['acc_id'] : NULL ?>">Edit</a>

                                <a href="?q=view_expense&deletExpenseId=<?php echo isset($value['acc_id']) ? $value['acc_id'] : NULL; ?>"
                                   <?php echo ($value['acc_head'] == 0) ? 'disabled' : '';  ?> onclick="return confirm('Are you sure you want to delete this Expense?');"
                                   class="btn btn-xs btn-danger padding_2_10_px">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
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
