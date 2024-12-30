<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$data = $obj->details_by_cond("tbl_customer", "id!=0 ORDER BY id DESC");

//$id= intval($data['ag_id']);

//if (($data['id'] + 1) < 10) {$STD = "CUS0000";}
//else if (($data['id'] + 1) < 100) {$STD = "CUS000";}
//else if (($data['id'] + 1) < 1000) {$STD = "CUS00";}
//else if (($data['id'] + 1) < 10000) {$STD = "CUS0";}
//else {$STD = "CUS";}
//$STD .= $data['id'] + 1;

//===================Add Function===================

if (isset ($_POST['submit'])) {

    extract($_POST);
    $form_data_tbl_customer = array(
        'cus_name' => str_replace("'", '', $name),
        'cus_mobile_no' => isset($mobile) ? str_replace("'", '', $mobile) : null,
        'cus_address' => str_replace("'", "", $address),
        'cus_email' => str_replace("'", '', $email),
        'target' => str_replace("'",'',$target),
        'comission' => str_replace("'",'',$comission),
        'cus_id' => "0",
        'cus_status' => '1',
        'cus_company' => str_replace("'", '', $company),
        'type'=>$type,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    $form_data_tbl_customer_insert = $obj->insert_by_condition("tbl_customer", $form_data_tbl_customer, " ");
    if ($form_data_tbl_customer_insert < 10) {$STD = "CUS0000";}
    else if ($form_data_tbl_customer_insert < 100) {$STD = "CUS000";}
    else if ($form_data_tbl_customer_insert < 1000) {$STD = "CUS00";}
    else if ($form_data_tbl_customer_insert < 10000) {$STD = "CUS0";}
    else {$STD = "CUS";}

    $STD .= $form_data_tbl_customer_insert;
    $form_update_customer_id = array(
            'cus_id' => $STD
    );
    $obj->Update_data('tbl_customer',$form_update_customer_id,'id='.$form_data_tbl_customer_insert);
    //for empty opennin balance. It's created for edit opening balance
    if (empty($opening_balance) || ($opening_balance == 0)) {
        $payment_method_type = 1;
        $type = '';
        if ($acc_head == 7) {
            $type = 'Advance';
        } else if ($acc_head == 8) {
            $type = 'Due';
        }

        $form_tbl_accounts = array(
            'acc_description' => "Customer Opening Balance with $type ",
            'acc_amount' => 0,
            'acc_type' => $acc_head,
            'purchase_or_sell_id' => 0,
            'cus_or_sup_id' => $STD,
            'payment_method' => $payment_method_type,
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    }
    //end empty opennin balance. It's created for edit opening balance

    if (!empty($opening_balance) && ($opening_balance != 0)) {
        $payment_method_type = 1;
        $type = '';
        if ($acc_head == 7) {
            $type = 'Advance';
        } else if ($acc_head == 8) {
            $type = 'Due';
        }

        if ($payment_method == 'bank') {
            $payment_method_type = 0;
            $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
            $total_balance = $total_balance_Data['balance'];
            $balance_calculate = ($total_balance + $opening_balance);

            $form_data_for_bank = array(
                'account_no' => $account_no,
                'chq_no' => $_POST['chq_no'],
                'description' => 'Customer Opening Balance.',
                'credit' => 0,
                'debit' => $opening_balance,
                'balance' => $balance_calculate,
                'withdraw_by' => '',
                'diposited_by' => $diposited_by,
                'entry_by' => $userid,
                'entry_date' => date('Y-m-d'),
                'update_by' => $userid
            );

            $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
        }

        $form_tbl_accounts = array(
            'acc_description' => "Customer Opening Balance with $type ",
            'acc_amount' => $opening_balance,
            'acc_type' => $acc_head,
            'purchase_or_sell_id' => 0,
            'cus_or_sup_id' => $STD,
            'payment_method' => $payment_method_type,
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    }

    if ($form_data_tbl_customer_insert) {
        
        global $notification;
        //sms 
        $body = "Welcome to " . $obj->companyname;
        if (!empty($mobile)) {
            $notification .=  $obj->smsSend($mobile,$body);
        }
        if (!empty($email)) {
            //email
            $notification .= $obj->emailSend($email,$body,'');
        }
        
        $notification = '<div class="alert alert-success">Customer Added Successfully</div>';
    } else {
        $notification = '<div class="alert alert-danger">Sorry! operation failed</div>';
    }

}

?>
<!--===================end Function===================-->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>


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

<div class="col-md-8 col-md-offset-2">
    <?php if (isset($notification)) {echo $notification;} ?>
</div>

<span style="color: red; font-size: 20px;text-align:left;"></span>
<div class="row" style="padding:10px; font-size: 12px;">
    <form role="form" enctype="multipart/form-data" method="post">
        <div class="row" style="padding:10px; font-size: 12px;">
            <div class="col-md-6 col-md-offset-3 bg-grey-700 text-center" style="margin-bottom:5px;">
                <h5>Add New Customer</h5>
            </div>
            <div class="col-md-6 col-md-offset-3">
                <div class="form-group">
                    <label>Customer's Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Provide Customer's Name" required="required">
                </div>



                <div class="form-group">
                    <label>Type</label>
                    <div class="input-group">
                            <select required name="type" >
<!--                                 <option hidden>---Select Type---</option>
 -->                             
                                <option value="">Choose Type</option>
                                <option value="1"> Retailer</option>
                                <option value="2"> Workshop</option>
                                <option value="3"> Houseowner</option>
                                <!-- <option value="4"> Hardware</option> -->
                                <option value="5"> Feed</option>
                                <option value="6"> Block Money</option>
                                <option value="7"> Sanatary</option>

                            </select>
                    </div><!-- /input-group -->
                </div>


                <div class="form-group">
                    <label>Customer's Company Name</label>
                    <input type="text" name="company" class="form-control" id="ResponsiveTitle" placeholder="Provide Customer's Company" required="required">
                </div>

                <div class="form-group">
                    <label>Mobile No</label>
                    <input type="text" name="mobile" onkeypress="return numbersOnly(event)" class="form-control" id="ResponsiveTitle" placeholder="Provide Customer's Mobile No">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" id="ResponsiveTitle" placeholder="Provide Customer's Email Address">
                </div>
                <div class="form-group">
                    <label>Customer Address</label>
                    <textarea class="form-control" name="address" id="ResponsiveDetelis" rows="5"></textarea>
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
                    <label>Opening Balance</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-btn">
                            <select class="" name="acc_head">
                                <option value="7"> Advance</option>
                                <option value="8"> Due</option>
                            </select>
                        </div><!-- /btn-group -->
                        <input style="height:32px" onkeypress="return numbersOnly(event)" type="text" class="form-control" name="opening_balance">
                        <span class="input-group-addon">Tk</span>
                    </div><!-- /input-group -->
                </div>
                <div class="form-group">
                    <label>Payment Method</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-btn">
                            <select class="" name="payment_method">
                                <option value="cash"> Cash</option>
                                <option value="bank"> Bank</option>
                            </select>
                        </div><!-- /btn-group -->
                    </div><!-- /input-group -->
                </div>
                <div id="bank_info" style="display: none;">
                    <div class="form-group">
                        <label for="account_no">Bank Account No</label>
                        <div>
                            <select class="form-control" name="account_no" id="status">
                                <option></option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all("bank_registration") as $value) {
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
                        <label>Depositor Name</label>
                        <input type="text" name="diposited_by" placeholder="Default take Customer name" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <!--            row-->
        <div class="row" style="text-align: center; padding: 5px 0px 15px 25px; font-size: 12px;">
            <button type="submit" class="btn btn-success text-center" name="submit">Add New Customer</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#datepicker').datepicker();
        $('select').selectpicker();
    })
    $(document).ready(function () {
        $('select[name="payment_method"').on('change', function () {
            if (this.value == 'bank') {
                $('#bank_info select[name="account_no"]').attr('required', 'required');
                $('#bank_info input[name="diposited_by"]').attr('required', 'required');
                $('#bank_info').show();
            } else {
                $('#bank_info select[name="account_no"]').removeAttr('required');
                $('#bank_info input[name="diposited_by"]').removeAttr('required');
                $('#bank_info').hide();
            }
        });
    })



</script>

