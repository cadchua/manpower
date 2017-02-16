<?php
require_once "../../../header/h.development.php"?>
<link rel="stylesheet" type="text/css" href="style.payroll_config.css">
<script type="text/javascript" data-main="script.payroll_config"
	src="../../../js/require.min.js"></script>
</head>
<body>
	<h1 id="module-title">Payroll Config</h1>



	<div id="payconfig-grid"></div>
	
	<div id="payconfig-popup" class="popup">
		<form id="payroll-config-form"
		style="font-size: 0.8em; font-family: Verdana, Arial, sans-serif;"
		method="post">
			<table>
				<thead>
				</thead>
				<tbody>
					<tr>
					<td>Pay Name</td>	<td><input id="pay_name" name="pay_name" type="text" required /></td>
					</tr>
					<tr>
					<td>Short Name</td>	<td><input id="short_name" name="short_name" type="text" required /></td>
					</tr>
					<tr>
					<td>Pay Category</td>
						<td><select id="pay_category" name="pay_category" required>
								<option value="">-select pay category-</option>
						</select></td>
					</tr>
					<tr>
					<td>Government Requirement</td>
						<td><select id="government_req" name="government_req">
								<option value="">-select government requirement-</option>
						</select></td>
					</tr>
					<tr>
					<td>Daily Limit</td>
					<td><input id="daily_limit" name="daily_limit" type="text"
							required /></td>
					</tr>
					<tr>
					<td>Max Limit</td>
						<td><input id="max_limit" name="max_limit" type="text" required /></td>
						</tr>
						
					<tr>
					<tr>
						<td><input id="income" name="income" type="checkbox" required /><label
							for="income">Income</label></td>
						<td><input id="taxable" name="taxable" type="checkbox" required />
							<label for="taxable">Taxable</label></td>
					</tr>
					<tr>
						<td><input id="tax_deductible" name="tax_deductible"
							type="checkbox" required /><label for="tax_deductible"> Tax
								Deductible </label></td>
						<td><input id="allowance" name="allowance" type="checkbox"
							required /><label for="allowance"> Allowance </label></td>
					</tr>
					<tr>
						<td><input id="loan" name="loan" type="checkbox" required /> <label
							for="loan"> Loan </label></td>
						<td><input id="cashadvance" name="cashadvance" type="checkbox" required /> <label
							for="cashadvance"> Cash Advance </label></td>
					</tr>

					<tr>
						<td>
							<button id="btn-save" type="button" name="save">Save</button>
						</td>
						<td>
							<button id="btn-reset" type="button" name="reset">Reset</button>
						</td>
					</tr>
				</tbody>
			</table>
	</form>
	</div>
</body>
</html>