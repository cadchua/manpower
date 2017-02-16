<?php
use \CashAdvance;
error_reporting ( E_ERROR );
require_once "../config/conf.db.php";
require_once "../classes/class.router.php";
require_once '../library/lib.cashadvance.php';
function handleRequest() {
	$output = array ();
	$userid = $_SESSION ['userid'];
	$workgroup = $_SESSION ['workgroup'];
	$command = $_REQUEST ['cmd'];
	$output ['cmd'] = $command;
	$router = new Router ();
	
	$router->post('get-records', function(){
		$employee_id = $_POST['employee_id'];
		$type = $_POST['type'];
		$from = $_POST['from'];
		$to = $_POST['to'];
		$cashadvance_type = $_POST['cashadvance_type'];
		
		if($type == 'accounts'){
			$result = \CashAdvance\getAccounts($employee_id, $cashadvance_type, $from, $to);
			$result['records'] = $result['accounts'];
			unset($result['accounts']);
		}
		
		$result['status'] = 'success';
		return $result;
	});
	
	$router->post('get-balance', function() use ($userid){
		$output = array();
		$employee_id = $_POST['employee_id'];
		$payconfig_id = $_POST['cashadvance_type'];
		try {
			$output['balance'] = \CashAdvance\getCashAdvance(array(
					'employee_id' => $employee_id,
					'payconfig_id' => $payconfig_id,
			))['balance'];
			$output['status'] = 'success';
		} catch(\PDOException $e){
			$output['status'] = 'error';
			$output['message'] = $e->getMessage();
		}
		
		return $output;
	});
	
	$router->get ( 'search-employees-cashadvances', function () use ($userid){
		$output = array();
		$employee_name = $_GET['employeeName'];
		$branch = $_GET['branch'];
		$cashadvance_type = $_GET['cashAdvanceType'];
		$date = $_GET['date'];

		try {
			$output['accounts'] = \CashAdvance\searchEmployeesCashAdvances($employee_name, $branch, $cashadvance_type, $date);
			$output['total'] = count($output['accounts']);
			$output['status'] = 'success';
		} catch(\PDOException $e){
			$output['status'] = 'error';
			$output['message'] = $e->getMessage();
		}
		return $output;
	});
	
	$router->post('add-memo', function() use ($userid) {
		$output = array();
		$cashadvance_account = $_POST['cashadvance_account'];
		$transaction = $_POST['transaction'];

		try{
			$result = \CashAdvance\addMemo($userid, $cashadvance_account, $transaction);
			$output['status'] = 'success';
		} catch(\PDOException $e){
			$output['status'] = 'error';
			$output['message'] = $e->getMessage();
			$output['trace'] = $e->getTrace();
		}
		
		return $output;
	});
	
	$router->post ( 'create-cashadvance', function () use ($userid) {
		$output = array ();
		list ( $cashadvance_account, $payconfig_id, $employee_id, $hold ) = array (
				$_POST ['cashadvance_account'],
				$_POST ['payconfig_id'],
				$_POST ['employee_id'],
				$_POST ['hold'] 
		);
		$result = \CashAdvance\createCashAdvance ( $cashadvance_account, $payconfig_id, $employee_id, $hold, $userid );
		
		return $result;
	} );
	
	$router->get('get-cashadvance', function(){
		$criteria = $_GET['criteria'];
		$result = \CashAdvance\getCashAdvance($criteria);
		
		return $result;		
	});
	
	$router->post ( 'new-transaction', function () use ($userid){
		$transaction = $_POST['transaction'];
		$result = \CashAdvance\addTransaction ($userid, $transaction );
		
		return $result;
	} );
	
	$router->post ('update-hold', function () use ($userid){
		$output = array();
		$cashadvance_account = $_POST['cashadvance_account'];
		$hold = filter_var($_POST['hold'], FILTER_VALIDATE_BOOLEAN);
		$hold = (int) $hold;
		try {
			$result = \CashAdvance\updateHold($cashadvance_account, $hold);
			$output['status'] = 'success';
		} catch(\PDOException $e){
			$output['status'] = 'error';
			$output['message'] = $e->getMessage();
		}
		
		return $output;
	});
	
	$router->post ('save-records', function () use ($userid){
		$output = array();
		$changes = $_POST['changes'];
		$date = $_POST['date'];
		
		$extract_ids = function($recid){
			$id_fields = array(
					'id',
					'payconfig'
					);
			
			return array_combine($id_fields, explode('_', $recid));
		};
		
		$change2transaction = function($change) use ($extract_ids) {
			return array_merge($extract_ids($change['recid']), array(
					'amount' => $change['balance'],
			));
		};
		
		$withBalance = function($transaction){
			return $transaction['amount'] > 0;
		};
		
		try {
			array_map(function($transaction) use ($userid, $date) {
					return \CashAdvance\addTransaction($userid, $transaction, $date);
				}, array_filter(
						array_map($change2transaction, $changes), $withBalance)
			);
			$output['status'] = 'success';
		} catch(\PDOException $e){
			$output['status'] = 'error';
			$output['trace'] = $e->getTrace();
			$output['message'] = $e->getMessage();
		}
		
		return $output;
	});
	
	$result = $router->run ( $_SERVER ['REQUEST_METHOD'], $command );
	
	$output = array_merge ( $output, $result );
	
	echo json_encode ( $output );
}

session_start ();
handleRequest ();
session_write_close ();
