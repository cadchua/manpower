<?php
  include("../config/conf.db.php");
  include("../classes/class.dbquery.php");
  include("../classes/class.payroll.php");
  include("../library/lib.general.php");
  $dbQ = new dbQuery();
  $orangeDB = new dbQuery();
  $payroll=new Payroll();
  
  $dbQ->connect($conf);
  $orangeDB->connect($orange);
  $payroll->dbQ=$dbQ;
  $payroll->orangeDB=$orange['db'];
  
  $value=array();
  if(count($_GET)>0){
    $value=$_GET;
  }else{
    $value=$_POST;
  }
  $cmd=$value['cmd'];
  $response=array("command"=>"nocommand","message"=>"Command not found!","status"=>"error");
  if($cmd=="codesbygrp"){
      $grpid=$value['group'];
      $dbQ->sqlStatement="select config_id as id,config_name as name from system_configs where group_id='$grpid' order by config_id";
      $dbQ->querySQL("");
      $data=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
      $response=array("command"=>$cmd,"group"=>$grpid, "data"=>$data);    
  }else
  if($cmd=="empstatus"){
      $orangeDB->sqlStatement="select id,name from ohrm_employment_status order by id";
      $orangeDB->querySQL("");
      $data=$orangeDB->stmt->fetchAll(PDO::FETCH_ASSOC);
      $response=array("command"=>$cmd,"data"=>$data);
  }else
  if($cmd=="locations"){
      $orangeDB->sqlStatement="SELECT id,name FROM ohrm_location ORDER BY name";
      $orangeDB->querySQL("");
      $data=$orangeDB->stmt->fetchAll(PDO::FETCH_ASSOC);
      $response=array("command"=>$cmd,"data"=>$data);
  }else
  if($cmd=="getpaytypes"){
     $customID=getid("system_configs","str_value","config_id","078002",$dbQ);
     $payTypes=$payroll->orangeHRMpayTypes($customID);
     sort($payTypes);
     $response=array("command"=>$cmd,"data"=>$payTypes); 
  }
  
  
  print json_encode($response);
  
 
?>