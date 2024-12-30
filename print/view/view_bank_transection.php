<style>

    .ui-autocomplete {
        padding: 0;
        list-style: none;
        background-color: #fff;
        width: 218px;
        border: 1px solid #B0BECA;
        max-height: 350px;
        overflow-x: hidden;
    }
    .ui-autocomplete .ui-menu-item {
        border-top: 1px solid #B0BECA;
        display: block;
        padding: 4px 6px;
        color: #353D44;
        cursor: pointer;
    }
    .ui-autocomplete .ui-menu-item:first-child {
        border-top: none;
    }
    .ui-autocomplete .ui-menu-item.ui-state-focus {
        background-color: #D5E5F4;
        color: #161A1C;
    }
    .full{
        padding:10px;
    }
    .test{
        background-color:white;
        width:40%;
        height:70px;
        padding:20px;
        margin-left:30%;
        color:black;
        font-weight:900;
        font-size:24px;
    }
    #con{
        margin-bottom:10px;
    }
    #move{
        position:absolute;
        left:43%;
        bottom:-1%;
        z-index:999;
        -webkit-transition:1s;
    }
</style>
<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
//$date        = date('Y-m-d');
$day = date('d', strtotime($date));
$Month = date('m', strtotime($date));
$Year = date('Y', strtotime($date));
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">


<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="col-md-12 bg-grey-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-8">
        <h4><strong>View Daily Transection </strong></h4>
    </div>
</div>
<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>


<div class="col-md-12 bg-teal-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateform">Select Start Date</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control datepicker" placeholder="Date" name="dateform" id="new_flight_date" required>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateto">Select End Date</label>
                <div class="col-sm-7">
                    <input style="color: black;" id="old_flight_date" type="text" class="form-control datepicker" placeholder="Date" name="dateto" required>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default"><span
                    class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>

<div class="row" style="padding:10px; font-size: 12px;"> 
<?php
$flag = isset($_GET['key']) ? "1" : "0";
$where = "";
$title = " " . date("M, Y");
;
if (isset($_POST['search'])) {
    extract($_POST);
    $flag = "1";
    $title = "" . $dateform . " to " . $dateto;
    $where = "entry_date BETWEEN '$dateform' and '$dateto'";
}
//entry_date BETWEEN '$dateform1' and '$dateto1' and acc_type='1'
?>
    <!-- here end table -->
    <?php
    if ($flag == "0") {
        $where = "MONTH(entry_date)='$Month' and YEAR(entry_date)='$Year'and YEAR(entry_date)='$Year'";
    }
    ?>
    <!--
    <div class="col-md-1 col-md-offset-11" >
                    <button type="submit" class="btn btn-primary pull-right" onclick="printDiv('month_print')"  >Print Statement</button>
    </div> -->
    <div class="row" id="">

        <div class="col-md-11" >
            <h4 style="text-align:center;"><?= $title ?></h4>
        </div>
        <div class="col-md-12" id="month_print">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="example">
                    <thead> 
                        <tr>
                            <th>Date</th> 
                            <th>Account No</th> 
                            <th>Account Name</th>
                            <th>Bank Name</th>
                            <th>Description</th>
                            <th>Dip/Withdray By</th>
                            <th>Credit(TK)</th>                        
                            <th>Debit(TK)</th>    
                            <th>Balance(TK)</th>                      

                        </tr>                     

                    </thead>
                    <tbody>
<?php
$balance = 0;
$total = 0;
$bank_total_diposit = 0;
$bank_total_withdraw = 0;
foreach ($obj->view_all_by_cond("bank_account", $where . " ORDER BY entry_date DESC") as $value) {
    $account_no = isset($value['account_no']) ? $value['account_no'] : NULL;
    $where = "account_no='$account_no'";
    foreach ($obj->view_all_by_cond("bank_registration", $where . " ORDER BY entry_date DESC") as $value_info) {
        
    }
    ?>
                            <tr>
                                <td><?php echo isset($value['entry_date']) ? $value['entry_date'] : NULL; ?></td>
                                <td>
                                    <a href="?q=bank_statement&token2=<?php echo isset($value['account_no']) ? $value['account_no'] : NULL ?>" ><?php echo isset($value['account_no']) ? $value['account_no'] : NULL; ?></a>
                                </td>
                                <td><?php echo isset($value_info['account_name']) ? $value_info['account_name'] : NULL; ?></td>
                                <td><?php echo isset($value_info['bank_name']) ? $value_info['bank_name'] : NULL; ?></td>
                                <td><?php echo isset($value['description']) ? $value['description'] : NULL; ?></td>
                                <td><?php
                        $val = isset($value['credit']) ? $value['credit'] : NULL;
                        if ($val <= 0) {

                            echo isset($value['withdraw_by']) ? $value['withdraw_by'] : NULL;
                        } else {
                            echo isset($value['diposited_by']) ? $value['diposited_by'] : NULL;
                        }
    ?>





                                </td>
                                <td><?php echo isset($value['credit']) ? $value['credit'] : NULL; ?></td>
                                <td><?php echo isset($value['debit']) ? $value['debit'] : NULL; ?></td>
                                <td><?php echo isset($value['balance']) ? $value['balance'] : NULL; ?></td>


                            </tr>
    <?php
    $bank_total_diposit += isset($value['credit']) ? $value['credit'] : NULL;
    $bank_total_withdraw += isset($value['debit']) ? $value['debit'] : NULL;
}
?>
                    </tbody>	
                    <tfoot>
                        <tr style="font-size:18px;font-weight:900;">
                            <td colspan="6" style="text-align:right;"><b>Grand Total</b></td>
                            <td><b ><?php echo $bank_total_diposit; ?></b></td>
                            <td><b><?php echo $bank_total_withdraw; ?></b></td>
                            <td><b><?php echo $bank_total_diposit - $bank_total_withdraw; ?></b></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>

    <script>
        $(document).ready(function () {
            $('input[name="dateform"]').datepicker({
                autoclose: true,
                toggleActive: true,
                format: 'dd-mm-yyyy'
            });

            $('input[name="dateto"]').datepicker({
                autoclose: true,
                toggleActive: true,
                format: 'dd-mm-yyyy'
            });

        });
        function printDiv(divName) {
            $(".print").css("margin-left", "0px");
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <!-- here end table -->



