<?php
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$token = isset($_GET['token']) ? $_GET['token'] : NULL;

$data = $obj->details_by_cond('vw_user_info', "UserId='$token'");

if (isset($_POST['update'])) {

    if (!empty($_FILES["user_photo"]["name"])) {
//Start Random code Generator
        $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $rand = array_rand($seed, 6);
        $convert = array_map(function ($n) {
            global $seed;
            return $seed[$n];
        }, $rand);
        $character = implode('', $convert);

        $seed = str_split('1234567890');
        $rand = array_rand($seed, 4);
        $convert = array_map(function ($n) {
            global $seed;
            return $seed[$n];
        }, $rand);
        $digit = implode('', $convert);

        $rend_code = "BSTL" . "$character" . "$digit";

//End Random code Generator

        $nep = "asset/userphoto/" . $rend_code . ".jpg";
        if ($_FILES["user_photo"]["name"])

            if (copy($_FILES["user_photo"]["tmp_name"], $nep))
                $photo_path = "asset/userphoto/" . $rend_code . ".jpg";

        $user_photo_path = isset($photo_path) ? $photo_path : NULL;
    } else {
        $user_photo_path = isset($_POST['ph_path']) ? $_POST['ph_path'] : NULL;
    }
    extract($_POST);
    $form_data_create_user = array(
        'FullName' => $full_name,
        'UserName' => $user_name,
        'Email' => $email,
        'MobileNo' => $mobile_no,
        'Address' => $address,
        'PhotoPath' => $user_photo_path,
        'UpdateBy' => $userid
    );
    if ($obj->Update_data("_createuser", $form_data_create_user, "where UserId='$user_id'")) {

        if($_SESSION['UserType'] == 'SA'){
            $form_data_user_access = array(
                'UserType' => $user_type,
                'MenuPermission' => serialize( !empty($menu_permission) ? $menu_permission : []),
                'WorkPermission' => serialize( !empty($workPermission) ? $workPermission : []),
                'UpdateBy' => $userid
            );

            if($obj->Update_data("_useraccess", $form_data_user_access, "where UserId='$user_id'")){

                $obj->notificationStore('User '.$full_name.' Updated Successful', 'success');
                echo '<script>window.location = "?q=user_edit&token='.$user_id.'";</script>';

            }else{

                $obj->notificationStore('User '.$full_name.'Updated Failed' );
                echo '<script>window.location = "?q=user_edit&token='.$user_id.'";</script>';
            }
        }else{

            $obj->notificationStore('User '.$full_name.' Updated Successful. <small>Only Super Admin can change permission</small>', 'success');
            echo '<script>window.location = "?q=user_edit&token='.$user_id.'";</script>';
        }
    }
}

extract($data);

?>

<div class="col-md-12"
     style=" background-image:url(asset/img/content_h1.png); margin-top:20px; margin-bottom: 15px; min-height:40px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <b>Edit Form</b>
</div>
<div class="row">
    <div class="col-md-12 padding_5_px">
        <?php echo $obj->notificationShowRedirect(); ?>
    </div>
</div>

