<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$allCustomerSecurityMoneyGiven = $obj->view_selected_field_by_cond_left_join("tbl_security_money_transaction", 'tbl_customer', 'customer_id', 'id', 'SUM(tbl_security_money_transaction.amount) as total_given', '*', 'tbl_security_money_transaction.pay_receive = 1 AND tbl_security_money_transaction.customer_id != 0 GROUP BY tbl_security_money_transaction.`customer_id`');
// all customer who gave money to company
$pay_type = 0;
$receive_type = 1;

$company_back_s_money_to_customer = $obj->getAccTypeId('company_back_s_money_to_customer');


if (isset($_POST['submit_money_back'])) {

    $customerSecurityMoneyGiven = $obj->details_selected_field_by_cond('tbl_security_money_transaction', 'SUM(amount) as total_given_amount', 'pay_receive = 1 AND customer_id = ' . $_POST['customer_id']);
    $customerSecurityMoneyBacked = $obj->details_selected_field_by_cond('tbl_security_money_transaction', 'SUM(amount) as total_back_amount', 'pay_receive = 0 AND customer_id = ' . $_POST['customer_id']);

    $customerSecurityMoney = intval($customerSecurityMoneyGiven['total_given_amount']) + intval($customerSecurityMoneyBacked['total_back_amount']);

    if ($_POST['s_money_back'] > $customerSecurityMoney) {

        $obj->notificationStore('Security money backed amount exceed from existing amount');
        echo '<script>window.location.href=window.location.href;</script>';

    } else {

        $customer_data = $obj->details_by_cond("tbl_customer", "id = " . $_POST['customer_id'] . "");

        $form_tbl_accounts = array(
            'acc_description' => "Company backed Security Money  to customer - " . $customer_data['cus_name'] .  ". ".$_POST['description'],
            'acc_amount' => $_POST['s_money_back'],
            'payment_method' => 1,
            'acc_type' => $company_back_s_money_to_customer,
            'purchase_or_sell_id' => 0,
            'cus_or_sup_id' => $customer_data['cus_id'],
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );
        $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");

        $form_tbl_security_money_transaction = array(
            'customer_id' => $_POST['customer_id'],
            'supplier_id' => 0,
            'amount' => $_POST['s_money_back'],
            'pay_receive' => $pay_type,
            'accounts_id' => $tbl_accounts_add,
            'description' => $_POST['s_money_back'] . 'tk back to Customer from company',
            'created_at' => date('Y-m-d'),
        );

        if ($obj->insert_by_condition("tbl_security_money_transaction", $form_tbl_security_money_transaction, " ")) {

            $obj->notificationStore('Security Money Backed to Customer Successfully ', 'success');
            echo '<script>window.location.href=window.location.href;</script>';
        } else {

            $obj->notificationStore('Security Money Backed to Customer Failed ');
            echo '<script>window.location.href=window.location.href;</script>';
        }
    }
}

?>

<div class="row">
    <div class="col-md-12 padding_5_px">
        <?php $obj->notificationShowRedirect(); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 bg-teal-800">
        <h4>View Customer's Security Money Received by Company</h4>
    </div>
</div>
<hr>
<div class="row" style="font-size:12px">
    <div class="col-md-12">
        <table class="table table-responsive table-bordered table-hover " id="datatable">
            <thead>
            <tr class="bg-teal-800">
                <th class="col-md-1">SL</th>
                <th class="col-md-1">Customer Name</th>
                <th class="col-md-1">Mobile No</th>
                <th class="col-md-1">Address</th>
                <th class="col-md-2">
                    <small>Total Security Money<br>Received by Company</small>
                </th>
                <th class="col-md-2">
                    <small>Total Security Money<br>Backed To Customer</small>
                </th>
                <th class="col-md-2">Present Total <br>Security Money of Customer</th>
                <th class="col-md-1">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0;

            foreach ($allCustomerSecurityMoneyGiven as $customerSecMoney) { ?>
                <tr>
                    <td class="text-center"> <?php echo ++$i ?> </td>
                    <td class=""><a href="?q=view_single_customer_security_money&customerid=<?php echo $customerSecMoney['id'] ?>"><?php echo $customerSecMoney['cus_name'] ?></a>
                    </td>
                    <td class="text-center"> <?php echo $customerSecMoney['cus_mobile_no'] ?> </td>
                    <td class=""> <?php echo $customerSecMoney['cus_address'] ?> </td>
                    <td class="text-right"> <?php echo number_format($customerSecMoney['total_given']) ?> </td>
                    <td class="text-right"> <?php
                        $customerTotalSecMoneyBackData = $obj->details_selected_field_by_cond('tbl_security_money_transaction', 'SUM(amount) as total_back_amount', 'pay_receive = 0 AND customer_id = ' . $customerSecMoney['id']);
                        $customerTotalSecMoneyBack = isset($customerTotalSecMoneyBackData['total_back_amount']) ? $customerTotalSecMoneyBackData['total_back_amount'] : 0;
                        echo $customerTotalSecMoneyBack;
                        ?> </td>
                    <?php $totalSupplierSecurityMoney = $customerSecMoney['total_given'] - $customerTotalSecMoneyBack; ?>

                    <td class="text-right"> <?php echo ($totalSupplierSecurityMoney < 0) ? '(Advance) ' : '';
                        echo number_format(abs($totalSupplierSecurityMoney)) ?> </td>

                    <td class="text-center action-btn">
                        <button class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#backToCompany"
                                data-id="<?php echo $customerSecMoney['id'] ?>"
                                data-customer="<?php echo $customerSecMoney['cus_name'] ?>">Back To Customer
                        </button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<hr>

<div class="modal fade" id="backToCompany" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Security Money Backed To Company From Supplier <span id="person_name"></span>
                </h4>
            </div>

            <form id="editloanForm" action="" method="post" class="form-horizontal">

                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Money Amount</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input onkeypress="return numbersOnly(event)" require type="number"
                                       name="s_money_back" class="form-control">
                                <span class="input-group-addon">Taka</span>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-8">
                            <textarea name="description" rows="7" class="form-control"></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="customer_id" value="">

                </div>
                <div class="modal-footer text-center">

                    <button type="submit" name="submit_money_back" class="btn btn-primary">Money Back</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    function numbersOnly(e) // Numeric Validation
    {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) {
            if ((unicode < 2534 || unicode > 2543) && (unicode < 48 || unicode > 57)) {
                return false;
            }
        }
    }


    $(document).ready(function () {

        $('table#datatable').on('click', 'td.action-btn button.edit', function () {

            var customerId = $(this).data('id');
            var customerName = $(this).data('customer');

            $('div#backToCompany h4.modal-title span#person_name').html(customerName);
            $('div#backToCompany form#editloanForm div.modal-body input[name="customer_id"]').val(customerId);

        });

        $('#editloan').on('hidden.bs.modal', function (e) {

            $('form#editloanForm').trigger("reset");
        })

    });
</script>