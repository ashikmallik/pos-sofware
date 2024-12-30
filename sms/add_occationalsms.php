<?php

date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;


//===================Add Function===================

if (isset ($_POST['submit'])) {
    extract($_POST);

    $form_data = array(
        'smsbody' => $sms_body
    );

    $branch_add = $obj->Update_data("sms", $form_data, " status='2' ");

    if ($branch_add) {
        ?>
        <script>
            window.location = "?q=occation";
        </script>
        <?php
    } else {
        echo $notification = 'Insert Failed';
    }
}

$value = $obj->details_by_cond("sms", "status='2'");
?>

<!--===================end Function===================-->

<div class="row">
    <div class="col-md-12 bg-teal-800">
        <h4>Occasional SMS Details</h4>
    </div>
</div>


<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="row" style="padding:10px; font-size: 12px;">
    <form role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-6 col-md-offset-3">
            <div class="form-group">
                <label>Occasional SMS Details</label>
                <textarea class="form-control" onkeyup="countChar(this)" name="sms_body" id="ResponsiveDetelis"
                          rows="6"><?php echo isset($value['smsbody']) ? $value['smsbody'] : NULL; ?></textarea><div class="float-left" style="margin-left: 523px;"><span id="charNum"></span></div>
            </div>

            <div class="form-group text-center">
                <div class="btn-group">
                    <button type="submit" class="btn btn-success" name="submit">SAVE SMS</button>
                </div>
            </div>
        </div>
    </form>


        <hr>
        <form role="form" action="index.php" method="get">
            <div class="col-md-6 col-md-offset-3">
                <input type="hidden" name="q" value="occationsend"/>
                <div class="col-md-8">

                    <div class="form-group">

                    <select class="form-control" name="cid" required>
                                    <option value="all">All  Client</option>
                                    <option value="1">Retailer  list</option>
                                    <option value="2">Workshop  List </option>
                                    <option value="3">Houseowner List</option>
                                    <option value="5">Feed List</option>
                                    <option value="6">Block Money List</option>
                                    <option value="7">Sanatary List</option>
                                </select>

                    </div>
                </div>

                <div class="col-md-4">

                    <div class="form-group">

                        <input type="submit" class="btn btn-primary" value="Send SMS" />
                    </div>
                </div>
            </div>
        </form>

</div>
</hr>
<script type="text/javascript">
   function countChar(val) {
        var len = val.value.length;
        if (len >= 900) {
          val.value = val.value.substring(0, 900);
        } else {
          $('#charNum').text(900 - len);
        }
      };
</script>