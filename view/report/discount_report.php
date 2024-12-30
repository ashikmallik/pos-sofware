<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$user_branch=$obj->details_by_cond("_createuser","UserId='$userid'");
$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;

if(isset($_POST['search'])){
    extract($_POST);
    $startDate  = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate  = date('Y-m-d', strtotime($_POST['endDate']));

     $discount = $obj->view_all_by_cond("discount", "entry_date BETWEEN '$startDate' AND '$endDate' AND amount > 0 ORDER BY id DESC");
}else{
    $discount = $obj->view_all_by_cond("discount", "amount > 0  ORDER BY id DESC");
}


//  var_dump($discount);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">
<script>

    function numbersOnly(e) // Numeric Validation
    {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) {
            if ((unicode < 2534 || unicode > 2543) && (unicode < 46 || unicode > 57)) {
                return false;
            } else if (unicode == 47) {
                return false;
            }
        }
    }
</script>
<div class="col-md-12 bg-slate-800" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View  Discount List </strong></h4>
    </div>
</div>

<div class="col-md-12 bg-slate-800" style="margin-bottom: 20px;padding:5px 0px">
    <form action="" method="POST">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="dateMonth">Select Start Date</label>
                <div class="col-sm-7">
                    <input class="form-control" value="<?php echo isset($_GET['startDate']) ? $_GET['startDate'] :
                        null; ?>" required="required" type="text" name="startDate" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label col-sm-5" style="padding-top:5px" for="damageRate">Select End Date</label>
                <div class="col-sm-7">
                    <input type="text" value="<?php echo isset($_GET['endDate']) ? $_GET['endDate'] :
                        null; ?>" class="form-control" required="required" name="endDate" autocomplete="off">
                </div>
            </div>
        </div>
        <input type="hidden" name="q" value="expense_report">
        <input type="hidden" name="action" value="search">
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default"><span class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
</div>

<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="example">
                <thead class="bg-grey-800">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Discount Amount</th>
                    <th>Description</th>
                    <th>Entry User</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = '0';
                $totalamount = 0;
                foreach ($discount as $value) {
                   
                        
                      $customerid =  $value['cus_or_sup_id'];
                      $userid=  $value['entry_by'] ;
                      
                      
                       $damount=  $value['amount'] ;
                       $ddate=  $value['entry_date'] ;
                      
                      
                        $customer = $obj->details_by_cond('tbl_customer', "cus_id ='$customerid'" );
                        $suplier = $obj->details_by_cond('tbl_supplier', "supplier_id ='$customerid'" );
                        $user = $obj->details_by_cond('_createuser', "UserId ='$userid'" );
                     
                        $discountdetails = $obj->details_by_cond('tbl_account', " cus_or_sup_id ='$customerid' AND entry_by='$userid' AND entry_date ='$ddate'  AND acc_amount='$damount' " );
                       
                       
                     
                   
                    $i++;
                    $totalamount += $value['amount'];
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo date("d-M-Y", strtotime(isset($value['entry_date']) ? $value['entry_date'] : "2016-02-1")); ?></td>
                        <td><?php echo $customerid; ?></td>
                        <td><?php  if($customer){ echo  $customer['cus_name']; }elseif($suplier){ echo  $suplier['supplier_name'].' ( Supplier )'; } ?></td>
                        <td class="text-right"><?php echo isset($value['amount']) ? number_format($value['amount']) . ' tk' : NULL; ?></td>
                       <td><?php  if($discountdetails){ echo  $discountdetails['acc_description']; } ?></td>
                       <td><?php  if($user){ echo  $user['FullName']; } ?></td>
                    </tr>
                    <?php  }?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="4">Total </th>
                    <th colspan="1" class="text-right"><?php echo number_format($totalamount);?> Tk</th>
                    <th></th>
                    <th></th>
                    
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function () {
        $('input[name="startDate"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });

        $('input[name="endDate"]').datepicker({
            autoclose: true,
            toggleActive: true,
            format: 'dd-mm-yyyy'
        });


        $(document).on('mouseover', 'tbody tr td [data-toggle="tooltip"]', function () {
            $('tbody tr td [data-toggle="tooltip"]').tooltip();
        });

    });

    $(document).ready(function() {
        $("#example").dataTable().fnDestroy();
        $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print ',
                    title: function () {
                        return "Discount Report "
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

<script type="application/javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="application/javascript"
        src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>