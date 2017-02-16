<?php

namespace Payroll;

require_once "../config/conf.db.php";
require_once "../library/lib.general.php";
require_once "../classes/class.dbquery.php";
const PayrollConfigTable = "payroll_config";
function createConfigEntry($payroll_entry) {
	global $conf;
	
	$db = new \dbQuery ();
	$db->connect ( $conf );
	$payroll_config_table = namespace\PayrollConfigTable;
	
	// make payconfig_id
	$id_prefix = "";
	if(filter_var($payroll_entry['income'], FILTER_VALIDATE_BOOLEAN)){
		$id_prefix .= '11';
	} else {
		$id_prefix .= '22';
	}
	
	$payconfig_id = idnumNoYear($id_prefix, $payroll_config_table, 'payconfig_id', $db, 2);
	
	$fields = array_keys ( $payroll_entry );
	$fields[] = 'payconfig_id';
	$values = array_map ( function ($value) {
		if(in_array($value, array('true', 'false'))){
			$value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
		}
		return $value;
	}, array_values ( $payroll_entry ) );
	
	$values[] = $payconfig_id;
	
	$fields_str = join ( ',', $fields );
	
	$create_stmt = "INSERT INTO $payroll_config_table ($fields_str)
						VALUES (?, ?, ?, ?, ?, ?, ? , ?, ?, ?, ?, ?, ?) ";
	$db->sqlStatement = $create_stmt;
	
	try {
		$db->beginTransaction ();
		$db->execSQL ( $values );
		$db->commitTransaction ();
		
		$output ['status'] = 'success';
		$output['payconfig_id'] = $payconfig_id;
		$db->closeConnection ();
	} catch ( \PDOException $e ) {
		$output ['status'] = 'error';
		$output ['message'] = $e->getMessage ();
		$db->closeConnection ();
	}
	
	return $output;
}

function saveConfigEntry($payconfig_id, $payconfig_entry) {
	global $conf;

	$db = new \dbQuery ();
	$db->connect ( $conf );

	$fields = array();
	$values = array();

	foreach($payconfig_entry as $key => $value){
		$fields[] = "$key = ?";
		$values[] = $value;
	}

	$fields_str = join($fields, ',');

	$payconfig_table = namespace\PayrollConfigTable;

	$update_stmt = "UPDATE $payconfig_table SET $fields_str
	WHERE payconfig_id = '$payconfig_id' ";
	$db->sqlStatement = $update_stmt;

	try {
	$db->beginTransaction ();
	$db->execSQL ( $values );
	$db->commitTransaction ();

	$output ['status'] = 'success';
	$db->closeConnection ();
	} catch ( \PDOException $e ) {
	$output ['status'] = 'error';
	$output ['message'] = $e->getMessage ();
	$db->closeConnection ();
	}

	return $output;
}

function getConfigEntries($criteria = null){
	global $conf;

	$checkbox_fields = array(
			'income',
			'taxable',
			'tax_deductible',
			'allowance',
			'loan',
			'cashadvance',
	);
	
	$db = new \dbQuery();
	$db->connect($conf);
	$payroll_config_table = namespace\PayrollConfigTable;
	$entry_stmt = "SELECT *, payconfig_id as recid , gov_req_codes.config_name as government_req_name, pay_category_codes.config_name as pay_category_name
					FROM $payroll_config_table
					LEFT JOIN system_configs gov_req_codes ON {$payroll_config_table}.government_req = gov_req_codes.config_id
					LEFT JOIN system_configs pay_category_codes ON {$payroll_config_table}.pay_category = pay_category_codes.config_id ";
	
	if($criteria){
		$entry_stmt .= " WHERE ";
		
		foreach($criteria as $key => $value){
			$curr_criteria = " $key = '$value',";
			$entry_stmt .= $curr_criteria;
		}
		$entry_stmt = rtrim($entry_stmt, ',');
	}
	$entries = array();

	$db->sqlStatement = $entry_stmt;
	$db->querySQL(null);

	while($entry = $db->stmt->fetch(\PDO::FETCH_ASSOC)){
		array_walk($entry, function(&$field, $key) use ($checkbox_fields){
			if(in_array($key, $checkbox_fields)){
				$field = filter_var($field, FILTER_VALIDATE_BOOLEAN);
			}
		});
		
		$entries[] = $entry;
	}

	$db->closeConnection();
	return $entries;
}

function getConfigEntry($payconfig_id){
	global $conf;

	$output = array();
	$db = new \dbQuery();
	$db->connect($conf);
	$payconfig_table = namespace\PayrollConfigTable;
	$entry_stmt = "SELECT *, $payconfig_id as recid
						 FROM $payconfig_table 
					WHERE payconfig_id = '$payconfig_id' ";
	$entries = array();

	$db->sqlStatement = $entry_stmt;

	try{
		$db->querySQL(null);

		$entry = $db->stmt->fetch(\PDO::FETCH_ASSOC);

		$db->closeConnection();
		$checkbox_fields = array(
			'income',
			'taxable',
			'tax_deductible',
			'allowance',
			'loan',
			'cashadvance',
		);
		array_walk($entry, function(&$field, $key) use ($checkbox_fields){
			if(in_array($key, $checkbox_fields)){
				$field = intval($field);
			}
		});
		$output['entry'] = $entry;
		$output['status'] = 'success';
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
	}
	return $output;
}