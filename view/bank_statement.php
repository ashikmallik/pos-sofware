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

//=====================start==============================
//=======================end============================
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">

<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
        <b>Statement of Accounts <?php echo $_GET['token2']?></b>
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
                    <input type="text" class="form-control datepicker" placeholder="Date" name="dateform" id="new_flight_date" autocomplete="off" required>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateto">Select End Date</label>
                <div class="col-sm-7">
                    <input style="color: black;" id="old_flight_date" type="text" class="form-control datepicker" placeholder="Date" name="dateto" autocomplete="off" required>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default">
                <span class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>

<div class="row" style="padding:10px; font-size: 12px;"> 
    <?php
    $cus_id = $_GET['token2'];
    $flag = isset($_GET['key']) ? "1" : "0";
    $where = "";
    $title = " " . date("d-M, Y");
    ;
    if (isset($_POST['search'])) {
        extract($_POST);
        $flag = "1";
        $title = "" . $dateform . " to " . $dateto;
        $startDate = date('Y-m-d', strtotime($dateform));
        $endDate = date('Y-m-d', strtotime($dateto));
        $where = "entry_date BETWEEN '$startDate' and '$endDate' and account_no='$cus_id'";
    }else{
        $where = "account_no='$cus_id'";
    }

//entry_date BETWEEN '$dateform1' and '$dateto1' and acc_type='1'
    ?>
    <!-- here end table -->
    <?php

    foreach ($obj->view_all_by_cond("bank_registration", $where . " ORDER BY entry_date DESC") as $customer_info) {
        ?>
        <div class="row" id="">
            <div class="col-md-12" >
                <h2 style="text-align:center;"><?php echo $title ?></h2>
            </div>
            <div class="col-md-12" >
                <table style="font-size: 14px;" class="table table-bordered table-striped" id="">
                    <tr>
                        <td><b class="text-bold">Bank Name:</b>  <?php echo isset($customer_info['bank_name']) ? $customer_info['bank_name'] : NULL; ?><br>
                            <b class="text-bold">Branch Name:</b>  <?php echo isset($customer_info['branch_name']) ? $customer_info['branch_name'] : NULL; ?></td>
                        <td><b class="text-bold">Period : </b> <?php echo $title ?><br>

                            <b class="text-bold">Account No:</b>  <?php echo isset($customer_info['account_no']) ? $customer_info['account_no'] : NULL; ?><br>
                            <b class="text-bold">Account Name:</b>    <?php echo isset($customer_info['account_name']) ? $customer_info['account_name'] : NULL; ?>
                        </td>
                    </tr>

                </table>
            </div>
            <?php
        }
        ?>
        <div class="col-md-12" id="month_print">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped"<?php if ($flag == 0) { ?> id="example"<?php } ?> id="example">
                    <thead> 
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Check No</th>
                            <th>Dip/Withdraw By</th>
                            <th> Debit(TK)</th>
                            <th> Credit(TK)</th>
                            <th> Balance(TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $balance = 0;
                        $total_bill = 0;
                        $total_pay = 0;
                        $tota_bank_credit = 0;
                        $tota_bank_debit = 0;

                        foreach ($qq =$obj->view_all_by_cond("bank_account", $where . " ORDER BY entry_date") as $value) {
                            $tota_bank_credit += isset($value['credit']) ? $value['credit'] : NULL;
                            $tota_bank_debit += isset($value['debit']) ? $value['debit'] : NULL;
                            ?>
                            <tr>
                                <td><?php echo date("d-m-Y", strtotime(isset($value['entry_date']) ? $value['entry_date'] : "NULL")); ?></td>
                                <td><?php echo isset($value['description']) ? $value['description'] : NULL; ?></td>
                                <th><?php echo $value['chq_no']?></th>
                                <td><?php
                                    $val = isset($value['credit']) ? $value['credit'] : NULL;
                                    echo $value['withdraw_by'];
                                    echo $value['diposited_by'];
                                    if ($val <= 0) {

                                        //echo isset($value['withdraw_by']) ? $value['withdraw_by'] : NULL;
                                    } else {
                                       // echo isset($value['diposited_by']) ? $value['diposited_by'] : NULL;
                                    }
                                    ?></td>
                                <td class="text-right"><?php echo isset($value['debit']) ? $value['debit'] : NULL; ?></td>
                                <td class="text-right"><?php echo isset($value['credit']) ? $value['credit'] : NULL; ?></td>

                                <?php
                                $debit = 0;
                                $credit = 0;

                                $debit = isset($value['debit']) ? $value['debit'] : NULL;
                                $credit = isset($value['credit']) ? $value['credit'] : NULL;

                                $balance -= $credit;
                                $balance = $balance + $debit;
                                $total_bill += $debit;
                                $total_pay += $credit;
                                ?>

                                <td class="text-right"><?php echo $balance; ?></td>

                            </tr>
                            <?php } ?>
                    </tbody>	
                    <tfoot>
                        <tr style="font-size:18px;font-weight:900;">
                            <td colspan="4" style="text-align:right;"><b>Grand Total</b></td>
                            <td class="text-right"><b><?php echo $tota_bank_debit; ?></b></td>
                            <td class="text-right"><b><?php echo $tota_bank_credit; ?></b></td>
                            <td class="text-right"><b><?php echo $tota_bank_debit-$tota_bank_credit ; ?></b></td>
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

        $(document).ready(function() {
            $("#example").dataTable().fnDestroy();
            $('#example').DataTable( {
                "ordering": false,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print Statement',
                        title: function () {
                            return "<table>" +
                                "<tr>" +
                                "<td>Bank Name : <?php echo isset($customer_info['bank_name']) ? $customer_info['bank_name'] : NULL; ?><br>Branch Name: <?php echo isset($customer_info['branch_name']) ? $customer_info['branch_name'] : NULL; ?></td>" +
                                "<td>Period : <?php echo $title ?><br>Account No : <?php echo isset($customer_info['account_no']) ? $customer_info['account_no'] : NULL; ?><br>Account Name :<?php echo isset($customer_info['account_name']) ? $customer_info['account_name'] : NULL; ?></td>" +
                                "<tr>" +
                                "</table>"
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

    <script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>
    <!-- here end table -->



