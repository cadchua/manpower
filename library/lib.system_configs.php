<?php

namespace SystemConfigs;

require_once "../config/conf.db.php";
require_once "../library/lib.general.php";
require_once "../classes/class.dbquery.php";

const SystemConfigsTable = "system_configs";

function getGroupCodes($group_id){
	global $conf;
	$output = array();
	$db = new \dbQuery();
	$db->connect($conf);
	
	$config_table = namespace\SystemConfigsTable;
	$group_stmt = "SELECT * FROM $config_table WHERE group_id = ? ";
	
	$db->sqlStatement = $group_stmt;

	try {
		$db->querySQL(array($group_id));
		$output['status'] = 'success';
		$codes = array();
		
		while($code = $db->stmt->fetch(\PDO::FETCH_ASSOC)){
			$codes[] = $code;
		}
		$output['codes'] =  $codes;
		
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
	}
	
	return $output;
}