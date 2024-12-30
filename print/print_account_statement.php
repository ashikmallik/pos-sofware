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

if (isset($_GET['startDate'])) {
    extract($_POST);
    $startDate = date('Y-m-d', strtotime($_GET['startDate']));
    $endDate = date('Y-m-d', strtotime($_GET['endDate']));
} else {
    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d');
}
$accountDetails = $sell_details = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "entry_date BETWEEN '$startDate' and '$endDate' ORDER BY `vw_accounts_with_acc_head_other_income`.`entry_date` ASC");
?>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">

<div class="row" style="margin:15px; 0">
    <button type="submit" class="btn btn-success bg-grey-800 btn-block" onclick="printDiv('statment_print')">Click to Print Below Statement
    </button>
</div>

<div class="row" id="acc_statment_print">
    <div class="col-md-12" style="font-size:12px;" id="statment_print">
        <h2 class="text-center">Accounts Statement Between <?php echo date("d-M-Y", strtotime($startDate)) . " to " . date("d-M-Y", strtotime($endDate)); ?></h2>
        <table class="table table-responsive table-bordered table-hover table-striped">
            <thead>
            <tr class="bg-slate-800">
                <th class="col-md-1 text-center">#</th>
                <th class="col-md-1 text-center">Date</th>
                <th class="col-md-2 text-center">Reference</th>
                <th class="col-md-5 text-center">Description</th>
                <th class="col-md-1 text-center">Credit</th>
                <th class="col-md-1 text-center">Debit</th>
                <th class="col-md-1 text-center">Balance</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0;
            $total_debit = 0;
            $balance = 0;
            $total_credit = 0;

            foreach ($accountDetails as $account) {
                $debit = 0;
                $credit = 0;
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
                    <td class="text-center">
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
                                $link= "";
                            }
                            echo $cus_or_supp_id . ' / ' . $name;
                        }
                        ?>
                    </td>
                    <td class="text-center">
                        <?php echo isset($account['acc_description']) ? $account['acc_description'] : null; ?>
                    </td>
                    <td class="text-center">
                        <?php echo isset($credit) ? number_format($credit) . ' tk' : "0"; ?>
                    </td>
                    <td class="text-center">
                        <?php echo isset($debit) ? number_format($debit) . ' tk' : "0"; ?>
                    </td>
                    <td class="text-center">
                        <?php echo isset($balance) ? number_format($balance) . ' tk' : "0"; ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
            <tr class="bg-grey">
                <th colspan="4" class="text-center">Total</th>

                <th class="col-md-1 text-center">
                    <strong><?php echo number_format($total_credit); ?></strong> .tk
                </th>
                <th class="col-md-1 text-center">
                    <strong><?php echo number_format($total_debit); ?></strong> .tk
                </th>
                <th class="col-md-1 text-center">
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
        //document.body.innerHTML = originalContents;
    }

    printDiv('statment_print');
</script>