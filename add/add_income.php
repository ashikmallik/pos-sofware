<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

//===================Add Function===================
$total_amount = 0;
$due = 0;

$other_income_cat = 4; // for accounts

if (isset ($_POST['addIncome'])) {
    extract($_POST);
    $payment_method_type = 1;
    if ($payment_method == 'bank') {
        $payment_method_type = 0;
        $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
        $total_balance = $total_balance_Data['balance'];
        $form_data_for_bank = array(
            'account_no' => $account_no,
            'chq_no' => $_POST['chq_no'],
            'description' => "Income",
            'credit' => 0,
            'debit' => $amount,
            'balance' => ($total_balance + $amount),
            'withdraw_by' => '',
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
    }

    $form_tbl_accounts = array(
        'acc_description' => 'Income :'.str_replace("'", "", $Details),
        'acc_amount' => $amount,
        'acc_type' => $other_income_cat,
        'cus_or_sup_id' => 0,
        'payment_method' => $payment_method_type,
        'acc_head' => $acc_id,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");

    if ($tbl_accounts_add) {
        $notification = '<div class="alert alert-success">Insert Successful</div>';
    } else { // $paid_amount <= $total_price
        $notification = '<div class="alert alert-danger"> Insert Failed</div>';
    }


}
?>
<!--===================end Function===================-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
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


<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>

<div class="col-md-8 col-md-offset-2 bg-slate-800 text-center" style="margin-bottom:5px;">
    <h4>Welcome to Add New Other Income</h4>
</div>

<div class="row">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-7 col-md-offset-2">
            <div class="form-group">
                <label class="control-label col-sm-4" for="sup_id">Other Income</label>
                <div class="col-sm-8">
                    <select class="form-control" required="required" name="acc_id" id="status">
                        <option></option>
                        <?php
                        $i = '0';
                        foreach ($obj->view_all_by_cond("tbl_ac_head_other_income", "ac_head_or_other_income = 0") as
                            $other_income) {
                            $i++; ?>
                            <option value="<?php echo isset($other_income['acc_id']) ? $other_income['acc_id'] : NULL; ?>"><?php echo isset($other_income['acc_name']) ? $other_income['acc_name'] : NULL; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="amount">Amount:</label>
                    <div class="input-group col-sm-8">
                        <div class="input-group-btn">
                            <select class="btn btn-default" name="payment_method">
                                <option value="cash" selected="selected"> Cash</option>
                                <option value="bank"> Bank</option>
                            </select>
                        </div><!-- btn-group -->
                        <input type="text" style="height:32px" onkeypress="return numbersOnly(event)" name="amount" class="form-control" required="required">
                        <span class="input-group-addon">TK</span>
                    </div>
                </div>
            </div>

            <div id="bank_info" style="display: none;" class="form-group">
                <div class="">
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
                                    - <?php echo isset($value['bank_name']) ? $value['bank_name'] : NULL; ?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12"></div>
                <div class="">
                    <label class="control-label col-sm-4" for="diposited_by">Check No</label>
                    <div class="col-sm-8">
                        <input type="text" name="chq_no" placeholder="Check No" class="form-control">
                    </div>
                </div>
                <div class="col-md-12"></div>
                <div class="">
                    <label class="control-label col-sm-4" for="diposited_by">Depositor Name</label>
                    <div class="col-sm-8">
                        <input type="text" disabled name="diposited_by" placeholder="" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="Details">Account Details:</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" name="Details" id="ResponsiveDetelis" rows="6"></textarea>
                    </div>
                </div>
            </div>

            <div class="row margin_top_10px" style="margin-bottom:20px;">
                <div class="col-md-2 col-md-offset-7">
                    <div class="text-center">
                        <button type="submit" class="btn btn-block btn-success" name="addIncome">Submit</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">
    $('select[name="acc_id"]').select2({
        placeholder: "Select Other Income",
        allowClear: true
    });

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

</script>