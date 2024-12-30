<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$token = isset($_GET['token']) ? $_GET['token'] : NULL;
$previous_advance = 0;
$previous_due = 0;
$type = 0;
//////////////
$customerId = $token;
$supplierOrCustomerTransaction = $obj->details_by_cond("vw_supplier_customer_total_transection", "supplier_customer='$customerId'");

                        

                        $supplierOrCustomerRecieved = $obj->details_by_cond("vw_supplier_customer_total_recieved", "supplier_customer='$customerId'");

                        $givePaymentToCustomer = $obj->get_sum_data('tbl_account','acc_amount', "acc_type = 12 AND cus_or_sup_id='$customerId'");

                        $supplierOrCustomerOpeningDue = $obj->details_by_cond("vw_supplier_customer_due_opening_balance", "supplier_customer='$customerId'");

                        $supplierOrCustomerOpeningAdvance = $obj->details_by_cond("vw_supplier_customer_advance_opening_balance", "supplier_customer='$customerId'");

                        $discountData = $obj->details_selected_field_by_cond("discount", "sum(`amount`) as amount", "cus_or_sup_id='$customerId'");

                        isset($discountData) ? $discount = $discountData['amount'] : $discount = 0;

                        isset($supplierOrCustomerOpeningDue) ? $openingDueBalance = $supplierOrCustomerOpeningDue['opening_due'] : $openingDueBalance = 0;

                        isset($supplierOrCustomerOpeningAdvance) ? $openingAdvance = $supplierOrCustomerOpeningAdvance['opening_due'] : $openingAdvance = 0;

                        $total_due = ($supplierOrCustomerTransaction['total_price'] - $supplierOrCustomerRecieved['total_recieved'] - $discount + $openingDueBalance - $openingAdvance)+ $givePaymentToCustomer;
  if($total_due < 0)
{
    $previous_advance = abs($total_due);
}
if($total_due > 0)
{
    $previous_due = abs($total_due);
}
if($previous_advance != 0) {
    $type = 7;
}
if($previous_due != 0) {
    $type = 8;
}
$cusInfo = $obj->details_by_cond("tbl_account", "cus_or_sup_id='$token' and acc_type='$type'");
/////////////////////
if (isset($_POST['update'])) {
    extract($_POST);
if (!empty($opening_balance) && ($opening_balance != 0)) {
    $bal = $cusInfo['acc_amount'];
    if($type == 7)
    {
        $amount = $total_due - $opening_balance;
    }
    if ($acc_head == 7) {
            $typedes = 'Advance';
        } else if ($acc_head == 8) {
            $typedes = 'Due';
        }
    $form_data_for_update = array(
        'acc_amount' => str_replace("'",'',$opening_balance),
    );
   // $customer_update = $obj->Update_data("tbl_account", $form_data_for_update, "where cus_or_sup_id='$token'");

    if ($customer_update) {
        ?>
        <script>
            window.location = "?q=edit_customer_due&token=<?php echo $token; ?>";
        </script>
        <?php
    } else {
        $notification = 'Update Failed';
    }
}
    
}
?>
<span style="color: red; font-size: 20px;text-align:left;"></span>
<div class="row" style="padding:10px; font-size: 12px;">
    <form role="form" enctype="multipart/form-data" method="post">
        <div class="row" style="padding:10px; font-size: 12px;">
            <div class="col-md-6 col-md-offset-3 bg-teal-700 text-center" style="margin-bottom:5px;">
                <h4>Welcome to Edit page<?php ?></h4>
            </div>
             <div class="col-md-6 col-md-offset-3" >
            <!--<div class="form-group">
                    <label for="exampleInputEmail1">Opening Balance</label>
                <?php    echo $cusInfo['acc_type']; ?>
                     <select class="form-control" required="required" name="acc_head" id="status">
                                <option <?php if ($cusInfo['acc_type'] == '7') echo 'selected'; ?> value="7">Advance
                                </option>
                                <option <?php if ($cusInfo['acc_type'] == '8') echo 'selected' ?> value="8">Due
                                </option>
                    </select>
            </div>-->
            
            <?php if($previous_advance != 0) {?>
            <div class="form-group">
                    <label>Balance</label>
                    <input style="height:32px" onkeypress="return numbersOnly(event)" type="text" value="<?php echo $previous_advance ?>" class="form-control" name="opening_balance">
                    <input type="hidden" value='7' name="type">
                </div>
                <?php } ?>
                <?php if($previous_due != 0) {?>
            <div class="form-group">
                    <label>Balance</label>
                    <input style="height:32px" onkeypress="return numbersOnly(event)" type="text" value="<?php echo $previous_due ?>" class="form-control" name="opening_balance">
                    <input type="hidden" value='8' name="type">
                </div>
                <?php } ?>
            </div>
        </div>
        <!--            row-->
        <div class="row" style="text-align: center; padding: 5px 0px 15px 25px; font-size: 12px;">
            <button type="submit" class="btn btn-success text-center" name="update">Update Customer</button>
        </div>
    </form>
</div>