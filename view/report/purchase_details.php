<?php
date_default_timezone_set('Asia/Dhaka');

// Fetch all purchase data from `report_tbl`
$allPurchaseData = $obj->view_all_by_cond("report_tbl", "1 ORDER BY `bill_id` DESC");
?>

<div class="container-fluid mt-4"> <!-- Changed to container-fluid for full-width -->
    <div class="row mb-3">
        <div class="col-md-6">
            <h3 class="text-primary">Purchase Details</h3>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped" id="datatable" style="width: 100%;">
            <thead class="table-dark">
                <tr>
                    <th>SL</th>
                    <th>Supplier</th>
                    <th>Total Qty</th>
                    <th>Total Price (TK)</th>
                    <th>Commission (TK)</th>
                    <th>Payment Received (TK)</th>
                    <th>Due To Company (TK)</th>
                    <th>Entry Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            $sumOfTotalPrice = 0;
            $sumOfPayment = 0;
            $sumOfDue = 0;

            foreach ($allPurchaseData as $purchase) {
                $billId = $purchase['bill_id'];
                $supplier = isset($purchase['supplier']) ? $purchase['supplier'] : 'Unknown Supplier';
                $totalQty = isset($purchase['total_qty']) ? $purchase['total_qty'] : 0;
                $totalPrice = isset($purchase['total_price']) ? $purchase['total_price'] : 0;
                $commission = isset($purchase['commission']) ? $purchase['commission'] : 0; 
                $paymentReceived = isset($purchase['payment_recieved']) ? $purchase['payment_recieved'] : 0;
                $dueToCompany = isset($purchase['due_to_company']) ? $purchase['due_to_company'] : 0;
                $entryDate = isset($purchase['entry_date']) ? date('d-M-y', strtotime($purchase['entry_date'])) : null;

                $i++;
                $sumOfTotalPrice += $totalPrice;
                $sumOfPayment += $paymentReceived;
                $sumOfDue += $dueToCompany;
            ?>
                <tr>
                    <td class="text-center"><strong><?php echo $i; ?></strong></td>
                    <td><?php echo htmlspecialchars($supplier); ?></td>
                    <td class="text-center"><?php echo number_format($totalQty); ?></td>
                    <td class="text-right"><?php echo number_format($totalPrice, 2); ?> TK</td>
                    <td class="text-right"><?php echo number_format($commission, 2); ?> TK</td>
                    <td class="text-right"><?php echo number_format($paymentReceived, 2); ?> TK</td>
                    <td class="text-right"><?php echo number_format($dueToCompany, 2); ?> TK</td>
                    <td class="text-center"><?php echo $entryDate; ?></td>
                </tr>
            <?php } ?>
            <tfoot>
            <tr class="table-secondary">
                <th colspan="3" class="text-center">Total</th>
                <th class="text-right"><?php echo number_format($sumOfTotalPrice, 2); ?> TK</th>
                <th class="text-right"><?php echo number_format(array_sum(array_column($allPurchaseData, 'commission')), 2); ?> TK</th>
                <th class="text-right"><?php echo number_format($sumOfPayment, 2); ?> TK</th>
                <th class="text-right"><?php echo number_format($sumOfDue, 2); ?> TK</th>
                <th></th>
            </tr>
            </tfoot>
            </tbody>
        </table>
    </div>
</div>

<!-- Custom JavaScript for Search -->
<script>
document.getElementById('datatable-search').addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#datatable tbody tr');

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>
