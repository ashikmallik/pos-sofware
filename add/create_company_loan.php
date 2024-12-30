<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

//===================Add Function===================
$total_amount = 0;
$due = 0;

$company_take_loan_from_person_type = $obj->getAccTypeId('company_take_loan_from_person'); // for accounts

if (isset($_POST['add_loan'])) {
    $payment_method_type = 1;
    extract($_POST);
    $personData = $obj->details_by_cond("tbl_person", "id = '$person_id'");
    $person_name = $personData['person_name'];
    $payment_method_type = 1;
    if ($_POST['payment_method'] == 'bank') {
        $payment_method_type = 0;
        $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
        $total_balance = $total_balance_Data['balance'];
        $balance_calculate = ($total_balance - $_POST['loan']);

        $form_data_for_bank = array(
            'account_no' => $_POST['account_no'],
            'description' => 'Provide Loan to Person.',
            'credit' => 0,
            'debit' =>  $_POST['loan'],
            'balance' => $balance_calculate,
            'withdraw_by' => '',
            'diposited_by' => $_POST['diposited_by'],
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );

        $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
    }

    $form_tbl_accounts = array(
        'acc_description' => "Company Take loan ".$loan."tk From Person " . $person_name . ". ".$_POST['description'],
        'acc_amount' => $loan,
        'acc_type' => $company_take_loan_from_person_type,
        'purchase_or_sell_id' => 'p_' . $person_id,
        'cus_or_sup_id' => $personData['person_id'],
        'payment_method' => $payment_method_type,
        'acc_head' => 0,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");

    $form_tbl_person = array(
        'person_id' => $person_id,
        'loan_recieve' => $loan,
        'loan_repayment' => 0,
        'accounts_id' => $tbl_accounts_add,
        'created_at' => date('Y-m-d'),
    );

    if ($obj->insert_by_condition("tbl_company_lend", $form_tbl_person, " ")) {

        $obj->notificationStore('Loan Received Form ' . $personData['person_name'] . ' Successfully', 'success');
        echo '<script> window.location.href = window.location.href; </script>';
    } else {
        $obj->notificationStore('Loan Receiving Failed');

        $notification = '<div class="alert alert-danger">Insert Failed</div>';
    }
}

?>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

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

<div class="row">
    <div class="col-md-12 bg-slate-800 text-center">
        <h4>Welcome to Take Loan From Person</h4>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12 padding_5_px">
        <?php $obj->notificationShowRedirect(); ?>
    </div>
</div>

<div class="row" style="font-size: 12px;">
    <form id="purchase_form" class="form-horizontal" role="form" enctype="multipart/form-data" method="post">

        <div class="col-md-8 col-md-offset-1">

            <div class="form-group">
                <label class="control-label col-sm-4" for="person_id">Person :</label>
                <div class="col-sm-8">
                    <select data-live-search="true"  required="required" name="person_id">
                        <option></option>
                        <?php
                        $i = '0';
                        foreach ($obj->view_all("tbl_person") as $person) {
                            $i++;
                            ?>
                            <option value="<?php echo isset($person['id']) ? $person['id'] : NULL; ?>">
                                <?php echo isset($person['person_name']) ? $person['person_name'] : NULL;?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                    <b><p class="text-primary" id="person_details"></p></b>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-md-offset-1">
            <div class="form-group">
                <label class="control-label col-sm-4" for="person_id">Loan Amount (Take) :</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <select class="btn btn-default" name="payment_method">
                                <option value="cash" selected="selected"> Cash</option>
                                <option value="bank"> Bank</option>
                            </select>
                        </div>
                        <input type="number" onkeypress="numbersOnly(e)" required name="loan" class="form-control" placeholder="Enter Loan Amount">
                    </div>

                </div>
            </div>
        </div>

        <div id="bank_info" style="display: none;" class="col-md-8 col-md-offset-1">
            <div class="form-group">
                <label for="account_no" class="col-sm-4 text-right">Bank Account No</label>
                <div class="col-sm-8">
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
                <label class="col-sm-4 text-right">Dipositer Name</label>
                <div class="col-sm-8">
                    <input type="text"  name="diposited_by" placeholder="Default take Customer name"
                           class="form-control">
                </div>
            </div>
        </div>


        <div class="col-md-8 col-md-offset-1">
            <div class="form-group">
                <label class="control-label col-sm-4" for="person_id">Description :</label>
                <div class="col-sm-8">
                    <textarea name="description" rows="7" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-2 col-md-offset-5">
                <div class="text-center">
                    <button type="submit" class="btn btn-block btn-success" name="add_loan">Loan Entry</button>
                </div>
            </div>
        </div>
    </form>
</div>
<hr>

<script type="text/javascript">
    $(document).ready(function () {
        $('select[name="person_id"]').selectpicker();

        function showPersonLoan(personId) {
            $.get('ajax_action/ajax_person_loan_check.php', {'person': personId}, function (result) {
                $('p#person_details').html('Previous Loan of this Person : ' + result.previous_loan + ' tk');

            }, 'json');
        }

        $('select[name="person_id"]').on('change', function (e) { // add new row when new item selected

            var personId = $(this).find(':selected').val();

            showPersonLoan(personId);
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('select[name="employee_id"]').selectpicker();

        $('select[name="payment_method"').on('change', function () { // banking section will show when click bank
            if (this.value == 'bank') {
                $('#bank_info select[name="account_no"]').removeAttr('disabled');
                $('#bank_info input[name="withdraw_by"]').removeAttr('disabled');
                $('#bank_info').show();
            } else {
                $('#bank_info select[name="account_no"]').attr('disabled', 'disabled');
                $('#bank_info input[name="withdraw_by"]').attr('disabled', 'disabled');
                $('#bank_info').hide();
            }
        });
    });
</script>
