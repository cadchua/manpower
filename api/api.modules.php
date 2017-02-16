<?php
require_once "../config/conf.db.php";
require_once "../classes/class.router.php";
require_once '../library/lib.modules.php';

function handleRequest(){
	$output = array();
	$userid = $_SESSION['userid'];
	$workgroup = $_SESSION['workgroup'];
	$command = $_POST['cmd'];
	$output['cmd'] = $command;
	$router = new Router();
	
	$router->post('get-records', function() use ($workgroup){
		$type = $_POST['type'];
		switch($type){
			case 'modules':
				$category_id = $_POST['category_id'];
				$output['records'] = \Modules\getModules(null, $category_id);
				break;
			case 'categories':
				$output['records'] = \Modules\getCategories();
				break;
			case 'workgroups':
				$output['records'] = \Modules\getWorkgroups();
				break;
			case 'modules-assignment':
				$workgroup = $_POST['workgroup_id'];
				try{
				$output['records'] = \Modules\getAssignments($workgroup);
				} catch(PDOException $e){
					$output['records'] = array();
					$output['stmt'] = $e->getMessage();
				}
				break;
		}
		$output['total'] = count($output['records']);
		
		return $output;
	});
	
	// category routes
	
	$router->post('add-category', function(){
		$output = array();
		$category_name = $_POST['category_name'];
		$sequence = $_POST['sequence'];
		$result = \Modules\addCategory($category_name, $sequence);
		
		if($result['status'] == 'success'){
			$output['status'] = 'success';
			$output['category_id'] = $result['category_id'];
		} elseif($result['status'] == 'error') {
			$output['status'] = $result['status'];
		}
		
		return $output;
	});
	
	$router->post('save-category', function(){
		$output = array();
		$category_id = $_POST['category_id'];
		$category_name = $_POST['category_name'];
		$sequence = $_POST['sequence'];
		$result = \Modules\saveCategory($category_id, $category_name, $sequence);
		
		if($result['status'] == 'success'){
			$output['status'] = 'success';
		} elseif($result['status'] == 'error') {
			$output['status'] = $result['status'];
		}
		
		return $output;
	});
	
	// modules routes
	$router->post('add-module', function(){
		$output = array();
		
		$category_id = $_POST['category_id'];
		$module_name = $_POST['module_name'];
		$filename = $_POST['filename'];
		$sequence = $_POST['sequence'];
		
		$result = \Modules\addModule($module_name, $category_id, $filename, $sequence);
		if($result['status'] == 'success'){
			$output['status'] = 'success';
			$output['module_id'] = $result['module_id'];
		} elseif($result['status'] == 'error') {
			$output['status'] = $result['status'];
		}
		
		return $output;
	});
	
	$router->post('delete-records', function(){
		$output = array();
		
		$type = $_POST['type'];
		
		switch($type){
			case 'modules':
				$modules = $_POST['selected'];
				$result = \Modules\deleteModules($modules);
				if($result['status'] == 'success'){
					$output['status'] = 'success';
				} elseif($result['status'] == 'error') {
					$output['status'] = $result['status'];
				}
				break;
			case 'categories':
				$categories = $_POST['selected'];
				$result = \Modules\deleteCategories($categories);
				if($result['status'] == 'success'){
					$output['status'] = 'success';
				} elseif($result['status'] == 'error') {
					$output['status'] = $result['status'];
				}
				break;
		}
		
		return $output;
	});
	
	$router->post('save-records', function(){
		$output = array();
		
		$type = $_POST['type'];
		switch($type){
			case 'modules':
				$modules = $_POST['changes'];
				foreach($modules as $module){
					$module_id = $module['recid'];
					unset($module['recid']);
					$result = \Modules\saveModule($module_id, $module);
					
					if($result['status'] == 'error') {
						$output['status'] = $result['status'];
						$output['message'] = $result['message'];
						return $output;
					}
				}
				break;
			case 'modules-assignment':
				$modules = $_POST['changes'];
				$workgroup_id = $_POST['workgroup_id'];
				foreach($modules as $module){
					$module_id = $module['recid'];
					unset($module['recid']);
					$result = \Modules\assignModule($workgroup_id, $module_id, $module['assigned']);
				}
				
				break;
		}
		
		if($result['status'] == 'success'){
			$output['status'] = 'success';
		}
		
		return $output;
	});
	
	$router->post('get-modules', function(){
		$workgroup = $_POST['workgroup'];
		$modules = \Modules\getModules($workgroup);
		$output = array();
		$output['modules'] = $modules;
		return $output;
	});
	
	$router->post('get-menu', function() use ($workgroup) {
		$modules = \Modules\getModules($workgroup);
		$output = array();
		$output['categories'] = array();
		
		foreach($modules as $module){
			$output['categories'][$module['category_name']][] = $module;			
		}
		
		$output['categories'] = array_reverse($output['categories']);
		return $output;
	});
	
	$router->post('get-categories', function(){
			$output = array();
			$output['categories'] = \Modules\getCategories();
			return $output;
	});
	
	$router->post('get-filename', function(){
		$output = array();
		$module_id = $_POST['module_id'];
		
		try {
			$output['link'] = \Modules\getLink($module_id);
			$output['status'] = 'success';
		} catch(PDOException $e){
			$output['status'] = 'error';
			$output['message'] = $e->getMessage();
		}
		
		return $output;
	});
	
	$router->post('get-workgroups', function(){
		$output = array();
		
		try {
			$output['workgroups'] = \Modules\getWorkgroups();
			$output['status'] = 'success';
		} catch(PDOException $e){
			$output['status'] = 'error';
			$output['message'] = $e->getMessage();
		}
		
		return $output;
	});
	
	$result = $router->run($_SERVER['REQUEST_METHOD'], $command);
	
	$output = array_merge($output, $result);
	
	echo json_encode($output);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	session_start();
	handleRequest();
	session_write_close();
}