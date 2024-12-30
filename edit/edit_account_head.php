<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$AcHeadId = isset($_GET['editAcHeadId']) ? $_GET['editAcHeadId'] : NULL;

$AcHeadDetails = $obj->details_by_cond("tbl_ac_head_other_income","acc_id = '$AcHeadId'");

if (isset ($_POST['updateAccount'])) {
    extract($_POST);

    $form_data = array(
        'acc_name' => $name,
        'acc_desc' => str_replace("'", "", $details),
        'update_by' => $userid
    );
    $service_update = $obj->Update_data("tbl_ac_head_other_income", $form_data, "acc_id = '$AcHeadId'");

    if ($service_update) {
        ?>
        <script>
            window.location = "?q=view_account_head";
        </script>
        <?php
    } else {
        $notification = 'Sorry ! Update Failed';
    }
}

?>


<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>

<div class="col-md-8 col-md-offset-2 bg-teal-600 text-center" style="margin-bottom:5px;">
    <h4>Welcome to Edit Expense Accounts Head</h4>
</div>

<div class="row" style="padding:10px; font-size: 12px;">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-7 col-md-offset-2" style="padding:10px; font-size: 12px;">
            <div class="form-group">
                <label class="control-label col-sm-4" for="name">Account Head Name</label>
                <div class="col-sm-8">
                    <input value="<?php echo $AcHeadDetails['acc_name']? $AcHeadDetails['acc_name']:NULL; ?>" type="text" name="name" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="details">Account Head Details</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="details" rows="4"><?php echo $AcHeadDetails['acc_desc']? $AcHeadDetails['acc_desc']:NULL; ?></textarea>
                </div>
            </div>
        </div>

        <div class="row margin_top_10px ">
            <div class="col-md-2 col-md-offset-5">
                <div class="text-center">
                    <button type="submit" class="btn btn-block btn-primary" name="updateAccount">Update Accounts</button>
                </div>
            </div>
        </div>
    </form>
</div>