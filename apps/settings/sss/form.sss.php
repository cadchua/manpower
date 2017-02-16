<?php
require_once "../../../header/h.development.php"?>
<link rel="stylesheet" type="text/css" href="style.sss.css">
<script type="text/javascript" data-main="script.sss"
	src="../../../js/require.min.js"></script>
</head>
<body>
	<h1 id="module-title">SSS</h1>

	<form id="sss-form" style="font-size: 0.8em; font-family: Verdana,Arial,sans-serif;" method="post">
                <fieldset>
                    <legend>SSS Input</legend>
                    <table>
                    <thead>
                    </thead>
                    <tbody>
                    <tr>
                    	<td><input id="bracket_no" name="bracket_no" type="text" required/></td>
                    	<td><input id="salary" name="salary" type="text" required/></td>
                    	<td><input id="monthly_credit" name="monthly_credit" required/></td>
                    	<td><input id="employer_share" name="employer_share" required/></td>
                    	<td><input id="employee_share" name="employee_share" required/></td>
                    	<td><input id="total_contribution" name="total_contribution" type="text" required/></td>
                    	<td>
                    	    <button id="btn-save" type="button" name="save" >Save</button>
                    	</td>
                    </tr>
                    <tr>
                        <td>
                            Bracket Number 
                        </td>
                        <td>
                            Salary Base 
                        </td>
                        <td>
                            Monthly Credit
                        </td>
                        <td>
                            Employer Share
                        </td>
                        <td>
                            Employee Share
                        </td>
                        <td>
                            Total Contribution
                        </td>
                        &nbsp;&nbsp;
                        <td>
                            <button id="btn-reset" type="button" name="reset" >Reset</button>
                        </td>
                    </tr>
                    </tbody>
                    </table>
                </fieldset>
            </form>
            
	<div id="sss-grid"></div>
</body>
</html>