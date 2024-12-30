<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customer_cat = 5; // for accounts


if (isset($_POST['search'])) {
    extract($_POST);
    $monthlyPurchase = $obj->view_all_by_cond("vw_purchase_dailly", "MONTH(entry_date)='$dateMonth' and YEAR
    (entry_date)='$dateYear' order by entry_date");

    $print = 'action=monthly&monthDate='.date('Y-m-d', strtotime("1.$dateMonth.$dateYear")).'';
    $header = date('M Y', strtotime("1.$dateMonth.$dateYear"));

}else if(isset($_GET['dtoken'])){
    $date = $_GET['dtoken'];
    $monthlyPurchase = $obj->view_all_by_cond("vw_purchase_dailly", "MONTH(entry_date)=MONTH('$date') and YEAR
    (entry_date)= YEAR('$date') order by entry_date");

    $print = 'action=monthly&monthDate='.$date.'';
    $header = date('M Y', strtotime($date));

} else {
    $monthlyPurchase = $obj->view_all_by_cond("vw_purchase_dailly", "month(entry_date) = month(CURDATE()) AND YEAR
    (entry_date)= YEAR(CURDATE())order by entry_date");
    $print = 'action=monthly&monthDate='.date('Y-m-d').'';
    $header = date('M Y');
}

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>


<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View All <?php echo $header; ?> Purchase List </strong></h4>
    </div>

    <div class="col-md-6" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>
            <a href="?q=print_monthly_yearly_purchase&<?php echo $print ?>" target="_blank" class="btn btn-primary btn-sm pull-right">Print Sell
                List</a>
        <?php } ?>
    </div>
</div>

<div class="col-md-12 bg-slate-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-4" style="padding-top:5px" for="dateMonth">Select Month</label>
                <div class="col-sm-8">
                    <select class="form-control" required="required" name="dateMonth" id="status">
                        <option></option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-4"  style="padding-top:5px" for="damageRate">Select Year</label>
                <div class="col-sm-8">
                    <select class="form-control" required="required" name="dateYear" id="status">
                        <option></option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-primary"><span
                    class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>
<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-teal-800">
                <tr>
                    <th class="text-center">SL</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Total Qty</th>
                    <th class="text-center">Total Price</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $total_price = 0;
                $total_qty = 0;
                foreach ($monthlyPurchase as $purchase) {
                    $total_price = $purchase['total_price'] + $total_price;
                    $total_qty = $purchase['total_qty'] + $total_qty;
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td class="text-center">
                            <a href="?q=today_purchase_report&dtoken=<?php echo $purchase['entry_date'];?>"> <?php echo isset($purchase['entry_date']) ? date('d-M-y', strtotime($purchase['entry_date'])) : NULL; ?></a>
                        </td>
                        <td class="text-center"><?php echo isset($purchase['total_qty']) ? number_format($purchase['total_qty']) . ' pcs' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($purchase['total_price']) ? number_format($purchase['total_price']) . ' TK.' : NULL; ?></td>

                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-center "></th>
                    <th class="text-center "><?php echo number_format($total_qty);  ?></th>
                    <th class="text-center "><?php echo number_format($total_price);  ?></th>

                </tr>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        $(document).ready(function () {
            $('select').selectpicker();

        });

    });
</script>