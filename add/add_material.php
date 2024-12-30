<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

//===================Add Function===================
$total_amount = 0;
$due = 0;
$purchase_cat = 2; // for accounts
$purchase_product_from_supplier = 22;

if (isset($_POST['add_purchase'])) {

    extract($_POST);
    $i = 0;
    $purchaseArray = array();
    $total_amount = 0;
    $total_qty = 0;
    $payment_method_type = 1;

    foreach ($product_id as $id) { // at first configure / arrange the array for db insert

        $item_price = !empty($price[$i]) ? $price[$i] : 0;

        $item_qty = !empty($qty[$i]) ? $qty[$i] : 0;
        $total_qty += $item_qty;

        if (isset($commision) && !empty($commision)) {
            // if user give commission in total price, the single price will be affected
            $commisionPercentage = ($commision * 100) / ($total_price + $commision);

            if (isset($less)&& !empty($less)) {
                $less_unit = ($less * 100) / $total_price_amount;
                //echo ', '.$commision;
                $item_price_u = $item_price - $item_price * ($commision / 100);
                $item_price = $item_price_u - ($item_price_u * $less_unit / 100);
            }else{
                $item_price = $item_price - (($item_price * $commision) / 100);
            }
        }

        $total_amount += $total = $item_price * $item_qty;

        $purchaseArray[$i] = array(
            'product_id' => $id,
            'price' => $item_price,
            'given_price' => !empty($price[$i]) ? $price[$i] : 0, // price in the form for print purpose
            'qty' => $item_qty,
            'total' => $total,
        );
        $i++;
    }

    if (isset($less) && !empty($less)){
        $total_amount = $total_amount;
    }

    $paid_amount = !empty($paid_amount) ? $paid_amount : 0;

    $due = $total_amount - $paid_amount;

    $form_purchase_add = array( // array for purchase table that id will be used for purchase item

        'supplier' => $sup_id,
        'total_price' => $total_amount,
        'less_amount' => $less,
        'payment_recieved' => $paid_amount,
        'due_to_company' => $due,
        'material' => 1,
        'total_qty' => $total_qty,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    $purchase_id = $obj->insert_by_condition("tbl_purchase", $form_purchase_add, " ");

    $supplier_data = $obj->details_by_cond("tbl_supplier", "supplier_id = '$sup_id'");
    $supplier_name = $supplier_data['supplier_name'];

    $form_tbl_product_accounts = array(
        'acc_description' => "Total Amount of Purchase Product Form Supplier(Bill) " . $supplier_name . " (" . $sup_id . ") And Purchase id : $purchase_id",
        'acc_amount' => $total_amount,
        'acc_type' => $purchase_product_from_supplier,
        'purchase_or_sell_id' => 'p_' . $purchase_id,
        'cus_or_sup_id' => $sup_id,
        'acc_head' => 0,
        'payment_method' => $payment_method_type,
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    $tbl_product_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_product_accounts, " ");

    if (!empty($paid_amount) && ($paid_amount != 0)) { // if paid amount is filled then account part will work

        if ($payment_method == 'bank') {
            $payment_method_type = 0;
            $total_balance_Data = $obj->details_by_cond('bank_account', "`account_id` != 0  ORDER BY `bank_account`.`account_id` DESC");
            $total_balance = $total_balance_Data['balance'];
            $form_data_for_bank = array(
                'account_no' => $account_no,
                'chq_no' => $_POST['chq_no'],
                'description' => "Company give payment to Supplier " . $supplier_name . " (" . $sup_id . ") And Purchase id : $purchase_id",
                'credit' => $paid_amount,
                'debit' => 0,
                'balance' => ($total_balance - $paid_amount),
                'withdraw_by' => (empty($withdraw_by) ? $supplier_name : $withdraw_by),
                'entry_by' => $userid,
                'entry_date' => date('Y-m-d'),
                'update_by' => $userid
            );
            $bank_data_add = $obj->insert_by_condition("bank_account", $form_data_for_bank, " ");
        }

        $form_tbl_accounts = array(
            'acc_description' => "Company give payment to Supplier " . $supplier_name . " (" . $sup_id . ") And Purchase id : $purchase_id",
            'acc_amount' => $paid_amount,
            'acc_type' => $purchase_cat,
            'purchase_or_sell_id' => 'p_' . $purchase_id,
            'cus_or_sup_id' => $sup_id,
            'acc_head' => 0,
            'payment_method' => $payment_method_type,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    }

    if ($purchase_id) { // per item saved one by one in purchase item database table
        foreach ($purchaseArray as $singlePurchase) {

            $form_purchase_item = array(
                'bill_id' => $purchase_id,
                'supplier' => $sup_id,
                'product_id' => $singlePurchase['product_id'],
                'price' => $singlePurchase['price'],
                'qty' => $singlePurchase['qty'],
                'total_amount' => $singlePurchase['total'],
                'status' => 1,
                'material' => 1,
                'update_date' => date('Y-m-d'),
                'update_by' => $userid
            );
            $purchase_item_add = $obj->insert_by_condition("tbl_purchase_item", $form_purchase_item, " ");

            if ($purchase_item_add) {
                $form_purchase_qty_print = array(// 	purchase_item_id 	bill_id 	product_id 	price

                    'purchase_item_id' => $purchase_item_add,
                    'bill_id' => $purchase_id,
                    'product_id' => $singlePurchase['product_id'],
                    'price' => $singlePurchase['given_price'],
                );

                if ($obj->insert_by_condition("tbl_purchase_qty_print", $form_purchase_qty_print, " ")) {

                    $obj->notificationStore(' Purchased Saved Successfully', 'success');
                    ?>
                    <script>
                        window.location = "?q=add_material";
                        window.open('pdf/bill.php?billId=<?php echo $purchase_id ?>', '_blank');
                    </script>
                    <?php
                } else {
                    $obj->notificationStore(' Sorry Purchase Saving Failed ');
                }
            }
        }
    }
} ?>
<style>
    .delete_row {  margin-top: 3px;  }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<!-- Latest compiled and minified JavaScript -->
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
    <b><?php echo isset($notification) ? $notification : NULL;
    $obj->notificationShowRedirect();
    $obj->notificationShow(); ?></b>
</div>
<span style="color: red; font-size: 20px;text-align:left;"></span>

<div class="col-md-12 bg-slate-800 text-center" style="margin-bottom:5px;">
    <h4>Welcome to Purchase New Material</h4>
</div>

<div class="row" style="padding:10px; font-size: 12px;">
    <form id="purchase_form" class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-10  col-md-offset-1" style="font-size: 12px;">

            <div class="col-md-9" style="margin-top:15px;margin-bottom:10px;">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="sup_id">Supplier Id:</label>
                    <div class="col-sm-8">
                        <select class="form-control" required="required" name="sup_id" id="status" data-live-search="true">
                            <option></option>
                            <?php
                            $i = '0';
                            foreach ($obj->view_all("tbl_supplier") as $supplier) {
                                $i++;
                                ?><option value="<?php echo isset($supplier['supplier_id']) ? $supplier['supplier_id'] : NULL; ?>"><?php echo $supplier['supplier_company'];?> - <?php echo isset($supplier['supplier_id']) ? $supplier['supplier_id'] : NULL; ?> - (<?php echo isset($supplier['supplier_name']) ? $supplier['supplier_name'] : NULL; ?>)</option>
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
                            <select class="form-control" data-live-search="true" required="required" name="item_name" id="status">
                                <option></option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all("tbl_item_with_price") as $item) {
                                    $i++; ?><option data-item="<?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?>" data-price="<?php echo isset($item['item_price']) ? $item['item_price'] : NULL; ?>" value="<?php echo isset($item['item_id']) ? $item['item_id'] : NULL; ?>"><?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?> - <?php echo isset($item['item_price']) ? $item['item_price'] . ' tk' : NULL; ?></option>
                                    <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <table class="table" style="margin-bottom:0px;  ">
                        <thead>
                        <tr>
                            <th class="col-md-1 text-center">SL</th>
                            <th class="col-md-3 text-center">Product</th>
                            <th class="col-md-2 text-center">Qty</th>
                            <th class="col-md-2 text-center">Unite Price</th>
                            <th class="col-md-2 text-center">Total</th>
                            <th class="col-md-2 text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody id="orderTable">
                        <tr id="row_1"></tr>
                        </tbody>
                        <hr>
                        <tfoot>
                            <tr id="total_row" class="hidden">
                                <td class="text-center" style="padding-right: 0px !important;">
                                    <h4 class="pull-right">Total Qty : </h4>
                                </td>
                                <td class="text-center">
                                    <input id="total_quantity" readonly type="text" class="form-control">
                                </td>
                                <td class="text-center"><h4>Total Price :</h4></td>
                                <td class="text-center">
                                    <input id="total_price" name="total_price" readonly type="text" class="form-control">
                                    <input id="total_price_amount" name="total_price_amount" type="hidden" class="form-control">
                                </td>

                                <td>
                                    <input id="commision" name="commision" placeholder="Commission %" type="text" class="form-control border-slate-800">
                                </td>
                                <td><input id="less" name="less" placeholder="Less" type="text" class="form-control border-slate-800"></td>
                            </tr>
                            <tr id="total_row_less" class="hidden">

                                <td colspan="6">
                                    <div class="col-sm-12">
                                        <div class="col-md-3">
                                            <h4 style="margin-top:4px">Payment Amount </h4>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <select class="btn btn-default" name="payment_method">
                                                        <option value="cash" selected="selected"> Cash</option>
                                                        <option value="bank"> Bank</option>
                                                    </select>
                                                </div><!-- btn-group -->
                                                <input type="text" style="height:32px" onkeypress="return numbersOnly(event)" name="paid_amount" class="form-control">
                                                <span class="input-group-addon">TK</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <div id="bank_info" style="display: none;" class="col-md-12 pull-right">
                        <div class="col-md-7">
                            <label class="control-label col-sm-4" for="damageRate">Bank Account</label>
                            <div class="col-sm-8">
                                <select class="form-control" disabled required="required" name="account_no" id="status">
                                    <option></option>
                                    <?php
                                    $i = '0';
                                    foreach ($obj->view_all("bank_registration") as $value) {
                                        $i++;
                                        ?>
                                        <option value="<?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>"><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?>
                                            - <?php echo isset($value['bank_name']) ? $value['bank_name'] : NULL; ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5 form-group">
                            <label class="control-label col-sm-5">Check No</label>
                            <div class="col-sm-7">
                            <input type="text" name="chq_no" placeholder="Check No" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-7">
                            <label class="control-label col-sm-4" for="diposited_by">Depositor Name</label>
                            <div class="col-sm-8">
                                <input type="text" disabled name="diposited_by" placeholder="Default take Supplier name" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12" style="margin-top:30px;">
            <div class="col-md-2 col-md-offset-5">
                <div class="text-center">
                    <button type="submit" class="btn btn-block btn-success" name="add_purchase">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('select[name="sup_id"]').selectpicker();
        $('select[name="item_name"]').selectpicker();

        var row = 1;
        var crow = 1;

        $('form#purchase_form').submit(function (submitEvent) {

            $('#orderTable').each(function () {
                var flag = true;
                var emptyfieldcount = 0;
                $(this).find('input#qty').each(function () {

                    if (!$(this).val() || $(this).val() == '0') {
                        flag = false;
                        emptyfieldcount++;
                    }
                });

                if (flag == false) {
                    submitEvent.preventDefault();
                    alert('Sorry Must Provide all Qty. ' + emptyfieldcount + ' Qty field is empty.')
                }
            });
        });

        var total_price_amount = '';

        function totalPriceCalculate() { // return total price
            var totalPrice = 0;
            $('#orderTable').each(function () {
                $(this).find('input#total').each(function () {
                    if (!!$(this).val()) {
                        totalPrice += parseInt($(this).val());
                    }
                });
            });

            var commision = ($('input#commision').val()) ? $('input#commision').val() : 0;
            var parcent_amount = commision *totalPrice / 100;

            var less_amount = ($('input#less').val()) ? $('input#less').val() : 0;
            total_price_amount = totalPrice - parcent_amount;
            return totalPrice - parcent_amount - less_amount;
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

        $('select[name="item_name"]').on('change', function (e) { // add new row when new item selected

            var itemName = $(this).find(':selected').data('item');
            var itemPrice = $(this).find(':selected').data('price');
            var itemId = $(this).find(':selected').val();

            if (itemId) {
                addRow('<td>' + crow + '</td><td><input type="text" value="' + itemName + '" class="form-control"></td>' +
                    '<input type="hidden" name="product_id[]" value="' + itemId + '" class="form-control">' +
                    '<td><input type="text" onkeypress="return numbersOnly(event)"  id="qty" name="qty[]" class="form-control"></td>' +
                    '<td><input type="text" onkeypress="return numbersOnly(event)"  id="price" name="price[]" value="' + itemPrice + '" class="form-control"></td>' +
                    '<td><input type="text" readonly onkeypress="return numbersOnly(event)"   id="total" name="total[]" class="form-control"></td>' +
                    '<td><a class="delete_row btn btn-danger btn-sm">Remove</a></td>');

                $('table tr#total_row').removeClass('hidden');
                $('table tr#total_row_less').removeClass('hidden');
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

        $("#orderTable").on('keyup', 'input#qty', function () { // total price and total field updated while give new qty

            var price = $(this).parent().parent().find('td > input#price').val();
            var qty = $(this).val();
            var total = price * qty;

            $(this).parent()
                .parent()
                .find('td > input#total')
                .val(total);

            $('input#total_price').val(totalPriceCalculate());
            $('input#total_quantity').val(totalQtyCalculate());
        });


        $("table.table").on('keyup', 'input#commision', function () {

            $('input#total_price').val(totalPriceCalculate());
            $('input#total_price_amount').val(total_price_amount);
        });

        $("table.table").on('keyup', 'input#less', function () {

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
    });

</script>
