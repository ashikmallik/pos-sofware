<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$data = $obj->details_by_cond("tbl_employee", "id!=0 ORDER BY id DESC");
if (($data['id'] + 1) < 10) {
    $STD = "EMPL0000"; // EMPL for employee
} else if (($data['id'] + 1) < 100) {
    $STD = "EMPL000";
} else if (($data['id'] + 1) < 1000) {
    $STD = "EMPL00";
} else if (($data['id'] + 1) < 10000) {
    $STD = "EMPL0";
} else {
    $STD = "EMPL";
}
$STD .= $data['id'] + 1;

//===================Add Function===================

if (isset ($_POST['submit'])) {
    extract($_POST);
    $form_data_tbl_employee = array(
        'employee_name' => str_replace("'",'',$name),
        'employee_national_id' => str_replace("'",'',$employee_national_id),
        'designation' => str_replace("'",'',$designation),
        'joining_date' => date_format(date_create($joining_date), "Y-m-d"),
        'employee_mobile_no' => isset($mobile)?str_replace("'",'',$mobile): null,
        'employee_address' => str_replace("'", "", $address),
        'employee_email' => str_replace("'",'',$email),
        'employee_id' => $STD,
        'employee_status' => '1',
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );

    if($obj->insert_by_condition("tbl_employee", $form_data_tbl_employee, " ")){

        $obj->notificationStore('Employee Stored Successfully', 'success');
        echo '<script>window.location.href = window.location.href;</script>';
    }else{
        $obj->notificationStore('Employee Stored Failed ');
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

<div class="row">
    <div class="col-md-12 padding_5_px">
        <?php $obj->notificationShowRedirect(); ?>
    </div>
</div>
<div class="row panel">
    <div class=" panel-body col-md-12 bg-teal-800 text-center" style="margin-bottom:5px;">
        <h4>Automated Employee Id : <?php echo $STD; ?></h4 >
    </div>
</div>
<div class="row" style="font-size: 12px;">
    <form role="form" method="post">

        <div class="col-md-6 col-md-offset-3">
            <div class="form-group">
                <label>Employee's Name</label>
                <input type="text" name="name" class="form-control" placeholder="Provide Employee Name" required="required">
            </div>
            <div class="form-group">
                <label>Employee's Mobile No</label>
                <input type="text" name="mobile" onkeypress="return numbersOnly(event)" class="form-control" placeholder="Provide Employee Mobile No">
            </div>
            <div class="form-group">
                <label>Employee's Email</label>
                <input type="email" name="email" class="form-control"  placeholder="Provide Email Address">
            </div>
            <div class="form-group">
                <label>Employee's National Id</label>
                <input type="text" name="employee_national_id" class="form-control"  placeholder="Provide National Id">
            </div>
            <div class="form-group">
                <label>Employee's Designation</label>
                <input type="text" name="designation" class="form-control"  placeholder="Provide Designation">
            </div>
            <div class="form-group">
                <label>Employee's Joining Date</label>
                <input id="datepicker" type="text" name="joining_date" class="form-control" placeholder="dd/mm/yyyy" required>
            </div>
            <div class="form-group">
                <label>Employee's  Address</label>
                <textarea class="form-control" name="address" id="ResponsiveDetelis" rows="5"></textarea>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-success text-center" name="submit">Add New Employee</button>
            </div>

        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            toggleActive: true,
        });
    })
</script>