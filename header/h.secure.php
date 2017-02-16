<?php
require_once "../../../config/conf.db.php";
require_once "../../../classes/class.dbquery.php";
session_start();

// check if userid and workgroup exist
function isLoggedIn() {
	return ! empty ( $_SESSION ['userid'] ) && ! empty ( $_SESSION ['workgroup'] );
}


//if(!isLoggedIn()){
//	header("Location: ../../../logout.php");
//}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manpower Management System</title>
  <meta name="viewport" content="initial-scale=1, maximum-scale=1"/>
  <link rel="icon" type="image/png" href="../../../favicon.ico">
  <link type="text/css" rel="stylesheet" href="../../../css/material-icons.css">   
	<link type="text/css" rel="stylesheet" href="../../../css/jquery-ui.min.css"/>
	<link type="text/css" rel="stylesheet" href="../../../css/w2ui-1.4.3.css"/>
<!--	<link type="text/css" rel="stylesheet" href="../../../css/materialize.min.css"  media="screen,projection"/>-->

	<script type="text/javascript" src="../../../js/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery.noty.packaged.min.js"></script>
	<script type="text/javascript" src="../../../js/w2ui-1.4.min.js"></script>
<!--	<script type="text/javascript" src="../../../js/materialize.min.js"></script> -->
	<script type="text/javascript" src="../../../js/script.library.js"></script>
       