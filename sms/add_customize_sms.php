<?php

date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$sms = $obj->details_by_cond("sms", "status='2'");

//===================Add Function===================

if (isset ($_POST['submit'])) {

    extract($_POST);

    $form_data_sms = array(
        'smsbody' => $sms_body,
    );


    if ($obj->Total_Count('sms', "status='2'") == 1) {

        $smsRow = $obj->Update_data("sms", $form_data_sms, " status='2' ");
    } else {

        $form_data_sms['status'] = 2;

        $smsRow = $obj->Reg_user_cond('sms', $form_data_sms, '');

    }

    if ($smsRow) {

        echo '<script> window.location="?q=add_customize_sms"; </script>';

    } else {

        echo $notification = 'Insert Failed';
    }
}
?>


<div class="row">
    <div class="col-md-12 bg-teal-800">
        <h4>Add Custome SMS <small>For Individual Customer</small></h4>
    </div>

</div>

<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>


<div class="row">
    <form role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-6 col-md-offset-3">

            <div class="form-group">
                <label>Custome SMS Details</label>
                <textarea class="form-control" onkeyup="countCharB(this)" name="sms_body" id="ResponsiveDetelis"
                          rows="6"><?php echo isset($sms['smsbody']) ? $sms['smsbody'] : NULL; ?></textarea><div class="float-left" style="margin-left: 523px;"><span id="charNumB"></span></div>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary" name="submit">SAVE SMS</button>
            </div>
        </div>


    </form>
</div>
<hr>
<script type="text/javascript">
     function countCharH(val) {
        var len = val.value.length;
        if (len >= 60) {
          val.value = val.value.substring(0, 60);
        } else {
          $('#charNumH').text(60 - len);
        }
      };
      function countCharB(val) {
        var len = val.value.length;
        if (len >= 160) {
          val.value = val.value.substring(0, 160);
        } else {
          $('#charNumB').text(160 - len);
        }
      };
</script>