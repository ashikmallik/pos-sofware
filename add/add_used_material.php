<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

//===================Add Function===================
$total_amount = 0;
$due = 0;
$sell_cat = 3; // for accounts
$sell_product_to_customer = 23;
$sell_item_add ='';
$sell_id='';

if (isset($_POST['add_sell'])) {
    extract($_POST);
    $i = 0;
    $usedArray = array();

    foreach ($product_id as $id) { // at first configure / arrange the array for db insert

        $usedArray[$i] = array(
            'product_id' => $id,
            'qty' => $qty[$i],
        );
        $i++;
    }


    foreach ($usedArray as $single) {

        $form_sell_item = array(
            'product_id' => $single['product_id'],
            'qty' => $single['qty'],
            'entry_date' => $date,
        );
        $sell_item_add = $obj->insert_by_condition("tbl_material_used", $form_sell_item, " ");
    }

//    $form_tbl_accounts = array(
//        'acc_description' => "Total Amount of Sell Product " . $customer_name . " (" . $customer_id . ") And Sell id : $sell_id",
//        'acc_amount' => $total_amount,
//        'acc_type' => $sell_product_to_customer,
//        'purchase_or_sell_id' => 's_' . $sell_id,
//        'cus_or_sup_id' => $customer_id,
//        'acc_head' => 0,
//        'payment_method' => $payment_method_type,
//        'entry_by' => $userid,
//        'entry_date' => date('Y-m-d'),
//        'update_by' => $userid
//    );
//    $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");

    if ($sell_item_add) {
        ?>
        <script>
            window.location = "?q=add_used_material";
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
<span style="color: red; font-size: 20px;text-align:left;"></span>

<div class="col-md-8 col-md-offset-2 bg-teal-800 text-center" style="margin-bottom:5px;">
    <h4>Add Used Material</h4>
</div>

<div class="row" style="padding:10px; font-size: 12px;">
    <form class="form-horizontal" role="form"  method="post">
        <div class="col-md-12" style="font-size: 12px;">

            <div class="row">
                <div class="col-md-10" style="margin-top:15px;">

                </div>
            </div>

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="form-group">
                        <label class="control-label col-sm-3  pull-left" for="sup_id">Item Name : </label>
                        <div class="col-sm-9">
                            <select class="form-control" required="required" name="item_name" id="status" data-live-search="true">
                                <option></option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all("tbl_item_with_price") as $item) {
                                    $i++; ?>
                                    <option data-item="<?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?>" data-price="<?php echo isset($item['item_price']) ? $item['item_price'] : NULL; ?>" value="<?php echo isset($item['item_id']) ? $item['item_id'] : NULL; ?>"><?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?>
                                        - <?php echo isset($item['item_price']) ? $item['item_price'] . ' tk' : NULL; ?></option>
                                    <?php } ?>
                            </select>
                            <b><small class="text-primary" id="do_number_details"></small></b>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 col-md-offset-2">
                    <table class="table" style="margin-bottom:0px;  ">
                        <thead>
                        <tr>
                            <th class="col-md-1 text-center">SL</th>
                            <th class="col-md-3 text-center">Used Product</th>
                            <th class="col-md-1 text-center">Used Qty</th>
                            <th class="col-md-1 text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody id="orderTable">

                        <tr id="row_1"></tr>

                        </tbody>

                        <hr>

                    </table>
<br>
                </div>
            </div>
        </div>

        <div class="col-md-12" style="margin-top:30px;">
            <div class="col-md-2 col-md-offset-5">
                <div class="text-center">
                    <button type="submit" class="btn btn-block btn-success" name="add_sell">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        $('input[name="dalivery_date"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });

        $('select[name="customer_id"]').selectpicker();
        $('select[name="item_name"]').selectpicker();

        var row = 1;
        var crow = 1;

        $('select[name="customer_id"]').on('change', function (e) {
            row = 1;
            $('#orderTable').html('<tr id="row_1"></tr>');
            $('table tr#total_row').addClass('hidden');
        });

        $('form').submit(function (submitEvent) {


            $('#orderTable').each(function () {
                var qtyErrorFlag = true;
                var stockErrorFlag = true;
                var emptyFieldCount = 0;
                var stockErrorCount = 0;

                $(this).find('input#qty').each(function () {
                    if (!$(this).val() || $(this).val() == '0') {
                        qtyErrorFlag = true;
                        emptyFieldCount++;
                    }
                });

                $(this).find('div.noticeTab').each(function () {
                    if ($(this).children().hasClass("alert-danger")) {
                        stockErrorFlag = true;
                        stockErrorCount++;
                    }
                });

                if (qtyErrorFlag == false) {
                    submitEvent.preventDefault();
                    alert('Sorry Must Provide all Qty. ' + emptyFieldCount + ' Qty field is empty.');

                } else if (stockErrorFlag == false) {
                    submitEvent.preventDefault();
                    alert('Sorry Must Clear this  ' + stockErrorCount + ' Stock unavailable error');
                }
            });
        });

        function itemAlreadyExist(itemId) {
            var itemExist = false;

            $('#orderTable').find('tr').each(function () {

                $(this).find('input[name="product_id[]"]').each(function () {
                    if ($(this).val() == itemId) {
                        itemExist = true;
                    }
                });
            });
            return itemExist;
        }

        var total_price_without_less = '';

        function totalPriceCalculate() { // return total price

            var totalPrice = 0;
            $('#orderTable').each(function () {

                $(this).find('input#total').each(function () {
                    if (!!$(this).val()) {
                        totalPrice += parseInt($(this).val());
                    }
                });
            });

            $('input#total_price_without_less').val(totalPrice);
            total_price_without_less = totalPrice;
            return totalPrice - total_less;
        }

        function totalQtyCalculate() { // return total qty
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

        function addRow(message) {

            $("tr#row_" + row).html(message);
            $('#orderTable').append('<tr id="row_' + (row + 1) + '"></tr>');
            row++;
            crow++;
        }

        function showItemDoDetails(itemId) {
            $.get('ajax_action/ajax_check_material_stock.php', {'item': itemId}, function (result) {
                $('small#do_number_details').html('Total Stock Qty is : ' + result.stock_qty + '');
                stockQty = result.stock_qty;
            }, 'json');
        }

        $('select[name="item_name"]').on('change', function (e) { // add new row when new item selected

            var itemName = $(this).find(':selected').data('item');
            var itemPrice = $(this).find(':selected').data('price');
            var itemId = $(this).find(':selected').val();
            if (!itemAlreadyExist(itemId)) {
                showItemDoDetails(itemId);

                addRow('<td class="text-center">' + crow + '</td><td><input readonly type="text" id="item_name" value="' + itemName + '" class="form-control"></td>' +
                    '<input type="hidden" id="product_id" name="product_id[]" value="' + itemId + '" class="form-control">' +
                    '<td><input type="text" onkeypress="return numbersOnly(event)"  id="qty" name="qty[]" class="form-control"></td>' +
                    '<td><a class="delete_row btn btn-danger btn-sm">Remove</a></td>');

                $('table tr#total_row').removeClass('hidden');
            } else {
                alert("This DO Already Exist");
            }

        });

        $("#orderTable").on('click', '.delete_row', function () { // delete entire row when press remove button
            crow=crow-1;
            if (crow==1){
                $('select[name="item_name"]').attr('required','required');
            }else {
                $('select[name="item_name"]').removeAttr('required');
            }

            $('select[name="item_name"]').val('');
            $(this).parent().parent().remove();
            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());

        });

        var stockQty = 0;

        $("#orderTable").on('keyup', 'input#qty', function () { // total price and total field updated while give new qty

            var price = $(this).parent().parent().find('td > input#price').val();
            var total_amount_wd = $(this).parent().parent().find('td > input#total_amount_wd').val();
            var qty = $(this).val();
            if (qty>stockQty){
                alert('Not Enough Quantity')
            }
            var itemId = $(this).parent().parent().find('input#product_id').val();
            var url = 'ajax_action/ajax_check_stock.php';
            var getData = {'item': itemId};
            if (qty) {
                if (qty != '0') {
                    $.get(url, getData, function (data) {
                        if (data.stock_qty >= qty) {
                            $('td div#errrMsg' + itemId).html('<div class="alert alert-success">You Can Proceed</div>');

                        } else {

                            $('td div#errrMsg' + itemId).html('<div class="alert alert-danger"><strong>Sorry! ' + Math.abs(data.stock_qty - qty) + ' pc short</strong></div>');
                        }
                    }, 'json');
                } else {
                    $('td div#errrMsg' + itemId).html('<div class="alert alert-info">Please add Qty</div>');
                }
            }
            var total = price * qty;

            $(this).parent()
                .parent()
                .find('td > input#total')
                .val(total);

            $(this).parent()
                .parent()
                .find('td > input#total_amount_wd')
                .val(total);

            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());
        });

        $("#orderTable").on('keyup', 'input#price', function () {// total price and total field updated while update price
            var price = $(this).val();
            var qty = $(this).parent().parent().find('td > input#qty').val();
            var total_amount_wd = $(this).parent().parent().find('td > input#total_amount_wd').val();
            var total = price * qty;
            $(this).parent()
                .parent()
                .find('td > input#total')
                .val(total);

            $(this).parent()
                .parent()
                .find('td > input#total_amount_wd')
                .val(total);

            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());
        });

        var total_less = '';

        $("#total_row").on('keyup', 'input#less_amount', function () {// total price and total field updated while update price
            total_less = $(this).val();
            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());
        });

        $('tr td input#total_price').on('click', function () {

            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());

        })

        $("#orderTable").on('keyup', 'input#discount', function () {// total discount field updated while update price

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

        $('tr td input#total_price').on('click', function () {

            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());

        })

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

        $('button#retailer_trigger').on('click', function (e) {

            e.preventDefault();

            $('#customer_info').toggle();
            $('#retailer_info').toggle();

            if ($("input[name='customer_id']").prop('disabled') == true) {
                $("input[name='customer_id']").prop("disabled", false);
            } else {
                $("input[name='customer_id']").prop("disabled", true);
            }

            if ($("input[name='retailer_name']").prop('disabled') == true) {
                $("input[name='retailer_name']").prop("disabled", false);
            } else {
                $("input[name='retailer_name']").prop("disabled", true);
            }

            if ($("input[name='retailer_phone']").prop('disabled') == true) {
                $("input[name='retailer_phone']").prop("disabled", false);
            } else {
                $("input[name='retailer_phone']").prop("disabled", true);
            }

            if ($("textarea[name='retailer_address']").prop('disabled') == true) {
                $("textarea[name='retailer_address']").prop("disabled", false);
            } else {
                $("textarea[name='retailer_address']").prop("disabled", true);
            }

            if ($("textarea[name='retailer_comments']").prop('disabled') == true) {
                $("textarea[name='retailer_comments']").prop("disabled", false);
            } else {
                $("textarea[name='retailer_comments']").prop("disabled", true);
            }
        })

    });

</script>
