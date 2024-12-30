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

    $invoiceId = isset($_GET['invoiceId']) ? $_GET['invoiceId'] : NULL;

    $saleData = $obj->details_by_cond('tbl_sell', "sell_id = '$invoiceId'");

    $customerData = $obj->details_by_cond('tbl_customer', "cus_id = '" . $saleData['customer'] . "'");

    echo $invoiceId;
    die();
    function checkPriceField($saleData, $rate, $qty) {
        if (isset($saleData[$rate])) {
            if ($saleData[$rate] == '0.00') {
                if ($saleData[$qty] == '0') {
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

        $redA = $saleData['redA_qty'];
        $redB = $saleData['redB_qty'];
        $redC = $saleData['redC_qty'];
        $whiteA = $saleData['whiteA_qty'];
        $whiteB = $saleData['whiteB_qty'];
        $whiteC = $saleData['whiteC_qty'];
        $duckEgg = $saleData['duckEgg_qty'];
        $birdEgg = $saleData['birdEgg_qty'];
        $damage = $saleData['damage_qty'];

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

        $form_sell_update_price = array(
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
        $form_tbl_bill_update_price = array(
            'total_price' => $total_price,
            'all_price_status' => $allPriceGiven,
        );

        $sale_update_price = $obj->Update_data("tbl_sell", $form_tbl_bill_update_price, "sell_id=$invoiceId");
        $sale_price_update_price = $obj->Update_data("tbl_sell_price", $form_sell_update_price, "sell_no=$invoiceId");

        if ($sale_update_price) {
            ?>
            <script>
                window.top.location.href = "../index.php?q=view_all_sell";
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
                    <div class="col-sm-12 bg-success" style="padding:10px; margin-bottom:10px; font-size: 12px;">
                        <h4 class="text-center">Sell for Customer <?php echo ucwords($customerData['cus_name']); ?></h4>
                        <p class="text-center"> Please Add Sale price rate for this bill </p>
                    </div>
                    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div
                                        class="form-group <?php echo (checkPriceField($saleData, 'redArate', 'redA_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="redArate">Red A Rate for qty <span
                                                class="badge"><?php echo isset($saleData['redA_qty']) ? number_format($saleData['redA_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="redArate"
                                                   value="<?php echo isset($saleData['redArate']) ? $saleData['redArate'] : null; ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group <?php echo (checkPriceField($saleData, 'redBrate', 'redB_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="redBrate">Red B Rate for qty <span
                                                class="badge"><?php echo isset($saleData['redB_qty']) ? number_format($saleData['redB_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="redBrate"
                                                   value="<?php echo isset($saleData['redBrate']) ? $saleData['redBrate'] : null; ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group <?php echo (checkPriceField($saleData, 'redCrate', 'redC_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="redCrate">Red C Rate for qty <span
                                                class="badge"><?php echo isset($saleData['redC_qty']) ? number_format($saleData['redC_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="redCrate"
                                                   value="<?php echo isset($saleData['redCrate']) ? $saleData['redCrate'] :
            null;
    ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div class="form-group <?php echo (checkPriceField($saleData, 'birdEggRate', 'birdEgg_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="birdEggRate">Bird Egg Rate for qty <span class="badge"><?php echo isset($saleData['birdEgg_qty']) ? number_format($saleData['birdEgg_qty']) : null; ?></span> pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="birdEggRate" value="<?php echo isset($saleData['birdEggRate']) ? $saleData['birdEggRate'] : null; ?>" class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div
                                        class="form-group <?php echo (checkPriceField($saleData, 'whiteArate', 'whiteA_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="whiteArate">White A Rate for <span
                                                class="badge"><?php echo isset($saleData['whiteA_qty']) ? number_format($saleData['whiteA_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="whiteArate"
                                                   value="<?php echo isset($saleData['whiteArate']) ? $saleData['whiteArate'] : null; ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group <?php echo (checkPriceField($saleData, 'whiteBrate', 'whiteB_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="whiteBrate">White B Rate for <span
                                                class="badge"><?php echo isset($saleData['whiteB_qty']) ? number_format($saleData['whiteB_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="whiteBrate"
                                                   value="<?php echo isset($saleData['whiteBrate']) ? $saleData['whiteBrate'] : null; ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group <?php echo (checkPriceField($saleData, 'whiteCrate', 'whiteC_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="whiteBrate">White B Rate for <span
                                                class="badge"><?php echo isset($saleData['whiteC_qty']) ? number_format($saleData['whiteC_qty']) : null; ?></span>
                                            pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="whiteCrate"
                                                   value="<?php echo isset($saleData['whiteCrate']) ? $saleData['whiteCrate'] : null; ?>"
                                                   class="form-control">
                                            <span class="input-group-addon">TK</span>
                                        </div>
                                    </div>
                                    <div class="form-group <?php echo (checkPriceField($saleData, 'duckEggRate', 'duckEgg_qty')) ? 'has-warning' : ''; ?>">
                                        <label class="control-label col-sm-8" for="duckEggRate">Duck Egg Rate for <span class="badge"><?php echo isset($saleData['duckEgg_qty']) ? number_format($saleData['duckEgg_qty']) : null; ?></span> pcs</label>
                                        <div class="col-sm-4 input-group input-group">
                                            <input type="text" onkeypress="return numbersOnly(event)" name="duckEggRate" value="<?php echo isset($saleData['duckEggRate']) ? $saleData['duckEggRate'] : null; ?>"
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
                                    class="form-group <?php echo (checkPriceField($saleData, 'damageRate', 'damage_qty')) ? 'has-warning' : ''; ?>">
                                    <label class="control-label col-sm-5" for="damageRate">Damage Rate for <span
                                            class="badge"><?php echo isset($saleData['damage_qty']) ? number_format($saleData['damage_qty']) : null; ?></span>
                                        pcs</label>
                                    <div class="col-sm-5 input-group input-group">
                                        <input type="text" onkeypress="return numbersOnly(event)" name="damageRate"
                                               value="<?php echo isset($saleData['damageRate']) ? $saleData['damageRate'] : null;
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