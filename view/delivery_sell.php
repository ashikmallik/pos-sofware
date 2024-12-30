<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : null;

$total_amount = 0;
$due = 0;
$sell_cat = 3; // for accounts

$sellId = isset($_GET['invoiceId']) ? $_GET['invoiceId'] : null;
$sellData = $obj->details_by_cond('vw_sell', "sell_id = $sellId");
$deliveryData = $obj->details_selected_field_by_cond('tbl_delivery', "*, SUM(total_qty) as total_delivery_for_sell_qty", "sell_id = $sellId");
$previousDeliveryQty = isset($deliveryData['total_delivery_for_sell_qty']) ? $deliveryData['total_delivery_for_sell_qty'] : 0;

$sellItemData = $obj->view_all_by_cond('vw_sell_item', 'sell_id = ' . $sellData['sell_id'] . ' AND delivery_status = 0 ORDER BY `vw_sell_item`.`id` ASC');


if (isset($_POST['update_sell'])) {
    extract($_POST);
    $i = 0;
    $deliveryArray = array();
    $total_amount = 0;
    $total_qty = 0;

    foreach ($product_id as $id) {
        $total_qty += $item_qty = !empty($qty[$i]) ? $qty[$i] : 0;

        $deliveryArray[$i] = array(
            'product_id' => $id,
            'qty' => $item_qty,
        );
        $i++;
    }


    $form_sell_update = array(

        'delivery_status' => 1,
        'delivery_date' => date('Y-m-d'),
    );


    $deliveryCheck = $obj->Total_Count('tbl_sell', "sell_id = $sellId AND delivery_status = 1");
    
    if ($deliveryCheck == 0) {
        if ($sellData['total_qty'] == $previousDeliveryQty + $total_qty) { // only when full delivery complete then sell will be updated
            $sell_update = $obj->Update_data("tbl_sell", $form_sell_update, "sell_id = $sellId");
        } else {
            /*$sell_update = $obj->Update_data("tbl_sell", ['delivery_date' => date('Y-m-d'),], "sell_id = $sellId");*/
            $sell_update = $obj->Update_data("tbl_sell", $form_sell_update, "sell_id = $sellId");
        }

        $form_delivery_item = array(

            'sell_id' => $sellId,
            'customer' => $sellData['customer'],
            'total_qty' => $total_qty,
            'delivery_date' => date('Y-m-d'),
            'entry_by' => $userid,
            'update_by' => 0

        );

        $delivery_add = $obj->insert_by_condition("tbl_delivery", $form_delivery_item, " ");

        foreach ($deliveryArray as $singleDelivery) {
            $sellItemData = $obj->details_by_cond('tbl_sell_item', 'sell_no = ' . $sellId . ' AND product_id = ' . $singleDelivery['product_id']);

            $deliveryItemData = $obj->details_selected_field_by_cond('tbl_delivery_item', 'SUM(qty) as total_qty', 'sell_no = ' . $sellId . ' AND product_id = ' . $singleDelivery['product_id']);

            $previousDeliveryItem = isset($deliveryItemData['total_qty']) ? $deliveryItemData['total_qty'] : 0;

            if ($sellItemData['qty'] == $previousDeliveryItem + $singleDelivery['qty']) {
                $obj->Update_data("tbl_sell_item", ['delivery_status' => 1], 'sell_no = ' . $sellId . ' AND product_id = ' . $singleDelivery['product_id']);
            }

            $form_delivery_item = array(
                'sell_no' => $sellId,
                'product_id' => $singleDelivery['product_id'],
                'qty' => $singleDelivery['qty'],
                'delivery_date' => date('Y-m-d'),
                'delivery_by' => $userid
            );
            $delivery_qty_add = $obj->insert_by_condition("tbl_delivery_item", $form_delivery_item, " ");
        }// foreach looop finish
        if ($delivery_qty_add) {
            $notification = "Item Deliverd Successfully";
            ?>
            <script>
                window.location = "?q=view_all_sell";
            </script>
            <?php
        } else {
            $notification = "Item Deliverd Failed";
        }
    } else {
        $notification = "Sorry! This Order is already Delevered";
    }
}

