<?php
session_start();

$user_id = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;
$FullName = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : NULL;
$UserName = isset($_SESSION['UserName']) ? $_SESSION['UserName'] : NULL;
$PhotoPath = isset($_SESSION['PhotoPath']) ? $_SESSION['PhotoPath'] : NULL;
$ty = isset($_SESSION['UserType']) ? $_SESSION['UserType'] : NULL;

if (!empty($_SESSION['UserId'])) {

    include 'model/Controller.php';
    include 'model/FormateHelper.php';
    include 'model/MenuShow.php';

    $formater = new FormateHelper();
    $obj = new Controller();
    $menu = new Menushow();

    $wpdata = $obj->details_by_cond('vw_user_info', "UserId='$user_id'");
    if ($wpdata) {
        extract($wpdata);
    }
    $wp = isset($wpdata['WorkPermission']) ? $wpdata['WorkPermission'] : NULL;
    $acc = explode(',', $wp);
    define("home", "#");
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
        <link rel="shortcut icon" type="image/png" href="asset/img/favicon.png"/>
        <title>
            <?php
            $q = isset($_GET['q']) ? $_GET['q'] : NULL;
            if (!empty($q)) {
                echo ucwords(str_replace("_", " ", $q) . '  ');
            } else {
                echo 'Bangladesh Software Development.';
            }
            ?>
        </title>
        <?PHP include 'include/js_cs_link.php'; ?>
    </head>

    <body onload="startTime()">
    <div class="container" id="content" style="width:100% !important; padding:0px; border:0px; background-color:#EAEAEA;">
        <div class="col-md-12" style="background:#100000; padding:0px; min-height:50px;">
            <?php include 'include/header.php'; ?>
        </div>
        <div class="col-md-12" style="margin:15px 0px 15px 0px; padding:0px 15px 0px 0px;">
            <?php include 'include/sidebar.php'; ?>
            <div id="editbodyload" class="col-md-10" style="  background:#FFFFFF; border:1px solid #999999;"
                 id="bodyload">
                <?php
                if ($q == 'home') {
                    include 'include/body.php';
                } // Insert Page Start
                elseif ($q == 'usercreate') {
                    ($obj->hasPermission($ty,'usercreate')) ? include 'add/usercreate.php' : include 'include/body.php';
                } elseif ($q == 'add_customer') {
                    ($obj->hasPermission($ty,'add_customer')) ? include 'add/add_customer.php' : include 'include/body.php';
                } elseif ($q == 'add_purchase') {
                    ($obj->hasPermission($ty,'add_purchase')) ? include 'add/add_purchase.php' : include 'include/body.php';
                } elseif ($q == 'add_material') {
                    ($obj->hasPermission($ty,'add_purchase')) ? include 'add/add_material.php' : include 'include/body.php';
                } elseif ($q == 'view_all_material_purchase') {
                    ($obj->hasPermission($ty,'add_purchase')) ? include 'view/view_all_material_purchase.php' : include 'include/body.php';
                } elseif ($q == 'add_sell') {
                    ($obj->hasPermission($ty,'add_sell')) ? include 'add/add_sell.php' : include 'include/body.php';
                } elseif ($q == 'add_used_material') {
                    ($obj->hasPermission($ty,'add_sell')) ? include 'add/add_used_material.php' : include 'include/body.php';
                } elseif ($q == 'add_supplier') {
                    ($obj->hasPermission($ty,'add_supplier')) ? include 'add/add_supplier.php' : include 'include/body.php';
                } elseif ($q == 'add_person') {
                    ($obj->hasPermission($ty,'add_loan_person')) ? include 'add/add_person.php' : include 'include/body.php';
                } elseif ($q == 'add_employee') {
                    include 'add/add_employee.php';
                } elseif ($q == 'add_employee_salary') {
                    include 'add/add_employee_salary.php';
                } elseif ($q == 'create_person_loan') {
                    ($obj->hasPermission($ty,'add_loan_person')) ? include 'add/create_person_loan.php' : include 'include/body.php';
                } elseif ($q == 'create_company_loan') {
                    ($obj->hasPermission($ty,'take_loan')) ? include 'add/create_company_loan.php' : include 'include/body.php';}
                elseif ($q == 'add_income') {include 'add/add_income.php';}
                elseif ($q == 'return_purchase') {include 'add/return_purchase.php';}
                elseif ($q == 'return_sales') {include 'add/return_sales.php';}
                elseif ($q == 'add_payment') {include 'add/add_payment.php';}
                elseif ($q == 'add_diposit_money') {include 'add/add_deposit_money.php';}
                elseif ($q == 'add_withdraw_money') {include 'add/add_withdraw_money.php';}
                elseif ($q == 'add_bank') {
                    include 'add/add_bank_registration.php';
                }
                elseif ($q == 'add_mobile_bank_registration') {
                    include 'add/add_mobile_bank_registration.php';
                }
                elseif ($q == 'add_expense') {
                    include 'add/add_expense.php';
                } elseif ($q == 'add_payment_person') {
                    ($obj->hasPermission($ty,'add_payment_person')) ? include 'add/add_payment_person.php' : include 'include/body.php';
                }elseif ($q == 'add_receive_person') {
                    ($obj->hasPermission($ty,'add_payment_person')) ? include 'add/add_receive_person.php' : include 'include/body.php';
                } elseif ($q == 'receive_security_money') {
                    include 'add/receive_security_money.php';
                } elseif ($q == 'provide_security_money') {
                    include 'add/provide_security_money.php';
                } // Edit Page Start
                elseif ($q == 'edit_customer') {
                    include 'edit/edit_customer.php';
                }
                elseif ($q == 'edit_customer_due') {
                    include 'edit/edit_customer_due.php';
                }elseif ($q == 'adjustment') {
                    include 'edit/adjustment.php';
                } elseif ($q == 'edit_supplier') {
                    include 'edit/edit_supplier.php';
                }  elseif ($q == 'edit_employee') {
                    include 'edit/edit_employee.php';
                } elseif ($q == 'edit_purchase') {
                    include 'edit/edit_purchase.php';
                } elseif ($q == 'edit_sell') {
                    include 'edit/edit_sell.php';
                } elseif ($q == 'edit_account_head') {
                    include 'edit/edit_account_head.php';
                } elseif ($q == 'edit_other_income') {
                    include 'edit/edit_other_income.php';
                } elseif ($q == 'edit_expense') {
                    include 'edit/edit_expense.php';
                } elseif ($q == 'edit_income') {
                    include 'edit/edit_income.php';
                } elseif ($q == 'edit_bank') {
                    include 'edit/edit_bank.php';
                } elseif ($q == 'user_edit') {
                    include 'edit/user_edit.php';
                } elseif ($q == 'user_ch_pass') {
                    include 'edit/user_ch_pass.php';
                } elseif ($q == 'edit_shortage') {
                    include 'edit/edit_shortage.php';
                } // View Page Start
                elseif ($q == 'view_user') {
                    ($obj->hasPermission($ty,'usercreate')) ? include 'view/view_user.php' : include 'include/body.php';
                } elseif ($q == 'user_details') {
                    include 'view/user_details.php';
                } elseif ($q == 'delivery_sell') {
                    include 'view/delivery_sell.php';
                }  elseif ($q == 'all_item') {
                    ($obj->hasPermission($ty,'all_item')) ? include 'view/all_item.php' : include 'include/body.php';
                }
                elseif ($q == 'supplier_due') {
                    include 'view/supplier_due.php';
                }
                elseif ($q == 'customer_due') {
                    include 'view/customer_due.php';
                }elseif ($q == 'retailer_due') {
                    include 'view/retailer_due.php';
                }elseif ($q == 'dealer_due') {
                    include 'view/dealer_due.php';
                }elseif ($q == 'houseowner_due') {
                    include 'view/houseowner_due.php';
                }
                
                
                elseif ($q == 'payment_supplier_history') {include 'view/payment_history.php';}
                elseif ($q == 'receive_supplier_history') {include 'view/payment_history.php';}
                elseif ($q == 'payment_customer_history') {include 'view/payment_history.php';}
                elseif ($q == 'receive_customer_history') {include 'view/payment_history.php';}
                
                elseif ($q == 'view_single_person_loan') {
                    include 'view/view_single_person_loan.php';
                } elseif ($q == 'view_single_company_loan') {
                    include 'view/view_single_company_loan.php';
                } elseif ($q == 'supplier_security_money') {
                    include 'view/supplier_security_money.php';
                } elseif ($q == 'customer_security_money') {
                    include 'view/customer_security_money.php';
                }  elseif ($q == 'view_single_customer_security_money') {
                    include 'view/view_single_customer_security_money.php';
                }  elseif ($q == 'view_single_supplier_security_money') {
                    include 'view/view_single_supplier_security_money.php';
                } elseif ($q == 'all_category') {
                    ($obj->hasPermission($ty,'all_category')) ? include 'view/all_category.php' : include 'include/body.php';
                } elseif ($q == 'view_customer') {
                    ($obj->hasPermission($ty,'view_customer')) ? include 'view/view_customer.php' : include 'include/body.php';
                }elseif ($q == 'cus_comission_report') {
                    ($obj->hasPermission($ty,'cus_comission_report')) ? include 'view/cus_comission_report.php' : include 'include/body.php';
                }
                elseif ($q == 'view_supplier') {
                    ($obj->hasPermission($ty,'view_supplier')) ? include 'view/view_supplier.php' : include 'include/body.php';
                }elseif ($q == 'sup_comission_report') {
                    ($obj->hasPermission($ty,'sup_comission_report')) ? include 'view/sup_comission_report.php' : include 'include/body.php';
                }
                elseif ($q == 'view_product_ledger') {
                      include 'view/view_product_ledger.php';
                } elseif ($q == 'view_all_purchase') {
                    ($obj->hasPermission($ty,'view_all_purchase')) ? include 'view/view_all_purchase.php' : include 'include/body.php';
                } elseif ($q == 'view_all_waiting_for_purchase_rate') {
                    include 'view/view_all_waiting_for_purchase_rate.php';
                } elseif ($q == 'view_all_waiting_for_sell_rate') {
                    include 'view/view_all_waiting_for_sell_rate.php';
                } elseif ($q == 'view_account_head') {
                    ($obj->hasPermission($ty,'expense')) ? include 'view/view_account_head.php' : include 'include/body.php';
                } elseif ($q == 'view_other_income') {
                    ($obj->hasPermission($ty,'income_report')) ? include 'view/view_other_income.php' : include 'include/body.php';
                } elseif ($q == 'view_income') {
                    ($obj->hasPermission($ty,'income_report')) ? include 'view/view_income.php' : include 'include/body.php';
                }elseif ($q == 'view_income_details') {
                    ($obj->hasPermission($ty,'income_report')) ? include 'view/view_income_details.php' : include 'include/body.php';
                } elseif ($q == 'view_expense') {
                    ($obj->hasPermission($ty,'expense')) ? include 'view/view_expense.php' : include 'include/body.php';
                } elseif ($q == 'view_discount') {
                    include 'view/view_discount.php';
                } elseif ($q == 'view_all_sell') {
                    ($obj->hasPermission($ty,'view_all_sell')) ? include 'view/view_all_sell.php' : include 'include/body.php';}
                elseif ($q == 'today_delivery_report') {
                    ($obj->hasPermission($ty,'view_all_sell')) ? include 'view/today_delivery_report.php' : include 'include/body.php';} elseif ($q == 'monthly_delivery_report') {
                    ($obj->hasPermission($ty,'view_all_sell')) ? include 'view/monthly_delivery_report.php' : include 'include/body.php';}

                elseif ($q == 'view_all_shortage') {include 'view/view_all_shortage.php';}
                elseif ($q == 'customer_ledger') {include 'view/customer_ledger.php';}
                elseif ($q == 'supplier_ledger') {include 'view/supplier_ledger.php';}
                elseif ($q == 'view_bank') {include 'view/view_bank_registration.php';}
                elseif ($q == 'view_bank_transection') {
                    include 'view/view_bank_transection.php';
                } elseif ($q == 'all_stock_item') {
                    ($obj->hasPermission($ty,'stock_report')) ? include 'view/all_stock_item.php' : include 'include/body.php';
                } elseif ($q == 'all_material_stock') {
                    ($obj->hasPermission($ty,'stock_report')) ? include 'view/all_material_stock.php' : include 'include/body.php';
                } elseif ($q == 'view_single_sell') {
                    include 'view/view_single_sell.php';
                } elseif ($q == 'view_single_purchase') {
                    include 'view/view_single_purchase.php';
                } elseif ($q == 'all_person_loan') {
                    ($obj->hasPermission($ty,'loan_list')) ? include 'view/all_person_loan.php' : include 'include/body.php';
                } elseif ($q == 'all_company_loan') {
                    ($obj->hasPermission($ty,'loan_list')) ? include 'view/all_company_loan.php' : include 'include/body.php';
                } elseif ($q == 'employee_transaction') {
                    include 'view/employee_transaction.php';
                }  elseif ($q == 'view_single_employee_transaction') {
                    include 'view/view_single_employee_transaction.php';}
                elseif ($q == 'all_installment') {include 'view/view_all_installment.php';}
                elseif ($q == 'today_stock_report') {
                    ($obj->hasPermission($ty,'stock_report')) ? include 'view/report/today_stock_report.php' : include 'include/body.php';
                } elseif ($q == 'today_stock_report') {
                    include 'view/report/today_stock_report.php';
                } elseif ($q == 'monthly_stock_report') {
                    ($obj->hasPermission($ty,'stock_report')) ? include 'view/report/monthly_stock_report.php' : include 'include/body.php';
                } elseif ($q == 'yearly_stock_report') {
                    include 'view/report/yearly_stock_report.php';
                } elseif ($q == 'today_purchase_report') {
                    ($obj->hasPermission($ty,'purchase_report')) ? include 'view/report/today_purchase_report.php' : include 'include/body.php';
                } elseif ($q == 'monthly_purchase_report') {
                    ($obj->hasPermission($ty,'purchase_report')) ? include 'view/report/monthly_purchase_report.php' : include 'include/body.php';
                } elseif ($q == 'yearly_purchase_report') {
                    ($obj->hasPermission($ty,'purchase_report')) ? include 'view/report/yearly_purchase_report.php' : include 'include/body.php';
                } elseif ($q == 'today_sale_report') {
                    ($obj->hasPermission($ty,'sale_report')) ? include 'view/report/today_sale_report.php' : include 'include/body.php';
                } elseif ($q == 'monthly_sale_report') {
                    ($obj->hasPermission($ty,'sale_report')) ? include 'view/report/monthly_sale_report.php' : include 'include/body.php';
                } elseif ($q == 'expense_report') {
                    ($obj->hasPermission($ty,'expense_report')) ? include 'view/report/expense_report.php' : include 'include/body.php';
                } elseif ($q == 'yearly_sale_report') {
                    ($obj->hasPermission($ty,'sale_report')) ? include 'view/report/yearly_sale_report.php' : include 'include/body.php';
                } elseif ($q == 'monthly_balance_report') {
                    ($obj->hasPermission($ty,'balance_sheet')) ? include 'view/report/monthly_balance_report.php' : include 'include/body.php';
                } elseif ($q == 'yearly_balance_report') {
                    ($obj->hasPermission($ty,'balance_sheet')) ? include 'view/report/yearly_balance_report.php' : include 'include/body.php';
                } elseif ($q == 'account_statement') {
                    ($obj->hasPermission($ty,'account_statement')) ? include 'view/report/account_statement.php' : include 'include/body.php';
                } elseif ($q == 'cash_account') {
                    ($obj->hasPermission($ty,'account_statement')) ? include 'view/report/cash_account.php' : include 'include/body.php';
                }elseif ($q == 'bank_statement') {
                    ($obj->hasPermission($ty,'account_statement')) ? include 'view/report/bank_statement.php' : include 'include/body.php';
                }
                elseif ($q == 'mobile_banking_statement') {
                    ($obj->hasPermission($ty,'mobile_banking_statement')) ? include 'view/report/mobile_banking_statement.php' : include 'include/body.php';
                }
                elseif ($q == 'widthrow_statement') {
                    ($obj->hasPermission($ty,'widthrow_statement')) ? include 'view/report/widthrow_statement.php' : include 'include/body.php';
                }
                elseif ($q == 'single_bank_account') {
                    ($obj->hasPermission($ty,'account_statement')) ? include 'view/bank_statement.php' : include 'include/body.php';
                }
                elseif ($q == 's_p_report') {
                    ($obj->hasPermission($ty,'s_p_report')) ? include 'view/report/s_p_report.php' : include 'include/body.php';
                    
                }elseif ($q == 'profit_report') {
                    ($obj->hasPermission($ty,'balance_sheet')) ? include 'view/report/profit_report.php' : include 'include/body.php';
                    
                }
                elseif ($q == 'monthly_report') {
                    ($obj->hasPermission($ty,'monthly_report')) ? include 'view/report/monthly_report.php' : include 'include/body.php';
                    
                } 
                elseif ($q == 'yearly_report') {
                    ($obj->hasPermission($ty,'yearly_report')) ? include 'view/report/yearly_report.php' : include 'include/body.php';
                    
                }  elseif ($q == 'discount_report') {
                        ($obj->hasPermission($ty, 'expense_report')) ? include 'view/report/discount_report.php' : include 'include/body.php';
                }
                elseif ($q == 'add_customize_sms') {
                        ($obj->hasPermission($ty, 'add_customize_sms')) ? include 'sms/add_customize_sms.php' : include 'include/body.php';
                    } elseif ($q == 'due_sms') {
                        ($obj->hasPermission($ty, 'due_sms')) ? include 'sms/add_duesms.php' : include 'include/body.php';
                    } elseif ($q == 'occation') {
                        ($obj->hasPermission($ty, 'occation')) ? include 'sms/add_occationalsms.php' : include 'include/body.php';
                    } elseif ($q == 'inactive_customer_sms') {
                        ($obj->hasPermission($ty, 'inactive_customer_sms')) ? include 'sms/inactive_customer_sms.php': include 'include/body.php';}
                        elseif ($q == 'marketing_sms') {
                        ($obj->hasPermission($ty, 'marketing_sms')) ? include 'sms/marketing_sms.php': include 'include/body.php';
                    }
                    
                    elseif ($q == 'send_due_sms') {
                        ($obj->hasPermission($ty,'view')) ? include 'sms/send_due_sms.php': include 'include/body.php';
                    } elseif ($q == 'occationsend') {
                        ($obj->hasPermission($ty,'view')) ? include 'sms/send_occationalsms.php': include 'include/body.php';
                    } elseif ($q == 'remaindersms') {
                        ($obj->hasPermission($ty,'view')) ? include 'sms/remaindersms.php': include 'include/body.php';
                }
                    
                elseif ($q == 'printclient') {include 'print/print_client_list.php';}
                elseif ($q == 'printcustomer') {include 'print/print_customer.php';}
                elseif ($q == 'print_view_customer') {include 'print/print_view_customer.php';}
                elseif ($q == 'print_customer_ledger') {include 'print/print_customer_ledger.php';}
                elseif ($q == 'print_cash_account') {include 'print/print_cash_account.php';}
                elseif ($q == 'print_customer_list') {include 'print/print_customer_list.php';}
                elseif ($q == 'print_supplier_list') {include 'print/print_supplier_list.php';}
                elseif ($q == 'print_supplier_ledger') {include 'print/print_supplier_ledger.php';}
                elseif ($q == 'print_purchase') {include 'print/print_purchase_list.php';}
                elseif ($q == 'print_monthly_yearly_sell') {include 'print/print_monthly_yearly_sell.php';}
                elseif ($q == 'print_today_stock_report') {include 'print/print_today_stock_report.php';}
                elseif ($q == 'print_account_statement') {include 'print/print_account_statement.php';}
                elseif ($q == 'print_all_stock') {include 'print/print_all_stock.php';}
                elseif ($q == 'print_monthly_stock') {include 'print/print_monthly_stock.php';}
                elseif ($q == 'print_monthly_yearly_purchase') {include 'print/print_monthly_yearly_purchase.php';}
                elseif ($q == 'print_sell') {include 'print/print_sell_list.php';}
                elseif ($q == 'print_profit_report') {include 'print/print_profit_report.php';}
                elseif ($q == 'print_single_employee_transaction') {include 'print/print_single_employee_transaction.php';}
                else {include 'include/body.php';}
                ?>
            </div>
            <!-- ============== End Container ========================== -->
        </div>
        <div class="col-md-12 text-center" id="footer" class="box">
            <p style="font-size:11px;">&copy; <?php echo date('Y'); ?> <a href="http://bsdbd.com/" target="_blank">
                Bangladesh Software Development.</a>, All Rights Reserved</p>
        </div>
    </div>
    </body>
    </html>
    <?php
} else {
    header("location: include/login.php");
}
?>