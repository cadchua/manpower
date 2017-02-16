<?php

namespace CashAdvance;
use \Payroll;

require_once "../config/conf.db.php";
require_once "../library/lib.general.php";
require_once "../library/lib.payroll.php";
require_once "../classes/class.dbquery.php";
const CashAdvanceTable = "cash_advance";
const CATransactionsTable = "ca_transactions";
const CASHADVANCE_TR_CODE = '038001';
const ACCOUNT_NO_PREFIX = '402';

$orangeDB=$orange['db'];

function getAccounts($employee_id, $type, $from = null, $to = null){
	global $conf;

	$output = array();
	$db = new \dbQuery();
	$db->connect($conf);

	$ca_table = namespace\CashAdvanceTable;
	$tr_table = namespace\CATransactionsTable;
	$payroll_table = \Payroll\PayrollConfigTable;

	$account_stmt = "SELECT *
						FROM $ca_table ca
							JOIN $tr_table tr ON tr.cashadvance_account = ca.cashadvance_account
						JOIN
							$payroll_table payconfig_codes ON ca.payconfig_id = payconfig_codes.payconfig_id
						WHERE employee_id = ? AND ca.payconfig_id = ? ";
	$values = array($employee_id, $type);

	if(!empty($from) && !empty($to)){
		$account_stmt .= " AND DATE(tr.tr_date) BETWEEN ? AND ? ";
		$values[] = $from;
		$values[] = $to;
	}
	try {
		$db->sqlStatement = $account_stmt. " ORDER BY tr_date desc limit 100";
		$db->querySQL($values);
		$accounts = array();
		$counter = 0;
		while($account = $db->stmt->fetch(\PDO::FETCH_ASSOC)){
			$account['recid'] = $counter++;
			$accounts[] = $account;
		}

		$db->closeConnection();
		$output['status'] = 'success';
		$output['accounts'] = $accounts;
		$output['total'] = count($accounts);
	} catch(\PDOException $e){
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
	}

	return $output;
}

function searchEmployeesCashAdvances($employee_name, $branch = null, $cashadvance_type = null, $date = null){
	global $conf, $orangeDB;
	$output = array();
	$db = new \dbQuery();
	$db->connect($conf);
	$ca_table = $conf['db'] . '.' . namespace\CashAdvanceTable;
	$employees_table = "$orangeDB.`hs_hr_employee`";
	$loc_table = "$orangeDB.`hs_hr_emp_locations`";
	if($branch != null){
		$branch_clause = " AND loc.location_id = '$branch' ";
	} else {
		$branch_clause = "";
	}
	$ca_stmt = " select *, employees.employee_id as employee_id from $employees_table employees
						 left join $loc_table loc on employees.emp_number = loc.emp_number
						 left join $ca_table ca
							on ca.employee_id = employees.employee_id
								and ca.payconfig_id = ?
					where
						CONCAT(employees.emp_lastname, ',', employees.emp_firstname, ' ', employees.emp_middle_name) LIKE ?
						$branch_clause  ORDER BY emp_lastname,emp_firstname,emp_middle_name
				  ";

	$values = array(
			$cashadvance_type,
			"%$employee_name%",
	);

	$db->sqlStatement = $ca_stmt;
	$db->querySQL($values);
	$accounts = array();
	while($account = $db->stmt->fetch(\PDO::FETCH_ASSOC)){
		$account['recid'] = "{$account['employee_id']}_{$cashadvance_type}";
		$account['name'] = "{$account['emp_lastname']},{$account['emp_firstname']} {$account['emp_middle_name']}";
		$accounts[] = $account;
	}

	return $accounts;
}

function createCashAdvance($payconfig_id, $employee_id, $added_by) {
	global $conf;
	$output = array ();
	$db = new \dbQuery ();
	$db->connect ( $conf );
	$ca_table = namespace\CashAdvanceTable;
	$account_no_prefix = namespace\ACCOUNT_NO_PREFIX;
	// generate account #
	$account_no = idnum ( $account_no_prefix, $ca_table, 'cashadvance_account', $db, 5 );

	$cash_advance_stmt = "INSERT INTO $ca_table (cashadvance_account,
												 payconfig_id,
												 employee_id,
												 added_by,
												 date_added)
												 VALUES (?, ?, ?, ?, NOW()) ";

	$db->sqlStatement = $cash_advance_stmt;
	$values = array (
			$account_no,
			$payconfig_id,
			$employee_id,
			$added_by
	);

	try {
		$db->beginTransaction ();
		$db->execSQL ( $values );
		$db->commitTransaction ();
		$db->closeConnection ();
		$output ['status'] = 'success';
		$output ['cashadvance_account'] = $account_no;
		$output ['balance'] = 0;
	} catch ( \PDOException $e ) {
		$output ['status'] = 'error';
		$output ['message'] = $e->getMessage ();
		$db->closeConnection ();
	}

	return $output;
}
function getCashAdvance($criteria) {
	global $conf;

	$db = new \dbQuery ();
	$db->connect ( $conf );
	$cashadvance_tbl = namespace\CashAdvanceTable;
	$fields = array_keys ( $criteria );
	$values = array_values ( $criteria );
	$fields_str = join ( " = ? AND ", $fields );
	$fields_str = rtrim ( $fields_str, 'AND ' ) . ' = ? ';
	$cash_stmt = "SELECT * FROM $cashadvance_tbl WHERE $fields_str ";
	$db->sqlStatement = $cash_stmt;
	$db->querySQL ( $values );

	$cashadvance = $db->stmt->fetch ( \PDO::FETCH_ASSOC );
	return $cashadvance;
}

