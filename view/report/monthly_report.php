<?php
date_default_timezone_set('Asia/Dhaka');

// Current year
$dateYear = date('Y');

// Array to hold monthly data
$monthlyData = [];

// Loop through all months
for ($month = 1; $month <= 12; $month++) {
    $totalSellQty = 0;
    $totalPurchasePrice = 0;
    $totalSellPrice = 0;
    $totalGrossProfit = 0;
    $totalExpense = 0;

    // Fetch profit data for the month
    for ($day = 1; $day <= 31; $day++) {
        $date = "$dateYear-$month-$day";

        $sell_item_daily = $obj->view_all_by_cond("vw_sell_purchase_item_daily", "purchase_sell_flag = 0 AND entry_date = '$date'");
        if (!$sell_item_daily) continue;

        foreach ($sell_item_daily as $sell_item) {
            $purchase_price = $obj->details_by_cond("vw_purchase_stock_item", "product_id = '".$sell_item['product_id']."'");
            $sell_price = $obj->details_by_cond("vw_sell_stock_item", "product_id = '".$sell_item['product_id']."'");

            $purchasePrice = isset($purchase_price['avg_purchase_price']) ? $purchase_price['avg_purchase_price'] : 0;
            $sellPrice = isset($sell_price['avg_sell_price']) ? $sell_price['avg_sell_price'] : 0;
            $unitProfit = $sellPrice - $purchasePrice;
            $grossProfit = $unitProfit * $sell_item['qty'];

            // Accumulate totals
            $totalSellQty += $sell_item['qty'];
            $totalPurchasePrice += $purchasePrice * $sell_item['qty'];
            $totalSellPrice += $sellPrice * $sell_item['qty'];
            $totalGrossProfit += $grossProfit;
        }
    }

    // Fetch expense data for the month
    $expenseData = $obj->view_all_by_cond("tbl_account", "acc_type = 1 AND MONTH(entry_date) = $month AND YEAR(entry_date) = $dateYear");
    if ($expenseData) {
        foreach ($expenseData as $expense) {
            $totalExpense += $expense['acc_amount'];
        }
    }

    // Calculate Net Profit
    $netProfit = $totalGrossProfit - $totalExpense;

    // Store the month's data
    $monthlyData[] = [
        'month' => date('F', strtotime("1-$month-$dateYear")),
        'sellQty' => $totalSellQty,
        'purchasePrice' => $totalPurchasePrice,
        'sellPrice' => $totalSellPrice,
        'grossProfit' => $totalGrossProfit,
        'expense' => $totalExpense,
        'netProfit' => $netProfit,
    ];
}

?>

    <h3 class="text-center">Monthly Report for <?php echo $dateYear; ?></h3>

    <!-- Report Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead style="background-color: #00695c; color:white ">
                <tr>
                    <th>Month</th>
                    <th>Total Sell Qty</th>
                    <th>Total Purchase Price</th>
                    <th>Total Sell Price</th>
                    <th>Total Gross Profit</th>
                    <th>Total Expense</th>
                    <th>Total Net Profit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monthlyData as $data) { ?>
                    <tr>
                        <td><?php echo $data['month']; ?></td>
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
