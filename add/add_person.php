<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$data = $obj->details_by_cond("tbl_person", "id!=0 ORDER BY id DESC");

//$id= intval($data['ag_id']);

if (($data['id'] + 1) < 10) {
    $STD = "PRSN0000"; // EMPL for person
} else if (($data['id'] + 1) < 100) {
    $STD = "PRSN000";
} else if (($data['id'] + 1) < 1000) {
    $STD = "PRSN00";
} else if (($data['id'] + 1) < 10000) {
    $STD = "PRSN0";
} else {
    $STD = "PRSN";
}
$STD .= $data['id'] + 1;

//===================Add Function===================

if (isset ($_POST['submit'])) {
    extract($_POST);
    $form_data_tbl_person = array(
        'person_name' => str_replace("'",'',$name),
        'person_mobile_no' => isset($mobile)?str_replace("'",'',$mobile): null,
        'person_address' => str_replace("'", "", $address),
        'person_id' => $STD,
        'person_status' => '1',
        'entry_by' => $userid,
        'entry_date' => date('Y-m-d'),
        'update_by' => $userid
    );
    
    if($obj->insert_by_condition("tbl_person", $form_data_tbl_person, " ")){
        $notification = '<div class="alert alert-success text-center"><strong>Person Added Successfully</strong></div>';
    }else{
        $notification = '<div class="alert alert-danger text-center "><strong>Sorry! operation failed</strong></div>';
    }
}

?>
<!--===================end Function===================-->
<script>

    function numbersOnly(e) // Numeric Validation
    {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) {
            if ((unicode < 2534 || unicode > 2543) && (unicode < 48 || unicode > 57)) {
                return false;
            }
        }
    }
</script>

<?php

if (isset($notification)) {
    echo $notification;
}
?>
<div class="row panel">
    <div class=" panel-body col-md-12 bg-teal-800 text-center" style="margin-bottom:5px;">
        <h4>Automated Person Id : <?php echo $STD; ?></h4 >
    </div>
</div>
<div class="row" style="font-size: 12px;">
    <form role="form" method="post">
                    
            <div class="col-md-6 col-md-offset-3">
                <div class="form-group">
                    <label>Person's Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Provide Person Name" required="required">
                </div>
                <div class="form-group">
                    <label>Person's Mobile No</label>
                    <input type="text" name="mobile" onkeypress="return numbersOnly(event)" class="form-control" placeholder="Provide Person Mobile No">
                </div>
                <div class="form-group">
                    <label>Person's Email</label>
                    <input type="email" name="email" class="form-control"  placeholder="Provide Email Address">
                </div>
                <div class="form-group">
                    <label>Person's  Address</label>
                    <textarea class="form-control" name="address" id="ResponsiveDetelis" rows="5"></textarea>
                </div>
                
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-success text-center" name="submit">Add New Person</button>
                </div>

            </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#datepicker').datepicker();
    })
</script>