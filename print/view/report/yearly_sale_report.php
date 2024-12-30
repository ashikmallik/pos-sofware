<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customer_cat = 5; // for accounts


if (isset($_POST['search'])) {
    extract($_POST);
    $yearlySell = $obj->view_all_by_cond("vw_sell_monthly", "YEAR(entry_date)='$dateYear' order by entry_date");
    $print = 'action=yearly&yearDate=' . date('Y-m-d', strtotime("1.1.$dateYear")) . '';
} else {
    $yearlySell = $obj->view_all_by_cond("vw_sell_monthly", "Year(entry_date) = Year(CURDATE()) order by 
    entry_date");
    $print = 'action=yearly&yearDate=' . date('Y-m-d') . '';
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<div class="col-md-12 bg-grey-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View All
<?php
if (isset($_POST['search'])) {
    echo date("Y", strtotime("1.1." . $_POST['dateYear'] . ""));
} else {
    echo 'This Year';
}
?>
                Purchase List </strong></h4>
    </div>
    <div class="col-md-6" style="padding-top:5px;">
        <?php if ($ty == 'SA') { ?>
            <a href="?q=print_monthly_yearly_sell&<?php echo $print ?>" target="_blank" class="btn btn-primary btn-sm pull-right">Print Purchase
                List</a>
        <?php } ?>
    </div>
</div>
<div class="col-md-12 bg-grey-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5 col-md-offset-3">
            <div class="form-group">
                <label class="control-label col-sm-4"  style="padding-top:5px" for="damageRate">Select Year</label>
                <div class="col-sm-8">
                    <select class="form-control" required="required" name="dateYear" id="status">
                        <option></option>
                        <option value="2016">2016</option>
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
                <thead class="bg-slate-800">
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
                    foreach ($yearlySell as $sell) {
                        $i++;
                        $total_price = $sell['total_price'] + $total_price;
                        $total_qty = $sell['total_qty'] + $total_qty;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td class="text-center">
                                <a href="?q=monthly_sale_report&dtoken=<?php echo $sell['entry_date']; ?>"> <?php echo isset($sell['entry_date']) ? date('M Y', strtotime($sell['entry_date'])) : NULL; ?></a>
                            </td>
                            <td class="text-center"><?php echo isset($sell['total_qty']) ? number_format($sell['total_qty']) . ' pcs' : NULL; ?></td>
                            <td class="text-center"><?php echo isset($sell['total_price']) ? number_format($sell['total_price']) . ' TK.' : NULL; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-center "></th>
                    <th class="text-center "><?php echo number_format($total_qty);  ?></th>
                    <th class="text-center "><?php echo number_format($total_price);  ?> TK</th>

                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        $(document).on('mouseover', 'tbody tr td [data-toggle="tooltip"]', function () {
            $('tbody tr td [data-toggle="tooltip"]').tooltip();
        });
        $('select').selectpicker();
    });
</script>