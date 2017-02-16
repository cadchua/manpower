<?php
require_once "../../../header/h.development.php";
?>
<link rel="stylesheet" type="text/css"
	href="style.module_management.css">
<script type="text/javascript" data-main="script.module_management"
	src="../../../js/require.min.js"></script>
</head>
<body>
	<h1 id="module-title">Module Management</h1>
	<div id="category-grid" class="grid"></div>
	<div id="module-grid" class="grid"></div>

	<div id="category-popup" class="popup" title="Add Category">
		<form id="category-form">
			<table id="category-table">
				<tr>
					<td><label for="txt-category">Category: </label></td>
					<td><input type="text" id="txt-category" name="category"></td>
				</tr>
				<tr>
					<td><label for="txt-sequence">Sequence: </label></td>
					<td><input type="text" id="txt-sequence" name="sequence"></td>
				</tr>
			</table>
			<input type="submit" id="submit-category">
		</form>
	</div>

	<div id="module-popup" class="popup" title="Add Module">
		<form id="module-form">
			<table id="module-table">
				<tr>
					<td><label for="txt-module">Module: </label></td>
					<td><input type="text" id="txt-module" name="module"></td>
				</tr>
				<tr>
					<td><label for="txt-filename">File Location: </label></td>
					<td><input type="text" id="txt-filename" name="filename"></td>
				</tr>
				<tr>
					<td><label for="txt-sequence">Sequence: </label></td>
					<td><input type="text" id="txt-sequence" name="sequence"></td>
				</tr>
			</table>
			<input type="submit" id="submit-module">
		</form>
	</div>
</body>
</html>