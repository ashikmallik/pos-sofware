<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$otherIncomeType = 4;
$otherExpenseType = 1;
$recieveCashFromSupplierType = 11;
$loanRepaymentFromPersonType = 14;

$get_head = $_GET['acc_head'];
if (isset($_GET['view'])&&$_GET['view']=='expense'){
    $startDate = date('Y-m-d', strtotime($_GET['startDate']));
    $endDate = date('Y-m-d', strtotime($_GET['endDate']));
    $allIncomeData = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income","acc_head = $get_head AND acc_type ='$otherExpenseType' AND entry_date BETWEEN '$startDate' AND '$endDate' ORDER BY entry_date DESC");
}else{
    $startDate = date('Y-m-d', strtotime($_GET['startDate']));
    $endDate = date('Y-m-d', strtotime($_GET['endDate']));
    $allIncomeData = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income","acc_head = $get_head AND acc_type ='$otherIncomeType' AND entry_date BETWEEN '$startDate' AND '$endDate' ORDER BY entry_date DESC");
}

if(isset($_GET['dltoken']) && !empty($_GET['dltoken'])){
    $deleteId = $_GET['dltoken'];
    $obj ->Delete_data('tbl_account',"acc_id = '$deleteId'");
    if($obj->details_by_cond("bank_account","expense_id='$deleteId'")){
    $obj ->Delete_data('bank_account',"	expense_id = '$deleteId'");
    }
    ?>
    <script>
        window.location  = "?q=view_income";
    </script>
    <?php } ?>

<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="col-md-12 bg-slate-800"
     style="margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; font-weight:bold;">
    <div class="col-md-6">
        <p>View Other <?php echo $_GET['view']?></p>
    </div>
    <div class="col-md-6" style="">

    </div>
</div>

<div class="col-md-12 bg-slate-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="?q=view_expense" method="GET">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateMonth">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" value="<?php echo isset($_GET['startDate']) ? date('d-m-Y',strtotime($_GET['startDate'])) : null; ?>" required="required" type="text" name="startDate" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="damageRate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" value="<?php echo isset($_GET['endDate']) ? date('d-m-Y',strtotime($_GET['endDate'])) : null; ?>" class="form-control" required="required" name="endDate" autocomplete="off">
                </div>
            </div>
        </div>
        <input type="hidden" name="q" value="view_income_details">
        <input type="hidden" name="acc_head" value="<?php echo $_GET['acc_head']?>">
        <input type="hidden" name="view" value="<?php echo $_GET['view']?>">
        <input type="hidden" name="action" value="search">
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default">
                <span class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>

<!-- all user show -->
<div id="div_all" class="row" style="padding:10px;font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable-btn">
                <thead class="bg-grey-800">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
                </thead>
                <?php
                $i = '0';
                $total_other_income = 0;
                $sum_of_amount = 0;
                foreach ($allIncomeData as $head) {
                    $i++;
                    $sum_of_amount = $head['acc_amount'] + $sum_of_amount; ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo date("d-m-Y", strtotime($head['entry_date']));?></td>
                        <td><?php echo $head['acc_description']?></td>
                        <td class="text-right"><?php echo number_format($head['acc_amount']) ?> Tk</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a class="btn btn-xs btn-primary" href="?q=<?php if($_GET['view']=='income') {echo 'edit_income';}else{echo 'edit_expense';} ?>&token=<?php echo $head['acc_id'] ?>">
                                    Edit <span class="glyphicon glyphicon-edit"</span>
                                </a>
                                <a onclick="return confirm('Are You Sure Delete Income Info')" href="?q=view_income_details&dltoken=<?php echo $head['acc_id'] ?>" class="btn btn-xs btn-danger" >
                                    Delete <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                <tfoot>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-right "><?php echo number_format($sum_of_amount);  ?> Tk</th>
                    <th class="text-center "></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

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

    $(document).ready(function() {
        $("#datatable-btn").dataTable().fnDestroy();
        $('#datatable-btn').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print Loan',
                    footer:true,
                    title: function () {
                        return "Expense"
                    },
                    exportOptions: {
                        columns: [0,1,2,3]
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
</script>

<script type="application/javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="application/javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>
