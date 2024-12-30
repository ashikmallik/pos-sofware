<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
//$date        = date('Y-m-d');
$Month = date('m', strtotime($date));
$Year = date('Y', strtotime($date));
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$dataForPrint = $obj->view_all("tbl_customer");

//=====================start==============================


//=======================end============================

?>

<div class="col-md-12"
     style="background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
        <b>All Customer List </b>
    </div>
</div>
<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="row" style="padding:10px; font-size: 12px;">

    <div class="col-md-1 col-md-offset-11">
        <button type="submit" class="btn btn-primary pull-right" onclick="printDiv('month_print')">Print Statement
        </button>
    </div>
    <div class="row" id="month_print">

        <div class="col-md-6">
            <b>All Customer List </b>
        </div>
        <div class="col-md-12">
            <table class="table table-responsive table-bordered table-hover table-striped" id="datatable">
                <thead>
                <tr>
                    <th>Sl</th>
                    <th>Customer ID</th>
                    <th>Customer Name</th>
                    <th>Customer Company</th>
                    <th>Mobile No</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Create Date</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $i = 0;
                foreach ($dataForPrint as $value) {
                    $i++;
                    ?>
                    <tr>
                        <td> <?php echo $i; ?></td>
                        <td class="text-center"><?php echo isset($value['cus_id']) ? $value['cus_id'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['cus_name']) ? $value['cus_name'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['cus_company']) ? $value['cus_company'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['cus_mobile_no']) ? $value['cus_mobile_no'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['cus_email']) ? $value['cus_email'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['cus_address']) ? $value['cus_address'] : NULL; ?></td>
                        <td class="text-center"><?php echo isset($value['entry_date']) ? date('d-m-y', strtotime($value['entry_date'])) : NULL; ?></td>

                    </tr>
                    <?php
                }
                ?>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
    <!-- here end table -->
</div>

<script>
    $(document).ready(function () {
        //$('#monthly_tbl').dataTable();
    });
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<script>
    $(document).ready(function () {
        $("tbody tr").dblclick(function () {
            _id = this.id;
            if (_id != "0")
                window.location = "?q=view_customer_payment_individual&token2=" + _id;
        });
    });
</script>
<!-- here end table -->