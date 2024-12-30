<?php
$token = isset($_GET['token'])? $_GET['token'] :NULL;

$data = $obj->details_by_cond('vw_user_info',"UserId='$token'");
      extract($data);

?>
        
                        
              <div class="col-md-12" style=" background-image:url(asset/img/content_h1.png); margin-top:20px; margin-bottom: 15px; min-height:40px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
              <b>User Details Information</b>
              </div>
              <div class="row bio_data">
              <div class="col-md-3"></div>
              <div class="col-md-6"  style="margin: 0 auto;">  
                  
              <div style="border: 1px black solid; text-align: center;">
              <h1 style="margin-top: 5px; color: black;font-size: 32px;"><?php echo isset($data['FullName'])? $data['FullName']:NULL; ?> </h1>
              <img src="<?php echo isset($data['PhotoPath'])? $data['PhotoPath']:NULL; ?>" height="180" width="180" style="border: 1px gray solid; margin: 20px; padding:5px"></img>
              
              <p style="font-size:medium; font-family:'ursive'">   
                User Status           : 
              
                 <?php if($data['Status']=='0') {echo 'Inactive';} else{echo 'Active';} ?></br>
                  
                   User Type             : <?php echo isset($data['UserType'])? $data['UserType']:NULL; ?></br>
               Email               : <?php echo isset($data['Email'])? $data['Email']:NULL; ?></br>
                  
                  Permission Profile       : <?php echo isset($data['MenuPermission'])? $data['MenuPermission']:NULL; ?> </br>
              <hr>
                                      
            <details>
             <summary style="font-size:18px;font-family: serif">Click For More Details..</summary>

              <p style="font-size:medium; font-family:'ursive'">   

           <b>   Mobile NO </b>            : <?php echo isset($data['MobileNo'])? $data['MobileNo']:NULL; ?></br>
             <b>   Address    </b>           : <?php echo isset($data['Address'])? $data['Address']:NULL; ?></br>
             <b>    User Type    </b>          : <?php echo isset($data['UserType'])? $data['UserType']:NULL; ?></br>
             <b>    Work Permission  </b>     : <?php echo isset($data['WorkPermission'])? $data['WorkPermission']:NULL; ?></br>
             <b>    Entry By      </b>        : <?php echo isset($data['entry_user_name'])? $data['entry_user_name']:NULL; ?></br>
             <b>    Entry Date   </b>          : <?php echo date('d-m-Y h:m:s',strtotime($data['EntryDate'])); ?></br>
             <b>    Update By   </b>          : <?php echo isset($data['up_by_user_name'])? $data['up_by_user_name']:NULL; ?></br>
             <b>    Update Date   </b>          : <?php echo date('d-m-Y h:m:s',strtotime($data['LastUpdate'])); ?></br>
              </p> 
              </details>
              </br>
              </br>
              </div>
            </br>
          </div> 
          <div class="col-md-3"></div>
  </div>
             
       
