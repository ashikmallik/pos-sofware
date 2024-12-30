<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$intoken = isset($_GET['intoken']) ? $_GET['intoken'] : NULL;
$actoken = isset($_GET['actoken']) ? $_GET['actoken'] : NULL;


if (!empty($intoken)) {
    $form_data = array('supplier_status' => '0', 'update_by' => $userid);

    $obj->Update_data("tbl_supplier", $form_data, "where id='$intoken'");
}

if (!empty($actoken)) {
    $form_data = array('supplier_status' => '1', 'update_by' => $userid);

    $obj->Update_data("tbl_supplier", $form_data, "where id='$actoken'");
}

// ========== Delete Function Start =================
$dltoken = isset($_GET['dltoken']) ? $_GET['dltoken'] : NULL;
if (!empty($dltoken)) {

    $dele = $obj->Delete_data("tbl_supplier", "id='$dltoken'");

    if (!$dele) {
        $notification = 'Delete Successfull';
    } else {

    }
}

// view all/ Active / Inactie and zone wise search
if (isset($_POST['zone'])) {
    $zone = $_POST['zone'];
    $allAgentData = $obj->view_all_by_cond("tbl_supplier", "zone='$zone'");
    $_SESSION['printQuery'] = $zone;

} elseif (isset($_GET['key'])) {
    $key = $_GET['key'];
    if ($key == "all") {
        $allAgentData = $obj->view_all("tbl_supplier");

    } elseif ($key == "active") {
        $allAgentData = $obj->view_all_by_cond("tbl_supplier", "supplier_status='1'");

    } elseif ($key == "inactive") {
        $allAgentData = $obj->view_all_by_cond("tbl_supplier", "supplier_status='0'");

    } else {
        $allAgentData = $obj->view_all("tbl_supplier");
    }
} else {

    $allAgentData = $obj->view_all("tbl_supplier");
}

if (isset($_POST['zoneUpdateSubmit'])) {

    $form_data_for_zone = array(
        'zone' => $_POST['zoneName'],
    );

    $update_zone = $obj->Update_data("tbl_supplier", $form_data_for_zone, "`tbl_supplier`.`id`='" . $_POST['zoneId'] . "'");

    if ($update_zone) {
        $notification = 'Zone Updated successfully';
        ?>
        <script>
            window.location = "?q=view_agent";
        </script>
        <?php
    } else {
        $notification = 'Zone Updated Failed';
    }
}

// ==========  Function End =================

?>

<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="col-md-12"
     style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
        <b>View Supplier Information</b>
    </div>
    <div class="col-md-6" style="">
        <?php if ($ty == 'SA') { ?>
            <a class="btn btn-primary btn-sm pull-right" href="?q=add_supplier">ADD NEW <span class="glyphicon
        glyphicon-plus"></span></a>
            <a href="?q=print_supplier_list" target="_blank" class="btn btn-primary btn-sm pull-right">Print Supplier List</a>
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
                    <th class="col-md-1">Supplier ID</th>
                    <th class="col-md-1">Supplier Name</th>
                    <th class="col-md-1">Supplier Company</th>
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
                    $i++; ?>
                    <tr>
                        <td class="text-center">
                            <a class="btn btn-xs bg-grey-600 btn-default" href="?q=supplier_ledger&supplierId=<?php echo isset($value['supplier_id']) ? $value['supplier_id'] : NULL; ?>"><?php echo isset($value['supplier_id']) ? $value['supplier_id'] : NULL; ?></a>
                        </td>
                        <td class=""><?php echo isset($value['supplier_name']) ? $value['supplier_name'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['supplier_company']) ? $value['supplier_company'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['supplier_mobile_no']) ? $value['supplier_mobile_no'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['supplier_email']) ? $value['supplier_email'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['supplier_address']) ? $value['supplier_address'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['entry_date']) ? date('d-m-y', strtotime($value['entry_date'])) : NULL; ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a class="btn btn-xs bg-teal btn-primary padding_2_10_px"
                                   href="?q=edit_supplier&token=<?php echo isset ($value['id']) ? $value['id'] : NULL ?>">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </a>
                                <a href="?q=view_supplier&dltoken=<?php echo isset($value['id']) ? $value['id'] : NULL;?>" onclick="return confirm('Are you sure you want to delete this Customer?');"  class="btn btn-xs btn-danger padding_2_10_px">
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
