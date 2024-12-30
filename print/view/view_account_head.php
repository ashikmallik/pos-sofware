<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$total_Acc_head = $obj -> Total_Count('tbl_ac_head_other_income', "ac_head_or_other_income = 1");

$all_acc_head_data = $obj -> view_all_by_cond('tbl_ac_head_other_income', "ac_head_or_other_income = 1");

if (isset ($_POST['addAccount'])) {
    extract($_POST);

    $form_data = array(
        'acc_name' => $name,
        'acc_desc' => str_replace("'", "", $details),
        'acc_status' => 1,
        'ac_head_or_other_income' => 1,
        'entry_by' => $userid,
        'entry_date' => $date,
        'update_by' => $userid
    );

    $service_add = $obj->insert_by_condition("tbl_ac_head_other_income", $form_data, " ");

    if ($service_add) {
        ?>
        <script>
            window.location = "?q=view_account_head";
        </script>
        <?php
    } else {
        $notification = 'Sorry ! Insert Failed';
    }
}

if(isset($_GET['deleteAcHeadId']) && !empty($_GET['deleteAcHeadId'])){
    $deleteId = $_GET['deleteAcHeadId'];
    $obj -> Delete_data('tbl_ac_head_other_income',"acc_id = '$deleteId'");
    $notification = 'Accounts Head Deleted Successfuly';

}
?>


<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>

<div class="col-md-8 col-md-offset-2 bg-grey-800 text-center" style="margin-bottom:5px;">
    <h4>Welcome to Add Expense Accounts Head</h4>
</div>

<div class="row" style="padding:10px; font-size: 12px;">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-7 col-md-offset-2" style="padding:10px; font-size: 12px;">
            <div class="form-group">
                <label class="control-label col-sm-4" for="name">Account Head Name</label>
                <div class="col-sm-8">
                    <input type="text" name="name" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="details">Account Head Details</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="details" rows="4"></textarea>
                </div>
            </div>
        </div>

        <div class="row margin_top_10px ">
            <div class="col-md-2 col-md-offset-5">
                <div class="text-center">
                    <button type="submit" class="btn btn-block bg-teal btn-success" name="addAccount">Add Accounts</button>
                </div>
            </div>
        </div>
    </form>
</div>
<hr>

<?php if($total_Acc_head > 0) { ?>

<div class="col-md-8 col-md-offset-2 bg-grey-800 text-center" style="margin-bottom:5px;">
    <h4>All Expense Accounts Head</h4>
</div>
<div class="row" style="padding:10px; font-size: 12px;">
    <div class="col-md-8 col-md-offset-2">
        <table class="table table-bordered table-hover table-striped" id="example">
            <thead>
            <tr>
                <th>#</th>
                <th>Account Name</th>
                <th class="text-center">Account Details</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <?php
            $i = '0';
            foreach ($all_acc_head_data as $acHead) {
                $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo isset($acHead['acc_name']) ? $acHead['acc_name'] : NULL; ?></td>
                    <td><?php echo isset($acHead['acc_desc']) ? $acHead['acc_desc'] : NULL; ?></td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a type="button" href="?q=edit_account_head&editAcHeadId=<?php echo $acHead['acc_id']?>" class="btn btn-primary btn-xs padding_2_5_px">Edit</a>
                            <a type="button" onclick="return confirm('Are you sure you want to delete this Account Head item?');" href="?q=view_account_head&deleteAcHeadId=<?php echo $acHead['acc_id']?>" class="btn btn-danger btn-xs padding_2_5_px">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>
<?php } ?>