<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 11/7/2015
 * Time: 12:08 AM
 */

include("../../../config/conf.db.php");
include("../../../classes/class.dbquery.php");
include("../../../library/lib.general.php");

/*$calldb= new dbQuery();
$dbconf['host']="localhost";
$dbconf['db']="paymaster";
$dbconf['user']="root";
$dbconf['pass']="toor";
$calldb->connect($dbconf);*/

if(count($_GET)>0){
    $value=$_GET;
}else{
    $value=$_POST;
}

$cmd=$value['cmd'];

//$db->beginTransaction();

function convertToAssociativeArray($val){
    $data=array();
    for($i=0;$i<count($val);$i++){
        $data[$val[$i]['name']]=strtoupper($val[$i]['value']);
    }
    return $data;
}

function generateUserId($prefix,$table,$field,$seqLen){
    global $conf;
    $db = new dbQuery();
    $db->connect($conf);

    $prefixLen = strlen($prefix)+1;

    $sql = "SELECT substr($field,$prefixLen,$seqLen) as user_id from $table where $field like '$prefix%' ".
           "order by substr($field,$prefixLen,$seqLen) desc limit 1";
    $db->sqlStatement=$sql;
    try{
        $db->beginTransaction();
        $db->querySQL(null);
        $data=$db->stmt->fetch(PDO::FETCH_ASSOC);
    }catch (Exception $e){
        echo $e->getMessage();
    }


    $tmpid="";
    $curid="";
    $curid=$data[$field];

    if($curid!=""){
        $curid=$curid+1;
        $tmp=addTrailingZero(strlen($curid),$seqLen).$curid;


        $tmpid=$prefix . $tmp;

    }else{
        $tmpid=$prefix . addTrailingZero(1,$seqLen)."1";
    }
//    echo $tmpid;

    return $tmpid;
}

try {
    if($cmd == "searchworkgroup"){
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $SQL = "SELECT config_id, config_name from system_configs where group_id = '022' order by group_id asc";
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
    }
    if($cmd == "searchstatus"){
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $SQL = "SELECT config_id, config_name from system_configs where group_id = '022' order by group_id asc";
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
    }
    if ($cmd == "displayallusers") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $SQL = "SELECT user_id, fullname, username, workgroup, is_active, SUBSTRING_INDEX(fullname,' ',1) fname, SUBSTRING_INDEX(fullname,' ',-1) lname from user_info";
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
    }if($cmd == "saveuser") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $data = convertToAssociativeArray($value['data']);
        $getDate = getCurrentDate();
        $datearr = explode("-", $getDate);
        $yr = substr($datearr[0], -2);
        $tmpid = generateUserId($yr, "user_info", "user_id", "3");

        $uid = $tmpid;
        $fname = $data['firstname'];
        $lname = $data['lastname'];
        $uname = $data['username'];
        $upass = $data['password'];
        $fullname = $fname . " " . $lname;

        $SQL = "INSERT INTO user_info(user_id, fullname, username, userpass, workgroup, is_active) values(?,?,?,?,?,?)";
        $db->sqlStatement = $SQL;
        $userInfo = array($uid, $fullname, $uname, md5($upass), "", "");

        try {
            $db->beginTransaction();
            $db->execSQL($userInfo);
            $db->commitTransaction();
            $message = "New User Created!";
            $status = "success";
        } catch (Exception $e) {
            $db->rollbackTransaction();
            $message = "Failure to create new user.";
            $status = "error";
            echo $e->getMessage();
        }
        $response=array("command"=>$cmd,"message"=>$message,"status"=>$status, "data"=>$userInfo);
        print json_encode($response);
    }
//    $calldb->commitTransaction();
}catch(Exception $e){
    $db->rollbackTransaction();
    $message="Failure to create new user.";
    $status="error";
    echo $e->getMessage();
}
