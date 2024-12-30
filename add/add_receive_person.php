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
    <h4><strong>Add / Receipt From Customer.</strong></h4>
    <h5><strong>Please select any Customer</strong></h5>
</div>

<div class="container" style="padding:10px; font-size: 12px;">
    <div class="col-md-8  col-md-offset-2" style="padding:10px; font-size: 12px;">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <a data-toggle="collapse" data-parent="#accordion"
                           href="#collapse1"><span class="glyphicon glyphicon-arrow-down"></span>Select Customer for Add Receipt </a>
                    </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <form method="get" class="form-horizontal" action="index.php?q=add_payment">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-2">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="customer_id">Customer Id:</label>
                                        <div class="col-sm-9">
                                            <select data-live-search="true"  required="required" name="customer_id" id="status">
                                                <option></option>
                                                <?php
                                                $i = '0';
                                                foreach ($obj->view_all("tbl_customer") as $customer) {
                                                    $i++;
                                                    ?>
                                                    <option
                                                        value="<?php echo isset($customer['cus_id']) ? $customer['cus_id'] : NULL; ?>"><?php echo isset($customer['cus_name']) ? $customer['cus_company'] : NULL; ?>
                                                        -<?php echo isset($customer['cus_id']) ? $customer['cus_id'] : NULL; ?></option>
                                                    <?php } ?>
                                            </select>
                                            <input type="hidden" value="cAd" name="action">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="sup_id">Receipt Action</label>
                                        <div class="col-sm-8">
                                            <select required="required" name="payment_action" >
                                                <option value="receive_payment_from_customer">Received Amount From Customer</option>
                                                <option value="give_payment_to_customer">Give Amount To Customer</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row margin_top_10px ">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-default" value="add_payment" name="q">Select Customer
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