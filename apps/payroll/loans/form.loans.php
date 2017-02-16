<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 12/8/2015
 * Time: 10:46 PM
 */
include("../../../header/h.development.php");
?>

<!DOCTYPE html>
<html>
<head>
    <link href="style.loans.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="layout-container" style="100%;">
    <div id="layout" style="width: 100%; height: 100%;">
        <div id="form" style="width:100%; height: 400px; display: block">
            <div class="w2ui-page page-0">

                <div id="left-grid-employeelist">
                    <!--<div class="header-employeelist" ><div class="title-span-center"> List of Employees </div></div>-->
                    <div class="w2ui-group-override">
                        <!--<form name="employeelist-form" id = "employeelist-form">-->
                                <div id="toolbar-employees" style="margin-left:0px; padding: 4px; border: 1px solid silver; border-radius: 3px;width:100%"></div>
                            <div id="employeelist-grid" style="width: 100%; height: 525px;"></div>
                        <!--</form>-->
                    </div>
                </div>
                <div id="right-grid-newloans">
                    <div class="w2ui-group-override">
                        <fieldset style="margin-bottom: 10px; ">
                            <legend style="font-family: Verdana, Arial, sans-serif; font-size: 14px;">Employee Details</legend>
                            <table>
                                <tr class="empdetails">
                                    <td>
                                        <img src="img-employee" id="img-employee" name="" style="width: 128px; height: 128px;">
                                    <td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    ID:
                                                </td>
                                                <td>
                                                    <div id="loans-employee-id"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Name:
                                                </td>
                                                <td>
                                                    <div id="loans-employee-name"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Branch:
                                                </td>
                                                <td>
                                                    <div id="loans-employee-branch"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Status:
                                                </td>
                                                <td>
                                                    <div id="loans-employee-status"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="button" id="btn-new-loans" name="btn-new-loans" value="New Loan" style="width: 80px; height: 35px;">
                                                    <!--<input type="button" id="btn-hold-loans" name="btn-hold-loans" value="Hold">-->
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                        <div id="loan-list-grid" style="width: 100%; height: 350px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="new-loan-dlg" style="display: none;font-size: 12px; height: auto; width: auto;" title="Create New Loan">
    <form id="new-loan-form">
        <div class="w2ui-page page-0">
            <div class="w2ui-field">
                <label style="text-align: left">Loan Type: </label>
                <div>
                    <select name="sel-payconfig" id="sel-payconfig">
                        <option value="">-Select Loan Type-</option>
                    </select>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="text-align: left; ">Amount:</label>
                <div>
                    <input id="amount" name="amount" type="text" maxlength="8" style="width: 70px; height: 15px;" required/>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="text-align: left; ">Term:</label>
                <div>
                    <input id="term" name="term" type="text" maxlength="3" style="width: 70px; height: 15px;" required/>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="text-align: left; ">Amortization:</label>
                <div>
                    <input id="amortization" name="amortization" type="text" maxlength="8" style="width: 70px; height: 15px;" required/>
                </div>
            </div>
            <div class="w2ui-field" hidden>
                <label style="text-align: left; ">Employee ID:</label>
                <div>
                    <input id="employeeid" name="employeeid" type="text" maxlength="8" style="width: 70px; height: 15px;" hidden/>
                </div>
            </div>
            <div class="w2ui-buttons">
                <button id="btn-save" type="button" name="btn-save" style="font-size: 15px;">Save</button>
                <button id="btn-reset" type="button" name="btn-reset" style="font-size: 15px;">Reset</button>
            </div>
        </div>
    </form>
</div>

<div id="loan-adjust-dlg" style="display: none;font-size: 12px; height: auto; width: auto;" title="Loan Adjustment">
    <form id="new-loan-adjust-form">
        <div class="w2ui-page page-0">
            <table>
                <tr class="empadjustdetails">
                    <td>
                        <img src="img-employee2" id="img-employee2" name="" style="width: 128px; height: 128px;">
                    <td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    ID:
                                </td>
                                <td>
                                    <div id="loansadj-employee-id"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Name:
                                </td>
                                <td>
                                    <div id="loansadj-employee-name"></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr><br>

            </table>
            <table>
                <tr>
                    <td>
                        Balance: <div id="loansadj-balance"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="button" id="add-memo-btn" value="Add DR/CR Memo">
                    </td>
                     <td>
                        <input type="button" id="change-amort-btn" value="Change Amortization">
                    </td>
                </tr>
            </table>
            <div id="loan-adjustment-grid" style="width: 100%; height: 225px;">
            </div>
        </div>
    </form>
</div>

<!-- popup for dr/cr memo -->
<div id="drcrmemoloans-popup">
    <form id="drcrmemoloans-form">
        <table id="drcrmemoloans-table">
            <tr>
                <input type="hidden" id="memo-acctno" name="memo-acctno">
            </tr>
            <tr>
                <td><label for="memo-type">Type: </label></td>
                <td><select id="memo-type" name="memo-type">
                        <option value="">-SELECT TYPE-</option>
                        <option value="038003">Debit</option>
                        <option value="038004">Credit</option>
                    </select></td>
            </tr>
            <tr>
                <td><label for="memo-date">Date: </label></td>
                <td><input type="text" class='datepicker' id="memo-date" name="memo-date"></td>
            </tr>
            <tr>
                <td><label for="memo-amount">Amount: </label></td>
                <td><input type='text' id="memo-amount" name="memo-amount"></td>
            </tr>
            <tr>
                <td><input type="button" id="memoloans-save-btn" value="Save"></td>
            </tr>
        </table>
    </form>
</div>

<div id="dlg-change-amort" title="Change Amortizaton" style="display:none">
   New Amortization: <input type="text" id="txt-new-amort" value=""/>
</div>

</body>
</html>
<script type="text/javascript" src="script.loans.js"></script>