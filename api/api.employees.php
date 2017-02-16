<?php
use \Employees;
include("../config/conf.db.php");
  include("../classes/class.dbquery.php");
  include("../library/lib.general.php");
  require_once('../library/lib.employees.php');
  $dbQ = new dbQuery();
  $dbQ->connect($conf);
  $orangeDB=$orange['db'];
  
  $value=array();
  if(count($_GET)>0){
    $value=$_GET;
  }else{
    $value=$_POST;
  }
  $cmd=$value['cmd'];
  $messsage="";
  $status="";
  $response=array("command"=>"nocommand","message"=>"Command not found!","status"=>"error");
  if($cmd=="searchemployee"){
      $branch=$value['branch'];
      $term=$value['term'];
      $status="";
      
      if(isset($value['status'])){
          $status=$value['status'];
      }
      
      $statusFilter="";
      if($status!="")
        $statusFilter=" AND emp_status=$status";
      
      $locationFilter="";
      if(isset($value['location'])){
          $locationID=$value['location'];
          if($locationID!="")
            $locationFilter=" AND emp_number IN (SELECT emp_number FROM $orangeDB.hs_hr_emp_locations WHERE location_id=$locationID)";
      }
      
     
      $dbQ->sqlStatement="SELECT employee_id,concat(emp_lastname,',',emp_firstname,' ',emp_middle_name) AS name,job_title_code from $orangeDB.hs_hr_employee ".
      " where concat(emp_lastname,',',emp_firstname,' ',emp_middle_name) LIKE '$term%'  AND  termination_id IS NULL AND job_title_code IS NOT NULL " .
      " $statusFilter $locationFilter ORDER BY emp_lastname,emp_firstname,emp_middle_name";
      //print $dbQ->sqlStatement;
      $dbQ->querySQL("");
      $data=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
      $emp=array();
     
      for($i=0;$i<count($data);$i++){
          $job=getid("$orangeDB.ohrm_job_title", "job_title", "id", $data[$i]["job_title_code"], $dbQ);
          $emp[]=array("recid"=>$i+1,"id"=>$data[$i]["employee_id"],"name"=>$data[$i]["name"],"job"=>$job);
      }
    
     
      $response=array("command"=>$cmd,"employees"=>$emp,"SQL"=>$dbQ->sqlStatement); 
     
  }else
  if($cmd=="loadphoto"){
       $empid=$value['id'];
       $empid=getid("$orangeDB.hs_hr_employee","emp_number","employee_id",$empid,$dbQ);
       $dbQ->sqlStatement="select epic_picture as file from  $orangeDB.hs_hr_emp_picture where emp_number=$empid";
       $dbQ->querySQL("");
       $data=$dbQ->stmt->fetch(PDO::FETCH_ASSOC);
       $img=base64_encode($data['file']);
       $response=array("command"=>$cmd,"img"=>$img);
      
   } else
   if($cmd=="getinfo"){
   		$response['command'] = $cmd;
   	    $employee_id=$value['employee_id'];
   	   	$result = \Employees\getInfo($employee_id);
   	   	$result['info']['name'] = "{$result['info']['emp_lastname']}, {$result['info']['emp_firstname']} " . $result['info']['emp_middle_name'][0] . ',';
   	   	unset($response['message']);
   	   	$response = array_merge($response, $result);
   }else
   if($cmd=="savepayrate"){
       //cmd=savepayrate&eid=0001&edate=2015-12-15&paytype=033002&accountno=54545&dailyrate=280&monthlyrate=7850&payfrequency=035001&sss=0&philhealth=&bir=1
      $rateid=idnumNoYear("302".date("y"), "employee_pay_rates", "rate_id", $dbQ, 5);
      $empid=$value['eid'];
      $edate=$value['edate'];
      $paytype=$value['paytype'];
      $account=$value['accountno'];
      $daiy=$value['dailyrate'];
      $monthly=$value['monthlyrate'];
      $freq=$value['payfrequency'];
      $phealth="";
      $sss="";
      $bir="";
      
      if($phealth=="")
        $phealth="-1";
      if($sss=="")
        $sss="-1";
      if($bir=="")
        $bir="-1";
      $dbQ->sqlStatement=" INSERT INTO employee_pay_rates(rate_id,employee_id,effective_date,pay_type,".
                         " account_no,daily_rate,monthly_rate,pay_frequency,sss_bracket_no,philhealth_bracket_no,bir_id)".
                         "VALUES('$rateid','$empid','$edate','$paytype','$account',$daiy,$monthly,'$freq',$sss,$phealth,$bir)";
      try{
         $dbQ->beginTransaction();
         $dbQ->execSQL();
         $dbQ->commitTransaction();
         $message="Employee Rate added!";
         $status="success";
      }catch(Exception $e){
          $dbQ->rollBack();
          $message="Error: ".$e->getMessage();
          $status="error";
      }
      $response=array("command"=>$cmd,"message"=>$message,"status"=>$status,"SQL"=>$dbQ->sqlStatement);
   }else
   if($cmd=="payratehistory"){
       $eid=$value['eid'];
       $dbQ->sqlStatement="SELECT rate_id,effective_date,pay_type,pay_frequency,daily_rate,monthly_rate from employee_pay_rates ".
                         " WHERE employee_id='$eid' ORDER BY effective_date";
       
        //print $dbQ->sqlStatement;
        /*
           {field:'rateid',hidden:true},
            {field:'edate',caption:'Effective Date',size:'100%'},
            {field:'paytype',caption:'Pay Type',size:'100%'},
            {field:'freq',caption:'Frequency',size:'100%'},
            {field:'daily',caption:'Daily Rate',size:'100%',render:'number:2'},
            {field:'monthly',caption:'Monthly Rate',size:'100%',render:'number:2'},
            
        */
        $dbQ->querySQL("");
        $data=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
        $rate=array();
        for($i=0;$i<count($data);$i++){
           // echo $data[$i]['pay_frequency'];
          $paytype=getid("system_configs", "config_name", "config_id", $data[$i]['pay_type'], $dbQ);
             $freq=getid("system_configs", "config_name", "config_id", $data[$i]['pay_frequency'], $dbQ);
           $rate[]=array("recid"=>$i,"rateid"=>$data[$i]['rate_id'],"edate"=>$data[$i]['effective_date'],
                       "paytype"=>$paytype,"freq"=>$freq,"daily"=>$data[$i]['daily_rate'],"monthly"=>$data[$i]['monthly_rate']);
        }
        $response=array("command"=>$cmd,"data"=>$rate);
   }
  
  $dbQ->closeConnection();
    
  print json_encode($response);
  
 
?>