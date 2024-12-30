<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$allEmployeeTransaction = $obj->view_selected_field_by_cond_left_join("tbl_employee_transaction", 'tbl_employee',
    'employee_id', 'id', 'SUM(tbl_employee_transaction.salary_amount) as total_salary_amount, 
    SUM(tbl_employee_transaction.conveyance) as total_conveyance, 
    SUM(tbl_employee_transaction.received_amount) as total_received_amount, 
    SUM(tbl_employee_transaction.punishment) as total_punishment', '*', 'tbl_employee_transaction.id != 0 GROUP BY tbl_employee_transaction.`employee_id`');


$employeeSalaryReceiveType = 1;
$employeeSalaryDueType = 0;

$company_give_payment_to_employee = $obj->getAccTypeId('company_give_payment_to_employee');


if (isset($_POST['submit_employee_payment'])) {

    $employee_data = $obj->details_by_cond("tbl_employee", "id = " . $_POST['employee_id'] . "");
    if( !empty($_POST['employee_payment'])){

        $form_tbl_accounts = array(
            'acc_description' => "Company give payment to Employee - " . $employee_data['employee_name'] . ". ".$_POST['description'],
            'acc_amount' => $_POST['employee_payment'],
            'acc_type' => $company_give_payment_to_employee,
            'purchase_or_sell_id' => 0,
            'cus_or_sup_id' => $employee_data['employee_id'],
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
        
        if ($_POST['payment_method'] == 'bank') {

            $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
            $total_balance = $total_balance_Data['balance'];
            $form_data_for_bank = array(
                'account_no' => $_POST['account_no'],
                'description' => "Company give payment to Employee - " . $employee_data['employee_name'] . ". ".$_POST['description'],
                'credit' => 0,
                'debit' => $_POST['employee_payment'],
                'balance' => ($total_balance - $_POST['employee_payment']),
                'withdraw_by' => $_POST['withdraw_by'],
                'entry_by' => $userid,
                'entry_date' => date('Y-m-d'),
                'update_by' => $userid
            );
            $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
        }
    }

    $form_tbl_employee_transaction = array(
        'employee_id' => $_POST['employee_id'],
        'salary_amount' => 0,
        'conveyance' => 0,
        'received_amount' => $_POST['employee_payment'],
        'received_due' => $employeeSalaryReceiveType,
        'punishment' => $_POST['employee_punishment'],
        'accounts_id' => !empty($_POST['employee_payment']) ? $tbl_accounts_add : 0,
        'created_at' => date('Y-m-d'),
    );
    if ($obj->insert_by_condition("tbl_employee_transaction", $form_tbl_employee_transaction, " ")) {
        $obj->notificationStore('Employee Payment Stored Successfully ', 'success');
        echo '<script>window.location.href=window.location.href;</script>';
    } else {
        $obj->notificationStore('Employee Payment Stored Failed ');
        echo '<script>window.location.href=window.location.href;</script>';
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
        <h4 class="col-md-9">View Employee's Transaction</h4>
        <button type="submit" class="btn btn-primary btn-sm pull-right" onclick="printDiv('print_transaction')">Print Transaction
        </button>
    </div>
</div>
<hr>
<div class="row" style="font-size:12px;">
    <div class="col-md-12">
        <table class="table table-responsive table-bordered table-hover " id="datatable">
            <thead>
            <tr class="bg-teal-800">
                <th class="col-md-1">SL</th>
                <th class="col-md-2">Employee Name</th>
                <th class="col-md-1">Mobile No</th>
                <th class="col-md-2">Address</th>
                <th class="col-md-1"> Total Salary Amount</th>
                <th class="col-md-1"> Total Conveyance Amount</th>
                <th class="col-md-1"> Total Received Amount</th>
                <th class="col-md-1">Total Punishment Amount</th>
                <th class="col-md-1">Employee Due From Company</th>
                <th class="col-md-1">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0;
            $total_salary = 0;
            $total_conveyance_amount = 0 ;
            $total_received_amount = 0;
            $total_punishment_amount = 0;
            $total_employee_due_from_company = 0;
            foreach ($allEmployeeTransaction as $employeePayment) {
                $total_salary = $employeePayment['total_salary_amount'] + $total_salary;
                $total_conveyance_amount = $employeePayment['total_conveyance'] + $total_conveyance_amount;
                $total_received_amount = $employeePayment['total_received_amount'] + $total_received_amount;
                $total_punishment_amount = $employeePayment['total_punishment'] + $total_punishment_amount;
                ?>
                <tr>
                    <td class="text-center"> <?php echo ++$i ?> </td>
                    <td class=""><a href="?q=view_single_employee_transaction&employeeid=<?php echo $employeePayment['id'] ?>"><?php echo $employeePayment['employee_name'] ?></a>
                    </td>
                    <td class="text-center"> <?php echo $employeePayment['employee_mobile_no'] ?> </td>
                    <td class=""> <?php echo $employeePayment['employee_address'] ?> </td>
                    <td class="text-right"> <?php echo number_format($employeePayment['total_salary_amount']) ?> </td>
                    <td class="text-right"> <?php echo number_format($employeePayment['total_conveyance']) ?> </td>
                    <td class="text-right"> <?php echo number_format($employeePayment['total_received_amount']) ?> </td>
                    <td class="text-right"> <?php echo number_format($employeePayment['total_punishment']) ?> </td>
                    <?php
                    $totalEmployeeDue = ($employeePayment['total_salary_amount'] + $employeePayment['total_conveyance'])
                        - ($employeePayment['total_received_amount'] + $employeePayment['total_punishment']);
                    $total_employee_due_from_company = $totalEmployeeDue + $total_employee_due_from_company;
                    ?>

                    <td class="text-right"> <?php echo ($totalEmployeeDue < 0) ? '(Advance) ' : '';
                        echo number_format(abs($totalEmployeeDue)) ?> </td>

                    <td class="text-center action-btn">
                        <button class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#employee_payment"
                            data-id="<?php echo $employeePayment['id'] ?>" data-employee="<?php echo $employeePayment['employee_name'] ?>">Employee Payment
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
                <th class="text-right "><?php echo number_format($total_salary);  ?></th>
                <th class="text-right "><?php echo number_format($total_conveyance_amount);  ?></th>
                <th class="text-right "><?php echo number_format($total_received_amount);  ?></th>
                <th class="text-right "><?php echo number_format($total_punishment_amount);  ?></th>
                <th class="text-right "><?php echo number_format($total_employee_due_from_company);  ?></th>
                <th class="text-center "></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="row" style="font-size:12px; display: none" id="print_transaction">
    <div class="col-md-12">
        <h3 class="text-center">Employee Transaction</h3>
        <table class="table table-responsive table-bordered table-hover " id="datatable">
            <thead>
            <tr class="bg-teal-800">
                <th class="col-md-1">SL</th>
                <th class="col-md-2">Employee Name</th>
                <th class="col-md-1">Mobile No</th>
                <th class="col-md-2">Address</th>
                <th class="col-md-1"> Total Salary Amount</th>
                <th class="col-md-1"> Total Conveyance Amount</th>
                <th class="col-md-1"> Total Received Amount</th>
                <th class="col-md-1">Total Punishment Amount</th>
                <th class="col-md-1">Employee Due From Company</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0;
            $total_salary = 0;
            $total_conveyance_amount = 0 ;
            $total_received_amount = 0;
            $total_punishment_amount = 0;
            $total_employee_due_from_company = 0;
            foreach ($allEmployeeTransaction as $employeePayment) {
                $total_salary = $employeePayment['total_salary_amount'] + $total_salary;
                $total_conveyance_amount = $employeePayment['total_conveyance'] + $total_conveyance_amount;
                $total_received_amount = $employeePayment['total_received_amount'] + $total_received_amount;
                $total_punishment_amount = $employeePayment['total_punishment'] + $total_punishment_amount;
                ?>
                <tr>
                    <td class="text-center"> <?php echo ++$i ?> </td>
                    <td class="text-center"><?php echo $employeePayment['employee_name'] ?></td>
                    <td class="text-center"> <?php echo $employeePayment['employee_mobile_no'] ?> </td>
                    <td class="text-center"> <?php echo $employeePayment['employee_address'] ?> </td>
                    <td class="text-center"> <?php echo number_format($employeePayment['total_salary_amount']) ?> </td>
                    <td class="text-center"> <?php echo number_format($employeePayment['total_conveyance']) ?> </td>
                    <td class="text-center"> <?php echo number_format($employeePayment['total_received_amount']) ?> </td>
                    <td class="text-center"> <?php echo number_format($employeePayment['total_punishment']) ?> </td>
                    <?php
                    $totalEmployeeDue = ($employeePayment['total_salary_amount'] + $employeePayment['total_conveyance'])
                        - ($employeePayment['total_received_amount'] - $employeePayment['total_punishment']);
                    $total_employee_due_from_company = $totalEmployeeDue + $total_employee_due_from_company;
                    ?>
                    <td class="text-center"> <?php echo ($totalEmployeeDue < 0) ? '(Advance) ' : '';
                        echo number_format(abs($totalEmployeeDue)) ?> </td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <th class="text-center">Total</th>
                <th class="text-center "></th>
                <th class="text-center "></th>
                <th class="text-center "></th>
                <th class="text-center "><?php echo number_format($total_salary);  ?></th>
                <th class="text-center "><?php echo number_format($total_conveyance_amount);  ?></th>
                <th class="text-center "><?php echo number_format($total_received_amount);  ?></th>
                <th class="text-center "><?php echo number_format($total_punishment_amount);  ?></th>
                <th class="text-center "><?php echo number_format($total_employee_due_from_company);  ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<hr>

<div class="modal fade" id="employee_payment" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Employee Payment Record for <span id="person_name"></span></h4>
            </div>

            <form id="employee" action="" method="post" class="form-horizontal">

                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Payment Amount</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <select class="btn btn-default" name="payment_method">
                                        <option value="cash" selected="selected"> Cash</option>
                                        <option value="bank"> Bank</option>
                                    </select>
                                </div><!-- btn-group -->
                                <input onkeypress="return numbersOnly(event)" require type="number" name="employee_payment" class="form-control">
                                <span class="input-group-addon">Taka</span>
                            </div>
                        </div>
                    </div>

                    <div id="bank_info" style="display: none;" class="col-md-12 pull-right">
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="damageRate">Bank Account</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="account_no" id="status">
                                    <option></option>
                                    <?php
                                    $i = '0';
                                    foreach ($obj->view_all("bank_registration") as $value) {
                                        $i++; ?>
                                        <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>
                                            - <?php echo isset($value['bank_name']) ? $value['bank_name'] : NULL; ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="withdraw_by">Withdraw By</label>
                            <div class="col-sm-8">
                                <input type="text" name="withdraw_by" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Punishment Amount</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input onkeypress="return numbersOnly(event)" type="number" name="employee_punishment" value="0" class="form-control">
                                <span class="input-group-addon">Taka</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-8">
                            <textarea name="description" rows="7" class="form-control"></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="employee_id" value="">

                </div>
                <div class="modal-footer text-center">

                    <button type="submit" name="submit_employee_payment" class="btn btn-primary">Employee Payment
                    </button>
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

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    $(document).ready(function () {

        $('table#datatable').on('click', 'td.action-btn button.edit', function () {

            var employeeId = $(this).data('id');
            var employeeName = $(this).data('employee');

            $('div#employee_payment h4.modal-title span#person_name').html(employeeName);
            $('div#employee_payment form#employee div.modal-body input[name="employee_id"]').val(employeeId);

        });

        $('#employee').on('hidden.bs.modal', function (e) {

            $('form#employee').trigger("reset");
        })

    });

    $('select[name="payment_method"').on('change', function () { // banking section will show when click bank

        if (this.value == 'bank') {
            $('#bank_info select[name="account_no"]').attr('required', 'required');
            $('#bank_info input[name="withdraw_by"]').attr('required', 'required');
            $('#bank_info').show();
        } else {
            $('#bank_info select[name="account_no"]').removeAttr('required');
            $('#bank_info input[name="withdraw_by"]').removeAttr('required');
            $('#bank_info').hide();
        }
    });
</script>