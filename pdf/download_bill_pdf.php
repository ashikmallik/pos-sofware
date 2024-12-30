<?php

include '../model/oop.php';
include '../model/Bill.php';
include '../model/FormateHelper.php';
$obj = new Controller();
$bill = new Bill();
$formater = new FormateHelper();

// Set the timezone and other variables
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$currentYear = date("Y");
$currentMonth = date("m");
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$token = isset($_GET['token1']) ? $_GET['token1'] : NULL;
$notification = "";
$ontime = $obj->get_sum_by_cond("acc_amount", "tbl_account", "company_id='$token' and acc_type=4 ");
$total_bill = $obj->get_sum_by_cond("taka", "tbl_agent", "company_id='$token'");
$com_name = $obj->details_by_cond("tbl_company", "company_id='$token'");

// Fetch the data for the current month and year
$allDuePayment = $obj->view_all_by_cond("vw_agent", "company_id='$token'");


// Header content - Will be repeated on every page
$header= '
    <header>
    <div><img style="" src="header.png" alt="Header Image" /></div>
    <hr>
    </header>
';

// Footer content - Will be repeated on every page
$footer = '
    <footer>
        <div style="background-color:#03086e;color:white;text-align:center; padding:6px;">
            Nadi Bangla tower, cth floor,Z1441, Main road, maijdee, Noakhali. cell: +8801688 626822, +8801932583299, E-mail: zero4network@gmail.com
        </div>
        
    </footer>
';

// Start HTML content for the PDF body
// Start HTML content for the PDF body
$html = '
    <div style="font-family: Helvetica; font-size: 12px; padding:5px;">
        
        <div style="font-size: 12px; font-weight: bold; margin-top:-100px">
            <div style="margin-left:70%;">Date: ' . date('d-F-Y') . '</div>
            <div><b>Bill Ref No :</b></div>
            <div style="margin-bottom:10px">
                <b>Bill to :' . $com_name['company_name'] . '</b><br>
                ' . $com_name['company_address'] . '<br>
                <b>Description of Bill :</b>Bill for ' . Date('F') . '-' . Date('Y') . ' monthly basis
            </div>
        </div>
        
        <table border="1" cellpadding="3" style="border-collapse: collapse; font-size:11px;">
            <thead>
                <tr style="background-color: #5cb85c; color: white; height: 25px;margin-top:-100px">
                    <th>Sl</th>
                    <th>Zone</th>
                    <th>Area</th>
                    <th>Client Name</th>
                    <th>District</th>
                    <th>Service Start Date</th>
                    <th>One-time Installation</th>
                    <th>Regular Bill</th>
                </tr>
            </thead>
            <tbody>';

$row_index = 1;
$zone_count = 0;
$area_count = 0;
$rowspan_data = [];

// Group data by zone and area for rowspan
foreach ($allDuePayment as $key => $value) {
    $zone = $value['zone_name'];
    $area = $value['sub_zone_name'];
    $rowspan_data[$zone][$area][] = $value;
}

// Render rows with rowspan logic
foreach ($rowspan_data as $zone => $areas) {
    foreach ($areas as $area => $rows) {
        $zone_rowspan = count($rows);
        $area_rowspan = count($rows);
        foreach ($rows as $index => $row) {
            $is_first_zone_row = $index == 0;
            $is_first_area_row = $index == 0;
            
            $html .= '<tr style="height: 25px;">'; // Adjust row height to fit better
            $html .= '<td>' . $row_index++ . '</td>';
            
            if ($is_first_zone_row) {
                $html .= '<td rowspan="' . $zone_rowspan . '">' . $zone . '</td>';
            }

            if ($is_first_area_row) {
                $html .= '<td rowspan="' . $area_rowspan . '">' . $area . '</td>';
            }

            $html .= '<td>' . $row['link_name'] . '</td>';
            $html .= '<td>' . $row['district'] . '</td>';
            $html .= '<td>' . $row['connection_date'] . '</td>';
            $html .= '<td>' . $row['connect_charge'] . '</td>';
            $html .= '<td>' . number_format($row['taka'], 2, ".", ",") . '</td>';
            $html .= '</tr>';
        }
    }
}

// Total and Grand Total
$grand_total = $ontime + $total_bill;

// Calculate 5% VAT
$vat_percentage = $com_name['vat']; // 5%
$vat_amount = ($grand_total * $vat_percentage) / 100;
$grand_total_with_vat = $grand_total + $vat_amount; // Total including VAT

// Check if VAT is set (not empty) and greater than 0
if (!empty($com_name['vat']) && $com_name['vat'] > 0) {
    // Calculate VAT amount if VAT is available
    $vat_percentage = $com_name['vat']; // Get VAT percentage dynamically
    $vat_amount = ($grand_total * $vat_percentage) / 100; // Calculate VAT
    $grand_total_with_vat = $grand_total + $vat_amount; // Total including VAT

    // Display VAT row
    $html .= '
        <tr style="height: 25px;">
            <td colspan="6"></td>
            <th><b>Sub total: ' . number_format($ontime, 2, ".", ",") . '</b></th>
            <th><b>Total: ' . number_format($total_bill, 2, ".", ",") . '</b></th>
        </tr>
        <tr>
            <td colspan="6"></td>
            <th colspan="2">
                <p style="text-align: right;">
                    <b>VAT ' . number_format($vat_percentage, 2) . '% : </b>' . number_format($vat_amount, 2, ".", ",") . '
                </p>
            </th>
        </tr>
        <tr style="height: 25px;">
            <td colspan="6"></td>
            <th colspan="2">
                <p style="text-align: right;">
                    <b>Grand Total : ' . number_format($grand_total_with_vat, 2, ".", ",") . '</b>
                </p>
            </th>
        </tr>
        <tr style="height: 25px;">
            <td colspan="8" style="text-align: center;">
                <span style="color: green;">Total Amount in Words: <span style="color: red;">' . ucwords($formater->convert_number_to_words($grand_total_with_vat)) . ' Taka only</span></span>
            </td>
        </tr>';
} else {
    // If VAT is not set, just show the total without VAT
    $html .= '
        <tr style="height: 25px;">
            <td colspan="6"></td>
            <th><b>Sub total: ' . number_format($ontime, 2, ".", ",") . '</b></th>
            <th><b>Total: ' . number_format($total_bill, 2, ".", ",") . '</b></th>
        </tr>
        <tr style="height: 25px;">
        <td colspan="6"></td>
            <td colspan="2" style="text-align: center;">
                <b>Grand Total : ' . $grand_total . ' Taka only</b>
            </td>
        </tr>
        <tr style="height: 25px;">
            <td colspan="8" style="text-align: center;">
                <span style="color: green;">Total Amount in Words: <span style="color: red;">' . ucwords($formater->convert_number_to_words($grand_total)) . ' Taka only</span></span>
            </td>
        </tr>
        ';
}

$html .= '
    </tbody>
</table>
</div>
';



// Set up mPDF and define the header and footer
define('_MPDF_PATH', './');
include("./mpdf.php");

$mpdf = new mPDF('c', 'A4', '', '', 10, 10, 30, 20, 10, 10);
$mpdf->SetWatermarkImage('logo.png', -3, [140, 140], 'C');
$mpdf->showWatermarkImage = true;
// $mpdf->autoPageBreak = true;
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("| Developed By BSD");
$mpdf->SetAuthor("BSD.");
$mpdf->SetDisplayMode('fullpage');

// Define header and footer
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);

// Write HTML content to PDF
$mpdf->WriteHTML($html);
$mpdf->Output('bill_report.pdf', 'D');

exit;


?>
