<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

//===================Add Function===================
$total_amount = 0;
$due = 0;

$expense_cat = 1; // for accounts
$incomeId = (isset($_GET['token']) && !empty($_GET['token'])) ? $_GET['token'] : null;

$incomeData = $obj->details_by_cond('tbl_account', "acc_id = '$incomeId'");


if (isset ($_POST['editIncome'])) {
    extract($_POST);

    $form_tbl_accounts = array(
        'acc_head' => (!empty($acc_id)) ? $acc_id : 0,
        'acc_description' => str_replace("'", "", $details),
        'acc_amount' => $amount,
        'update_by' => $userid
    );
    $tbl_accounts_update = $obj->Update_data("tbl_account", $form_tbl_accounts, "acc_id = '$incomeId'");

    if ($tbl_accounts_update) {
        ?>
        <script>
            window.location = "?q=edit_income&token=<?php echo $incomeId; ?>";
        </script>
        <?php
    } else { // $paid_amount <= $total_price
        $notification = '<div class="alert alert-danger">Insert Failed</div>';
    }


}
?>
<!--===================end Function===================-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
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

<div class="col-md-8 col-md-offset-2 bg-grey-800 text-center" style="margin-bottom:15px;">
    <h4>Edit Income</h4>
</div>

<div class="row">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
        <div class="col-md-7 col-md-offset-2">
            <div class="form-group">
                <label class="control-label col-sm-4" for="sup_id">Account Head</label>
                <div class="col-sm-8">
                    <select class="form-control" name="acc_id" id="status">
                        <option></option>
                        <?php
                        $i = '0';
                        foreach ($obj->view_all_by_cond("tbl_ac_head_other_income", "ac_head_or_other_income = 0") as
                                 $acc_head) {
                            $i++;
                            ?>
                            <option
                                    value="<?php echo isset($acc_head['acc_id']) ? $acc_head['acc_id'] : NULL; ?>"
                                <?php echo ($acc_head['acc_id'] == $incomeData['acc_head']) ? 'selected' : ''; ?>><?php echo
                                isset
                                ($acc_head['acc_name']) ?
                                    $acc_head['acc_name'] : NULL;
                                ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="amount">Amount:</label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo isset($incomeData['acc_amount']) ?
                            $incomeData['acc_amount'] : null; ?>" onkeypress="return numbersOnly(event)"
                               required="required"
                               name="amount" class="form-control"
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="details">Expense Details:</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" name="details" id="ResponsiveDetelis"
                                  rows="4"><?php echo isset($incomeData['acc_amount']) ?
                                $incomeData['acc_description'] : null; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row margin_top_10px" style="margin-bottom:20px;">
                <div class="col-md-2 col-md-offset-7">
                    <div class="text-center">
                        <button type="submit" class="btn btn-block btn-success" name="editIncome">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">
    $('select[name="acc_id"]').select2({
        placeholder: "Select Account Head",
        allowClear: true
    });

</script>