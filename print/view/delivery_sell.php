<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$total_amount = 0;
$due = 0;
$sell_cat = 3; // for accounts

$sellId = isset($_GET['invoiceId']) ? $_GET['invoiceId'] : null;

$sellData = $obj->details_by_cond('vw_sell', "sell_id = $sellId");
$sellItemData = $obj->view_all_by_cond('vw_sell_item', 'sell_id = ' . $sellData['sell_id'] . ' ORDER BY `vw_sell_item`.`id` ASC');

if (isset($_POST['update_sell'])) {
    extract($_POST);
    $i = 0;
    $sellArray = array();
    $total_amount = 0;
    $total_qty = 0;

    foreach ($product_id as $id) {

        $item_price = !empty($price[$i]) ? $price[$i] : 0;

        $discount_existing = (!empty($discount[$i])) ? $discount[$i] : 0;

        $total_qty += $item_qty = !empty($qty[$i]) ? $qty[$i] : 0;

        $total = $item_price * $item_qty;

        $total_amount += ($total - (($total * $discount_existing) / 100));

        $sellArray[$i] = array(
            'product_id' => $id,
            'price' => $item_price,
            'qty' => $item_qty,
            'total' => $total,
            'discount' => $discount_existing,
        );
        $i++;
    }

    $obj->Delete_data('tbl_sell_item', "`sell_no` = $sellId");

    $due = $total_amount - $sellData['payment_recieved'];

    $form_sell_update = array(

        'customer' => $sellData['customer'],
        'total_price' => $total_amount,
        'total_qty' => $total_qty,
        'due_to_company' => $due,
        'delivery_status' => 1,
        'last_update' => date('Y-m-d'),
        'update_by' => $userid

    );

    $sell_update = $obj->Update_data("tbl_sell", $form_sell_update, "sell_id = $sellId");

    foreach ($sellArray as $singleSell) {

        $form_sell_item = array(
            'customer' => $sellData['customer'],
            'sell_no' => $sellId,
            'product_id' => $singleSell['product_id'],
            'price' => $singleSell['price'],
            'qty' => $singleSell['qty'],
            'total_amount' => $singleSell['total'],
            'discount_exist' => $singleSell['discount'],
            'delivery_status' => 1,
            'update_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $sell_qty_add = $obj->insert_by_condition(" tbl_sell_item", $form_sell_item, " ");

    }
    if ($sell_qty_add) {
        ?>
        <script>
            window.location = "?q=view_all_sell";
            window.open('pdf/invoice.php?invoiceId=<?php echo $sellId ?>//', '_blank');
        </script>
        <?php
    } else {
        $notification = '<div class="alert alert-danger">Insert Failed</div>';
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

<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>

<div class="col-md-12 bg-teal-800 text-center" style="margin-bottom:5px;">
    <h4>Welcome to Delivery Item Page . ...
        <img src="asset/img/delivery-icon.png" style="margin-bottom: -13px">
    </h4>
</div>

<div class="row" style="padding:10px; font-size: 12px;">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-12">

            <div class="bg-info padding_5_px text-center">

                <h4>Customer Name: <?php echo $sellData['customer_name'] ?></h4>
                <h4>Customer Id: <?php echo $sellData['customer']; ?></h4>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">

                        <label class="control-label">Please confirm below list as ready for delivery</label>

                    </div>
                </div>

                <div class="col-md-12">
                    <table class="table" style="margin-bottom:0px;  ">
                        <thead>
                        <tr>
                            <th class="col-md-1 text-center">SL</th>
                            <th class="col-md-3 text-center">Product</th>
                            <th class="col-md-2 text-center">Unite Price</th>
                            <th class="col-md-1 text-center">Qty</th>
                            <th class="col-md-2 text-center">Total</th>
                            <th class="col-md-1 text-center">Action</th>
                            <th class="col-md-2 text-center">Notice</th>
                            <th class="col-md-1 text-center">Discount %</th>
                        </tr>
                        </thead>
                        <tbody id="orderTable">
                        <?php
                        $rowNum = 0;
                        $total_amount = 0;
                        foreach ($sellItemData as $sellItem) {
                            $rowNum++;

                            ?>

                            <tr id="row_1">
                                <td class="text-center">
                                    <p><?php echo $rowNum; ?></p>
                                </td>
                                <td>
                                    <input readonly="readonly"
                                           value="<?php echo isset($sellItem['product_name']) ? $sellItem['product_name'] : null; ?>"
                                           class="form-control" type="text">
                                </td>
                                <input name="product_id[]" value="<?php echo $sellItem['product_id'] ?>" id="product_id"
                                       class="form-control" type="hidden">
                                <td>
                                    <input onkeypress="return numbersOnly(event)" id="price" name="price[]"
                                           value="<?php echo $sellPrice = isset($sellItem['price']) ? $sellItem['price'] : 0; ?>"
                                           class="form-control" type="text">
                                </td>
                                <td>
                                    <input onkeypress="return numbersOnly(event)" id="qty" name="qty[]"
                                           value="<?php echo $sellQty = isset($sellItem['qty']) ? $sellItem['qty'] : 0; ?>"
                                           class="form-control" type="text">
                                </td>
                                <td>
                                    <?php
                                    $total = $sellPrice * $sellQty;
                                    $total_with_discount = ($total - (($total * $sellItem['discount_exist']) / 100)); ?>
                                    <input readonly="" onkeypress="return numbersOnly(event)" id="total"
                                           value="<?php echo($total_with_discount); ?>" name="total[]"
                                           class="form-control" type="text">
                                </td>
                                <?php $total_amount += $total_with_discount; ?>
                                <td>
                                    <a class="delete_row btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this Sell item?');">Remove</a>
                                </td>
                                <td>
                                    <div class="noticeTab" id="errrMsg<?php echo $sellItem['product_id'] ?>">
                                        <div class="alert alert-success">You Can Proceed</div>
                                </td>
                                <td>
                                    <input onkeypress="return numbersOnly(event)" value="<?php echo $sellItem['discount_exist']; ?>" id="discount" name="discount[]" class="form-control" type="text">
                                </td>
                            </tr>
                        <?php }

                        echo '<tr id="row_' . ($rowNum + 1) . '"></tr>';
                        ?>
                        </tbody>
                        <hr>
                        <tr id="total_row" class="bg-warning">
                            <td colspan="4" class="text-center" style="padding-right: 0px !important;">
                                <div class="col-sm-12">
                                    <div class="pull-right">
                                        <h4 style="margin-top:4px"> Total Taka </h4>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><input id="total_price" value="<?php echo $total_amount; ?>"
                                                           type="text" class="form-control"></td>
                            <td colspan="3"><h4 style="margin-top:4px">Taka</h4></td>
                        </tr>
                    </table>

                </div>
            </div>
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

        function totalPriceCalculate() { // return total price

            var totalPrice = 0;
            $('#orderTable').each(function () {

                $(this).find('input#total').each(function () {
                    if (!!$(this).val()) {
                        totalPrice += parseInt($(this).val());
                    }
                });
            });
            return totalPrice;
        }


        var row = 1;

        function addRow(message) {

            $("tr#row_" + row).html(message);
            $('#orderTable').append('<tr id="row_' + (row + 1) + '"></tr>');
            row++;
        }

        $('select[name="item_name"]').on('change', function (e) { // add new row when new item selected

            var itemName = $(this).find(':selected').data('item');
            var itemPrice = $(this).find(':selected').data('price');
            var itemId = $(this).find(':selected').val();

            addRow('<td>' + row + '</td><td><input type="text" id="item_name" value="' + itemName + '" class="form-control"></td>' +
                '<input type="hidden" id="product_id" name="product_id[]" value="' + itemId + '" class="form-control">' +
                '<td><input type="text" onkeypress="return numbersOnly(event)"  id="price" name="price[]" value="' + itemPrice + '" class="form-control"></td>' +
                '<td><input type="text" onkeypress="return numbersOnly(event)"  id="qty" name="qty[]" class="form-control"></td>' +
                '<td><input type="text" readonly onkeypress="return numbersOnly(event)"   id="total" name="total[]" class="form-control"></td>' +
                '<td><a class="delete_row btn btn-danger btn-sm">Remove</a></td>' +
                '<td><div class="noticeTab" id="errrMsg' + itemId + '"><div class="alert alert-info">Please add Qty</div></td>');

            $('table tr#total_row').removeClass('hidden');

        });

        $("#orderTable").on('click', '.delete_row', function () { // delete entire row when press remove button

            $('select[name="item_name"]').val('');
            $(this).parent().parent().remove();
            $('input#total_price').val(totalPriceCalculate());

        });

        $("#orderTable").on('keyup', 'input#qty', function () { // total price and total field updated while give new qty

            var price = $(this).parent().parent().find('td > input#price').val();
            var qty = $(this).val();
            var itemId = $(this).parent().parent().find('input#product_id').val();
            var itemName = $(this).parent().parent().find('input#item_name').val();

            var url = 'ajax_action/ajax_check_stock.php';
            var postData = {item: itemId, qty_value: qty};

            if (qty) {

                if (qty != '0') {

                    $.get(url, postData, function (data) {

                        if (data != 'ok') {
                            $('td div#errrMsg' + itemId).html('' +
                                '<div class="alert alert-danger"><strong>Sorry! ' + data + ' pc short</strong></div>');
                        } else {
                            $('td div#errrMsg' + itemId).html('<div class="alert alert-success">You Can Proceed</div>');
                        }
                    });
                } else {

                    $('td div#errrMsg' + itemId).html('<div class="alert alert-info">Item will be romoved</div>');

                }
            } else {
                $('td div#errrMsg' + itemId).html('<div class="alert alert-info">Please add Qty</div>');
            }
            var total = price * qty;
            $(this).parent()
                .parent()
                .find('td > input#total')
                .val(total);

            $('input#total_price').val(totalPriceCalculate());
        });

        $("#orderTable").on('keyup', 'input#price', function () {// total price and total field updated while update price
            var price = $(this).val();
            var qty = $(this).parent().parent().find('td > input#qty').val();
            var total = price * qty;
            $(this).parent()
                .parent()
                .find('td > input#total')
                .val(total);
            $('input#total_price').val(totalPriceCalculate());
        });

        $('tr td input#total_price').on('click', function () {

            $('input#total_price').val(totalPriceCalculate());

        })


        $("#orderTable").on('keyup', 'input#discount', function () {// total discount field updated while update price

            var discount = $(this).val();

            var qty = $(this).parent().parent().find('td > input#qty').val();
            if(!qty){ alert("Please fill the qty first before discount")}
            var price = $(this).parent().parent().find('td > input#price').val();

            var total = price * qty;

            var discountAmount = total - ((total * discount)/100);

            $(this).parent()
                .parent()
                .find('td > input#total')
                .val(discountAmount);
            $('input#total_price').val(totalPriceCalculate());
        });



        $('select[name="payment_method"').on('change', function () { // banking section will show when click bank
            if (this.value == 'bank') {
                $('#bank_info select[name="account_no"]').removeAttr('disabled');
                $('#bank_info input[name="diposited_by"]').removeAttr('disabled');
                $('#bank_info').show();
            } else {
                $('#bank_info select[name="account_no"]').attr('disabled', 'disabled');
                $('#bank_info input[name="diposited_by"]').attr('disabled', 'disabled');
                $('#bank_info').hide();
            }
        });

    });

</script>