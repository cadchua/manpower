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
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $SQL = "SELECT row_id, pay_frequency, tax_code, column_no, pay_rate, tax_amount, over_tax_percent from bir";
        $db->sqlStatement=$SQL;

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (Exception $e){
            echo $e->getMessage();
        }


        $response = array("command" => $cmd, "data" => $data);
        print json_encode($response);
    }if($cmd == "saverecord") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $data = convertToAssociativeArray($value['data']);

        $payfrequency = $data['payfrequency'];
        $taxcode = $data['sel-taxcode'];
        $colmunno = $data['columnno'];
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
    }

}catch(Exception $e){
    $db->rollbackTransaction();
    $message="Failure to add record.";
    $status="error";
    echo $e->getMessage();
}