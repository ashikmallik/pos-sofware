<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$total_amount = 0;
$due = 0;
$purchase_cat = 3; // for accounts

$billId = isset($_GET['billId']) ? $_GET['billId'] : null;

$purchaseData = $obj->details_by_cond('tbl_purchase', "bill_id = $billId");
$purchaseItemData = $obj->view_all_by_cond('vw_purchase_item', 'bill_id = ' . $purchaseData['bill_id'] . ' ORDER BY `vw_purchase_item`.`id` ASC');

$paymentReceived = floatval($purchaseData['payment_recieved']);

if (isset($_POST['update_purchase'])) {
    extract($_POST);
    $i = 0;
    $purchaseArray = array();
    $total_amount = 0;
    $total_qty = 0;
    foreach ($product_id as $id) {

        $item_price = !empty($price[$i]) ? $price[$i] : 0;
        $total_qty += $item_qty = !empty($qty[$i]) ? $qty[$i] : 0;
        $total_amount += $total = $item_price * $item_qty;
        $purchaseArray[$i] = array(
            'product_id' => $id,
            'price' => $item_price,
            'qty' => $item_qty,
            'total' => $total,
        );
        $i++;
    }
    $form_purchase_update = array(

        'supplier' => $supplier_id,
        'total_price' => $total_amount,
        'total_qty' => $total_qty,
        'due_to_company' => ($total_amount-$paymentReceived),
        'last_update' => date('Y-m-d'),
        'update_by' => $userid

    );
    $bill_id = $obj->Update_data("tbl_purchase", $form_purchase_update, "bill_id = $billId");

    $form_account_update = array(
        'acc_amount' => $total_amount,
    );
    $account_update = $obj->Update_data("tbl_account", $form_account_update, "acc_type=22 AND purchase_or_sell_id='p_$billId'");

    $purchase_item_prevData = array();
    $purchase_item_data  = $obj->raw_sql('product_id FROM tbl_purchase_item WHERE bill_id ='.$billId);

    

    foreach ($purchaseArray as $singlePurchase) {
        $productId = $singlePurchase['product_id'];

        $checkPurchaseItem  = $obj->details_by_cond('tbl_purchase_item', "bill_id = $billId AND product_id=".$productId);

        if(!$checkPurchaseItem){
            $form_purchase_item = array(
                'supplier' => $supplier_id,
                'bill_id' => $billId,
                'product_id' => $productId,
                'price' => $singlePurchase['price'],
                'qty' => $singlePurchase['qty'],
                'total_amount' => $singlePurchase['total'],
                'status' => 1,
                'update_date' => date('Y-m-d'),
                'update_by' => $userid
            );
            $purchase_item_id = $obj->insert_by_condition("tbl_purchase_item", $form_purchase_item, " ");
            if ($purchase_item_id) {
                $form_purchase_qty_print = array(
                    'purchase_item_id' => $purchase_item_id,
                    'bill_id' => $billId,
                    'product_id' => $productId,
                    'price' => $singlePurchase['price'],
                );
                $obj->insert_by_condition("tbl_purchase_qty_print", $form_purchase_qty_print, " ");
            }
        }else{
            $form_purchase_item_update = array(
                'supplier' => $supplier_id,
                'price' => $singlePurchase['price'],
                'qty' => $singlePurchase['qty'],
                'total_amount' => $singlePurchase['total'],
                'status' => 1,
                'update_date' => date('Y-m-d'),
                'update_by' => $userid
            );
            $purchase_item_id = $obj->Update_data("tbl_purchase_item", $form_purchase_item_update, "bill_id=$billId AND product_id=".$productId);
        }
        
    }

    foreach ($purchase_item_data as $p_item) {
        array_push($purchase_item_prevData, $p_item['product_id']);
    }
    $purchase_item_newData= array();

    foreach ($purchaseArray as $purchase_itmData) {
        array_push($purchase_item_newData, $purchase_itmData['product_id']);
    }
    $delete_itemData = (array_diff($purchase_item_prevData, $purchase_item_newData));
    $k=0;
    foreach ($delete_itemData as $deleteData) {
        $delteItem = $obj->Delete_data('tbl_purchase_item',"bill_id=$billId AND product_id=".$deleteData[$k]);
        //$delteItemPrint = $obj->Delete_data('tbl_purchase_qty_print',"bill_id=$billId AND product_id=".$deleteData[$k]);
    }

    if ($purchase_item_id) {
        ?>
        <script>
            window.location = "?q=edit_purchase&billId=<?php echo $billId?>";
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
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    padding: 8px 1px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
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
    <h4>Welcome to Edit Purchase Item Page</h4>
</div>

<div class="row" style="padding:10px; font-size: 12px;">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-10  col-md-offset-1" style="font-size: 12px;">

            <div class="col-md-9" style="margin-top:15px;margin-bottom:10px;">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="sup_id">Supplier Id:</label>
                    <div class="col-sm-8">
                        <select class="form-control" required="required" name="supplier_id" id="status">
                            <option></option>
                            <?php
                            $i = '0';
                            foreach ($obj->view_all("tbl_supplier") as $supplier) {
                                $i++;
                                ?>
                                <option value="<?php echo isset($supplier['supplier_id']) ? $supplier['supplier_id'] : NULL; ?>" <?php echo ($purchaseData['supplier'] == $supplier['supplier_id']) ? 'selected' : ''; ?>><?php echo isset($supplier['supplier_name']) ? $supplier['supplier_name'] : NULL; ?>
                                - <?php echo isset($supplier['supplier_id']) ? $supplier['supplier_id'] : NULL; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <label class="control-label col-sm-3  pull-left" for="sup_id">Item Name : </label>
                        <div class="col-sm-9">
                            <select class="form-control" data-live-search="true" name="item_name" id="status">
                                <option></option>
                                <?php
                                $i = '0';
                                foreach ($obj->view_all("tbl_item_with_price") as $item) {
                                    $i++;
                                    ?>
                                    <option data-item="<?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?>"
                                        data-price="<?php echo isset($item['item_price']) ? $item['item_price'] : NULL; ?>"
                                        value="<?php echo isset($item['item_id']) ? $item['item_id'] : NULL; ?>"><?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?> - <?php echo isset($item['item_price']) ? $item['item_price'] . ' tk' : NULL; ?></option>
                                        <?php
                                    }
                                    ?>
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
                                    <th class="col-md-2 text-center">Unite Price</th>
                                    <th class="col-md-2 text-center">Qty</th>
                                    <th class="col-md-2 text-center">Total</th>
                                    <th class="col-md-2 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="orderTable">
                                <?php
                                $rowNum = 0;
                                $total_amount = 0;
                                foreach ($purchaseItemData as $purchaseItem) {
                                    $rowNum++;
                                    $itemNewPrice = isset($purchaseItem['total_amount']) ? $purchaseItem['total_amount'] : 0;
                                    $itemNewQty = isset($purchaseItem['qty']) ? $purchaseItem['qty'] : 0;
                                    $purchasePrice = $itemNewPrice / $itemNewQty;
                                    ?>
                                    <tr id="row_1">
                                        <td>
                                            <?php echo $rowNum; ?>
                                        </td>
                                        <td>
                                            <input value="<?php echo isset($purchaseItem['product_name']) ? $purchaseItem['product_name'] : null; ?>"
                                            class="form-control" type="text">
                                        </td>
                                        <input name="product_id[]" value="<?php echo $purchaseItem['product_id'] ?>"
                                        class="form-control" type="hidden">
                                        <td>
                                            <input onkeypress="return numbersOnly(event)" id="price" name="price[]"
                                            value="<?= $purchasePrice;?>"
                                            class="form-control" type="text">
                                        </td>
                                        <td>
                                            <input onkeypress="return numbersOnly(event)" id="qty" name="qty[]"
                                            value="<?php echo $purchaseQty = isset($purchaseItem['qty']) ? $purchaseItem['qty'] : 0; ?>"
                                            class="form-control" type="text">
                                        </td>
                                        <td>
                                            <input readonly="" onkeypress="return numbersOnly(event)" id="total"
                                            value="<?php echo($purchasePrice * $purchaseQty) ?>" name="total[]"
                                            class="form-control" type="text">
                                        </td>
                                        <?php $total_amount += ($purchasePrice * $purchaseQty); ?>
                                        <td>
                                            <a class="delete_row btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this Purchase item?');">Remove</a>
                                        </td>
                                    </tr>
                                <?php }

                                echo '<tr id="row_' . ($rowNum + 1) . '"></tr>';
                                ?>
                            </tbody>
                            <hr>
                            <tr id="total_row">
                                <td colspan="4" class="text-center" style="padding-right: 0px !important;">
                                    <div class="col-sm-12">
                                        <div class="col-md-3 pull-right">
                                            <h4 style="margin-top:4px"> Total Taka </h4>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center"><input id="total_price" value="<?php echo $total_amount; ?>"
                                 type="text" class="form-control"></td>
                                 <td><h4 style="margin-top:0px">Taka</h4></td>
                             </tr>
                         </table>

                     </div>
                 </div>
             </div>

             <div class="col-md-12" style="margin-top:30px;">
                <div class="col-md-2 col-md-offset-5">
                    <div class="text-center">
                        <button type="submit" class="btn btn-block btn-success" name="update_purchase">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('select[name="supplier_id"]').selectpicker();
        $('select[name="item_name"]').selectpicker();

        $('form').submit(function (submitEvent) {

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

        function totalPriceCalculate() { // return total price
            var totalPrice = 0;
            $('#orderTable').each(function () {

                $(this).find('input#total').each(function () {
                    if (!!$(this).val()) {
                        totalPrice += parseFloat($(this).val());
                    }
                });
            });
            totalPrice = totalPrice.toFixed(6);
            return totalPrice;
        }


        var row = <?php echo($rowNum + 1); ?>;

        function addRow(message) {
            $("tr#row_" + row).html(message);
            $('#orderTable').append('<tr id="row_' + (row + 1) + '"></tr>');
            row++;
        }

        $('select[name="item_name"]').on('change', function (e) { // add new row when new item selected

            var itemName = $(this).find(':selected').data('item');
            var itemPrice = $(this).find(':selected').data('price');
            var itemId = $(this).find(':selected').val();

            addRow('<td>' + row + '</td><td><input type="text" value="' + itemName + '" class="form-control"></td>' +
                '<input type="hidden" name="product_id[]" value="' + itemId + '" class="form-control">' +
                '<td><input type="text" onkeypress="return numbersOnly(event)"  id="price" name="price[]" value="' + itemPrice + '" class="form-control"></td>' +
                '<td><input type="text" onkeypress="return numbersOnly(event)"  id="qty" name="qty[]" class="form-control"></td>' +
                '<td><input type="text" readonly onkeypress="return numbersOnly(event)"   id="total" name="total[]" class="form-control"></td>' +
                '<td><a class="delete_row btn btn-danger btn-sm">Remove</a></td>');


        });

        $("#orderTable").on('click', '.delete_row', function () { // delete entire row when press remove button
            $('select[name="item_name"]').val('');
            $(this).parent().parent().remove();
            $('input#total_price').val(totalPriceCalculate());
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