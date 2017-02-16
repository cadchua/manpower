<?php
require_once "../config/conf.db.php";
require_once "../classes/class.router.php";
require_once '../library/lib.system_configs.php';

function handleRequest(){
	$output = array();
	$userid = $_SESSION['userid'];
	$workgroup = $_SESSION['workgroup'];
	$command = $_REQUEST['cmd'];
	$output['cmd'] = $command;
	$router = new Router();
	
	$router->get('get-group-codes', function(){
		$output = array();
		$group_id = $_GET['group_id'];
		$result = \SystemConfigs\getGroupCodes($group_id);

		$output = array_merge($output, $result);
		
		return $output;
	});
	
	$result = $router->run($_SERVER['REQUEST_METHOD'], $command);
	
	$output = array_merge($output, $result);
	
	echo json_encode($output);
}

session_start();
handleRequest();
session_write_close();
