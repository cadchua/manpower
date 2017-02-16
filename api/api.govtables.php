<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 11/16/2015
 * Time: 11:33 PM
 */

include("../config/conf.db.php");
include("../classes/class.dbquery.php");
include("../library/lib.general.php");

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

try {
    if ($cmd == "displayrecords") {
        $grp = $value['grp'];

        global $conf;
        $db = new dbQuery();
        $db->connect($conf);
        $SQL="";

        if($grp=="bir"){
            $SQL = "SELECT row_id, pay_frequency, tax_code, column_no, pay_rate, tax_amount, over_tax_percent from bir";
            $db->sqlStatement=$SQL;
        }else if($grp=="philhealth"){
            $SQL = "SELECT row_id, bracket_no, salary_base, employer_share, employee_share, total_contribution from philhealth";
            $db->sqlStatement=$SQL;
        }

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);

            $records = array();
            if($grp=="bir"){
                for($i=0;$i<count($data);$i++){
                    $records[]=array("recid"=>$i+1,"row_id"=>$data[$i]["row_id"],"pay_frequency"=>$data[$i]["pay_frequency"],"tax_code"=>$data[$i]["tax_code"],
                        "column_no"=>$data[$i]["column_no"],"pay_rate"=>$data[$i]["pay_rate"],"tax_amount"=>$data[$i]["tax_amount"],"over_tax_percent"=>$data[$i]["over_tax_percent"]);
                }
            }else if($grp=="philhealth"){
                for($i=0;$i<count($data);$i++){
                    $records[]=array("recid"=>$i+1,"row_id"=>$data[$i]["row_id"],"bracket_no"=>$data[$i]["bracket_no"],"salary_base"=>$data[$i]["salary_base"],"employer_share"=>$data[$i]["employer_share"],
                        "employee_share"=>$data[$i]["employee_share"],"total_contribution"=>$data[$i]["total_contribution"]);
                }
            }

        }catch (Exception $e){
            echo $e->getMessage();
        }


        $response = array("command" => $cmd, "group" => $grp, "data" => $records);
        print json_encode($response);
    }
    if($cmd == "loadselected"){
        $recID = $value['rid'];
        $govtype = $value['type'];
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);
        $SQL= "";

        if($govtype=="bir"){
            $SQL = "SELECT row_id, pay_frequency, tax_code, column_no, pay_rate, tax_amount, over_tax_percent from bir where row_id = '$recID'";
        }else if($govtype=="philhealth"){
            $SQL = "SELECT row_id, bracket_no, salary_base, employer_share, employee_share, total_contribution from philhealth where row_id = '$recID'";
        }

        $db->sqlStatement=$SQL;

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);

            $records = array();
            if($govtype=="bir"){
                for($i=0;$i<count($data);$i++){
                    $records[]=array("recid"=>$i+1,"row_id"=>$data[$i]["row_id"],"pay_frequency"=>$data[$i]["pay_frequency"],"tax_code"=>$data[$i]["tax_code"],
                        "column_no"=>$data[$i]["column_no"],"pay_rate"=>$data[$i]["pay_rate"],"tax_amount"=>$data[$i]["tax_amount"],"over_tax_percent"=>$data[$i]["over_tax_percent"]);
                }
            }else if($govtype=="philhealth"){
                for($i=0;$i<count($data);$i++){
                    $records[]=array("recid"=>$i+1,"row_id"=>$data[$i]["row_id"],"bracket_no"=>$data[$i]["bracket_no"],"salary_base"=>$data[$i]["salary_base"],"employer_share"=>$data[$i]["employer_share"],
                        "employee_share"=>$data[$i]["employee_share"],"total_contribution"=>$data[$i]["total_contribution"]);
                }
            }


        }catch (Exception $e){
            echo $e->getMessage();
        }
        $response = array("command" => $cmd, "type"=> $govtype,"records" => $records);
        print json_encode($response);

    }
    if($cmd == "getpayfrequency"){
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $SQL = "SELECT config_id as id, config_name as name from system_configs where group_id = '035' order by group_id asc";
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
    }if($cmd == "saverecordbir") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $data = convertToAssociativeArray($value['data']);

        $payfrequency = $data['sel-payfrequency'];
        $taxcode = $data['sel-taxcode'];
        $colmunno = $data['sel-columno'];
        $payrate = $data['payrate'];
        $taxamount = $data['taxamount'];
        $overtaxpct = $data['overtaxpercent'];


        $SQL = "INSERT INTO bir(pay_frequency, tax_code, column_no, pay_rate, tax_amount, over_tax_percent) values(?,?,?,?,?,?)";
        $db->sqlStatement = $SQL;
        $recInfo = array($payfrequency, $taxcode, $colmunno, $payrate, $taxamount, $overtaxpct);

        try {
            $db->beginTransaction();
            $db->execSQL($recInfo);
            $db->commitTransaction();
            $message = "Records Saved Successfully!";
            $status = "success";
        } catch (Exception $e) {
            $db->rollbackTransaction();
            $message = "Failure to insert record.";
            $status = "error";
            echo $e->getMessage();
        }
        $response=array("command"=>$cmd,"message"=>$message,"status"=>$status, "data"=>$recInfo);
        print json_encode($response);
    }if($cmd == "saverecordphilhealth") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $data = convertToAssociativeArray($value['data']);

        $bracketno = $data['bracketno'];
        $salarybase = $data['salarybase'];
        $employershare = $data['employershare'];
        $employeeshare = $data['employeeshare'];
        $totalcontribution = $data['totalcontribution'];


        $SQL = "INSERT INTO philhealth(bracket_no, salary_base, employer_share, employee_share, total_contribution) values(?,?,?,?,?)";
        $db->sqlStatement = $SQL;
        $recInfo = array($bracketno, $salarybase, $employershare, $employeeshare, $totalcontribution);

        try {
            $db->beginTransaction();
            $db->execSQL($recInfo);
            $db->commitTransaction();
            $message = "Records Saved Successfully!";
            $status = "success";
        } catch (Exception $e) {
            $db->rollbackTransaction();
            $message = "Failure to insert record.";
            $status = "error";
            echo $e->getMessage();
        }
        $response=array("command"=>$cmd,"message"=>$message,"status"=>$status, "data"=>$recInfo);
        print json_encode($response);
    }
    if($cmd == "updaterecordbir") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $data = convertToAssociativeArray($value['data']);
        $rid = $data['row-id-mod'];
        $payfreq = $data['sel-payfrequency-mod'];
        $taxcode = $data['sel-taxcode-mod'];
        $colno = $data['sel-columno-mod'];
        $payrate = $data['payrate-mod'];
        $taxamount = $data['taxamount-mod'];
        $overtax = $data['overtaxpercent-mod'];

        $recordsInfo = array();

        $SQL = "UPDATE bir set pay_frequency = ?, tax_code = ?, column_no = ?, pay_rate = ?, tax_amount = ?, over_tax_percent =? where row_id = ?";
        $db->sqlStatement = $SQL;
        $recordsInfo = array($payfreq, $taxcode, $colno, $payrate, $taxamount, $overtax, $rid);

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
    }if($cmd == "updaterecordphilhealth") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $data = convertToAssociativeArray($value['data']);
        $rid = $data['row-id-mod'];
        $bracketno = $data['bracketno-mod'];
        $salarybase = $data['salarybase-mod'];
        $employershare = $data['employershare-mod'];
        $employeeshare = $data['employeeshare-mod'];
        $totalcontribution = $data['totalcontribution-mod'];
        $recordsInfo = array();

        $SQL = "UPDATE philhealth set bracket_no = ?, salary_base = ?, employer_share = ?, employee_share = ?, total_contribution = ? where row_id = ?";
        $db->sqlStatement = $SQL;
        $recordsInfo = array($bracketno, $salarybase, $employershare, $employeeshare, $totalcontribution, $rid);

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
    }

}catch(Exception $e){
    $db->rollbackTransaction();
    $message="Failure to add record.";
    $status="error";
    echo $e->getMessage();
}