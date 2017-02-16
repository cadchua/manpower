<?php
namespace Modules;

require_once "../config/conf.db.php";
require_once "../library/lib.general.php";
require_once "../classes/class.dbquery.php";

function getCategories(){
	global $conf;
	$db = new \dbQuery();
	$db->connect($conf);
	$category_stmt = "SELECT *, category_id as recid FROM module_category ORDER BY sequence";
	$db->sqlStatement = $category_stmt;
	$db->querySQL(null);
	
	$categories = array();
	
	while($category = $db->stmt->fetch()){
		$categories[] = $category;
	}
	
	return $categories;
}

function getModules($workgroup = null, $category = null){
	global $conf;
	
	$db = new \dbQuery();
	$db->connect($conf);
	
	$module_stmt = "SELECT *, mm.module_id as recid, mc.category_name
					FROM module_category mc 
					JOIN module_menu mm ON mc.category_id = mm.menu_category 
					 ";
	
	if(!empty($workgroup) || !empty($category)){
		$criteria_clause = " WHERE ";
		if(!empty($workgroup)){
			$workgroup_clause = " wm.workgroup = '$workgroup' ";
			$module_stmt .= " JOIN workgroup_module wm ON wm.module_id = mm.module_id ";
			$criteria_clause .= $workgroup_clause;
		}
		
		if(!empty($category)){
			$category_clause = " mm.menu_category = '$category' ";
			$criteria_clause .= $category_clause;
		}
		
		$module_stmt .= $criteria_clause;
	}
	
	$db->sqlStatement = $module_stmt;
	$db->querySQL(null);
	
	$modules = array();
	
	while($module = $db->stmt->fetch()){
		$modules[] = $module;
	}

	$db->closeConnection();
	return $modules;
}

function getAssignments($workgroup = null){
	global $conf;

	$db = new \dbQuery();
	$db->connect($conf);

	$module_stmt = "SELECT *, mm.module_id as recid, mc.category_name, IF(wm.module_id IS NOT NULL, 1, 0) as assigned
					FROM module_category mc
					JOIN module_menu mm ON mc.category_id = mm.menu_category ";

	if(!empty($workgroup)){
		$module_stmt .= " LEFT JOIN workgroup_module wm ON wm.module_id = mm.module_id AND wm.workgroup = ? ";
	}

	$db->sqlStatement = $module_stmt;
	$db->querySQL(array($workgroup));
	$modules = array();

	while($module = $db->stmt->fetch()){
		$module['assigned'] = intval($module['assigned']);
		$modules[] = $module;
	}

	$db->closeConnection();
	return $modules;
}


function addModule($module_name, $category_id, $filename, $sequence){
	global $conf;
	
	$db = new \dbQuery();
	$db->connect($conf);
	$output = array();
	$module_id = idnumNoYear(2, 'module_menu', 'module_id', $db, 3);
	$module_stmt = "INSERT INTO module_menu (module_id, menu_name, menu_category, file_location, sequence)
					VALUES (?, ?, ?, ?, ?)";
	$db->sqlStatement = $module_stmt;

	$values = array(
		$module_id,
		$module_name,
		$category_id,
		$filename,
		$sequence,
	);
	
	try{
		$db->beginTransaction();
		$db->execSQL($values);
		$db->commitTransaction();
	
		$output['status'] = 'success';
		$output['module_id'] = $module_id;
		$db->closeConnection();
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
		$db->closeConnection();
	}
	
	return $output;
}

function saveModule($module_id, $changes){
	global $conf;
	
	$db = new \dbQuery();
	$db->connect($conf);
	$output = array();
	
	$fields = array();
	$values = array();
	
	foreach($changes as $key => $change){
		$fields[] = " $key = ? ";
		$values[] = $change;
	}
	
	$field_str = join($fields, ',');
	$field_str = rtrim($field_str, ',');
	
	$module_stmt = "UPDATE module_menu SET $field_str WHERE module_id = ? ";
	$db->sqlStatement = $module_stmt;

	$values[] = $module_id;
	try{
		$db->beginTransaction();
		$db->execSQL($values);
		$db->commitTransaction();
	
		$output['status'] = 'success';
		$db->closeConnection();
		
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
		$db->closeConnection();
		
	}
	
	return $output;
	
}

