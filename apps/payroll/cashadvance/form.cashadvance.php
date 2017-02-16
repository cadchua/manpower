<?php
require_once "../../../header/h.development.php"?>
<link rel="stylesheet" type="text/css" href="style.cashadvance.css">
<script type="text/javascript" data-main="script.cashadvance"
	src="../../../js/require.min.js"></script>
</head>
<body>
	<div id="cash-tabs">
		<ul>
			<li><a href="#cash-tabs-1">Cash Advance</a></li>
		</ul>
		<div id="cash-tabs-1">
			<div id="cash-advance-wrap">
				<div id="cash-advance-tab">
					<div class="grid pane" id="employee-grid"></div>
					<div id="cash-advance-pane" class="pane">
						<div id="employee-info-container">
							
							<table class="employee-info">
								<tbody>
									<tr valign="top">   
										<td><img class="employee-img"></td>
										<td>
										  ID:<span class="employee-id"></span>
										  <br>
										  Name:<span class="employee-name"></span>
										</td>
										
									</tr>
									
								</tbody>
							</table>
                                              
						</div>
					</div>
				</div>

				<div id="ca-btns" style="position:absolute;margin-left:400px;margin-top:400px">
					<input type="button" id="save-ca-btn" value="Save Cash Advances" class="button">
					<input type="button" id="view-ca-btn" value="View Cash Advance"
						class="button">
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>

	<!-- employee cashadvance accounts popup -->
	<div id="employee-ca-accounts-popup" class="popup">
		<div id="employee-ca-info-container">
			<img class="employee-img">
			<table class="employee-info">
				<tbody>
					<tr>
						<td>ID:</td>
						<td class="employee-id"></td>
					</tr>
					<tr>
						<td>Name:</td>
						<td class="employee-name"></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="clear" id="ca-list-controls">
			<select id="ca-list-cashadvance-type">
				<option value="">-CASH ADVANCE TYPE-</option>
			</select>

			<p id="ca-list-balance-container">
				BALANCE: <span id="ca-list-balance"></span>
			</p>

			<input type="button" id="add-memo-btn" value="Add DR/CR Memo"> <input
				type="text" class="datepicker" id="ca-from-txt"
				placeholder="FROM DATE"> <input type="text" class="datepicker"
				id="ca-to-txt" placeholder="TO DATE"> <input type="button"
				id="ca-display-btn" value="Display">

		</div>

		<div class="grid pane" id="ca-list-grid"></div>
		<div class="both" id="ca-hold-ctrl">
			<input type="checkbox" id="ca-hold-chk"> <label for="ca-hold-chk">Hold</label>
		</div>
	</div>

	<!-- popup for dr/cr memo -->
	<div id="drcrmemo-popup" class="popup">
		<table id="drcrmemo-table">
			<tr>
				<td><label for="memo-type">Type: </label></td>
				<td><select id="memo-type">
						<option value="">-SELECT TYPE-</option>
						<option value="038003">Debit</option>
						<option value="038004">Credit</option>
				</select></td>
			</tr>
			<tr>
				<td><label for="memo-date">Date: </label></td>
				<td><input type="text" class='datepicker' id="memo-date"></td>
			</tr>
			<tr>
				<td><label for="memo-amount">Amount: </label></td>
				<td><input type='text' id="memo-amount"></td>
			</tr>
			<tr>
				<td><input type="button" id="memo-save-btn" value="Save"></td>
			</tr>
		</table>
	</div>
</body>
</html>