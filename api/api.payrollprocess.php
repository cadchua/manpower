<?php

  session_start();
  include("../config/conf.db.php");
  include("../classes/class.dbquery.php");
  include("../classes/class.payroll.php");
  include("../library/lib.general.php");

  include('../library/lib.employees.php');
  
  $dbQ = new dbQuery();
  $dbQ2 = new dbQuery();
   $dbQ3 = new dbQuery();
  $dbQ->connect($conf);
  $dbQ2->connect($conf);
  $dbQ3->connect($conf);
  $payroll=new Payroll();
  $payroll->dbQ=$dbQ3;
  $payroll->orangeDB=$orange['db'];

  //$orangeDB=$orange['db'];
  function getSSSBracket($amount){
    global $dbQ2;

    $bracket_stmt = "SELECT *, bracket_no as recid
                        FROM `sss`
                        WHERE salary <=  ?
                        ORDER BY salary desc
                        LIMIT 1";
    $values = array($amount);

    $dbQ2->sqlStatement = $bracket_stmt;
    $dbQ2->querySQL($values);

    $bracket = $dbQ2->stmt->fetch(PDO::FETCH_ASSOC);
    if($bracket['employer_share']=="")
         $bracket['employer_share']=0.00;
    if($bracket['employee_share']=="")
         $bracket['employee_share']=0.00;
     
    return $bracket;
 }

  function getPhilhealthContrib($basepay){
    global $dbQ2;

    $SQL = "SELECT * FROM philhealth WHERE  salary_base <= $basepay   ORDER BY salary_base desc LIMIT 1";
    $dbQ2->sqlStatement=$SQL;

    try{

        $dbQ2->querySQL("");
        $data=$dbQ2->stmt->fetch(PDO::FETCH_ASSOC);
    }catch (Exception $e){
        echo $e->getMessage();
    }
    if(($data['employee_share']!="")&&($data['employer_share'])){
      $data['employer_share']=$data['employer_share']/2;
      $data['employee_share']=$data['employee_share']/2;
     }else{
        $data['employer_share']=0.00;
      $data['employee_share']=0.00;
     }
    
    //$response = $data;
    return $data;
 }


