<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$total_balance = 0;
if (isset ($_POST['submit'])) {
    extract($_POST);
    
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
    foreach ($obj->view_all("bank_account") as $value) {
        $total_balance += isset($value['balance']) ? $value['balance'] : NULL;
    }
    $form_data = array(
        'mobile_banking_name' => $mobile_banking_name,
        'withdrow_or_deposit' => 2,
        'type' => $payment_method,
        'account_no' => $account_no,
        'description' => 'Bank Withdrow: '.str_replace("'", "", $description),
        'credit' => $deposit_amount,
        'debit' => 0,
        'balance' => $total_balance - $withdraw_money,
        'withdraw_by' => $withdraw_by,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );

    $form_tbl_accounts = array(
        'acc_description' => "Bank Withdrow: ".str_replace("'", "", $description),
        'acc_amount' => $deposit_amount,
        'acc_type' => 24,
        'purchase_or_sell_id' => '',
        'cus_or_sup_id' => 0,
        'acc_head' => 0,
        'payment_method' => $payment_method,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");

    $service_add = $obj->insert_by_condition("bank_account", $form_data, " ");
    if ($service_add) { ?>
        <script>
            window.location = "?q=view_bank_transection";
        </script>
        <?php
    } else {
        echo $notification = 'Insert Failed';
    }
}
?>
<!--===================end Function===================-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    function numbersOnly(e) // Numeric Validation
    {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) {
            if ((unicode < 2534 || unicode > 2543) && (unicode < 48 || unicode > 57)) {
                return false;
            }
        }
    }
</script>

<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">

    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
    
</div>
<div class="col-md-12 bg-teal-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-12">
        <h4><strong>Withdraw Money</strong></h4>
    </div>
</div>
<div class="row" style="padding:10px; font-size: 12px;">
    <div class="col-md-7 col-md-offset-2">
        <form role="form" enctype="multipart/form-data" method="post">
            <div class="row" style="padding:10px; font-size: 12px;">

            <div class="col-md-6 col-md-offset-3">
                <div class="form-group">
                    <label>Withdrow Amount</label>
                    <div class="col-sm-12 input-group">
                        <div class="input-group-btn">
                            <select class="btn btn-default" name="payment_method">
                                <option value="1" selected="selected"> Cash</option>
                                <option value="0"> Bank</option>
                                <option value="3"> Bkash</option>
                                <option value="4"> Nagod</option>
                                <option value="5"> Rocket</option>
                            </select>
                        </div><!-- btn-group -->
                        <input type="text" style="height:32px" onkeypress="return numbersOnly(event)" name="deposit_amount" class="form-control">
                        <span class="input-group-addon">TK</span>
                    </div>
                </div>

                <div id="bank_info" style="display: none;">

                    <div class="form-group">
                    <label>Deposite Account No</label>
                    <select class="form-control" required="required" name="account_no" id="status">
                        <option value="">select</option>
                        <?php
                        $i = '0';
                        foreach ($obj->view_all_by_cond("bank_registration","type=2") as $value) {
                            $i++;
                            ?>
                            <option  value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>--<?php echo isset($value['account_name']) ? $value['account_name'] : NULL; ?>--<?php echo isset($value['bank_name']) ? $value['bank_name'] : NULL; ?></option>
                            <?php
                        }
                        ?> 
                    </select>  
                </div>              
                    
                </div>
                
                <div id="baksh_info" style="display: none;">
                    <div class="form-group">
                        <label for="account_no">Bkash Account No</label>
                        <div>
                            <select class="form-control" name="account_no" id="status">
                                <option value="">Select Bkash Account</option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all_by_cond("bank_registration","type=3") as $value) {
                                    $i++;  ?>
                                    <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>--Bkash</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Deposite Name</label>
                        <input type="text" name="withdraw_by" placeholder="Default take Customer name" class="form-control">
                    </div>
                </div>
                
                <div id="nagad_info" style="display: none;">
                    <div class="form-group">
                        <label for="account_no">Nagad Account No</label>
                        <div>
                            <select class="form-control" name="account_no" id="status">
                                <option value="">Select Nagad Account</option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all_by_cond("bank_registration","type=4") as $value) {
                                    $i++;  ?>
                                    <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>--Nagad</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

            
                    <div class="form-group">
                        <label>Deposite Name</label>
                        <input type="text" name="withdraw_by" placeholder="Default take Customer name" class="form-control">
                    </div>
                </div>
                
                <div id="rocket_info" style="display: none;">
                    <div class="form-group">
                        <label for="account_no">Rocket Account No</label>
                        <div>
                            <select class="form-control" name="account_no" id="status">
                                <option value="">Select Rocket Account</option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all_by_cond("bank_registration","type=5") as $value) {
                                    $i++;  ?>
                                    <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>--Rocket</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label>Deposite Name</label>
                        <input type="text" name="withdraw_by" placeholder="Default take Customer name" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label> Description</label>
                    <textarea class="form-control" name="description" id="ResponsiveDetelis" rows="6"></textarea>
                </div>  

                <div class="form-group">
                    <label>Depositor Name</label>
                    <input type="text" name="diposited_by" class="form-control" id="ResponsiveTitle" required="required" >
                </div>  

                <div class="row" style="text-align: center; padding: 5px 0px 15px 25px; font-size: 12px;">
                    <button type="submit" class="btn btn-lg btn-success pull-left" name="submit">Submit</button> 
                </div>                                                                                 

            </div>

        </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    

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