<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$token = isset($_GET['token']) ? $_GET['token'] : NULL;

$cusInfo = $obj->details_by_cond("tbl_customer", "id='$token'");

 $cusid=$cusInfo['cus_id'];
$cusduead = $obj->details_by_cond("tbl_account", "cus_or_sup_id='$cusid' AND (acc_type='7' OR acc_type='8') ");

if (isset($_POST['update'])) {
    extract($_POST);

    $form_data_for_update = array(
        'cus_name' => str_replace("'",'',$name),
        'cus_mobile_no' => isset($mobile)?str_replace("'",'',$mobile): null,
        'cus_address' => str_replace("'", "", $address),
        'cus_email' => str_replace("'",'',$email),
        'target' => str_replace("'",'',$target),
        'comission' => str_replace("'",'',$comission),
        'type' => $type,
        'cus_company' => str_replace("'",'',$company),
        'update_by' => $userid
    );
    $customer_update = $obj->Update_data("tbl_customer", $form_data_for_update, "where id='$token'");
   

    $form_data_for_update_account = array(
            'acc_amount' => $opening_balance,
            'acc_type' => $acc_head
    );
 
    $customer_update_account = $obj->Update_data("tbl_account", $form_data_for_update_account, "where cus_or_sup_id='$cusid' AND acc_type='$acc_type'");


    if ($customer_update) {
        ?>
        <script>
            window.location = "?q=edit_customer&token=<?php echo $token; ?>";
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
                    <input type="text" name="name" value="<?php echo isset($cusInfo['cus_name']) ?
                        $cusInfo['cus_name']:NULL;
                    ?>" class="form-control" placeholder="Edit Customer's Name"
                           required="required">
                </div>
                <div class="form-group">
                    <label>Type</label>
                            <select class="form-control" name="type" >
                                <option value=""> Select</option>                           
                                <option value="1" <?php if($cusInfo['type'] == 1 ) echo'selected' ?>> Retailer</option>
                                <option value="2" <?php if($cusInfo['type'] == 2 ) echo'selected' ?>> Workshop</option>
                                <option value="3" <?php if($cusInfo['type'] == 3 ) echo'selected' ?>> Houseowner</option>
                                <option value="5" <?php if($cusInfo['type'] == 5 ) echo'selected' ?>> Feed</option>
                                <option value="6" <?php if($cusInfo['type'] == 6 ) echo'selected' ?>> Block Money</option>
                                <option value="7" <?php if($cusInfo['type'] == 7 ) echo'selected' ?>> Sanatary</option>
                            </select>
                </div>

                <div class="form-group">
                    <label>Customer's Company Name</label>
                    <input type="text" value="<?php echo isset($cusInfo['cus_company']) ? $cusInfo['cus_company']:NULL; ?>"
                           name="company" class="form-control" id="ResponsiveTitle"
                           placeholder="Edit Person' Company" required="required">
                </div>

                <div class="form-group">
                    <label>Mobile No</label>
                    <input type="text" value="<?php echo isset($cusInfo['cus_mobile_no']) ? $cusInfo['cus_mobile_no']:NULL; ?>" name="mobile" onkeypress="return numbersOnly(event)" class="form-control"
                           id="ResponsiveTitle"
                           placeholder="Edit Person's Mobile No">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email"  value="<?php echo isset($cusInfo['cus_email']) ? $cusInfo['cus_email']:NULL; ?>"
                           name="email" class="form-control" id="ResponsiveTitle"
                           placeholder="Edit Email Address">
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
                    <label>Customer Address</label>
                    <textarea class="form-control" name="address" id="ResponsiveDetelis" rows="5"><?php echo isset($cusInfo['cus_address']) ? $cusInfo['cus_address']:NULL; ?></textarea>
                </div>
                
                 <div class="form-group">
                   <label>Opening Balance</label>
                     <input style="height:32px;width:320px;"type="text" class="form-control" value="<?php echo isset($cusduead['acc_amount']) ? $cusduead['acc_amount']:NULL; ?>" name="opening_balance">
                         <input type="hidden" class="form-control" value="<?php echo isset($cusduead['acc_type']) ? $cusduead['acc_type']:NULL; ?>" name="acc_type">
                </div>
                <div class="form-group">
                       <label>Due/Advance</label>
                            <select class="" name="acc_head">
                                <option value="<?php echo isset($cusduead['acc_type']) ? $cusduead['acc_type']:NULL;?>">
                                    <?php 
                                    if($cusduead['acc_type']=='7') {echo 'Advance';}
                                    else { echo 'due';}?>
                                    </option>
                                  <option value="7">Advance</option>
                                <option value="8"> Due</option>
                            </select>
                        </div>
            </div>
        </div>
        
      
        <!--            row-->
        <div class="row" style="text-align: center; padding: 5px 0px 15px 25px; font-size: 12px;">
            <button type="submit" class="btn btn-success text-center" name="update">Update Customer</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#datepicker').datepicker();
    })
</script>