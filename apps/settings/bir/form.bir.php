<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 11/16/2015
 * Time: 11:03 PM
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
            <form id="bir-form" style="font-size: 0.8em; font-family: Verdana,Arial,sans-serif;" method="post">
                <fieldset>
                    <legend>BIR Input</legend>
                    <tr>
                        <td>
                            Pay Frequency:
                            <select name="sel-payfrequency" id="sel-payfrequency" required>
                            </select>
                        </td>
                        <td> Tax Code:
                            <select name="sel-taxcode" id="sel-taxcode" required>
                                <option value=""> Select Tax Code </option>
                                <option value="Z">Z</option>
                                <option value="S/ME">S/ME</option>
                                <option value="ME1/S1">ME1/S1</option>
                                <option value="ME2/S2">ME2/S2</option>
                                <option value="ME3/S3">ME3/S3</option>
                                <option value="ME4/S4">ME4/S4</option>
                            </select>
                        </td>
                        <td>
                            Column No:
                            <select name="sel-columno" id="sel-columno" required>
                                <option value=""> Select </option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>

                            </select>
                        </td>
                        <td>
                            Pay Rate: <input id="payrate" name="payrate" type="text" maxlength="8" style="width: 70px; " required/>
                        </td>
                        <td>
                            Tax Amount: <input id="taxamount" name="taxamount" type="text" maxlength="8" style="width: 70px; " required/>
                        </td>
                        <td>
                            Over Tax Percent: <input id="overtaxpercent" name="overtaxpercent" type="text" maxlength="5" style="width: 60px; " required/>
                        </td>
                        &nbsp;
                        <td>
                            <button id="btn-save" type="button" name="btn-save" style="font-size: 15px; width: 60px; height: 30px;">Save</button>
                            <button id="btn-reset" type="button" name="btn-reset" style="font-size: 15px; width: 60px; height: 30px;">Reset</button>
                        </td>
                    </tr>
                </fieldset>
            </form>
        </div>
        <div>
            <form id="bir-form-mod" style="font-size: 0.8em; font-family: Verdana,Arial,sans-serif;" method="post">
                <fieldset>
                    <legend>BIR Input</legend>
                    <tr>
                        <td>
                            Pay Frequency:
                            <select name="sel-payfrequency-mod" id="sel-payfrequency-mod" required>
                            </select>
                        </td>
                        <td> Tax Code:
                            <select name="sel-taxcode-mod" id="sel-taxcode-mod" required>
                                <option value=""> Select Tax Code </option>
                                <option value="Z">Z</option>
                                <option value="S/ME">S/ME</option>
                                <option value="ME1/S1">ME1/S1</option>
                                <option value="ME2/S2">ME2/S2</option>
                                <option value="ME3/S3">ME3/S3</option>
                                <option value="ME4/S4">ME4/S4</option>
                            </select>
                        </td>
                        <td>
                            Column No:
                            <select name="sel-columno-mod" id="sel-columno-mod" required>
                                <option value=""> Select </option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>

                            </select>
                        </td>
                        <td>
                            Pay Rate: <input id="payrate-mod" name="payrate-mod" type="text" maxlength="8" style="width: 70px; " required/>
                        </td>
                        <td>
                            Tax Amount: <input id="taxamount-mod" name="taxamount-mod" type="text" maxlength="8" style="width: 70px; " required/>
                        </td>
                        <td>
                            Over Tax Percent: <input id="overtaxpercent-mod" name="overtaxpercent-mod" type="text" maxlength="5" style="width: 60px; " required/>
                        </td>
                        <td>
                            <input type="text" id="row-id-mod" name="row-id-mod" maxlength="120" style="width: 60px;" hidden="hidden"/>
                        </td>
                        &nbsp;
                        <td>
                            <button id="btn-update-mod" type="button" name="btn-update-mod" style="font-size: 15px; width: 80px; height: 35px;">Update</button>
                        </td>
                    </tr>
                </fieldset>
            </form>
        </div>
        <br>
        <div id="bir-grid" style="width: 100%; height: 600px;"></div>
    </div>
</div>
</body>
</html>
<script type="text/javascript" src="script.bir.js"></script>