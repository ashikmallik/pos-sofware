<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$total_other_income = $obj -> Total_Count('tbl_ac_head_other_income', "ac_head_or_other_income = 0");

$all_other_income_data = $obj -> view_all_by_cond('tbl_ac_head_other_income', "ac_head_or_other_income = 0");

if (isset ($_POST['addOtherIncome'])) {
    extract($_POST);

    $form_data = array(
        'acc_name' => $name,
        'acc_desc' => str_replace("'", "", $details),
        'acc_status' => 1,
        'ac_head_or_other_income' => 0,
        'entry_by' => $userid,
        'entry_date' => $date,
        'update_by' => $userid
    );

    $service_add = $obj->insert_by_condition("tbl_ac_head_other_income", $form_data, " ");

    if ($service_add) {
        ?>
        <script>
            window.location = "?q=view_other_income";
        </script>
        <?php
    } else {
        $notification = 'Sorry ! Insert Failed';
    }
}

if(isset($_GET['deleteotherIncomeId']) && !empty($_GET['deleteotherIncomeId'])){
    $deleteId = $_GET['deleteotherIncomeId'];
    $obj -> Delete_data('tbl_ac_head_other_income',"acc_id = '$deleteId'");
    ?>
    <script>
        window.location = "?q=view_other_income";
    </script>
    <?php
}
?>


    <div class="col-md-12"
         style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
        <b><?php echo isset($notification) ? $notification : NULL; ?></b>
    </div>

    <div class="col-md-8 col-md-offset-2 bg-slate-800 text-center" style="margin-bottom:5px;">
        <h4>Welcome to Add Other Income</h4>
    </div>

    <div class="row" style="padding:10px; font-size: 12px;">
        <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
            <div class="col-md-7 col-md-offset-2" style="padding:10px; font-size: 12px;">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="name">Other Income Name</label>
                    <div class="col-sm-8">
                        <input type="text" name="name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="details">Other Income Details</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" name="details" rows="4"></textarea>
                    </div>
                </div>
            </div>

            <div class="row margin_top_10px ">
                <div class="col-md-2 col-md-offset-5">
                    <div class="text-center">
                        <button type="submit" class="btn btn-block bg-teal btn-success" name="addOtherIncome">Add Other Income</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <hr>

<?php if($total_other_income > 0) { ?>

    <div class="col-md-8 col-md-offset-2 bg-grey-800 text-center" style="margin-bottom:5px;">
        <h4>All Other Income</h4>
    </div>
    <div class="row" style="padding:10px; font-size: 12px;">
        <div class="col-md-8 col-md-offset-2">
            <table class="table table-bordered table-hover table-striped" id="example">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Other Income Name</th>
                    <th class="text-center">Other Income Details</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <?php
                $i = '0';
                foreach ($all_other_income_data as $otherIncome) {
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo isset($otherIncome['acc_name']) ? $otherIncome['acc_name'] : NULL; ?></td>
                        <td><?php echo isset($otherIncome['acc_desc']) ? $otherIncome['acc_desc'] : NULL; ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <?php if ($otherIncome['acc_id'] !=1){?>
                                <a type="button" href="?q=edit_other_income&editOthIncomeId=<?php echo $otherIncome['acc_id']?>" class="btn btn-primary btn-xs padding_2_5_px">Edit</a>
                                <a type="button" onclick="return confirm('Are you sure you want to delete this Other Income item?');" href="?q=view_other_income&deleteotherIncomeId=<?php echo $otherIncome['acc_id']?>" class="btn btn-danger btn-xs padding_2_5_px">Delete</a>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
<?php  } ?>