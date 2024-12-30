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

$sellData = $obj->details_by_cond('tbl_sell', "sell_id = $sellId");
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

        'customer' => $customer_id,
        'total_price' => $total_price,
        'total_qty' => $total_qty,
        'due_to_company' => $due,
        'last_update' => date('Y-m-d'),
        'update_by' => $userid

    );
    $sell_update = $obj->Update_data("tbl_sell", $form_sell_update, "sell_id = $sellId");

    $form_account_update = array(
        'acc_amount' => $total_amount,
    );
    $account_update = $obj->Update_data("tbl_account", $form_account_update, "acc_type=23 AND purchase_or_sell_id='s_$sellId'");

    foreach ($sellArray as $singleSell) {

        $form_sell_item = array(

            'customer' => $customer_id,
            'sell_no' => $sellId,
            'product_id' => $singleSell['product_id'],
            'price' => $singleSell['price'],
            'qty' => $singleSell['qty'],
            'total_amount' => $singleSell['total'],
            'discount_exist' => $singleSell['discount'],
            'delivery_status' => 0,
            'update_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $sell_qty_add = $obj->insert_by_condition("tbl_sell_item", $form_sell_item, " ");

    }
    if ($sell_qty_add) {
        ?>
        <script>
            window.location = "?q=view_all_sell";
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
    .alert{
        margin-bottom: 0px !important;
        padding: 6px !important;
    }
    .alert-info {
        color: #0b4a69 !important;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

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

<div class="col-md-12 bg-slate-800 text-center" style="margin-bottom:5px;">
    <h4>Welcome to Edit Sell Item Page</h4>
</div>

<div class="row" style="padding:10px; font-size: 12px;">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-12" style="font-size: 12px;">

            <div class="col-md-9" style="margin-top:15px;margin-bottom:10px;">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="sup_id">Customer Id:</label>
                    <div class="col-sm-8">
                        <select class="form-control" required="required" name="customer_id" id="status">
                            <option></option>
                            <?php
                            $i = '0';
                            foreach ($obj->view_all("tbl_customer") as $customer) {
                                $i++; ?>
                                <option value="<?php echo isset($customer['cus_id']) ? $customer['cus_id'] : NULL; ?>" <?php echo ($sellData['customer'] == $customer['cus_id']) ? 'selected' : ''; ?>><?php echo isset($customer['cus_name']) ? $customer['cus_name'] : NULL; ?> - <?php echo isset($customer['cus_id']) ? $customer['cus_id'] : NULL; ?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <label class="control-label col-sm-3  pull-left" for="sup_id">Item Name : </label>
                        <div class="col-sm-9">
                            <select class="form-control" data-live-search="true" name="item_name"
                                    id="status">
                                <option></option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all("tbl_item_with_price") as $item) {
                                    $i++;
                                    ?>
                                    <option data-item="<?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?>"
                                            data-price="<?php echo isset($item['item_price']) ? $item['item_price'] : NULL; ?>"
                                            value="<?php echo isset($item['item_id']) ? $item['item_id'] : NULL; ?>"><?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?>
                                        - <?php echo isset($item['item_price']) ? $item['item_price'] . ' tk' : NULL; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <b>
                                <small class="text-primary" id="do_number_details"></small>
                            </b>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <table class="table" style="margin-bottom:0px;  ">
                        <thead>
                        <tr>
                            <th class="col-md-1 text-center">SL</th>
                            <th class="col-md-3 text-center">Product</th>
                            <th class="col-md-2 text-center">Unit Price</th>
                            <th class="col-md-1 text-center">Qty</th>
                            <th class="col-md-2 text-center">Total</th>
                            <th class="col-md-1 text-center">Action</th>
                            <th class="col-md-2 text-center">Notice</th>

                        </tr>
                        </thead>
                        <tbody id="orderTable">
                        <?php
                        $rowNum = 0;
                        $total_amount = 0;
                        foreach ($sellItemData as $sellItem) {
                            $sellPrice  = isset($sellItem['total_amount'])? $sellItem['total_amount'] : 0;
                            $sellQty    = isset($sellItem['qty']) ? $sellItem['qty'] : 0;

                            $sellItemPrice = $sellPrice/$sellQty;
                            $rowNum++; ?>

                            <tr id="row_1">
                                <td class="text-center">
                                    <?php echo $rowNum; ?>
                                </td>
                                <td>
                                    <input value="<?php echo isset($sellItem['product_name']) ? $sellItem['product_name'] : null; ?>"
                                           class="form-control" type="text">
                                </td>
                                <input name="product_id[]" value="<?php echo $sellItem['product_id'] ?>" id="product_id"
                                       class="form-control" type="hidden">
                                <td>
                                    <input onkeypress="return numbersOnly(event)" id="price" name="price[]"
                                           value="<?php echo $sellItemPrice; ?>"
                                           class="form-control" type="text">
                                </td>
                                <td>
                                    <input onkeypress="return numbersOnly(event)" id="qty" name="qty[]"
                                           value="<?= $sellQty;?>"
                                           class="form-control" type="text">
                                </td>
                                <td>
                                    <?php
                                    $total = $sellItemPrice * $sellQty;
                                    $total_with_discount = ($total - (($total * $sellItem['discount_exist']) / 100)); ?>
                                    <input readonly="" onkeypress="return numbersOnly(event)" id="total"
                                           value="<?php echo($total); ?>" name="total[]"
                                           class="form-control" type="text">
                                </td>
                                <?php $total_amount += $total; ?>
                                <td>
                                    <a class="delete_row btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this Sell item?');">Remove</a>
                                </td>
                                <td>
                                    <div class="noticeTab" id="errrMsg<?php echo $sellItem['product_id'] ?>"><div class="alert alert-success">You Can Proceed</div>
                                </td>
                            </tr>
                        <?php }
                        echo '<tr id="row_' . ($rowNum + 1) . '"></tr>';
                        ?>
                        </tbody>
                        <hr>
                        <tr id="total_row" class="bg-info">
                            <td colspan="4" class="text-center" style="padding-right: 0px !important;">
                                <div class="col-sm-12">
                                    <div class="col-md-3 pull-right">
                                        <h4 style="margin-top:4px"> Total Taka </h4>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><input id="total_price" name="total_price" value="<?php echo $total_amount; ?>" type="text" class="form-control"></td>
                            <td colspan="3"><h4 style="margin-top:5px">Taka</h4></td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>

        <div class="col-md-12" style="margin-top:30px;">
            <div class="col-md-2 col-md-offset-5">
                <div class="text-center">
                    <button type="submit" class="btn btn-block btn-success" name="update_sell">Update</button>
                </div>
            </div>
        </div>
</div>
</form>
</div>
<!--=========================================================JS====================================================-->

<script type="text/javascript">
    $(document).ready(function () {

        $('select[name="customer_id"]').selectpicker();
        $('select[name="item_name"]').selectpicker();

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

                if (!qtyErrorFlag) {
                    submitEvent.preventDefault();
                    alert('Sorry, all Qty fields must be filled. ' + emptyFieldCount + ' Qty fields are empty.');
                }
            });
        });

        function totalPriceCalculate() {
            var totalPrice = 0;
            $('#orderTable').find('input#total').each(function () {
                if ($(this).val()) {
                    totalPrice += parseFloat($(this).val()) || 0;
                }
            });
            return totalPrice.toFixed(2);
        }

        let row = 1;

        function addRow(itemName, itemPrice, itemId) {
            var newRow = `
                <tr id="row_${row}">
                    <td>${row}</td>
                    <td><input type="text" id="item_name" value="${itemName}" class="form-control" readonly></td>
                    <input type="hidden" id="product_id" name="product_id[]" value="${itemId}" class="form-control">
                    <td><input type="text" id="price" name="price[]" value="${itemPrice}" class="form-control" readonly></td>
                    <td><input type="text" id="qty" name="qty[]" class="form-control" onkeypress="return numbersOnly(event)"></td>
                    <td><input type="text" id="total" name="total[]" class="form-control" readonly></td>
                    <td><a class="delete_row btn btn-danger btn-sm">Remove</a></td>
                    
                    <td><div class="noticeTab" id="errrMsg${itemId}"><div class="alert alert-info">Please add Qty</div></div></td>
                    
                </tr>`;
            $('#orderTable').append(newRow);
            row++;
        }

        $('select[name="item_name"]').on('change', function () {
            var itemName = $(this).find(':selected').data('item');
            var itemPrice = $(this).find(':selected').data('price');
            var itemId = $(this).find(':selected').val();

            if (!itemName || !itemPrice || !itemId) {
                alert('Invalid product selected.');
                return;
            }

            addRow(itemName, itemPrice, itemId);
            $(this).val('').selectpicker('refresh');
        });

        $("#orderTable").on('click', '.delete_row', function () {
            $(this).closest('tr').remove();
            $('input#total_price').val(totalPriceCalculate());
        });

        $("#orderTable").on('keyup', 'input#qty', function () {
            var row = $(this).closest('tr');
            var price = parseFloat(row.find('input#price').val()) || 0;
            var qty = parseFloat($(this).val()) || 0;
            var itemId = row.find('input#product_id').val();

            var url = 'ajax_action/ajax_check_stock.php';
            var getData = { 'item': itemId };

            if (qty) {
                if (qty !== 0) {
                    $.get(url, getData, function (data) {
                        if (data.stock_qty >= qty) {
                            row.find('div#errrMsg' + itemId).html('<div class="alert alert-success">You Can Proceed</div>');
                        } else {
                            row.find('div#errrMsg' + itemId).html('<div class="alert alert-danger"><strong>Sorry! ' + Math.abs(data.stock_qty - qty) + ' pcs short</strong></div>');
                        }
                    }, 'json');
                } else {
                    row.find('div#errrMsg' + itemId).html('<div class="alert alert-info">Please add Qty</div>');
                }
            }

            var total = price * qty;
            row.find('input#total').val(total.toFixed(2));
            $('input#total_price').val(totalPriceCalculate());
        });
        
        $("#orderTable").on('keyup', 'input#discount', function() { // total discount field updated while update price

            var discount = $(this).val();

            var qty = $(this).parent().parent().find('td > input#qty').val();
            if (!qty) {
                alert("Please fill the qty first before discount")
            }
            var price = $(this).parent().parent().find('td > input#price').val();

            var total = price * qty;

            var discountAmount = total - total * discount / 100;

            $(this).parent()
                .parent()
                .find('td > input#total')
                .val(discountAmount);
            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());
        });

        $('select[name="payment_method"]').on('change', function () {
            if (this.value === 'bank') {
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
