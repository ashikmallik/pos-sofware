<script>
    window.print();
</script>

<?php   
    include '../model/Controller.php';
include '../model/FormateHelper.php';

$formater = new FormateHelper();
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body style="height: auto; width: 70%; padding-left: 15%;">
        <div style="height:120px; width: 100%; float: left; background-color:  #cccccc;">
            
        </div>
        <div style="height: auto; width:100%; float: left;">
        <table border="1" style="height: auto; width:100%;">
            <tbody>
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Payment Amount</th>
                </tr>
                <tr>
                    <td style="width: 50px;">1</td>
                    <td style="width: 200px;">15/11/2015</td>
                    <td style="width: 500px;">Customer</td>
                    <td style="width: 200px;">2200.00</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>15/11/2015</td>
                    <td>Customer</td>
                    <td>2200.00</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>15/11/2015</td>
                    <td>Customer</td>
                    <td>2200.00</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>15/11/2015</td>
                    <td>Customer</td>
                    <td>2200.00</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>15/11/2015</td>
                    <td>Customer</td>
                    <td>2200.00</td>
                </tr>
                <tr>
                    <td colspan="3"><span style="margin-left: 750px;">Total amount = </span></td>
                    
                    <td><strong>2200.00</strong></td>
                </tr>
            </tbody>
        </table>
            </div>
        <div style="float: left; height: 80px; width: 20%; margin-left:80%; margin-top: 100px; ">
            <span style="">__________________________</span><br>
            <span style="padding-left: 30px;">Administrator Signature</span>
        </div>
    </body>
</html>
