<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

//===================Add Function===================
$total_amount = 0;
$due = 0;

$expense_cat = 1; // for accounts

if (isset ($_POST['addIncome'])) {
    extract($_POST);
    $payment_method_type = 1;
    if($payment_method == 0){
        $mobile_banking_name = 'Bank';
    }elseif($payment_method == 3){
        $mobile_banking_name = 'Bkash';
    }
    elseif($payment_method == 4){
        $mobile_banking_name = 'Nagad';
    }
    else{
        $mobile_banking_name = 'Rocket';
    }

    $form_tbl_accounts = array(
        'acc_description' => 'Expense : '.str_replace("'", "", $details),
        'acc_amount' => $amount,
        'acc_type' => $expense_cat,
        'cus_or_sup_id' => 0,
        'payment_method' => $payment_method,
        'acc_head' => $acc_id,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid

    );
    $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    // if ($payment_method == 'bank') {
        $payment_method_type = 0;
        $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
        $total_balance = $total_balance_Data['balance'];
        $form_data_for_bank = array(
            'mobile_banking_name' => $mobile_banking_name,
            'type' => $payment_method,
            'account_no' => $account_no,
            'chq_no' => $_POST['chq_no'],
            'description' => "Expense",
            'credit' => $amount,
            'debit' => 0,
            'balance' => ($total_balance - $amount),
            'withdraw_by' => '',
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid,
            'expense_id' => $tbl_accounts_add,
        );
        $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
    // }
    if ($tbl_accounts_add) {
        $notification = '<div class="alert alert-success">Insert Successful</div>';
    } else { // $paid_amount <= $total_price
        $notification = '<div class="alert alert-danger">Insert Failed</div>';
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

<div class="col-md-8 col-md-offset-2 bg-grey-800 text-center" style="margin-bottom:15px;">
    <h4>Welcome to Add New Expense</h4>
</div>

<div class="row">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-7 col-md-offset-2">
            <div class="form-group">
                <label class="control-label col-sm-4" for="sup_id">Account Head</label>
                <div class="col-sm-8">
                    <select class="form-control" required="required" name="acc_id" id="status">
                        <option></option>
                        <?php
                        $i = '0';
                        foreach ($obj->view_all_by_cond("tbl_ac_head_other_income", "ac_head_or_other_income = 1") as
                                 $acc_head) {
                            $i++;
                            ?>
                            <option
                                value="<?php echo isset($acc_head['acc_id']) ? $acc_head['acc_id'] : NULL; ?>"><?php echo isset($acc_head['acc_name']) ? $acc_head['acc_name'] : NULL; ?>
                            </option>
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
                                <option value="1" selected="selected"> Cash</option>
                                <option value="0"> Bank</option>
                                <option value="3"> Bkash</option>
                                <option value="4"> Nagod</option>
                                <option value="5"> Rocket</option>
                            </select>
                        </div><!-- btn-group -->
                        <input type="text" style="height:32px" onkeypress="return numbersOnly(event)" name="amount" class="form-control" required="required">
                        <span class="input-group-addon">TK</span>
                    </div>
                </div>
            </div>

            <div id="bank_info" style="display: none;" class="form-group">
                <div class="">
                    <label class="control-label col-sm-4" for="damageRate">Bank Account No</label>
                    <div class="col-sm-8">
                        <select class="form-control" disabled required="required" name="account_no" id="status">
                            <option></option>
                            <?php
                            $i = '0';
                            foreach ($obj->view_all_by_cond("bank_registration","type=2") as $value) {
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
            
            <div id="baksh_info" style="display: none;" class="form-group">
                <div class="">
                    <label class="control-label col-sm-4" for="damageRate">Bkash Account No</label>
                    <div class="col-sm-8">
                        <select class="form-control" disabled required="required" name="account_no" id="status">
                            <option></option>
                            <?php
                            $i = '0';
                            foreach ($obj->view_all_by_cond("bank_registration","type=3") as $value) {
                                $i++;
                                ?>
                                <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>
                                    - Bkash</option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12"></div>
                
                <div class="col-md-12"></div>
                <div class="">
                    <label class="control-label col-sm-4" for="diposited_by">Depositor Name</label>
                    <div class="col-sm-8">
                        <input type="text" disabled name="diposited_by" placeholder="" class="form-control">
                    </div>
                </div>
            </div>
            
            <div id="nagad_info" style="display: none;" class="form-group">
                <div class="">
                    <label class="control-label col-sm-4" for="damageRate">Nagad Account No</label>
                    <div class="col-sm-8">
                        <select class="form-control" disabled required="required" name="account_no" id="status">
                            <option></option>
                            <?php
                            $i = '0';
                            foreach ($obj->view_all_by_cond("bank_registration","type=4") as $value) {
                                $i++;
                                ?>
                                <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>
                                    - Nagad</option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12"></div>
                
                <div class="col-md-12"></div>
                <div class="">
                    <label class="control-label col-sm-4" for="diposited_by">Depositor Name</label>
                    <div class="col-sm-8">
                        <input type="text" disabled name="diposited_by" placeholder="" class="form-control">
                    </div>
                </div>
            </div>
            
            <div id="rocket_info" style="display: none;" class="form-group">
                <div class="">
                    <label class="control-label col-sm-4" for="damageRate">Rocket Account No</label>
                    <div class="col-sm-8">
                        <select class="form-control" disabled required="required" name="account_no" id="status">
                            <option></option>
                            <?php
                            $i = '0';
                            foreach ($obj->view_all_by_cond("bank_registration","type=5") as $value) {
                                $i++;
                                ?>
                                <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>
                                    - Rocket</option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12"></div>
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
                    <label class="control-label col-sm-4" for="details">Expense Details:</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" name="details" id="ResponsiveDetelis" rows="6"></textarea>
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
        placeholder: "Select Account Head",
        allowClear: true
    });

    $('select[name="payment_method"]').on('change', function () {
    const method = this.value;

    // Hide all info sections and disable their inputs
    $('#bank_info, #baksh_info, #nagad_info, #rocket_info').each(function () {
        $(this).find('select[name="account_no"], input[name="diposited_by"]').attr('disabled', 'disabled');
        $(this).hide();
    });

    // Show and enable the appropriate section based on the selected payment method
    if (method === '0') { // Bank
        $('#bank_info select[name="account_no"]').removeAttr('disabled');
        $('#bank_info input[name="diposited_by"]').removeAttr('disabled');
        $('#bank_info').show();
    } else if (method === '3') { // Bkash
        $('#baksh_info select[name="account_no"]').removeAttr('disabled');
        $('#baksh_info input[name="diposited_by"]').removeAttr('disabled');
        $('#baksh_info').show();
    } else if (method === '4') { // Nagad
        $('#nagad_info select[name="account_no"]').removeAttr('disabled');
        $('#nagad_info input[name="diposited_by"]').removeAttr('disabled');
        $('#nagad_info').show();
    } else if (method === '5') { // Rocket
        $('#rocket_info select[name="account_no"]').removeAttr('disabled');
        $('#rocket_info input[name="diposited_by"]').removeAttr('disabled');
        $('#rocket_info').show();
    }
});

</script>