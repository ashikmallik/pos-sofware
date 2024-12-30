<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$date = date('Y-m-d');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$allPersonLoan = $obj->view_selected_field_by_cond_left_join("tbl_company_lend", 'tbl_person', 'person_id', 'id', 'SUM(tbl_company_lend.loan_recieve) as total_loan_recieve, SUM(tbl_company_lend.loan_repayment) as total_loan_repayment', '*', 'tbl_company_lend.id != 0 GROUP BY tbl_company_lend.`person_id`');

$company_repay_loan_to_person_type = $obj->getAccTypeId('company_repay_loan_to_person'); // for accounts



if (isset($_POST['submit_loan_payment'])) {
    $loan_receive = $obj->get_sum_data('tbl_company_lend','loan_recieve','person_id='.$_POST['person_id']);
    $loan_repay = $obj->get_sum_data('tbl_company_lend','loan_repayment','person_id='.$_POST['person_id']);

    echo $total_loan_check = $loan_receive - ($loan_repay+$_POST['loan_re_payment']);

    if ($total_loan_check < 0){
        $personData = $obj->details_by_cond('tbl_person', 'id=' . $_POST['person_id']);
        $form_data_loan_accounts = array(
            'acc_description' => 'Company Repay loan of ' . $personData['person_name'].'. '.$_POST['description'],
            'acc_amount' => $_POST['loan_re_payment'],
            'acc_type' => $company_repay_loan_to_person_type,
            'purchase_or_sell_id' => 'p_' . $_POST['person_id'],
            'cus_or_sup_id' => $personData['person_id'],
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );

        $accountsId = $obj->insert_by_condition("tbl_account", $form_data_loan_accounts, " ");

        $form_data_company_loan = array(
            'person_id' => $_POST['person_id'],
            'loan_recieve' => 0,
            'loan_repayment' => $_POST['loan_re_payment']-(-$total_loan_check),
            'accounts_id' => $accountsId,
            'created_at' => $date_time,
        );

        $form_data_person_loan = array(
            'person_id' => $_POST['person_id'],
            'loan_recieve' => -$total_loan_check,
            'loan_repayment' => 0,
            'accounts_id' => $accountsId,
            'created_at' => $date_time,
        );

        $obj->insert_by_condition("tbl_person_loan", $form_data_person_loan, " ");
        if($obj->insert_by_condition("tbl_company_lend", $form_data_company_loan, " ")){

            $obj->notificationStore('Company Repay Loan to ' . $personData['person_name'] . ' Successfully', 'success');
            echo '<script>window.location = "?q=all_company_loan";</script>';
        }else{

            $obj->notificationStore('Company Repay Loan Store Failed');
        }
    }else{
        $personData = $obj->details_by_cond('tbl_person', 'id=' . $_POST['person_id']);
        $form_data_loan_accounts = array(
            'acc_description' => 'Company Repay loan of ' . $personData['person_name'].'. '.$_POST['description'],
            'acc_amount' => $_POST['loan_re_payment'],
            'acc_type' => $company_repay_loan_to_person_type,
            'purchase_or_sell_id' => 'p_' . $_POST['person_id'],
            'cus_or_sup_id' => $personData['person_id'],
            'acc_head' => 0,
            'entry_by' => $userid,
            'entry_date' => date('Y-m-d'),
            'update_by' => $userid
        );

        $accountsId = $obj->insert_by_condition("tbl_account", $form_data_loan_accounts, " ");

        $form_data_person_loan = array(
            'person_id' => $_POST['person_id'],
            'loan_recieve' => 0,
            'loan_repayment' => $_POST['loan_re_payment'],
            'accounts_id' => $accountsId,
            'created_at' => $date_time,
        );

        if($obj->insert_by_condition("tbl_company_lend", $form_data_person_loan, " ")){

            $obj->notificationStore('Company Repay Loan to ' . $personData['person_name'] . ' Successfully', 'success');
            echo '<script>window.location = "?q=all_company_loan";</script>';
        }else{

            $obj->notificationStore('Company Repay Loan Store Failed');
        }
    }



}

?>

<div class="row">
    <div class="col-md-12 padding_5_px">
        <?php $obj->notificationShowRedirect(); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 bg-teal-800">
        <h4>View Company loan List</h4>
    </div>
</div>
<hr>
<div class="row" style="font-size:12px">
    <div class="col-md-12">
        <table class="table table-responsive table-bordered table-hover " id="datatable">
            <thead>
            <tr class="bg-slate-800">
                <th class="col-md-1">SL</th>
                <th class="col-md-1">Person Name</th>
                <th class="col-md-1">Mobile No</th>
                <th class="col-md-1">Address</th>
                <th class="col-md-2">
                    <small>Total Loan Recieve</small>
                </th>
                <th class="col-md-2">
                    <small>Total Loan Repay</small>
                </th>
                <th class="col-md-2"> Total Loan</th>
                <th class="col-md-1">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0;
            foreach ($allPersonLoan as $personLoan) { ?>
                <tr>
                    <td class="text-center"> <?php echo ++$i ?> </td>
                    <td class="text-center"><a
                            href="?q=view_single_company_loan&personid=<?php echo $personLoan['id'] ?>"><?php echo $personLoan['person_name'] ?></a>
                    </td>
                    <td class="text-center"> <?php echo $personLoan['person_mobile_no'] ?> </td>
                    <td class="text-center"> <?php echo $personLoan['person_address'] ?> </td>
                    <td class="text-center"> <?php echo number_format($personLoan['total_loan_recieve']) ?> </td>
                    <td class="text-center"> <?php echo number_format($personLoan['total_loan_repayment']) ?> </td>
                    <?php $totalLoan = $personLoan['total_loan_recieve'] - $personLoan['total_loan_repayment']; ?>

                    <td class="text-center"> <?php echo ($totalLoan < 0) ? '(Advance) ' : '';
                        echo number_format(abs($totalLoan)) ?> </td>

                    <td class="text-center action-btn">
                        <button class="edit btn btn-success btn-sm" data-toggle="modal" data-target="#editloan"
                                data-id="<?php echo $personLoan['id'] ?>"
                                data-person="<?php echo $personLoan['person_name'] ?>">Payment
                        </button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<hr>

<div class="modal fade" id="editloan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Payment of Loan Repay for Person <span id="person_name"></span></h4>
            </div>

            <form id="editloanForm" action="" method="post" class="form-horizontal">

                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Loan Repayment</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input onkeypress="return numbersOnly(event)" require type="number"
                                       name="loan_re_payment" class="form-control">
                                <span class="input-group-addon">Taka</span>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-8">
                            <textarea name="description" rows="7" class="form-control"></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="person_id" value="">

                </div>
                <div class="modal-footer text-center">

                    <button type="submit" name="submit_loan_payment" class="btn btn-primary">Save Payment</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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


    $(document).ready(function () {

        $('table#datatable').on('click', 'td.action-btn button.edit', function () {

            var personId = $(this).data('id');
            var personName = $(this).data('person');

            console.log(personId);
            console.log(personName);

            $('div#editloan div.modal-header span#person_name').html(personName);
            $('div#editloan form#editloanForm div.modal-body input[name="person_id"]').val(personId);

        });

        $('#editloan').on('hidden.bs.modal', function (e) {

            $('form#editloanForm').trigger("reset");
        })

    });
</script>