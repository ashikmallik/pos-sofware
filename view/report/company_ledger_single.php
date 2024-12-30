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
$loanGiveToPersonType = 13;
$CustomerDue = 8;
$SupplierDue = 10;
$sell_product_to_customer = 23;
$bank_deposit = 25;

$credit = 0;
$debit = 0;
$total_sec_give=0;
$total_sec_back=0;
$total_sec_money=0;

$allSupplierSecurityMoneyReceive = $obj->view_selected_field_by_cond_left_join("tbl_security_money_transaction", 'tbl_supplier', 'supplier_id', 'id', 'SUM(tbl_security_money_transaction.amount) as total_receive', '*', 'tbl_security_money_transaction.pay_receive != 1 AND tbl_security_money_transaction.supplier_id != 0 GROUP BY tbl_security_money_transaction.`supplier_id`');

foreach ($allSupplierSecurityMoneyReceive as $supplierSecMoney) {
    $total_sec_give+=$supplierSecMoney['total_receive'];
    $supplierTotalSecMoneyBackData = $obj->details_selected_field_by_cond('tbl_security_money_transaction','SUM(amount) as total_back_amount','pay_receive = 1 AND supplier_id = '.$supplierSecMoney['id']);
    $supplierTotalSecMoneyBack = isset($supplierTotalSecMoneyBackData['total_back_amount']) ? $supplierTotalSecMoneyBackData['total_back_amount'] : 0;
    $total_sec_back+=$supplierTotalSecMoneyBack;
}

$total_sec_money = $total_sec_give - $total_sec_back;

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h3><strong>Company Ledger</strong></h3>
    </div>

    <div class="col-md-6" style="padding-top:15px;">
        <button type="submit" class="btn btn-primary bg-teal btn-sm pull-right" onclick="printDiv('year_table')">Print Statement
        </button>
    </div>
</div>
<!--<div class="col-md-12 bg-teal-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateMonth">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" required="required" type="text" name="startDate" autocomplete="off" value="<?php if (isset($_POST['startDate'])){ echo $_POST['startDate'];}?>">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="damageRate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" required="required" name="endDate" autocomplete="off" value="<?php if (isset($_POST['endDate'])){ echo $_POST['endDate'];}?>">
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default"><span class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>-->

