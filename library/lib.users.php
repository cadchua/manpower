<?php
namespace Users;

require_once "../config/conf.db.php";
require_once "../library/lib.general.php";
require_once "../classes/class.dbquery.php";

function checkLogin($username, $password){
	global $conf;
	$output = array();
	// get password digest
	$md5_password = md5($password);
	
	$db = new \dbQuery();
	$db->connect($conf);
	$user_stmt = "SELECT COUNT(*) as user_exists FROM user_info WHERE username = ? AND userpass = ? ";
	$db->sqlStatement = $user_stmt;
	$values = array(
		$username, 
		$md5_password,
	);
	try {
	$db->querySQL($values);
	
	$result = $db->stmt->fetch();
	
	$output['user_exists'] = $result['user_exists'] > 0;
	$output['status'] = 'success';
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
	}
	return $output;
}

function login($username, $password){
	global $conf;
	$output = array();
	$db = new \dbQuery();
	$db->connect($conf);
	$md5_password = md5($password);
	$user_stmt = "SELECT user_id, workgroup FROM user_info WHERE username = ? AND userpass = ? ";
	$db->sqlStatement = $user_stmt;
	
	$values = array(
		$username,
		$md5_password,
	);

	try{
		$db->querySQL($values);
		$result = $db->stmt->fetch();
		$output['user_id'] = $result['user_id'];
		$_SESSION['userid'] = $output['user_id'];
		$_SESSION['workgroup'] = $result['workgroup'];
		$output['status'] = 'success';
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
	}
	
	return $output;
}