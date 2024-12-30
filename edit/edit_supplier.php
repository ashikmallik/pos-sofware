<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$token = isset($_GET['token']) ? $_GET['token'] : NULL;

$cusInfo = $obj->details_by_cond("tbl_supplier", "id='$token'");


if (isset($_POST['update'])) {
    extract($_POST);

    $form_data_for_update = array(
        'supplier_name' => str_replace("'",'',$name),
        'supplier_mobile_no' => isset($mobile)?str_replace("'",'',$mobile): null,
        'supplier_address' => str_replace("'", "", $address),
        'supplier_email' => str_replace("'",'',$email),
        'target' => str_replace("'",'',$target),
        'comission' => str_replace("'",'',$comission),
        'supplier_company' => str_replace("'",'',$company),
        'update_by' => $userid
    );
    $supplier_update = $obj->Update_data("tbl_supplier", $form_data_for_update, "where id='$token'");

    if ($supplier_update) {
        ?>
        <script>
            window.location = "?q=edit_supplier&token=<?php echo $token; ?>";
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
                    <label>Supplier's Name</label>
                    <input type="text" name="name" value="<?php echo isset($cusInfo['supplier_name']) ?
                        $cusInfo['supplier_name']:NULL;
                    ?>" class="form-control" placeholder="Edit Supplier's Name"
                           required="required">
                </div>

                <div class="form-group">
                    <label>Supplier's Company Name</label>
                    <input type="text" value="<?php echo isset($cusInfo['supplier_company']) ? $cusInfo['supplier_company']:NULL; ?>"
                           name="company" class="form-control" id="ResponsiveTitle"
                           placeholder="Edit Person' Company" required="required">
                </div>

                <div class="form-group">
                    <label>Mobile No</label>
                    <input type="text" value="<?php echo isset($cusInfo['supplier_mobile_no']) ? $cusInfo['supplier_mobile_no']:NULL; ?>" name="mobile" onkeypress="return numbersOnly(event)" class="form-control"
                           id="ResponsiveTitle"
                           placeholder="Edit Person's Mobile No">
                </div>
                <div class="form-group">
                    <label>Terget</label>
                    <input type="text" value="<?php echo isset($cusInfo['target']) ? $cusInfo['target']:NULL; ?>" name="target" class="form-control"
                           id="ResponsiveTitle"
                           placeholder="Edit terget">
                </div>
                <div class="form-group">
                    <label>Comission</label>
                    <input type="text" value="<?php echo isset($cusInfo['comission']) ? $cusInfo['comission']:NULL; ?>" name="comission" class="form-control"
                           id="ResponsiveTitle"
                           placeholder="Edit comission">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email"  value="<?php echo isset($cusInfo['supplier_email']) ? $cusInfo['supplier_email']:NULL; ?>"
                           name="email" class="form-control" id="ResponsiveTitle"
                           placeholder="Edit Email Address">
                </div>
                <div class="form-group">
                    <label>Supplier Address</label>
                    <textarea class="form-control" name="address" id="ResponsiveDetelis" rows="5"><?php echo isset($cusInfo['supplier_address']) ? $cusInfo['supplier_address']:NULL; ?></textarea>
                </div>
            </div>
        </div>
        <!--            row-->
        <div class="row" style="text-align: center; padding: 5px 0px 15px 25px; font-size: 12px;">
            <button type="submit" class="btn btn-success text-center" name="update">Update Supplier</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#datepicker').datepicker();
    })
</script>