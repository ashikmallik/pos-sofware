    <?php
    session_start();

    $user_id = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
    $FullName = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : NULL;
    $UserName = isset($_SESSION['UserName']) ? $_SESSION['UserName'] : NULL;
    $PhotoPath = isset($_SESSION['PhotoPath']) ? $_SESSION['PhotoPath'] : NULL;
    $ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;


    //========================================
    include '../model/Controller.php';
    include '../model/FormateHelper.php';

    $formater = new FormateHelper();
    $obj = new Controller();
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

    if (isset( $_POST['customer_data'])) {
    $customer_data  = $_POST['customer_data'];
    if ($customer_data == "1") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='1'");
    }elseif ($customer_data == "2") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='2'");
    }elseif ($customer_data == "3") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='3'");
    }
    elseif ($customer_data == "4") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='4'");
    }
    elseif ($customer_data == "5") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='5'");
    }
    elseif ($customer_data == "6") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='6'");
    }
    elseif ($customer_data == "7") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='7'");
    }else{
            $customerPersonalData =$obj->view_all("tbl_customer");
    }
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
            <a href="?q=printcustomer&token=<?php echo $customer_data ?>" id="print_link" target="_blank" style="margin-top: -35px;" class="btn btn-primary btn-xs pull-left" >Print Client List</a>
<?php } ?>
<div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-teal-800">
                    <tr>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th class="text-center">Advance</th>
                        <th class="text-center">Due</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    $grand_advance = 0;
                    $grand_due     = 0;

                    foreach ($customerPersonalData as $customer) {
                        $customerName = $customer['cus_name'];
                        $customerId   = $customer['cus_id'];

                        $supplierOrCustomerTransaction = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$customerId'");

                        

                        $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved", "supplier_customer='$customerId'");

                        $givePaymentToCustomer = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 12 AND cus_or_sup_id='$customerId'");

                        $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$customerId'");
                        $sales_return = $obj->get_sum_data('tbl_account','acc_amount', "acc_type =28 AND cus_or_sup_id='$customerId'");
                        $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$customerId'");

                        $discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$customerId'");

                        isset($discountData) ? $discount = $discountData['amount'] : $discount = 0;

                        isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0;

                        isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0;

                        $total_due_withoutret = ($supplierOrCustomerTransaction['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance)+ $givePaymentToCustomer;
                        if($sales_return > 0){
                        $total_due = $total_due_withoutret - $sales_return;
                        }
                        else
                        {
                            $total_due = $total_due_withoutret;
                        }
                        if($total_due !=0){

                            if ($total_due < 0) {
                                $grand_advance += $total_due;
                            }
                            if ($total_due > 0) {
                                $grand_due += $total_due;
                            }

                            ?>
                            <tr>
                                <td class="text-center"><a class="btn btn-xs bg-grey-600 btn-default" href="?q=customer_ledger&customerId=<?php echo $customerId ?>"><?php echo $customerId ?></a></td>
                                <td class=""><?php echo $customerName;?></td>
                                <td class=""><?php echo ($total_due< 0)? abs (round($total_due,2)) :0;?></td>
                                <td class=""><?php echo ($total_due> 0)? abs(round($total_due,2)) :0;?></td>
                            </tr>
                        <?php }}?>
                    </tbody>

                    <tfoot>
                        <tr class="bg-grey-300">
                            <th colspan="2">Total</th>
                            <th class="text-right"><?php echo number_format(abs($grand_advance));?></th>
                            <th class="text-right"><?php echo number_format(abs($grand_due)); ?></th>
                        </tr>
                    </tfoot>

                </table>
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
            $('#datatable').DataTable();

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