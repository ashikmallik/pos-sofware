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

$customerPersonalData =$obj->view_all("tbl_customer");
$totalCustomer = $obj->Total_Count("tbl_customer","cus_status != 0");
$totalRetailer = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 1");
$totalWorkshop = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 2");
$totalHouseowner = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 3");
$totalFeed = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 5");
$totalBlockMoney = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 6");
$totalSanatary = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 7");


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
<style>
    .btn
    {
      padding: 6px 26px;
    }
</style>
<div class="col-md-12"
style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
<!-- <div class="col-md-6">
    <b>View Customer Due / Advance Information</b>
</div> -->
<div class="row">
    <div class="col-md-12">
        <h4>View Customer Due / Advance Information</h4>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default" style="width:976px;">
            <div class="panel-body" style="padding: 4px;">
                <div id="client_list" class="btn-group btn-group-justified">
                    <form action="" method="post">

                        <button type="button" onclick="getComplains(this.value)" class="btn btn-primary" value="all">ALL<!-- (<?php echo $totalCustomer;?>) --> </button>

                        <button type="button" onclick="getComplains(this.value)" class="btn btn-success" value="1">Retailer<!-- (<?php echo $totalRetailer;?>) --> </button>

                        <button type="button" onclick="getComplains(this.value)" class="btn btn-warning" value = "2">Workshop<!-- (<?php echo $totalWorkshop;?>) --> </button>

                        <button type="button" onclick="getComplains(this.value)" class="btn btn-info" value="3">Houseowner<!-- (<?php echo $totalHouseowner;?>) --></button>

                        <button type="button" onclick="getComplains(this.value)"  class="btn btn-danger" value="5">Feed<!-- (<?php echo $totalFeed;?>) --></button>

                        <button style="background: #61442b;" type="button" onclick="getComplains(this.value)" class="btn btn-info" value="6">Block Money<!-- (<?php echo $totalBlockMoney;?>) --></button>

                        <button style="background: #4a6f38;" type="button" onclick="getComplains(this.value)" class="btn btn-info" value="7">Sanatary<!-- (<?php echo $totalSanatary;?>) --></button>

                        <a href="index.php?q=add_customer"><button type="button" class="btn btn-primary" >Add Customer</button></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
      <div id="customer-list">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-teal-800">
                    <tr>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th class="text-center">Advance</th>
                        <th class="text-center">Due</th>
                        <!--<th class="text-center">Action</th>-->
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
                        
                        $labortransporcostForCustomer = $obj->get_sum_data('tbl_account', 'acc_amount', " cus_or_sup_id='$customerId' AND (acc_type = 201 OR acc_type = 202)");

                        $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$customerId'");
                        $sales_return = $obj->get_sum_data('tbl_account','acc_amount', "acc_type =28 AND cus_or_sup_id='$customerId'");

                        $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$customerId'");

                        $discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$customerId'");

                        isset($discountData) ? $discount = $discountData['amount'] : $discount = 0;

                        isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0;

                        isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0;

                        $total_due_withoutret = ($supplierOrCustomerTransaction['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance)+ $givePaymentToCustomer+(($labortransporcostForCustomer > 0)?$labortransporcostForCustomer:0);
                        if($sales_return > 0){
                        $total_due =round($total_due_withoutret - $sales_return);
                        }
                        else
                        {
                            $total_due = round($total_due_withoutret);
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
                                <td class=""><?php echo ($total_due< -10)? abs (round($total_due,2)) :0;?></td>
                                <td class=""><?php echo ($total_due> 10)? abs(round($total_due,2)) :0;?></td>
                              <!--<td class="">  <a class="btn btn-xs bg-teal btn-primary padding_2_10_px" href="?q=edit_customer_due&token=<?php echo $customerId ?>">
                                    <span class="glyphicon glyphicon-edit"></span> </td>
                                </a>-->
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
          </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
    <script>
        function getComplains(value){
        if (value != '') {
            $.ajax({
                url:"view/getcustomer.php",
                method:"POST",
                data:{customer_data:value},
                dataType:"text",
                success:function(data){
                    $('#customer-list').html(data);
                }
            });
        }
    }
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

        });
      </script>
        <script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
        <script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>