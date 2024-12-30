<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

//===================Add Function===================

$pay_type = 0;
$receive_type = 1;

$company_provide_security_money_to_supplier = $obj->getAccTypeId('company_provide_s_money_to_supplier');

if (isset($_POST['provide_s_money'])) {
    extract($_POST);
    $supplier_data = $obj->details_by_cond("tbl_supplier", "id = '$sup_id'");
    $payment_method_type = 1;
    if ($payment_method == 'bank') {
        $payment_method_type = 0;
        $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
        $total_balance = $total_balance_Data['balance'];

        $form_data_for_bank = array(
            'account_no' => $account_no,
            'chq_no' => $_POST['chq_no'],
            'description' => "Company Gave Security Money To Supplier " . $supplier_data['supplier_name'] . " ",
            'credit' => $paid_amount,
            'debit' => 0,
            'balance' => ($total_balance - $paid_amount),
            'diposited_by' => (empty($diposited_by) ? $customer_name : $diposited_by),
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
    }


    $form_tbl_accounts = array(
        'acc_description' => "Company Gave Security Money To Supplier - " .$supplier_data['supplier_name'] .". ". $description . " ",
        'acc_amount' => $paid_amount,
        'acc_type' => $company_provide_security_money_to_supplier,
        'purchase_or_sell_id' => 0,
        'cus_or_sup_id' => $supplier_data['supplier_id'],
        'payment_method' => $payment_method_type,
        'acc_head' => 0,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");

    $form_tbl_security_money_transaction = array(
        'customer_id' => 0,
        'supplier_id' => $sup_id,
        'amount' => $paid_amount,
        'pay_receive' => $pay_type,
        'accounts_id' => $tbl_accounts_add,
        'description' => $description,
        'created_at' => date('Y-m-d'),
    );
    if($obj->insert_by_condition("tbl_security_money_transaction", $form_tbl_security_money_transaction, " ")){

        $obj->notificationStore('Provide Security Money To Supplier Successfully', 'success');

       echo '<script>window.location.href=window.location.href;</script>';
    } else {
        $obj->notificationStore('Provide Security Money To Supplier Successfully', 'success');
        echo '<script>window.location.href=window.location.href;</script>';
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
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
    <div class="col-md-12 bg-primary text-center">
        <h4>Welcome to Give Security Money To Supplier</h4>
    </div>
</div>
<div class="row">
    <div class="col-md-12 padding_5_px">
        <?php $obj->notificationShowRedirect(); ?>
    </div>
</div>
<hr>

<div class="row" style="font-size: 12px;">
    <form id="purchase_form" class="form-horizontal" role="form" enctype="multipart/form-data" method="post">

        <div class="col-md-8 col-md-offset-1">

            <div class="form-group">
                <label class="control-label col-sm-4" for="customer_id">Supplier :</label>
                <div class="col-sm-8">
                    <select class="form-control" required="required" name="sup_id" id="status">
                        <option></option>
                        <?php
                        $i = '0';
                        foreach ($obj->view_all("tbl_supplier") as $supplier) {
                            $i++;
                            ?>
                            <option value="<?php echo isset($supplier['id']) ? $supplier['id'] : NULL; ?>"><?php echo isset($supplier['supplier_name']) ? $supplier['supplier_name'] : NULL; ?> - <?php echo isset($supplier['supplier_id']) ? $supplier['supplier_id'] : NULL; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-4">Amount (tk)  :</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <select class="btn btn-default" name="payment_method">
                                <option value="cash" selected="selected">Payment in Cash</option>
                                <option value="bank">Payment in Bank</option>
                            </select>
                        </div><!-- btn-group -->
                        <input type="text" style="height:32px" onkeypress="return numbersOnly(event)" name="paid_amount" class="form-control" required>
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
                <label class="control-label col-sm-4">Description  :</label>
                <div class="col-sm-8">
                    <textarea name="description" class="form-control" id="" cols="30" rows="5"></textarea>
                </div>
            </div>

        </div>

        <div class="col-md-12">
            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-success" name="provide_s_money">Provide Security Money</button>
            </div>
        </div>

    </form>
</div>
<hr>

<script type="text/javascript">
    $(document).ready(function () {

        $('select[name="sup_id"]').selectpicker();


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
