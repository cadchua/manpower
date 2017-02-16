<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 11/23/2015
 * Time: 11:38 PM
 */
?>

<div id="edituser-dialog">
    <form id="edituser-form" style="font-size: 0.8em;" method="post" action="">
        <div class="w2ui-page page-0">
            <div class="w2ui-field">
                <label style="text-align: left; ">User ID:</label>
                <div>
                    <input id="userid" name="userid" type="text" maxlength="100" style="width: 250px;  height: 15px;" readonly/>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="text-align: left; ">Full Name:</label>
                <div>
                    <input id="edit-fullname" name="edit-fullname" type="text" maxlength="100" style="width: 250px;  height: 15px;" required/>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="text-align: left; ">Username:</label>
                <div>
                    <input id="edit-username" name="edit-username" type="text" maxlength="100" style="width: 250px; height: 15px;" required readonly/>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="text-align: left; ">Password:</label>
                <div>
                    <input id="edit-password" name="edit-password" type="password" maxlength="100" style="width: 250px; height: 15px;" required/>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="text-align: left; font-size: 12px;">Retype Password:</label>
                <div>
                    <input id="edit-password2" name="edit-password2" type="password" maxlength="100" style="width: 250px; height: 15px;" required/>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="width: 145px; text-align: left">Workgroup: </label>
                <div>
                    <select name="sel-workgroup-mod" id="sel-workgroup-mod">
                        <option value="">-Select Workgroup-</option>
                    </select>
                </div>
            </div>
            <div class="w2ui-field">
                <label style="width: 145px; text-align: left">Status</label>
                <div>
                    <select name="sel-status-mod" id="sel-status-mod" required>
                        <option value="">-Select Status-</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div><br>

            <div class="w2ui-buttons">
                <button id="btn-update-mod" type="button" name="btn-update-mod" style="font-size: 15px;">Update</button>
            </div>

        </div>
    </form>
</div>