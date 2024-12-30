<?php
date_default_timezone_set('Asia/Dhaka');
$date_time =date('Y-m-d g:i:sA');
$date =date('Y-m-d');
//$date        = date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;

$alldue1 =isset($_SESSION['alldue']) ? $_SESSION['alldue']:NULL;
                                


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
                    <label class="col-sm-6" >Month</label>
                    <select class="form-control" required="required" name="dateform" id="status">
                        <option value="">--select--</option>                                  
                        <option  value="1">January</option>
                        <option  value="2">February</option>
                        <option  value="3">March</option>
                        <option  value="4">April</option>
                        <option  value="5">May</option>
                        <option  value="6">June</option>
                        <option  value="7">July</option>
                        <option  value="8">August</option>
                        <option  value="9">September</option>
                        <option  value="10">October</option>
                        <option  value="11">November</option>
                        <option  value="12">December</option>                                     
                    </select>
                 </div> 
                
            </div>            
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
<div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<div class="container">
  <h2>Income Statement</h2>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#ag_payment">Agent Payment</a></li>
    <li><a data-toggle="tab" href="#others_payment">Others Payment</a></li>
    <li><a data-toggle="tab" href="#con_payment">Connection Charge</a></li>
    <li><a data-toggle="tab" href="#ex_st">Expense Statement</a></li>
    <li><a data-toggle="tab" href="#summary">Summary</a></li>
  </ul>
 <?php 
    if(isset($_POST['search'])){
      extract($_POST);
?>
 <div class="tab-content">
    <div id="ag_payment" class="tab-pane fade in active">
      <div class="row">  
		<div class="col-md-11">
            <table class="table table-responsive table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>#</th>
                        <th>Date</th> 
                         <th>Received By</th>
                        <th>Amount</th>                                             
                                                                                           
                    </tr>
                </thead> 
				<tbody>
					<?php
					$i=0;
					$totalin1=0;
					
				   
					foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$dateform' and YEAR(entry_date)='$dateto' and acc_type='3' and agent_id!='0' order by acc_id") as $customer_info){
					extract($customer_info);
					$i++;
					$totalin1+=$customer_info['acc_amount'];
					
					
					?>
					<tr>
						   <td><?php echo $i;  ?></td>
						   <td><?php echo isset($customer_info['entry_date'])?$customer_info['entry_date']:NULL; ?></td>
						   <td style="text-align: right;"><?php echo isset($customer_info['FullName'])?$customer_info['FullName']:NULL; ?>(<?php echo isset($customer_info['UserName'])?$customer_info['UserName']:NULL; ?>)</td>
						   <td style="text-align: right;"><?php echo isset($customer_info['acc_amount'])?$customer_info['acc_amount']:NULL; ?></td>
														
					</tr>
					<?php 
					 }
					?>
				  <tr >                          
					  <td colspan="3" style="text-align: right;">Total:</td>
					  <td style="text-align: right;"><?PHP echo $totalin1; ?></td>                                   
				  </tr>    
					
				  <tr>
					  <?php
						$word=$formater->convert_number_to_words($totalin1);
					  ?>
					  
					  <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
				  </tr> 
				</tbody>
			</table>
		</div>
	   </div>
	</div>
    <div id="others_payment" class="tab-pane fade">
            <div class="row">  
		<div class="col-md-11">
            <table class="table table-responsive table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>#</th>
                        <th>Date</th> 
                         <th>Received By</th>
                        <th>Amount</th>                                             
                                                                                           
                    </tr>
                </thead> 
				<tbody>
					<?php
					$i=0;
					$totalin2=0;
					
				   
					foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$dateform' and YEAR(entry_date)='$dateto' and acc_type='2' and cus_id='0' and agent_id='0' order by acc_id") as $customer_info){
					extract($customer_info);
					$i++;
					$totalin2+=$customer_info['acc_amount'];
					
					
					?>
					<tr>
						   <td><?php echo $i;  ?></td>
						   <td><?php echo isset($customer_info['entry_date'])?$customer_info['entry_date']:NULL; ?></td>
						   <td style="text-align: right;"><?php echo isset($customer_info['FullName'])?$customer_info['FullName']:NULL; ?>(<?php echo isset($customer_info['UserName'])?$customer_info['UserName']:NULL; ?>)</td>
						   <td style="text-align: right;"><?php echo isset($customer_info['acc_amount'])?$customer_info['acc_amount']:NULL; ?></td>
														
					</tr>
					<?php 
					 }
					?>
				  <tr >                          
					  <td colspan="3" style="text-align: right;">Total:</td>
					  <td style="text-align: right;"><?PHP echo $totalin2; ?></td>                                   
				  </tr>    
					
				  <tr>
					  <?php
						$word=$formater->convert_number_to_words($totalin2);
					  ?>
					  
					  <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
				  </tr> 
				</tbody>
			</table>
		</div>
	   </div>
	</div>
    <div id="con_payment" class="tab-pane fade">
	   <div class="row">  
		<div class="col-md-11">
            <table class="table table-responsive table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>#</th>
                        <th>Date</th> 
                         <th>Received By</th>
                        <th>Amount</th>                                             
                                                                                           
                    </tr>
                </thead> 
				<tbody>
					<?php
					$i=0;
					$totalin3=0;
					
				   
					foreach($obj->view_all_by_cond("tbl_agent","MONTH(entry_date)='$dateform' and YEAR(entry_date)='$dateto' order by ag_id") as $customer_info){
					extract($customer_info);
					$i++;
					$totalin3+=$customer_info['connect_charge'];
					
					
					?>
					<tr>
						   <td><?php echo $i;  ?></td>
						   <td><?php echo isset($customer_info['entry_date'])?$customer_info['entry_date']:NULL; ?></td>
						   <td style="text-align: right;"><?php echo isset($customer_info['ag_name'])?$customer_info['ag_name']:NULL; ?>(<?php echo isset($customer_info['UserName'])?$customer_info['UserName']:NULL; ?>)</td>
						   <td style="text-align: right;"><?php echo isset($customer_info['connect_charge'])?$customer_info['connect_charge']:NULL; ?></td>
														
					</tr>
					<?php 
					 }
					?>
				  <tr >                          
					  <td colspan="3" style="text-align: right;">Total:</td>
					  <td style="text-align: right;"><?PHP echo $totalin3; ?></td>                                   
				  </tr>    
					
				  <tr>
					  <?php
						$word=$formater->convert_number_to_words($totalin3);
					  ?>
					  
					  <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
				  </tr> 
				</tbody>
			</table>
		</div>
	   </div>
	</div>
    <div id="ex_st" class="tab-pane fade">
	   <div class="row">  
		<div class="col-md-11">
            <table class="table table-responsive table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>#</th>
                        <th>Date</th> 
                         <th>Received By</th>
                        <th>Amount</th>                                             
                                                                                           
                    </tr>
                </thead> 
				<tbody>
					<?php
					$i=0;
					$totalin4=0;

					foreach($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$dateform' and YEAR(entry_date)='$dateto' and acc_type='1' order by acc_id") as $customer_info){
					extract($customer_info);
					$i++;
					$totalin4+=$customer_info['acc_amount'];
					
					
					?>
					<tr>
						   <td><?php echo $i;  ?></td>
						   <td><?php echo isset($customer_info['entry_date'])?$customer_info['entry_date']:NULL; ?></td>
						   <td style="text-align: right;"><?php echo isset($customer_info['ag_name'])?$customer_info['ag_name']:NULL; ?>(<?php echo isset($customer_info['UserName'])?$customer_info['UserName']:NULL; ?>)</td>
						   <td style="text-align: right;"><?php echo isset($customer_info['acc_amount'])?$customer_info['acc_amount']:NULL; ?></td>
														
					</tr>
					<?php 
					 }
					?>
				  <tr >                          
					  <td colspan="3" style="text-align: right;">Total:</td>
					  <td style="text-align: right;"><?PHP echo $totalin4; ?></td>                                   
				  </tr>    
					
				  <tr>
					  <?php
						$word=$formater->convert_number_to_words($totalin4);
					  ?>
					  
					  <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
				  </tr> 
				</tbody>
			</table>
		</div>
	   </div>
	</div>
	<div id="summary" class="tab-pane fade in active">
      <div class="row">  
			<div class="col-md-11">
				<table class="table table-responsive table-bordered table-hover table-striped" id="example">
					<thead> 
						<tr>
							<th>#</th>
							<th>Title</th> 
							 <th>Total Amount</th>                                            																   
						</tr>
					</thead> 
						<tr>
							<td>1</td>
							<td>Agent Payment</td>
							<td><?=$totalin1?></td>
						</tr>
						<tr>
							<td>2</td>
							<td>Other Payment</td>
							<td><?=$totalin2?></td>
						</tr>
						<tr>
							<td>3</td>
							<td>Connection Charge</td>
							<td><?=$totalin3?></td>
						</tr>
						<tr>
							<td>4</td>
							<td>Expense Statement</td>
							<td><?=$totalin4?></td>
						</tr>
						<tr>
						
							<td>Profit:</td>
							<td>
							<?php
							$grandtotal=$totalin1+$totalin2+$totalin3;
							$grandtotalfinal=$grandtotal-$totalin4;
							echo $grandtotalfinal;
							?></td>
						</tr>
						<tr>
							<td>Total Ammount In Word::</td>
							<td><?php echo $formater->convert_number_to_words($grandtotalfinal)?></td>
						</tr>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>



