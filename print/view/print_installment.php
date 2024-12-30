<?php

session_start();

$user_id = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$FullName = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : NULL;
$UserName = isset($_SESSION['UserName']) ? $_SESSION['UserName'] : NULL;
$PhotoPath = isset($_SESSION['PhotoPath']) ? $_SESSION['PhotoPath'] : NULL;
$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;

if (!empty($_SESSION['UserId'])) {

    include '../model/Controller.php';
    include '../model/FormateHelper.php';
    $formater = new FormateHelper();
    $obj = new Controller();

    $allinstallment = $obj->view_all_ordered_by("installments", "`installments`.`id` DESC");

    ?>
    <!DOCTYPE html>
    <html>
    <head>

        <title>Show Item</title>

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"/>

        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

        <style>
            .bg-slate-700 {
                background-color: #455a64;
                border-color: #455a64;
                color: #fff;
            }
            .bg-grey-600 {
                background-color: #545455 !important;
                border-color: #666;
                color: #fff !important;
            }
            .table{
                font-family: helvetica;
                font-size: 14px;
                letter-spacing: 0.05em;
            }
        </style>

    </head>
    <body>
    <div class="container">
        <div class="table-responsive" id="acc_statment_print">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-teal-800">
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-1">Customer & sell</th>
                    <th class="text-center col-md-1">Installment Month</th>
                    <th class="text-center col-md-1">Installment Amount</th>
                    <th class="text-center col-md-1">Total Amount</th>
                    <th class="text-center col-md-1">Installment Due</th>
                    <th class="text-center col-md-1">Punishment</th>
                    <th class="text-center col-md-1">Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $sumOfTotalInstallment = 0;
                $sumOfTotalInstallmentAmount = 0;
                $sumOfTotalAmount = 0;
                $sumOfTotalDue = 0;
                $sumOfPayment = 0;
                $sumOfDue = 0;
                foreach ($allinstallment as $installment) {

                    $i++;
                    $totalInstallmentAmount = $installment['total_installment']/$installment['installment_month'];
                    $sumOfTotalInstallment = $totalInstallmentAmount + $sumOfTotalInstallment;
                    $sumOfTotalAmount = $installment['total_installment'] + $sumOfTotalAmount;
                    $sumOfTotalDue = $installment['installment_due'] + $sumOfTotalDue;
                    $installment_id = $installment['id'];
                    ?>
                    <tr>
                        <td class="text-center">
                            <strong><?php echo $i; ?></strong><br>
                        </td>
                        <td class="text-center" style="padding-top:5px">
                            <a class="padding_5_px btn-xs btn-default" href="?q=customer_ledger&customerId=<?php echo $installment['cus_id']; ?>"><?php echo $installment['cus_id']; ?></a>
                        </td>
                        <td class="text-center"><?php echo $installment['installment_month']?></td>
                        <td class="text-center"><?php echo $totalInstallmentAmount; ?></td>
                        <td class="text-center"><?php echo $installment['total_installment'] ?></td>
                        <td class="text-center"><?php echo $installment['installment_due'] ?></td>
                        <td class="text-center"><?php echo $installment['punishment'] ?></td>
                        <td class="text-center"><?php echo $installment['date'] ?></td>

                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="text-center col-md-1">Total</th>
                    <th class="text-center col-md-1"></th>
                    <th class="text-center col-md-1"></th>
                    <th class="text-center col-md-1"><?php echo number_format($sumOfTotalInstallment); ?></th>
                    <th class="text-center col-md-1"><?php echo number_format($sumOfTotalAmount); ?></th>
                    <th class="text-center col-md-2"><?php echo number_format($sumOfTotalDue); ?></th>
                    <th class="col-md-2"></th>
                    <th class="col-md-2"></th>


                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    </body>
    </html>
    <?php
}else{
    header("location: ../include/login.php");
}
?>