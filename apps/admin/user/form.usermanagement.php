<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 11/5/2015
 * Time: 3:20 PM
 */
include("../../../header/h.development.php");
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
<div id="layout-container" style="100%;">
    <div id="layout" style="width: 100%; height: ">
        <div id="usergrid" style="width: 100%; height: 600px;"></div>
    </div>
</div>
<div id="add-user-dlg" title="Add User" style="background-color: #f5f5f5;">
    <?php include("form.usermanagement.adduser.php"); ?>
</div>
<div id="edit-user-dlg" title="Edit User" style="background-color: #f5f5f5;">
    <?php include("form.usermanagement.edituser.php"); ?>
</div>
</body>
</html>
<script type="text/javascript" src="script.usermanagement.js"></script>