<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$intoken = isset($_GET['intoken']) ? $_GET['intoken'] : NULL;
$actoken = isset($_GET['actoken']) ? $_GET['actoken'] : NULL;


if (!empty($intoken)) {
    $form_data = array('cus_status' => '0', 'update_by' => $userid);

    $obj->Update_data("tbl_customer", $form_data, "where id='$intoken'");
}

if (!empty($actoken)) {
    $form_data = array('cus_status' => '1', 'update_by' => $userid);

    $obj->Update_data("tbl_customer", $form_data, "where id='$actoken'");
}


// ========== Delete Function Start =================
$dltoken = isset($_GET['dltoken']) ? $_GET['dltoken'] : NULL;
if (!empty($dltoken)) {

    $dele = $obj->Delete_data("tbl_customer", "id='$dltoken'");

    if (!$dele) {
        $notification = 'Delete Successfull';
    } else {
        $notification = 'Delete Failed';
    }
}

$allAgentData = $obj->view_all("tbl_customer");

// ==========  Function End =================

?>

<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="col-md-12"
     style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
        <b>View Customer Information</b>
    </div>
    <div class="col-md-6" style="">
        <?php if ($ty == 'SA') { ?>
            <a class="btn btn-primary btn-sm pull-right" href="?q=add_customer">ADD NEW <span class="glyphicon
        glyphicon-plus"></span></a>
            <a href="?q=print_customer_list" target="_blank" class="btn btn-primary btn-sm pull-right">Print Customer List</a>
        <?php } ?>
    </div>
</div>

<!-- all user show -->
<div id="div_all" class="row" style="padding:10px;font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-slate-800">
                <tr>
                    <th class="col-md-1">Customer ID</th>
                    <th class="col-md-1">Customer Name</th>
                    <th class="col-md-1">Customer Company</th>
                    <th class="col-md-1">Mobile No</th>
                    <th class="col-md-1">Email</th>
                    <th class="col-md-3">Address</th>
                    <th class="col-md-1">Create Date</th>
                    <th class="col-md-1">Action</th>
                </tr>
                </thead>
                <?php
                $i = '0';
                foreach ($allAgentData as $value) {
                    $i++;
                    ?>
                    <tr>
                        <td class="text-center">
                            <a class="btn btn-xs bg-grey-600 btn-default" href="?q=customer_ledger&customerId=<?php echo isset
                            ($value['cus_id']) ? $value['cus_id']:NULL;?>"><?php echo isset($value['cus_id']) ? $value['cus_id']:NULL;?></a>
                        </td>
                        <td class="text-center"><?php echo isset($value['cus_name']) ? $value['cus_name'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['cus_company']) ? $value['cus_company'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['cus_mobile_no']) ? $value['cus_mobile_no'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['cus_email']) ? $value['cus_email'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['cus_address']) ? $value['cus_address'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['entry_date']) ? date('d-m-y', strtotime($value['entry_date'])) : NULL; ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a class="btn btn-xs bg-teal btn-primary padding_2_10_px" href="?q=edit_customer&token=<?php echo isset ($value['id']) ? $value['id'] : NULL ?>">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </a>
                                <a href="?q=view_customer&dltoken=<?php echo isset($value['id']) ? $value['id'] : NULL;?>" onclick="return confirm('Are you sure you want to delete this Customer?');" class="btn btn-xs btn-danger padding_2_10_px">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>