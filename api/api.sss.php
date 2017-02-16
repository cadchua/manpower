<?php
require_once "../config/conf.db.php";
require_once "../classes/class.router.php";
require_once '../library/lib.sss.php';

function handleRequest(){
	$output = array();
	$userid = $_SESSION['userid'];
	$workgroup = $_SESSION['workgroup'];
	$command = $_POST['cmd'];
	$output['cmd'] = $command;
	$router = new Router();
	
	$router->post('get-records', function(){
		$output = array();
		
		$output['records'] = \SSS\getEntries();
		$output['total'] = count($output['records']);
		
		return $output;
	});
	
	$router->post('save-record', function(){
		$output = array();
		
		$sss_entry = $_POST['record'];
		$type = $_POST['type'];

		switch($type){
			case 'create':
				$output = \SSS\createEntry($sss_entry);
				break;
			case 'save':
				$output = \SSS\saveEntry($sss_entry);
				break;
		}
		
		return $output;
	});
	
	$router->post('get-record', function(){
		$output = array();
		
		$bracket_no = $_POST['bracket_no'];
		
		$result = \SSS\getEntry($bracket_no);
		$output = array_merge($output, $result);
		
		return $output;
	});
	
	$router->post('get-bracket', function(){
		$output = array();
		
		$amount = $_POST['amount'];
		
		$bracket = \SSS\getBracket($amount);
		$output['bracket'] = $bracket;
		return $output;
	});
	
	$result = $router->run($_SERVER['REQUEST_METHOD'], $command);

	$output = array_merge($output, $result);
	
	echo json_encode($output);
}

session_start();
handleRequest();
session_write_close();
