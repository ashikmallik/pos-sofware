    <?php
    session_start();

    $user_id = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
    $FullName = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : NULL;
    $UserName = isset($_SESSION['UserName']) ? $_SESSION['UserName'] : NULL;
    $PhotoPath = isset($_SESSION['PhotoPath']) ? $_SESSION['PhotoPath'] : NULL;
    $ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;


    //========================================
    include '../model/Controller.php';
    include '../model/FormateHelper.php';

    $formater = new FormateHelper();
    $obj = new Controller();
    //========================================


    date_default_timezone_set('Asia/Dhaka');
    $date_time = date('Y-m-d g:i:sA');
    $date = date('Y-m-d');
    $ip_add = $_SERVER['REMOTE_ADDR'];
    $userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
    $notification = "";
    //taking month and years
    $day = date('M-Y');


    /*
     * Starting last 12 month income
     */

    $lastMonthIncome = array();
    $lastMonthExpense = array();
    $finalData = array();

    for ($i = 0; $i < 12; $i++) {

        $lastMonthIncome[] = $obj->view_selected_field_by_cond('tbl_account', 'SUM(`acc_amount`) AS `acc_amount`,
             `entry_date` as inc_date', "acc_type != 1 AND acc_type != 2 AND MONTH(entry_date) = MONTH(CURRENT_DATE - INTERVAL '$i' MONTH) 
            AND `entry_date`
             IS NOT NULL ORDER BY `entry_date` DESC");


        $lastMonthExpense[] = $obj->view_selected_field_by_cond('tbl_account', 'SUM(`acc_amount`) AS `exp_amount`,
            `entry_date` as exp_date', "(acc_type = 1 OR acc_type = 2) AND MONTH(entry_date) = MONTH(CURRENT_DATE - INTERVAL '$i' MONTH) 
            AND 
            `entry_date`
             IS NOT NULL ORDER BY `entry_date` DESC");

        $finalData[$i] = $lastMonthIncome[$i][0]+$lastMonthExpense[$i][0];
    }

    /*
     * calculate Debit and Credit
     */

    $debitTotal = $obj->details_selected_field_by_cond("tbl_account", "SUM(acc_amount) as acc_amount", "MONTH(entry_date)= MONTH
    ('$date') AND   YEAR (entry_date)= YEAR ('$date') AND acc_type = 1 OR  acc_type = 2 OR  acc_type = 6 ORDER BY `entry_date` ASC");

    $creditTotal = $obj->details_selected_field_by_cond("tbl_account", "SUM(acc_amount) as acc_amount", "MONTH(entry_date)= MONTH
    ('$date') AND   YEAR (entry_date)= YEAR ('$date') AND acc_type = 3 OR  acc_type = 4 OR  acc_type = 5 ORDER BY 
    `entry_date` ASC");

    $debit = $debitTotal['acc_amount'];

    $credit = $creditTotal['acc_amount'];

    // making percentage ....

    $total = $debit + $credit;
    $debitPercentage = ($debit/ $total) * 100;
    $creditPercentage = ($credit / $total) * 100;

    $finalData[13] = array('debit' => number_format($debitPercentage, 2), 'credit' => number_format($creditPercentage, 2) );


    echo json_encode($finalData);