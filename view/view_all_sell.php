<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$sell_cat = 3; // for accounts table cat





if (isset($_POST['search'])) {

    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));
    

    $allSellData = $obj->view_all_by_cond("vw_sell", "entry_date BETWEEN '$startDate' AND '$endDate' order by entry_date");

    $print = 'action=search&startDate=' . $startDate . '&endDate=' . $endDate . '';
    $header = 'Between ' . date('d-M-Y', strtotime($startDate)) . ' To ' . date('d-M-Y', strtotime($endDate)) . '';
} else {

    $allSellData = $obj->view_all_ordered_by("vw_sell", "`vw_sell`.`sell_id` DESC Limit 200 ");
}


if (isset($_GET['deleteSellId']) && !empty($_GET['deleteSellId'])) {

    $deleteId = $_GET['deleteSellId'];
    $obj->Delete_data('tbl_sell_item', "sell_no = '$deleteId'");
    $obj->Delete_data('tbl_sell', "sell_id = '$deleteId'");
    $obj->Delete_data('tbl_account', "purchase_or_sell_id = 's_$deleteId'");
    $obj->Delete_data('tbl_account', "purchase_or_sell_id = 's_l_$deleteId'");
    $obj->Delete_data('tbl_account', "purchase_or_sell_id = 's_t_$deleteId'");
    $obj->Delete_data('tbl_sell_invoice', "sell_id = '$deleteId'");
    ?>
    <script>
        window.location = '?q=view_all_sell';
    </script>
    <?php }


if (isset($_POST['addPayment'])) {

    if(!empty($_POST['payment']) && isset($_POST['payment'])){
        $purchaseData = $obj->details_by_cond('tbl_sell', "sell_id = ".$_POST['sellId']);

        $totalPrice = $purchaseData['total_price'];
        $previousPayment = $purchaseData['payment_recieved'];
        $newPayment = $_POST['payment'] + $previousPayment;
        $newDue = $totalPrice - $newPayment;

        $form_purchase_update = array(
            'payment_recieved' => $newPayment,
            'due_to_company' => $newDue,
            'update_by' => $userid
        );
        $obj->Update_data("tbl_sell", $form_purchase_update, "sell_id=".$_POST['sellId']);

        $form_tbl_accounts = array(

            'acc_description' => "Company Receipt from Customer (id = ".$_POST['sellId'].")." . $_POST['comments'],
            'acc_amount' => $_POST['payment'],
            'purchase_or_sell_id' => 's_'.$_POST['sellId'],
            'acc_type' => $sell_cat,
            'payment_method'=>1,
            'cus_or_sup_id' => $_POST['customerId'],
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );

        $tbl_accounts_add = $obj->insert_by_condition("tbl_account", $form_tbl_accounts, " ");
    }

    ?>
    <script>
        window.location = '?q=view_all_sell';
        window.open('pdf/invoice.php?invoiceId=<?php echo $_POST['sellId'] ?>', '_blank');
    </script>
    <?php } ?>


<link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js"></script>

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
<?php echo isset($notification) ? $notification : NULL; ?>
<div class="col-md-12 bg-teal-600" style="margin-top:20px; margin-bottom: 15px;">
 
        <h4 style="text-align:center"><strong>View All Sell List</strong></h4>
 
     <form action="" method="POST">
        <div class="col-md-3">
            <div class="form-group">
                
                    <input class="form-control" required="required" type="text" Placeholder="Start Date" name="startDate" autocomplete="off">
              
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                
                    <input type="text" class="form-control" required="required"  Placeholder="Start Date" name="endDate" autocomplete="off">
               
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-default"><span
                        class="glyphicon  glyphicon-search"></span>&nbsp;&nbsp;Search
            </button>
        </div>
    </form>
    <div class="col-md-3" style="padding-top:5px;">
        <?php if ($ty == 'SA'){ ?>
            <a class="btn btn-success btn-sm pull-right" href="?q=add_sell">ADD NEW <span class="glyphicon
        glyphicon-plus"></span></a>
            <!--<a href="?q=print_sell" target="_blank" class="btn btn-success btn-sm pull-right">Print Sell List</a>-->
        <?php } ?>
    </div>
