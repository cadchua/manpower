<?php

namespace SSS;

require_once "../config/conf.db.php";
require_once "../library/lib.general.php";
require_once "../classes/class.dbquery.php";
const SSSTable = "sss";
function createEntry($sss_entry) {
	global $conf;
	
	$db = new \dbQuery ();
	$db->connect ( $conf );
	
	$fields = array_keys ( $sss_entry );
	$values = array_values ( $sss_entry );
	
	$fields_str = join ( ',', $fields );
	
	$sss_table = namespace\SSSTable;
	
	$create_stmt = "INSERT INTO $sss_table ($fields_str)
						VALUES (?, ?, ?, ?, ?, ?) ";
	
	$db->sqlStatement = $create_stmt;
	
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

function getBracket($amount){
	global $conf;
	
	$db = new \dbQuery();
	$db->connect($conf);
	
	$sss_table = namespace\SSSTable;
	$bracket_stmt = "SELECT *, bracket_no as recid 
						FROM `$sss_table` 
						WHERE salary <= ? 
						ORDER BY salary desc 
						LIMIT 1";
	$values = array($amount);
	
	$db->sqlStatement = $bracket_stmt;
	$db->querySQL($values);
	
	$bracket = $db->stmt->fetch(\PDO::FETCH_ASSOC);
	
	return $bracket;
}

function getEntries(){
	global $conf;
	
	$db = new \dbQuery();
	$db->connect($conf);
	
	$entry_stmt = "SELECT *, bracket_no as recid FROM sss ";
	$entries = array();
	
	$db->sqlStatement = $entry_stmt;
	$db->querySQL(null);
	
	while($entry = $db->stmt->fetch()){
		$entries[] = $entry;		
	}

	$db->closeConnection();
	return $entries;
}

function saveEntry($sss_entry) {
	global $conf;
	
	$db = new \dbQuery ();
	$db->connect ( $conf );

	$fields = array();
	$values = array();
	
	foreach($sss_entry as $key => $value){
		$fields[] = "$key = ?";
		$values[] = $value;
	}
	
	$fields_str = join($fields, ',');
	
	$sss_table = namespace\SSSTable;
	
	$update_stmt = "UPDATE $sss_table SET $fields_str
						WHERE bracket_no = '{$sss_entry['bracket_no']}' ";
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

function getEntry($bracket_no){
	global $conf;

	$output = array();
	$db = new \dbQuery();
	$db->connect($conf);

	$entry_stmt = "SELECT *, bracket_no as recid FROM sss WHERE bracket_no = '$bracket_no' ";
	$entries = array();

	$db->sqlStatement = $entry_stmt;
	
	try{
		$db->querySQL(null);
	
		$entry = $db->stmt->fetch(\PDO::FETCH_ASSOC);
	
		$db->closeConnection();
		
		$output['status'] = 'success';
		$output['entry'] = $entry;
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
	}
	return $output;
}