?>
<!--===================end Function===================-->
<style>
    .delete_row {
        margin-top: 3px;
    }

    .alert {
        margin-bottom: 0px !important;
        padding: 6px !important;
    }

    .alert-info {
        color: #0b4a69 !important;
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

<div class="col-md-12 bg-teal-800 text-center" style="margin-bottom:5px;">
    <h4>Welcome to Delivery Item Page . ...
        <img src="asset/img/delivery-icon.png" style="margin-bottom: -13px">
    </h4>
</div>

<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; text-align: center;">
    <b><?php echo isset($notification) ? $notification : null; ?></b>
</div>


<div class="row" style="padding:10px; font-size: 12px;">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-12">

            <div class="bg-info padding_5_px text-center">

                <h4>Customer Name: <?php echo $sellData['customer_name'] ?></h4>
                <label class="control-label">Please confirm below list as ready for Customer Id: <?php echo $sellData['customer']; ?> </label>

            </div>

            <table class="table" style="margin-bottom:0px;  ">
                        <thead>
                            <tr>
                                <th class="col-md-4 text-center">Product</th>
                                <th class="col-md-2 text-center">Qty</th>
                                <th class="col-md-2 text-center">Sell Date</th>
                                <th class="col-md-2 text-center">Notice</th>
                                <!--<th class="col-md-1 text-center">Action</th>-->
                            </tr>
                        </thead>
                        <tbody id="orderTable">
                        <?php
                        $rowNum = 0;
                        $total_amount = 0;

                        $totalQty = 0;

                        foreach ($sellItemData as $sellItem) {
                            $rowNum++;
                            ?>
                            <tr id="row_1">

                                <td class="text-center customer">
                                    <p class="form-control"><?php echo isset($sellItem['product_name']) ? $sellItem['product_name'] : null; ?></p>
                                    <input name="product_id[]" value="<?php echo $sellItem['product_id'] ?>"
                                           id="product_id" type="hidden">
                                </td>
                                <?php

                                $deliveryItem = $obj->details_selected_field_by_cond('tbl_delivery_item', 'SUM(qty) as total_qty', 'sell_no = ' . $sellItem['sell_id'] . ' AND product_id = ' . $sellItem['product_id']);

                                $sellQty = (isset($sellItem['qty']) ? $sellItem['qty'] : 0) - (isset($deliveryItem['total_qty']) ? $deliveryItem['total_qty'] : 0);
                                ?>
                                <td class="text-center">
                                    <p class="form-control"><?php echo isset($sellQty) ? $sellQty : null; ?></p>
                                    <input onkeypress="return numbersOnly(event)" id="qty" name="qty[]"
                                           value="<?php echo $sellQty ?>"
                                           class="form-control border-slate-600" type="hidden">
                                </td>
                                <?php
                                $totalQty += $sellQty;
                                ?>

                                <td class="text-center">
                                    <p class="form-control"><?php echo date('d/m/Y', strtotime($sellItem['entry_date'])) ?></p>
                                </td>

                                <td class="text-center">
                                    <div class="noticeTab" id="errrMsg<?php echo $sellItem['product_id'] ?>">
                                        <div class="alert alert-success">Proceed</div>

                                    </div>
                                </td>

                                <!--<td class="text-center">
                                    <button class="delete_row btn btn-danger btn-sm">Remove</button>
                                </td>-->

                            </tr>
                        <?php }  ?>

                        </tbody>
                        <hr>
                        <tfoot>
                            <tr id="total_row" class="bg-warning">
                            <td colspan="2" class="text-center" style="padding-right: 0px !important;">
                                <div class="col-sm-12">
                                    <div class="pull-right">
                                        <h4 style="margin-top:4px"> Total Qty </h4>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <p class="form-control"><?php echo isset($totalQty) ? $totalQty : null; ?></p>
                                <input id="total_qty" value="<?php echo $totalQty; ?>"
                                                           type="hidden" class="form-control"></td>
                            <td colspan="3"><h4 style="margin-top:4px"></h4></td>
                        </tr>
                        </tfoot>
                    </table>
        </div>

        <div class="col-md-12" style="margin-top:30px;">
            <div class="col-md-2 col-md-offset-5">
                <div class="text-center">
                    <button type="submit" class="btn btn-block btn-success" name="update_sell">Confirm</button>
                </div>
            </div>
        </div>
</div>
</form>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        $('form').submit(function (submitEvent) {

            $('#orderTable').each(function () {

                var qtyErrorFlag = true;
                var emptyFieldCount = 0;

                $(this).find('input#qty').each(function () {

                    if (!$(this).val() || $(this).val() == '0') {
                        qtyErrorFlag = false;
                        emptyFieldCount++;
                    }
                });

                if (qtyErrorFlag == false) {

                    submitEvent.preventDefault();
                    alert('Sorry Must Provide all Qty. ' + emptyFieldCount + ' Qty field is empty.');

                }

            });
        });

        function totalQtyCalculate() { // return total price

            var totalQty = 0;
            $('#orderTable').each(function () {

                $(this).find('input#qty').each(function () {
                    if (!!$(this).val()) {
                        totalQty += parseInt($(this).val());
                    }
                });
            });
            return totalQty;
        }


        var row = 1;

        function addRow(message) {

            $("tr#row_" + row).html(message);
            $('#orderTable').append('<tr id="row_' + (row + 1) + '"></tr>');
            row++;
        }

        $("#orderTable").on('click', '.delete_row', function () { // delete entire row when press remove button

            $('select[name="item_name"]').val('');
            $(this).parent().parent().remove();
            $('input#total_price').val(totalPriceCalculate());

        });

        $("#orderTable").on('keyup', 'input#qty', function () { // total price and total field updated while give new qty
            
            var qty = $(this).val();
            var sellId = <?php echo $sellId; ?>;
            var itemId = $(this).parent().parent().find('td.customer input#product_id').val();
            var url = 'add/check_sell_ajax.php';
            var getData = {'item': itemId, 'sell': sellId};
            
            if (qty) {
                if (qty != '0') {
                    $.get(url, getData, function (result) {
                        if (result.remaining_sell_qty >= qty) {

                            $('td div#errrMsg' + itemId).html('<div class="alert alert-success">Proceed</div>');
                        } else {

                            $('td div#errrMsg' + itemId).html('<div class="alert alert-danger"><strong>' + Math.abs((result.remaining_sell_qty - parseInt(qty))) + ' pc short!</strong></div>');
                        }
                    }, 'json');
                }
            } else {
                $('td div#errrMsg' + itemId).html('<div class="alert alert-info">Please add Qty</div>');
            }

            $('input#total_qty').val(totalQtyCalculate());
        });

    });

</script>