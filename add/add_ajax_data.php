<?php
session_start();

$userid = $user_id = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$FullName = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : NULL;
$UserName = isset($_SESSION['UserName']) ? $_SESSION['UserName'] : NULL;
$PhotoPath = isset($_SESSION['PhotoPath']) ? $_SESSION['PhotoPath'] : NULL;
$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;


if (!empty($_SESSION['UserId'])) {

//========================================
    include '../model/Controller.php';
    include '../model/FormateHelper.php';

    $formater = new FormateHelper();
    $obj = new Controller();
//========================================
    date_default_timezone_set('Asia/Dhaka');
    $date_time =date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
    $ip_add = $_SERVER['REMOTE_ADDR'];

    if (isset($_GET['token']) && isset($_GET['amount'])) {

    $token = $_GET['token'];
    $form_data = array(
        'agent_id' => $token,
        'acc_amount' => $_GET['amount'],
        'acc_type' => '3',
        'acc_description' => "Bill collection full payment",
        'entry_by' => $userid,
        'entry_date' => $date_time,
        'update_by' => $userid
    );
    $service_add = $obj->insert_by_condition("tbl_account", $form_data, " ");


        $bill_amount = $_GET['amount'];
        $value2 = $obj->details_by_cond("tbl_agent", "ag_id='$token'");
        $mobile = isset($value2['ag_mobile_no']) ? $value2['ag_mobile_no'] : NULL;

// Company's name POST URL
        $postUrl = "http://api.bulksms.icombd.com/api/v3/sendsms/xml";
// XML-formatted data
//$mass="$sms_h $all_d $sms_b ";
        $smsbody = "Dear Customer Your Internet Bill-$bill_amount taka has been paid successfully.For any Query plz call 01621111777, 01621111666 .Thank you.";

        $xmlString =
            "<SMS>
    <authentification>
        <username>fastlinkbdsms1</username>
        <password>Wc24EpGr1</password>
    </authentification>
    <message>
        <sender>Fast Link</sender>
        <text>$smsbody</text>
    </message>
    <recipients>
        <gsm>88.$mobile</gsm>
    </recipients>
</SMS>";// previously formatted XML data becomes value of "XML" POST variable
        $fields = "XML=" . urlencode($xmlString);
// in this example, POST request was made using PHP's CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
// response of the POST request
        $response = curl_exec($ch);
        curl_close($ch);
// write out the response
// $response;


    if ($service_add) {
        $form_data3 = array(
            'pay_status' => 0
        );
        $due_list = $obj->Update_data("tbl_agent", $form_data3, "where ag_id='$token'");
        $details3 = $obj->details_by_cond("tbl_agent", "ag_id='$token'");
        extract($details3);
        $notification = 'Full Paid Successfully of , ' . $details3['ag_name'] . ", ID: " . $details3['cus_id'];

    } else {
        $notification = 'Full Paid Failed! try again';
    }

    }

}