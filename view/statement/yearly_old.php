<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
$date =date('Y-m-d');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;
$total=0;
$total1=0;
$total2=0;
$total3=0;
$total4=0;
$total5=0;


?>


 <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
        <b><?php echo isset($notification)? $notification :NULL; ?></b>
 </div>
<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
    <div class="col-md-6">
         <b>Account Statement </b>
    </div>               
</div>
 <div class="col-md-12" style=" margin-top:5px; margin-bottom: 5px; font-size:14px;  color:red; font-weight:bold; text-align: center;">
        <b><?php echo isset($notification)? $notification :NULL; ?></b>
 </div>
<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">   
        <form  action="" method="POST">                      
            <div class="col-md-5">
                 <div class="form-group">
                    <label class="col-sm-6" >Year</label>
                    <select class="form-control" required="required" name="dateto" id="status">
                        <option  value="">--select--</option>                                  
                        <option  value="2015">2015</option>
                        <option  value="2016">2016</option>
                        <option  value="2017">2017</option>
                        <option  value="2018">2018</option>
                        <option  value="2019">2019</option>
                        <option  value="2020">2020</option>
                        <option  value="2021">2021</option>
                        <option  value="2022">2022</option>
                        <option  value="2023">2023</option>
                        <option  value="2024">2024</option>
                        <option  value="2025">2025</option>                                
                    </select>
                 </div>                 
            </div>            
            <div class="col-md-2" style="margin-top: 30px;">
               <button type="submit"  name="search" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</button> 
            </div>
        </form>                  
