<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
//$date        = date('Y-m-d');
$Month = date('m', strtotime($date));
$Year = date('Y', strtotime($date));
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

if (isset($_GET['zonePrint'])) {

    $dataForPrint = $obj->view_all_by_cond("tbl_agent", "zone = " . $_GET['zonePrint'] . "");

} else {

    $dataForPrint = $obj->view_all("tbl_agent");
}

//=====================start==============================


//=======================end============================

?>


<!------------------------------------------>

<script src="auto_serach/js/jquery-ui.min.js"></script>
<script src="auto_serach/js/jquery.select-to-autocomplete.js"></script>
<script>
    (function ($) {
        $(function () {
            $('select').selectToAutocomplete();
            // $('form').submit(function(){
            //   alert( $(this).serialize() );
            //   return false;
            // });
        });
    })(jQuery);
</script>
<link rel="stylesheet" href="auto_serach/js/jquery-ui.css">
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
</style>
<!------------------------------------------>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<!--<link rel="stylesheet" href="/resources/demos/style.css">-->
<script>
    $(function () {
        $(".datepicker").datepicker();
    });
</script>


<div class="col-md-12"
     style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
        <b>All Client List </b>
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
            <b>All Client List Of <?php
                if (isset($_GET['zonePrint'])) {
                    $zoneData = $obj->details_by_cond('tbl_zone', 'zone_id = ' . $_GET['zonePrint'] . '');
                    echo $zoneData['zone_name'];
                }
                ?></b>
        </div>
        <div class="col-md-12">
            <table class="table table-responsive table-bordered table-hover table-striped" id="monthly_tbl">
                <thead>
                <tr>
                    <th>Sl</th>
                    <th>Client name</th>
                    <th>Address</th>
                    <th>Mobile</th>
                    <th>IP</th>
                    <th>Blood Group</th>
                    <th>National Id</th>
                    <th>Occupation</th>
                    <th>Speed</th>
                    <th>Amount</th>
                    <th>Connection Date</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $i = 0;
                $total_bill = 0;
                foreach ($dataForPrint as $value) {
                    $i++;
                    ?>
                    <tr>
                        <td> <?php echo $i; ?></td>
                        <td><?php echo isset($value['ag_name']) ? $value['ag_name'] : NULL; ?></td>
                        <td><?php echo isset($value['ag_office_address']) ? $value['ag_office_address'] : NULL; ?></td>
                        <td><?php echo isset($value['ag_mobile_no']) ? $value['ag_mobile_no'] : NULL; ?></td>
                        <td><?php echo isset($value['ip']) ? $value['ip'] : NULL; ?></td>
                        <td><?php echo isset($value['blood_group']) ? $value['blood_group'] : NULL; ?></td>
                        <td><?php echo isset($value['national_id']) ? $value['national_id'] : NULL; ?></td>
                        <td><?php echo isset($value['occupation']) ? $value['occupation'] : NULL; ?></td>
                        <td><?php echo isset($value['mb']) ? $value['mb'] : NULL; ?></td>
                        <td><?php echo $total = isset($value['taka']) ? $value['taka'] : NULL; ?></td>
                        <td><?php echo isset($value['connection_date']) ? $value['connection_date'] : NULL; ?></td>
                        <?php
                        $total_bill += $total;
                        ?>
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>

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