<div class="row" style="padding:10px; font-size: 12px;">         
    <div class="col-md-6"> 
        <p>Income Statement</p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>#</th>
                        <th>Date</th> 
                         <th>Received By</th>
                        <th>Amount</th>                                             
                                                                                           
                    </tr>
                </thead> 
                  <tr >                          
                      <td colspan="4" > Other Payment</td>                                                           
                  </tr>
                        <?php
                        $i=0;
                        $totalin1=0;
                        
                       
                        foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$dateform' and YEAR(entry_date)='$dateto' and acc_type='2' and cus_id='0' and agent_id='0' order by acc_id") as $customer_info){
                        extract($customer_info);
                        $i++;
                        $totalin1+=$customer_info['acc_amount'];
                        
                        
                        ?>
                        <tr>
                               <td><?php echo $i;  ?></td>
                               <td><?php echo isset($customer_info['entry_date'])?$customer_info['entry_date']:NULL; ?></td>
                               <td style="text-align: right;"><?php echo isset($customer_info['FullName'])?$customer_info['FullName']:NULL; ?>(<?php echo isset($customer_info['UserName'])?$customer_info['UserName']:NULL; ?>)</td>
                               <td style="text-align: right;"><?php echo isset($customer_info['acc_amount'])?$customer_info['acc_amount']:NULL; ?></td>
                                                            
                        </tr>
                        <?php 
                         }
                        ?>
                      <tr >                          
                          <td colspan="3" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $totalin1; ?></td>                                   
                      </tr>    
                        
                      <tr>
                          <?php
                            $word=$formater->convert_number_to_words($totalin1);
                          ?>
                          
                          <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
                      </tr> 
                      <!-------------------------------For Customer---------------------------------->
                       
                      <!----------------------------for agent----------------------->
                      <tr >                          
                          <td colspan="4" > Agent Payment</td>                                                           
                      </tr> 
                      <?php
                        $i=0;
                        $agenttotal=0;
                        
                      
                        foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$dateform' and YEAR(entry_date)='$dateto' and acc_type='3' and agent_id!='0' order by acc_id") as $agent_info){
                        extract($agent_info);
                        $i++;
                        $agenttotal+=$agent_info['acc_amount'];
                        
                        
                        ?>
                        <tr>
                               <td><?php echo $i;  ?></td>
                               <td><?php echo isset($agent_info['entry_date'])?$agent_info['entry_date']:NULL; ?></td>
                               <td style="text-align: right;"><?php echo isset($agent_info['FullName'])?$agent_info['FullName']:NULL; ?>(<?php echo isset($agent_info['UserName'])?$customer_info['UserName']:NULL; ?>)</td>
                               <td style="text-align: right;"><?php echo isset($agent_info['acc_amount'])?$agent_info['acc_amount']:NULL; ?></td>
                                                            
                        </tr>
                        <?php 
                         }
                        ?>
                      <tr >                          
                          <td colspan="3" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $agenttotal; ?></td>                                   
                      </tr>    
                      <tr>
                          <?php
                            $agenttotal1=$formater->convert_number_to_words($agenttotal);
                          ?>
                          
                          <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $agenttotal1; ?></span></span></td>                          
                      </tr>
                      
                      <!---------------------------Connection Charge--------------------------->
                       <tr >                          
                          <td colspan="4" >Connection Charge</td>                                                           
                      </tr> 
                      <?php
                        $i=0;
                        $totalin3=0;
                        
                       
                        
                        foreach ($obj->view_all_by_cond("tbl_agent","MONTH(entry_date)='$dateform' and YEAR(entry_date)='$dateto' order by ag_id") as $customer_info){
                        extract($customer_info);
                        $i++;
                        $totalin3+=$customer_info['connect_charge'];
                                                
                        ?>
                        <tr>
                               <td><?php echo $i;  ?></td>
                               <td><?php echo isset($customer_info['entry_date'])?$customer_info['entry_date']:NULL; ?></td>
                               <td style="text-align: right;"><?php echo isset($customer_info['ag_name'])?$customer_info['ag_name']:NULL; ?></td>
                               <td style="text-align: right;"><?php echo isset($customer_info['connect_charge'])?$customer_info['connect_charge']:NULL; ?></td>
                                                            
                        </tr>
                        <?php 
                         }
                        ?>
                      <tr >                          
                          <td colspan="3" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $totalin3; ?></td>                                   
                      </tr>    
                      <tr>
                          <?php
                            $word2=$formater->convert_number_to_words($totalin3);
                          ?>
                          
                          <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word2; ?></span></span></td>                          
                      </tr>  
                      <!-----------------------end------------------------>
                      
                      
                      
                      
                      <!------------------------------For Grand Total-------------------------------->
                      <tr >   
                          <?php
                          
                          $grandtotal=$totalin1+$totalin3+$agenttotal;
                          ?>
                          
                          <td colspan="3" style="text-align: right;">Grand Total:</td>
                          <td style="text-align: right;"><?PHP echo $grandtotal; ?></td>                                   
                      </tr>    
                      <tr>
                          <?php
                            $word=$formater->convert_number_to_words($grandtotal);
                          ?>
                          
                          <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Ammount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
                      </tr> 
                      <!------------------------------------------Due Payment-------------------------------------->
                     
                    <!------------------------------------------------------------>  
                                                       
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
                        <th>Date</th> 
                        <th>Received By</th>  
                        <th>Amount</th>                                                                                                                                       
                    </tr>
                </thead>                   
                        <?php
                        $i=0;
                        $totalin4=0;
                        
     
                        foreach ($obj->view_all_by_cond("vw_account","MONTH(entry_date)='$dateform' and YEAR(entry_date)='$dateto' and acc_type='1' order by acc_id") as $customer_info){
                        extract($customer_info);
                        $i++;
                        $totalin4+=$customer_info['acc_amount'];
                        
                        
                        ?>
                        <tr>
                               <td><?php echo $i;  ?></td>
                               <td><?php echo isset($customer_info['entry_date'])?$customer_info['entry_date']:NULL; ?></td>
                               <td style="text-align: right;"><?php echo isset($customer_info['FullName'])?$customer_info['FullName']:NULL; ?>(<?php echo isset($customer_info['UserName'])?$customer_info['UserName']:NULL; ?>)</td>                                                     
                               <td style="text-align: right;"><?php echo isset($customer_info['acc_amount'])?$customer_info['acc_amount']:NULL; ?></td>
                          </tr>
                        <?php 
                         }
                        ?>
                      <tr >                          
                          <td colspan="3" style="text-align: right;">Total:</td>
                          <td style="text-align: right;"><?PHP echo $totalin4; ?></td>                                   
                      </tr>    
                      <tr>
                          <?php
                            $word=$formater->convert_number_to_words($totalin4);
                          ?>
                          
                          <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Amount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
                      </tr>                                         
            </table>
            
        </div>
    </div>
    <table class="table table-bordered table-hover table-striped" >
        <tr>
            <td style="text-align: right;" >Profit=</td>
            <td>
               <?php 
                $grandtotalfinal=$grandtotal-$totalin4;
                echo $grandtotalfinal;
                
               ?> 
            </td>
        </tr>
       <tr>
            <?php
              $word=$formater->convert_number_to_words($grandtotalfinal);
            ?>

            <td colspan="5" style="text-align: center;" ><span style="color: green;">Total Amount In Word::&nbsp;&nbsp;&nbsp;<span style="color: red;"><?PHP echo $word; ?></span></span></td>                          
        </tr>
    </table>
</div>
<?php 
    }
?>