</div>


 <?php 
    if(isset($_POST['search'])){
      extract($_POST);
?>

<div class="row" style="padding:10px; font-size: 12px;">         
    <div class="col-md-6"> 
        <p>Income Statement</p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>#</th>
                        <th>Month</th>
                        <th>Amount</th>                                                                                                                                        
                    </tr>
                </thead> 
                  <tr >                          
                      <td colspan="3" > Other Payment</td>                                                           
                  </tr>
                        <?php
                        $i=0;
                      
                       
                        for($j=1; $j<=12; $j++){
                        $monthtotal=0;    
                        $i++;                                            
                        foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$j' and YEAR(entry_date)='$dateto' and acc_type='2' and cus_id='0' and agent_id='0' order by acc_id") as $month_info){
                        extract($month_info);                    
                        $monthtotal+=$month_info['acc_amount'];
                          }                                              
                        ?>
                        <tr>
                            <td><?php echo $i;  ?></td>
                            <td style="text-align: center;">
                                <?php 
                                if($j==1){
                                    echo 'January';
                                }
                                else if($j==2){
                                    echo 'February';
                                }
                                else if($j==3){
                                    echo 'March';
                                }
                                else if($j==4){
                                    echo 'April';
                                }
                                else if($j==5){
                                    echo 'May';
                                }
                                else if($j==6){
                                    echo 'June';
                                }
                                else if($j==7){
                                    echo 'July';
                                }
                                else if($j==8){
                                    echo 'August';
                                }
                                else if($j==9){
                                    echo 'September';
                                }
                                else if($j==10){
                                    echo 'October';
                                }
                                else if($j==11){
                                    echo 'November';
                                }
                                else if($j==12){
                                    echo 'December';
                                }
                                ?>
                            </td>
                            <td style="text-align: right;">
                                <?php echo $monthtotal ?>
                            </td>                                                            
                        </tr>
                        <?php 
                             $total+=$monthtotal;
                         }
                        
                        ?>
                      <tr >                          
                          <td colspan="2" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $total; ?></td>                                   
                      </tr>    
                        
                      <tr>
                          <?php
                            $totalw=$formater->convert_number_to_words($total);
                          ?>
                          
                          <td colspan="3" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $totalw; ?></span></span></td>                          
                      </tr> 
                      
                      <!------------------------------------------------------------------------>
                      
                      <!------------------------------------------------------------------------>    
                      <tr >                          
                      <td colspan="3" > Agent Payment</td>                                                           
                  </tr>
                        <?php
                        $i=0;
                                             
                        for($j=1; $j<=12; $j++){
                        $monthtotal=0;    
                        $i++;                                            
                        foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$j' and YEAR(entry_date)='$dateto' and acc_type='3' and cus_id='0' and agent_id!='0' order by acc_id") as $month_info){
                        extract($month_info);                    
                        $monthtotal+=$month_info['acc_amount'];
                          }                                              
                        ?>
                        <tr>
                            <td><?php echo $i;  ?></td>
                            <td style="text-align: center;">
                                <?php 
                                if($j==1){
                                    echo 'January';
                                }
                                else if($j==2){
                                    echo 'February';
                                }
                                else if($j==3){
                                    echo 'March';
                                }
                                else if($j==4){
                                    echo 'April';
                                }
                                else if($j==5){
                                    echo 'May';
                                }
                                else if($j==6){
                                    echo 'June';
                                }
                                else if($j==7){
                                    echo 'July';
                                }
                                else if($j==8){
                                    echo 'August';
                                }
                                else if($j==9){
                                    echo 'September';
                                }
                                else if($j==10){
                                    echo 'October';
                                }
                                else if($j==11){
                                    echo 'November';
                                }
                                else if($j==12){
                                    echo 'December';
                                }
                                ?>
                            </td>
                            <td style="text-align: right;">
                                <?php echo $monthtotal ?>
                            </td>                                                            
                        </tr>
                        <?php 
                             $total2+=$monthtotal;
                         }
                        
                        ?>
                      <tr >                          
                          <td colspan="2" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $total2; ?></td>                                   
                      </tr>    
                        
                      <tr>
                          <?php
                            $total2w=$formater->convert_number_to_words($total2);
                          ?>
                          
                          <td colspan="3" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $total2w; ?></span></span></td>                          
                      </tr> 
                      
                      
                      <!-------------------------------------------------------------------------------->
                    <tr >                          
                      <td colspan="3" >Connection Charge Payment</td>                                                           
                    </tr>
                        <?php
                        $i=0;
                      
                       
                        for($j=1; $j<=12; $j++){
                        $monthtotal=0;    
                        $i++;                                            
                        foreach ($obj->view_all_by_cond("tbl_agent","MONTH(entry_date)='$j' and YEAR(entry_date)='$dateto' order by ag_id") as $month_info){
                        extract($month_info);                    
                        $monthtotal+=$month_info['connect_charge'];
                          }                                              
                        ?>
                        <tr>
                            <td><?php echo $i;  ?></td>
                            <td style="text-align: center;">
                                <?php 
                                if($j==1){
                                    echo 'January';
                                }
                                else if($j==2){
                                    echo 'February';
                                }
                                else if($j==3){
                                    echo 'March';
                                }
                                else if($j==4){
                                    echo 'April';
                                }
                                else if($j==5){
                                    echo 'May';
                                }
                                else if($j==6){
                                    echo 'June';
                                }
                                else if($j==7){
                                    echo 'July';
                                }
                                else if($j==8){
                                    echo 'August';
                                }
                                else if($j==9){
                                    echo 'September';
                                }
                                else if($j==10){
                                    echo 'October';
                                }
                                else if($j==11){
                                    echo 'November';
                                }
                                else if($j==12){
                                    echo 'December';
                                }
                                ?>
                            </td>
                            <td style="text-align: right;">
                                <?php echo $monthtotal ?>
                            </td>                                                            
                        </tr>
                        <?php 
                             $total1+=$monthtotal;
                         }
                        
                        ?>
                      <tr >                          
                          <td colspan="2" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $total1; ?></td>                                   
                      </tr>    
                        
                      <tr>
                          <?php
                            $total1w=$formater->convert_number_to_words($total1);
                          ?>
                          
                          <td colspan="3" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $total1w; ?></span></span></td>                          
                      </tr> 
                      <!-------------------------------------------------------------------------------->
                      <tr >   
                          <?php
                        
                          $grandtotal=$total+$total1+$total2;
                          ?>
                          
                          <td colspan="2" style="text-align: right;">Grand Total:</td>
                          <td style="text-align: right;"><?PHP echo $grandtotal; ?></td>                                   
                      </tr>    
                      <tr>
                          <?php
                            $word=$formater->convert_number_to_words($grandtotal);
                          ?>
                          
                          <td colspan="3" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
                      </tr>                 
                </table>
            </div>
    </div>
    <div class="col-md-6">
         <p>Expense Statement</p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>#</th>
                        <th>Month</th>                      
                        <th>Amount</th>                                                                                                                                       
                    </tr>
                </thead> 
                    <tr >                          
                        <td colspan="3" >Other Expense </td>                                                           
                      </tr>
                        <?php
                        $i=0;
                      
                       
                        for($j=1; $j<=12; $j++){
                        $monthtotal=0;    
                        $i++;                                            
                        foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$j' and YEAR(entry_date)='$dateto' and acc_type='1' and cus_id='0' and agent_id='0' order by acc_id") as $month_info){
                        extract($month_info);                    
                        $monthtotal+=$month_info['acc_amount'];
                          }                                              
                        ?>
                        <tr>
                            <td><?php echo $i;  ?></td>
                            <td style="text-align: center;">
                                <?php 
                                if($j==1){
                                    echo 'January';
                                }
                                else if($j==2){
                                    echo 'February';
                                }
                                else if($j==3){
                                    echo 'March';
                                }
                                else if($j==4){
                                    echo 'April';
                                }
                                else if($j==5){
                                    echo 'May';
                                }
                                else if($j==6){
                                    echo 'June';
                                }
                                else if($j==7){
                                    echo 'July';
                                }
                                else if($j==8){
                                    echo 'August';
                                }
                                else if($j==9){
                                    echo 'September';
                                }
                                else if($j==10){
                                    echo 'October';
                                }
                                else if($j==11){
                                    echo 'November';
                                }
                                else if($j==12){
                                    echo 'December';
                                }
                                ?>
                            </td>
                            <td style="text-align: right;">
                                <?php echo $monthtotal ?>
                            </td>                                                            
                        </tr>
                        <?php 
                             $total3+=$monthtotal;
                         }
                        
                        ?>
                      <tr >                          
                          <td colspan="2" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $total3; ?></td>                                   
                      </tr>    
                        
                      <tr>
                          <?php
                            $total3w=$formater->convert_number_to_words($total3);
                          ?>
                          
                          <td colspan="3" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $total3w; ?></span></span></td>                          
                      </tr>     
                      <!--------------------------------------------------------------------------->
                      
            <!---------------------------------------------------------------------------> 
      
            </table>
            </div>
    </div>
       
</div>
<?php 
    }
?>
