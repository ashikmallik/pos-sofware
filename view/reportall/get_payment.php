<?php
date_default_timezone_set('Asia/Dhaka');
//$date_time =date('Y-m-d g:i:sA');
$date= date('Y-m-d');
$ip_add = $_SERVER['REMOTE_ADDR'];
$userid =isset($_SESSION['UserId']) ? $_SESSION['UserId']:NULL;
                 
$custotal=0;
$custotalwork=0;
$custotalpayment=0;
$custotalduepayment=0;
$agenttotal=0;
$agenttotalwork=0;
$agenttotalpayment=0;
$agenttotalduepayment=0;

?>
<div class="col-md-12" style=" background:#606060; margin-top:20px; margin-bottom: 15px; min-height:45px; padding:8px 0px 0px 15px; font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">  
    <b>View Advanced Report</b> 
</div>
<div class="row" style="padding:10px; font-size: 12px;">         
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="example">
                <thead> 
                    <tr>
                        <th>#</th>
                        <th>Customer Name</th>
                        <th>Previous Due</th>
                        <th>Today Work</th>
                        <th>today payment</th>
                        <th>Due Payment</th>                       
                    </tr>
                </thead>
                   <?php
                    $i='0';
                    foreach ($obj->view_all("tbl_customer_info") as $value){
                      $sumit=0;                       
                      $sumit1=0;                       
                      $i++; 
                       $idsumit=$value['id'];
                       foreach ($obj->view_all_by_cond("tbl_ticket_sale","t_cus_id='$idsumit'") as $customer_info_due){
                                extract($customer_info_due); 
                                
                                $sumit+=$customer_info_due['t_charge'];
                            }
                       foreach ($obj->view_all_by_cond("tbl_account","t_cus_id='$idsumit'") as $customer_info_due){
                                extract($customer_info_due);                        
                                $sumit1+=$customer_info_due['acc_amount'];
                            }
                       $sumit2=($sumit-$sumit1);  
                       if($sumit2<0){                                                             
                    ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo isset($value['pax_f_name'])?$value['pax_f_name']:NULL;?><?php echo isset($value['pax_l_name'])?$value['pax_l_name']:NULL;?></td>
                        <td style="text-align: right;">
                        <?php
                            $id=$value['id'];
                            $totalin1=0;   
                            
                           
                            foreach ($obj->view_all_by_cond("tbl_ticket_sale","t_cus_id='$id' and entry_date!='$date'") as $customer_info){
                            extract($customer_info);                        
                            $totalin1+=$customer_info['t_charge'];
                        }
                        $totalin2=0;                                                                       
                            foreach ($obj->view_all_by_cond("tbl_account","cus_id='$id' and entry_date!='$date'") as $customer_info1){
                            extract($customer_info1);                        
                            $totalin2+=$customer_info1['acc_amount'];
                        }
                        
                        $previous=$totalin1-$totalin2;
                        echo $previous;
                        
                        $custotal+=$previous;
                        
                        ?>
                            
                        </td> 
                         <td style="text-align: right;" >
                             <?php
                            $id2=$value['id'];                            
                            $totalin4=0;                                                                       
                                foreach ($obj->view_all_by_cond("tbl_ticket_sale","t_cus_id='$id2' and entry_date='$date'") as $customer_info){
                                    extract($customer_info);                        
                                    $totalin4+=$customer_info['t_charge'];
                                }
                            echo $totalin4;
                            
                            $custotalwork+=$totalin4;
                            ?>
                        </td>  
                        <td style="text-align: right;" >
                             <?php
                            $id1=$value['id'];                            
                            $totalin3=0;                                                                       
                                foreach ($obj->view_all_by_cond("tbl_account","cus_id='$id1' and entry_date='$date'") as $customer_info2){
                                extract($customer_info2);                        
                                $totalin3+=$customer_info2['acc_amount'];
                            }
                        echo $totalin3;
                        $custotalpayment+=$totalin3;
                        ?>
                        </td>
                        <td style="text-align: right;" >
                            <?php  
                            $dueamount=($previous+$totalin4)-$totalin3;
                            echo  $dueamount;
                            $custotalduepayment+=$dueamount;
                            ?>
                        </td>
                    </tr>
                    <?php
                    
                    }
                    }
                    
                    ?>
                   
                    
                    
                    <!-------------------------------------for agent------------------------------------>
                   
                    <?php                
                    
                    foreach ($obj->view_all("tbl_agent") as $value){
                      $sumit=0;                       
                      $sumit1=0;                       
                      $i++; 
                       $idsumit=$value['ag_id'];
                       foreach ($obj->view_all_by_cond("tbl_ticket_sale","t_agent_id='$idsumit'") as $customer_info_due){
                                extract($customer_info_due); 
                                
                                $sumit+=$customer_info_due['t_charge'];
                            }
                       foreach ($obj->view_all_by_cond("tbl_account","agent_id='$idsumit'") as $customer_info1){
                                extract($customer_info1);                        
                                $sumit1+=$customer_info1['acc_amount'];
                            }
                       $sumit2=($sumit-$sumit1);  
                       if($sumit2<0){
                    ?>
                    <tr >                                                 
                        <td><?php echo $i;?></td>
                        <td><?php echo isset($value['ag_name'])?$value['ag_name']:NULL;?></td>
                        <td style="text-align: right;" >
                            <?php
                                $id3=$value['ag_id'];                           
                                $totalin1=0;                                                                       
                                foreach ($obj->view_all_by_cond("tbl_ticket_sale","t_agent_id='$id3' and entry_date!='$date'") as $customer_info){
                                extract($customer_info);                        
                                $totalin1+=$customer_info['t_charge'];
                            }
                            $totalin2=0;                                                                       
                                foreach ($obj->view_all_by_cond("tbl_account","agent_id='$id3' and entry_date!='$date'") as $customer_info1){
                                extract($customer_info1);                        
                                $totalin2+=$customer_info1['acc_amount'];
                            }

                            $previous1=$totalin1-$totalin2;
                            echo $previous1;

                            $agenttotal+=$previous1;                                                      
                            ?>                            
                        </td> 
                         <td style="text-align: right;" >
                             <?php
                            $id2=$value['ag_id'];                            
                            $totalin4=0;                                                                       
                                foreach ($obj->view_all_by_cond("tbl_ticket_sale","t_agent_id='$id2' and entry_date='$date'") as $customer_info){
                                    extract($customer_info);                        
                                    $totalin4+=$customer_info['t_charge'];
                                }
                            echo $totalin4;
                            $agenttotalwork+=$totalin4;
                            ?>
                        </td>  
                        <td style="text-align: right;" >
                             <?php
                            $id1=$value['ag_id'];                            
                            $totalin3=0;                                                                       
                                foreach ($obj->view_all_by_cond("tbl_account","agent_id='$id1' and entry_date='$date'") as $customer_info2){
                                extract($customer_info2);                        
                                $totalin3+=$customer_info2['acc_amount'];
                                    }
                                echo $totalin3;
                                $agenttotalpayment+=$totalin3;
                        ?>
                        </td>
                        <td style="text-align: right;" class="balance" >
                            <?php  
                            $dueamount=($previous1+$totalin4)-$totalin3;
                                                                                            
                            echo  $dueamount;
                            
                            $agenttotalduepayment+=$dueamount;                                                                                       
                            ?>
                        </td>
                    </tr>
                    <?php
                    }
                    }
                                
                    ?>
                    <tr>
                        <td><?php $i++; echo $i; ?></td>
                        <td style="text-align: right; color: green;">Total=</td>
                        <td style="text-align: right; color: green; " ><?php echo $agenttotal+$custotal; ?></td>
                        <td style="text-align: right; color: green; " ><?php echo $agenttotalwork+$custotalwork; ?></td>
                        <td style="text-align: right; color: green; " ><?php echo $custotalpayment+$agenttotalpayment; ?></td>
                        <td  style="text-align: right; color: green; " >
                            <?php
                            $due=$agenttotalduepayment+$custotalduepayment;
                            
                            $_SESSION["alldue"] = "$due";
                            
                            echo $due;
                            
                            
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
    </div>
</div>

