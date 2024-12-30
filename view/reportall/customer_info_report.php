<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">     

<script>
    $(function() {
      $( ".datepicker" ).datepicker();
    });
</script>
<div class="panel-heading">
    <h4>Select Customer Basic Information for Customize Customer Info Print</h4>
    <div class="options">   
        <!-- <a href="javascript:;"><i class="icon-cog"></i></a>
        <a href="javascript:;"><i class="icon-wrench"></i></a> -->
        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
    </div>
</div>

<div class="panel-body collapse in">
<form action="" method="POST" class="form-horizontal row-border"  id="validate-form" >   
    <div class="form-group">
        <div class="col-sm-2">
            <select name="passenger" class="form-control tooltips" data-trigger="hover" data-original-title="Select Any Section" id="section_id" >
                <option value="">- - Passenger Name- -</option>
                 <?php                    
                    foreach ($obj->view_all_by_cond("tbl_customer_info","status='0' ORDER BY id") as $value){
                    extract($value);                    
                ?>
                <option value="<?php echo isset($value['id'])? $value['id']:NULL; ?>"><?php echo isset($value['pax_f_name'])? $value['pax_f_name']:NULL; ?></option>
                <?php } ?>
                <option value="all" style="color: red;">All Passenger</option>
            </select>
        </div>
        <div class="col-sm-2">
            <select name="branch" class="form-control tooltips" data-trigger="hover" data-original-title="Select Any Shift" id="shift_id" >
                <option value="">- - Branch - -</option>                
                <?php                    
                    foreach ($obj->view_all_by_cond("tbl_branch","br_status='1' ORDER BY br_id") as $value){
                    extract($value);                    
                ?>
                <option value="<?php echo isset($value['br_id'])? $value['br_id']:NULL; ?>"><?php echo isset($value['br_name'])? $value['br_name']:NULL; ?></option>
                <?php } ?> 
                       
            </select>
        </div>

        <div class="col-sm-2">
            <select name="agent" class="form-control tooltips" data-trigger="hover" data-original-title="Select Any Department/Group" id="group_id">
                <option value="">- - Agent - -</option> 
                 <?php                    
                    foreach ($obj->view_all_by_cond("tbl_agent","ag_status='1' ORDER BY ag_id") as $value){
                    extract($value);                    
                ?>
                <option value="<?php echo isset($value['ag_id'])? $value['ag_id']:NULL; ?>"><?php echo isset($value['ag_name'])? $value['ag_name']:NULL; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-sm-2">
            <select name="served" class="form-control tooltips" data-trigger="hover" data-original-title="Select Any Student Type" id="std_type_id">
                <option value="">- -Served By - -</option> 
                <?php                    
                    foreach ($obj->view_all_by_cond("_createuser","Status='1' ORDER BY UserId") as $value){
                    extract($value);                    
                ?>
                <option value="<?php echo isset($value['UserId'])? $value['UserId']:NULL; ?>"><?php echo isset($value['FullName'])? $value['FullName']:NULL; ?></option>
                <?php } ?>
            </select>
        </div>       
        <div class="col-sm-2" >
            <div class="form-group" style="margin-left: -10px;">              
                <input style="color: black; padding-bottom: 10px;" id="new_flight_date" class="datepicker" type="text" placeholder="----Form Date--" name="dateform">
             </div>              
        </div>
        <div class="col-sm-2" >
             <div class="form-group" style="margin-left: 0px;">                
                <input style="color: black; padding-bottom: 10px;" id="old_flight_date" class="datepicker" type="text" placeholder="----To Date---" name="dateto">
             </div>              
        </div>
         
    </div>
<!-- ========================== -->
    <div class="panel-footer" style="margin-top: 0px;">
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-toolbar" style="text-align: center;">
                    <input name="search" type="submit" class="btn-primary btn"  value="Search">                    
                    <input type="reset" class="btn-default btn" value="Reset">
                </div>
            </div>
        </div>
    </div>
   
    </form>            
</div>


<?php
    if(isset($_POST['search'])) {
    extract($_POST);
?>

<!-- ========================= Table ===================== -->


<div class="panel panel-sky" style="margin-top: 20px;">
    <div class="panel-heading">
        <h4>Customer List</h4>
        <div class="options">   
            <!-- <a href="javascript:;"><i class="icon-cog"></i></a>
            <a href="javascript:;"><i class="icon-wrench"></i></a> -->
            <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
        </div>
    </div>

    <div class="panel-body collapse in">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables table-condensed table-responsive" id="example">
            <thead>               
                   <tr>
                        <th>#</th>
                        <th>Entry Date</th>
                        <th>CID</th>
                        <th>Customer Name</th> 
                        <th>Branch</th>
                        <th>SMS No</th>
                        <th>Refferal Type</th>                                            
                        <th>Done By</th>
                        <th>Work Status</th>                                                                                                           
                    </tr>
            </thead>
            <tbody>                               
                <?php
                if($passenger){
                    include 'customer/passenger.php';
                }
                elseif($branch){
                    include 'customer/branch.php';
                }                
                elseif($agent){
                    include 'customer/agent.php';
                }
                elseif($served){
                    include 'customer/served.php';
                }
                elseif($dateform && $dateto){
                    include 'customer/datesearch.php';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
}

?>
<!-- ======================================== -->
