<?php
error_reporting(E_ERROR);
require_once "../config/conf.db.php";
require_once "../classes/class.router.php";
require_once '../library/lib.payroll.php';

function handleRequest(){
	$output = array();
	$userid = $_SESSION['userid'];
	$workgroup = $_SESSION['workgroup'];
	$command = $_REQUEST['cmd'];
	$output['cmd'] = $command;
	$router = new Router();
	
	$router->post('get-records', function(){
		$output = array();
		$criteria = $_POST['criteria'];
		$output['records'] = \Payroll\getConfigEntries($criteria);
		$output['total'] = count($output['records']);
		
		return $output;
	});
	
	$router->get('get-config-list', function(){
		$output = array();
		$criteria = $_GET['criteria'];
		$config_entries = \Payroll\getConfigEntries($criteria);
		
		$output['configs'] = array_map(function($config){
			$config = array(
				'payconfig_id' => $config['payconfig_id'],
				'pay_name' => $config['pay_name'],		
			);
			
			return $config;
		}, $config_entries);
		
		return $output;
	});
	
	$router->post('save-record', function(){
		$output = array();
		
		$payroll_entry = $_POST['record'];
		$type = $_POST['type'];
		$payconfig_id = $_POST['payconfig_id'];
		switch($type){
			case 'create':
				$output = \Payroll\createConfigEntry($payroll_entry);
				break;
			case 'save':
				$output = \Payroll\saveConfigEntry($payconfig_id, $payroll_entry);
				break;
		}
		
		return $output;
	});
	
	$router->post('get-record', function(){
		$output = array();
		
		$payconfig_id = $_POST['payconfig_id'];
		
		$result = \Payroll\getConfigEntry($payconfig_id);
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