function updateAccountBalance ($cashadvance_account, $balance){
	global $conf;

	$output = array();
	$db = new \dbQuery();
	$db->connect($conf);
	$cashadvance_table = namespace\CashAdvanceTable;

	$balance_stmt = "UPDATE $cashadvance_table SET balance = '$balance' WHERE cashadvance_account = ? ";
	$db->sqlStatement = $balance_stmt;
	$values = array($cashadvance_account);

	try {
		$db->beginTransaction();
		$db->execSQL($values);
		$db->commitTransaction();
		$db->closeConnection();
		$output['status'] = 'success';
	} catch(\PDOException $e){
		$db->closeConnection();
		$output['status'] = 'error';
		$output['message'] = $e->getMessage();
	}

	return $output;
}

function updateHold($cashadvance_account, $hold){
	global $conf;
	$db = new \dbQuery();
	$db->connect($conf);
	$cashadvance_table = namespace\CashAdvanceTable;
	$hold = (int) $hold;
	$ca_stmt = " update $cashadvance_table set hold = ? where cashadvance_account = ? ";

	$values = array(
			$hold, $cashadvance_account
	);
	$db->sqlStatement = $ca_stmt;

	$db->beginTransaction ();
	$result = $db->execSQL($values);
	$db->commitTransaction ();
	$db->closeConnection ();

	return $result;
}

function addMemo($user_id, $cashadvance_account, $transaction){
	global $conf;
	$output = array();
	$db = new \dbQuery();
	$db->connect($conf);
	$transaction_tbl = namespace\CATransactionsTable;

	$tr_types = array(
			'debit' => '038003',
			'credit' => '038004',
	);

	$tr_stmt = " insert into $transaction_tbl
					(cashadvance_account, payconfig_id, tr_date, tr_code, debit, credit, balance, posted_by)
					values (?, ?, ?, ?, ?, ?, ?, ?)
				";


	$ca_account = getCashAdvance(array(
			'employee_id' => $cashadvance_account['employee_id'],
			'payconfig_id' => $cashadvance_account['cashadvance_type'],
	));

	$balance = $ca_account['balance'];

	if($transaction['type'] == $tr_types['debit']){
		$transaction['debit'] = $transaction['amount'];
		$transaction['credit'] = 0;
		$balance += $transaction['amount'];
	} elseif($transaction['type'] == $tr_types['credit']){
		$transaction['credit'] = $transaction['amount'];
		$transaction['debit'] = 0;
		$balance -= $transaction['amount'];
	}

	$tr_values = array(
			$ca_account['cashadvance_account'],
			$ca_account['payconfig_id'],
			$transaction['date'],
			$transaction['type'],
			$transaction['debit'],
			$transaction['credit'],
			$balance,
			$user_id,
	);
	$db->sqlStatement = $tr_stmt;
	$db->beginTransaction();

	$result = $db->execSQL($tr_values);

	$db->commitTransaction();
	$db->closeConnection();

	updateAccountBalance($ca_account['cashadvance_account'], $balance);

	return $result;
}

function addTransaction($user_id, $transaction, $date) {
	global $conf;
	$output = array ();
	$db = new \dbQuery ();
	$db->connect ( $conf );
	$cashadvance_table = namespace\CashAdvanceTable;
	$transaction_tbl = namespace\CATransactionsTable;
	$cashadvance_tr_code = namespace\CASHADVANCE_TR_CODE;

	$credit = $debit = 0;



	$account = getCashAdvance ( array (
			'employee_id' => $transaction ['id'],
			'payconfig_id' => $transaction ['payconfig']
	) );

	if (! $account) {
		$account = namespace\createCashAdvance ( $transaction ['payconfig'], $transaction ['id'], $user_id );
	}

	$debit =$transaction ['amount'];
	$account['balance'] += $debit;

	$transaction_stmt = "INSERT INTO $transaction_tbl (cashadvance_account,
													   payconfig_id,
													   tr_date,
													   tr_code,
													   debit,
													   credit,
													   balance,
													   posted_by)
						  VALUES (?, ?, ?, ?, ?, 0, ?, ?) ";

	$values = array (
			$account ['cashadvance_account'],
			$transaction ['payconfig'],
			$date,
			$cashadvance_tr_code,
			$debit,
			$account['balance'],
			$user_id
	);

	$db->sqlStatement = $transaction_stmt;

	$db->beginTransaction ();
	$db->execSQL ( $values );
	$db->commitTransaction ();
	$db->closeConnection ();

	$result = namespace\updateAccountBalance($account['cashadvance_account'], $account['balance']);

	return $result;
}
