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
        <b>View Supplier Sales and Comission Report</b>
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
                    <th class="col-md-1">Terget</th>
                    <th class="col-md-1">Total Comission</th>
                    <th class="col-md-1">Total Achieve Sales</th>
                     <th class="col-md-2">Total Payment Recive</th>
                    <th class="col-md-1">Create Date</th>
                    
                </tr>
                </thead>
                <tbody>
                <?php
                $i = '0';
                $total_sales_comission2 = 0;
                $totalAchieve2 = 0 ;
                $totalPaymentRecive = 0;
                foreach ($allAgentData as $value) {
                    $id = $value['supplier_id'];
                    $total_sales_comission = $obj->get_sum_data('tbl_purchase','total_comission_earn',"supplier='$id'");
                    $totalAchieve = $obj->get_sum_data('tbl_purchase','total_price',"supplier='$id'");
                    $totalPaymentRecive = $obj->get_sum_data('tbl_purchase','payment_recieved',"supplier='$id'");
                    $total_sales_comission2 += $total_sales_comission;
                    $totalAchieve2 += $totalAchieve;
                    $totalPaymentRecive2 += $totalPaymentRecive;
                    $i++; ?>
                    <tr>
                        <td class="text-center">
                            <a class="btn btn-xs bg-grey-600 btn-default" href="?q=supplier_ledger&supplierId=<?php echo isset($value['supplier_id']) ? $value['supplier_id'] : NULL; ?>"><?php echo isset($value['supplier_id']) ? $value['supplier_id'] : NULL; ?></a>
                        </td>
                        <td class=""><?php echo isset($value['supplier_name']) ? $value['supplier_name'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['supplier_company']) ? $value['supplier_company'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['supplier_mobile_no']) ? $value['supplier_mobile_no'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['target']) ? ($value['target']) : NULL; ?></td>
                        <td class="text-center"><?php echo $total_sales_comission; ?></td>
                        <td class="text-center"><?php echo $totalAchieve; ?></td>
                        <td class="text-center"><?php echo $totalPaymentRecive; ?></td>
                        <td class="text-center"><?php echo isset($value['entry_date']) ? date('d-m-y', strtotime($value['entry_date'])) : NULL; ?></td>
                        
                        
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tfoot class="bg-success">
                    <tr>
                        <th colspan="6"></th>
                        <th>Total : <?php echo number_format($total_sales_comission2) ?></th>
                        <th>Total : <?php echo number_format($totalAchieve2) ?></th>
                        <th>Total : <?php echo number_format($totalPaymentRecive2) ?></th>
                        
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
            $("#datatable").dataTable().fnDestroy();
            $('#datatable').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                {
                    extend: 'print',
                    text: 'Print',
                    footer: true,
                
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8]
                    },
                    customize: function (win) {
                        $(win.document.body).css('font-size', '12px');
                        $(win.document.body).find('h1').addClass('text-center').css('font-size', '20px');
                        $(win.document.body).find('table').addClass('container').css('font-size', 'inherit');
                        $(win.document.body).find('table').removeClass('table-bordered');
                    }
                }
                ]
            } );
        } );
    
</script>
