<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 11/6/2015
 * Time: 9:54 PM
 */
?>
<div id="adduser-dialog">
    <form id="adduser-form" style="font-size: 0.8em;" method="post" action="">
        <div class="w2ui-page page-0">
            <div class="w2ui-field">
                <label style="text-align: left; ">Full Name:</label>
                <div>
                    <input id="fullname" name="fullname" type="text" maxlength="100" style="width: 250px;  height: 15px;" required/>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="text-align: left; ">Username:</label>
                <div>
                    <input id="username" name="username" type="text" maxlength="100" style="width: 250px; height: 15px;" required/>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="text-align: left; ">Password:</label>
                <div>
                    <input id="password" name="password" type="password" maxlength="100" style="width: 250px; height: 15px;" required/>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="text-align: left; font-size: 12px;">Retype Password:</label>
                <div>
                    <input id="password2" name="password2" type="password" maxlength="100" style="width: 250px; height: 15px;" required/>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="width: 145px; text-align: left">Workgroup: </label>
                <div>
                    <select name="sel-workgroup" id="sel-workgroup">
                        <option value="">-Select Workgroup-</option>
                    </select>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="width: 145px; text-align: left">Status</label>
                <div>
                    <select name="sel-status" id="sel-status" required>
                        <option value="">-Select Status-</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div><br>

            <div class="w2ui-buttons">
                <button id="btn-reset" type="button" name="btn-reset" style="font-size: 15px;">Reset</button>
                <button id="btn-save" type="button" name="btn-save" style="font-size: 15px;">Save</button>
            </div>

        </div>
    </form>
</div>
