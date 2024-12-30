<?php

date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
     
//$date        = date('Y-m-d');
$ip_add      = $_SERVER['REMOTE_ADDR'];
$userid ='1';

$token = isset($_GET['token'])? $_GET['token']:NULL;


if(isset($_POST['submit'])){
extract($_POST);
    
    $details = $obj->details_by_cond("_createuser","UserId='$token'");
    extract($details);

    $pass=$details['Password'];
    
    if(md5($password1)==$pass){
        if($password==$password2){

        $form_data = array('Password'=>md5($password),'UpdateBy' => $userid);

        $pass = $obj->Update_data("_createuser", $form_data,"where UserId='$token'");

             if($pass)
              {$notification = 'Password Change Successfull';}
              else
              {$notification = 'Password Change Failed';}       
        }
        else{$notification = 'Retype Password do not macht Please type same password';}
    }
     else{$notification = 'Old Password Do not match';}
}        
?>

<div class="col-md-12 bg-grey-700" style=" margin-top:20px; margin-bottom: 15px;">
    <h4><b>User Password Change Form</b></h4>
</div>
 <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
            <b><?php echo isset($notification)? $notification :NULL; ?></b>
        </div>
                
        <div class="row" style="padding:10px; font-size: 12px; text-align: center;">
            <form action="" enctype="multipart/form-data" method="post">
                <div class="col-md-offset-4 col-md-3">

                      <div class="form-group">
                        <label >Enter Your Old Password</label>
                        <input type="password" class="form-control" id="LanguageName" placeholder="********" name="password1" required>
                      </div>
                      <div class="form-group">
                        <label >Enter a New Password</label>
                        <input type="password" class="form-control" id="LanguageName" placeholder="********" name="password" required>
                      </div>
                      <div class="form-group">
                        <label >Retype New Password</label>
                        <input type="password" class="form-control" id="LanguageName" placeholder="********" name="password2" required>
                      </div>
                      <button type="submit" name="submit" class="btn btn-success">Change</button>        
                </div>
          </form>
        <div class="col-md-2"></div>
        </div>
                