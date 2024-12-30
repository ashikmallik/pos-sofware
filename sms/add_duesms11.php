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
                <textarea class="form-control" name="sms_head" id="ResponsiveDetelis"
                          rows="2"><?php echo isset($value['smshead']) ? $value['smshead'] : NULL; ?></textarea>
            </div>

            <div class="form-group">
                <label>DUE SMS Details</label>
                <textarea class="form-control" name="sms_body" id="ResponsiveDetelis"
                          rows="6"><?php echo isset($value['smsbody']) ? $value['smsbody'] : NULL; ?></textarea>
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

                                <select class="form-control" name="zone" required>
                                    <option value="x">All Zone's Due Client</option>
                                    <?php foreach ($obj->view_all('tbl_zone') as $singleZone) { ?>
                                        <option value="<?php echo $singleZone['zone_id'] ?>">
                                            <?php echo $singleZone['zone_name'] ?> -
                                            (<?php echo $obj->Total_Count('tbl_agent', "zone = " . $singleZone['zone_id'] . ""); ?>
                                            )
                                        </option>
                                    <?php }// foreach   ?>

                                </select>

                            </div>
                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                                <a type="submit" class="btn btn-primary">SEND SMS </a>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
      
    </div>


</div>
<hr></hr>

