<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 11/7/2015
 * Time: 12:08 AM
 */

include("../config/conf.db.php");
include("../classes/class.dbquery.php");
include("../library/lib.general.php");

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

        $SQL = "SELECT config_id as id, config_name as name from system_configs where group_id = '022' order by group_id asc";
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

        $SQL = "SELECT a.user_id, a.fullname, a.username, b.config_name as workgroup, is_active from user_info a, system_configs b ".
               "where a.workgroup = b.config_id order by a.user_id";
        $db->sqlStatement=$SQL;

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);

            $users = array();
            for($i=0;$i<count($data);$i++){
                $users[]=array("recid"=>$i+1,"user_id"=>$data[$i]["user_id"],"fullname"=>$data[$i]["fullname"],"username"=>$data[$i]["username"],
                              "workgroup"=>$data[$i]["workgroup"],"is_active"=>$data[$i]["is_active"]);
            }

        }catch (Exception $e){
            echo $e->getMessage();
        }
        $response = array("command" => $cmd, "users" => $users);
        print json_encode($response);
    }
    if($cmd == "saveuser") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $data = convertToAssociativeArray($value['data']);
        $getDate = getCurrentDate();
        $datearr = explode("-", $getDate);
        $yr = substr($datearr[0], -2);
        $tmpid = generateUserId($yr, "user_info", "user_id", "3");

        $uid = $tmpid;
        $fullname = $data['fullname'];
        $uname = $data['username'];
        $upass = $data['password'];
        $workgroup = $data['sel-workgroup'];
        $status = $data['sel-status'];

        $SQL = "INSERT INTO user_info(user_id, fullname, username, userpass, workgroup, is_active) values(?,?,?,?,?,?)";
        $db->sqlStatement = $SQL;
        $userInfo = array($uid, $fullname, $uname, md5($upass), $workgroup, $status);

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
    if ($cmd == "checkuserifexist") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $grp=$value['grp'];

        $SQL = "SELECT username from user_info where username = '$grp'";
        $db->sqlStatement=$SQL;

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);
            if(empty($data)){
                $result = "available";
            }else{
                $result = "taken";
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }


        $response = array("command" => $cmd, "data" => $data, "sql" => $SQL, "result" => $result);
        print json_encode($response);
    }if($cmd == "loadcustomerinfo"){
        $customerID = $value['cid'];
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $SQL = "SELECT a.user_id, a.fullname, a.username, a.workgroup, is_active from user_info a, system_configs b ".
            "where a.workgroup = b.config_id and a.user_id = '$customerID'";
        $db->sqlStatement=$SQL;

        try{
            $db->beginTransaction();
            $db->querySQL(null);
            $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);

            $users = array();
            for($i=0;$i<count($data);$i++){
                $users[]=array("recid"=>$i+1,"user_id"=>$data[$i]["user_id"],"fullname"=>$data[$i]["fullname"],"username"=>$data[$i]["username"],
                    "workgroup"=>$data[$i]["workgroup"],"is_active"=>$data[$i]["is_active"]);
            }

        }catch (Exception $e){
            echo $e->getMessage();
        }
        $response = array("command" => $cmd, "users" => $users);
        print json_encode($response);

    }
    if($cmd == "updateuser") {
        global $conf;
        $db = new dbQuery();
        $db->connect($conf);

        $data = convertToAssociativeArray($value['data']);
        $uid = $data['userid'];
        $fullname = $data['edit-fullname'];
        $uname = $data['edit-username'];
        $upass = $data['edit-password'];
        $workgroup = $data['sel-workgroup-mod'];
        $status = $data['sel-status-mod'];

//        $SQL = "INSERT INTO user_info(user_id, fullname, username, userpass, workgroup, is_active) values(?,?,?,?,?,?)";
        $SQL="";
        $userInfo = array();
        if(empty($upass)){
            $SQL = "UPDATE user_info set fullname = ?, username = ?, workgroup = ?, is_active = ? where user_id = ?";
            $db->sqlStatement = $SQL;
            $userInfo = array($fullname, $uname, $workgroup, $status, $uid);
        }else {
            $SQL = "UPDATE user_info set fullname = ?, username = ?, userpass = ?, workgroup = ?, is_active = ? where user_id = ?";
            $db->sqlStatement = $SQL;
            $userInfo = array($fullname, $uname, md5($upass), $workgroup, $status, $uid);
        }

        try {
            $db->beginTransaction();
            $db->execSQL($userInfo);
            $db->commitTransaction();
            $message = "Record Updated!";
            $status = "success";
        } catch (Exception $e) {
            $db->rollbackTransaction();
            $message = "Failure to update record.";
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
