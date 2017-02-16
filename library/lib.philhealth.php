<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 3/10/2016
 * Time: 10:39 PM
 */
include("../config/conf.db.php");
include("../classes/class.dbquery.php");
include("../library/lib.general.php");

if(count($_GET)>0){
    $value=$_GET;
}else{
    $value=$_POST;
}

function getPhilhealthContrib($basepay){
    global $conf;
    $db = new dbQuery();
    $db->connect($conf);

    $SQL = "SELECT * FROM philhealth WHERE salary_base <= $basepay ORDER BY salary_base desc LIMIT 1";
    $db->sqlStatement=$SQL;

    try{
        $db->beginTransaction();
        $db->querySQL(null);
        $data=$db->stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch (Exception $e){
        echo $e->getMessage();
    }


    $response = array("data" => $data);
    print json_encode($response);
}
