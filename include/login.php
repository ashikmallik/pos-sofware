<?php 
session_start(); 

//========================================
include '../model/Controller.php';
include '../model/FormateHelper.php';

$formater = new FormateHelper();
$obj = new Controller();
//========================================

  
  if(isset($_POST['submit'])? $_POST['submit'] :NULL) {

    $in_user_name    = isset($_POST['in_user_name'])? $_POST['in_user_name'] :NULL;
	$in_password     = md5(isset($_POST['in_password'])? $_POST['in_password'] :NULL);

    $data = $obj->login_check("vw_user_info","UserName='$in_user_name' AND Password='$in_password' AND Status='1'");
    
    
    if($data){
    extract($data);

    $_SESSION['UserId']             = $data['UserId'];
    $_SESSION['FullName']           = $data['FullName'];
    $_SESSION['UserName']           = $data['UserName'];
    $_SESSION['PhotoPath']          = $data['PhotoPath'];
    $_SESSION['UserType']           = $data['UserType'];

    

      ?>
      <script>
        window.location = "../index.php";
      </script>

    <?php
    }
    else
      {$notification = '<span style="color: red;">Invalid User Name or Password</span>';}
  }
  
  
  
    $date = strtotime("June 9, 2025 6:00 PM");//provide disconnect date
    $remaining = $date - time();
    $days_remaining = floor($remaining / 86400);
    $days_rem_pos = abs($days_remaining);
    $hours_remaining = floor(($remaining % 86400) / 3600);
    $hours_remaining_pos = abs($hours_remaining);
 
    if($days_remaining <= 0  && $hours_remaining == 0 || $hours_remaining < 0)
    {
        $method = "";
        $type ="hidden";
    }
    else{
        $method ="POST";
        $type="password";
    }
    
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../../asset/img/logo.png">
    <title>Bangladesh Software Development.</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="../asset/css/stylelog.css" rel="stylesheet" type="text/css"/>
</head>
<body>


<div class="form-signin" style="text-align:center">


    <div class="shadow">
        <div class="shadowindiv">
            <div class="logindivtop">
                <?php if($days_remaining <=7 && $days_remaining >= 0) { ?>
                <span style="color: red;">Your Software is already Expired!!! This is Continue in Grace Time.Please pay beofre <?php  echo $days_rem_pos ?> days <?php echo $hours_remaining_pos ?> hours, otherwise your connection will be disconnect</span>
                <br>
                <?php } ?>
                <?php if($method =="")
                { ?>
                   <span style="color: red;">You lost your connection! Please pay for reactive again.</span> <br>
               <?php }
                ?>
                <b>Enter Your Login ID and Password</b>
            </div>
            <div>
                <b><?php echo isset($notification) ? $notification : NULL; ?></b>
            </div>

            <div class="logindiv">

                <form action="" class="form-horizontal"  method=<?php echo $method ?>>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">User Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="in_user_name" class="inputtext form-control" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-8">
                            <input type=<?php echo $type ?> name="in_password" class="inputtext form-control" >
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <input name="submit" type="submit" value="Login" class="loginbutton btn btn-primary"/>
                    </div>
                </form>
            </div>
            <div class=" logindivbottom">
                <b></b>
            </div>
        </div>
    </div>
    <div class="wellcomebottom">
        <b>Bangladesh Software Development.</b> <br>
        <b class="loginFooter"> &copy; &nbsp;2014-<?php echo date('Y') ?> Bangladesh Software Development.  All Right reserved. </b>
    </div>

</div>

</body>
</html>