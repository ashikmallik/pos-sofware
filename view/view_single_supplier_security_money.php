<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$supplierId = isset($_GET['supplierid'])? $_GET['supplierid']:NULL;

if(!empty($supplierId)){

    $supplierData = $obj->details_by_cond("tbl_supplier", "id = $supplierId ");
    $allSupplierTransactionData = $obj->view_all_by_cond("tbl_security_money_transaction", "supplier_id = $supplierId");
}

if (isset($_GET['deletetransactionId']) && !empty($_GET['deletetransactionId'])) {

    $deleteId = $_GET['deletetransactionId'];
    $deletedLoanData = $obj->details_by_cond('tbl_security_money_transaction', "id = $deleteId");

    if($obj->Delete_data('tbl_security_money_transaction', "id = $deleteId")){

        $obj->Delete_data('tbl_account', 'acc_id = ' . $deletedLoanData['accounts_id']);
        $obj->notificationStore('Transaction Record Deleted Successfully', 'success' );

        echo '<script> window.location = "?q=view_single_supplier_security_money&supplierid='.$supplierId.'"; </script>';
    }else{

        $obj->notificationStore('Transaction Record Deleted Failed' );
        echo '<script> window.location = "?q=view_single_supplier_security_money&supplierid='.$supplierId.'"; </script>';
    }
}

?>

<div class="col-md-12 bg-slate-700" style="margin-top:20px; margin-bottom: 15px;">
    <h4><strong>View Security Money Transaction of <?php echo $supplierData['supplier_name'];?></strong></h4>

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
                <thead class="bg-teal-800">
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-2">Date</th>
                    <th class="text-center col-md-2">Supplier Received Amount</th>
                    <th class="text-center col-md-2">Supplier Backed Amount</th>
                    <th class="text-center col-md-2">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $totalReceived = 0;
                $totalBack = 0;
                foreach ($allSupplierTransactionData as $transection) {
                    $i++;
                    ?>
                    <tr>
                        <td> <strong><?php echo $i; ?></strong>&nbsp; </td>
                        <td class="text-center"><?php echo !empty($transection['created_at']) ? date('d-M-y', strtotime($transection['created_at'])) : NULL; ?></td>
                        <td class="text-right"><?php
                            if($transection['pay_receive'] == 0){
                                $totalReceived += $transection['amount'];
                                echo $transection['amount'];
                            }else{ echo 0; }
                            ?>
                        </td>

                        <td class="text-right"><?php
                            if($transection['pay_receive'] == 1){
                                $totalBack += $transection['amount'];
                                echo $transection['amount'];
                            }else{ echo 0; }
                            ?>
                        </td>

                        <td class="text-center">
                            <a type="button" onclick="return confirm('Are you sure you want to delete this transaction Record?');"
                               href="?q=view_single_supplier_security_money&supplierid=<?php echo $transection['supplier_id'];?>&deletetransactionId=<?php echo $transection['id']; ?>"
                               class="btn btn-danger btn-xs"> <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="text-center" colspan=2> Total</th>
                    <th class="text-right"><?php echo $totalReceived?></th>
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
                <h4>Add Payments for this Sell and Supplier <b><span id="supplierNameModal" class="text-grey-800"> </span></b></h4>
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
                    <input type="hidden" name="supplierId" value="">
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
            var supplierId = $(this).data('supplier');
            var supplierName = $(this).data('name');
            var sellId = $(this).data('sell_id');

            $('div#addPriceModel input[name="supplierId"]').val(supplierId);
            $('div#addPriceModel input[name="sellId"]').val(sellId);
            $('div#addPriceModel span#supplierNameModal').html(supplierName);

        });
    });
</script>