</div>
<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-slate-800">
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-2">Customer</th>
                    <th class="text-center col-md-1">Total Qty</th>
                    <th class="text-center col-md-1">Total Bill</th>
                    <th class="text-center col-md-1">Payment</th>
                    <th class="text-center col-md-1">Dues</th>
                    <th class="text-center col-md-1">Bill Date</th>
                    <th class="text-center col-md-4">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $sumOfTotalPrice = 0;
                $sumOfPayment = 0;
                $sumOfDue = 0;

                foreach ($allSellData as $sell) {

                    $i++;
                    $sell_id = $sell['sell_id'];
                    $sellItemData = $obj->view_all_by_cond("vw_sell_item", "sell_id=$sell_id");
                    $unit = isset($sellItemData[0]['unit'])? $sellItemData[0]['unit']: NULL;
                    
                    $invoiceData = $obj->details_by_cond("vw_sell", "sell_id='$sell_id'");
                    $ltCost= $invoiceData['laborcost']+$invoiceData['transportcost'];
                    
                

                    $iframButton = '<button href="view/view_sell_item.php?invoiceId=' . $sell_id . '" class="btn btn-warning bg-teal-700 btn-xs open-popup-link" data-effect="mfp-zoom-in">' . $sell['total_qty'] ." ".$unit.'</a></button>'; ?>
                    <tr>
                        <td class="text-center">
                            <strong><?php echo $i; ?></strong>&nbsp;
                        </td>
                        <td class="" style="padding-top:5px">
                            <a class="padding_5_px btn-xs btn-default" href="?q=customer_ledger&customerId=<?php echo isset
                            ($sell['customer']) ? $sell['customer'] : NULL; ?>"><?php echo isset($sell['customer_name']) ? $sell['customer_name'] : null; ?></a>
                        </td>
                        <td class="" style="padding-top:14px;"><?php echo isset($sell['total_qty']) ? $iframButton : NULL; ?></td>
                        <td class="text-right">
                             Sell: <?php   $sumOfTotalPrice += $sell['total_price'];   echo isset($sell['total_price']) ? number_format($sell['total_price']) . ' TK.' : NULL; ?>
                             L/T.Cost: <?php echo $ltCost; ?> TK.
                             Total: <?php echo $ltCost+$sell['total_price']; ?> TK.
                        </td>
                        <td class="text-right">
                            Sell Payment : <?php $sumOfPayment += $sell['payment_recieved']; echo isset($sell['payment_recieved']) ? number_format($sell['payment_recieved']) . ' TK.' : NULL; ?>
                            L/T.Cost: <?php echo  ($sell['payment_recieved'] > 0)? $ltCost:0; ?> TK.
                            Total Payment: <?php echo ($sell['payment_recieved']>0)?$ltCost+$sell['payment_recieved']:0; ?> TK.
                        </td>
                        <td class="text-right"><?php $sumOfDue += $sell['due_to_company']; echo isset($sell['due_to_company']) ? number_format($sell['due_to_company']) . ' TK.' : NULL; ?></td>
                        <td class="text-center"><?php echo isset($sell['entry_date']) ? date('d-M-y', strtotime($sell['entry_date'])) : NULL; ?></td>
                        <td class="text-center">
                            <div class="btn-group" style="margin-top:5px">
                                <a type="button" data-name = "<?php echo isset ($sell['customer_name']) ? $sell['customer_name'] : null; ?>" data-customer = "<?php echo isset($sell['customer']) ? $sell['customer'] : NULL; ?>" data-sell_id = "<?php echo isset($sell['sell_id']) ? $sell['sell_id'] : NULL; ?>" data-toggle="modal" data-target="#addPriceModel" class="btn bg-teal btn-success btn-xs">
                                    <span class="glyphicon glyphicon-usd"></span> Receipt
                                </a>
                                <?php echo '<a type="button" href="?q=edit_sell&invoiceId=' . $sell['sell_id'] . '" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span> Edit</a>'; ?>
                                <?php if ($sell['delivery_status']==1){?>
                                    <button type="button" disabled class="btn btn-primary btn-xs">Delivered</button>
                                <?php } else {?>
                                    <a type="button" href="?q=delivery_sell&invoiceId=<?php echo $sell['sell_id']?>" class="btn bg-slate-700 btn-info btn-xs"><span class="glyphicon glyphicon-transfer"></span> Delivery</a>
                                <?php } ?>
                                <a type="button"
                                   onclick="return confirm('Are you sure you want to delete this Sell item?');"
                                   href="?q=view_all_sell&deleteSellId=<?php echo $sell['sell_id']; ?>"
                                   class="btn btn-danger btn-xs">
                                    <span class="glyphicon glyphicon-trash"></span> Delete
                                </a>
                                <a type="button" target="_blank" href="pdf/invoice.php?invoiceId=<?php echo $sell_id ?>"
                                   class="btn bg-grey-800 btn-default btn-xs">
                                    <span class="glyphicon glyphicon-print"></span> Print</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-center col-md-4">Total</th>
                        <th class="text-center col-md-1"><?php echo number_format($sumOfTotalPrice); ?> Tk.</th>
                        <th class="text-center col-md-1"><?php echo number_format($sumOfPayment); ?> Tk.</th>
                        <th class="text-center col-md-1"><?php echo number_format($sumOfDue);  ?> Tk.</th>
                        <th colspan="2" class="col-md-5"></th>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="addPriceModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Add Receipt for this Sell and Customer <b><span id="customerNameModal" class="text-grey-800"> </span></b></h4>
            </div>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group" style="margin-bottom:20px;">
                            <label class="control-label col-sm-4" for="payment">Add Receipt </label>
                            <div class="col-sm-6">
                                <input type="text" onkeypress="return numbersOnly(event)" name="payment" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" style="margin-top:20px;">
                            <label class="control-label col-sm-4" for="comments">Comments </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="comments" placeholder="" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="customerId" value="">
                    <input type="hidden" name="sellId" value="">
                </div>
                <div class="modal-footer text-center">
                    <div class="row">
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" name="addPayment">Add Receipt</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
    });
    
    $(document).ready(function () {
        $(document).on('click', '.open-popup-link', function () {
            $(this).magnificPopup({
                type: 'iframe',
                iframe: {
                    markup: '<div class="col-md-12">' +
                    '<div class="mfp-iframe-scaler" >' +
                    '<div class="mfp-close"></div>' +
                    '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                    '</div>' +
                    '</div>'
                },
                callbacks: {
                    beforeOpen: function () {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    },
                },
                enableEscapeKey: false,
                midClick: true

            }).magnificPopup('open');

        });

        $('#datatable').on('click', '[data-target="#addPriceModel"]', function () {
            var customerId = $(this).data('customer');
            var customerName = $(this).data('name');
            var sellId = $(this).data('sell_id');

            $('div#addPriceModel input[name="customerId"]').val(customerId);
            $('div#addPriceModel input[name="sellId"]').val(sellId);
            $('div#addPriceModel span#customerNameModal').html(customerName);

        });
    });
    
    
        $(document).ready(function() {
        $("#datatable").dataTable().fnDestroy();
        $('#datatable').DataTable( {
            "ordering": false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print Sell List',
                    footer: true,
                    title: function () {
                        return "View All Sell List <?php echo date('d, M Y')?>"
                    },
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
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