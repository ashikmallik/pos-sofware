<?php
include '../model/Controller.php';
include '../model/FormateHelper.php';

$formater = new FormateHelper();
$obj = new Controller();

$token = isset($_GET['token'])? $_GET['token']:NULL;

$details = $obj->details_by_cond("vw_account","acc_id='$token'");
if (!empty($details)) {
    extract($details);
}

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
<div style="margin-top: 50px; text-align: center; "><img src="ddd.jpg" style="height: 100px; width: 150px;" /></div>
<p style="margin-top: 50px; text-align: center; font-size: 30px; font-weight: bold;">
    ROMAN TRAVELS AND TOURS</br>
    <span style="font-size: 15px;">Proprietor: Md. Repon Hossain</span></br>
    <span style="font-size: 15px;">Cell: 01911-742948</span>
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
                <?php echo isset($details['entry_date'])?$details['entry_date']:NULL; ?>
            </td>
            <td style="text-align: right;">
                <?php echo isset($details['acc_amount'])?$details['acc_amount']:NULL; ?>
            </td>
            <td style="text-align: right;">
                <?php echo isset($details['FullName'])?$details['FullName']:NULL; ?>
                (<?php echo isset($details['UserName'])?$details['UserName']:NULL; ?>)
            </td>
        </tr>
        
    </table>   
    
</div>
<div style="width: 90%; float: right; margin-top: 200px; margin-right: 10%;">
    <p style="float: right;">
            --------------------</br>
            Authorized Signature
        </p>
</div>