function assignModule($workgroup_id, $module_id, $assigned){
	global $conf;
	
	$db = new \dbQuery();
	$db->connect($conf);
	$output = array();
	$assigned = filter_var($assigned, FILTER_VALIDATE_BOOLEAN);
	if($assigned){
		$module_stmt = "INSERT INTO workgroup_module (module_id, workgroup)
						VALUES (?, ?) ";
	} else {
		$module_stmt = "DELETE FROM workgroup_module WHERE module_id = ? AND workgroup = ? ";
	}
	
	$db->sqlStatement = $module_stmt;
	$values = array(
		$module_id,
		$workgroup_id,
	);
	
	
	try {
		$db->beginTransaction();
		$db->execSQL($values);
		$db->commitTransaction();
		
		$output['status'] = 'success';
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
	}
	
	return $output;
}

function deleteModules($module_ids){
	global $conf;
	
	$db = new \dbQuery();
	$db->connect($conf);
	$output = array();
	
	$id_str = join($module_ids, ',');
	$del_stmt = "DELETE FROM module_menu WHERE module_id IN ($id_str) ";
	
	$db->sqlStatement = $del_stmt;
	
	try{
		$db->beginTransaction();
		$db->execSQL(null);
		$db->commitTransaction();
	
		$output['status'] = 'success';
		$db->closeConnection();
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
		$db->closeConnection();
	}
	
	return $output;
}

function deleteCategories($category_ids){
	global $conf;

	$db = new \dbQuery();
	$db->connect($conf);
	$output = array();

	$id_str = join($category_ids, ',');
	$del_stmt = "DELETE FROM module_category WHERE category_id IN ($id_str) ";

	$db->sqlStatement = $del_stmt;

	try{
		$db->beginTransaction();
		$db->execSQL(null);
		$db->commitTransaction();

		$output['status'] = 'success';
		$db->closeConnection();
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
		$db->closeConnection();
	} 

	return $output;
}

function addCategory($category_name, $sequence){
	global $conf;
	
	$db = new \dbQuery();
	$db->connect($conf);
	$output = array();
	$category_id = idnumNoYear(1, 'module_category', 'category_id', $db, 3);
	$category_stmt = "INSERT INTO module_category (category_id, category_name, sequence) VALUES (?, ?, ?) ";
	$db->sqlStatement = $category_stmt;
	$values = array($category_id, $category_name, $sequence);
	
	try{
		$db->beginTransaction();
		$db->execSQL($values);
		$db->commitTransaction();
		
		$output['status'] = 'success';
		$output['category_id'] = $category_id;
		$db->closeConnection();
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
		$db->closeConnection();
	} 
	
	return $output;	
}

function saveCategory($category_id, $category_name, $sequence){
	global $conf;
	
	$db = new \dbQuery();
	$db->connect($conf);

	$output = array();
	
	$category_stmt = "UPDATE module_category SET category_name = ? , sequence = ? WHERE category_id = ?";
	$values = array(
		$category_name,
		$sequence,
		$category_id,
	);
	
	$db->sqlStatement = $category_stmt;
	
	try {
		$db->beginTransaction();
		$db->execSQL($values);
		$db->commitTransaction();
		$output['status'] = 'success';
		$db->closeConnection();
	} catch (\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
		$db->closeConnection();
		return $output;
	} 

	return $output;
}

function getLink($module_id){
	global $conf;
	
	$db = new \dbQuery();
	$db->connect($conf);
	
	$output = array();
	
	$link_stmt = "SELECT file_location FROM module_menu WHERE module_id = '$module_id' ";
	$db->sqlStatement = $link_stmt;
	$db->querySQL(null);
	
	$result = $db->stmt->fetch();
	
	return $result['file_location'];
}

function getWorkgroups(){
	global $conf;
	
	$db = new \dbQuery();
	$db->connect($conf);
	
	$workgroup_stmt = "SELECT config_id, config_name as workgroup_name, config_id as recid 
						from system_configs 
						WHERE group_id = 022";
	$db->sqlStatement = $workgroup_stmt;
	$db->querySQL(null);
	
	$workgroups = $db->stmt->fetchAll();
	
	return $workgroups;
}
