<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
// They are the expense type
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
$SupplierBill = 22;
$CustomerBill = 23;
$discount = 29;
$purchaseReturn = 27;

if (isset($_POST['search'])) {
    extract($_POST);
    $startDate = date('Y-m-d', strtotime($startDate));
    $endDate = date('Y-m-d', strtotime($endDate));
} else {
    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d');
}

$balance_bd = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "entry_date < '$startDate'");

$balance_of_bd = 0;
$total_debit_bd = 0;
$total_credit_bd = 0;
foreach ($balance_bd as $bd) {
    $debit_bd = 0;
    $credit_bd = 0;

    if ($bd['acc_type']==7
        ||$bd['acc_type']==8
        ||$bd['acc_type']==9
        ||$bd['acc_type']==10
    ){continue;}

    if ($bd['acc_type'] == $expenseType || $bd['acc_type'] == $purchasePaymentType
        || $bd['acc_type'] == $supplierPurchaseIndividualPaymentType
        || $bd['acc_type'] == $giveCashToCustomer
        || $bd['acc_type'] == $loanGiveToPersonType
        || $bd['acc_type'] == $CompanyRepayHisLoanType
        || $bd['acc_type'] == $CompanyBackSecurityMoneyToCustomerType
        || $bd['acc_type'] == $CompanyGiveSecurityMoneyToSupplierType
        || $bd['acc_type'] == $CompanyGivePaymentEmployeeType
        || $bd['acc_type'] == $supplierPurchaseIndividualPaymentType
        || $bd['acc_type'] == $CustomerDue
        || $bd['acc_type'] == $SupplierAdvance
        || $bd['acc_type'] == $CustomerBill
        || $bd['acc_type'] == $discount
        || $bd['acc_type'] == $purchaseReturn
        || $bd['acc_type'] == 201
        || $bd['acc_type'] == 202
    ) {
        $debit_bd = $bd['acc_amount'];
        $balance_of_bd -= $debit_bd;
        $total_debit_bd += $debit_bd;
    } else {
        $credit_bd = $bd['acc_amount'];
        $balance_of_bd += $credit_bd;
        $total_credit_bd += $credit_bd;
    }
}

$accountDetails = $sell_details = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "entry_date BETWEEN '$startDate' and '$endDate' ORDER BY `vw_accounts_with_acc_head_other_income`.`entry_date` ASC");

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">

