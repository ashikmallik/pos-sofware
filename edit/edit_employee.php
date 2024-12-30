<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$token = isset($_GET['token']) ? $_GET['token'] : NULL;

$cusInfo = $obj->details_by_cond("tbl_employee", "id='$token'");

if (isset($_POST['update'])) {
    extract($_POST);
    $joining_update_date = date('Y-m-d', strtotime(str_replace('/','-',$joining_date)));
    $form_data_for_update = array(
        'employee_name' => str_replace("'",'',$employee_name),
        'employee_mobile_no' => isset($employee_mobile_no)?str_replace("'",'',$employee_mobile_no): null,
        'employee_address' => str_replace("'", "", $employee_address),
        'employee_email' => str_replace("'",'',$email),
        'employee_national_id' => str_replace("'",'',$employee_national_id),
        'designation' => str_replace("'",'',$designation),
        'joining_date' => str_replace("'",'',$joining_update_date),
        'update_by' => $userid
    );
    $customer_update = $obj->Update_data("tbl_employee", $form_data_for_update, "where id='$token'");

    if ($customer_update) {
        ?>
        <script>
            window.location = "?q=edit_employee&token=<?php echo $token; ?>";
        </script>
        <?php
    } else {
        $notification = 'Update Failed';
    }
}
?>

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

<?php if (isset($notification)) {
    ?>
    <div class="col-md-8 col-md-offset-2">
        <div class="bs-example bs-example-standalone" data-example-id=dismissible-alert-js>
            <div class="alert alert-warning alert-dismissible fade in" role=alert>
                <button type=button class=close data-dismiss=alert aria-label=Close><span aria-hidden=true>&times;</span>
                </button>
                <strong><?php echo $notification; ?></strong>.
            </div>
        </div>
    </div>
    <?php
}
?>
<span style="color: red; font-size: 20px;text-align:left;"></span>
<div class="row" style="padding:10px; font-size: 12px;">
    <form role="form" enctype="multipart/form-data" method="post">
        <div class="row" style="padding:10px; font-size: 12px;">
            <div class="col-md-6 col-md-offset-3 bg-teal-700 text-center" style="margin-bottom:5px;">
                <h4>Welcome to Edit page of <?php ?></h4>
            </div>
            <div class="col-md-6 col-md-offset-3">
                <div class="form-group">
                    <label>Customer's Name</label>
                    <input type="text" name="employee_name" value="<?php echo isset($cusInfo['employee_name']) ?
                    $cusInfo['employee_name']:NULL;
                    ?>" class="form-control" placeholder="Edit employee Name"
                           required="required">
                </div>
                
                <div class="form-group">
                    <label>Mobile No</label>
                    <input type="text" value="<?php echo isset($cusInfo['employee_mobile_no']) ? $cusInfo['employee_mobile_no']:NULL; ?>" name="employee_mobile_no" onkeypress="return numbersOnly(event)" class="form-control"
                           id="ResponsiveTitle"
                           placeholder="Edit Employee Mobile No">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email"  value="<?php echo isset($cusInfo['employee_email']) ? $cusInfo['employee_email']:NULL; ?>"
                           name="email" class="form-control" id="ResponsiveTitle"
                           placeholder="Edit Email Address">
                </div>
                
                <div class="form-group">
                    <label>Employee National ID</label>
                    <textarea class="form-control" name="employee_national_id" id="ResponsiveDetelis" rows="1"><?php echo isset($cusInfo['employee_national_id']) ? $cusInfo['employee_national_id']:NULL; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Employee Designation</label>
                    <textarea class="form-control" name="designation" id="ResponsiveDetelis" rows="1"><?php echo isset($cusInfo['designation']) ? $cusInfo['designation']:NULL; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Employee's Joining Date</label>
                    <input id="datepicker" type="text" name="joining_date" value="<?php echo isset($cusInfo['joining_date']) ? date('d/m/Y',strtotime($cusInfo['joining_date'])) :NULL; ?>" class="form-control" placeholder="dd/mm/yyyy" required>
                </div>
                <div class="form-group">
                    <label>Employee Address</label>
                    <textarea class="form-control" name="employee_address" id="ResponsiveDetelis" rows="3"><?php echo isset($cusInfo['employee_address']) ? $cusInfo['employee_address']:NULL; ?></textarea>
                </div>
            </div>
        </div>
        <!--            row-->
        <div class="row" style="text-align: center; padding: 5px 0px 15px 25px; font-size: 12px;">
            <button type="submit" class="btn btn-success text-center" name="update">Update Employee</button>
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