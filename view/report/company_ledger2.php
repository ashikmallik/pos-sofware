
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
                    <input type="text" class="form-control" required="required" name="endDate" autocomplete="off" value="<?php if (isset($_POST['endDate'])){ echo $_POST['endDate'];}?>"/>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="_search" class="btn btn-default"><span class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>-->

<div class="row" id="year_table">
    <h4 class="text-center">Company Ledger <?php if (isset($_POST['search'])){echo $_POST['startDate'].' to '.$_POST['endDate'];}else{echo '';} ?> </h4>
    <div class="col-md-12">
        <table class="table table-responsive table-bordered table-hover table-striped">
            <thead>
                <tr class="bg-grey-800">
                    <th class="col-md-3 text-center">Title</th>
                    <th class="col-md-1 text-center">
                        <small>Credit</small>
                    </th>
                    <th class="col-md-1 text-center">
                        <small>Debit</small>
                    </th>
                    <th class="col-md-1 text-center">
                        <small>Balance</small>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_POST['search'])) {
                    $startDate = date('Y-m-d',strtotime($_POST['startDate']));
                    $endDate = date('Y-m-d',strtotime($_POST['endDate']));
                    $where = "AND entry_date BETWEEN '$startDate' AND '$endDate'";

                    // $accountDetails= $obj->view_all_by_cond("tbl_account", "NOT (acc_type=22 OR acc_type=23 OR acc_type=10 OR acc_type =8 OR acc_type =7 OR acc_type =9 OR acc_type =29 OR acc_type =30 ) AND payment_method=1 '$where'");
                } else {
                   $dateYear = date('Y');
                    $startDate = date('Y-m-d');
                    $endDate = date('Y-m-d');
                    $where = '';
                    // $accountDetails= $obj->view_all_by_cond("tbl_account", "NOT (acc_type=22 OR acc_type=23 OR acc_type=10 OR acc_type =8 OR acc_type =7 OR acc_type =9 OR acc_type =29 OR acc_type =30 ) AND payment_method=1");
                    
                    //  $accountDetails = $sell_details = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "payment_method=1 AND entry_date BETWEEN '$startDate' and '$endDate' ORDER BY `vw_accounts_with_acc_head_other_income`.`entry_date` ASC");
                    //  $accountDetails= $obj->view_all_by_cond("tbl_account", "payment_method=1");
                }
                
               

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
$bankDeposit = 25;
$bankWithdrow = 24;


    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d');


$balance_bd = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "payment_method=1 AND entry_date < '$startDate'");

