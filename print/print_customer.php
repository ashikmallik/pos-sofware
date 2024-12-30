<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
//$date        = date('Y-m-d');
$Month = date('m', strtotime($date));
$Year = date('Y', strtotime($date));
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

//$dataForPrint = $obj->view_all("tbl_customer");

//=====================start==============================

if (isset( $_GET['token'])) {
    $customer_data  = $_GET['token'];
    if ($customer_data == "1") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='1'");
        $des = "View Customer Due / Advance Information of Retailer";
    }elseif ($customer_data == "2") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='2'");
        $des = "View Customer Due / Advance Information of Workshop";
    }elseif ($customer_data == "3") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='3'");
        $des = "View Customer Due / Advance Information of HouseOwner";
    }
    elseif ($customer_data == "4") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='4'");
    }
    elseif ($customer_data == "5") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='5'");
        $des = "View Customer Due / Advance Information of Feed";
    }
    elseif ($customer_data == "6") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='6'");
        $des = "View Customer Due / Advance Information of Block Money";
    }
    elseif ($customer_data == "7") {
        $customerPersonalData =$obj->view_all_by_cond("tbl_customer","type='7'");
        $des = "View Customer Due / Advance Information of Sanatary";
    }else{
            $customerPersonalData =$obj->view_all("tbl_customer");
            $des = "View Customer Due / Advance Information";
    }
}
//=======================end============================

?>

<div class="col-md-12"
     style="background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
        <b><?php echo $des; ?></b>
    </div>
</div>
<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="row" style="padding:10px; font-size: 12px;">

    <div class="col-md-1 col-md-offset-11">
        <button type="submit" class="btn btn-primary pull-right" onclick="printDiv('month_print')">Print Statement
        </button>
    </div>
    <div class="row" id="month_print">

        <div class="col-md-6">
            <b><?php echo $des; ?> </b>
        </div>
        <div class="col-md-12">
            <table class="table table-responsive table-bordered table-hover table-striped">
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
                                <td class="text-center"><?php echo $customerId ?></td>
                                <td class=""><?php echo $customerName;?></td>
                                <td class=""><?php echo ($total_due< 0)? abs(round($total_due,2)) :0;?></td>
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

                </tbody>
            </table>
        </div>
    </div>
    <!-- here end table -->
</div>

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
<script>
    $(document).ready(function () {
        $("tbody tr").dblclick(function () {
            _id = this.id;
            if (_id != "0")
                window.location = "?q=view_customer_payment_individual&token2=" + _id;
        });
    });
</script>
<!-- here end table -->