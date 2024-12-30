<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$key = isset($_GET['key']) ? $_GET['key'] : "all";
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;


$intoken = isset($_GET['intoken']) ? $_GET['intoken'] : NULL;
$actoken = isset($_GET['actoken']) ? $_GET['actoken'] : NULL;


if (!empty($intoken)) {
    $form_data = array('ag_status' => '0', 'update_by' => $userid);

    $obj->Update_data("bank_registration", $form_data, "where c_id='$intoken'");
}

if (!empty($actoken)) {
    $form_data = array('ag_status' => '1', 'update_by' => $userid);

    $obj->Update_data("bank_registration", $form_data, "where c_id='$actoken'");
}

$dltoken = isset($_GET['dltoken']) ? $_GET['dltoken'] : NULL;
if (!empty($dltoken)) {

    $dele = $obj->Delete_data("bank_registration", "a_id='$dltoken'");

    if (!$dele) {
        //$notification = 'Delete Successfull';
    } else {
        //$notification = 'Delete Failed';
    }
}
?>

<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
        <b>View Customer Information</b>
    </div>           
    <div class="col-md-6" style="">
        <?php if ($ty == 'SA') { ?>
            <a class="addbutton" href="?q=add_bank">ADD NEW BANK<span class="glyphicon glyphicon-plus"></span></a>
        <?php } ?> 
    </div>
</div>

<!-- all user show -->
<div id="div_all" class="row" style="padding:10px;font-size: 12px;">       
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>Account No</th>
                        <th>Balance</th>
                        <th>Account Name</th>
                        <th>Bank Name</th>
                        <th>Branch Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php
                $i = '0';
                $grand_balance = 0;
                foreach ($obj->view_all("bank_registration") as $value) {
                    $i++;
                    ?>
                    <tr>
                       <?php
                       $total_balance=0;
                       $tota_bank_credit = 0;
                       $tota_bank_debit = 0;
                       $acc_no = isset($value['account_no']) ? $value['account_no'] : NULL;
                       $bank_info = $obj-> view_all_by_cond("bank_account","account_no='$acc_no'");
                       foreach($bank_info as $single_info){
                       $tota_bank_credit += isset($single_info['credit']) ? $single_info['credit'] : NULL;
                       $tota_bank_debit += isset($single_info['debit']) ? $single_info['debit'] : NULL;
                       $total_balance = $tota_bank_debit-$tota_bank_credit;
                       }
                       ?>
                        <td>
                            <a href="?q=single_bank_account&token2=<?php echo isset($value['account_no']) ? $value['account_no'] : NULL ?>" ><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?></a>
                        </td>
                        <?php $grand_balance += $total_balance;?>
                        <td><?php echo isset($total_balance) ? number_format($total_balance,2) : NULL; ?></td>
                        <td><?php echo isset($value['account_name']) ? $value['account_name'] : NULL; ?></td>
                        <td><?php echo isset($value['bank_name']) ? $value['bank_name'] : NULL; ?></td>
                        <td><?php echo isset($value['branch_name']) ? $value['branch_name'] : NULL; ?></td>
                        <td>
                            <div class="btn-group">                                                                        
                                <a class="btn btn-xs btn-info" style="padding:5px;" href="?q=edit_bank&token=<?php echo isset($value['a_id']) ? $value['a_id'] : NULL ?>">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </a>                            
                                <a href="?q=view_bank&dltoken=<?php echo isset($value['a_id']) ? $value['a_id'] : NULL; ?>" class="btn btn-xs btn-danger" style="padding:5px;">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>                        
                            </div>                              
                        </td>
                    </tr>
                    <?php } ?>
                     <tfoot>
                        <tr style="font-size:18px;font-weight:900;">
                            <td style="text-align:left;"><b>Total Balance: </b></td>
                            <td class="text-center"><b><?php echo $grand_balance; ?></b></td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
            </table>
        </div>
    </div>
</div>
<?php
