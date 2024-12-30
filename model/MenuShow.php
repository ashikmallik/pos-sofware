<?php


class MenuShow
{
    public $q;

    function __construct() {
        $this->q =  ( isset($_GET['q']) ) ? $_GET['q'] : '' ;
    }

    public function itemList(){
        if( $this->q == 'all_category' ){return 'in';}
        else if( $this->q == 'all_item' ){return 'in';}
        else{return '';}
    }
    
    public function customer(){
        if( $this->q == 'add_customer' ){return 'in';}
        else if( $this->q == 'view_customer' ){return 'in';}
        else{return '';}
    }
    
    public function supplier(){
        if( $this->q == 'add_supplier' ){return 'in';}
        else if( $this->q == 'view_supplier' ){return 'in';}
        else{return '';}
    }
    
    public function purchase(){
        if( $this->q == 'add_purchase' ){return 'in';}
        else if( $this->q == 'view_all_purchase' ){return 'in';}
        else if( $this->q == 'return_purchase' ){return 'in';}
        else{return '';}
    }
    public function ledger(){
        if( $this->q == 'company_ledger' ){return 'in';}
        else if( $this->q == 'company_ledger2' ){return 'in';}
        else{return '';}
    }

     public function materials(){
        if( $this->q == 'add_material' ){return 'in';}
        else if( $this->q == 'add_used_material' ){return 'in';}
        else if( $this->q == 'all_material_stock' ){return 'in';}
        else if( $this->q == 'view_all_material_purchase' ){return 'in';}
        else{return '';}
    }

    public function sell(){
        
        if( $this->q == 'add_sell' ){return 'in';}
        else if( $this->q == 'view_all_sell' ){return 'in';}
        else if( $this->q == 'return_sales' ){return 'in';}
        else{return '';}
    }

    public function accounts(){
        
        if( $this->q == 'all_installment' ){return 'in';}
        else if( $this->q == 'account_statement' ){return 'in';}
        else if( $this->q == 'mobile_banking_statement' ){return 'in';}
        else if( $this->q == 'cash_account' ){return 'in';}
        else if( $this->q == 'bank_statement' ){return 'in';}
        else if( $this->q == 'widthrow_statement' ){return 'in';}
        else{return '';}
    }

    public function payment(){

        if( $this->q == 'add_payment_person' ){return 'in';}
        else if( $this->q == 'add_receive_person' ){return 'in';}
        else{return '';}
    }
    
    public function stock(){
        
        if( $this->q == 'today_stock_report' ){return 'in';}
        else if( $this->q == 'monthly_stock_report' ){return 'in';}
        else if( $this->q == 'all_stock_item' ){return 'in';}
        else{return '';}
    }
    
    public function income(){
        
        if( $this->q == 'view_other_income' ){return 'in';}
        else if( $this->q == 'view_income' ){return 'in';}
        else{return '';}
    }
    
    public function expense(){
        
        if( $this->q == 'view_account_head' ){return 'in';}
        else if( $this->q == 'view_expense' ){return 'in';}
        else{return '';}
    }
    
    public function purchase_report(){
        
        if( $this->q == 'today_purchase_report' ){return 'in';}
        else if( $this->q == 'monthly_purchase_report' ){return 'in';}
        else if( $this->q == 'yearly_purchase_report' ){return 'in';}
        else{return '';}
    }
    
    public function sale_report(){
        
        if( $this->q == 'today_sale_report' ){return 'in';}
        else if( $this->q == 'monthly_sale_report' ){return 'in';}
        else if( $this->q == 'yearly_sale_report' ){return 'in';}
        else{return '';}
    }

    public function delivery_report(){

        if( $this->q == 'today_delivery_report' ){return 'in';}
    }

    public function expense_report(){
        
        if( $this->q == 'expense_report' ){return 'in';}
        else{return '';}
    }
     public function discount_report(){

        if( $this->q == 'discount_report' ){return 'in';}
    }
    public function balance_sheet(){
        
        if( $this->q == 'monthly_balance_report' ){return 'in';}
        else if( $this->q == 'yearly_balance_report' ){return 'in';}
        else if( $this->q == 'profit_report' ){return 'in';}
        else{return '';}
    }

    public function bank(){
        
        if( $this->q == 'add_bank' ){return 'in';}
        else if( $this->q == 'view_bank' ){return 'in';}
        else if( $this->q == 'add_mobile_bank_registration' ){return 'in';}
        else if( $this->q == 'add_diposit_money' ){return 'in';}
        else if( $this->q == 'add_withdraw_money' ){return 'in';}
        else if( $this->q == 'view_bank_transection' ){return 'in';}
        else{return '';}
    }

    public function securityMoney(){
        if( $this->q == 'receive_security_money' ){return 'in';}
        else if( $this->q == 'provide_security_money' ){return 'in';}
        else if( $this->q == 'customer_security_money' ){return 'in';}
        else if( $this->q == 'supplier_security_money' ){return 'in';}
        else if( $this->q == 'view_single_customer_security_money' ){return 'in';}
        else if( $this->q == 'view_single_supplier_security_money' ){return 'in';}
        else{return '';}
    }

    public function employee(){

        if( $this->q == 'add_employee' ){return 'in';}
        elseif( $this->q == 'add_employee_salary' ){return 'in';}
        elseif( $this->q == 'employee_transaction' ){return 'in';}
        elseif( $this->q == 'view_single_employee_transaction' ){return 'in';}
        else{return '';}
    }
    
    public function person(){
        
        if( $this->q == 'add_person' ){return 'in';}else if( $this->q == 'create_person_loan' ){return 'in';}
        else if( $this->q == 'all_person_loan' ){return 'in';}
        else if( $this->q == 'view_single_loan' ){return 'in';}
        else if( $this->q == 'create_company_loan' ){return 'in';}
        else if( $this->q == 'all_company_loan' ){return 'in';}
        else if( $this->q == 'view_single_person_loan' ){return 'in';}
        else if( $this->q == 'view_single_company_loan' ){return 'in';}
        else{return '';}
    }
    
    public function smsmenu(){

        if( $this->q == 'add_customize_sms' ){return 'in';}
        elseif( $this->q == 'due_sms' ){return 'in';}
        elseif( $this->q == 'occation' ){return 'in';}
        elseif( $this->q == 'inactive_customer_sms' ){return 'in';}
        elseif( $this->q == 'marketing_sms' ){return 'in';}
        else{return '';}
    }
}