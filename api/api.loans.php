<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 12/10/2015
 * Time: 10:39 PM
 */

include("../config/conf.db.php");
include("../classes/class.dbquery.php");
include("../library/lib.general.php");

global $conf;
$db = new dbQuery();
$db->connect($conf);
$orangeDB=$orange['db'];

$LOAN_ACCOUNT_NO = "401";

if(count($_GET)>0){
    $value=$_GET;
}else{
    $value=$_POST;
}

$cmd=$value['cmd'];

function convertToAssociativeArray($val){
    $data=array();
    for($i=0;$i<count($val);$i++){
        $data[$val[$i]['name']]=strtoupper($val[$i]['value']);
    }
    return $data;
}

function getBalance($field,$dbquery){
    //
    $sql="select sum(debit-credit) as balance from loans_ledger where loan_account = '$field' ";

    $dbquery->sqlStatement=$sql;
    $dbquery->querySQL("");
    $data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);

    $bal=$data["balance"];

    return $bal;
}

try{
    if($cmd == "loadselected"){
        $recID = $value['rid'];

        $SQL = "SELECT employee_id,concat(emp_lastname,', ',emp_firstname,' ',emp_middle_name) as name,job_title_code,emp_status from $orangeDB.hs_hr_employee ".
            " where employee_id = $recID and  termination_id is NULL";
        $db->sqlStatement=$SQL;

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);

            $records = array();
            for($i=0;$i<count($data);$i++) {
                $records[] = array("recid" => $i + 1, "id"=>$data[$i]["employee_id"],"name"=>$data[$i]["name"],"status"=>$data[$i]["emp_status"]);

            }


        }catch (Exception $e){
            echo $e->getMessage();
        }
        $response = array("command" => $cmd, "eid"=>$recID, "records" => $records);
        print json_encode($response);

    }if($cmd == "loadloans"){
        $recID = $value['rid'];

        $SQL = "SELECT loan_account,payconfig_id,employee_id,loan_date,loan_amount,term,amortization,loan_balance,hold ".
                "from loans where employee_id = '$recID'";
        $db->sqlStatement=$SQL;

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);

            $records = array();
            for($i=0;$i<count($data);$i++) {
                $records[] = array("recid" => $i + 1, "loan_account"=>$data[$i]["loan_account"],"payconfig_id"=>$data[$i]["payconfig_id"],
                                    "employee_id"=>$data[$i]["employee_id"],"loan_date"=>$data[$i]["loan_date"],
                                    "loan_amount"=>$data[$i]["loan_amount"],"term"=>$data[$i]["term"],"amortization"=>$data[$i]["amortization"],
                                    "loan_balance"=>$data[$i]["loan_balance"],"hold"=>$data[$i]["hold"]);
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
        $response = array("command" => $cmd, "records" => $records);
        print json_encode($response);

    }if($cmd == "displayemploans"){
        $recID = $value['rid'];

        /*$SQL = "SELECT loan_account,payconfig_id,employee_id,loan_date,loan_amount,term,amortization,loan_balance,hold ".
            "from loans where employee_id = '$recID'";*/

        $SQL = "SELECT a.loan_account,a.payconfig_id, concat(b.emp_lastname,',',b.emp_firstname,' ',b.emp_middle_name) as name, a.loan_amount, a.hold,a.loan_balance,a.amortization from loans a, $orangeDB.hs_hr_employee b where ".
               "a.employee_id = '$recID' and a.employee_id = b.employee_id ORDER BY a.loan_balance desc";
        $db->sqlStatement=$SQL;

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);

            $records = array();
            for($i=0;$i<count($data);$i++) {
            	$name=getid("payroll_config","pay_name","payconfig_id",$data[$i]['payconfig_id'],$db);
                $records[] = array("recid" => $i + 1, "accountno"=>$data[$i]["loan_account"],"name"=>$name,
                    "loanamount"=>$data[$i]["loan_amount"],"holdstatus"=>$data[$i]["hold"],"balance"=>$data[$i]["loan_balance"],"amortization"=>$data[$i]["amortization"]);
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
        $response = array("command" => $cmd, "records" => $records, "sql" => $SQL);
        print json_encode($response);

    }if($cmd == "getpayconfig"){
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $SQL = "SELECT payconfig_id as id, pay_name as name from payroll_config where loan = 1";
        $db->sqlStatement=$SQL;

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (Exception $e){
            echo $e->getMessage();
        }

        $response = array("command" => $cmd, "data" => $data, "sql" => $SQL);
        print json_encode($response);
    }if($cmd == "saverecord") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $db2 = new dbQuery();
        $db2->connect($conf);

        $dbacctno = new dbQuery();
        $dbacctno->connect($conf);

        $data = convertToAssociativeArray($value['data']);

        $account_no = idnum ( $LOAN_ACCOUNT_NO, "loans", 'loan_account', $dbacctno, 5 );
        $payconfig = $data['sel-payconfig'];
