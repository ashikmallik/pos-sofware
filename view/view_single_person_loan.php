<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$personId = isset($_GET['personid']) ? $_GET['personid'] : NULL;
$personIdName = isset($_GET['personidname']) ? $_GET['personidname'] : NULL;

if (isset($_GET['search'])) {
    $startDate = date('Y-m-d', strtotime($_GET['startDate']));
    $endDate = date('Y-m-d', strtotime($_GET['endDate']));
} else {
    $startDate = date('Y-m-01');
    $endDate = date('Y-m-d');
}

if (!empty($personId)) {

    $personData = $obj->details_by_cond("tbl_person", "id = $personId ");
    $allPersonLoanData = $obj->view_all_by_cond("tbl_person_loan", "person_id = $personId AND created_at BETWEEN '$startDate' and '$endDate'");
}

if (!empty($personIdName)) {

    $personData = $obj->details_by_cond("tbl_person", "person_id = '$personIdName' ");
    $allPersonLoanData = $obj->view_all_by_cond("tbl_person_loan", "person_id = ".$personData['id']." AND created_at BETWEEN '$startDate' and '$endDate'");
}


if (isset($_GET['deleteloanId']) && !empty($_GET['deleteloanId'])) {

    $deleteId = $_GET['deleteloanId'];
    $deletedLoanData = $obj->details_by_cond('tbl_person_loan', "id = $deleteId");

    if ($obj->Delete_data('tbl_person_loan', "id = $deleteId")) {
        $obj->Delete_data('tbl_account', 'acc_id = ' . $deletedLoanData['accounts_id']);
        $obj->notificationStore('Data Deleted Successfully', 'success' );
        echo '<script> window.location = "?q=view_single_person_loan&personid=' . $personId . '"; </script>';
    } else {
        $obj->notificationStore('Data Deleted Failed ');
        echo '<script> window.location = "?q=view_single_person_loan&personid=' . $personId . '"; </script>';
    }
} ?>

<div class="col-md-12 bg-teal-600" style="margin-top:20px; margin-bottom: 15px;">
    <h4><strong>View Ledger of <?php echo $personData['person_name']; ?></strong>
        (
        <?php
        if (isset($_GET['search'])) {
            echo date("d-M-Y", strtotime($_GET['startDate'])).' To ';
            echo date("d-M-Y", strtotime($_GET['endDate']));
        } else {
            echo 'This Months';
        } ?> )
    </h4>
</div>

<div class="col-md-12 bg-teal-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="get">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateMonth">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" required="required" type="text" name="startDate" value="<?php if (isset($_GET['startDate'])){echo $_GET['startDate'];}?>">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="damageRate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" required="required" name="endDate" value="<?php if (isset($_GET['endDate'])){echo $_GET['endDate'];}?>">
                    <input type="hidden" value="view_single_person_loan" name="q">
                    <input type="hidden" value="<?php echo $_GET['personid'];?>" name="personid">
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default"><span class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
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
            <table class="table table-bordered table-hover table-striped" id="datatable-btn">
                <thead class="bg-slate-800">
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-2">Date</th>
                    <th class="text-center col-md-2">Take Loan Amount</th>
                    <th class="text-center col-md-2">Repayment Loan</th>
                    <th class="text-center col-md-2">Balance</th>
                    <th class="text-center col-md-2">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $totalTakeLoan = 0;
                $totalRecieveLoan = 0;
                $total_loan = 0;
                foreach ($allPersonLoanData as $loan) {
                    $i++;
                    $totalTakeLoan += $loan['loan_recieve'];
                    $totalRecieveLoan += $loan['loan_repayment'];
                    ?>
                    <tr>
                        <td><strong><?php echo $i; ?></strong>&nbsp;</td>
                        <td class="text-center"><?php echo !empty($loan['created_at']) ? date('d-M-y', strtotime($loan['created_at'])) : NULL; ?></td>
                        <td class="text-right"><?php echo ($loan['loan_recieve'] != '0.00') ? $loan['loan_recieve'] : NULL; ?></td>
                        <td class="text-right"><?php echo ($loan['loan_repayment'] != '0.00') ? $loan['loan_repayment'] : NULL; ?></td>
                        <td class="text-right"><?php echo $total_loan = $totalTakeLoan - $totalRecieveLoan ?></td>
                        <td class="text-center">
                            <a type="button" onclick="return confirm('Are you sure you want to delete this Loan Record?');"
                               href="?q=view_single_person_loan&personid=<?php echo $personId; ?>&deleteloanId=<?php echo $loan['id']; ?>" class="btn btn-danger btn-xs"> <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="text-center" colspan=2> Total</th>
                    <th class="text-right"><?php echo $totalTakeLoan ?></th>
                    <th class="text-right"><?php echo $totalRecieveLoan ?></th>
                    <th class="text-right"><?php echo $total_loan ?></th>
                    <th class="text-center">
                    </td>
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
                                <input type="text" onkeypress="return numbersOnly(event)" name="payment"
                                       placeholder="Insert payments" class="form-control">
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
    $(document).ready(function() {
        $("#datatable-btn").dataTable().fnDestroy();
        $('#datatable-btn').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print Loan',
                    footer: true,
                    title: function () {
                        return "Loan Borrower <?php echo $personData['person_name'];?>"
                    },
                    exportOptions: {
                        columns: [0,1,2,3,4]
                    },
                    customize: function (win) {
                        $(win.document.body).css('font-size', '12px');
                        $(win.document.body).find('h1').addClass('text-center').css('font-size', '20px');
                        $(win.document.body).find('table').addClass('container').css('font-size', 'inherit');
                        $(win.document.body).find('table').removeClass('table-bordered');
                    }
                }
            ]
        } );
    } );

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function () {
        $('input[name="startDate"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });

        $('input[name="endDate"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });

        $(document).on('mouseover', 'tbody tr td [data-toggle="tooltip"]', function () {
            $('tbody tr td [data-toggle="tooltip"]').tooltip();
        });

    });
</script>

<script type="application/javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="application/javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>