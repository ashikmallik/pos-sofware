<?php
include '../model/Controller.php';
include '../model/FormateHelper.php';

$formater = new FormateHelper();
include '../lib/fpdf.php';
$obj = new Controller();
date_default_timezone_set('Asia/Dhaka');
$date =date('Y-m-d');

?>

<script>
    window.print();
</script>

<!-- Start Styles. Move the 'style' tags and everything between them to between the 'head' tags -->
<style type="text/css">
    .myTable { width: 90%; background-color:#eee;border-collapse:collapse; }
    .myTable th { background-color:#000; color:white; width:33.33%; }
    .myTable td, .myTable th { padding:5px; border:1px solid #000; }
</style>
<!-- End Styles -->
<?php
foreach ($details = $obj->view_all("vw_account") as $value){                                                              
?>
<div style="margin-left: 10%; width:80%; margin-top: 50px; " ><p style="text-align: right;" >Date:-<?php echo $date; ?></p></div>
<div style=" text-align: center; "><img src="ddd.jpg" style="height: 100px; width: 150px;" /></div>
<p style="margin-top: 50px; text-align: center; font-size: 30px; font-weight: bold;">
    ISP SOFTWARE COMPANY</br>
    <span style="font-size: 15px;">Managing Director: zzzz</span></br>
    <span style="font-size: 15px;">Cell: 00000-000000</span>
</p>
    
<div style="margin-left: 10%; margin-top: 20px;" >
    
    <table class="myTable">
        <tr>
            <th>Date</th>
            <th>Amount</th>
            <th>Received By</th>
        </tr>
        <tr>
            <td>
                <?php echo isset($value['entry_date'])?$value['entry_date']:NULL; ?>
            </td>
            <td style="text-align: right;">
                <?php echo isset($value['acc_amount'])?$value['acc_amount']:NULL; ?>
            </td>
            <td style="text-align: right;">
                <?php echo isset($value['FullName'])?$value['FullName']:NULL; ?>
                (<?php echo isset($value['UserName'])?$value['UserName']:NULL; ?>)
            </td>
        </tr>
        
    </table>   
    
</div>
<div style="width: 90%; float: right; margin-top: 200px; margin-right: 10%; margin-bottom: 70px;">
    <p style="float: right;">
            --------------------</br>
            Authorized Signature</br>            
        </p>
</div>
<?php
}
?>