<?php

date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$value = $obj->details_by_cond("sms", "status='1'");

if (isset ($_POST['submit'])) {

    extract($_POST);

    $form_data_sms = array(
        'smsbody' => $sms_body,
        'smshead' => $sms_head
    );


    if ($obj->Total_Count('sms', "status='1'") == 1) {

        $smsRow = $obj->Update_data("sms", $form_data_sms, " status='1' ");
    } else {

        $form_data_sms['status'] = 1;

        $smsRow = $obj->Reg_user_cond('sms', $form_data_sms, '');

    }

    if ($smsRow) {

        echo '<script> window.location="?q=due_sms"; </script>';

    } else {

        echo $notification = 'Insert Failed';
    }
}
?>

<!--===================end Function===================-->

<div class="row">
    <div class="col-md-12 bg-slate-800">
        <h4>Add Due SMS || Send SMS To All Due Client</h4>
    </div>
</div>

<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>


<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form role="form" enctype="multipart/form-data" method="post">

            <div class="form-group">
                <label>DUE SMS Header</label>
                <textarea class="form-control" onkeyup="countCharH(this)" name="sms_head" id="ResponsiveDetelis"
                          rows="2"><?php echo isset($value['smshead']) ? $value['smshead'] : NULL; ?></textarea><div class="float-left" style="margin-left: 523px;"><span id="charNumH"></span></div>
            </div>

            <div class="form-group">
                <label>DUE SMS Details</label>
                <textarea class="form-control" onkeyup="countCharB(this)" name="sms_body" id="ResponsiveDetelis"
                          rows="6"><?php echo isset($value['smsbody']) ? $value['smsbody'] : NULL; ?></textarea><div class="float-left" style="margin-left: 523px;"><span id="charNumB"></span></div>
            </div>

            <button type="submit" class="btn btn-success" name="submit">SAVE SMS</button>
        </form>

       
            <hr>
            <form role="form" action="index.php" method="get">
                <div class="row">

                    <div class="col-md-10 col-md-offset-1">

                        <input type="hidden" name="q" value="send_due_sms"/>
                        <div class="col-md-8">

                            <div class="form-group">

                                <select class="form-control" name="cid" required>
                                    <option value="4">All Due Client</option>
                                    <option value="1">Dealar Due list</option>
                                    <option value="2">Customer Due List </option>
                                    <option value="3">Retailer Due List</option>
                               

                                </select>

                            </div>
                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                                <button type="submit" class="btn btn-primary">SEND SMS </button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        
    </div>


</div>
<hr></hr>
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