function computeWithHoldingTax($payfreq,$taxcode,$payrate){
    $overhead = 0;
    $taxable = 0;
    $percentover = 0;
    $tax = 0;

    //echo "Pay Freq: ".$payfreq. " Tax code: ".$taxcode." Payrate: ".$payrate. " ";

    if($payfreq == "semimonthly"){
        if($taxcode == "Z"){
            if($payrate >= 0 && $payrate <= 417){

                $tax = 0;

            }else if($payrate > 417 && $payrate <= 1250){

                $overhead = 20.83;
                $taxable = 417;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 1250 && $payrate <= 2917){

                $overhead = 104.17;
                $taxable = 1250;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 2917 && $payrate <= 5833){

                $overhead = 354.17;
                $taxable = 2917;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 5833 && $payrate <= 10417){

                $overhead = 937.50;
                $taxable = 5833;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 10417 && $payrate <= 20833){

                $overhead = 2083.33;
                $taxable = 20833;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 20833){

                $overhead = 5208.33;
                $taxable = 20833;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME"){
            if($payrate >= 1 && $payrate <= 2083){

                $tax = 0;

            }else if($payrate > 2083 && $payrate <= 2500){

                $overhead = 0;
                $taxable = 2083;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 2500 && $payrate <= 3333){

                $overhead = 20.83;
                $taxable = 2500;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 3333 && $payrate <= 5000){

                $overhead = 104.17;
                $taxable = 3333;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 5000 && $payrate <= 7917){

                $overhead = 354.17;
                $taxable = 5000;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 7917 && $payrate <= 12500){

                $overhead = 937.50;
                $taxable = 7917;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 12500 && $payrate <= 22917){

                $overhead = 2083.33;
                $taxable = 12500;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 22917){

                $overhead = 5208.33;
                $taxable = 22917;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME1"){
            if($payrate >= 1 && $payrate <= 3125){

                $tax = 0;

            }else if($payrate > 3125 && $payrate <= 3542){

                $overhead = 0;
                $taxable = 3125;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 3542 && $payrate <= 4375){

                $overhead = 20.83;
                $taxable = 3542;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 4375 && $payrate <= 6042){

                $overhead = 104.17;
                $taxable = 4375;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 6042 && $payrate <= 8958){

                $overhead = 354.17;
                $taxable = 6042;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 8958 && $payrate <= 13542){

                $overhead = 937.50;
                $taxable = 8958;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 13542 && $payrate <= 23958){

                $overhead = 2083.33;
                $taxable = 13542;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 23958){

                $overhead = 5208.33;
                $taxable = 23958;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME2"){
            if($payrate >= 1 && $payrate <= 4167){

                $tax = 0;

            }else if($payrate > 4167 && $payrate <= 4583){

                $overhead = 0;
                $taxable = 4167;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 4583 && $payrate <= 5417){

                $overhead = 20.83;
                $taxable = 4583;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 5417 && $payrate <= 7083){

                $overhead = 104.17;
                $taxable = 5417;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 7083 && $payrate <= 10000){

                $overhead = 354.17;
                $taxable = 7083;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 10000 && $payrate <= 14583){

                $overhead = 937.50;
                $taxable = 10000;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 14583 && $payrate <= 25000){

                $overhead = 2083.33;
                $taxable = 14583;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 25000){

                $overhead = 5208.33;
                $taxable = 25000;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME3"){
            if($payrate >= 1 && $payrate <= 5208){

                $tax = 0;

            }else if($payrate > 5208 && $payrate <= 5625){

                $overhead = 0;
                $taxable = 5208;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 5625 && $payrate <= 6458){

                $overhead = 20.83;
                $taxable = 5625;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 6458 && $payrate <= 8125){

                $overhead = 104.17;
                $taxable = 6458;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 8125 && $payrate <= 11042){

                $overhead = 354.17;
                $taxable = 8125;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 11042 && $payrate <= 15625){

                $overhead = 937.50;
                $taxable = 11042;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 15625 && $payrate <= 26042){

                $overhead = 2083.33;
                $taxable = 15625;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 26042){

                $overhead = 5208.33;
                $taxable = 26042;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }else if($taxcode == "SME4"){
            if($payrate >= 1 && $payrate <= 6250){

                $tax = 0;

            }else if($payrate > 6250 && $payrate <= 6667){

                $overhead = 0;
                $taxable = 6250;
                $percentover = 5;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 6667 && $payrate <= 7500){

                $overhead = 20.83;
                $taxable = 6667;
                $percentover = 10;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 7500 && $payrate <= 9167){

                $overhead = 104.17;
                $taxable = 7500;
                $percentover = 15;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 9167 && $payrate <= 12083){

                $overhead = 354.17;
                $taxable = 9167;
                $percentover = 20;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 12083 && $payrate <= 16667){

                $overhead = 937.50;
                $taxable = 12083;
                $percentover = 25;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 16667 && $payrate <= 27083){

                $overhead = 2083.33;
                $taxable = 16667;
                $percentover = 30;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }else if($payrate > 27083){

                $overhead = 5208.33;
                $taxable = 27083;
                $percentover = 32;
                $tax = $overhead + (($payrate - $taxable) * ($percentover/100));

            }
        }
    }

    return $tax;
}



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
  if($cmd=="createpayroll"){
  	$payid=idnum("401", "payroll", "payroll_id", $dbQ, 4);
    $from=$value['from'];
    $to=$value['to'];
    $days=$value['days'];
    $data=array($payid,$from,$to,"036001","039001",$days);
    $sql="";
    try{
      $dbQ->sqlStatement="INSERT INTO payroll(payroll_id,from_date,to_date,status,process_status,days_work)".
                         " VALUES(?,?,?,?,?,?)";
      $sql=$dbQ->sqlStatement;
      $dbQ->beginTransaction();
      $dbQ->execSQL($data);

    //  $values=array($payid,"","","",$_SESSION ['userid'],"036001",$cmd,"039001");
     // actionLogs($values, $dbQ);
      $dbQ->commitTransaction();
       $message="Payroll Created.";
      $status="success";
    }catch(Exception $e){
      $dbQ->rollBack();
      $message="Error creating payroll. ".$e->getMessage();
      $status="error";

    }
    $response=array("command"=>$cmd,"message"=>$message,"status"=>$status);

  }else
  if($cmd=="loadpayrollheader"){
  	$dbQ->sqlStatement=" SELECT payroll_id,from_date,to_date,status,process_status,days_work FROM payroll WHERE status<>'036004' ".
  	                   " ORDER BY from_date desc limit 24 ";
      $data=array();
    try{
    $dbQ->querySQL("");
    $result=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);

    for($i=0;$i<count($result);$i++){
        $status=getid("system_configs", "config_name", "config_id", $result[$i]['status'], $dbQ);
        $process=getid("system_configs","config_name","config_id",$result[$i]['process_status'], $dbQ);
        if($status=="")
          $status="Pending";
        $data[]=array("recid"=>$i+1,"id"=>$result[$i]["payroll_id"],
        		"from"=>$result[$i]["from_date"],
        		"to"=>$result[$i]["to_date"],"status"=>$status,"process"=>$process,
        		"days"=>$result[$i]['days_work']);

    }
    $response=array("command"=>$cmd,"status"=>"0", "records"=>$data,"total"=>count($data));
    }catch(Exception $e){
         $response=array("command"=>$cmd,"status"=>"error","message"=>$e->getMessage(), "records"=>$data,"total"=>count($data));
    }
    }else
    if($cmd=="loadactivepayroll"){
      $dbQ->sqlStatement="SELECT payroll_id,from_date,to_date FROM payroll WHERE status='036002'"  ;
      $dbQ->querySQL("");
      $data= $dbQ->stmt->fetch(PDO::FETCH_ASSOC);

      $response=array("command"=>$cmd,"payid"=>$data['payroll_id'],"from"=>$data['from_date'],"to"=>$data['to_date']);
    }else
    if($cmd=="activatepayroll"){
        $pid=$value['payid'];


       try{
           $dbQ->beginTransaction();
           $dbQ->sqlStatement="UPDATE payroll SET status='036003' WHERE status='036002'";
           $dbQ->execSQL("");

           $dbQ->sqlStatement="UPDATE payroll SET status='036002' WHERE payroll_id='$pid'";
           $dbQ->execSQL("");

           $dbQ->commitTransaction();
           $status="success";
           $messsage="Payroll Activated.";
        }catch(Exception $e){
            $dbQ->rollBack();
            $status="error";
            $messsage=$e->getMessage();
        }
        $response=array("command"=>$cmd,"status"=>$status,"message"=>$messsage,"sql"=>$dbQ->sqlStatement);
    }else
    if($cmd=="loadwotu"){
        /*
         *  {field:'id',hidden:true},
           {field:'payfield',hidden:true},
           {field:'name',caption:'Field'},
           {field:'value',caption: 'Value', size: '100%' }
         *
         *
         *   {field:'id',caption:'ID',size:'125px'},
         {field:'name',caption:'Name',size:'185px'},
         {field:'job',caption:'Job Title',size:'160px'},
         {field:'days',caption:'Days Work',size:'100px',editable:{type:'int'}},
         */

        $payid=$value['pid'];
        $emp=$value['employee'];
        $data=array();
        for($i=0;$i<count($emp);$i++){
          $daysWork=0;
          $splHoliday=0;
          $regHoliday=0;
          $training=0;
          $eid=$emp[$i]['id'];

          $payHead=json_decode(getFieldValues2("payroll_header", "header_id,days_work,reg_holiday,spl_holiday,training_days", "payroll_id", $payid,"employee_id",$eid, $dbQ));
          if($payHead->{'days_work'}!="")
             $daysWork=$payHead->{'days_work'};
          if($payHead->{'reg_holiday'}!="")
             $regHoliday=$payHead->{'reg_holiday'};
          if($payHead->{'spl_holiday'}!="")
             $splHoliday=$payHead->{'spl_holiday'};
          if($payHead->{'training_days'}!="")
             $training=$payHead->{'training_days'};




          $data[]=array("recid"=>$emp[$i]['recid'],"empid"=>$eid,"days"=>$daysWork,"training"=>$training,"regular"=>$regHoliday,"special"=>$splHoliday);
        }


        $response=array("command"=>$cmd,"data"=>$data);


    }else
  if($cmd=="savewotu"){
      /*
       * cmd:savewotu
payid:401160001
eid:0001
data[0][recid]:1
data[0][id]:
data[0][payfield]:rate
data[0][name]:Rate
data[0][value]:285.00
data[0][editable]:false
       */
       //---SAVE PAYROLL HEADER FIRST---
         // $eid=$value['eid'];
        $payid=$value['payid'];
        $regDaysWork=getid("payroll","days_work","payroll_id",$payid,$dbQ);
        $trainingRate=getid("system_configs","num_value","config_id","099005",$dbQ);
         $emp=$value['employee'];
         $overtimeWithAllowance=getid("system_configs","str_value","config_id","099004",$dbQ);
         $empStatusWithNoGovDed=getid("system_configs","num_value","config_id","099006",$dbQ);
                $empStatusField=getid("system_configs","str_value","config_id","099006",$dbQ);
         $overtimeWithAllowance=split($overtimeWithAllowance);
         if(count($overtimeWithAllowance)==0)
         	$overtimeWithAllowance=array(getid("system_configs","str_value","config_id","099004",$dbQ));
         
         if($payid==""){
             $response=array("command"=>$cmd,"message"=>"No active payroll!","status"=>"success");
             print json_encode($response);
             exit;
         }
         $breakpoint="";
      //  $data=$value['data'];
     try{
     
        $headerSEQ=1;
        $detailSEQ=1;
        $eid="";
         $govData=array();
         for($i=0;$i<count($emp);$i++){
          //  var_dump($emp);
              $dbQ->beginTransaction();
               $eid=$emp[$i]['id'];
              $name=$emp[$i]['name'];
         $empNumber=getid($orange['db'].".hs_hr_employee" , "emp_number", "employee_id", $eid, $dbQ2);
         $empStatus=getid($orange['db'].".hs_hr_employee" , $empStatusField, "employee_id", $eid, $dbQ2);
           $payRate=$payroll->orangeHRMpayRate($empNumber);
         $allowance=$payroll->orangeHRMAllowance($empNumber);
      
         $withGovDed=true;
         if($empStatus==$empStatusWithNoGovDed)
            $withGovDed=false;
          
          $govData[]=array("eid"=>$eid,"name"=>$name,"govDed"=>$withGovDed,"empStatus"=>$empStatus,"empStatusWithNoGov"=>$empStatusWithNoGovDed);
          $location=$payroll->orangeHRMLocation($empNumber);
          $basicPay=0.00;
          $alloancePay=0.00;
          $regHolidayPay=0.00;
          $splHolidayPay=0.00;
          $trainingPay=0.00;

          
      
          //------TAX CODE----------
            $customID=getid("system_configs","str_value","config_id","078001",$dbQ2);
            $taxCode=$payroll->orangeHRMCustomField($eid, $customID);
            if($taxCode=="")
              $taxCode="Z";
          //------ACCOUNT NO-------
           $customID=getid("system_configs","str_value","config_id","078003",$dbQ2);
            $accountNum=$payroll->orangeHRMCustomField($eid, $customID);
          //-----PAY TYPE-------
          $customID=getid("system_configs","str_value","config_id","078002",$dbQ2);
            $payType=$payroll->orangeHRMCustomField($eid, $customID);

                    $days=$emp[$i]['days'];
              $regHoliday=$emp[$i]['regular'];
              $splHoliday=$emp[$i]['special'];
              $trainingDays=$emp[$i]['training'];
          if($days>0){
             $basicPay=$payRate*$days;
             $allowancePay=$allowance*$days;
    
          if($trainingDays>0)
             $trainingPay=$trainingDays*$trainingRate;
          if($regHoliday>0){
          	 if(in_array($location,$overtimeWithAllowance))
                $regHolidayPay=($payRate+$allowance)*$regHoliday;
          	 else 
          	 	$regHolidayPay=($payRate)*$regHoliday;
          }
          if($splHoliday>0){
          	if(in_array($location,$overtimeWithAllowance))
                $splHolidayPay=(($payRate+$allowance) *0.3) * $splHoliday;
          	else
          		$splHolidayPay=(($payRate) *0.3) * $splHoliday;
          }

          //--Check if data exists---
          $headerID=getid2("payroll_header","header_id","employee_id",$eid,"payroll_id",$payid,$dbQ);
            $update=true;
          if($headerID==""){
            $headerID=idnumSEQ("202", "payroll_header", "header_id", $dbQ, 7,$headerSEQ);
            $headerSEQ++;
              $update=false;
          }
          if(!$update){
            $dbQ->sqlStatement="INSERT INTO payroll_header(header_id,payroll_id,employee_id,employee_name,rate,days_work,basic_pay,branch_id,pay_type,
                                 account_no,tax_code,reg_holiday,spl_holiday,reg_holiday_pay,spl_holiday_pay,living_allowance,training_days,training_pay)
                                  VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $payval=array($headerID,$payid,$eid,$name,$payRate,$days,$basicPay,$location,$payType,
                $accountNum,$taxCode,$regHoliday,$splHoliday,$regHolidayPay,$splHolidayPay,
                $allowancePay,$trainingDays,$trainingPay);
           }else{
            $dbQ->sqlStatement="UPDATE payroll_header SET rate=?,days_work=?,basic_pay=?,employee_name=?,branch_id=?,pay_type=?,account_no=?,tax_code=?, ".
                               " reg_holiday=?,spl_holiday=?,reg_holiday_pay=?,spl_holiday_pay=?,".
                               "living_allowance=?,training_days=?,training_pay=? ".

                               " WHERE header_id=? AND payroll_id=? and employee_id=?";
            $payval=array($payRate,$days,$basicPay,$name,$location,$payType,$accountNum,$taxCode,$regHoliday,$splHoliday,$regHolidayPay,$splHolidayPay,$allowancePay,$trainingDays,$trainingPay, $headerID,$payid,$eid);

          }
          $dbQ->execSQL($payval);

 
          
          //---PAYROLL DETAILS----
             
           //SSS
             $sssBasicPay=0.00;
            
             if($days<=$regDaysWork){
             	$sssBasicPay=$basicPay+$alloancePay+$regHolidayPay+$splHolidayPay;
             }else{
             	$sssBasicPay=($payRate*$regDaysWork)+($allowance*$regDaysWork)+$regHolidayPay+$splHolidayPay;
             	
             }
             
            
             $active=getid("payroll_config", "active", "government_req", "077005", $dbQ2);
              $sss['employee_share']=0.00;
              $sss['employer_share']=0.00;
              
              if($withGovDed)
                 $sss=getSSSBracket($sssBasicPay);
               
            $configID=getid("payroll_config", "payconfig_id", "government_req", "077005", $dbQ2);
            $detailID=getid3("payroll_details","detail_id","header_id",$headerID,"payroll_id",$payid,"payconfig_id",$configID,$dbQ2);
            $values=array();
            $breakpoint="SSS: ".$basicPay." - ES: ".$sss['employee_share']." ER:".$sss;
            if($active==1){
            if($detailID==""){

              $detailID=idnumSEQ("204","payroll_details","detail_id",$dbQ2,6,$detailSEQ);
              $detailSEQ++;


              $dbQ->sqlStatement="  INSERT INTO payroll_details(payroll_id,header_id,detail_id,payconfig_id,amount,income,sequence,employer_share) ".
                                  " VALUES(?,?,?,?,?,?,?,?) ";
              $values=array($payid,$headerID,$detailID,$configID, $sss['employee_share'],0,1,$sss['employer_share']);
            }else{
              $dbQ->sqlStatement=" UPDATE payroll_details SET amount=?,employer_share=? ".
                                 " WHERE payroll_id=? AND header_id=? AND detail_id=? ";
              $values=array($sss['employee_share'],$sss['employer_share'],$payid,$headerID,$detailID);
            }
            $dbQ->execSQL($values);
            }else{
            	$dbQ->sqlStatement="DELETE FROM payroll_details WHERE header_id='$headerID' AND payconfig_id='$configID'";
            	$dbQ->execSQL("");
            	$sss['employee_share']=0.00;
            	$sss['employer_share']=0.00;
            }
            
           //PhilHealth
           $active=getid("payroll_config", "active", "government_req", "077004", $dbQ2);
           $pHealth['employee_share']=0.00;
           $pHealth['employer_share']=0.00;
           
           if($withGovDed)
            $pHealth=getPhilhealthContrib($basicPay);
           

           $configID=getid("payroll_config", "payconfig_id", "government_req", "077004", $dbQ2);
           $detailID=getid3("payroll_details","detail_id","header_id",$headerID,"payroll_id",$payid,"payconfig_id",$configID,$dbQ2);
           
           if($active==1){
            $values=array();
            $breakpoint="PhilHealth";
            if($detailID==""){
               $detailID=idnumSEQ("204","payroll_details","detail_id",$dbQ2,6,$detailSEQ);
              $detailSEQ++;
              $dbQ->sqlStatement="  INSERT INTO payroll_details(payroll_id,header_id,detail_id,payconfig_id,amount,income,sequence,employer_share) ".
                                  " VALUES(?,?,?,?,?,?,?,?) ";
              $values=array($payid,$headerID,$detailID,$configID, $pHealth['employee_share'],0,2,$pHealth['employer_share']);
            }else{
              $dbQ->sqlStatement=" UPDATE payroll_details SET amount=?,employer_share=? ".
                                 " WHERE payroll_id=? AND header_id=? AND detail_id=? ";
              $values=array($pHealth['employee_share'],$pHealth['employer_share'],$payid,$headerID,$detailID);
            }
            $dbQ->execSQL($values);
           }else{
           	 $dbQ->sqlStatement="DELETE FROM payroll_details WHERE header_id='$headerID' AND payconfig_id='$configID'";
           	 $dbQ->execSQL("");
           	 $pHealth['employee_share']=0.00;
           	 $pHealth['employer_share']=0.00;
           }
           
            //---PAGIBIG----------
           $active=getid("payroll_config", "active", "government_req", "077002", $dbQ2);
           $pagibigES=0.00;
           $pagibigER=0.00;
           if(($basicPay>=1000)&&($withGovDed)){
             $pagibigES=getid("system_configs","num_value","config_id","079002",$dbQ2);
             $pagibigER=getid("system_configs","num_value","config_id","079001",$dbQ2);
            }
              $configID=getid("payroll_config", "payconfig_id", "government_req", "077002", $dbQ2);
              $detailID=getid3("payroll_details","detail_id","header_id",$headerID,"payroll_id",$payid,"payconfig_id",$configID,$dbQ2);
                $values=array();
            $breakpoint="Pagibig ES:$pagibigES   ER:$pagibigER";
            if($active==1){
            if($detailID==""){
               $detailID=idnumSEQ("204","payroll_details","detail_id",$dbQ2,6,$detailSEQ);
              $detailSEQ++;
              $dbQ->sqlStatement="  INSERT INTO payroll_details(payroll_id,header_id,detail_id,payconfig_id,amount,income,sequence,employer_share) ".
                " VALUES(?,?,?,?,?,?,?,?) ";
                         $values=array($payid,$headerID,$detailID,$configID, $pagibigES,0,3,$pagibigER);
            }else{
              $dbQ->sqlStatement=" UPDATE payroll_details SET amount=?,employer_share=? ".
                                 " WHERE payroll_id=? AND header_id=? AND detail_id=? ";
              $values=array($pagibigES,$pagibigER,$payid,$headerID,$detailID);
            }
            $dbQ->execSQL($values);
            }else{
            	$dbQ->sqlStatement="DELETE FROM payroll_details WHERE header_id='$headerID' AND payconfig_id='$configID'";
            	$dbQ->execSQL("");
            	$pagibigES=0.00;
            }


            //BIR
            $grossNet=0.00;
            $taxDeductible=0.00;

           $taxDeductible = $pHealth['employee_share']+$sss['employee_share']+$pagibigES;
           $grossNet=($basicPay+$regHolidayPay+$splHolidayPay) -$taxDeductible;
           $breakpoint="BIR";
          $incomeTax=computeWithHoldingTax("semimonthly", $taxCode ,$grossNet );
          $configID=getid("payroll_config", "payconfig_id", "government_req", "077001", $dbQ2);
           $detailID=getid3("payroll_details","detail_id","header_id",$headerID,"payroll_id",$payid,"payconfig_id",$configID,$dbQ2);
            $values=array();
            if($detailID==""){
               $detailID=idnumSEQ("204","payroll_details","detail_id",$dbQ2,6,$detailSEQ);
              $detailSEQ++;

              $dbQ->sqlStatement="  INSERT INTO payroll_details(payroll_id,header_id,detail_id,payconfig_id,amount,income,sequence) ".
                                  " VALUES(?,?,?,?,?,?,?) ";
              $values=array($payid,$headerID,$detailID,$configID, $incomeTax,2,4);

            }else{
              $dbQ->sqlStatement=" UPDATE payroll_details SET amount=? ".
                                 " WHERE payroll_id=? AND header_id=? AND detail_id=? ";
                                
              $values=array($incomeTax,$payid,$headerID,$detailID);
              
            }
            $dbQ->execSQL($values);
        

            //-----CASH ADVANCES---------
                
            $dbQ2->sqlStatement="SELECT cashadvance_account,payconfig_id,balance,hold FROM cash_advance WHERE balance>? AND employee_id=? ";
            $values=array(0,$eid);
            $dbQ2->querySQL($values);
            
            $caData=$dbQ2->stmt->fetchAll(PDO::FETCH_ASSOC);
            $breakpoint="CA";
            $seq=5;
            for($ci=0;$ci<count($caData);$ci++){

                 $account=$caData[$ci]['cashadvance_account'];
                  $amount=$caData[$ci]['balance'];
                $configID=$caData[$ci]['payconfig_id'];
                    $hold=$caData[$ci]['hold'];
                $detailID=getid3("payroll_details","detail_id","header_id",$headerID,"payroll_id",$payid,"payconfig_id",$configID,$dbQ2);
                $values=array();
                $dbQ->sqlStatement=" DELETE FROM payroll_details ".
                        " WHERE payroll_id=? AND header_id=? AND cashadvance_account=? ";
                $values=array($payid,$headerID,$account);
                $dbQ->execSQL($values);


                if(($hold==0)&&($amount>0)){
                   $detailID=idnumSEQ("204","payroll_details","detail_id",$dbQ2,6,$detailSEQ);
                   $detailSEQ++;
                   $dbQ->sqlStatement="  INSERT INTO payroll_details(payroll_id,header_id,detail_id,payconfig_id,amount,income,sequence,cashadvance_account) ".
                                      "  VALUES(?,?,?,?,?,?,?,?) ";
                   $values=array($payid,$headerID,$detailID,$configID, $amount,0,$seq,$account);
                     $dbQ->execSQL($values);
                }
                $seq++;
               
             
              // 
             

            }//end of FOR CA


            //-----LOANS---------
            $breakpoint="LOANS";
            

            $dbQ2->sqlStatement="SELECT loan_account,payconfig_id,amortization,hold,loan_balance FROM loans WHERE loan_balance>? AND employee_id=? ";
            $values=array(0,$eid);
            $dbQ2->querySQL($values);
            $Data=$dbQ2->stmt->fetchAll(PDO::FETCH_ASSOC);
           // var_dump($Data);
            for($ci=0;$ci<count($Data);$ci++){
                 $account=$Data[$ci]['loan_account'];
                  $amount=$Data[$ci]['amortization'];
                $configID=$Data[$ci]['payconfig_id'];
                    $hold=$Data[$ci]['hold'];
             $loanBalance=$Data[$ci]['loan_balance'];
                $values=array();
                $dbQ->sqlStatement=" DELETE FROM payroll_details ".
                        " WHERE payroll_id=? AND header_id=? AND loan_account=? ";
                $values=array($payid,$headerID,$account);
                
                $dbQ->execSQL($values);  
             if($loanBalance<$amount)
             	$amount=$loanBalance;
             
               
                
                if(($hold==0)&&($amount>0)){
                   $detailID=idnumSEQ("204","payroll_details","detail_id",$dbQ2,6,$detailSEQ);
                   $detailSEQ++;
                   
                   $dbQ->sqlStatement="  INSERT INTO payroll_details(payroll_id,header_id,detail_id,payconfig_id,amount,income,sequence,loan_account) ".
                                      "  VALUES(?,?,?,?,?,?,?,?) ";
                   $values=array($payid,$headerID,$detailID,$configID, $amount,0,$seq,$account);
                    $dbQ->execSQL($values);
                  
                }
               
                $seq++;
               
            


            }//end of FOR LOANS




           }//End of If days>0

           $dbQ->commitTransaction();
     
        }//end of for loop
           
             

          $message="Work saved.";
          $status="success";
        }catch(Exception $e){
          $message=$e->getMessage()."-".$dbQ->sqlStatement."-".$eid."-".$breakpoint;
          $status="error";
        }
      $response=array("command"=>$cmd,"message"=>$message,"status"=>$status,"payval"=>$payval,"data"=>$govData);

  }else
  if($cmd=="deletewotu"){
      $eid=$value['eid'];
      $payid=$value['payid'];
      $headerID=getid2("payroll_header","header_id","employee_id",$eid,"payroll_id",$payid,$dbQ);
      try{
        $dbQ->beginTransaction();
        $dbQ->sqlStatement="DELETE FROM payroll_header WHERE payroll_id='$payid' and employee_id='$eid'";
        $dbQ->execSQL();
        $dbQ->sqlStatement="DELETE FROM payroll_details WHERE payroll_id='$payid' and header_id='$headerID'";
        $dbQ->execSQL();
        $message="Delete Sucessful.";
        $status="success";
        $dbQ->commitTransaction();
      }catch(Exception $e){
          $dbQ->rollBack();
          $message=$e->getMessage();
          $status="error";
      }
      $response=array("command"=>$cmd,"message"=>$message,"status"=>$status);
  }else
  if($cmd=="loadcreatedpayroll"){
      $branch=$value['branch'];
      if(!isset($value['payid'])||($value['payid']==""))
       $payid=getid("payroll","payroll_id","status","036002",$dbQ2);
      else
       $payid=$value['payid'];
       
      $paydates=getid("payroll","CONCAT(from_date,' to ',to_date)","payroll_id",$payid,$dbQ2);
      $colGrp=array();
      $colGrp[]=array("caption"=>"Employee","master"=>true);
      $colGrp[]=array("caption"=>"Rate","master"=>true);
      $colGrp[]=array("caption"=>"Days Work","master"=>true);
      $colGrp[]=array("caption"=>"Training","master"=>true);
      $colGrp[]=array("caption"=>"Reg. Holiday","master"=>true);
      $colGrp[]=array("caption"=>"Spl. Holiday","master"=>true);



      //---SET INCOME HEADER-------
      $dbQ->sqlStatement="SELECT payconfig_id,short_name FROM payroll_config WHERE income=1 ORDER BY short_name ";
      $dbQ->querySQL("");
      $income=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);

      $colGrp[]=array("caption"=>"INCOME","span"=>count($income)+4);
      $colGrp[]=array("caption"=>"Gross Pay","master"=>true);
      //---SET DEDUCTION HEADER-------
      $dbQ->sqlStatement="SELECT payconfig_id,short_name FROM payroll_config WHERE income=0 ORDER BY government_req DESC ";
      $dbQ->querySQL("");
      $deduction=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
      $colGrp[]=array("caption"=>"DEDUCTION","span"=>count($deduction));
      $colGrp[]=array("caption"=>"Total Deductions","master"=>true);
      $colGrp[]=array("caption"=>"Net Pay","master"=>true);



      $column=array();

      $column[]=array("field"=>"employee","caption"=>"Employee","size"=>"240px","sortable"=>true);
      $column[]=array("field"=>"rate","caption"=>"Rate","size"=>"75px");
      $column[]=array("field"=>"days","caption"=>"Days Work","size"=>"75px");
      $column[]=array("field"=>"training","caption"=>"Training","size"=>"75px");
      $column[]=array("field"=>"regular","caption"=>"Reg. Holiday","size"=>"75px");
      $column[]=array("field"=>"special","caption"=>"Spl. Holiday","size"=>"75px");
      $column[]=array("field"=>"basicpay","caption"=>"Basic Pay","size"=>"85px","render"=>"number:2");
      $column[]=array("field"=>"trainingpay","caption"=>"Training Pay","size"=>"85px","render"=>"number:2");
      $column[]=array("field"=>"lallow","caption"=>"Living Allowance","size"=>"85px","render"=>"number:2");
      $column[]=array("field"=>"regularpay","caption"=>"Reg. Hol. Pay","size"=>"85px","render"=>"number:2");
      $column[]=array("field"=>"specialpay","caption"=>"Spl. Hol. Pay","size"=>"85px","render"=>"number:2");


      for($i=0;$i<count($income);$i++){
          $column[]=array("field"=>$income[$i]['payconfig_id'],"caption"=>$income[$i]["short_name"],"size"=>"75px","render"=>"number:2");
      }

      $column[]=array("field"=>"grosspay","caption"=>"Gross Pay","size"=>"85px","render"=>"number:2");

      for($i=0;$i<count($deduction);$i++){
          $column[]=array("field"=>$deduction[$i]['payconfig_id'],"caption"=>$deduction[$i]["short_name"],"size"=>"75px","render"=>"number:2");
      }

      $column[]=array("field"=>"totaldeduction","caption"=>"Total Deduction","size"=>"95px","render"=>"number:2");
      $column[]=array("field"=>"netpay","caption"=>"Net Pay","size"=>"85px","render"=>"number:2");
      $column[]=array("field"=>"eid","hidden"=>true);
      $column[]=array("field"=>"headerid","hidden"=>true);
      $records=array();
      //--BASIC Pay---
       $dbQ->sqlStatement=" SELECT a.header_id,a.employee_id,a.employee_name,a.rate,a.days_work,b.payconfig_id,b.amount,b.income,a.basic_pay,a.reg_holiday,a.spl_holiday,a.reg_holiday_pay,a.spl_holiday_pay,a.living_allowance, a.status,a.training_days,a.training_pay FROM payroll_header a,payroll_details b ".
                          " WHERE a.payroll_id=$payid AND a.branch_id=$branch AND a.payroll_id=b.payroll_id AND a.header_id=b.header_id ".
                          " ORDER BY a.employee_name,b.income DESC ";

       $dbQ->querySQL("");
       $data=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
       $headerID="";
       $basicPay=0.00;
       $regHoliday=0.00;
       $splHoliday=0.00;
       $trainingPay=0.00;
       $grossPay=0.00;
       $totalDeduction=0.00;
       $netPay=0.00;
       $recordIndx=0;
       $items=array();
       $status="";
       for($i=0;$i<count($data);$i++){
         // echo $data[$i]['status'];
          if($headerID!=$data[$i]['header_id']){//NEW HEADER...

            $status=$data[$i]['status'];
            $headerID=$data[$i]['header_id'];
            if($i==0){
              $basicPay=$data[$i]['basic_pay'];
              $splHoliday=$data[$i]['spl_holiday_pay'];
              $regHoliday=$data[$i]['reg_holiday_pay'];
              $livingAllowance=$data[$i]['living_allowance'];
              $trainingPay=$data[$i]['training_pay'];
              $items=array("recid"=>$i+1,"headerid"=>$headerID, "eid"=>$data[$i]['employee_id'],"employee"=>$data[$i]['employee_name'],
              "rate"=>$data[$i]['rate'],"days"=>$data[$i]['days_work'],"basicpay"=>$basicPay,"lallow"=>$livingAllowance, "regular"=>$data[$i]['reg_holiday'],"special"=>$data[$i]['spl_holiday'] ,"regularpay"=>$regHoliday,"specialpay"=>$splHoliday,"training"=>$data[$i]['training_days'],"trainingpay"=>$trainingPay);
               
               if($data[$i]['income']==1){
                   $grossPay=$grossPay+$basicPay+$regHoliday+$splHoliday+$livingAllowance+ $data[$i]['amount']+$trainingPay;
              
                $items[$data[$i]['payconfig_id']]=$data[$i]['amount'];
               }else
               if($data[$i]['income']==0){
                  $grossPay=$grossPay+$basicPay+$regHoliday+$splHoliday+$livingAllowance+$trainingPay;
                 if($grossPay>0){
                    $items["grosspay"]=$grossPay;
                    $netPay=$grossPay;
                    $grossPay=0;
                  }
                 $totalDeduction=$totalDeduction+$data[$i]['amount'];
                 $items[$data[$i]['payconfig_id']]=$data[$i]['amount'];

               }else
                 $grossPay=$grossPay+$basicPay+$regHoliday+$splHoliday+$livingAllowance+$trainingPay;  

            }else{//SAME header
               //echo "xx:".$status;
               $items["totaldeduction"]=$totalDeduction;
               $items["netpay"]=$netPay-$totalDeduction;

               if($status==""){
                   if($items['netpay']<0)
                     $items["style"]="color:#ff1515";
                   else
                     $items["style"]="color:#000000";
               }else{
                  if($items['netpay']<0)
                    $items["style"]="background-color:#ffe5e5; color:#00c321";
                  else
                    $items["style"]="color:#00722a";
               }
               for($ci=0;$ci<count($column);$ci++){
                  if(!isset($colum[$ci]['hidden'])){
                      if(!isset($items[$column[$ci]['field']])){
                          $items[$column[$ci]['field']]=0.00;
                      }
                  }
               }

               $records[]=$items;



               //---NEW ITEM----
                 $basicPay=0.00;
                 $grossPay=0.00;
                 $totalDeduction=0.00;
                 $netPay=0.00;


               $basicPay=$data[$i]['basic_pay'];
              $splHoliday=$data[$i]['spl_holiday_pay'];
              $regHoliday=$data[$i]['reg_holiday_pay'];
              $livingAllowance=$data[$i]['living_allowance'];
              $trainingPay=$data[$i]['training_pay'];
               $items=array("recid"=>$i+1,"headerid"=>$headerID, "eid"=>$data[$i]['employee_id'],"employee"=>$data[$i]['employee_name'],
              "rate"=>$data[$i]['rate'],"days"=>$data[$i]['days_work'],"basicpay"=>$basicPay,"lallow"=>$livingAllowance, "regular"=>$data[$i]['reg_holiday'],"special"=>$data[$i]['spl_holiday'] ,"regularpay"=>$regHoliday,"specialpay"=>$splHoliday,"training"=>$data[$i]['training_days'],
                  "trainingpay"=>$data[$i]['training_pay']);
            
               
               if($data[$i]['income']==1){
                $grossPay=$grossPay+$basicPay+$regHoliday+$splHoliday+$livingAllowance+ $data[$i]['amount']+$trainingPay;

                $items[$data[$i]['payconfig_id']]=$data[$i]['amount'];
               }else
               if($data[$i]['income']==0){
                   
                  $grossPay=$grossPay+$basicPay+$regHoliday+$splHoliday+$livingAllowance+$trainingPay;
                  if($grossPay>0){
                    $items["grosspay"]=$grossPay;
                    $netPay=$grossPay;
                    $grossPay=0;
                   }
                  $totalDeduction=$totalDeduction+$data[$i]['amount'];
                  $items[$data[$i]['payconfig_id']]=$data[$i]['amount'];

                }else
                  $grossPay=$grossPay+$basicPay+$regHoliday+$splHoliday+$livingAllowance+$trainingPay;                    


            }


          }else{
             if($data[$i]['income']==0){//DEDUCTIONS--
               if($grossPay>0){
                  $items["grosspay"]=$grossPay;
                  $netPay=$grossPay;
                  $grossPay=0;

               }

               $totalDeduction=$totalDeduction+$data[$i]['amount'];
               $items[$data[$i]['payconfig_id']]=$data[$i]['amount'];
              }else{
                 $grossPay=$grossPay+$data[$i]['amount'];
                 $items[$data[$i]['payconfig_id']]=$data[$i]['amount'];
              }


          }



       }//END OF FOR LOOP

               $items["totaldeduction"]=$totalDeduction;
               $items["netpay"]=$netPay-$totalDeduction;


              //echo "xxx:".$status;
              if($status==""){
                   if($items['netpay']<0)
                     $items["style"]="color:#ff1515";
                   else
                     $items["style"]="color:#000000";
               }else{
                  if($items['netpay']<0)
                    $items["style"]="background-color:#ffe5e5; color:#00c321";
                  else
                    $items["style"]="color:#00722a";
               }


              for($ci=0;$ci<count($column);$ci++){
                  if(!isset($colum[$ci]['hidden'])){
                      if(!isset($items[$column[$ci]['field']])){
                          $items[$column[$ci]['field']]=0.00;
                      }
                  }
               }
               
               $records[]=$items;
               
               
               


       $response=array("command"=>$cmd,"colgrp"=>$colGrp,"col"=>$column,"records"=>$records,"paydate"=>$paydates);



  }else
  if($cmd=="payrollchecked"){
      $payid=getid("payroll","payroll_id","status","036002",$dbQ2);
      $brid=$value['branch'];
      $data=$payroll->getPayrollDetails($payid,$brid);
      $dbQ->sqlStatement=" UPDATE payroll_header SET gross_pay=?,total_deductions=?,net_pay=?,status=? ".
                         " WHERE header_id=? AND payroll_id=? ";
      
      try{
        $dbQ->beginTransaction();
       
        for($i=0;$i<count($data);$i++){
           $values=array($data[$i]['grosspay'],$data[$i]['totaldeduction'],$data[$i]['netpay'],
                       "039002",$data[$i]['headerid'],$payid);
           if($data[$i]['headerid']!=0)
             $dbQ->execSQL($values);
           
        }
        $dbQ->commitTransaction();
        $messsage="Payroll Checked!";
        $status="success";
      }catch(Exception $e){
         $dbQ->rollBack();
         $messsage="Error in checking payroll. Error message:".$e->getMessage();
         $status="error";
      }
      $response=array("command"=>$cmd,"message"=>$messsage,"status"=>$status);

  }else
  if($cmd=="loadpayrollsummary"){
      $orangeDB=$orange['db'];
      if(!isset($value['payid'])||($value['payid']==""))
       $payid=getid("payroll","payroll_id","status","036002",$dbQ2);
      else
       $payid=$value['payid'];
      
      $payDates=getid("payroll","concat(from_date,' to ',to_date)","payroll_id",$payid,$dbQ2);
      $dbQ->sqlStatement="SELECT id,name FROM $orangeDB.ohrm_location ORDER BY name";
      $dbQ->querySQL("");
      $locations=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
      $columns=array();
      $columns[]=array("field"=>"id","hidden"=>true);
      $columns[]=array("field"=>"status_id","hidden"=>true);
      $columns[]=array("field"=>"branch","caption"=>"Branch","size"=>"100%");
      $columns[]=array("field"=>"status","caption"=>"Status","size"=>"100%");
      $payTypeID=getid("system_configs","str_value","config_id","078002",$dbQ2);
      $payTypes=$payroll->orangeHRMpayTypes($payTypeID);

      for($i=0;$i<count($payTypes);$i++){
          $columns[]=array("field"=>$payTypes[$i],"caption"=>$payTypes[$i],"size"=>"100%","render"=>"number:2");
      }
      //GOVERNMENT CONTRIBUTIONS
      $columns[]=array("field"=>"sss","caption"=>"SSS","size"=>"100%","render"=>"number:2");
      $columns[]=array("field"=>"phealth","caption"=>"Phil.Health","size"=>"100%","render"=>"number:2");
      $columns[]=array("field"=>"pagibig","caption"=>"Pag-ibig","size"=>"100%","render"=>"number:2");
      
      $columns[]=array("field"=>"total","caption"=>"Total","size"=>"100%","render"=>"number:2");
      $records=array();
      $grandTotal=0.00;
      $sssTOTAL=0.00;
      $phealthTOTAL=0.00;
      $pagibigTOTAL=0.00;
      $ptypeTotal=array();
      for($i=0;$i<count($locations);$i++){
            $locID=$locations[$i]['id'];
          $locName=$locations[$i]['name'];
          $payStatusID=getid2("payroll_header","status","payroll_id",$payid,"branch_id",$locID,$dbQ2);
          $payStatus=getid("system_configs","config_name","config_id",$payStatusID,$dbQ2);
          $tmpRecord=array();
          $tmpRecord["recid"]=count($records)+1;
          $tmpRecord["id"]=$locID;
          $tmpRecord["branch"]=$locName;
          $tmpRecord["status"]=$payStatus;
          $tmpRecord["status_id"]=$payStatusID;
          $totalPayroll=0.00;

          for($pi=0;$pi<count($payTypes);$pi++){
              $ptype=$payTypes[$pi];
              $dbQ->sqlStatement="SELECT SUM(net_pay) as total FROM payroll_header WHERE payroll_id='$payid' AND branch_id=$locID AND pay_type='$ptype'";
             //   echo $dbQ->sqlStatement;
              $dbQ->querySQL("");
              $amount=$dbQ->stmt->fetch(PDO::FETCH_ASSOC);
              if($amount['total']==null)
              $amount['total']=0.00;
              $tmpRecord[$ptype]=$amount['total'];
              $totalPayroll=$totalPayroll+$amount['total'];
              $ptypeTotal[$ptype]=$ptypeTotal[$ptype]+$amount['total'];
             // echo $ptypeTotal[$ptype].";";
          }
          $tmpRecord['sss']=sumGovernmentContrib($payid,$locID, '077005', $dbQ);
          $tmpRecord['phealth']=sumGovernmentContrib($payid,$locID, '077004', $dbQ);
          $tmpRecord['pagibig']=sumGovernmentContrib($payid,$locID, '077002', $dbQ);
          
          $tmpRecord['total']=$totalPayroll;
          
          $sssTOTAL=$sssTOTAL+$tmpRecord['sss'];
          $phealthTOTAL=$phealthTOTAL+$tmpRecord['phealth'];
          $pagibigTOTAL=$pagibigTOTAL+$tmpRecord['pagibig'];
          $grandTotal=$grandTotal+$totalPayroll;
          $records[]=$tmpRecord;

      }
      $summary=array();
      $summary=array("recid"=>"S1","branch"=>"GRAND TOTAL","total"=>$grandTotal,"summary"=>true);
      for($si=0;$si<count($payTypes);$si++){
         $summary[$payTypes[$si]]=$ptypeTotal[$payTypes[$si]];
      }
      $summary['sss']=$sssTOTAL;
      $summary['phealth']=$phealthTOTAL;
      $summary['pagibig']=$pagibigTOTAL;
      $records[]=$summary;


      $response=array("command"=>$cmd,"header"=>"Payroll Summary for ".$payDates, "columns"=>$columns,"records"=>$records,"total"=>count($records),"paydates"=>$payDates);



  }else
  if($cmd=="payrollapprove"){
       $payid=getid("payroll","payroll_id","status","036002",$dbQ2);
       $status="039002";//CHECKED STATUS
       $approve="039003";
       $data=$value['payroll'];
       $selected=$value['selected'];
       $branch=array();
   
       try{
      
        if($selected==0){
              $dbQ->beginTransaction();    
              
         for($i=0;$i<count($data);$i++){ 
           if(($data[$i]['status_id']==$status)||($data[$i]['status_id']==$approve)){
               
               $brid=$data[$i]['id'];
             
               $dbQ->sqlStatement="UPDATE payroll_header SET status=? WHERE payroll_id=? AND branch_id=? ";
               $values=array($approve,$payid,$brid);
               $dbQ->execSQL($values);
              
           
            
               
           }//end of if $status
         }
         $dbQ->commitTransaction();
              $messsage="Payroll Approved!";
           $status="success";
         
        }else//end of selected==1
        if($selected==1){
            
            if(($data['status_id']==$status)||($data['status_id']==$approve)){
               $dbQ->beginTransaction();   
               $brid=$data['id'];
               $dbQ->sqlStatement="UPDATE payroll_header SET status=? WHERE payroll_id=? AND branch_id=? ";
              // echo $dbQ->sqlStatement;
               $values=array($approve,$payid,$brid);
               $dbQ->execSQL($values);
               $dbQ->commitTransaction();
           }
        }
        
       
        $messsage="Payroll Approved!";
        $status="success";
         
        
      }catch(Exception $e){
        $dbQ->rollBack();
        $messsage="Error in approving payroll. Error message:".$e->getMessage();
        $status="error";
      }
      //--POST LOANS AND CA---
         
       if($selected==0){
     
         for($i=0;$i<count($data);$i++)
             if(($data['status_id']==$status)||($data['status_id']==$approve)){
               $brid=$data[$i]['id'];
               $dbQ->sqlStatement="  SELECT amount,payconfig_id,loan_account,cashadvance_account FROM payroll_details ".
                                 "  WHERE  (loan_account<>'' OR cashadvance_account<>'') AND ".
                                  " header_id IN (SELECT header_id FROM payroll_header WHERE payroll_id='$payid' AND branch_id=$brid) ";
                    
              $dbQ->querySQL("");
              $xdata=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
              for($pi=0;$pi<count($xdata);$pi++){
                if($xdata[$pi]['loan_account']!=""){
                	
                   $payroll->postToLoan($xdata[$pi]['loan_account'],$payid,$xdata[$pi]['amount'],$xdata[$pi]['payconfig_id']);
                }else
                if($xdata[$pi]['cashadvance_account']!=""){
                  $payroll->postToCA($xdata[$pi]['cashadvance_account'],$payid,$xdata[$pi]['amount'],$xdata[$pi]['payconfig_id']);
                }
             }//end of for pi
            }//end of if status
          
        }else//end of if $selected==0
         if($selected==1){
            if(($data['status_id']==$status)||($data['status_id']==$approve)){
               $brid=$data['id'];
                $dbQ->sqlStatement="  SELECT amount,payconfig_id,loan_account,cashadvance_account FROM payroll_details ".
                                 "  WHERE  (loan_account<>'' OR cashadvance_account<>'') AND ".
                                  " header_id IN (SELECT header_id FROM payroll_header WHERE payroll_id='$payid' AND branch_id=$brid) ";
              
              $dbQ->querySQL("");
              $xdata=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
              for($pi=0;$pi<count($xdata);$pi++){
                if($xdata[$pi]['loan_account']!=""){
                  $payroll->postToLoan($xdata[$pi]['loan_account'],$payid,$xdata[$pi]['amount'],$xdata[$pi]['payconfig_id']);
                }else
                if($xdata[$pi]['cashadvance_account']!=""){
                  $payroll->postToCA($xdata[$pi]['cashadvance_account'],$payid,$xdata[$pi]['amount'],$xdata[$pi]['payconfig_id']);
                }
             }//end of for pi
            }
         }
       $response=array("command"=>$cmd,"message"=>$messsage,"status"=>$status);
       
      
  }else
  if($cmd=="displaynetperpaytype"){
      $data=$payroll->displayPerPayType($value['id'], $value['type']);
      $response=array("command"=>$cmd,"data"=>$data);
  }else
  if($cmd=="changeamort"){
    $data=array($value['amort'],$value['account']);
    $dbQ->sqlStatement="UPDATE loans SET amortization=? WHERE loan_account=?";
    $message="Change amortization successful!";
    $status="success";
    try{
     $dbQ->beginTransaction();
     $dbQ->execSQL($data);
     $dbQ->commitTransaction();
    }catch(Exception $e){
      $dbQ->rollBack();
      $message="Error updating! ".$e->getMessage();
      $status="error";
      
    }
    $response=array("command"=>$cmd,"message"=>$message,"status"=>$status);
  }


  $dbQ->closeConnection();
  print json_encode($response);
