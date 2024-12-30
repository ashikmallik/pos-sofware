<div class="col-md-2" style="padding:0px 10px 0px 0px;">

    <div class="col-md-12" style="padding:0px; width:100%;">

        <div id="body_service">
            <div id="body_service_list">

                <div id="body_service_list_text">
                    <ul class="box" style="margin:0px ! important;">

                        <li class="mactive">
                            <a href="?q=main" class="left_menu_bull mainli" style="color:white;">Dashboard</a>
                            <ul>
                                <?php if ($ty == 'SA') { ?>
                                    <li class="list">&nbsp;<span class="glyphicon glyphicon-user"></span>&nbsp;<a class="texta" href="?q=usercreate">User Create</a></li>
                                    <?php }
                                if ($ty == 'SA') {?>
                                    <li class="list">&nbsp;<span class="glyphicon glyphicon-user"></span>&nbsp;<a class="texta" href="?q=view_user">View User Info</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#item"
                               class="left_menu_bull mainli" style="color:white;">Item List</a>
                            <ul id="item" class="collapse <?php echo $menu->itemList(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'all_category', 'all_category', 'All Category', 'th-list');
                                $obj->printSidebarElement($ty, 'all_item','all_item', 'All Item', 'th-list'); ?>
                            </ul>
                        </li>
                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#customer" class="left_menu_bull"
                               style="padding: 1px 0px 1px 30px; font-size:14px;  color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-weight:600;">Customer Info</a>
                            <ul id="customer" class="collapse <?php echo $menu->customer(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'add_customer', 'add_customer', 'Add New Customer', 'plus');
                                $obj->printSidebarElement($ty, 'view_customer','view_customer', 'Customer Info', 'fullscreen'); 
                                $obj->printSidebarElement($ty, 'cus_comission_report','cus_comission_report', 'Comission Report', 'fullscreen');
                                 $obj->printSidebarElement($ty, 'customer_due','customer_due', 'Customer Advance/ Due', 'th-list');
                                 /*$obj->printSidebarElement($ty, 'retailer_due','retailer_due', 'Retailer Advance/ Due', 'th-list');
                                 $obj->printSidebarElement($ty, 'dealer_due','dealer_due', 'Workshop Advance/ Due', 'th-list'); 
                                 $obj->printSidebarElement($ty, 'houseowner_due','houseowner_due', 'Houseowner Advance/ Due', 'th-list'); */  
                                 ?>

                                


                            </ul>
                        </li>
                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#supplier" class="left_menu_bull"
                               style=" padding: 1px 0px 1px 30px;
                               font-size:14px;  color:#FFFFFF; font-family:Arial, Helvetica, sans-serif;
                               font-weight:600;">Supplier Info</a>
                            <ul id="supplier" class="collapse <?php echo $menu->supplier(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'add_supplier', 'add_supplier', 'Add New Supplier', 'plus');
                                $obj->printSidebarElement($ty, 'view_supplier','view_supplier', 'Supplier Info', 'fullscreen'); 
                                $obj->printSidebarElement($ty, 'sup_comission_report','sup_comission_report', 'Comission Report', 'fullscreen'); 
                                $obj->printSidebarElement($ty, 'supplier_due','supplier_due', 'Supplier Advance/ Due', 'th-list'); 
                                ?>
                            </ul>
                        </li>
                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#purchase"
                               class="left_menu_bull mainli"
                               style="color:white;">Purchase</a>
                            <ul id="purchase" class="collapse <?php echo $menu->purchase(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'add_purchase', 'add_purchase', 'New Purchase', 'plus-sign');
                                $obj->printSidebarElement($ty, 'view_all_purchase','view_all_purchase', 'All Purchase', 'book');
                                $obj->printSidebarElement($ty, 'add_purchase', 'return_purchase', 'Purchase Return', 'repeat'); ?>
                            </ul>
                        </li>

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#sell"
                               class="left_menu_bull mainli" style="color:white;">Sell</a>
                            <ul id="sell" class="collapse <?php echo $menu->sell(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'add_sell', 'add_sell', 'New Sell', 'plus-sign');
                                $obj->printSidebarElement($ty, 'view_all_sell','view_all_sell', 'All Sell', 'book');
                                $obj->printSidebarElement($ty, 'view_all_sell','return_sales', 'Sales Return', 'repeat'); ?>
                            </ul>
                        </li>

                      

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#accounts"
                               class="left_menu_bull mainli"
                               style="color:white;">Accounts</a>
                            <ul id="accounts" class="collapse <?php echo $menu->accounts(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'account_statement','account_statement', 'Account Statement', 'list');
                                $obj->printSidebarElement($ty, 'account_statement','cash_account', 'Cash Statement', 'list');
                                $obj->printSidebarElement($ty, 'account_statement','bank_statement', 'Bank Statement', 'list');
                                 $obj->printSidebarElement($ty, 'mobile_banking_statement','mobile_banking_statement', 'Mobile Banking Statement', 'list');
                                 $obj->printSidebarElement($ty, 'widthrow_statement','widthrow_statement', 'Withdrow Banking Statement', 'list');
                             //   $obj->printSidebarElement($ty, 'account_statement','all_installment', 'Installments', 'list'); ?>
                            </ul>
                        </li>

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#payments"
                               class="left_menu_bull mainli"
                               style="color:white;">Payment | Receive</a>
                            <ul id="payments" class="collapse <?php echo $menu->payment(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'add_payment_person', 'add_payment_person', 'Payment', 'euro');
                                $obj->printSidebarElement($ty, 'add_payment_person', 'add_receive_person', 'Receive', 'euro'); 
                                
                                
                                 $obj->printSidebarElement($ty, 'payment_supplier_history', 'payment_supplier_history', 'Supplier Paymnet History', 'euro');
                                 $obj->printSidebarElement($ty, 'receive_supplier_history', 'receive_supplier_history', 'Supplier Return History', 'euro');
                                 $obj->printSidebarElement($ty, 'receive_customer_history', 'receive_customer_history', 'Customer Receive History', 'euro');
                                 $obj->printSidebarElement($ty, 'payment_customer_history', 'payment_customer_history', 'Customer Return History', 'euro');
                                 
                                ?>
                            </ul>
                        </li>

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#stock"
                               class="left_menu_bull mainli"
                               style="color:white;">Stock</a>
                            <ul id="stock" class="collapse <?php echo $menu->stock(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'stock_report', 'today_stock_report', 'Today Stock Report', 'list-alt');
                                $obj->printSidebarElement($ty, 'stock_report','monthly_stock_report', 'Monthly Stock Report', 'list-alt');
                                $obj->printSidebarElement($ty, 'stock_report','all_stock_item', 'All Stock Item', 'list-alt');
                              ?>
                            </ul>
                        </li>

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#income"
                               class="left_menu_bull mainli"
                               style="color:white;">Income</a>
                            <ul id="income" class="collapse <?php echo $menu->income(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'income_report', 'view_other_income', 'Income List', 'list-alt');
                                $obj->printSidebarElement($ty, 'income_report','view_income', 'Income', 'usd'); ?>
                            </ul>
                        </li>

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#expense"
                               class="left_menu_bull mainli"
                               style="color:white;">Expense</a>
                            <ul id="expense" class="collapse <?php echo $menu->expense(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'expense_report', 'view_account_head', 'Expense Head', 'list-alt');
                                $obj->printSidebarElement($ty, 'expense_report','view_expense', 'Expense', 'usd'); ?>
                            </ul>
                        </li>

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#purchase_report"
                               class="left_menu_bull mainli" style="color:white;">Purchase Report</a>
                            <ul id="purchase_report" class="collapse <?php echo $menu->purchase_report(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'purchase_report', 'today_purchase_report', 'Today Purchase Report', 'list-alt');
                                $obj->printSidebarElement($ty, 'purchase_report','monthly_purchase_report', 'Monthly Purchase Report', 'list-alt');
                                $obj->printSidebarElement($ty, 'purchase_report','yearly_purchase_report', 'All Purchase Item', 'list-alt'); ?>
                            </ul>
                        </li>

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#sale_report"
                               class="left_menu_bull mainli" style="color:white;">Sale Report</a>
                            <ul id="sale_report" class="collapse <?php echo $menu->sale_report(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'sale_report', 'today_sale_report', 'Today Sale Report', 'list-alt');
                                $obj->printSidebarElement($ty, 'sale_report','monthly_sale_report', 'Monthly Sale Report', 'list-alt');
                                $obj->printSidebarElement($ty, 'sale_report','yearly_sale_report', 'All Sale Item', 'list-alt'); ?>
                            </ul>
                        </li>

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#delivery_report"
                               class="left_menu_bull mainli" style="color:white;">Delivery Report</a>
                            <ul id="delivery_report" class="collapse <?php echo $menu->delivery_report(); ?>">
                                <?php
                                    $obj->printSidebarElement($ty, 'sale_report', 'today_delivery_report', 'Today Delivery Report', 'list-alt');
                                ?>
                            </ul>
                        </li>

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#expense_report"
                               class="left_menu_bull mainli" style="color:white;">Expense Report</a>
                            <ul id="expense_report" class="collapse <?php echo $menu->expense_report(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'expense_report', 'expense_report&action=day', 'Today Expense Report', 'list-alt');
                                $obj->printSidebarElement($ty, 'expense_report','expense_report&action=month', 'Monthly Expense Report', 'list-alt');
                                $obj->printSidebarElement($ty, 'expense_report','expense_report&&action=year', 'All Expense Item', 'list-alt'); ?>
                            </ul>
                        </li>
                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#discount_report"
                               class="left_menu_bull mainli" style="color:white;">Discount Report</a>
                            <ul id="discount_report" class="collapse <?php echo $menu->discount_report(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'expense_report', 'discount_report', 'Discount Report', 'list-alt');
                                ?>
                            </ul>
                        </li>
                  
                        <!-- <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#balance_sheet"
                               class="left_menu_bull mainli" style="color:white;">Balance Sheet</a>
                            <ul id="balance_sheet" class="collapse <?php echo $menu->balance_sheet(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'balance_sheet', 'monthly_bala/nce_report', 'Monthly Balance Report', 'euro');
                                $obj->printSidebarElement($ty, 'balance_sheet','yearly_balance_report', 'Yearly Expense Report', 'euro');
                                $obj->printSidebarElement($ty, 'balance_sheet','profit_report', 'Profit Report', 'gbp'); ?>
                            </ul>
                        </li> -->
                        <li class="mactive">
    <a data-toggle="collapse" data-parent="#accordion" href="#balance_sheet"
       class="left_menu_bull mainli" style="color:white;">Balance Sheet</a>
    <ul id="balance_sheet" class="collapse <?php echo $menu->balance_sheet(); ?>">
        <?php
        $obj->printSidebarElement($ty, 'balance_sheet', 'monthly_balance_report', 'Monthly Balance Report', 'euro');
        $obj->printSidebarElement($ty, 'balance_sheet','yearly_balance_report', 'Yearly Expense Report', 'euro');
        $obj->printSidebarElement($ty, 'balance_sheet','s_p_report', 'Sell-Purchase report', 'euro');
        $obj->printSidebarElement($ty, 'balance_sheet','profit_report', 'Profit Report', 'gbp');
        $obj->printSidebarElement($ty, 'balance_sheet','monthly_report', 'Monthly Profit Report', 'gbp');
        $obj->printSidebarElement($ty, 'balance_sheet','yearly_report', 'Yearly Profit Report', 'gbp');
    
        ?>
    </ul>
</li> 

                      

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#employee"
                               class="left_menu_bull mainli" style="color:white;">Employee</a>
                            <ul id="employee" class="collapse <?php echo $menu->employee(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'add_employee', 'add_employee', 'Add New Employee', 'user');
                                $obj->printSidebarElement($ty, 'add_employee','add_employee_salary', 'Assign Employee Salary', 'gbp');
                                $obj->printSidebarElement($ty, 'view_employee','employee_transaction', 'Employee Transaction', 'transfer');
                                ?>

                            </ul>
                        </li>

                        <li class="mactive">
                            <a data-toggle="collapse" data-parent="#accordion" href="#bank"
                               class="left_menu_bull mainli" style="color:white;">Banking</a>
                            <ul id="bank" class="collapse <?php echo $menu->bank(); ?>">
                                <?php
                                $obj->printSidebarElement($ty, 'add_bnk', 'add_bank', 'Bank Account Registration', 'briefcase');
                                $obj->printSidebarElement($ty, 'add_mobile_bank_registration', 'add_mobile_bank_registration', 'Mobile Banking Registration', 'briefcase');
                                $obj->printSidebarElement($ty, 'view_bank','view_bank', 'View Bank Info', 'briefcase');
                                $obj->printSidebarElement($ty, 'add_bnk','add_diposit_money', 'Diposit money', 'usd');
                                $obj->printSidebarElement($ty, 'add_bnk','add_withdraw_money', 'Withdraw money', 'usd');
                                $obj->printSidebarElement($ty, 'view_bank','view_bank_transection', 'View bank Transection', 'transfer'); ?>
                            </ul>
                        </li>
                        
                         <li class="mactive">
                        <a data-toggle="collapse" data-parent="#accordion" href="#smsservice"class="left_menu_bull mainli" style="color:white;">SMS SERVICE</a>
                                <ul id="smsservice" class="collapse <?php echo $menu->smsmenu(); ?>">
                                <?php
                              
                                $obj->printSidebarElement( $ty, 'due_sms','due_sms', 'Due SMS');
                                $obj->printSidebarElement( $ty, 'occation','occation', 'Occational SMS');
                                // $obj->printSidebarElement( $ty, 'inactive_customer_sms','inactive_customer_sms', 'Inactive Customer SMS');
                                // $obj->printSidebarElement( $ty, 'marketing_sms','marketing_sms', 'Marketing SMS');
                                
                                ?>
                            </ul>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>