//        $type = $data['type'];
        $amount = $data['amount'];
        $term = $data['term'];
        $amortization = $data['amortization'];
        $employeeid = $data['employeeid'];

        $SQL = "INSERT INTO loans(loan_account, payconfig_id, employee_id, loan_date, loan_amount, term, amortization, loan_balance, hold, added_by, date_added) values(?,?,?,NOW(),?,?,?,?,?,?,NOW())";
        $db->sqlStatement = $SQL;
        $recInfo = array($account_no, $payconfig, $employeeid, $amount, $term, $amortization, $amount, "0", "SYSADM");

        $LedgerSQL = "INSERT INTO loans_ledger(loan_account, payconfig_id, tr_date, tr_code, credit, debit, balance, posted_by) values(?,?,NOW(),?,?,?,?,?)";
        $db2->sqlStatement = $LedgerSQL;
        $recInfo2 = array($account_no, $payconfig, "037001","0", $amount,  $amount, "SYSADM");

        try {
            $db->beginTransaction();
            $db2->beginTransaction();
            $db->execSQL($recInfo);
            $db2->execSQL($recInfo2);
            $db->commitTransaction();
            $db2->commitTransaction();
            $message = "Records Saved Successfully!";
            $status = "success";
        } catch (Exception $e) {
           // echo $e;
            $db->rollbackTransaction();
            $db2->rollbackTransaction();
            $message = "Failure to insert record.";
            $status = "error";
            echo $e->getMessage();
        }
        $response=array("command"=>$cmd,"message"=>$message,"status"=>$status, "data"=>$recInfo);
        print json_encode($response);
    }if($cmd == "updateholdstatus") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $acctno = $value['acctno'];
        $stat = $value['stat'];

        $SQL = "UPDATE loans set hold = ? where loan_account = ?";
        $db->sqlStatement = $SQL;
        $recordsInfo = array($stat, $acctno);

        try {
            $db->beginTransaction();
            $db->execSQL($recordsInfo);
            $db->commitTransaction();
            $message = "Record Updated!";
            $status = "success";
        } catch (Exception $e) {
            $db->rollbackTransaction();
            $message = "Failure to update record.";
            $status = "error";
            echo $e->getMessage();
        }
        $response=array("command"=>$cmd,"message"=>$message,"status"=>$status, "data"=>$recordsInfo);
        print json_encode($response);
    }if($cmd == "getloanbalance"){
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $acctno = $value['acctno'];

//        $SQL = "SELECT balance from loans_ledger where loan_account = '$acctno' order by row_id desc limit 1";
        $SQL = "SELECT loan_balance as balance from loans where loan_account = '$acctno'";
        $db->sqlStatement=$SQL;

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (Exception $e){
            echo $e->getMessage();
        }
        /*$response = array("command" => $cmd, "eid"=>$recID, "records" => $records);
        print json_encode($response);*/

        $response = array("command" => $cmd, "data" => $data, "sql" => $SQL);
        print json_encode($response);
    }if($cmd == "saverecordmemo") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $db2 = new dbQuery();
        $db2->connect($conf);

        $dbacctno = new dbQuery();
        $dbacctno->connect($conf);

        $data = convertToAssociativeArray($value['data']);

        $memotype = $data['memo-type'];
        $memodate = $data['memo-date'];
        $memoamount = $data['memo-amount'];
        $memoacctno = $data['memo-acctno'];

        $latestbal = getBalance($memoacctno,$db);

        $LedgerSQL = "INSERT INTO loans_ledger(loan_account, payconfig_id, tr_date, tr_code, debit, credit, balance, posted_by) values(?,?,?,?,?,?,?,?)";
        $db->sqlStatement = $LedgerSQL;

        $UpdateSQL = "UPDATE loans set loan_balance = ? where loan_account = ?";
        $db2->sqlStatement = $UpdateSQL;

        if($memotype=="038003"){
            $lastbalance = $latestbal + $memoamount;
            $recInfo = array($memoacctno, "", $memodate, "037003", $memoamount, "0", $lastbalance, "SYSADM");
            $recInfo2 = array($lastbalance,$memoacctno);
        }else if($memotype=="038004"){
            $lastbalance = $latestbal - $memoamount;
            $recInfo = array($memoacctno, "", $memodate, "037004", "0", $memoamount, $lastbalance, "SYSADM");
            $recInfo2 = array($lastbalance,$memoacctno);
        }

        try {
            $db->beginTransaction();
            $db2->beginTransaction();
            $db->execSQL($recInfo);
            $db2->execSQL($recInfo2);
            $db->commitTransaction();
            $db2->commitTransaction();
            $message = "Records Saved Successfully!";
            $status = "success";
        } catch (Exception $e) {
            echo $e;
//            $db->rollbackTransaction();
            $db2->rollbackTransaction();
            $message = "Failure to insert record.";
            $status = "error";
            echo $e->getMessage();
        }
        $response=array("command"=>$cmd,"message"=>$message,"status"=>$status, "data"=>$recInfo,"update"=>$recInfo2);
        print json_encode($response);
    }if($cmd == "displayadjusments"){
        $loanid = $value['acctno'];

        /*$SQL = "SELECT loan_account,payconfig_id,employee_id,loan_date,loan_amount,term,amortization,loan_balance,hold ".
            "from loans where employee_id = '$recID'";*/

        $SQL = "select loan_account,tr_date, tr_code, debit, credit from loans_ledger where loan_account = '$loanid'";
        $db->sqlStatement=$SQL;

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);

            $records = array();
            for($i=0;$i<count($data);$i++) {
                if($data[$i]["debit"]=="0"){
                    $records[] = array("recid" => $i + 1, "date"=>$data[$i]["tr_date"],"tr_type"=>"Credit","dr"=>$data[$i]["debit"],
                        "cr"=>$data[$i]["credit"], "loan_account" => $data[$i]["loan_account"]);
                }else{
                    $records[] = array("recid" => $i + 1, "date"=>$data[$i]["tr_date"],"tr_type"=>"Debit","dr"=>$data[$i]["debit"],
                        "cr"=>$data[$i]["credit"], "loan_account" => $data[$i]["loan_account"]);
                }

            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
        $response = array("command" => $cmd, "records" => $records, "sql" => $SQL);
        print json_encode($response);

    }
}catch(Exception $e){
    $db->rollbackTransaction();
    $message="Failure to add record.";
    $status="error";
    echo $e->getMessage();
}

