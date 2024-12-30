<?php
                    $i='0';
                    foreach ($obj->view_all_by_cond("vw_customer_info","service='$service'") as $value){
                        $i++;                                                              
                    ?>
                    <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo isset($value['entry_date'])?$value['entry_date']:NULL;?></td>
                    <td><?php echo isset($value['cus_id'])?$value['cus_id']:NULL;?></td>
                    <td><?php echo isset($value['pax_f_name'])?$value['pax_f_name']:NULL;?><?php echo isset($value['pax_l_name'])?$value['pax_l_name']:NULL;?></td>
                    <td><?php echo isset($value['sms_no'])?$value['sms_no']:NULL;?></td>
                    <td><?php echo isset($value['air_name'])?$value['air_name']:NULL;?></td>
                    <td><?php echo isset($value['s_name'])?$value['s_name']:NULL;?></td>
                    <td><?php echo isset($value['service_charge'])?$value['service_charge']:NULL;?></td>
                    <td><?php echo isset($value['done_by'])?$value['done_by']:NULL;?></td>
                    <td>                      
                            <?php
                              if ($value['work_status']=='0')
                                {echo '<p style="color:red;" >pending</p>';}
                              elseif($value['work_status']=='1')
                                {echo 'Done';} 
                            ?>                      
                    </td>
                    <td>
                           <?php
                              if ($value['update_status']=='0')
                                {echo '<p style="color:red;" >Not Updated</p>';}
                              elseif($value['update_status']=='1')
                                {echo 'Updated';} 
                            ?>
                       
                    </td>
                    <td>
                          <?php
                              if ($value['delivery_status']=='0')
                                {echo '<p style="color:red;" >Not delivered </p>';}                              
                              elseif($value['delivery_status']=='1')
                                {echo 'delivered';} 
                            ?>
                    </td>
                    
                    </tr>
                    <?php
                    }
                    ?> 