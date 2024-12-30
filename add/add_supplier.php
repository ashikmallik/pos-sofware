<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$data = $obj->details_by_cond("tbl_supplier", "id!=0 ORDER BY id DESC");

//$id= intval($data['ag_id']);

//if (($data['id'] + 1) < 10) {$STD = "SUPL0000"; }
//else if (($data['id'] + 1) < 100) {$STD = "SUPL000";}
//else if (($data['id'] + 1) < 1000) {$STD = "SUPL00";}
//else if (($data['id'] + 1) < 10000) {$STD = "SUPL0";}
//else {$STD = "SUPL";}
//$STD .= $data['id'] + 1;

if (isset ($_POST['submit'])) {
    extract($_POST);
    $form_data_tbl_supplier = array(
        'supplier_name' => str_replace("'",'',$name),
        'supplier_mobile_no' => isset($mobile)?str_replace("'",'',$mobile): null,
        'supplier_address' => str_replace("'", "", $address),
        'supplier_email' => str_replace("'",'',$email),
        'target' => str_replace("'",'',$target),
        'comission' => str_replace("'",'',$comission),
        'supplier_id' => "0",
        'supplier_status' => '1',
        'supplier_company' => str_replace("'",'',$company),
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    $form_data_tbl_supplier_insert = $obj->insert_by_condition("tbl_supplier", $form_data_tbl_supplier, " ");

    if ($form_data_tbl_supplier_insert < 10) {$STD = "SUPL0000"; }
    else if ($form_data_tbl_supplier_insert < 100) {$STD = "SUPL000";}
    else if ($form_data_tbl_supplier_insert < 1000) {$STD = "SUPL00";}
    else if ($form_data_tbl_supplier_insert < 10000) {$STD = "SUPL0";}
    else {$STD = "SUPL";}
    $STD .= $form_data_tbl_supplier_insert;

    $form_supplier_id_update = array(
            'supplier_id' => $STD
    );

    $obj->Update_data('tbl_supplier',$form_supplier_id_update,'id='.$form_data_tbl_supplier_insert);
    if (!empty($opening_balance) && ($opening_balance != 0)) {
        $payment_method_type = 1;
        $type = '';
        if ($acc_head == 9) {
            $type = 'Advance';
        } else if ($acc_head == 10) {
            $type = 'Due';
        }
        
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

        // if ($payment_method == 'bank') {
            $payment_method_type = 0;
            $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
            $total_balance = $total_balance_Data['balance'];
            $balance_calculate = ($total_balance - $opening_balance);

            $form_data_for_bank = array(
                'mobile_banking_name' => $mobile_banking_name,
                'type' => $payment_method,
                'account_no' => $account_no,
                'chq_no' => $_POST['chq_no'],
                'description' => 'Supplier Opening Balance.',
                'credit' => $opening_balance,
                'debit' => 0,
                'balance' => $balance_calculate,
                'withdraw_by' => $withdraw_by,
                'diposited_by' => '',
                'entry_by' => $userid,
                'entry_date' => date('Y-m-d'),
                'update_by' => $userid
            );

            $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
        // }

        $form_tbl_accounts = array(
            'acc_description' => "Supplier Opening Balance with $type ",
            'acc_amount' => $opening_balance,
            'acc_type' => $acc_head,
            'purchase_or_sell_id' => 0,
            'cus_or_sup_id' => $STD,
            'payment_method' => $payment_method,
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    }
    if($form_data_tbl_supplier_insert){
        $notification = '<div class="alert alert-success"><strong>Supplier Added Successfully</strong></div>';
        ?>
        <script>
            window.location = "?q=add_supplier";
        </script>
        <?php
    }else{
        $notification = '<div class="alert alert-danger"><strong>Sorry! operation failed</strong></div>';
    }
}

?>
<!--===================end Function===================-->
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

<?php

if (isset($notification)) {
    echo $notification;
}
?>
<span style="color: red; font-size: 20px;text-align:left;"></span>
<div class="row" style="padding:10px; font-size: 12px;">
    <form role="form" enctype="multipart/form-data" method="post">
        <div class="row" style="padding:10px; font-size: 12px;">
            <div class="col-md-6 col-md-offset-3 bg-grey-700 text-center" style="margin-bottom:5px;">
                <h5>Add Supplier</h5>
            </div>
            <div class="col-md-6 col-md-offset-3">
                <div class="form-group">
                    <label>Supplier's Name</label>
                    <input type="text" name="name" class="form-control"
                           placeholder="Provide supplier's Name" required="required">
                </div>
                <div class="form-group">
                    <label>Supplier's Company Name</label>
                    <input type="text" name="company" class="form-control" id="ResponsiveTitle"
                           placeholder="Provide Supplier's Company" required="required">
                </div>
                <div class="form-group">
                    <label>Mobile No</label>
                    <input type="text" name="mobile" onkeypress="return numbersOnly(event)" class="form-control"
                           id="ResponsiveTitle"
                           placeholder="Provide Supplier's Mobile No">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" id="ResponsiveTitle"
                           placeholder="Provide Supplier's Email Address">
                </div>
                <div class="form-group">
                    <label>Terget</label>
                    <input type="number" name="target" class="form-control" id="ResponsiveTitle"
                           placeholder="input target">
                </div>
                <div class="form-group">
                    <label>Comission</label>
                    <input type="number" name="comission" class="form-control" id="ResponsiveTitle"
                           placeholder="input Comission">
                </div>
                <div class="form-group">
                    <label>supplier Address</label>
                    <textarea class="form-control" name="address" id="ResponsiveDetelis" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label>Opening Balance</label>
                    <div class="input-group">
                        <div class="input-group-btn">
                            <select class="btn btn-default" name="acc_head">
                                <option value="9"> Advance</option>
                                <option value="10"> Due</option>
                            </select>
                        </div><!-- /btn-group -->
                        <input style="height:32px" onkeypress="return numbersOnly(event)" type="text" class="form-control" name="opening_balance">
                        <span class="input-group-addon">Tk</span>
                    </div><!-- /input-group -->
                </div>
                <div class="form-group">
                    <label>Payment Method</label>
                    <div class="input-group-btn">
                        <select class="btn btn-default" name="payment_method">
                            <option value="1" selected="selected"> Cash</option>
                                <option value="0"> Bank</option>
                                <option value="3"> Bkash</option>
                                <option value="4"> Nagod</option>
                                <option value="5"> Rocket</option>
                        </select>
                    </div><!-- /btn-group -->
                </div>
                <div id="bank_info" style="display: none;">
                    <div class="form-group">
                        <label for="account_no">Bank Account No</label>
                        <div>
                            <select class="form-control" name="account_no" id="status">
                                <option value="">Select Bank Account</option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all_by_cond("bank_registration","type=2") as $value) {
                                    $i++;  ?>
                                    <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>--<?php echo isset($value['bank_name']) ? $value['bank_name'] : NULL; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Check No</label>
                        <input type="text" name="chq_no" placeholder="Check No" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Withdraw Name</label>
                        <input type="text" name="withdraw_by" placeholder="Default take Customer name" class="form-control">
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
                        <label>Withdraw Name</label>
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
                        <label>Withdraw Name</label>
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
                        <label>Withdraw Name</label>
                        <input type="text" name="withdraw_by" placeholder="Default take Customer name" class="form-control">
                    </div>
                </div>
                

            </div>
        </div>
        <!--            row-->
        <div class="row" style="text-align: center; padding: 5px 0px 15px 25px; font-size: 12px;">
            <button type="submit" class="btn btn-success text-center" name="submit">Add New supplier</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#datepicker').datepicker();
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