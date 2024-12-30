<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

if (isset($_GET['search'])) {
    $startDate = date('Y-m-d', strtotime($_GET['startDate']));
    $endDate = date('Y-m-d', strtotime($_GET['endDate']));
} else {
    $startDate = date('2019-09-01');
    $endDate = date('Y-m-d');
}

$allPersonLoan = $obj->view_selected_field_by_cond_left_join("tbl_company_lend", 'tbl_person', 'person_id', 'id', 'SUM(tbl_company_lend.loan_recieve) as total_loan_recieve, SUM(tbl_company_lend.loan_repayment) as total_loan_repayment', '*', "tbl_company_lend.id != 0 AND tbl_company_lend.created_at BETWEEN '$startDate' and '$endDate' GROUP BY tbl_company_lend.`person_id`");

$company_repay_loan_to_person_type = $obj->getAccTypeId('company_repay_loan_to_person'); // for accounts

if (isset($_POST['submit_loan_payment'])) {
    $payment_method_type = 1;
    $loan_receive = $obj->get_sum_data('tbl_company_lend','loan_recieve','person_id='.$_POST['person_id']);
    $loan_repay = $obj->get_sum_data('tbl_company_lend','loan_repayment','person_id='.$_POST['person_id']);

    $total_loan_check = $loan_receive - ($loan_repay+$_POST['loan_re_payment']);

    if ($total_loan_check < 0){
        if ($_POST['payment_method'] == 'bank') {
        $payment_method_type = 0;
        $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
        $total_balance = $total_balance_Data['balance'];
        $balance_calculate = ($total_balance - $_POST['loan_re_payment']);

        $form_data_for_bank = array(
            'account_no' => $_POST['account_no'],
            'description' => 'Provide Loan re payment to Person(From Loan Giver List).',
            'credit' => $_POST['loan_re_payment'],
            'debit' => 0,
            'balance' => $balance_calculate,
            'withdraw_by' => $_POST['withdraw_by'],
            'diposited_by' => '',
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );

        $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
    }
        $personData = $obj->details_by_cond('tbl_person', 'id=' . $_POST['person_id']);
        $form_data_loan_accounts = array(
            'acc_description' => 'Company Repay loan of ' . $personData['person_name'].'. '.$_POST['description'],
            'acc_amount' => $_POST['loan_re_payment'],
            'acc_type' => $company_repay_loan_to_person_type,
            'purchase_or_sell_id' => 'p_' . $_POST['person_id'],
            'cus_or_sup_id' => $personData['person_id'],
            'payment_method' => $payment_method_type,
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );

        $accountsId = $obj->insert_by_condition("tbl_account", $form_data_loan_accounts, " ");

        $form_data_company_loan = array(
            'person_id' => $_POST['person_id'],
            'loan_recieve' => 0,
            'loan_repayment' => $_POST['loan_re_payment']-(-$total_loan_check),
            'accounts_id' => $accountsId,
            'created_at' => $date_time,
        );

        $form_data_person_loan = array(
            'person_id' => $_POST['person_id'],
            'loan_recieve' => -$total_loan_check,
            'loan_repayment' => 0,
            'accounts_id' => $accountsId,
            'created_at' => $date_time,
        );

        $obj->insert_by_condition("tbl_person_loan", $form_data_person_loan, " ");
        if($obj->insert_by_condition("tbl_company_lend", $form_data_company_loan, " ")){
            $obj->notificationStore('Company Repay Loan to ' . $personData['person_name'] . ' Successfully', 'success');
            echo '<script>window.location = "?q=all_company_loan";</script>';
        }else{
            $obj->notificationStore('Company Repay Loan Store Failed');
        }
    }else{
        if ($_POST['payment_method'] == 'bank') {
        $payment_method_type = 0;
        $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
        $total_balance = $total_balance_Data['balance'];
        $balance_calculate = ($total_balance - $_POST['loan_re_payment']);

        $form_data_for_bank = array(
            'account_no' => $_POST['account_no'],
            'description' => 'Provide Loan re payment to Person(From Loan Giver List).',
            'credit' => $_POST['loan_re_payment'],
            'debit' => 0,
            'balance' => $balance_calculate,
            'withdraw_by' => $_POST['withdraw_by'],
            'diposited_by' => '',
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );

        $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
    }
        $personData = $obj->details_by_cond('tbl_person', 'id=' . $_POST['person_id']);
        $form_data_loan_accounts = array(
            'acc_description' => 'Company Repay loan of ' . $personData['person_name'].'. '.$_POST['description'],
            'acc_amount' => $_POST['loan_re_payment'],
            'acc_type' => $company_repay_loan_to_person_type,
            'purchase_or_sell_id' => 'p_' . $_POST['person_id'],
            'cus_or_sup_id' => $personData['person_id'],
            'payment_method' => $payment_method_type,
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );

        $accountsId = $obj->insert_by_condition("tbl_account", $form_data_loan_accounts, " ");

        $form_data_person_loan = array(
            'person_id' => $_POST['person_id'],
            'loan_recieve' => 0,
            'loan_repayment' => $_POST['loan_re_payment'],
            'accounts_id' => $accountsId,
            'created_at' => $date_time,
        );

        if($obj->insert_by_condition("tbl_company_lend", $form_data_person_loan, " ")){
            $obj->notificationStore('Company Repay Loan to ' . $personData['person_name'] . ' Successfully', 'success');
            echo '<script>window.location = "?q=all_company_loan";</script>';
        }else{

            $obj->notificationStore('Company Repay Loan Store Failed');
        }
    }
}