<div class="row" id="year_table">
    <h4 class="text-center"><?php
    echo strtoupper($_GET['type']).' STATEMENT';
    if (isset($_POST['search'])){echo $_POST['startDate'].' to '.$_POST['endDate'];}else{echo '';} ?>  </h4>
    <div class="col-md-12">
        <table class="table table-responsive table-bordered table-hover table-striped">
            <thead>
                <tr class="bg-grey-800">
                    <th class="col-md-3 text-center">Sector</th>
                    <th class="col-md-1 text-center">
                        <small>Amount</small>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_POST['search'])) {
                //$dateYear = $_POST['dateYear'];
                    $startDate = date('Y-m-d',strtotime($_POST['startDate']));
                    $endDate = date('Y-m-d',strtotime($_POST['endDate']));
                    $where = "AND entry_date BETWEEN '$startDate' and '$endDate'";
                } else {
                    $dateYear = date('Y');
                    $startDate = date('Y-m-d');
                    $endDate = date('Y-m-d');
                    $where = "";
                }

                $accountDetails= $obj->view_all_by_cond("tbl_account", "NOT (acc_type=22 OR acc_type=23 OR acc_type=10 OR acc_type =8 OR acc_type =7 OR acc_type =9 ) AND payment_method=1 AND opening_status=0");

                $total_debit = 0;
                $total_credit = 0;
                $cash_balance = 0;
                $supplier_advance =0;
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
                        || $account['acc_type'] == $SupplierDue
                        || $account['acc_type'] == $sell_product_to_customer
                        || $account['acc_type'] == $bank_deposit
                    ) {
                        $debit = $account['acc_amount'];
                        $cash_balance -= $debit;
                        $total_debit += $debit;
                    } else {
                        $credit = $account['acc_amount'];
                        $cash_balance += $credit;
                        $total_credit += $credit;
                    }
                }

                $balance_bd_bank = 0;
                foreach ($obj->view_all("bank_account") as $value_bd) {
                    $balance_bd_bank += $value_bd['credit'];
                    $balance_bd_bank -= $value_bd['debit'];
                }

               /* $stock_avarege = $obj->stock_avg('vw_purchase_with_price','redAavg','redBavg','redCavg','whiteAavg','whiteBavg','whiteCavg','duckEggAvg','birdEggAvg','damageAvg');

                $total_stock_price = ($stock_avarege['redAavg']*$redAStock)+
                ($stock_avarege['redBavg']*$redBStock)+
                ($stock_avarege['redCavg']*$redCStock)+
                ($stock_avarege['whiteAavg']*$whiteAStock)+
                ($stock_avarege['whiteBavg']*$whiteBStock)+
                ($stock_avarege['whiteCavg']*$whiteCStock)+
                ($stock_avarege['duckEggAvg']*$duckEggStock)+
                ($stock_avarege['birdEggAvg']*$birdEggStock)+
                ($stock_avarege['damageAvg']*$damageStock);*/
                $total_capital =0;

                $customerTransaction = $obj->get_sum_data("tbl_sell", "total_price","total_price!=0 ".$where);

                $customerRecieved = $obj->get_sum_data("tbl_account", "acc_amount ","acc_type=3 OR acc_type=5 ".$where);

                $customerDiscount = $obj->get_sum_data("discount", "amount", "cus_or_sup_id LIKE 'CUS%'  ".$where);
                $openingCustomerDueBalance = $obj->get_sum_data("tbl_account",'acc_amount', "acc_type=8  ".$where);
                $customerOpeningAdvance = $obj->get_sum_data("tbl_account", 'acc_amount',"acc_type=7 ".$where);

                $givePaymentToCustomer = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 12 AND cus_or_sup_id LIKE 'CUS%' ".$where);

                $total_customer_due = ($customerTransaction - $customerRecieved - $customerDiscount + $openingCustomerDueBalance - $customerOpeningAdvance)+ $givePaymentToCustomer;


                $supplierOpeningAdvance = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type = 9 ".$where);

              //  $supplierTransaction = $obj->get_sum_data("tbl_bill",'total_price', "total_price!=0 ".$where);

                $supplierRecieved = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type = 2 OR acc_type = 6 ".$where);

                $supplierDiscount = $obj->get_sum_data("discount","amount", "cus_or_sup_id LIKE 'SUP%'   ".$where);
                $supplierOpeningDue = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type = 10 ".$where);

                $receiveCashFromSupplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 11 AND cus_or_sup_id LIKE 'SUP%' ".$where);
                $receiveCashFromSupplierBack = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 18 AND cus_or_sup_id LIKE 'SUP%' ".$where);




                // $receiveSecurityMoney = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 17 AND cus_or_sup_id LIKE 'SUP%' ".$where);

                // $giveSecurityMoney = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 19 AND cus_or_sup_id LIKE 'SUP%' ".$where);
                // $giveSecurityMoneyBack = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 20 AND cus_or_sup_id LIKE 'SUP%' ".$where);

                $total_supplier_due = ($supplierOpeningAdvance  + $supplierRecieved - $supplierDiscount - $supplierOpeningDue)-$receiveCashFromSupplier;

                $total_supplier_advance = $supplierOpeningAdvance + $receiveCashFromSupplier;

                // $personData         = $obj->view_all("tbl_person");
                $loan_receive       = 0;
                $loan_payable       = 0;
                $loan_receivable    = 0;
                $total_receivable   = 0;
                $total_payable      = 0;

                // foreach ($personData as $person) {
                  
                //   $personId       = $person['person_id'];

                //     $cgltp = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 13 AND cus_or_sup_id='$personId'");
                    
                //     $prltc = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 14 AND cus_or_sup_id='$personId'");
                    
                //     $ctlfp = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 15 AND cus_or_sup_id='$personId'");

                //     $crltp = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 16 AND cus_or_sup_id='$personId'");

                //     $loanBal = ($prltc+$ctlfp)-($cgltp+$crltp);
                    
                //     // $personId       = $person['id'];
                //     // $loanData       = $obj->raw_sql("(SUM(loan_give)- SUM(loan_recieve))AS loan_bal FROM `tbl_loan` WHERE `person_id` =".$personId. $where);
                //     // $loanBal   = $loanData[0]['loan_bal'];

                //     if($loanBal > 0 ){
                //         $loan_payable += $loanBal;
                //     }else{
                //         $loan_receivable += $loanBal;
                //     }

                // }

               $tpl=0;$tplr=0;
                $allPersonLoan = $obj->view_selected_field_by_cond_left_join("tbl_person_loan", 'tbl_person', 'person_id', 'id', 'SUM(tbl_person_loan.loan_recieve) as total_loan_recieve, SUM(tbl_person_loan.loan_repayment) as total_loan_repayment', '*', "tbl_person_loan.id != 0 GROUP BY tbl_person_loan.`person_id`");
                  foreach ($allPersonLoan as $personLoan) {
                    $tpl += isset($personLoan['total_loan_recieve'])?$personLoan['total_loan_recieve']:0;
                    $tplr += isset($personLoan['total_loan_repayment'])?$personLoan['total_loan_repayment']:0; 
                  }
                $loan_receivable =$tpl-$tplr;
                  
                $tcpl=0;$tcplr=0;
                $allcompanyPersonLoan = $obj->view_selected_field_by_cond_left_join("tbl_company_lend", 'tbl_person', 'person_id', 'id', 'SUM(tbl_company_lend.loan_recieve) as total_loan_recieve, SUM(tbl_company_lend.loan_repayment) as total_loan_repayment', '*', "tbl_company_lend.id != 0  GROUP BY tbl_company_lend.`person_id`");
                 foreach ($allcompanyPersonLoan as $cpersonLoan) {
                    $tcpl += isset($cpersonLoan['total_loan_recieve'])?$cpersonLoan['total_loan_recieve']:0;
                    $tcplr += isset($cpersonLoan['total_loan_repayment'])?$cpersonLoan['total_loan_repayment']:0; 
                  }
                  
                 $loan_payable=$tcpl-$tcplr;


                $total_customer_due = 0;
                $total_customer_advance =0;
                $all_customer = $obj->view_all('tbl_customer');
                foreach ($all_customer as $customer){

                    $customerId = isset($customer['cus_id']) ? $customer['cus_id'] : null;
                    $customerPersonalData = $obj->details_by_cond("tbl_customer", "`cus_id` = '$customerId'");

                    $customerName = $customerPersonalData['cus_name'];

                    $supplierOrCustomerTransaction = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$customerId'");
                    $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved", "supplier_customer='$customerId'");

                    $givePaymentToCustomer = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 12 AND cus_or_sup_id='$customerId'");
                    
                    $labortransporcostForCustomer = $obj->get_sum_data('tbl_account', 'acc_amount', " cus_or_sup_id='$customerId' AND (acc_type = 201 OR acc_type = 202)");

                    $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$customerId'");
                    $sales_return = $obj->get_sum_data('tbl_account','acc_amount', "acc_type =28 AND cus_or_sup_id='$customerId'");

                    $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$customerId'");

                    $discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$customerId'");

                    isset($discountData) ? $discount = $discountData['amount'] : $discount = 0;

                    isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0;

                    isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0;

                    $total_due_c = ($supplierOrCustomerTransaction['total_price'] - $supplierOrCustomerRecieved['total_recieved'] + $openingDueBalance - $openingAdvance)+ $givePaymentToCustomer+(($labortransporcostForCustomer > 0)?$labortransporcostForCustomer:0);
                    $total_due_c -= $discount;
                   if($sales_return > 0){
                        $total_due_c = $total_due_c - $sales_return;
                        }
                        else
                        {
                            $total_due_c = $total_due_c;
                        }
                    if ($total_due_c < 0){
                        $total_customer_advance += abs($total_due_c);
                    }else{
                        $total_customer_due += abs($total_due_c);
                    }
                }


                $all_supplier = $obj->view_all('tbl_supplier');

                $total_supplier_due =0;
                $total_supplier_advance =0;
                $i = 0;
                foreach ($all_supplier as $supplier){
                    $supplierId = isset($supplier['supplier_id']) ? $supplier['supplier_id'] : null;

                    $supplierPersonalData = $obj->details_by_cond("tbl_supplier", "`supplier_id` = '$supplierId'");

                    $supplierName = $supplierPersonalData['supplier_name'];

                    $supplierOrCustomerTransaction = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$supplierId'");
                    $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved","supplier_customer='$supplierId'");

                    $receiveCashFromSupplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 11 AND cus_or_sup_id='$supplierId'");

                    $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance","supplier_customer='$supplierId'");

                    $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance","supplier_customer='$supplierId'");

                    $giveSecurityMoney = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type=19 AND cus_or_sup_id='$supplierId'");

                    $backSecurityMoney = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type=20 AND cus_or_sup_id='$supplierId'");

                    $supplierSecurityMoney = $giveSecurityMoney - $backSecurityMoney;
                    
                    $purchse_return = $obj->get_sum_data('tbl_return','total_return_price',"type=1 AND cus_or_sup_id='$supplierId'");

                    $discountData = $obj->details_selected_field_by_cond("discount","sum(`amount`) as amount", "cus_or_sup_id='$supplierId'");


                    isset($discountData) ? $discount = $discountData['amount'] : $discount = 0 ;
                    isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0 ;
                    isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0 ;
                    

                    // $total_due_s = ($openingAdvance - $supplierOrCustomerTransaction['total_price'] + $supplierOrCustomerRecieved['total_recieved'] - $discount - $openingDueBalance)-($receiveCashFromSupplier-$purchse_return);
           


                   

                        //add
                        $mpurchase_payment = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 2 AND cus_or_sup_id='$supplierId'");
                        $msupplier_individual_payment = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 6 AND cus_or_sup_id='$supplierId'");
                        $msupplier_advance = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 9 AND cus_or_sup_id='$supplierId'");
                        $mcompany_provide_s_money_to_supplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 19 AND cus_or_sup_id='$supplierId'");

                        //minuse
                        $msupplier_due = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 10 AND cus_or_sup_id='$supplierId'");
                        $msupplier_back_s_money_to_company = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 20 AND cus_or_sup_id='$supplierId'");
                        $mpurchase_product_from_supplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 22 AND cus_or_sup_id='$supplierId'");
                        
                        $total_due_s =($mpurchase_payment +$msupplier_individual_payment +$msupplier_advance+$mcompany_provide_s_money_to_supplier+$purchse_return+$discount) -( $msupplier_due + $msupplier_back_s_money_to_company + $mpurchase_product_from_supplier +$receiveCashFromSupplier);



                      if ($total_due_s < 0){
                        $total_supplier_due += abs($total_due_s);
                      
                    }else{
                        $total_supplier_advance += abs($total_due_s);
                    
                        }
                        
                        
                        
                }

                $total_supplier_advance;

                $total_due_s;


                $balance = 0;

                if ($_GET['type']=='receivable'){
                    ?>
                    <tr>
                        <td style="padding-left: 30px;">Total Customer Due</td>
                        <td class="text-right">
                            <?php 
                            echo number_format($total_customer_due);
                            $total_receivable += $total_customer_due;
                            ?>                    
                        </td>
                    </tr>

                </tr>

                <tr>
                    <td style="padding-left: 30px;">Total Supplier Advance</td>
                    <td class="text-right">
                        <?php echo number_format($total_supplier_advance); 
                        $total_receivable += $total_supplier_advance;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">Total Security Money</td>
                    <td class="text-right">
                        <?php

                        if ($total_sec_money > 0) {
                            echo number_format(abs($total_sec_money));
                            $total_receivable += $total_sec_money;
                        }else{
                            echo 0;
                        }


                        ?>                    
                    </td>
                </tr>

                <tr>
                    <td style="padding-left: 30px;">Total Loan Receivable</td>
                    <td class="text-right"><?php
                    echo number_format(abs($loan_receivable));
                    $total_receivable += abs($loan_receivable);
                    ?>   
                </td>
            </tr>



            <tr>
                <th class="bg-success">Total Receivable</th>
                <th class="text-right bg-success"><?php echo number_format($total_receivable);
                ?> </th>
            </tr>
        <?php } else {?>
            <tr>
                <td style="padding-left: 30px;">Total Customer Advance</td>
                <td class="text-right">
                    <?php
                    echo number_format($total_customer_advance);
                    $total_payable += $total_customer_advance;
                    ?>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 30px;">Total Supplier Due</td>
                <td class="text-right"><?php 
                echo number_format($total_supplier_due);
                $total_payable += $total_supplier_due;                    
                ?> </td>
            </tr>


            <tr>
                <td style="padding-left: 30px;">Total Security Money</td>
                <td class="text-right">
                    <?php
                    if ($total_sec_money < 0) {
                        echo number_format(abs($total_sec_money));
                        $total_payable += abs($total_sec_money);
                    }else{
                        echo 0;
                    }                                            
                    ?>                    
                </td>
            </tr>

            <tr>
                <td style="padding-left: 30px;">Total Loan Payable</td>
                <td class="text-right">
                    <?php
                    echo number_format(abs($loan_payable));
                    $total_payable += abs($loan_payable); 
                    ?>
                </td>
            </tr>

            <tr>
                <th class="bg-success">Total Payable</th>
                <th class="text-right bg-success"><?php echo number_format($total_payable);
                ?> </th>
            </tr>
        <?php } ?>

    </tbody>
    <tfoot>

    </tfoot>
</table>
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