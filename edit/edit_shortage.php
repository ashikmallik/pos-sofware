<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

//===================Add Function===================
$total_amount = 0;
$due = 0;

$shortageId = isset($_GET['shortageId']) ? $_GET['shortageId'] : NULL;

$shortageData = $obj->details_by_cond('tbl_shortage', "id = '$shortageId'");

if (isset($_POST['submit'])) {

    extract($_POST);

    $form_shortage_table = array(
        'redA' => $redA,
        'redB' => $redB,
        'redC' => $redC,
        'whiteA' => $whiteA,
        'whiteB' => $whiteB,
        'whiteC' => $whiteC,
        'duckEgg' => $duckEgg,
        'birdEgg' => $birdEgg,
        'damage' => $damage,
        'last_update' => $date_time,
        'update_by' => $userid
    );

    $shortage_update = $obj->Update_data("tbl_shortage", $form_shortage_table, "id = '$shortageId'");
    if ($shortage_update) {
        $notification = 'Edit Successful';
        ?>
        <script>
            window.location = "?q=view_all_shortage"
        </script>
        <?php
    } else {
        $notification = 'Edit Failed';
    }
}
?>
<!--===================end Function===================-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
<script>

    function numbersOnly(e) // Numeric Validation
    {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) {
            if ((unicode < 2534 || unicode > 2543) && (unicode < 46 || unicode > 57)) {
                return false;
            } else if (unicode == 47) {
                return false;
            }
        }
    }
</script>


<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<span style="color: red; font-size: 20px;text-align:left;"></span>

<div class="col-md-9 col-md-offset-2 bg-grey-800 text-center" style="margin-bottom:5px;">
    <h4>Welcome to Edit Shortage of date <?php echo date('d-M-Y', strtotime($shortageData['entry_date'])) ?> </h4>
</div>

<div class="row" style="padding:10px; font-size: 12px;">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-10  col-md-offset-1" style="padding:10px; font-size: 12px;">
            <div class="col-md-6">
                
                <div class="form-group">
                    <label class="control-label col-sm-4" for="redA">Red A:</label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo isset($shortageData['redA']) ? $shortageData['redA'] : '' ?>" onkeypress="return numbersOnly(event)" name="redA" class="form-control" >
                    </div>
                </div>
                <hr>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="redB">Red B</label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo isset($shortageData['redB']) ? $shortageData['redB'] : '' ?>" onkeypress="return numbersOnly(event)" name="redB" class="form-control">
                    </div>
                </div>
                <hr>
                
                <div class="form-group">
                    <label class="control-label col-sm-4" for="redB">Red C</label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo isset($shortageData['redC']) ? $shortageData['redC'] : '' ?>" onkeypress="return numbersOnly(event)" name="redC" class="form-control">
                    </div>
                </div>
                <hr>
                
                <div class="form-group">
                    <label class="control-label col-sm-4" for="duckEgg">Duck Egg</label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo isset($shortageData['duckEgg']) ? $shortageData['duckEgg'] : '' ?>" onkeypress="return numbersOnly(event)" name="duckEgg" class="form-control">
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
            
                <div class="form-group">
                    <label class="control-label col-sm-4" for="whiteA">White A</label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo isset($shortageData['whiteA']) ? $shortageData['whiteA'] : '' ?>" onkeypress="return numbersOnly(event)" name="whiteA" class="form-control">
                    </div>
                </div>
                <hr>
                
                <div class="form-group">
                    <label class="control-label col-sm-4" for="whiteB">White B</label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo isset($shortageData['whiteB']) ? $shortageData['whiteB'] : '' ?>" onkeypress="return numbersOnly(event)" name="whiteB" class="form-control">
                    </div>
                </div>
                <hr>
                
                <div class="form-group">
                    <label class="control-label col-sm-4" for="whiteC">White C</label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo isset($shortageData['whiteC']) ? $shortageData['whiteC'] : '' ?>" onkeypress="return numbersOnly(event)" name="whiteC" class="form-control">
                    </div>
                </div>
                <hr>
                
                <div class="form-group">
                    <label class="control-label col-sm-4" for="birdEgg">Bird Egg</label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo isset($shortageData['birdEgg']) ? $shortageData['birdEgg'] : '' ?>" onkeypress="return numbersOnly(event)" name="birdEgg" class="form-control">
                    </div>
                </div>
              </div>
                
            <div class="col-md-6 col-md-offset-3" style="padding-top: 20px;">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="damage">Wastage</label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo isset($shortageData['damage']) ? $shortageData['damage'] : '' ?>" onkeypress="return numbersOnly(event)" name="damage" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row margin_top_10px ">
                <div class="col-md-2 col-md-offset-5">
                    <div class="text-center">
                        <button type="submit" class="btn btn-block btn-success" name="submit">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
