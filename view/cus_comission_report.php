<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$intoken = isset($_GET['intoken']) ? $_GET['intoken'] : NULL;
$actoken = isset($_GET['actoken']) ? $_GET['actoken'] : NULL;

if (!empty($intoken)) {
    $form_data = array('cus_status' => '0', 'update_by' => $userid);
    $obj->Update_data("tbl_customer", $form_data, "where id='$intoken'");
}

if (!empty($actoken)) {
    $form_data = array('cus_status' => '1', 'update_by' => $userid);
    $obj->Update_data("tbl_customer", $form_data, "where id='$actoken'");
}

// ========== Delete Function Start =================
$dltoken = isset($_GET['dltoken']) ? $_GET['dltoken'] : NULL;
if (!empty($dltoken)) {
    $dele = $obj->Delete_data("tbl_customer", "id='$dltoken'");
    if (!$dele) {
        $notification = 'Delete Successfull';
    } else {

    }
}

$allAgentData = $obj->view_all("tbl_customer");
$totalCustomer = $obj->Total_Count("tbl_customer","cus_status != 0");
$totalRetailer = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 1");
$totalWorkshop = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 2");
$totalHouseowner = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 3");
$totalFeed = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 5");
$totalBlockMoney = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 6");
$totalSanatary = $obj->Total_Count("tbl_customer","cus_status != 0 and type = 7");
// ==========  Function End =================
?>
<style>
    .btn
    {
      padding: 6px 14px;
    }
</style>
<div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
        <b>View Customer Sales and Comission Report</b>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 4px;">
                <div id="client_list" class="btn-group btn-group-justified">
                    <form action="" method="post">

                        <button type="button" onclick="getComplains(this.value)" class="btn btn-primary" value="all">ALL(<?php echo $totalCustomer;?>)</button>

                        <button type="button" onclick="getComplains(this.value)" class="btn btn-success" value="1">Retailer(<?php echo $totalRetailer;?>)</button>

                        <button type="button" onclick="getComplains(this.value)" class="btn btn-warning" value = "2">Workshop(<?php echo $totalWorkshop;?>)</button>

                        <button type="button" onclick="getComplains(this.value)" class="btn btn-info" value="3">Houseowner(<?php echo $totalHouseowner;?>)</button>

                        <?php if ($ty == 'SA') { ?>
                        <a class="btn btn-primary" href="?q=add_customer">ADD NEW <span class="glyphicon
                    glyphicon-plus"></span></a>
                         <!--<a href="?q=print_customer_list" target="_blank" class="btn btn-primary">Print</a> -->
                    <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6" style="">
        
    </div>
</div>

<!-- all user show -->
<div id="div_all" class="row" style="padding:10px;font-size: 12px;">
    <div class="col-md-12">
      <div id="customer-list">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-slate-800">
                <tr>
                    <th class="col-md-1">Customer ID</th>
                    <th class="col-md-1">Customer Name</th>
                    <th class="col-md-1">Customer Company</th>
                    <th class="col-md-1">Mobile No</th>
                    <th class="col-md-2">Type</th>
                    <th class="col-md-1">Terget</th>
                    <th class="col-md-2">Total Comission</th>
                    <th class="col-md-2">Total Payment Recive</th>
                    <th class="col-md-3">Total Achieve Sales</th>
                    
                    
                </tr>
                </thead>
                <tbody>
                <?php
                $total_sales_comission2 = 0;
                $totalAchieve2 = 0;
                $totalPaymentRecive2 = 0;
                
                $i = '0';
                foreach ($allAgentData as $value) {
                    $id = $value['cus_id'];
                    $total_sales_comission = $obj->get_sum_data('tbl_sell','total_comission_earn',"customer='$id'");
                    $totalAchieve = $obj->get_sum_data('tbl_sell','total_price',"customer='$id'");
                    $totalPaymentRecive = $obj->get_sum_data('tbl_sell','payment_recieved',"customer='$id'");
                    $total_sales_comission2 += $total_sales_comission;
                    $totalAchieve2 += $totalAchieve;
                    $totalPaymentRecive2 +=$totalPaymentRecive;
                    $i++; ?>
               
                    <tr>
                        <td class="text-center">
                            <a class="btn btn-xs bg-grey-600 btn-default" href="?q=customer_ledger&customerId=<?php echo isset
                            ($value['cus_id']) ? $value['cus_id']:NULL;?>"><?php echo isset($value['cus_id']) ? $value['cus_id']:NULL;?></a>
                        </td>
                        <td class=""><?php echo isset($value['cus_name']) ? $value['cus_name'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['cus_company']) ? $value['cus_company'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['cus_mobile_no']) ? $value['cus_mobile_no'] : NULL; ?></td>
                        <td class=""><?php $type = $value['type']; 
                          if($type == 1){echo"Retailer";}elseif($type == 2){echo"Workshop";}elseif($type == 3){echo"Houseowner";}elseif($type == 5){echo"Feed";}elseif($type == 6){echo"Block Money";}
                          elseif($type == 7){echo"Sanatary";} else{echo"";}
                        ?></td>
                        <td class=""><?php echo isset($value['target']) ? $value['target'] : NULL; ?></td>
                        <td class=""><?php echo number_format($total_sales_comission,2) ?></td>
                        <td class=""><?php echo number_format($totalPaymentRecive,2) ?></td>
                        <td class=""><?php echo number_format($totalAchieve,2) ?></td>
                        
                        
                        
                        
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot class="bg-success">
                    <tr>
                        <th colspan="6"></th>
                        <th>Total : <?php echo number_format($total_sales_comission2) ?></th>
                        <th>Total : <?php echo number_format($totalAchieve2) ?></th>
                        <th>Total : <?php echo number_format($totalPaymentRecive2) ?></th>
                        
                    </tr>
                </tfoot>
            </table>
            
        </div>
     </div>
    </div>
</div>
<script type="text/javascript">
    function getComplains(value){
        if (value != '') {
            $.ajax({
                url:"view/ajax_view_customer.php",
                method:"POST",
                data:{customer_data:value},
                dataType:"text",
                success:function(data){
                    $('#customer-list').html(data);
                }
            });
        }
    }
    $(document).ready(function() {
            $("#datatable").dataTable().fnDestroy();
            $('#datatable').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                {
                    extend: 'print',
                    text: 'Print',
                    footer: true,
                
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8]
                    },
                    customize: function (win) {
                        $(win.document.body).css('font-size', '12px');
                        $(win.document.body).find('h1').addClass('text-center').css('font-size', '20px');
                        $(win.document.body).find('table').addClass('container').css('font-size', 'inherit');
                        $(win.document.body).find('table').removeClass('table-bordered');
                    }
                }
                ]
            } );
        } );
    
</script>
