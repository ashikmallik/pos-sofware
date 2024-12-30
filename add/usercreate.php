<?php

date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
     
//$date        = date('Y-m-d');
$ip_add      = $_SERVER['REMOTE_ADDR'];

$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;

if(isset($_POST['submit'])){

//Start Random code Generator
$seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
$rand = array_rand($seed, 6);
$convert = array_map(function($n){
    global $seed;
    return $seed[$n];
},$rand);
$character = implode('',$convert);

$seed = str_split('1234567890');
$rand = array_rand($seed, 4);
$convert = array_map(function($n){
    global $seed;
    return $seed[$n];
},$rand);
$digit = implode('',$convert);

$rend_code = "BSTL" . "$character" . "$digit";

//End Random code Generator


$nep = "asset/userphoto/" . $rend_code . ".jpg";
if ($_FILES["user_photo"]["name"])

if (copy($_FILES["user_photo"]["tmp_name"], $nep)) 
$photo_path       = "asset/userphoto/" . $rend_code . ".jpg"; 

if(!empty($_FILES["user_photo"]["name"]))
{
  $user_photo_path = isset($photo_path) ? $photo_path :NULL;
}
else
{
  $user_photo_path = '0';
}
 {

        extract($_POST);
        $form_data = array(
          'FullName' => $full_name,
          'UserName' => $user_name,
          'Password' => MD5($password),
          'Email' => $email,
          'MobileNo' => $mobile_no,
            'zone' => '0',
          'Address' => $address,
          'PhotoPath' => $user_photo_path,
          'Status' => $status,
          'EntryBy' => $userid,       
          'EntryDate' => $date_time,
          'UpdateBy' => $userid
          );
        $created_id = $obj->insert_by_condition("_createuser", $form_data,"UserName='$user_name'");

        if($created_id)
          {


            if($created_id) {
                $form_data_user_access = array(
                    'UserId' => $created_id,
                    'UserType' => $user_type,
                    'MenuPermission' => serialize( !empty($menu_permission) ? $menu_permission : []),
                    'WorkPermission' => serialize( !empty($workPermission) ? $workPermission : []),
                    'EntryBy' => $userid,
                    'EntryDate' => $date_time,
                    'UpdateBy' => $userid
                );

                if ($obj->Insert_data("_useraccess", $form_data_user_access)) {

                    $obj->notificationStore('User '.$full_name.' Created Successfull', 'success');
                } else {
                    $obj->notificationStore('Sorry! User Create Failed, Please Try again');
                }

                $notification = 'Saved Successfull';
            }
              else
              {$notification = 'Saved Failed';}            
          }
          else {
            $obj->notificationStore('This User Name "'.$user_name.'" Already Exist, Try Another User Name.');
            $notification = 'Already Exist, Try Another User Name.';

        }
      }

   }


?>

        <div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:40px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
            <b>User Create Form</b>
        </div>

        <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
            <b><?php echo isset($notification)? $notification :NULL; ?></b>
        </div>

        <form role="form" enctype="multipart/form-data" method="post" id="user_create">
        <div class="row" style="padding:10px; font-size: 12px;">
          
            <div class="col-md-6 col-md-offset-1">

                  <div class="form-group">
                    <label for="exampleInputEmail1">Full Name</label>
                    <input type="text" name="full_name" class="form-control" id="exampleInputEmail1" placeholder="Full Name" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">User Name</label>
                    <input type="text" name="user_name" class="form-control" id="exampleInputEmail1" placeholder="User Name" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="**********" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email Address</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email Address" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Mobile No</label>
                    <input type="text" name="mobile_no" class="form-control" id="exampleInputEmail1" placeholder="Mobile No" required>
                  </div>
                
                  <div class="form-group">
                    <label for="exampleInputEmail1">Address</label>
                    <input type="text" name="address" class="form-control" id="exampleInputEmail1" placeholder="Address" required>
                  </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">User Status</label>
                    <select name="status" class="form-control" style="margin-bottom: 5px;" required>
                        <option value="1">Active</option>
                        <option value="0">InActive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">User Type</label>
                    <select name="user_type" class="form-control" style="margin-bottom: 5px;" required>
                        <option value="">- - - - -</option>
                        <option value="SA">Supper Admin</option>

                        <option value="EO">Entry Operator</option>

                    </select>
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Permission</label>

                    <div class="form-group" style="border: 1px solid #CCCCCC; padding: 5px; border-radius:4px;">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="WorkPermission[]" class="wclschekbox" id="inlineCheckbox1" value="add" onclick="work()"> Add
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="WorkPermission[]" class="wclschekbox" id="inlineCheckbox2" value="view" onclick="work()"> View
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="WorkPermission[]" class="wclschekbox" id="inlineCheckbox3" value="edit" onclick="work()"> Edit
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="WorkPermission[]" class="wclschekbox" id="inlineCheckbox3" value="delete" onclick="work()"> Delete
                        </label>
                        <input type="hidden" name="workaccess" id="workaccess"/>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <img width="140" height="140" src="asset/img/def_img.png" alt="..." class="img-thumbnail" id="pre_photo">
                      </div>
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
                                <label><input type="checkbox" name="menu_permission[]" value="usercreate">Add User</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="view_user">View All User</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="all_category">All Category</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="all_item">All Item</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="add_customer">Add Customer</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="view_customer">Customer Info</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="add_supplier">Add Supplier</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="view_supplier">Supplier Info</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="add_purchase">Add Purchase</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="view_all_purchase">View Purchase</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="add_sell">Add Sell</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="view_all_sell">View Sell</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="add_payment_person">Add Payment Person</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="account_statement">Account Statement</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="stock_report">Stock Report</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="income_report">Income Report</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="expense_report">Expense Report</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="purchase_report">Purchase Report</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="sale_report">Sale Report</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="expense">Expense</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="sale_report">Company Ledger</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="balance_sheet">Balance Sheet</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="add_loan_person">Add Loan Person</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="loan_list"> Loan List</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="take_loan"> Take Loan</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="add_security_money">Add Security Money</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="view_security_money"> View Security Money</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="add_employee">Add Employee</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="view_employee"> View Employee</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="add_bnk">Add Bank Info</label>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="checkbox">
                                <label><input type="checkbox" name="menu_permission[]" value="view_bank"> View Bank Info</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="margin: 0px;">


            </div>          
        </div>

        <div class="row" style="padding: 5px 0px 15px 0px; font-size: 12px; text-align: center;">
          <button type="submit" name="submit" class="btn btn-success">Submit</button> 
        </div>
</form>

<script>
    $(document).ready(function () {
        $('form#user_create').on('change', 'input#select_all_menu_permission', function (event) {

            var checked = $(this).prop('checked');

            $('form#user_create').find('input[name="menu_permission[]"]').prop('checked', checked);

        })
    });
</script>
