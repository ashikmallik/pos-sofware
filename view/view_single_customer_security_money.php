<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customerId = isset($_GET['customerid'])? $_GET['customerid']:NULL;

if(!empty($customerId)){

    $customerData = $obj->details_by_cond("tbl_customer", "id = $customerId ");
    $allCustomerTransactionData = $obj->view_all_by_cond("tbl_security_money_transaction", "customer_id = $customerId");
}

if (isset($_GET['deletetransactionId']) && !empty($_GET['deletetransactionId'])) {

    $deleteId = $_GET['deletetransactionId'];
    $deletedSecurityData = $obj->details_by_cond('tbl_security_money_transaction', "id = $deleteId");

    if($obj->Delete_data('tbl_security_money_transaction', "id = $deleteId")){

        $obj->Delete_data('tbl_account', 'acc_id = ' . $deletedSecurityData['accounts_id']);
        $obj->notificationStore('Transaction History Deleted Successfully', 'success' );

        echo '<script> window.location = "?q=view_single_customer_security_money&customerid='.$customerId.'"; </script>';
    }else{

        $obj->notificationStore('Transaction History deleted Successfully', 'success');
        echo '<script> window.location = "?q=view_single_customer_security_money&customerid='.$customerId.'"; </script>';
    }
}

?>

<div class="col-md-12 bg-teal-600" style="margin-top:20px; margin-bottom: 15px;">
    <h4><strong>View Security Money Transaction of <?php echo $customerData['cus_name'];?></strong></h4>

</div>

<div class="row">
    <div class="col-md-12 padding_5_px">
        <?php $obj->notificationShowRedirect(); ?>
    </div>
</div>
<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-slate-800">
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-2">Date</th>
                    <th class="text-center col-md-2">Customer Given Amount</th>
                    <th class="text-center col-md-2">Customer Back Amount</th>
                    <th class="text-center col-md-2">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $totalGiven = 0;
                $totalBack = 0;
                foreach ($allCustomerTransactionData as $transection) {
                    $i++;
                    ?>
                    <tr>
                        <td> <strong><?php echo $i; ?></strong>&nbsp; </td>
                        <td class="text-center"><?php echo !empty($transection['created_at']) ? date('d-M-y', strtotime($transection['created_at'])) : NULL; ?></td>
                        <td class="text-right"><?php
                            if($transection['pay_receive'] == 1){
                                $totalGiven += $transection['amount'];
                                echo $transection['amount'];
                            }else{ echo 0; }
                            ?>
                        </td>

                        <td class="text-right"><?php
                            if($transection['pay_receive'] == 0){
                                $totalBack += $transection['amount'];
                                echo $transection['amount'];
                            }else{ echo 0; }
                            ?>
                        </td>

                        <td class="text-center">
                            <a type="button" onclick="return confirm('Are you sure you want to delete this Transaction Record?');"
                               href="?q=view_single_customer_security_money&customerid=<?php echo $transection['customer_id'];?>&deletetransactionId=<?php echo $transection['id']; ?>"
                               class="btn btn-danger btn-xs"> <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="text-center" colspan=2> Total</th>
                    <th class="text-right"><?php echo $totalGiven?></th>
                    <th class="text-right"><?php echo $totalBack?></th>
                    <th class="text-center"></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="addPriceModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Add Payments for this Sell and Customer <b><span id="customerNameModal" class="text-grey-800"> </span></b></h4>
            </div>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group" style="margin-bottom:20px;">
                            <label class="control-label col-sm-4" for="payment">Add Payments </label>
                            <div class="col-sm-6">
                                <input type="text" onkeypress="return numbersOnly(event)" name="payment" placeholder="Insert payments" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" style="margin-top:20px;">
                            <label class="control-label col-sm-4" for="comments">Comments </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="comments" placeholder="Payment Description" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="customerId" value="">
                    <input type="hidden" name="sellId" value="">
                </div>
                <div class="modal-footer text-center">
                    <div class="row">
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" name="addPayment">Add Payment</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        $('#datatable').on('click', '[data-target="#addPriceModel"]', function () {
            var customerId = $(this).data('customer');
            var customerName = $(this).data('name');
            var sellId = $(this).data('sell_id');

            $('div#addPriceModel input[name="customerId"]').val(customerId);
            $('div#addPriceModel input[name="sellId"]').val(sellId);
            $('div#addPriceModel span#customerNameModal').html(customerName);

        });
    });
</script>