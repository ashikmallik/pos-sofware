<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<div class="col-md-8 col-md-offset-2 bg-slate-800 text-center" style="margin-bottom:5px;">
    <h4><strong>Add / Recieve Payment to Supplier.</strong></h4>
    <h5><strong>Please select any Supplier</strong></h5>
</div>

<div class="container" style="padding:10px; font-size: 12px;">
    <div class="col-md-8  col-md-offset-2" style="padding:10px; font-size: 12px;">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><span class="glyphicon
                        glyphicon-arrow-down"></span>Select Supplier for Add payment</a>
                    </h4>
                </div>
                <div id="collapse2" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <form method="get" class="form-horizontal" action="?q=add_payment">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-2">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="sup_id">Suplier Id:</label>
                                        <div class="col-sm-8">
                                            <select data-live-search="true"  required="required" name="sup_id" id="status">
                                                <option></option>
                                                <?php
                                                $i = '0';
                                                foreach ($obj->view_all("tbl_supplier") as $supplier) {
                                                    $i++;
                                                    ?>
                                                    <option
                                                        value="<?php echo isset($supplier['supplier_id']) ? $supplier['supplier_id'] : NULL; ?>"><?php echo isset($supplier['supplier_name']) ? $supplier['supplier_company'] : NULL; ?>
                                                        -<?php echo isset($supplier['supplier_id']) ? $supplier['supplier_id'] : NULL; ?></option>
                                                    <?php } ?>
                                            </select>
                                            <input type="hidden" value="sAd" name="action">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="sup_id">Payment Action</label>
                                        <div class="col-sm-8">
                                            <select required="required" name="payment_action" >
                                                <option value="give_payment_to_supplier">Give Payment To Supplier</option>
                                                <option value="receive_payment_from_supplier">Received Amount From Supplier</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row margin_top_10px ">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-default" value="add_payment"
                                            name="q">Select Supplier
                                    </button>
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

    $('select[name="customer_id"]').selectpicker();

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

</script>