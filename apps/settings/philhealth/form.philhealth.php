<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 11/17/2015
 * Time: 9:36 PM
 */
include("../../../header/h.development.php");
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
<div id="layout-container" style="100%;">
    <div id="layout" style="width: 100%; height: 100%;">
        <div>
            <form id="philhealth-form" style="font-size: 0.8em; font-family: Verdana,Arial,sans-serif;" method="post">
                <fieldset>
                    <legend>Philhealth Input</legend>
                    <tr>
                        <td>
                            Bracket Number: <input id="bracketno" name="bracketno" type="text" maxlength="3" style="width: 30px; " required/>
                        </td>
                        <td>
                            Salary Base: <input id="salarybase" name="salarybase" type="text" maxlength="8" style="width: 70px; " required/>
                        </td>
                        <td>
                            Employer Share: <input id="employershare" name="employershare" type="text" maxlength="8" style="width: 70px; " required/>
                        </td>
                        <td>
                            Employee Share: <input id="employeeshare" name="employeeshare" type="text" maxlength="5" style="width: 60px; " required/>
                        </td>
                        <td>
                            Total Contribution: <input id="totalcontribution" name="totalcontribution" type="text" maxlength="5" style="width: 60px; " required/>
                        </td>
                        <td>
                            <input type="text" id="row-id" name="row-id" maxlength="120" style="width: 60px;" hidden="hidden"/>
                        </td>
                        &nbsp;&nbsp;
                        <td>
                            <button id="btn-save" type="button" name="btn-save" style="font-size: 15px; width: 80px; height: 35px;">Save</button>
                            <button id="btn-reset" type="button" name="btn-reset" style="font-size: 15px; width: 80px; height: 35px;">Reset</button>
                        </td>
                    </tr>
                </fieldset>
            </form>
        </div>
        <div>
            <form id="philhealth-form-mod" style="font-size: 0.8em; font-family: Verdana,Arial,sans-serif;" method="post">
                <fieldset>
                    <legend>Philhealth Input</legend>
                    <tr>
                        <td>
                            Bracket Number: <input id="bracketno-mod" name="bracketno-mod" type="text" maxlength="3" style="width: 30px; " required/>
                        </td>
                        <td>
                            Salary Base: <input id="salarybase-mod" name="salarybase-mod" type="text" maxlength="8" style="width: 70px; " required/>
                        </td>
                        <td>
                            Employer Share: <input id="employershare-mod" name="employershare-mod" type="text" maxlength="8" style="width: 70px; " required/>
                        </td>
                        <td>
                            Employee Share: <input id="employeeshare-mod" name="employeeshare-mod" type="text" maxlength="8" style="width: 60px; " required/>
                        </td>
                        <td>
                            Total Contribution: <input id="totalcontribution-mod" name="totalcontribution-mod" type="text" maxlength="5" style="width: 60px; " required/>
                        </td>
                        <td>
                            <input type="text" id="row-id-mod" name="row-id-mod" maxlength="120" style="width: 60px;" hidden="hidden"/>
                        </td>
                        &nbsp;&nbsp;
                        <td>
                            <button id="btn-update-mod" type="button" name="btn-update-mod" style="font-size: 15px; width: 80px; height: 35px;">Update</button>
                        </td>
                    </tr>
                </fieldset>
            </form>
        </div>
        <br>
        <div id="philhealth-grid" style="width: 100%; height: 600px;"></div>
    </div>
</div>
</body>
</html>
<script type="text/javascript" src="script.philhealth.js"></script>