<div class="col-md-12 bg-grey-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>Accounts Statement Between <?php echo date("d-M-Y", strtotime($startDate)) . " to " . date("d-M-Y", strtotime($endDate)); ?></strong>
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
            <button type="submit" name="search" class="btn btn-default"><span class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
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
                <th class="col-md-1 text-center">Date</th>
                <th class="col-md-2 text-center">Reference</th>
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
            $total_credit = 0;

            foreach ($accountDetails as $account) {
                $debit = 0;
                $credit = 0;

                if ($account['acc_type']==7
                    ||$account['acc_type']==8
                    ||$account['acc_type']==9
                    ||$account['acc_type']==10

                ){continue;}

                if ($account['acc_type'] == $expenseType || $account['acc_type'] == $purchasePaymentType
                    || $account['acc_type'] == $supplierPurchaseIndividualPaymentType
                    || $account['acc_type'] == $giveCashToCustomer
                    || $account['acc_type'] == $loanGiveToPersonType
                    || $account['acc_type'] == $CompanyRepayHisLoanType
                    || $account['acc_type'] == $CompanyBackSecurityMoneyToCustomerType
                    || $account['acc_type'] == $CompanyGiveSecurityMoneyToSupplierType
                    || $account['acc_type'] == $CompanyGivePaymentEmployeeType
                    || $account['acc_type'] == $supplierPurchaseIndividualPaymentType
                    || $account['acc_type'] == $CustomerDue
                    || $account['acc_type'] == $SupplierAdvance
                    || $account['acc_type'] == $CustomerBill
                    || $account['acc_type'] == $discount
                    || $account['acc_type'] == $purchaseReturn
                    || $account['acc_type'] == 201
                    || $account['acc_type'] == 202
                ) {
                    $debit = $account['acc_amount'];
                    $balance -= $debit;
                    $total_debit += $debit;
                } else {
                    $credit = $account['acc_amount'];
                    $balance += $credit;
                    $total_credit += $credit;
                }

                $i++;
                ?>
                <tr>
                    <td class="text-center">
                        <?php echo $i; ?>
                    </td>
                    <td class="text-center">
                        <?php echo isset($account['entry_date']) ? date('d-m-Y', strtotime($account['entry_date'])) : null; ?>
                    </td>
                    <td class="">
                        <?php
                        if ($account['acc_head'] != '0') {
                            echo $account['acc_name'];
                        } else {
                            $cus_or_supp_id = $account['cus_or_sup_id'];

                            if ($obj->Total_Count('tbl_supplier', "supplier_id = '$cus_or_supp_id'") > 0) {
                                $sup_data = $obj->details_by_cond('tbl_supplier', "supplier_id = '$cus_or_supp_id'");
                                $name = $sup_data['supplier_name'];
                                $link= "?q=supplier_ledger&supplierId=";

                            } else if ($obj->Total_Count('tbl_customer', "cus_id = '$cus_or_supp_id'") > 0) {
                                $cus_data = $obj->details_by_cond('tbl_customer', "cus_id = '$cus_or_supp_id'");
                                $name = $cus_data['cus_name'];
                                $link= "?q=customer_ledger&customerId=";

                            } else if ($obj->Total_Count('tbl_employee', "employee_id = '$cus_or_supp_id'") > 0) {
                                $employee_data = $obj->details_by_cond('tbl_employee', "employee_id = '$cus_or_supp_id'");
                                $name = $employee_data['employee_name'];
                                $link= "";

                            } else if ($obj->Total_Count('tbl_person', "person_id = '$cus_or_supp_id'") > 0) {
                                $person_data = $obj->details_by_cond('tbl_person', "person_id = '$cus_or_supp_id'");
                                $name = $person_data['person_name'];
                                $link= "?q=view_single_person_loan&personidname=";
                            }
                            //echo $account['acc_type'];
                        if ($account['acc_type']!=25 && $account['acc_type']!=24) {
                            echo '<a href="' . $link . $cus_or_supp_id . '" target="_blank">' . $cus_or_supp_id . ' / ' . $name . '</a>';
                        }
                            }

                        ?>
                    </td>
                    <td class="">
                        <?php echo isset($account['acc_description']) ? $account['acc_description'] : null; ?>
                    </td>
                    <td>
                        <?php
                        $user = $obj->details_by_cond('_createuser','UserId ='.$account['entry_by']);
                        echo '<a href="?q=user_details&token='.$account['entry_by'].'">'.$user['FullName'].'</a>';
                        ?>
                    </td>
                    <td class="text-right">
                        <?php echo isset($credit) ? number_format($credit) . ' tk' : "0"; ?>
                    </td>
                    <td class="text-right">
                        <?php echo isset($debit) ? number_format($debit) . ' tk' : "0"; ?>
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
                    <strong><?php echo ($balance_of_bd>0)?number_format($total_credit+$balance_of_bd):number_format($total_credit); ?></strong> .tk
                </th>
                <th class="col-md-1 text-right">

                    <strong><?php echo ($balance_of_bd<0)?number_format($total_debit+abs($balance_of_bd)):number_format($total_debit); ?></strong> .tk
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
            dom: 'Bfrtip',
            "paging": false,
            "ordering": false,
            buttons: [
                {
                    extend: 'print',
                    text: 'Print',
                    footer: true,
                    title: function () {
                        return "Accounts Statement Between <?php echo date("d-M-Y", strtotime($startDate)) . " to " . date("d-M-Y", strtotime($endDate)); ?> "
                    },
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
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