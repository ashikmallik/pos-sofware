<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$employeeSalaryReceiveType = 1;
$employeeSalaryDueType = 0;

if (isset($_POST['employee_salary'])) {

    $form_tbl_employee_transaction = array(
        'employee_id' => $_POST['employee_id'],
        'salary_amount' => $_POST['salary_amount'],
        'conveyance' => $_POST['conveyance_amount'],
        'received_amount' => 0,
        'received_due' => $employeeSalaryDueType,
        'punishment' => 0,
        'accounts_id' => 0,
        'created_at' => date('Y-m-d'),
    );

    if ($obj->insert_by_condition("tbl_employee_transaction", $form_tbl_employee_transaction, " ")) {
        $obj->notificationStore('Employee Salary Assigned Successfully', 'success');
       echo '<script>window.location.href=window.location.href;</script>';
    } else {
        $obj->notificationStore('Employee Salary Assigned Failed');
        $notification = '<div class="alert alert-danger">Insert Failed</div>';
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
        <h4>Welcome to Assign Salary To Employee</h4>
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
                <label class="control-label col-sm-4" for="customer_id">Employee :</label>
                <div class="col-sm-8">
                    <select class="form-control" required="required" name="employee_id" id="status">
                        <option></option>
                        <?php
                        $i = '0';
                        foreach ($obj->view_all("tbl_employee") as $employee) {
                            $i++; ?>
                            <option value="<?php echo isset($employee['id']) ? $employee['id'] : NULL; ?>"><?php echo isset($employee['employee_name']) ? $employee['employee_name'] : NULL; ?>
                                - <?php echo isset($employee['employee_id']) ? $employee['employee_id'] : NULL; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-4">Salary Amount (tk) :</label>
                <div class="col-sm-8">
                    <input type="text" style="height:32px" required onkeypress="return numbersOnly(event)" name="salary_amount" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-4">Conveyance Amount (tk) :</label>
                <div class="col-sm-8">
                    <input type="text" style="height:32px" onkeypress="return numbersOnly(event)" name="conveyance_amount" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="text-center">
                <button type="submit" class="btn btn-sm btn-success" name="employee_salary">Assign Employee Salary</button>
            </div>
        </div>
    </form>
</div>
<hr>

<script type="text/javascript">
    $(document).ready(function () {
        $('select[name="employee_id"]').selectpicker();

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
