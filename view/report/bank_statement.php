<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

// They are the expense type
////
$Opening =26;
/////
$expenseType = 1;
$purchasePaymentType = 2;
$supplierPurchaseIndividualPaymentType = 6;
$giveCashToCustomer = 12;
$loanGiveToPersonType = 13;
$CompanyRepayHisLoanType = 16;
$CompanyBackSecurityMoneyToCustomerType = 18;
$CompanyGiveSecurityMoneyToSupplierType = 19;
$CompanyGivePaymentEmployeeType = 21;
$CustomerDue = 8;
$SupplierAdvance = 9;
$bankDeposit = 25;
$bankWithdrow = 24;

if (isset($_POST['search'])) {
    extract($_POST);
    $startDate = date('Y-m-d', strtotime($startDate));
    $endDate = date('Y-m-d', strtotime($endDate));
} else {
    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d');
}
$balance_bd = $obj->view_all_by_cond("bank_account", "entry_date < '$startDate'");

$balance_of_bd = 0;
$total_debit_bd = 0;
$total_credit_bd = 0;
foreach ($balance_bd as $bd) {
    $debit_bd = 0;
    $credit_bd = 0;
    if ($bd['debit'] !='0.00') {
        $debit_bd = $bd['debit'];
        $balance_of_bd += $debit_bd;
        $total_debit_bd += $debit_bd;
    } else {
        $credit_bd = $bd['credit'];
        $balance_of_bd -= $credit_bd;
        $total_credit_bd += $credit_bd;
    }
}
$accountDetails = $sell_details = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "payment_method=0 AND entry_date BETWEEN '$startDate' and '$endDate' ORDER BY `vw_accounts_with_acc_head_other_income`.`entry_date` ASC");

$accountDetailsBank = $sell_details = $obj->view_all_by_cond("bank_account", "entry_date BETWEEN '$startDate' and '$endDate' and type=0 ORDER BY `bank_account`.`entry_date` ASC");

?>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">

<div class="col-md-12 bg-grey-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>Bank Statement Between <?php echo date("d-M-Y", strtotime($startDate)) . " to " . date("d-M-Y", strtotime($endDate)); ?></strong>
        </h4>
    </div>

    <div class="col-md-6" style="padding-top:5px;">

    </div>
</div>

<div class="col-md-12 bg-teal-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateMonth">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" required="required" type="text" name="startDate" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="damageRate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" required="required" name="endDate" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default"><span
                        class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>

<div class="row" id="acc_statment_print">
    <div class="col-md-12" style="font-size:12px;">
        <table class="table table-responsive table-bordered table-hover table-striped" id="datatable-btn">
            <thead>
            <tr class="bg-slate-800">
                <th class="col-md-1 text-center">#</th>
                <th class="col-md-2 text-center">Date</th>
                <th class="col-md-1 text-center">Reference</th>
                <th class="col-md-5 text-center">Description</th>
                <th class="col-md-1 text-center">Entry By</th>
                <th class="col-md-1 text-center">Debit</th>
                <th class="col-md-1 text-center">Credit</th>
                <th class="col-md-1 text-center">Balance</th>
            </tr>
            </thead>
            <tbody>
              <?php if ($balance_of_bd!=0){
                $balance = $balance_of_bd;
                ?>
                <tr>
                    <td class="text-center">
                        ---
                    </td>
                    <td class="text-center">

                    </td>
                    <td class="">

                    </td>
                    <td class="">
                        <b>Balance Bd</b>
                    </td>
                    <td>

                    </td>
                    <td class="text-right">
                        <?php echo isset($balance_of_bd) && $balance_of_bd>0 ? number_format($balance_of_bd) . ' tk' : 0; ?>

                    </td>
                    <td class="text-right">
                        <?php echo isset($balance_of_bd) && $balance_of_bd<0? number_format($balance_of_bd) . ' tk' : 0; ?>
                    </td>
                    <td class="text-right">
                        <?php echo isset($balance_of_bd) ? number_format($balance_of_bd) . ' tk' : 0; ?>
                    </td>
                </tr>
            <?php }else{
                $balance = 0;
            }  
             $i = 0;
            $total_debit = 0;
           // $balance = 0;
            $total_credit = 0;
                foreach ($accountDetailsBank as $account) {
                    $total_debit = $account['debit'] + $total_debit;
                    $total_credit = $account['credit'] + $total_credit;
                    $balance -= $account['credit'];
                    $balance += $account['debit'];
                    $i++;
                ?>
                <tr>
                    <td class="text-center">
                        <?php echo $i; ?>
                    </td>
                    <td class="text-center">
                        <?php echo isset($account['entry_date']) ? date('d-m-Y', strtotime($account['entry_date'])) : null; ?>
                    </td>
                    <td class="text-center">

                    </td>
                    <td class="">
                        <?php echo isset($account['description']) ? $account['description'] : null; ?>
                    </td>
                    <td>
                        <?php
                        $user = $obj->details_by_cond('_createuser','UserId ='.$account['entry_by']);
                        echo '<a href="?q=user_details&token='.$account['entry_by'].'">'.$user['FullName'].'</a>';
                        ?>
                    </td>
                    <td class="text-right">
                        <?php echo isset($account['debit']) ? number_format($account['debit']) . ' tk' : "0"; ?>
                    </td>
                    <td class="text-right">
                        <?php echo isset($account['credit']) ? number_format($account['credit']) . ' tk' : "0"; ?>
                    </td>
                    <td class="text-right">
                        <?php echo isset($balance) ? number_format($balance) . ' tk' : "0"; ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr class="bg-grey">
                <th colspan="4" class="text-center">Total</th>
                <th></th>
                <th class="col-md-1 text-right">
                    <strong><?php echo number_format($total_debit); ?></strong> .tk
                </th>
                <th class="col-md-1 text-right">
                    <strong><?php echo number_format($total_credit); ?></strong> .tk
                </th>
                <th class="col-md-1 text-right">
                    <strong><?php echo number_format($balance); ?></strong> .tk
                </th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- end new update part -->
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

    $(document).ready(function() {
        $("#datatable-btn").dataTable().fnDestroy();
        $('#datatable-btn').DataTable( {
            "ordering": false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print Bank Statement',
                    footer: true,
                    title: function () {
                        return "Bank Transection <?php echo date("d-M-Y", strtotime($startDate)) . " to " . date("d-M-Y", strtotime($endDate)); ?>"
                    },
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    },
                    customize: function (win) {
                        $(win.document.body).css('font-size', '12px');
                        $(win.document.body).find('h1').addClass('text-center').css('font-size', '20px');
                        $(win.document.body).find('table').addClass('container').css('font-size', 'inherit');
                        $(win.document.body).find('table').removeClass('table-bordered');
                    }
                }
            ]
        } );
    } );
</script>

<script>
    $(document).ready(function () {
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

<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>