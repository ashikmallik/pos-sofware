<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$otherIncomeType = 4;
$recieveCashFromSupplierType = 11;
$loanRepaymentFromPersonType = 14;

$allIncomeData = $obj->view_all_by_cond("vw_accounts_with_acc_head_other_income","acc_type ='$otherIncomeType' OR acc_type ='$recieveCashFromSupplierType' OR acc_type ='$loanRepaymentFromPersonType' ORDER BY entry_date DESC");

if(isset($_GET['deletIncomeId']) && !empty($_GET['deletIncomeId'])){

    $deleteId = $_GET['deletIncomeId'];

    $obj -> Delete_data('tbl_account',"acc_id = '$deleteId'");
    ?>
    <script>
        window.location  = "?q=view_income";
    </script>
    <?php
}

// ==========  Function End =================

?>

<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="col-md-12 bg-slate-800"
     style="margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; font-weight:bold;">
    <div class="col-md-6">
        <p>View Other Income</p>
    </div>
    <div class="col-md-6" style="">
        <?php if ($ty == 'SA') { ?>
            <a class="btn btn-primary btn-sm pull-right" href="?q=add_income">ADD NEW Income<span class="glyphicon
        glyphicon-plus"></span></a>
        <?php } ?>
    </div>
</div>

<!-- all user show -->
<div id="div_all" class="row" style="padding:10px;font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-grey-800">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Head</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                </thead>
                <?php
                $i = '0';
                $total_other_income = 0;
                foreach ($allIncomeData as $value) {
                    $i++;
                    $total_other_income = $value['acc_amount']+$total_other_income;
                    ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo date("d-m-Y", strtotime(isset($value['entry_date'])?$value['entry_date']:"2016-02-1"));?></td>
                        <td><?php echo isset($value['acc_name'])?$value['acc_name']:NULL;?></td>
                        <td><?php echo isset($value['acc_amount'])?$value['acc_amount']:NULL;?></td>
                        <td><?php echo isset($value['acc_description'])?$value['acc_description']:NULL;?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a class="btn btn-xs bg-teal btn-primary padding_2_10_px"
                                    <?php echo ($value['acc_head'] == 0) ? 'disabled' : '';  ?> href="?q=edit_income&incomeId=<?php echo isset ($value['acc_id']) ? $value['acc_id'] : NULL ?>">Edit</a>
                                <a href="?q=view_income&deletIncomeId=<?php echo isset($value['acc_id']) ? $value['acc_id'] : NULL; ?>"
                                    <?php echo ($value['acc_head'] == 0) ? 'disabled' : '';  ?> onclick="return confirm('Are you sure you want to delete this Income?');"
                                   class="btn btn-xs btn-danger padding_2_10_px">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tfoot>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>
                    <th class="text-center "><?php echo number_format($total_other_income);  ?></th>
                    <th class="text-center "></th>
                    <th class="text-center "></th>

                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
