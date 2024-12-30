<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$customer_cat = 5; // for accounts


if (isset($_POST['search'])) {
    extract($_POST);
    $monthlyShortage = $obj->view_all_by_cond("tbl_shortage", "MONTH(entry_date)='$dateMonth' and YEAR
    (entry_date)='$dateYear' order by entry_date");
    $header = date('M Y', strtotime("1.$dateMonth.$dateYear"));

} else {
    $monthlyShortage = $obj->view_all_by_cond("tbl_shortage", "month(entry_date) = month(CURDATE()) AND YEAR (entry_date)= YEAR(CURDATE())order by entry_date");
    $header = date('M Y');
}


?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>


<div class="col-md-12 bg-grey-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View All <?php echo $header; ?> Shortage List </strong></h4>
    </div>
</div>

<div class="col-md-12 bg-grey-800" style="margin-bottom: 20px;padding:5px 0px">
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
                        <?php for($i = 2017; $i <= 2025; $i++){ 
                        echo '<option value="'.$i.'"';
                        echo (date('Y') == $i)? " selected ": "";
                        echo '>'.$i.'</option>
                ';
                         } ?>
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
                    <th class="col-md-1 text-center">SL</th>
                    <th class="col-md-1 text-center">Date</th>
                    <th class="col-md-1 text-center">Red A Qty</th>
                    <th class="col-md-1 text-center">Red B Qty</th>
                    <th class="col-md-1 text-center">Red C Qty</th>
                    <th class="col-md-1 text-center">White A Qty</th>
                    <th class="col-md-1 text-center">White B Qty</th>
                    <th class="col-md-1 text-center">White C Qty</th>
                    <th class="col-md-1 text-center">Duck Qty</th>
                    <th class="col-md-1 text-center">Bird Qty</th>
                    <th class="col-md-1 text-center">Damage Qty</th>
                    <th class="col-md-1 text-center">Action</th>

                </tr>
                </thead>
                <tbody style="font-size: 12px;">
                <?php
                $i = 0;
                foreach ($monthlyShortage as $shortage) {
                    $i++;
                    ?>
                    <tr>
                        <td>
                            <?php echo $i; ?>
                        </td>
                        <td class="text-center">
                            <strong><?php echo isset($shortage['entry_date']) ? date('d-M-y', strtotime($shortage['entry_date'])) : NULL; ?></strong>
                        </td>
                        <td class="text-center">
                            <?php echo isset($shortage['redA']) ? $shortage['redA'] . ' pc' : NULL; ?>
                        </td>
                        <td class="text-center">
                            <?php echo isset($shortage['redB']) ? $shortage['redB'] . ' pc' : NULL; ?>
                        </td>
                        <td class="text-center">
                            <?php echo isset($shortage['redC']) ? $shortage['redC'] . ' pc' : NULL; ?>
                        </td>
                        <td class="text-center">
                            <?php echo isset($shortage['whiteA']) ? $shortage['whiteA'] . ' pc' : NULL; ?>
                        </td>
                        <td class="text-center">
                            <?php echo isset($shortage['whiteB']) ? $shortage['whiteB'] . ' pc' : NULL; ?>
                        </td>
                        <td class="text-center">
                            <?php echo isset($shortage['whiteC']) ? $shortage['whiteC'] . ' pc' : NULL;?>
                        </td>
                        <td class="text-center">
                            <?php echo isset($shortage['duckEgg']) ? $shortage['duckEgg'] . ' pc' : NULL; ?>
                        </td>
                        <td class="text-center">
                            <?php echo isset($shortage['birdEgg']) ? $shortage['birdEgg'] . ' pc' : NULL; ?>
                        </td>
                        <td class="text-center">
                            <?php echo isset($shortage['damage']) ? $shortage['damage'] . ' pc' : NULL; ?>
                        </td>
                        <td class="text-center">
                            <?php echo '<a type="button" href="?q=edit_shortage&shortageId=' . $shortage['id'] . '" class="btn bg-grey-800 btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Edit</a>'; ?>
                        </td>
                        
                    </tr>
                    <?php
                }
                ?>
                </tbody>
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