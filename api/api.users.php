<?php
error_reporting(E_ERROR);
require_once "../config/conf.db.php";
require_once "../classes/class.router.php";
require_once "../library/lib.users.php";

function handleRequest() {
	$output = array ();
	$userid = $_SESSION ['userid'];
	$workgroup = $_SESSION ['workgroup'];
	$command = $_POST ['cmd'];
	$output ['cmd'] = $command;
	$router = new Router ();
	
	$router->post('check-login', function() {
		$output = array();
		$username = $_POST['username'];
		$password = $_POST['password'];
		$check_result = \Users\checkLogin($username, $password);
		if($check_result['status'] == 'success' && $check_result['user_exists']){
			$result = \Users\login($username, $password);
			if($result['status'] == 'success'){
				$output = array_merge($output, $result);
				$output['logged_in'] = true;
			}
		} else {
			$output['logged_in'] = false;
		}
		
		return $output;
	});
	
	$router->post('logout', function(){
		$output = array();
		$_SESSION['userid'] = null;
		$_SESSION['workgroup'] = null;
		
		$output['status'] = 'success';
		return $output;
	});
	
	$result = $router->run ($_SERVER['REQUEST_METHOD'], $command );
	
	$output = array_merge ( $output, $result );
	
	echo json_encode ( $output );
}

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	session_start ();
	handleRequest ();
	session_write_close ();
}