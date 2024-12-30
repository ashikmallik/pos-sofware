<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
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
$ReceiveCashFromSupplier = 11;
$CustomerDue = 8;
$SupplierAdvance = 9;
$PurchaseProductBill = 22;
$PurchaseReturn = 27;
$PurchaseDiscount = 30;
$supplierPersonalData = $obj->view_all("tbl_supplier");
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js"></script>
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

<div class="col-md-12"
style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
<div class="col-md-6">
    <b>View Supplier Due / Advance Information</b>
</div>

</div>

<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-teal-800">
                    <tr>
                        <th>Supplier ID</th>
                        <th>Supplier Name</th>
                        <th class="text-center">Advance</th>
                        <th class="text-center">Due</th>
                    </tr>
                </thead>
                <tbody>

                    <?php  
                    $grand_advance = 0;
                    $grand_due     = 0;
                    foreach ($supplierPersonalData as $supplier) {
                        $supplierName = $supplier['supplier_name'];
                        $supplierId   = $supplier['supplier_id'];

                        $supplierOrCustomerTransaction = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$supplierId'");
                        $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved","supplier_customer='$supplierId'");


                        $receiveCashFromSupplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 11 AND cus_or_sup_id='$supplierId'");
                        
                        $giveSecurityMoney = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type=19 AND cus_or_sup_id='$supplierId'");
                        $backSecurityMoney = $obj->get_sum_data("tbl_account",'acc_amount',"acc_type=20 AND cus_or_sup_id='$supplierId'");
                        $supplierSecurityMoney = $giveSecurityMoney - $backSecurityMoney;
                        
                        $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance","supplier_customer='$supplierId'");
                        $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance","supplier_customer='$supplierId'");
                        isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0 ;
                        isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0 ;
                      
                        $purchse_return = $obj->get_sum_data('tbl_return','total_return_price',"type=1 AND cus_or_sup_id='$supplierId'");
                         
                        $discountData = $obj->details_selected_field_by_cond("discount","sum(`amount`) as amount", "cus_or_sup_id='$supplierId'");
                        isset($discountData) ? $discount = $discountData['amount'] : $discount = 0 ;
                        
                        
                        // $total_due = ($openingAdvance - $supplierOrCustomerTransaction['total_price'] + $supplierOrCustomerRecieved['total_recieved'] + $discount - $openingDueBalance)-($receiveCashFromSupplier-$purchse_return);



                        //add
                        $mpurchase_payment = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 2 AND cus_or_sup_id='$supplierId'");
                        $msupplier_individual_payment = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 6 AND cus_or_sup_id='$supplierId'");
                        $msupplier_advance = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 9 AND cus_or_sup_id='$supplierId'");
                        $mcompany_provide_s_money_to_supplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 19 AND cus_or_sup_id='$supplierId'");

                        //minuse
                        $msupplier_due = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 10 AND cus_or_sup_id='$supplierId'");
                        $msupplier_back_s_money_to_company = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 20 AND cus_or_sup_id='$supplierId'");
                        $mpurchase_product_from_supplier = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 22 AND cus_or_sup_id='$supplierId'");
                        
                        
                         $total_due =($mpurchase_payment+$msupplier_individual_payment +$msupplier_advance +$mcompany_provide_s_money_to_supplier+$purchse_return+$discount) -( $msupplier_due + $msupplier_back_s_money_to_company + $mpurchase_product_from_supplier +$receiveCashFromSupplier);
                       

                      
                        if($total_due !=0){

                            if ($total_due> 0) {
                                $grand_advance += $total_due;
                            }
                            if ($total_due < 0) {
                                $grand_due += $total_due;
                            }

                            ?>
                            <tr>
                                <td class="text-center"><a class="btn btn-xs bg-grey-600 btn-default" href="?q=supplier_ledger&supplierId=<?php echo $supplierId ?>"><?php echo $supplierId ?></a></td>
                                <td class="text-left"><?php echo $supplierName;?></td>
                                <td class="text-right"><?php echo ($total_due> 0)? number_format(abs($total_due),2)  :0;?></td>
                                <td class="text-right"><?php echo ($total_due< 0)? number_format(abs($total_due),2) :0;?></td>
                            </tr>
                        <?php }}?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-grey-300">
                            <th colspan="2">Total</th>
                            <th class="text-right"><?php echo number_format($grand_advance);?></th>
                            <th class="text-right"><?php echo number_format(abs($grand_due)); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    


        