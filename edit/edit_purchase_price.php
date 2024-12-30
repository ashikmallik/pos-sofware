<?php
session_start();

$user_id = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$FullName = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : NULL;
$UserName = isset($_SESSION['UserName']) ? $_SESSION['UserName'] : NULL;
$PhotoPath = isset($_SESSION['PhotoPath']) ? $_SESSION['PhotoPath'] : NULL;
$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;


if (!empty($_SESSION['UserId'])) {

    include '../model/Controller.php';
    include '../model/FormateHelper.php';

    $formater = new FormateHelper();
    $obj = new Controller();

    $billId = isset($_GET['billId']) ? $_GET['billId'] : NULL;

    $billData = $obj->details_by_cond('vw_purchase', "bill_id = '$billId'");

    $supplierData = $obj->details_by_cond('tbl_supplier', "supplier_id = '" . $billData['suplier'] . "'");

    function checkPriceField($billData, $rate, $qty) {
        if (isset($billData[$rate])) {
            if ($billData[$rate] == '0.00') {
                if ($billData[$qty] == '0') {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    if (isset($_POST['updatePrice'])) {
        extract($_POST);
        $allPriceGiven = 0;
       
        !empty($redArate) ? $redArate : $redArate = '0.00';
        !empty($redBrate) ? $redBrate : $redBrate = '0.00';
        !empty($redCrate) ? $redCrate : $redCrate = '0.00';
        !empty($whiteArate) ? $whiteArate : $whiteArate = '0.00';
        !empty($whiteBrate) ? $whiteBrate : $whiteBrate = '0.00';
        !empty($whiteCrate) ? $whiteCrate : $whiteCrate = '0.00';
        !empty($duckEggRate) ? $duckEggRate : $duckEggRate = '0.00';
        !empty($birdEggRate) ? $birdEggRate : $birdEggRate = '0.00';
        !empty($damageRate) ? $damageRate : $damageRate = '0.00';
        
        $redA = $billData['redA_qty'];
        $redB = $billData['redB_qty'];
        $redC = $billData['redC_qty'];
        $whiteA = $billData['whiteA_qty'];
        $whiteB = $billData['whiteB_qty'];
        $whiteC = $billData['whiteC_qty'];
        $duckEgg = $billData['duckEgg_qty'];
        $birdEgg = $billData['birdEgg_qty'];
        $damage = $billData['damage_qty'];

        $redAprice = $redArate * $redA;
        $redBprice = $redBrate * $redB;
        $redCprice = $redCrate * $redC;
        $whiteAprice = $whiteArate * $whiteA;
        $whiteBprice = $whiteBrate * $whiteB;
        $whiteCprice = $whiteCrate * $whiteC;
        $duckEggPrice = $duckEggRate * $duckEgg;
        $birdEggPrice = $birdEggRate * $birdEgg;
        $damagePrice = $damageRate * $damage;

        $total_price = $redAprice + $redBprice + $redCprice + $whiteAprice + $whiteBprice + $whiteCprice + $duckEggPrice + $birdEggPrice + $damagePrice;

        //check if all price given
        if ($redArate == '0.00' && $redA != '0') {
            $allPriceGiven = 0;
        } else if ($redBrate == '0.00' && $redB != '0') {
            $allPriceGiven = 0;
        } else if ($redCrate == '0.00' && $redC != '0') {
            $allPriceGiven = 0;
        } else if ($whiteArate == '0.00' && $whiteA != '0') {
            $allPriceGiven = 0;
        } else if ($whiteBrate == '0.00' && $whiteB != '0') {
            $allPriceGiven = 0;
        } else if ($whiteCrate == '0.00' && $whiteC != '0') {
            $allPriceGiven = 0;
        } else if ($duckEggRate == '0.00' && $duckEgg != '0') {
            $allPriceGiven = 0;
        } else if ($birdEggRate == '0.00' && $birdEgg != '0') {
            $allPriceGiven = 0;
        } else if ($damageRate == '0.00' && $damage != '0') {
            $allPriceGiven = 0;
        } else {
            $allPriceGiven = 1;
        }

        $form_purchase_update_price = array(
            'redArate' => $redArate,
            'redBrate' => $redBrate,
            'redCrate' => $redCrate,
            'whiteArate' => $whiteArate,
            'whiteBrate' => $whiteBrate,
            'whiteCrate' => $whiteCrate,
            'duckEggRate' => $duckEggRate,
            'birdEggRate' => $birdEggRate,
            'damageRate' => $damageRate,
            'total_price' => $total_price,
            'all_price_status' => $allPriceGiven,
        );
        $form_tbl_purchase_update_price = array(
            'total_price' => $total_price,
            'all_price_status' => $allPriceGiven,
        );

        $purchase_update_bill = $obj->Update_data("tbl_bill", $form_tbl_purchase_update_price, "bill_id=$billId");
        $purchase_update_price = $obj->Update_data("tbl_purchase_price", $form_purchase_update_price, "bill_no=$billId");

        if ($purchase_update_price) {
            ?>
            <script>
                window.top.location.href = "../index.php?q=view_all_purchase";
            </script>
            <?php
        } else {
            $notification = 'Insert Failed';
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Add Price</title>

            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"/>

            <style>
                .form-group label {
                    padding-top: 10px;
                }
            </style>
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
        </head>
        <body>
            <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
                <b><?php echo isset($notification) ? $notification : NULL; ?></b>
            </div>
            <div class="container">
                <div class="row" style="padding:10px; font-size: 12px;">
                    <div class="col-sm-12 bg-primary" style="padding:10px; margin-bottom:10px; font-size: 12px;">
                        <h4 class="text-center">Bill from Supplier <?php echo ucwords($supplierData['supplier_name']); ?></h4>
                        <p class="text-center"> Please Add price rate for this bill </p>
                    </div>
                    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div
                                        class="form-group <?php echo (checkPriceField($billData, 'redArate', 'redA_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="redArate">Red A Rate for qty <span
                                                class="badge"><?php echo isset($billData['redA_qty']) ? number_format($billData['redA_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="redArate"
                                                   value="<?php echo isset($billData['redArate']) ? $billData['redArate'] : null; ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group <?php echo (checkPriceField($billData, 'redBrate', 'redB_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="redBrate">Red B Rate for qty <span
                                                class="badge"><?php echo isset($billData['redB_qty']) ? number_format($billData['redB_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="redBrate"
                                                   value="<?php echo isset($billData['redBrate']) ? $billData['redBrate'] : null; ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group <?php echo (checkPriceField($billData, 'redCrate', 'redC_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="redCrate">Red C Rate for qty <span
                                                class="badge"><?php echo isset($billData['redC_qty']) ? number_format($billData['redC_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="redCrate"
                                                   value="<?php echo isset($billData['redCrate']) ? $billData['redCrate'] :
            null;
    ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div class="form-group <?php echo (checkPriceField($billData, 'birdEggRate', 'birdEgg_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="birdEggRate">Bird Egg Rate for qty <span class="badge"><?php echo isset($billData['birdEgg_qty']) ? number_format($billData['birdEgg_qty']) : null; ?></span> pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="birdEggRate" value="<?php echo isset($billData['birdEggRate']) ? $billData['birdEggRate'] : null; ?>" class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div
                                        class="form-group <?php echo (checkPriceField($billData, 'whiteArate', 'whiteA_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="whiteArate">White A Rate for <span
                                                class="badge"><?php echo isset($billData['whiteA_qty']) ? number_format($billData['whiteA_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="whiteArate"
                                                   value="<?php echo isset($billData['whiteArate']) ? $billData['whiteArate'] : null; ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group <?php echo (checkPriceField($billData, 'whiteBrate', 'whiteB_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="whiteBrate">White B Rate for <span
                                                class="badge"><?php echo isset($billData['whiteB_qty']) ? number_format($billData['whiteB_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="whiteBrate"
                                                   value="<?php echo isset($billData['whiteBrate']) ? $billData['whiteBrate'] : null; ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group <?php echo (checkPriceField($billData, 'whiteCrate', 'whiteC_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="whiteBrate">White B Rate for <span
                                                class="badge"><?php echo isset($billData['whiteC_qty']) ? number_format($billData['whiteC_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="whiteCrate"
                                                   value="<?php echo isset($billData['whiteCrate']) ? $billData['whiteCrate'] : null; ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div class="form-group <?php echo (checkPriceField($billData, 'duckEggRate', 'duckEgg_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="duckEggRate">Duck Egg Rate for <span class="badge"><?php echo isset($billData['duckEgg_qty']) ? number_format($billData['duckEgg_qty']) : null; ?></span> pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="duckEggRate" value="<?php echo isset($billData['duckEggRate']) ? $billData['duckEggRate'] : null; ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="margin_top_10px col-md-6 col-sm-offset-2">
                                <div
                                    class="form-group <?php echo (checkPriceField($billData, 'damageRate', 'damage_qty')) ? 'has-warning' : ''; ?>">
                                    <label class="control-label col-sm-5" for="damageRate">Damage Rate for <span
                                            class="badge"><?php echo isset($billData['damage_qty']) ? number_format($billData['damage_qty']) : null; ?></span>
                                        pcs</label>
                                    <div class="col-sm-5 input-group input-group">
                                        <input type="text" onkeypress="return numbersOnly(event)" name="damageRate"
                                               value="<?php echo isset($billData['damageRate']) ? $billData['damageRate'] : null;
    ?>"
                                               class="form-control">
                                        <span class="input-group-addon">TK</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row margin_top_10px ">
                            <div class="col-sm-12">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success" name="updatePrice">Update Price</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </body>
    </html>
    <?php
}
?>