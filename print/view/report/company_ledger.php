<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

// this below account type not used in code, just for reference.
$expenseType = 1;
$purchasePaymentType = 2;
$sellReceivedPaymentType = 3;
$otherIncomeType = 4;
$customerSelIndividualPaymentType = 5;
$supplierPurchaseIndividualPaymentType = 6;
$customerAdvance = 7;
$customerDue = 8;
$supplierAdvance = 9;
$supplierDue = 10;
$receiveCashFromSupplier = 11;
$giveCashToCustomer = 12;
$companyGiveLoanToPerson = 13;
$personRepayLoanToCompany = 14;
$companyTakeLoanFromPerson = 15;
$CompanyRepayHisLoanType = 16;
$companyReceivedSecurityMoneyFromCustomer = 17;
$CompanyBackSecurityMoneyToCustomerType = 18;
$CompanyGiveSecurityMoneyToSupplierType = 19;
$supplierBackSecurityMoneyToCompany = 20;
$CompanyGivePaymentEmployeeType = 21;

?>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h3><strong>The Yearly Statement
                of <?php echo isset($_POST['dateYear']) ? $_POST['dateYear'] : date('Y'); ?></strong></h3>
    </div>

    <div class="col-md-6" style="padding-top:15px;">
        <button type="submit" class="btn btn-primary bg-teal btn-sm pull-right"
                onclick="printDiv('year_table')">Print Statement
        </button>
    </div>
</div>
<div class="col-md-12 bg-teal-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-7">
            <div class="form-group">
                <label class="control-label col-sm-3" style="padding-top:5px" for="dateYear">Select Year</label>
                <div class="col-sm-9">
                    <select class="form-control" required="required" name="dateYear" id="status">
                        <option></option>
                        <?php for ($i = 2017; $i <= 2025; $i++) {
                            echo '<option value="' . $i . '"';
                            echo (date('Y') == $i) ? " selected " : "";
                            echo '>' . $i . '</option>';
                        } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-md-offset-3">
            <button type="submit" name="search" class="btn btn-success"><span
                        class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>

<div class="row" id="year_table">
    <div class="col-md-12">
        <table class="table table-responsive table-bordered table-hover table-striped">
            <thead>
            <tr class="bg-grey-800">
                <th class="col-md-3 text-center">Sector</th>
                <th class="col-md-1 text-center">
                    <small>January</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>February</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>March</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>April</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>May</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>June</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>July</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>August</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>September</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>October</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>November</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>December</small>
                </th>

            </tr>
            </thead>
            <tbody>
            <?php
            if (isset($_POST['search'])) {

                $dateYear = $_POST['dateYear'];
            } else {

                $dateYear = date('Y');

            }

            $totalJanuary = 0;
            $totalFeb = 0;
            $totalMarch = 0;
            $totalApril = 0;
            $totalMay = 0;
            $totalJune = 0;
            $totalJuly = 0;
            $totalAugust = 0;
            $totalSeptember = 0;
            $totalOctober = 0;
            $totalNovember = 0;
            $totalDecember = 0;

            foreach ($obj->view_all('acc_type_list') as $accType) {

                $totalJanuary += $january = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 1 and YEAR (entry_date) ='$dateYear' ");
                $totalFeb += $february = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 2 and YEAR (entry_date) ='$dateYear' ");
                $totalMarch += $march = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 3 and YEAR (entry_date) ='$dateYear' ");
                $totalApril += $april = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 4 and YEAR (entry_date) ='$dateYear' ");
                $totalMay += $may = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 5 and YEAR (entry_date) ='$dateYear' ");
                $totalJune += $june = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 6 and YEAR (entry_date) ='$dateYear' ");
                $totalJuly += $july = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 7 and YEAR (entry_date) ='$dateYear' ");
                $totalAugust += $august = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 8 and YEAR (entry_date) ='$dateYear' ");
                $totalSeptember += $september = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 9 and YEAR (entry_date) ='$dateYear' ");
                $totalOctober += $october = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 10 and YEAR (entry_date) ='$dateYear' ");
                $totalNovember += $november = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 11 and YEAR (entry_date) ='$dateYear' ");
                $totalDecember += $december = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = " . $accType['id'] . " AND MONTH(entry_date) = 12 and YEAR (entry_date) ='$dateYear' ");

                if( $january == 0 && $february == 0 && $march == 0 && $april == 0
                    && $may == 0 && $june == 0 && $july == 0 && $august == 0
                    && $september == 0 && $october == 0 && $november == 0 && $december == 0 ){
                    continue;
                }
                ?>
                <tr>
                    <td><?php echo $accType['description']; ?></td>

                    <td class="text-center"><?php echo number_format($january) ?> </td>
                    <td class="text-center"><?php echo number_format($february) ?> </td>
                    <td class="text-center"><?php echo number_format($march) ?> </td>
                    <td class="text-center"><?php echo number_format($april) ?> </td>
                    <td class="text-center"><?php echo number_format($may) ?> </td>
                    <td class="text-center"><?php echo number_format($june) ?> </td>
                    <td class="text-center"><?php echo number_format($july) ?> </td>
                    <td class="text-center"><?php echo number_format($august) ?> </td>
                    <td class="text-center"><?php echo number_format($september) ?> </td>
                    <td class="text-center"><?php echo number_format($october) ?> </td>
                    <td class="text-center"><?php echo number_format($november) ?> </td>
                    <td class="text-center"><?php echo number_format($december) ?> </td>
                </tr>
                <?php
            } // foreach

            ?>

            </tbody>
            <tfoot>
            <tr class="bg-grey-800">
                <td>Total </td>

                <td class="text-center"><?php echo number_format($totalJanuary) ?> </td>
                <td class="text-center"><?php echo number_format($totalFeb) ?> </td>
                <td class="text-center"><?php echo number_format($totalMarch) ?> </td>
                <td class="text-center"><?php echo number_format($totalApril) ?> </td>
                <td class="text-center"><?php echo number_format($totalMay) ?> </td>
                <td class="text-center"><?php echo number_format($totalJune) ?> </td>
                <td class="text-center"><?php echo number_format($totalJuly) ?> </td>
                <td class="text-center"><?php echo number_format($totalAugust) ?> </td>
                <td class="text-center"><?php echo number_format($totalSeptember) ?> </td>
                <td class="text-center"><?php echo number_format($totalOctober) ?> </td>
                <td class="text-center"><?php echo number_format($totalNovember) ?> </td>
                <td class="text-center"><?php echo number_format($totalDecember) ?> </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    $('select').selectpicker();

</script>