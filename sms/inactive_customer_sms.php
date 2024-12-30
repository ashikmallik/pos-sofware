<?php

date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$value = $obj->details_by_cond("sms", "status='3'");

//===================Add Function===================

if (isset ($_POST['submit'])) {
    extract($_POST);

    $form_data = array(
        'smsbody' => $sms_body
    );

    $branch_add = $obj->Update_data("sms", $form_data, " status='3' ");

    if ($branch_add) {
        ?>
        <script>
            window.location = "?q=inactive_customer_sms";
        </script>
        <?php
    } else {
        echo $notification = 'Insert Failed';
    }
}
if (isset ($_POST['sms'])) {
    $smsClient = $obj->view_all_by_cond("tbl_agent", "ag_status='0'");

    foreach ($smsClient as $value) {

    $mobile = isset($value['ag_mobile_no']) ? $value['ag_mobile_no'] : NULL;
    $value1 = $obj->details_by_cond("sms", "status='3'");
    $smstext = isset($value1['smsbody']) ? $value1['smsbody'] : NULL;

// Company's name POST URL
    $postUrl = "http://api.bulksms.icombd.com/api/v3/sendsms/xml";
// XML-formatted data
//$mass="$sms_h $all_d $sms_b ";
//$tt="$ttt";

    $xmlString =
        "<SMS>
<authentification>
<username>" . $obj->getSettingValue('sms', 'user') . "</username>
<password>" . $obj->getSettingValue('sms', 'pass') . "</password>
</authentification>
<message>
<sender>".$obj->getSettingValue('sms', 'sender')."</sender>
<text>$smstext</text>
</message>
<recipients>
<gsm>88.$mobile</gsm>

</recipients>
</SMS>";// previously formatted XML data becomes value of "XML" POST variable
    $fields = "XML=" . urlencode($xmlString);
// in this example, POST request was made using PHP's CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $postUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
// response of the POST request
    $response = curl_exec($ch);
    curl_close($ch);
     }?>
      <script>
        var notify="SMS Sent Successfully";
        window.location = "?q=inactive_customer_sms&del="+notify;
    </script>
<?php
    }

/*$value = $obj->details_by_cond("sms", "status='3'");*/
?>

<!--===================end Function===================-->

<div class="row">
    <div class="col-md-12 bg-teal-800">
        <h4>Inactive SMS Details</h4>
    </div>
</div>


<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php if(isset ($_GET['del'])){ echo $_GET['del'] ;}echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="row" style="padding:10px; font-size: 12px;">
    <form role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-6 col-md-offset-3">
            <div class="form-group">
                <label>Inactive SMS Details</label>
                <textarea class="form-control" onkeyup="countChar(this)" name="sms_body" id="ResponsiveDetelis"
                          rows="6"><?php echo isset($value['smsbody']) ? $value['smsbody'] : NULL; ?></textarea> <div class="float-left" style="margin-left: 523px;"><span id="charNum"></span></div>
            </div>

            <div class="form-group text-center">
                <div class="btn-group">
                    <button type="submit" class="btn btn-success" name="submit">SAVE SMS</button>
                </div>
                <button type="submit" class="btn btn-primary" name="sms">SEND SMS</button>
            </div>
        </div>
    </form>
        <!-- <br>
        <br><br><br>
        <form role="form" action="index.php" method="get">
            <div class="">
                <input type="hidden" name="q" value="inactivesend"/>
                <input type="hidden" name="status" value="3"/>
                <div class="">
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Send SMS" />
                    </div>
                </div>
            </div>
        </form> -->

</div>
</hr>
<script type="text/javascript">
    function countChar(val) {
        var len = val.value.length;
        if (len >= 160) {
          val.value = val.value.substring(0, 160);
        } else {
          $('#charNum').text(160 - len);
        }
      };
</script>