$accountDetails = $sell_details = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income", "payment_method=1 AND entry_date BETWEEN '$startDate' and '$endDate' ");


            $totalddebitbd = 0;
            $balanceofbdd = 0;
            $totalrtcreditbd = 0;
            $balances= 0;

            foreach ($balance_bd as $bd) {
                $debit_bd = 0;
                $credit_bd = 0;
                if ($bd['acc_type']==7
                    ||$bd['acc_type']==8
                    ||$bd['acc_type']==9
                    ||$bd['acc_type']==10
                    ||$bd['acc_type']==23
                    ||$bd['acc_type']==22
                    ||$bd['acc_type']==29) {
                    continue;
                }

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
                    || $bd['acc_type'] == $bankDeposit
                ) {
                    $debit_bd = $bd['acc_amount'];
                    $balanceofbdd -= $debit_bd;
                    $totalddebitbd += $debit_bd;
                } else {
                    $credit_bd = $bd['acc_amount'];
                    $balanceofbdd += $credit_bd;
                    $totalrtcreditbd += $credit_bd;
                }
            }
            
            if ($balanceofbdd!=0) {
                $balances = $balanceofbdd;
   
            }else{
                $balances = 0;
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
                    ||$account['acc_type']==23
                    ||$account['acc_type']==22
                    ||$account['acc_type']==29) {
                    continue;
                }

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
                    || $account['acc_type'] == $bankDeposit
                ) {
                    $debit = $account['acc_amount'];
                    $balances -= $debit;
                    $total_debit += $debit;
                } else {
                    $credit = $account['acc_amount'];
                    $balances += $credit;
                    $total_credit += $credit;
                }
                $i++;
            }
                 $cash_balance =    $balances;
                    
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
                $allStockItemData  = $obj->view_all_ordered_by("vw_sell_purchase_stock", "`vw_sell_purchase_stock`.`total_purchase_qty` DESC");
                $total_purchase = 0;
                $total_avg_purchase_price =0;
                $total_sell_price = 0;
                $total_avg_sell_price = 0;
                $total_stock = 0;
                $total_delevered_all = 0 ;
                $total_stock_price = 0;
                foreach ($allStockItemData as $stock) {
                    $i++;
                    $total_purchase = $stock['total_purchase_qty'] + $total_purchase;
                    $total_avg_purchase_price = $stock['avg_purchase_price'];
                    $total_sell_price = $stock['total_sell_qty'] + $total_sell_price;
                    $total_avg_sell_price = $stock['avg_sell_price'] + $total_avg_sell_price;
                    $total_delevered  = $obj->get_sum_data("tbl_delivery_item","qty"," `product_id`='".$stock['product_id']."'");
                    $total_delevered_all = $total_delevered +$total_delevered_all;
                    isset($stock['total_purchase_qty']) ? $purchase_qty = $stock['total_purchase_qty'] : $purchase_qty = 0;
                    isset($total_delevered) ? $total_delevered : $total_delevered = 0;
                    $return_purchase  = $obj->get_sum_data('tbl_return','return_qty','type=1 AND product_id='.$stock['product_id']);
                    $return_sell  = $obj->get_sum_data('tbl_return','return_qty','type=0 AND product_id='.$stock['product_id']);
                    $stockQty = $purchase_qty - $total_delevered - $return_purchase + $return_sell;
                    $stock_price = $total_avg_purchase_price * $stockQty;
                    $total_stock_price += $stock_price;
                }
                $balance_bd = $obj->view_all_by_cond("bank_account", "entry_date <= '$startDate'");

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
                $total_capital =0;

                $customerTransaction = $obj->get_sum_data("tbl_sell", "total_price","total_price!=0 ".$where);

                $customerRecieved = $obj->get_sum_data("tbl_account", "acc_amount ","acc_type=3 OR acc_type=5 ".$where);

                $customerDiscount = $obj->get_sum_data("discount", "amount", "cus_or_sup_id LIKE 'CUS%'  ".$where);
                $openingCustomerDueBalance = $obj->get_sum_data("tbl_account",'acc_amount', "acc_type=8  ".$where);
                $customerOpeningAdvance = $obj->get_sum_data("tbl_account", 'acc_amount',"acc_type=7 ".$where);

                $givePaymentToCustomer = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 12 AND cus_or_sup_id LIKE 'CUS%' ".$where);

                $total_customer_due = ($customerTransaction - $customerRecieved - $customerDiscount + $openingCustomerDueBalance - $customerOpeningAdvance)+ $givePaymentToCustomer;


                $supplierOpeningAdvance = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type = 9 ".$where);

               // $supplierTransaction = $obj->get_sum_data("tbl_bill",'total_price', "total_price!=0 ".$where);

                $supplierRecieved = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type = 2 OR acc_type = 6 ".$where);

                $supplierDiscount = $obj->get_sum_data("discount","amount", "cus_or_sup_id LIKE 'SUP%'   ".$where);
                $supplierOpeningDue = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type = 10 ".$where);

                // $receiveSecurityMoney = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 17 AND cus_or_sup_id LIKE 'SUP%' ".$where);
                // $receiveSecurityMoneyBack = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 18 AND cus_or_sup_id LIKE 'SUP%' ".$where);

                // $giveSecurityMoney = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 19 AND cus_or_sup_id LIKE 'SUP%' ".$where);
                // $giveSecurityMoneyBack = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 20 AND cus_or_sup_id LIKE 'SUP%' ".$where);

                $receiveCashFromSupplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 11 AND cus_or_sup_id LIKE 'SUP%' ".$where);

                $total_supplier_due  = ($supplierOpeningAdvance  + $supplierRecieved - $supplierDiscount - $supplierOpeningDue)-$receiveCashFromSupplier;

                /*$loan_receive       = $obj->get_sum_data('tbl_loan','loan_recieve','repayment=0 '.$where);
                $loan_receive_repay = $obj->get_sum_data('tbl_loan','loan_give','repayment=1 '.$where);

                $loan_give          = $obj->get_sum_data('tbl_loan','loan_give','repayment=0 '.$where);
                $loan_give_repay    = $obj->get_sum_data('tbl_loan','loan_recieve','repayment=1 '.$where);
                $total_loan         = $loan_give-$loan_receive;

                if($total_loan > 0){
                    $loan_payable       = 0;
                    $loan_receivable   = $total_loan;
                }else if($total_loan < 0){
                    $loan_payable       = $total_loan;
                    $loan_receivable   = 0;
                }else{
                    $loan_payable       = 0;
                    $loan_receivable   = 0;
                }*/


                // $personData         = $obj->view_all("tbl_person");
                $loan_receive       = 0;
                $loan_payable       = 0;
                $loan_receivable    = 0;
                $total_receivable   = 0;
                $total_payable      = 0;


                // foreach ($personData as $person) {
                    
                //     $personId       = $person['person_id'];

                //     $cgltp = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 13 AND cus_or_sup_id='$personId'");
                    
                //     $prltc = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 14 AND cus_or_sup_id='$personId'");
                    
                //     $ctlfp = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 15 AND cus_or_sup_id='$personId'");

                //     $crltp = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 16 AND cus_or_sup_id='$personId'");

                //     // $loanBal = ($prltc+$ctlfp)-($cgltp+$crltp);
                    
                //     $loanBal = ($cgltp-$prltc)-($ctlfp-$crltp);
                    
                //     // $personId       = $person['id'];
                //     // $loanData       = $obj->raw_sql("(SUM(loan_give)- SUM(loan_recieve))AS loan_bal FROM `tbl_loan` WHERE `person_id` =".$personId. $where);

                //     // if ($loanData) {
                //     //     $loanBal   = $loanData[0]['loan_bal'];
                        
                //     // }

                //     if($loanBal < 0 ){
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
                 

                /*$total_receivable   = $total_customer_due;
                $total_payable      = $total_supplier_due;
                $total_receivable   =$total_receivable + $loan_receivable + ($giveSecurityMoney - $giveSecurityMoneyBack);
                $total_payable      = $total_payable + $loan_payable  - ($receiveSecurityMoney-$receiveSecurityMoneyBack);*/

