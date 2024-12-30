<?php
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$intoken = isset($_GET['intoken']) ? $_GET['intoken'] : NULL;
$actoken = isset($_GET['actoken']) ? $_GET['actoken'] : NULL;


if (!empty($intoken)) {
    $form_data = array('Status' => '0', 'UpdateBy' => $userid);

    $obj->Update_data("_createuser", $form_data, "where UserId='$intoken'");
}

if (!empty($actoken)) {
    $form_data = array('Status' => '1', 'UpdateBy' => $userid);

    $obj->Update_data("_createuser", $form_data, "where UserId='$actoken'");
}
//---------------------------------------------

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

    {
        extract($_POST);

        $form_data = array(
            'FullName' => $full_name,
            'UserName' => $user_name,
            'Email' => $email,
            'MobileNo' => $mobile_no,
            'Address' => $address,
            'zone' => '0',
            'PhotoPath' => $user_photo_path,
            'UpdateBy' => $userid
        );
        $result = $obj->Update_data("_createuser", $form_data, "where UserId='$user_id'");

        if ($result) {
            extract($_POST);
            $form_data = array(
                'UserType' => $user_type,

                'WorkPermission' => $workaccess,
                'UpdateBy' => $userid
            );
            $update_user = $obj->Update_data("_useraccess", $form_data, "where UserId='$user_id'");

            if ($update_user) {
                $notification = 'Update Successfull';
            } else {
                $notification = 'Update Failed';
            }

        }

    }
}


?>


<div class="col-md-12"
     style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
    <b><?php echo isset($notification) ? $notification : NULL; ?></b>
</div>
<div class="col-md-12"
     style=" background:#606060 ; margin-top:20px; margin-bottom: 15px; min-height:40px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <b>View All User Information</b>
</div>

<div class="row" style="padding:10px; font-size: 12px;">

    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead>
                <tr class="bg-default">
                    <th style="width: 20px;">#</th>
                    <th style="text-align: center;">Full Name</th>
                    <th style="text-align: center;">User Name</th>
                    <th style="text-align: center;">Email Address</th>
                    <th style="text-align: center;">Mobile No</th>
                    <th style="text-align: center;">Zone</th>
                    <th style="text-align: center;">User Type</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Details</th>
                    <?php if ($ty == 'SA') { ?>
                        <th style="text-align: center;">Edit</th>
                        <th style="text-align: center;">Change Pass</th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = '0';
                foreach ($obj->view_all("vw_user_info") as $value) {
                    extract($value);
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo isset($value['FullName']) ? $value['FullName'] : NULL; ?></td>
                        <td><?php echo isset($value['UserName']) ? $value['UserName'] : NULL; ?></td>
                        <td><?php echo isset($value['Email']) ? $value['Email'] : NULL; ?></td>
                        <td><?php echo isset($value['MobileNo']) ? $value['MobileNo'] : NULL; ?></td>
                        <td><?php
                            if(isset($value['zone']) && !empty($value['zone'])){
                                $zoneData = $obj->details_by_cond('tbl_zone','zone_id = '.$value['zone'].'');
                                echo $zoneData['zone_name'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($value['UserType'] == 'SA') {
                                echo 'Supper Admin';
                            } elseif ($value['UserType'] == 'A') {
                                echo 'Admin';
                            } elseif ($value['UserType'] == 'EO') {
                                echo 'Entry Operator';
                            } elseif ($value['UserType'] == 'E') {
                                echo 'Editor';
                            } elseif ($value['UserType'] == 'SU') {
                                echo 'Supper User';
                            } elseif ($value['UserType'] == 'U') {
                                echo 'User';
                            }
                            ?>
                        </td>

                        <td style="text-align: center;">
                            <?php
                            if ((isset($value['Status']) ? $value['Status'] : NULL) == '1') {
                                ?>
                                <a href="?q=view_user&intoken=<?php echo isset($value['UserId']) ? $value['UserId'] : NULL; ?>"><span
                                        class="glyphicon glyphicon-ok"></span></a>

                                <?php
                            } else {
                                ?>
                                <a href="?q=view_user&actoken=<?php echo isset($value['UserId']) ? $value['UserId'] : NULL; ?>"><span
                                        class="glyphicon glyphicon-remove btn-danger"></span></a>
                            <?php } ?>
                        </td>
                        <td style="text-align: center;"><a
                                href="?q=user_details&token=<?php echo isset($value['UserId']) ? $value['UserId'] : NULL; ?>"><span
                                    class="glyphicon glyphicon-eye-open"></span></a></td>
                        <?php if ($ty == 'SA') { ?>
                            <td style="text-align: center;"><a
                                    href="?q=user_edit&token=<?php echo isset($value['UserId']) ? $value['UserId'] : NULL; ?>"><span
                                        class="glyphicon glyphicon-edit"></span></a></td>
                            <td style="text-align: center;"><a
                                    href="?q=user_ch_pass&token=<?php echo isset($value['UserId']) ? $value['UserId'] : NULL; ?>">Change</a>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>