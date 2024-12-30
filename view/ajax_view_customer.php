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
    $expenseType = 1;
    if (isset( $_POST['customer_data'])) {
    $customer_data  = $_POST['customer_data'];
    if ($customer_data == "1") {
        $allAgentData =$obj->view_all_by_cond("tbl_customer","type='1'");
    }elseif ($customer_data == "2") {
        $allAgentData =$obj->view_all_by_cond("tbl_customer","type='2'");
    }elseif ($customer_data == "3") {
        $allAgentData =$obj->view_all_by_cond("tbl_customer","type='3'");
    }
    elseif ($customer_data == "4") {
        $allAgentData =$obj->view_all_by_cond("tbl_customer","type='4'");
    }
    elseif ($customer_data == "5") {
        $allAgentData =$obj->view_all_by_cond("tbl_customer","type='5'");
    }
    elseif ($customer_data == "6") {
        $allAgentData =$obj->view_all_by_cond("tbl_customer","type='6'");
    }
    elseif ($customer_data == "7") {
        $allAgentData =$obj->view_all_by_cond("tbl_customer","type='7'");
    }else{
            $allAgentData =$obj->view_all("tbl_customer");
    }
}
    ?>
    <a href="?q=print_view_customer&token=<?php echo $customer_data ?>" id="print_link" target="_blank" style="margin-top: -35px;" class="btn btn-primary btn-xs pull-left" >Print Client List</a>
    <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-slate-800">
                <tr>
                    <th class="col-md-1">Customer ID</th>
                    <th class="col-md-1">Customer Name</th>
                    <th class="col-md-1">Customer Company</th>
                    <th class="col-md-1">Mobile No</th>
                    <th class="col-md-1">Email</th>
                    <th class="col-md-3">Address</th>
                    <th class="col-md-3">Type</th>
                    <th class="col-md-1">Create Date</th>
                    <th class="col-md-1">Action</th>
                </tr>
                </thead>
                <?php
                $i = '0';
                foreach ($allAgentData as $value) {
                    $i++; ?>
                    <tr>
                        <td class="text-center">
                            <a class="btn btn-xs bg-grey-600 btn-default" href="?q=customer_ledger&customerId=<?php echo isset
                            ($value['cus_id']) ? $value['cus_id']:NULL;?>"><?php echo isset($value['cus_id']) ? $value['cus_id']:NULL;?></a>
                        </td>
                        <td class=""><?php echo isset($value['cus_name']) ? $value['cus_name'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['cus_company']) ? $value['cus_company'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['cus_mobile_no']) ? $value['cus_mobile_no'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['cus_email']) ? $value['cus_email'] : NULL; ?></td>
                        <td class=""><?php echo isset($value['cus_address']) ? $value['cus_address'] : NULL; ?></td>
                        <td class=""><?php $type = $value['type']; 
                          if($type == 1){echo"Retailer";}elseif($type == 2){echo"Workshop";}elseif($type == 3){echo"Houseowner";}elseif($type == 5){echo"Feed";}elseif($type == 6){echo"Block Money";}
                          elseif($type == 7){echo"Sanatary";} else{echo"N/A";}
                        ?></td>
                        <td class="text-center"><?php echo isset($value['entry_date']) ? date('d-m-Y', strtotime($value['entry_date'])) : NULL; ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a class="btn btn-xs bg-teal btn-primary padding_2_10_px" href="?q=edit_customer&token=<?php echo isset ($value['id']) ? $value['id'] : NULL ?>">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </a>
                                <a href="?q=view_customer&dltoken=<?php echo isset($value['id']) ? $value['id'] : NULL;?>" onclick="return confirm('Are you sure you want to delete this Customer?');" class="btn btn-xs btn-danger padding_2_10_px">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
            </table>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
            $('#datatable').DataTable();

        });
        </script>
        <script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
        <script type="application/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>