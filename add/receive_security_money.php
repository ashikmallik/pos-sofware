<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$pay_type = 0;
$receive_type = 1;
$company_received_security_money_from_customer = $obj->getAccTypeId('company_received_s_money_from_customer');

if (isset($_POST['receive_s_money'])) {
    extract($_POST);

    $customer_data = $obj->details_by_cond("tbl_customer", "id = '$customer_id'");
    $payment_method_type = 1;
    if ($payment_method == 'bank') {
        $payment_method_type = 0;
        $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
        $total_balance = $total_balance_Data['balance'];

        $form_data_for_bank = array(
            'account_no' => $account_no,
            'chq_no' => $_POST['chq_no'],
            'description' => "Company Received Security Money From Customer " . $customer_data['cus_name'] . " ",
            'credit' => 0,
            'debit' => $paid_amount,
            'balance' => ($total_balance + $paid_amount),
            'diposited_by' => (empty($diposited_by) ? $customer_name : $diposited_by),
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
    }

    $form_tbl_accounts = array(
        'acc_description' => "Company Received Security Money From Customer - " . $customer_data['cus_name'] . ". " . $description . " ",
        'acc_amount' => $paid_amount,
        'acc_type' => $company_received_security_money_from_customer,
        'purchase_or_sell_id' => 0,
        'cus_or_sup_id' => $customer_data['cus_id'],
        'payment_method' => $payment_method_type,
        'acc_head' => 0,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");


    $form_tbl_security_money_transaction = array(

        'customer_id' => $customer_id,
        'supplier_id' => 0,
        'amount' => $paid_amount,
        'pay_receive' => $receive_type,
        'accounts_id' => $tbl_accounts_add,
        'description' => $description,
        'created_at' => date('Y-m-d'),
    );
    if ($obj->insert_by_condition("tbl_security_money_transaction", $form_tbl_security_money_transaction, " ")) {

        $obj->notificationStore('Receiving Security Money form Customer Successfully', 'success');

        echo '<script>window.location.href=window.location.href;</script>';
    } else {

        $obj->notificationStore('Receiving Security Money form Customer Failed' );
        echo '<script>window.location.href=window.location.href;</script>';
    }

}
?>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<script>
    function numbersOnly(e) {
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

<div class="row">
    <div class="col-md-12 bg-grey-800 text-center">
        <h4>Welcome to Received Security Money form Customer</h4>
    </div>
</div>
<div class="row">
    <div class="col-md-12 padding_5_px">
        <?php $obj->notificationShowRedirect(); ?>
    </div>
</div>
<hr>

<div class="row" style="font-size: 12px;">
    <form class="form-horizontal" role="form" method="post">

        <div class="col-md-8 col-md-offset-1">

            <div class="form-group">
                <label class="control-label col-sm-4" for="customer_id">Customer :</label>
                <div class="col-sm-8">
                    <select class="form-control" name="customer_id">
                        <option></option>
                        <?php
                        $i = '0';
                        foreach ($obj->view_all("tbl_customer") as $customer) {
                            $i++;
                            ?>
                            <option value="<?php echo isset($customer['id']) ? $customer['id'] : NULL; ?>"><?php echo isset($customer['cus_name']) ? $customer['cus_name'] : NULL; ?>
                                - <?php echo isset($customer['cus_id']) ? $customer['cus_id'] : NULL; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <b><p class="text-primary" id="customer_details"></p></b>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-4">Amount (tk) :</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <select class="btn btn-default" name="payment_method">
                                <option value="cash" selected="selected">Payment in Cash</option>
                                <option value="bank">Payment in Bank</option>
                            </select>
                        </div><!-- btn-group -->
                        <input type="text" style="height:32px"
                               onkeypress="return numbersOnly(event)"
                               name="paid_amount" class="form-control">
                        <span class="input-group-addon">TK</span>
                    </div>
                </div>
            </div>


            <div id="bank_info" style="display: none;" class="col-md-12 pull-right">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="damageRate">Bank Account</label>
                    <div class="col-sm-8">
                        <select class="form-control" disabled required="required" name="account_no" id="status">
                            <option></option>
                            <?php
                            $i = '0';
                            foreach ($obj->view_all("bank_registration") as $value) {
                                $i++;
                                ?>
                                <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>
                                    --<?php echo isset($value['bank_name']) ? $value['bank_name'] : NULL; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="diposited_by">Check No</label>
                    <div class="col-sm-8">
                        <input type="text" name="chq_no" placeholder="Check No" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="diposited_by">Depositor Name</label>
                    <div class="col-sm-8">
                        <input type="text" disabled name="diposited_by" placeholder="Depositor Name"
                               class="form-control">
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-sm-4">Description :</label>
                <div class="col-sm-8">
                    <textarea name="description" class="form-control" id="" cols="30" rows="5"></textarea>
                </div>
            </div>


        </div>

        <div class="col-md-12">
            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-success" name="receive_s_money">Receive Security Money
                </button>
            </div>
        </div>
    </form>
</div>
<hr>

<script type="text/javascript">
    $(document).ready(function () {

        $('select[name="customer_id"]').selectpicker();


        $('select[name="payment_method"').on('change', function () { // banking section will show when click bank
            if (this.value == 'bank') {
                $('#bank_info select[name="account_no"]').removeAttr('disabled');
                $('#bank_info input[name="diposited_by"]').removeAttr('disabled');
                $('#bank_info').show();
            } else {
                $('#bank_info select[name="account_no"]').attr('disabled', 'disabled');
                $('#bank_info input[name="diposited_by"]').attr('disabled', 'disabled');
                $('#bank_info').hide();
            }
        });
    });
</script>
