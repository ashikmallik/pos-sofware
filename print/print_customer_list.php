<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$allAgentData = $obj->view_all("tbl_customer");
?>

<div class="row" style="margin:15px; 0">
    <button type="submit" class="btn btn-primary bg-teal-800 btn-block"
            onclick="printDiv('customer_print')">Click to Print Customer
    </button>
</div>


<div class="row" id="customer_print">
    <div class="col-md-12 text-center">
        <h3>Customer Information</h3>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="">
                <thead>
                <tr>
                    <th class="col-md-1">Customer ID</th>
                    <th class="col-md-1">Customer Name</th>
                    <th class="col-md-1">Customer Company</th>
                    <th class="col-md-1">Mobile No</th>
                    <th class="col-md-1">Email</th>
                    <th class="col-md-3">Address</th>
                    <th class="col-md-1">Create Date</th>
                </tr>
                </thead>
                <?php
                $i = '0';
                foreach ($allAgentData as $value) {
                    $i++;
                    ?>
                    <tr>
                        <td class="text-center">
                            <?php echo isset($value['cus_id']) ? $value['cus_id'] : NULL; ?>
                        </td>
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
            </table>
        </div>
    </div>
</div>

<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        //document.body.innerHTML = originalContents;
    }

    printDiv('customer_print');

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