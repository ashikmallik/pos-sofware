<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$total_amount = 0;
$due = 0;
$purchase_cat = 3; // for accounts

if(isset($_GET['billId'])){
    $billId     = $_GET['billId'];
    $purchaseId = trim($billId,'p_');
    $prevPaymentData = $obj->details_by_cond("tbl_purchase", "`bill_id` = '$purchaseId'");

}else if(isset($_GET['invoiceId'])){
    $billId     = $_GET['invoiceId'];
    $sellId     = trim($billId,'s_');
    $prevPaymentData = $obj->details_by_cond("tbl_sell", "`sell_id` = '$sellId'");
}else {
    $billId = NULL;
}

$prevAccountData = $obj->details_by_cond("tbl_account", "`purchase_or_sell_id` = '$billId'");

?>


<?php
if (isset($_POST['adjust'])) {
    extract($_POST);
    if($status == 1){
        $amount = $prev_amount + $amount;
    }else{
        $amount = $prev_amount - $amount;
    }

    $adjustmentData = array(         
            'acc_amount' => $amount
        );
    $adjustment_amount = $obj->Update_data("tbl_account", $adjustmentData, "purchase_or_sell_id = '$billId'");

    if (isset($purchaseId)) {
        $due_to_company = $prevPaymentData['total_price'] - $amount;
        $paymentData = array(         
            'payment_recieved' => $amount,
            'due_to_company' => $due_to_company
        );
        $adjustPayment = $obj->Update_data("tbl_purchase", $paymentData, "bill_id = '$purchaseId'");
    }

    if (isset($sellId)) {
        $due_to_company = $prevPaymentData['total_price'] - $amount;
        $paymentData = array(         
            'payment_recieved' => $amount,
            'due_to_company' => $due_to_company
        );
        $adjustPayment = $obj->Update_data("tbl_sell", $paymentData, "sell_id = '$sellId'");
    }

    if ($adjustPayment) {
        $notification = '<div class="alert alert-success">Adjusted Successfully</div>';

        ?>
        <?php
    } else {
        $notification = '<div class="alert alert-danger">Failed to Adjust</div>';
    }
}
?>
<!--===================end Function===================-->
<style>
    .delete_row {
        margin-top: 3px;
    }


    .radio-inline{
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .radio-inline input{
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .checkmark {
      position: absolute;
      top: 0;
      left: 0;
      height: 25px;
      width: 25px;
      color:#4e4747;
      border-radius: 50%;
  }

  .radio-inline:hover input ~ .checkmark {
      color: #1984a1;;
  }

  .radio-inline input:checked ~ .checkmark {
  color: #2196F3;
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
    <h4>Welcome to Adjustment Page</h4>
</div>

<div class="row" style="padding:10px; font-size: 12px;">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-10  col-md-offset-1" style="font-size: 12px;">

            <div class="col-md-9" style="margin-top:15px;margin-bottom:10px;">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="prev_amount">Previous Amount:</label>
                    <div class="col-sm-8">
                        <input class="form-control" onkeypress="return numbersOnly(event)" type="text" name="prev_amount" value="<?php echo $prevAccountData['acc_amount']; ?>" readonly/>
                    </div>
                </div>
            </div>

            <div class="col-md-9" style="margin-top:15px;margin-bottom:10px;">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="amount">Adjustment Amount:</label>
                    <div class="col-sm-8">
                        <input class="form-control" onkeypress="return numbersOnly(event)" type="text" name="amount" placeholder="Amount" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10">
                    <label class="control-label col-sm-4" for="status">Adjustment Type:</label>

                    <label class="radio-inline">
                      <input type="radio" name="status" value="1" required="required"> <span class="checkmark glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="status" value="0" required="required"> <span class="checkmark glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
                </label>
            </div>


        </div>

        <div class="col-md-12" style="margin-top:30px;">
            <div class="col-md-2 col-md-offset-5">
                <div class="text-center">
                    <button type="submit" class="btn btn-block btn-success" name="adjust">Adjust</button>
                </div>
            </div>
        </div>
    </div>
</form>
</div>


<script type="text/javascript">
   

</script>