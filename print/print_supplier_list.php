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
        $notification = 'Delete Failed';
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

<div class="row" style="margin:15px; 0">
    <button type="submit" class="btn btn-success bg-grey-800 btn-block"
            onclick="printDiv('supplier_print')">Click to Print Below Statement
    </button>
</div>


<div class="row" id="supplier_print">
    <div class="col-md-12 text-center">
        <b>View Supplier Information</b>
    </div>

    <!-- all user show -->
    <div id="div_all" class="row" style="padding:10px;font-size: 12px;">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th class="col-md-1">Supplier ID</th>
                        <th class="col-md-1">Supplier Name</th>
                        <th class="col-md-1">Supplier Company</th>
                        <th class="col-md-1">Mobile No</th>
                        <th class="col-md-1">Email</th>
                        <th class="col-md-3">Address</th>
                        <th class="col-md-1">Create Date</th>
                    </tr>
                    </thead>
                    <?php
                    $i = '0';
                    foreach ($allAgentData as $value) {
                        $i++;
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php echo isset($value['supplier_id']) ? $value['supplier_id'] : NULL; ?>
                            </td>
                            <td class="text-center"><?php echo isset($value['supplier_name']) ? $value['supplier_name'] : NULL; ?></td>
                            <td class="text-center"><?php echo isset($value['supplier_company']) ? $value['supplier_company'] : NULL; ?></td>
                            <td class="text-center"><?php echo isset($value['supplier_mobile_no']) ? $value['supplier_mobile_no'] : NULL; ?></td>
                            <td class="text-center"><?php echo isset($value['supplier_email']) ? $value['supplier_email'] : NULL; ?></td>
                            <td class="text-center"><?php echo isset($value['supplier_address']) ? $value['supplier_address'] : NULL; ?></td>
                            <td class="text-center"><?php echo isset($value['entry_date']) ? date('d-m-y', strtotime($value['entry_date'])) : NULL; ?></td>

                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<script>
    $(document).ready(function () {
        $("tbody tr").dblclick(function () {
            _id = this.id;
            if (_id != "0")
                window.location = "?q=view_customer_payment_individual&token2=" + _id;
        });
    });
</script>
<!-- here end table -->