//            if ($total_loan > 0 ){$total_receivable += $total_loan;}
//            else{$total_payable -= $loan_receive;}


                $total_customer_due     = 0;
                $total_customer_advance =0;
                $all_customer           = $obj->view_all('tbl_customer');
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

                    $total_due_c = ($supplierOrCustomerTransaction['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance)+ $givePaymentToCustomer+(($labortransporcostForCustomer > 0)?$labortransporcostForCustomer:0);
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
                foreach ($all_supplier as $supplier){
                    $supplierId = isset($supplier['supplier_id']) ? $supplier['supplier_id'] : null;

                    $supplierPersonalData = $obj->details_by_cond("tbl_supplier", "`supplier_id` = '$supplierId'");

                    $supplierName = $supplierPersonalData['supplier_name'];

                    // $supplierOrCustomerTransaction = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$supplierId'");
                    // $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved","supplier_customer='$supplierId'");

                    // $receiveCashFromSupplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 11 AND cus_or_sup_id='$supplierId'");

                    // $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance","supplier_customer='$supplierId'");

                    // $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance","supplier_customer='$supplierId'");

                    //     /*$giveSecurityMoney = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type=19 AND cus_or_sup_id='$supplierId'");

                    //     $backSecurityMoney = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type=20 AND cus_or_sup_id='$supplierId'");

                    //     $supplierSecurityMoney = $giveSecurityMoney - $backSecurityMoney;*/
                        
                    //       $purchse_return = $obj->get_sum_data('tbl_return','total_return_price',"type=1 AND cus_or_sup_id='$supplierId'");

                    //     $discountData = $obj->details_selected_field_by_cond("discount","sum(`amount`) as amount", "cus_or_sup_id='$supplierId'");

                    //     isset($discountData) ? $discount = $discountData['amount'] : $discount = 0 ;
                    //     isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0 ;
                    //     isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0 ;

                    // $total_due_s = ($openingAdvance - $supplierOrCustomerTransaction['total_price'] + $supplierOrCustomerRecieved['total_recieved'] - $discount - $openingDueBalance)-($receiveCashFromSupplier-$purchse_return);
           

                        //add
                        $mpurchase_payment = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 2 AND cus_or_sup_id='$supplierId'");
                        $msupplier_individual_payment = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 6 AND cus_or_sup_id='$supplierId'");
                        $msupplier_advance = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 9 AND cus_or_sup_id='$supplierId'");
                        $mcompany_provide_s_money_to_supplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 19 AND cus_or_sup_id='$supplierId'");

                        $purchse_return = $obj->get_sum_data('tbl_return','total_return_price',"type=1 AND cus_or_sup_id='$supplierId'");
                        $discountData = $obj->details_selected_field_by_cond("discount","sum(`amount`) as amount", "cus_or_sup_id='$supplierId'");
                        isset($discountData) ? $discountsup = $discountData['amount'] : $discountsup = 0 ;
                        
                        //minuse
                        $msupplier_due = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 10 AND cus_or_sup_id='$supplierId'");
                        $msupplier_back_s_money_to_company = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 20 AND cus_or_sup_id='$supplierId'");
                        $mpurchase_product_from_supplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 22 AND cus_or_sup_id='$supplierId'");
                        
                        $receiveCashFromSupplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 11 AND cus_or_sup_id='$supplierId'");
                        
                        
                        $total_due_s =($mpurchase_payment +$msupplier_individual_payment +$msupplier_advance+$mcompany_provide_s_money_to_supplier+$purchse_return+$discountsup) -( $msupplier_due + $msupplier_back_s_money_to_company + $mpurchase_product_from_supplier +$receiveCashFromSupplier);





                        if ($total_due_s < 0){
                            $total_supplier_due += abs($total_due_s);
                        }else{
                            $total_supplier_advance += abs($total_due_s);
                        }
                    }
                    

                    $total_receivable = $total_customer_due + $total_supplier_advance + abs($loan_receivable);
                    if ($total_sec_money > 0) {
                        $total_receivable += $total_sec_money;
                    }



                    $total_payable = $total_supplier_due + $total_customer_advance + abs($loan_payable);

                    if ($total_sec_money < 0) {
                        $total_payable += abs($total_sec_money);
                    }



                    $balance  = 0;
                    $total_cr = 0;
                    $total_dr = 0;
                    $credit   = 0;
                    $debit    = 0;
                    ?>

                    <tr>
                        <th><a href="?q=company_ledger_single&type=receivable" target="_blank">Total Receivable</a> </th>
                        <th class="text-right">
                            <?php
                            $credit = $total_receivable;
                            echo number_format($credit);
                            $total_cr += abs($credit);
                            ?>
                            &#x9f3;
                        </th>
                        <th class="text-right">


                        </th>
                        <th class="text-right">
                            <?php 
                            $balance += ($total_cr - $total_dr);
                            echo number_format($balance) ." &#x9f3;";
                            ?>
                        </th>
                    </tr>

                    <tr>
                        <th><a href="?q=company_ledger_single&type=payable" target="_blank">Total Payable</a> </th>
                        <th class="text-right">

                        </th>
                        <th class="text-right">

                            <?php  $debit = $total_payable;
                            echo number_format(abs($debit));

                            $total_dr += abs($debit);
                            ?>
                            &#x9f3;
                        </th>
                        <th class="text-right">
                            <?php 
                            $balance = ($total_cr - $total_dr);
                            echo number_format(abs($balance)) ." &#x9f3;";
                            ?>
                        </th>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-grey-400">
                        <td>Total </td>
                        <td class="text-right">
                            <?php
                            echo number_format($total_cr);
                            ?>
                            &#x9f3;
                        </td>
                        <td class="text-right">
                            <?php
                            echo number_format($total_dr);
                            ?>
                            &#x9f3;          
                        </td>
                        <td class="text-right"><?php echo number_format($cap_two=$balance); number_format($balance) ." &#x9f3;"?> </td>
                    </tr>

                    <tr>
                        <td colspan="4" style="height: 30px;"></td>
                    </tr>
                    <tr class="bg-grey-800">
                        <th colspan="3" class="text-center"> Assets</th>
                        <th class="text-center"> Amount </th>
                    </tr>


                    <tr>
                        <th colspan="3">Total Stock Price</th>
                        <th class="text-right"><?php echo number_format($total_stock_price) ." &#x9f3;";
                        if(isset($credit)){
                            $credit += $total_stock_price;
                        }
                        $balance = $total_stock_price;
                        ?>
                    </th>

               <!--  <th class="text-right"> </th>
                <th class="text-right"> <?php echo number_format($balance+$total_stock_price) ." &#x9f3;";
                //$balance += $total_stock_price;
                ?> </th> -->
            </tr>
            <tr>
                <th colspan="3">Total Cash in Hand</th>
                <th class="text-right"><?php echo number_format($cash_balance) ." &#x9f3;";
                if(isset($credit)){
                    $credit += $cash_balance;
                }
                $balance += $cash_balance;
                ?> </th>

            </tr>
            <tr>
                <th colspan="3">Total Bank Balance</th>
                <th class="text-right"><?php echo ($balance_of_bd > 0)? $balance_of_bd:'0';
                ($balance_of_bd > 0)? $credit+=$cash_balance:'0';
                $balance += $balance_of_bd;                
                ?>
            &#x9f3;</th>

        </tr>

        <tr class="bg-grey-400">
            <td colspan="3">Total</td>
            <td class="text-right"><?php  $cap_one=$balance;  echo number_format($balance); ?> &#x9f3;</td>
        </tr>
         <tr class="bg-red-400">
            <td colspan="3">Company Capital</td>
            <td class="text-right"><?php $cap=$cap_one+$cap_two; echo number_format($cap); ?> &#x9f3;</td>
        </tr>

                   
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