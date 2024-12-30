<?php
require_once 'DBController.php';
$db = new DBController();

$dateYear = date('Y');
$dateMonth = date('m');

if (isset($_POST['search'])) {
    $dateYear = $_POST['dateYear'];
    $dateMonth = $_POST['dateMonth'];
}

// Define the start and end dates for the filter (the first and last day of the selected month)
$startDate = "$dateYear-$dateMonth-01";
$endDate = date("Y-m-t", strtotime($startDate));

// SQL query to join the vw_purchase, vw_sell, and vw_purchase_item tables based on the date range
$query = "
    SELECT 
        pi.product_name,
        AVG(p.total_price / p.total_qty) AS avg_purchase_price,
        AVG(s.total_price / s.total_qty) AS avg_sell_price,
        SUM(p.total_qty) AS total_purchase_qty,
        SUM(s.total_qty) AS total_sell_qty,
        SUM(p.total_price) AS total_purchase_amount
    FROM 
        vw_purchase p
    LEFT JOIN 
        vw_sell s ON p.bill_id = s.sell_id
    LEFT JOIN 
        vw_purchase_item pi ON p.bill_id = pi.bill_id
    WHERE 
        (p.entry_date BETWEEN :startDate AND :endDate OR s.entry_date BETWEEN :startDate AND :endDate)
    GROUP BY 
        pi.product_name";

// Bind the parameters for the prepared statement
$params = [':startDate' => $startDate, ':endDate' => $endDate];

// Execute the query and fetch the data
try {
    $sellPurchaseData = $db->runQuery($query, $params);

    // Debug: Check if data is returned
    if (empty($sellPurchaseData)) {
        echo "<p>No data found for the selected period.</p>";
    }

} catch (Exception $e) {
    echo "Query execution failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell & Purchase Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
    <style>
        body {
            background-color: #f8f9fa;
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
    <h3 class="text-center mb-4">Sell & Purchase Report</h3>
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
                <label class="control-label col-sm-4" style="padding-top:5px" for="damageRate">Select Year</label>
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
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Search</button>
        </div>
    </form>
</div>
    <div>
        <table class="table table-bordered table-hover table-striped">
            <thead class="bg-success text-white">
            <tr>
                <th>SL</th>
                <th>Product Name</th>
                <th>Purchase QTY</th>
                <th>Unit Purchase Price</th>
                <th>Total Purchase Amount</th>
                <th>Sell QTY</th>
                <th>Unit Sell Price</th>
                <th>Total Sell Price</th>
                <th>Balance Stock</th>
                <th>Balance Amount</th>
                <th>Gross Profit</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sl = 1;
            if ($sellPurchaseData) {
                foreach ($sellPurchaseData as $data) {
                    $productName = $data['product_name'];
                    $singleUnitPurchasePrice = $data['avg_purchase_price'];
                    $singleUnitSellPrice = $data['avg_sell_price'];
                    $totalPurchaseQty = $data['total_purchase_qty'];
                    $totalSellQty = $data['total_sell_qty'];
                    $totalPurchaseAmount = $data['total_purchase_amount'];
                    $remainingQty = $totalPurchaseQty - $totalSellQty;
                    $remainingPrice = $remainingQty * $singleUnitPurchasePrice;
                    $totalSellPrice = $singleUnitSellPrice * $totalSellQty;

                    // Gross Profit Calculation
                    $grossProfit = ($remainingQty * $singleUnitPurchasePrice) + $totalSellPrice - ($totalPurchaseQty * $singleUnitPurchasePrice);

                    echo "<tr>";
                    echo "<td>$sl</td>";
                    echo "<td>$productName</td>";
                    echo "<td>{$totalPurchaseQty}</td>";
                    echo "<td>" . number_format($singleUnitPurchasePrice, 2) . "</td>";
                    echo "<td>" . number_format($totalPurchaseAmount, 2) . "</td>";
                    echo "<td>{$totalSellQty}</td>";
                    echo "<td>" . number_format($singleUnitSellPrice, 2) . "</td>";
                    echo "<td>" . number_format($totalSellPrice, 2) . "</td>";
                    echo "<td>{$remainingQty}</td>";
                    echo "<td>" . number_format($remainingPrice, 2) . "</td>";
                    echo "<td>" . number_format($grossProfit, 2) . "</td>";
                    echo "</tr>";

                    $sl++;
                }
            } else {
                echo "<tr><td colspan='11'>No data available</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
