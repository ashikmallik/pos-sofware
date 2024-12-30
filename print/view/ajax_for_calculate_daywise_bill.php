    <?php
    session_start();
    /**
     * Created by mehedi
     * Date: 25/02/17
     * Reason: for calculating total bill for form date and to date in modal.
     */

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
    include '../model/TotalBill.php';
    $totalBill = new TotalBill();
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
     * Figure out the bill for selected days
     */
    $formDate = $_POST['formDate'];
    $toDate = $_POST['todate'];
    $id = $_POST['id'];
    $billForSelectDays = $totalBill->billForSelectedDays($formDate, $toDate, $id);

    /*
     * FormDate and ToDate convert to dateTime class for calculate
     * the day interval
     */
    $formDateMysqlFormat = date('Y-m-d H:i:s', strtotime($formDate));
    $toDateMysqlFormat = date('Y-m-d H:i:s', strtotime($toDate));

    $formDateForShowDateInterval = new DateTime($formDateMysqlFormat);
    $toDateForShowDateInterval = new DateTime($toDateMysqlFormat);

    $diffOfDate = $toDateForShowDateInterval->diff($formDateForShowDateInterval);
    $diffInDays = $diffOfDate->d+1;
    $diffInMonth = $diffOfDate->m;

//    $totalDayDiff = ($diffOfDate->y * 365) + ($diffInMonth * ($dayOfThisMonth)) + $diffInDays;

    $startDay = strtotime($formDateForShowDateInterval->format('d-m-Y'));
    $endDay = strtotime($toDateForShowDateInterval->format('d-m-Y'));

    $dayDiffrence = ($endDay - $startDay)/(24*3600)+1;




//    $totalDayDiff = ($diffOfDate->y * 365) + ($diffInMonth * 30) + $diffInDays;

    $totalDayDiff =$dayDiffrence;


    echo json_encode(array("billForDays" => $billForSelectDays, "days" => $totalDayDiff));