?>

<div class="row">
    <div class="col-md-12 padding_5_px">
        <?php $obj->notificationShowRedirect(); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 bg-teal-800">
        <h4>View Company loan List (
            <?php
            if (isset($_GET['search'])) {
                echo date("d-M-Y", strtotime($_GET['startDate'])).' To ';
                echo date("d-M-Y", strtotime($_GET['endDate']));
            } else {
                echo 'This Months';
            } ?> )</h4>
    </div>
</div>
<div class="col-md-12 bg-grey" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="get">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateMonth">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" required="required" type="text" name="startDate" value="<?php if (isset($_GET['startDate'])){echo $_GET['startDate'];}?>" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="damageRate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" required="required" name="endDate" value="<?php if (isset($_GET['endDate'])){echo $_GET['endDate'];}?>" autocomplete="off">
                    <input type="hidden" value="all_company_loan" name="q">
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default"><span class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>
<hr>
<div class="row" style="font-size:12px">
    <div class="col-md-12">
        <table class="table table-responsive table-bordered table-hover " id="datatable-btn">
            <thead>
            <tr class="bg-slate-800">
                <th class="col-md-1">SL</th>
                <th class="col-md-1">Person Name</th>
                <th class="col-md-1">Mobile No</th>
                <th class="col-md-1">Address</th>
                <th class="col-md-2">
                    <small>Total Loan Recieve</small>
                </th>
                <th class="col-md-2">
                    <small>Total Loan Repay</small>
                </th>
                <th class="col-md-2"> Total Loan</th>
                <th class="col-md-1">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0;
            $total_loan_receive = 0;
            $total_loan_repay = 0;
            $total_loan = 0;
            foreach ($allPersonLoan as $personLoan) { 
                $total_loan_receive = $personLoan['total_loan_recieve'] + $total_loan_receive;
                $total_loan_repay = $personLoan['total_loan_repayment'] + $total_loan_repay;?>
                <tr>
                    <td class="text-center"> <?php echo ++$i ?> </td>
                    <td class=""><a href="?q=view_single_company_loan&personid=<?php echo $personLoan['id'] ?><?php if (isset($_GET['startDate'])){ echo '&startDate='.$_GET['startDate'].'&endDate='.$_GET['endDate'].'&search=';}?>"><?php echo $personLoan['person_name'] ?></a>
                    </td>
                    <td class="text-center"> <?php echo $personLoan['person_mobile_no'] ?> </td>
                    <td class=""> <?php echo $personLoan['person_address'] ?> </td>
                    <td class="text-right"> <?php echo number_format($personLoan['total_loan_recieve']) ?> </td>
                    <td class="text-right"> <?php echo number_format($personLoan['total_loan_repayment']) ?> </td>
                    <?php $totalLoan = $personLoan['total_loan_recieve'] - $personLoan['total_loan_repayment']; 
                     $total_loan = $totalLoan + $total_loan;?>

                    <td class="text-right"> <?php echo ($totalLoan < 0) ? '(Advance) ' : '';
                        echo number_format(abs($totalLoan)) ?> </td>

                    <td class="text-center action-btn">
                        <button class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#editloan" data-id="<?php echo $personLoan['id'] ?>" data-person="<?php echo $personLoan['person_name'] ?>">Payment
                        </button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
             <tfoot>
            <tr>
                <th class="text-center">Total</th>
                <th class="text-center "></th>
                <th class="text-center "></th>
                <th class="text-center "></th>
                <th class="text-right "><?php echo number_format($total_loan_receive);  ?></th>
                <th class="text-right "><?php echo number_format($total_loan_repay);  ?></th>
                <th class="text-right "><?php echo number_format($total_loan);  ?></th>
                <th class="text-center "></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<hr>

<div class="modal fade" id="editloan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Payment of Loan Repay for Person <span id="person_name"></span></h4>
            </div>

            <form id="editloanForm" action="" method="post" class="form-horizontal">

                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Loan Repayment</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-btn">
                                <select class="btn btn-default" name="payment_method">
                                    <option value="cash" selected="selected"> Cash</option>
                                    <option value="bank"> Bank</option>
                                </select>
                              </div>
                                <input onkeypress="return numbersOnly(event)" require type="number" name="loan_re_payment" class="form-control">
                                <span class="input-group-addon">Taka</span>
                            </div>
                        </div>
                    </div>
            <div id="bank_info" style="display: none;margin-left: 6px;" class="col-sm-8 col-md-offset-1">
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
                <label class="col-sm-4 text-right">Withdraw Name</label>
                <div class="col-sm-8">
                    <input type="text" name="withdraw_by" placeholder="Default take Customer name" class="form-control">
                </div>
            </div>
        </div>

                    <div class="form-group">
                        <!--<label class="col-sm-3 control-label">Description</label>-->
                        <div class="col-sm-8" style="margin-left: 140px;">
                            <textarea name="description" rows="7" class="form-control" placeholder="Enter Description"></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="person_id" value="">

                </div>
                <div class="modal-footer text-center">

                    <button type="submit" name="submit_loan_payment" class="btn btn-primary">Save Payment</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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

    $(document).ready(function() {
        $("#datatable-btn").dataTable().fnDestroy();
        $('#datatable-btn').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print Loan',
                    footer:true,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    },
                    title: function () {
                        return "Loan Giver List"
                    },
                    customize: function (win) {
                        $(win.document.body).css('font-size', '12px');
                        $(win.document.body).find('h1').addClass('text-center').css('font-size', '20px');
                        $(win.document.body).find('table').addClass('container').css('font-size', 'inherit');
                        $(win.document.body).find('table').removeClass('table-bordered');
                    }
                }
            ]
        } );
    } );


    $(document).ready(function () {
        $('table#datatable-btn').on('click', 'td.action-btn button.edit', function () {

            var personId = $(this).data('id');
            var personName = $(this).data('person');

            console.log(personId);
            console.log(personName);

            $('div#editloan div.modal-header span#person_name').html(personName);
            $('div#editloan form#editloanForm div.modal-body input[name="person_id"]').val(personId);

        });
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

        $('#editloan').on('hidden.bs.modal', function (e) {

            $('form#editloanForm').trigger("reset");
        })

    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function () {
        $('input[name="startDate"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });

        $('input[name="endDate"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });

        $(document).on('mouseover', 'tbody tr td [data-toggle="tooltip"]', function () {
            $('tbody tr td [data-toggle="tooltip"]').tooltip();
        });

    });
</script>

<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>