<form enctype="multipart/form-data" method="POST">
    <div class="row" style="padding:10px; font-size: 12px;">
        <div class="col-md-5 col-md-offset-1">

            <input type="hidden" name="user_id" value="<?php echo isset($token) ? $token : NULL; ?>"/>
            <input type="hidden" name="ph_path"
                   value="<?php echo isset($data['PhotoPath']) ? $data['PhotoPath'] : NULL; ?>"/>
            <div class="form-group">
                <label for="exampleInputEmail1">Full Name</label>
                <input type="text" name="full_name"
                       value="<?php echo isset($data['FullName']) ? $data['FullName'] : NULL; ?>" class="form-control"
                       id="exampleInputEmail1" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">User Name</label>
                <input type="text" name="user_name"
                       value="<?php echo isset($data['UserName']) ? $data['UserName'] : NULL; ?>" class="form-control"
                       id="exampleInputEmail1" placeholder="User Name" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email Address</label>
                <input type="email" name="email" value="<?php echo isset($data['Email']) ? $data['Email'] : NULL; ?>"
                       class="form-control" id="exampleInputEmail1" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Mobile No</label>
                <input type="text" name="mobile_no"
                       value="<?php echo isset($data['MobileNo']) ? $data['MobileNo'] : NULL; ?>" class="form-control"
                       id="exampleInputEmail1" placeholder="Mobile No" required>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">Address</label>
                <input type="text" name="address"
                       value="<?php echo isset($data['Address']) ? $data['Address'] : NULL; ?>" class="form-control"
                       id="exampleInputEmail1" placeholder="Address" required>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">User Type</label>
                <select name="user_type" class="form-control" style="margin-bottom: 5px;" required>
                    <option></option>
                    <?php if($ty=='SA'){ ?>
                        <option <?php if ($data['UserType'] == 'SA') echo 'selected'; ?> value="SA">Supper Admin</option>
                        <option <?php if ($data['UserType'] == 'EO') echo 'selected'; ?> value="EO">Entry Operator</option>
                    <?php }else{ ?>
                        <option <?php if ($data['UserType'] == 'EO') echo 'selected'; ?> value="EO">Entry Operator</option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <div class="form-group" style="border: 1px solid #CCCCCC; padding: 5px; border-radius:4px;">
                    <label class="checkbox-inline">
                        <input type="checkbox" <?php $m = $data['WorkPermission'];
                        $access = explode(',', $m);
                        foreach ($access as $tkey) {
                            if ($tkey == 'add') {
                                echo "checked='checked'";
                            }
                        } ?>
                               name="WorkPermission[]" class="wclschekbox" id="inlineCheckbox1" value="add"
                               onclick="work()"> Add
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" <?php $m = $data['WorkPermission'];
                        $access = explode(',', $m);
                        foreach ($access as $tkey) {
                            if ($tkey == 'view') {
                                echo "checked='checked'";
                            }
                        } ?>
                               name="WorkPermission[]" class="wclschekbox" id="inlineCheckbox2" value="view"
                               onclick="work()"> View
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" <?php $m = $data['WorkPermission'];
                        $access = explode(',', $m);
                        foreach ($access as $tkey) {
                            if ($tkey == 'edit') {
                                echo "checked='checked'";
                            }
                        } ?>
                               name="WorkPermission[]" class="wclschekbox" id="inlineCheckbox3" value="edit"
                               onclick="work()"> Edit
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" <?php $m = $data['WorkPermission'];
                        $access = explode(',', $m);
                        foreach ($access as $tkey) {
                            if ($tkey == 'delete') {
                                echo "checked='checked'";
                            }
                        } ?>
                               name="WorkPermission[]" class="wclschekbox" id="inlineCheckbox3" value="delete"
                               onclick="work()"> Delete
                    </label>
                    <input type="hidden" name="workaccess" id="workaccess"/>
                </div>
            </div>

        </div>

        <div class="col-md-5">
            <div class="row">
                <div class="col-md-4">
                    <?php if ($data['PhotoPath'] == '0') { ?>
                        <div class="form-group">
                            <img width="140" height="140" src="asset/img/def_img.png" alt="..." class="img-thumbnail"
                                 id="pre_photo">
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="form-group">
                            <img width="140" height="140"
                                 src="<?php echo isset($data['PhotoPath']) ? $data['PhotoPath'] : NULL; ?>" alt="..."
                                 class="img-thumbnail" id="pre_photo">
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="exampleInputFile">Chose Photo</label>
                        <input type="file" name="user_photo" onchange="usershow_photo(this)" id="photo">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label>Menu Permission</label>
                </div>
                <div class="col-md-6">
                    <label class="btn-primary btn-xs pointer">
                        <input type="checkbox" id="select_all_menu_permission"><b> Select All</b>
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('usercreate', $token)) ? 'checked' : '' ?> value="usercreate">Add User</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('view_user', $token)) ? 'checked' : '' ?> value="view_user">View All User</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('all_category', $token)) ? 'checked' : '' ?> value="all_category">All Category</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('all_item', $token)) ? 'checked' : '' ?> value="all_item">All Item</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('add_customer', $token)) ? 'checked' : '' ?> value="add_customer">Add Customer</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('view_customer', $token)) ? 'checked' : '' ?> value="view_customer">Customer Info</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('add_customer', $token)) ? 'checked' : '' ?> value="add_supplier">Add Supplier</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('view_supplier', $token)) ? 'checked' : '' ?> value="view_supplier">Supplier Info</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('add_purchase', $token)) ? 'checked' : '' ?> value="add_purchase">Add Purchase</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('view_all_purchase', $token)) ? 'checked' : '' ?> value="view_all_purchase">View Purchase</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('add_sell', $token)) ? 'checked' : '' ?> value="add_sell">Add Sell</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('view_all_sell', $token)) ? 'checked' : '' ?> value="view_all_sell">View Sell</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('add_payment_person', $token)) ? 'checked' : '' ?> value="add_payment_person">Add Payment Person</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('account_statement', $token)) ? 'checked' : '' ?> value="account_statement">Account Statement</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('stock_report', $token)) ? 'checked' : '' ?> value="stock_report">Stock Report</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('income_report', $token)) ? 'checked' : '' ?> value="income_report">Income Report</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('expense_report', $token)) ? 'checked' : '' ?> value="expense_report">Expense Report</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('purchase_report', $token)) ? 'checked' : '' ?> value="purchase_report">Purchase Report</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('sale_report', $token)) ? 'checked' : '' ?> value="sale_report">Sale Report</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('expense', $token)) ? 'checked' : '' ?> value="expense">Expense</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('sale_report', $token)) ? 'checked' : '' ?> value="sale_report">Company Ledger</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('balance_sheet', $token)) ? 'checked' : '' ?> value="balance_sheet">Balance Sheet</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('add_loan_person', $token)) ? 'checked' : '' ?> value="add_loan_person">Add Loan Person</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('loan_list', $token)) ? 'checked' : '' ?> value="loan_list"> Loan List</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('take_loan', $token)) ? 'checked' : '' ?> value="take_loan"> Take Loan</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('add_security_money', $token)) ? 'checked' : '' ?> value="add_security_money">Add Security Money</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('view_security_money', $token)) ? 'checked' : '' ?> value="view_security_money"> View Security Money</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('add_employee', $token)) ? 'checked' : '' ?> value="add_employee">Add Employee</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('view_employee', $token)) ? 'checked' : '' ?> value="view_employee"> View Employee</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('add_bnk', $token)) ? 'checked' : '' ?> value="add_bnk">Add Bank Info</label>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">

                        <div class="checkbox">
                            <label><input type="checkbox" name="menu_permission[]" <?php echo ($obj->userHasPermission('view_bank', $token)) ? 'checked' : '' ?> value="view_bank"> View Bank Info</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 0px;">



        </div>


    </div>

    <div class="row" style="padding: 5px 0px 15px 0px; font-size: 12px; text-align: center;">
        <button type="submit" name="update" class="btn btn-success">Update</button>
    </div>
</form>


<!--============== jquery part ===================-->

<script>
    $(document).ready(function () {
        $('select[name="zone"]').select2({
            placeholder: "Select a Zone",
            allowClear: true
        });
    })
</script>