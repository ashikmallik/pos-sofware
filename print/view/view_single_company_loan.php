<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$personId = isset($_GET['personid'])? $_GET['personid']:NULL;

if(!empty($personId)){

    $personData = $obj->details_by_cond("tbl_person", "id = $personId ");
    $allPersonLoanData = $obj->view_all_by_cond("tbl_company_lend", "person_id = $personId");
}

if (isset($_GET['deleteloanId']) && !empty($_GET['deleteloanId'])) {

    $deleteId = $_GET['deleteloanId'];
    $deletedLoanData = $obj->details_by_cond('tbl_company_lend', "id = $deleteId");

    if($obj->Delete_data('tbl_company_lend', "id = $deleteId")){

        $obj->Delete_data('tbl_account', 'acc_id = ' . $deletedLoanData['accounts_id']);
        echo '<script> window.location = "?q=view_single_company_loan&personid='.$personId.'"; </script>';

    }else{

        echo '<script> window.location = "?q=view_single_company_loan&personid='.$personId.'"; </script>';
    }


}

?>

<div class="col-md-12 bg-teal-600" style="margin-top:20px; margin-bottom: 15px;">
    <h4><strong>View Ledger of <?php echo $personData['person_name'];?></strong></h4>

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
                    <th class="text-center col-md-2">Take Loan Amount</th>
                    <th class="text-center col-md-2">Repayment Loan</th>
                    <th class="text-center col-md-2">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $totalTakeLoan = 0;

                $totalRecieveLoan = 0;
                foreach ($allPersonLoanData as $loan) {
                    $i++;
                    $totalTakeLoan += $loan['loan_recieve'];
                    $totalRecieveLoan += $loan['loan_repayment'];
                    ?>
                    <tr>
                        <td> <strong><?php echo $i; ?></strong>&nbsp; </td>
                        <td class="text-center"><?php echo !empty($loan['created_at']) ? date('d-M-y', strtotime($loan['created_at'])) : NULL; ?></td>
                        <td class="text-center"><?php echo ($loan['loan_recieve'] != '0.00') ? $loan['loan_recieve'] : NULL; ?></td>
                        <td class="text-center"><?php echo ($loan['loan_repayment'] != '0.00') ? $loan['loan_repayment'] : NULL; ?></td>
                        <td class="text-center">
                            <a type="button"
                               onclick="return confirm('Are you sure you want to delete this Loan Record?');"
                               href="?q=view_single_company_loan&personid=<?php echo $personId;?>&deleteloanId=<?php echo $loan['id']; ?>"
                               class="btn btn-danger btn-xs">
                                <span class="glyphicon glyphicon-trash"></span> Delete
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
                    <th class="text-center"><?php echo $totalTakeLoan?></th>
                    <th class="text-center"><?php echo $totalRecieveLoan?></th>
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