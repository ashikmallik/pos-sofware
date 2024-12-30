<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$expenseType = 1;
$purchasePaymentType = 2;
$sellReceivedPaymentType = 3;
$otherIncomeType = 4;
$customerSelIndividualPaymentType = 5;
$supplierPurchaseIndividualPaymentType = 6;


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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
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
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-3" style="padding-top:5px" for="dateYear">Select Year</label>
                <div class="col-sm-9">
                    <select class="form-control" required="required" name="dateYear" id="status">
                        <option></option>
                        <?php for($i = 2017; $i <= 2025; $i++){
                            echo '<option value="'.$i.'"';
                            echo (date('Y') == $i)? " selected ": "";
                            echo '>'.$i.'</option>';
                        } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
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
                <th class="col-md-1 text-center">#</th>
                <th class="col-md-1 text-center">Month</th>
                <th class="col-md-1 text-center">
                    <small>Opening Balance</small>
                </th>
                <th class="col-md-1 text-center"><small>Buy Qty</small></th>
                <th class="col-md-1 text-center"><small>Buy Price (tk)</small></th>
                <th class="col-md-1 text-center"><small>Sell Qty</small></th>
                <th class="col-md-1 text-center"><small>Sell Price (tk)</small></th>
                <th class="col-md-1 text-center"><small>Received Amount (tk)</small></th>
                <th class="col-md-1 text-center"><small>Pay Amount (tk)</small></th>
                <th class="col-md-1 text-center"><small>Other Income (tk)</small></th>
                <th class="col-md-1 text-center"><small>Add Capital (tk)</small></th>
                <th class="col-md-1 text-center">
                    <small>Expense Statement (tk)</small>
                </th>
                <th class="col-md-1 text-center">
                    <small>Closing Balance (tk)</small>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (isset($_POST['search'])) {

                $dateYear = $_POST['dateYear'];
            }else {

                $dateYear = date('Y');

            }

            $max = 12;
            $i = 0;
            $opening_balance = 0;
            $year_total = 0;
            $yearly_expense = 0;
            $yearly_other_income = 0;
            $yearly_received_amount = 0;
            $yearly_pay_amount = 0;
            $month_digit = date('n', strtotime(($date)));
            $year_digit = date('Y', strtotime(($date)));
            if ($year_digit == $dateYear) {
                $max = $month_digit;
            }
            for ($mnth = 1; $mnth <= $max; $mnth++) {

                $allPurchasePriceGivenCount = 0;
                $total_purchase_qty = 0;
                $total_purchase_price = 0;
                $total_sell_qty = 0;
                $total_sell_price = 0;
                $total_other_income = 0;
                $total_expense = 0;
                $total_purchase_payment = 0;
                $total_sell_received_payment = 0;
                $total_customer_sell_individual_payment = 0;
                $total_supplier_purchase_individual_payment = 0;

                $i++;
                // Total Purchase Qty and Price Calculate
                // ------------++--------------
                $purchase_details = $obj->details_by_cond("vw_purchase_monthly", "MONTH(entry_date)='$mnth' and 
                    YEAR (entry_date)='$dateYear'");

                $allPurchasePriceGivenCount = $obj->Total_Count('vw_purchase', "all_price_status = 0 AND MONTH
                    (entry_date)='$mnth' and 
                    YEAR (entry_date)='$dateYear'");

                $total_purchase_qty = $purchase_details['total_qty'];
                $total_purchase_price = $purchase_details['total_price'];

                // Total Sell Qty and Price Calculate
                // ------------++--------------
                $sell_details = $obj->details_by_cond("vw_sell_monthly", "MONTH(entry_date)='$mnth' and YEAR (entry_date)='$dateYear'");

                $allSellPriceGivenCount = $obj->Total_Count('vw_sell', "all_price_status = 0 AND MONTH (entry_date)='$mnth' and 
                    YEAR (entry_date)='$dateYear'");

                $total_sell_qty = $sell_details['total_qty'];
                $total_sell_price = $sell_details['total_price'];


                // Other Income Calculate
                // ------------++--------------
                $total_other_income = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = '$otherIncomeType' AND MONTH(entry_date) ='$mnth' and YEAR (entry_date) ='$dateYear' ");
                $total_other_income_not_opening = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = '$otherIncomeType' AND acc_head !=1 AND MONTH(entry_date) ='$mnth' and YEAR (entry_date) ='$dateYear' ");
                $total_other_income_opening = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = '$otherIncomeType' AND acc_head =1 AND MONTH(entry_date) ='$mnth' and YEAR (entry_date) ='$dateYear' ");

                // Expense Calculate
                // ------------++--------------
                $total_expense = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = '$expenseType' AND MONTH(entry_date) ='$mnth' and YEAR (entry_date) ='$dateYear' ");

//pay amount
                $total_company_repay_loan_to_person = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $CompanyRepayHisLoanType AND MONTH(entry_date) ='$mnth' and YEAR (entry_date) ='$dateYear' ");
                $total_company_back_security_money_to_customer = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $CompanyBackSecurityMoneyToCustomerType AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_company_provide_security_money_to_supplier = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $CompanyGiveSecurityMoneyToSupplierType AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_company_give_payment_to_employee = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $CompanyGivePaymentEmployeeType AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_company_give_loan_to_person = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $companyGiveLoanToPerson AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_give_cash_to_customer = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $giveCashToCustomer AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_supplier_purchase_individual_payment = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $supplierPurchaseIndividualPaymentType AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_purchase_payment = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $purchasePaymentType AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");

//receive amount
                $total_company_take_loan_from_person = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $companyTakeLoanFromPerson AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_company_received_security_money_from_customer = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $companyReceivedSecurityMoneyFromCustomer AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_supplier_back_security_money_to_company = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $supplierBackSecurityMoneyToCompany AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_person_repay_loan_to_company = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $personRepayLoanToCompany AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_receive_cash_from_supplier = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $receiveCashFromSupplier AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_customer_sell_individual_payment = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $customerSelIndividualPaymentType AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");
                $total_sell_received_payment = $obj->get_sum_data('tbl_account', 'acc_amount', "acc_type = $sellReceivedPaymentType AND MONTH(entry_date) ='$mnth' AND YEAR (entry_date) ='$dateYear' ");



                $yearly_pay_amount += $total_pay_amount = $total_purchase_payment +
                    $total_supplier_purchase_individual_payment + $total_give_cash_to_customer +
                    $total_company_give_loan_to_person + $total_company_give_payment_to_employee +
                    $total_company_provide_security_money_to_supplier + $total_company_back_security_money_to_customer+
                    $total_company_repay_loan_to_person;



                $yearly_received_amount += $total_received_amount = $total_sell_received_payment +
                    $total_customer_sell_individual_payment + $total_receive_cash_from_supplier +
                    $total_person_repay_loan_to_company + $total_supplier_back_security_money_to_company +
                    $total_company_received_security_money_from_customer + $total_company_take_loan_from_person
                ;



                $yearly_expense += $total_expense;


                $yearly_other_income += $total_other_income;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td style="text-align: center;">
                        <?php echo date('M', strtotime('1.' . $mnth . '.' . $dateYear)); ?>
                    </td>
                    <td style="text-align: right;">
                        <?php echo $opening_balance ?>
                    </td>

                    <td style="text-align: right;"
                        class="<?php echo ($allPurchasePriceGivenCount != 0) ? 'bg-danger' : '';
                        ?>">
                        <?php echo isset($total_purchase_qty) ? $total_purchase_qty : '0' ?>
                    </td>
                    <td style="text-align: right;"
                        class="<?php echo ($allPurchasePriceGivenCount != 0) ? 'bg-danger' : '';
                        ?>">
                        <?php if ($allPurchasePriceGivenCount != 0) {
                            echo '<span href="#" data-toggle="tooltip" title="You have ' . $allPurchasePriceGivenCount . ' bill rate pending . 
                                Please fill first!">';
                            echo isset($total_purchase_price) ? number_format($total_purchase_price) : '0';
                            echo '</span>';
                        } else {
                            echo isset($total_purchase_price) ? number_format($total_purchase_price) : '0';
                        } ?>

                    </td>

                    <td style="text-align: right;" class="<?php echo ($allSellPriceGivenCount != 0) ? 'bg-danger' :
                        '';
                    ?>">
                        <?php echo isset($total_sell_qty) ? $total_sell_qty : '0' ?>
                    </td>
                    <td style="text-align: right;" class="<?php echo ($allSellPriceGivenCount != 0) ? 'bg-danger' : '';
                    ?>">
                        <?php if ($allSellPriceGivenCount != 0) {
                            echo '<span href="#" data-toggle="tooltip" title="You have ' . $allSellPriceGivenCount . ' Invoice rate pending . 
                                Please fill first!">';
                            echo isset($total_sell_price) ? number_format($total_sell_price) : '0';
                            echo '</span>';
                        } else {
                            echo isset($total_sell_price) ? number_format($total_sell_price) : '0';
                        } ?>

                    </td>

                    <td style="text-align: right;">
                        <?php echo isset($total_received_amount) ? number_format($total_received_amount) : '0'; ?>
                    </td>

                    <td style="text-align: right;">
                        <?php echo isset($total_pay_amount) ? number_format($total_pay_amount) : '0'; ?>
                    </td>

                    <td style="text-align: right;">
                        <?php echo ( isset($total_other_income_not_opening)) ? number_format($total_other_income_not_opening) : '0'; ?>
                    </td>

                    <td style="text-align: right;">
                        <?php echo ( isset($total_other_income_opening)) ? number_format($total_other_income_opening) : '0'; ?>
                    </td>

                    <td style="text-align: right;">
                        <?php echo ( isset($total_expense) ) ? number_format($total_expense) : '0'; ?>

                    </td>

                    <td style="text-align: right;"><b>
                            <?php
                            $closing_balance = ($total_received_amount + $total_other_income + $opening_balance) - ($total_pay_amount + $total_expense);
                            echo number_format($closing_balance);
                            $opening_balance = $closing_balance;
                            ?>
                        </b>
                    </td>
                </tr>
                <?php
            } // for loop

            ?>

            <tr>
                <td colspan="9" style="text-align: right;">Total Other Income & Capital Amount:</td>
                <td colspan="3" style="text-align: right;"><?php echo number_format($yearly_other_income) ?> tk</td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: right;">Total Received Amount:</td>
                <td colspan="3" style="text-align: right;"><?php echo number_format($yearly_received_amount) ?> tk</td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: right;">Total Pay Amount:</td>
                <td colspan="3" style="text-align: right;"><?php echo number_format($yearly_pay_amount); ?> tk</td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: right;">Total Expense Amount:</td>
                <td colspan="3" style="text-align: right;"><?php echo number_format($yearly_expense) ?> tk</td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: right;">Cash In hand:</td>
                <td colspan="3" style="text-align: right;"><?php
                    $cash_in_hand = (($yearly_received_amount + $yearly_other_income) - ($yearly_pay_amount + $yearly_expense));

                    echo number_format($cash_in_hand); ?> tk</td>
            </tr>
            <tr>
                <?php
                $totalw = $formater->convert_number_to_words($cash_in_hand);
                ?>
                <td colspan="12" style="text-align: center;"><span style="color: green;">Total Ammount In Word:&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $totalw; ?></span></span></td>
            </tr>
            </tbody>
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