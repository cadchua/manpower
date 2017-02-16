<?php

namespace Employees;
error_reporting(E_ERROR);
require_once "../config/conf.db.php";
require_once "../library/lib.general.php";
require_once "../classes/class.dbquery.php";
const EMPLOYEE_TBL = 'hs_hr_employee';
function getInfo($employee_id) {
	global $conf, $orange;
	$output = array ();
	$employee_tbl = $orange ['db'] . '.' . namespace\EMPLOYEE_TBL;
	$db = new \dbQuery ();
	$db->connect ( $conf );
	$info_stmt = "SELECT * FROM $employee_tbl WHERE employee_id = '$employee_id' ";
	try {
		$db->sqlStatement = $info_stmt;
		$values = array (
				$employee_id 
		);
		
		$db->querySQL ( '');
		$info = $db->stmt->fetch (\PDO::FETCH_ASSOC);
		$db->closeConnection ();
		$output ['info'] = $info;
		$output ['status'] = 'success';
	} catch ( \PDOException $e ) {
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
	}
	
	return $output;
}