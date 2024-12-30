<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$expenseCat = 1; // for accounts
$giveCashToCustomer = 12;
$loanGiveToPersonType = 13;
$CompanyRepayHisLoanType = 16;
$CompanyBackSecurityMoneyToCustomerType = 18;
$CompanyGiveSecurityMoneyToSupplierType = 19;
$CompanyGivePaymentEmployeeType = 21;


$whereCondition = "(acc_type ='$expenseCat' OR 
acc_type ='$giveCashToCustomer' OR 
acc_type ='$loanGiveToPersonType' OR 
acc_type ='$CompanyRepayHisLoanType' OR 
acc_type ='$CompanyBackSecurityMoneyToCustomerType' OR 
acc_type ='$CompanyGiveSecurityMoneyToSupplierType' OR 
acc_type ='$CompanyGivePaymentEmployeeType')";



$time = "";

if (isset($_GET['action'])) {

    $action = (isset($_GET['action']) && !empty($_GET['action'])) ? $_GET['action'] : null;

    if ($action == 'day') {

        $expenseData = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "$whereCondition AND `entry_date` = CURDATE() order by entry_date");
        $time = "Today's ";
    } else if ($action == 'month') {
        $expenseData = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "$whereCondition  AND month(entry_date) = month(CURDATE()) order by entry_date");
        $time = "This Month's ";
    } else if ($action == 'year') {
        $expenseData = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "$whereCondition  AND Year(entry_date) = Year(CURDATE()) order by 
    entry_date");
        $time = "This Years's ";
    } else if ($action == 'search') {
        $startDate = date('Y-m-d', strtotime($_GET['startDate']));
        $endDate = date('Y-m-d', strtotime($_GET['endDate']));

        $time = $_GET['startDate'] . " To " . $_GET['endDate'] . " ";
        $expenseData = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "$whereCondition AND entry_date 
    BETWEEN '$startDate' AND '$endDate' order by entry_date");
    }else{
        ?>
        <script>
            window.location  = "?q=view_expense";
        </script>
        <?php
    }


} else {

    $expenseData = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "`acc_type` = 1");
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">
<script>

    function numbersOnly(e) // Numeric Validation
    {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) {
            if ((unicode < 2534 || unicode > 2543) && (unicode < 46 || unicode > 57)) {
                return false;
            } else if (unicode == 47) {
                return false;
            }
        }
    }
</script>
<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View <?php echo $time; ?>Expense List </strong></h4>
    </div>

    <div class="col-md-6" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>
            <a href="?q=print_Expense&daily" target="_blank" class="btn btn-primary btn-sm pull-right">Print Expense
                List</a>
        <?php } ?>
    </div>
</div>

<div class="col-md-12 bg-slate-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="GET">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateMonth">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" value="<?php echo isset($_GET['startDate']) ? $_GET['startDate'] :
                        null; ?>"
                           required="required" type="text"
                           name="startDate">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="damageRate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" value="<?php echo isset($_GET['endDate']) ? $_GET['endDate'] :
                        null; ?>" class="form-control" required="required" name="endDate">
                </div>
            </div>
        </div>
        <input type="hidden" name="q" value="expense_report">
        <input type="hidden" name="action" value="search">
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default"><span
                    class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>


<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
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
                </tr>
                </thead>
                <tbody>
                <?php
                $i = '0';
                $totalExpense = 0;
                foreach ($expenseData as $value) {
                    $i++;
                    $totalExpense += $value['acc_amount'];
                    ?>
                    <tr>

                        <td><?php echo $i; ?></td>
                        <td><?php echo date("d-M-Y", strtotime(isset($value['entry_date'])
                                ? $value['entry_date'] : "2016-02-1")); ?></td>
                        <td><?php echo isset($value['acc_name']) ? $value['acc_name'] : NULL; ?></td>
                        <td><?php echo isset($value['acc_amount']) ? number_format($value['acc_amount']) . ' tk' : NULL;
                            ?></td>
                        <td><?php echo isset($value['acc_description']) ? $value['acc_description'] : NULL; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3">total </th>
                    <th colspan="2"><?php echo $totalExpense;?> Taka</th>
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