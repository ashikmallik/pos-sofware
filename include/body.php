        <div class="col-md-12" style=" background:#606060; min-height:40px; margin-top:20px; padding:8px 0px 0px 15px;
        font-size:16px; font-family:Lucida Sans Unicode; color:#FFFFFF; font-weight:bold;">
			<b>Dashboard</b>
		</div>

		<div class="row" style="margin-top:100px;">
            <div class="col-md-7 col-md-offset-1">
                <h4 class="text-center">Income and Expense for Previous Months</h4>
                <canvas id="accountsChart"></canvas>
            </div>

			<div class="col-md-3 col-md-offset-1">
				<h4 class="text-center padding_10_px">Debit & Credit of <?php echo date("F"); ?></h4>
				<canvas id="expenseIncome"></canvas>
			</div>
		</div>
	</div>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

		<script type="text/javascript" src="asset/js/CustomChartData.js"></script>


				