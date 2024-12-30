<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$purchase_items = $obj->view_all('tbl_item_with_price');
$suppliers = $obj->view_all('tbl_supplier');

if (isset($_POST['return_purchase'])){

    $form_purchase_return = array(
            'purchase_item_id' => '',
            'return_qty' => $_POST['qty'],
            'product_id' => $_POST['product'],
            'cus_or_sup_id' => $_POST['supplier'],
            'return_price' => $_POST['price'],
            'total_return_price' => $_POST['qty']*$_POST['price'],
            'type' => 1,
            'date' => $date
    );

    $obj->insert_by_condition('tbl_return',$form_purchase_return,"");

        $acc_amount = $_POST['price']*$_POST['qty'];

        $form_acc_return=array(
            'acc_amount' => $acc_amount,
            'acc_description'=> 'Purchase Return : '.$_POST['description'],
            'acc_type' => 27,
            'cus_or_sup_id' => $_POST['supplier'],
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $obj->insert_by_condition('tbl_account',$form_acc_return,"");

    ?>
    <script>
        window.location = "?q=return_purchase";
    </script>
<?php } ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<div class="container" style="padding:10px; font-size: 12px;">
    <div class="col-md-8  col-md-offset-2" style="padding:10px; font-size: 12px;">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <a href="#"><span class="glyphicon glyphicon-repeat"></span> Return Purchase</a>
                    </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <form method="post" class="form-horizontal" action="?q=return_purchase">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-2">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="product">Select Product:</label>
                                        <div class="col-sm-9">
                                            <select data-live-search="true"  required="required" name="product" id="status">
                                                <option></option>
                                                <?php $i = '0';
                                                foreach ($purchase_items as $item) { $i++; ?>
                                                    <option value="<?php echo isset($item['item_id']) ? $item['item_id'] : NULL; ?>"><?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?></option>
                                                    <?php } ?>
                                            </select>
                                            <b><small class="text-primary" id="do_number_details"></small></b>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="supplier">Select Supplier:</label>
                                        <div class="col-sm-9">
                                            <select data-live-search="true"  required="required" name="supplier" id="status">
                                                <option></option>
                                                <?php $i = '0';
                                                foreach ($suppliers as $supplier) { $i++; ?>
                                                    <option value="<?php echo isset($supplier['supplier_id']) ? $supplier['supplier_id'] : NULL; ?>"><?php echo isset($supplier['supplier_name']) ? $supplier['supplier_name'] : NULL; ?></option>
                                                <?php } ?>
                                            </select>
                                            <b><small class="text-primary" id="total_purchase"></small></b>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="qty">Return Qty</label>
                                        <div class="col-sm-5">
                                            <input type="number" name="qty" step="0.1" required class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="price">Return Price</label>
                                        <div class="col-sm-5">
                                            <input type="number" name="price"  step="0.1" placeholder="Price Per Unit" required class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="description">Description</label>
                                        <div class="col-sm-5">
                                            <textarea class="form-control" name="description"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row margin_top_10px ">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-default" name="return_purchase"> Return</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">
    $('select[name="sup_id"]').selectpicker();
    $('select[name="product"]').selectpicker();
    $('select[name="supplier"]').selectpicker();
    $('select[name="payment_action"]').selectpicker();

    $('input[name="total_egg"]').focus(function () {
        var redA = ($('input[name="redA"]').val()) ? parseInt($('input[name="redA"]').val()) : parseInt(0);
        var redB = ($('input[name="redB"]').val()) ? parseInt($('input[name="redB"]').val()) : parseInt(0);
        var redC = ($('input[name="redC"]').val()) ? parseInt($('input[name="redC"]').val()) : parseInt(0);
        var whiteA = ($('input[name="whiteA"]').val()) ? parseInt($('input[name="whiteA"]').val()) : parseInt(0);
        var whiteB = ($('input[name="whiteB"]').val()) ? parseInt($('input[name="whiteB"]').val()) : parseInt(0);
        var whiteC = ($('input[name="whiteC"]').val()) ? parseInt($('input[name="whiteC"]').val()) : parseInt(0);

        var totalEgg = redA + redB + redC + whiteA + whiteB + whiteC;

        $('input[name="total_egg"]').val(totalEgg);
    });

    function showTotalPurchase(itemId, supplier) {
        $.get('ajax_action/ajax_check_supplier_purchase.php', {'item': itemId, 'supplier':supplier}, function (result) {
            $('small#total_purchase').html('Total Purchase from Supplier : ' + result.total_purchase + '');

        }, 'json');
    }

    var stock = 0;
    function showItemDoDetails(itemId) {
        $.get('ajax_action/ajax_check_stock.php', {'item': itemId}, function (result) {
            $('small#do_number_details').html('Total Stock Qty is : ' + result.stock_qty + '');
            stock = result.stock_qty;
        }, 'json');
    }

    var itemId= 0;
    $('select[name="product"]').on('change', function (e) {
        itemId = $(this).val();
        $('#do_number_details').attr('class', 'text-primary');
        showItemDoDetails(itemId);
    })

    $('select[name="supplier"]').on('change', function (e) {
        var supplier = $(this).val();
        $('#do_number_details').attr('class', 'text-primary');
        showTotalPurchase(itemId, supplier);
    })

</script>