<?php
date_default_timezone_set('Asia/Dhaka');

// Database connection and object creation
// require_once 'db_connect.php'; // Adjust this file name/path if needed
// $obj = new DBController();

// Array to hold yearly data
$yearlyData = [];
$startYear = 2020; // Replace with your desired starting year
$currentYear = date('Y');

// Populate data for each year
for ($year = $startYear; $year <= $currentYear; $year++) {
    $totalSellQty = 0;
    $totalPurchasePrice = 0;
    $totalSellPrice = 0;
    $totalGrossProfit = 0;
    $totalExpense = 0;

    // Loop through all months of the year
    for ($month = 1; $month <= 12; $month++) {
        $sell_item_monthly = $obj->view_all_by_cond(
            "vw_sell_purchase_item_daily",
            "purchase_sell_flag = 0 AND MONTH(entry_date) = $month AND YEAR(entry_date) = $year"
        );
        if ($sell_item_monthly) {
            foreach ($sell_item_monthly as $sell_item) {
                $purchase_price = $obj->details_by_cond("vw_purchase_stock_item", "product_id = '".$sell_item['product_id']."'");
                $sell_price = $obj->details_by_cond("vw_sell_stock_item", "product_id = '".$sell_item['product_id']."'");

                $purchasePrice = isset($purchase_price['avg_purchase_price']) ? $purchase_price['avg_purchase_price'] : 0;
                $sellPrice = isset($sell_price['avg_sell_price']) ? $sell_price['avg_sell_price'] : 0;
                $unitProfit = $sellPrice - $purchasePrice;
                $grossProfit = $unitProfit * $sell_item['qty'];

                // Accumulate yearly totals
                $totalSellQty += $sell_item['qty'];
                $totalPurchasePrice += $purchasePrice * $sell_item['qty'];
                $totalSellPrice += $sellPrice * $sell_item['qty'];
                $totalGrossProfit += $grossProfit;
            }
        }

        // Fetch expense data for the month
        $expenseData = $obj->view_all_by_cond("tbl_account", "acc_type = 1 AND MONTH(entry_date) = $month AND YEAR(entry_date) = $year");
        if ($expenseData) {
            foreach ($expenseData as $expense) {
                $totalExpense += $expense['acc_amount'];
            }
        }
    }

    // Calculate Net Profit
    $netProfit = $totalGrossProfit - $totalExpense;

    // Store the year's data
    $yearlyData[] = [
        'year' => $year,
        'sellQty' => $totalSellQty,
        'purchasePrice' => $totalPurchasePrice,
        'sellPrice' => $totalSellPrice,
        'grossProfit' => $totalGrossProfit,
        'expense' => $totalExpense,
        'netProfit' => $netProfit,
    ];
}
?>

<!-- HTML Structure -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" >
    <title>Yearly Report</title>
    <!-- Include Bootstrap -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
    <style>
    
        .container {
            max-width: auto;
            margin: auto;
        }
        .dropdown-container {
            margin: auto;
            text-align: center;
        }
        .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            font-size: 16px;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    
        <h3 class="text-center">Yearly Report</h3>
        <!-- <div class="dropdown-container">
            <select id="yearDropdown" class="form-select w-50 mx-auto">
                <option value="" disabled selected>Select Year</option>
                <?php foreach ($yearlyData as $data) { ?>
                    <option value="<?php echo $data['year']; ?>"><?php echo $data['year']; ?></option>
                <?php } ?>
            </select>
        </div> -->

        <div class="table-container">
            <table id="yearlyReportTable" class="table table-bordered table-striped d-none">
                <thead style="background-color: #00695c; color:white ">
                    <tr>
                        <th>Year</th>
                        <th>Total Sell Qty</th>
                        <th>Total Purchase Price</th>
                        <th>Total Sell Price</th>
                        <th>Total Gross Profit</th>
                        <th>Total Expense</th>
                        <th>Total Net Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($yearlyData as $data) { ?>
                        <tr data-year="<?php echo $data['year']; ?>" class="year-data d-none">
                            <td><?php echo $data['year']; ?></td>
                            <td><?php echo $data['sellQty']; ?></td>
                            <td><?php echo number_format($data['purchasePrice'], 2); ?></td>
                            <td><?php echo number_format($data['sellPrice'], 2); ?></td>
                            <td><?php echo number_format($data['grossProfit'], 2); ?></td>
                            <td><?php echo number_format($data['expense'], 2); ?></td>
                            <td><?php echo number_format($data['netProfit'], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    

    <!-- Include Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
       
    </script>
</body>
</html>
