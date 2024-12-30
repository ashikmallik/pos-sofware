<?php

session_start();

$userid = $user_id = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;

if (!empty($_SESSION['UserId'])) {

//========================================
    include '../model/Controller.php';

    $obj = new Controller();
//========================================

    $employeeId = $_GET['employee'];

    $totalLoanRecieve  = $obj->get_sum_data("tbl_employee_loan", "loan_recieve", "`employee_id`=$employeeId");
    $totalLoanRepayment  = $obj->get_sum_data("tbl_employee_loan", "loan_repayment", "`employee_id`=$employeeId");

    $advance = "";

    if( ($totalLoanRecieve - $totalLoanRepayment) < 0 ){

        $advance = '(Advance) ';
    }

    echo json_encode(['previous_loan' => $advance.abs(($totalLoanRecieve - $totalLoanRepayment))] );

}