<?php
  class Payroll{
      var $dbQ;
      var $payFrequency;
      var $rateID;
      var $orangeDB;
      var $headerColumns;
      var $details;
      function getPayrate($empID){
          $rate=0;
          $this->dbQ->sqlStatement=" SELECT rate_id,daily_rate,monthly_rate,pay_frequency FROM employee_pay_rates ".
                             " WHERE employee_id='$empID'  ORDER BY effective_date DESC LIMIT 1";
          $this->dbQ->querySQL();
          $data=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
          $this->rateID=$data['rate_id'];
          $this->payFrequency=$data['pay_frequency'];
         // echo $this->dbQ->sqlStatement;
          if($data['daily_rate']>0)
            $rate=$data['daily_rate'];
          else
          if($data['monthly_rate']>0)
            $rate=$data['monthly_rate'];


          return $rate;
      }

      function orangeHRMpayRate($empID){
          $rate=0;
          $this->dbQ->sqlStatement=" SELECT id,ebsal_basic_salary as rate FROM ".$this->orangeDB.".hs_hr_emp_basicsalary ".
                             " WHERE emp_number='$empID' and salary_component!='ALLOWANCE' ORDER BY id DESC LIMIT 1";
          $this->dbQ->querySQL();
          $data=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
          $this->rateID=$data['id'];
          $this->payFrequency="";
          $rate=$data['rate'];


          return $rate;
      }


      function orangeHRMAllowance($empID){
          $rate=0;
          $this->dbQ->sqlStatement=" SELECT id,ebsal_basic_salary as rate FROM ".$this->orangeDB.".hs_hr_emp_basicsalary ".
                             " WHERE emp_number='$empID' and salary_component='ALLOWANCE' ORDER BY id DESC LIMIT 1";
          $this->dbQ->querySQL();
          $data=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
          $this->rateID=$data['id'];
          $this->payFrequency="";
          $rate=$data['rate'];


          return $rate;
      }

      function orangeHRMLocation($empID){
          /*
           * SELECT emp_number FROM $orangeDB.hs_hr_emp_locations WHERE location_id=$locationID
           */
          $location="";
          $this->dbQ->sqlStatement=" SELECT location_id as id FROM ".$this->orangeDB.".hs_hr_emp_locations ".
                             " WHERE emp_number='$empID' ";
          $this->dbQ->querySQL();
          $data=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
          $location=$data['id'];
          return $location;


      }

      function orangeHRMCustomField($empID,$customID){
          $custom="";
          $this->dbQ->sqlStatement="  SELECT custom$customID as id FROM ".$this->orangeDB.".hs_hr_employee ".
                                    " WHERE employee_id='$empID' ";
          $this->dbQ->querySQL();
          $data=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
          $custom=$data['id'];
          if($custom==NULL)
            $custom="";
          return $custom;

      }

      function orangeHRMpayTypes($customID){
          $payTypes=array();
          $this->dbQ->sqlStatement=" SELECT extra_data FROM ".$this->orangeDB.".hs_hr_custom_fields ".
                                   " WHERE field_num=$customID ";
      
          $this->dbQ->querySQL();
          $tmp=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
          //echo $tmp['extra_data'];
          $payTypes=explode(",",$tmp['extra_data']);
          //var_dump($payTypes);
          return $payTypes;
      }
      
      function postToLoan($accntNum,$refNum,$amount,$payConfig){
           $trCode="037002";
           $this->dbQ->sqlStatement="SELECT credit FROM loans_ledger WHERE loan_account='$accntNum' and reference_no='$refNum'";
           $this->dbQ->querySQL();
           $data=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
           $sql=array();
           $creditAmount=$data['credit'];
           $proceed=true;
         
           if($creditAmount>0){//Existing POST
             $this->dbQ->beginTransaction();
             $sql[]="UPDATE loans SET loan_balance=loan_balance+$creditAmount WHERE loan_account='$accntNum'";
             $sql[]="DELETE FROM loans_ledger WHERE loan_account='$accntNum' AND reference_no='$refNum' ";
             try{
              $this->dbQ->sqlStatement=$sql[0];
              $this->dbQ->execSQL("");
              $this->dbQ->sqlStatement=$sql[1];
              $this->dbQ->execSQL("");
              $this->dbQ->commitTransaction();
              $proceed=true;
             }catch(Exception $e){
              $this->dbQ->rollBack();
              $proceed=false;
             }
             
             if($proceed){
                 
               $this->dbQ->beginTransaction();
               $this->dbQ->sqlStatement="UPDATE loans SET loan_balance=loan_balance-$amount WHERE loan_account='$accntNum'";
               $this->dbQ->execSQL("");
               $this->dbQ->commitTransaction();
              
               $this->dbQ->sqlStatement="SELECT loan_balance FROM loans WHERE loan_account='$accntNum' ";
               $this->dbQ->querySQL();
               $data=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
               $loanBalance=$data['loan_balance'];
              
               $this->dbQ->beginTransaction();
               $this->dbQ->sqlStatement="INSERT INTO loans_ledger(loan_account,payconfig_id,tr_date,tr_code,reference_no,debit,credit,balance) ".
                                        " VALUES('$accntNum','$payConfig',NOW(),'$trCode','$refNum',0,$amount,$loanBalance)";
               $this->dbQ->execSQL("");
               $this->dbQ->commitTransaction();
                    
             }
               
           }else{//NEW
           
              
           
                  
               $this->dbQ->beginTransaction();
               $this->dbQ->sqlStatement="UPDATE loans SET loan_balance=loan_balance-$amount WHERE loan_account='$accntNum'";
             
               $this->dbQ->execSQL("");
               $this->dbQ->commitTransaction();
              
               $this->dbQ->sqlStatement="SELECT loan_balance FROM loans WHERE loan_account='$accntNum' ";
               $this->dbQ->querySQL();
               $data=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
               $loanBalance=$data['loan_balance'];
              
               $this->dbQ->beginTransaction();
               $this->dbQ->sqlStatement="INSERT INTO loans_ledger(loan_account,payconfig_id,tr_date,tr_code,reference_no,debit,credit,balance) ".
                                        " VALUES('$accntNum','$payConfig',NOW(),'$trCode','$refNum',0,$amount,$loanBalance)";
            //   echo $this->dbQ->sqlStatement.";;";
               $this->dbQ->execSQL("");
               $this->dbQ->commitTransaction();
               
           }
      }//end of postToLoans
      
      function postToCA($accntNum,$refNum,$amount,$payConfig){
           $trCode="038002";
           $this->dbQ->sqlStatement="SELECT credit FROM ca_transactions WHERE cashadvance_account='$accntNum' and reference_no='$refNum'";
         
           $this->dbQ->querySQL();
           $data=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
           $sql=array();
           $creditAmount=$data['credit'];
         //  echo $creditAmount;
           $proceed=true;
           if($creditAmount>0){//Existing POST
             $this->dbQ->beginTransaction();
             $sql[]="UPDATE cash_advance SET balance=balance+$creditAmount WHERE cashadvance_account='$accntNum'";
            // $sql[]="DELETE FROM ca_transactions WHERE cashadvance_account='$accntNum' AND reference_no='$refNum' ";
             try{
              $this->dbQ->sqlStatement=$sql[0];
              $this->dbQ->execSQL("");
           //   $this->dbQ->sqlStatement=$sql[1];
             // $this->dbQ->execSQL("");
              $this->dbQ->commitTransaction();
              $proceed=true;
             }catch(Exception $e){
              $this->dbQ->rollBack();
              $proceed=false;
             }
             
             if($proceed){
                 
               $this->dbQ->beginTransaction();
               $this->dbQ->sqlStatement="UPDATE cash_advance SET balance=balance-$amount WHERE cashadvance_account='$accntNum'";
               $this->dbQ->execSQL("");
               $this->dbQ->commitTransaction();
              
               $this->dbQ->sqlStatement="SELECT balance FROM cash_advance WHERE cashadvance_account='$accntNum' ";
               $this->dbQ->querySQL();
               $data=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
               $balance=$data['balance'];
              
               $this->dbQ->beginTransaction();
               $this->dbQ->sqlStatement="INSERT INTO ca_transactions(cashadvance_account,payconfig_id,tr_date,tr_code,reference_no,debit,credit,balance) ".
                                        " VALUES('$accountNum','$payConfig',NOW(),'$trCode','$refNum',0,$amount,$balance)";
               $this->dbQ->execSQL("");
               $this->dbQ->commitTransaction();
                    
             }
               
           }else{//NEW
           
              
           
                  
               $this->dbQ->beginTransaction();
               $this->dbQ->sqlStatement="UPDATE cash_advance SET balance=balance-$amount WHERE cashadvance_account='$accntNum'";
               $this->dbQ->execSQL("");
               $this->dbQ->commitTransaction();
              
               $this->dbQ->sqlStatement="SELECT balance FROM cash_advance WHERE cashadvance_account='$accntNum' ";
               $this->dbQ->querySQL();
               $data=$this->dbQ->stmt->fetch(PDO::FETCH_ASSOC);
               $balance=$data['balance'];
              
               $this->dbQ->beginTransaction();
               $this->dbQ->sqlStatement="INSERT INTO ca_transactions(cashadvance_account,payconfig_id,tr_date,tr_code,reference_no,debit,credit,balance) ".
                                        " VALUES('$accntNum','$payConfig',NOW(),'$trCode','$refNum',0,$amount,$balance)";
               $this->dbQ->execSQL("");
               $this->dbQ->commitTransaction();
               
           }
      }//end of postToCA
      
      function displayPerPayType($payID,$type){
          $this->dbQ->sqlStatement="SELECT employee_id,employee_name,pay_type,account_no,net_pay FROM payroll_header ".
                " WHERE payroll_id='$payID' AND pay_type='$type' ORDER BY employee_name ";
          $this->dbQ->querySQL("");
          $result=$this->dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
          $data=array();
          for($i=0;$i<count($result);$i++){
              $data[]=array("recid"=>$i+1,
                            "id"=>$result[$i]['employee_id'],
                            "name"=>$result[$i]['employee_name'],
                            "type"=>$result[$i]['pay_type'],
                            "accnt"=>$result[$i]['account_no'],
                            "net"=>$result[$i]['net_pay']);
          }
          return $data;
      }

      function generatePayrollColumns($withGroup){ //Payroll Header with groups

      //---SET INCOME HEADER-------
       $this->dbQ->sqlStatement="SELECT payconfig_id,short_name FROM payroll_config WHERE income=1 ORDER BY short_name ";
       $this->dbQ->querySQL("");
       $income=$this->dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);

        //---SET DEDUCTION HEADER-------
       $this->dbQ->sqlStatement="SELECT payconfig_id,short_name FROM payroll_config WHERE income=0 ORDER BY government_req DESC ";
       $this->dbQ->querySQL("");
       $deduction=$this->dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
      
       $colGrp=array();
      if($withGroup){
       $colGrp[]=array("caption"=>"Employee","master"=>true);
       $colGrp[]=array("caption"=>"Rate","master"=>true);
       $colGrp[]=array("caption"=>"Days Work","master"=>true);
       $colGrp[]=array("caption"=>"Training","master"=>true);
       $colGrp[]=array("caption"=>"Reg. Holiday","master"=>true);
       $colGrp[]=array("caption"=>"Spl. Holiday","master"=>true);
       $colGrp[]=array("caption"=>"INCOME","span"=>count($income)+4);
       $colGrp[]=array("caption"=>"Gross Pay","master"=>true);
       $colGrp[]=array("caption"=>"DEDUCTION","span"=>count($deduction));
       $colGrp[]=array("caption"=>"Total Deductions","master"=>true);
       $colGrp[]=array("caption"=>"Net Pay","master"=>true);
      }


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
        $this->headerColumns=array("columnGroup"=>$colGrp,"columns"=>$column);
      }

      
      
      function getPayrollDetails($payID,$branch){
       $column=$this->headerColumns['columns'];
      	$records=array();
      	//--BASIC Pay---
      	$this->dbQ->sqlStatement=" SELECT a.header_id,a.employee_id,a.employee_name,a.rate,a.days_work,b.payconfig_id,b.amount,b.income,a.basic_pay,a.reg_holiday,a.spl_holiday,a.reg_holiday_pay,a.spl_holiday_pay,a.living_allowance, a.status,a.training_days,a.training_pay FROM payroll_header a,payroll_details b ".
      			" WHERE a.payroll_id=$payID AND a.branch_id=$branch AND a.payroll_id=b.payroll_id AND a.header_id=b.header_id ".
      			" ORDER BY a.employee_name,b.income DESC ";
        
      	
      	$this->dbQ->querySQL("");
      	$data=$this->dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
      	$headerID="";
      	$basicPay=0.00;
        $trainingPay=0.00;
      	$regHoliday=0.00;
      	$splHoliday=0.00;
      	$grossPay=0.00;
      	$totalDeduction=0.00;
      	$netPay=0.00;
      	$livingAllowance=0.00;
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
      						"rate"=>$data[$i]['rate'],"days"=>$data[$i]['days_work'],"basicpay"=>$basicPay,"lallow"=>$livingAllowance, "regular"=>$data[$i]['reg_holiday'],"special"=>$data[$i]['spl_holiday'] ,"regularpay"=>$regHoliday,"specialpay"=>$splHoliday,
                  "training"=>$data[$i]['training_days'],"trainingpay"=>$trainingPay);
      				 
      				if($data[$i]['income']==1){
      					$grossPay=$grossPay+$basicPay+$regHoliday+$splHoliday+$livingAllowance+ $data[$i]['amount']
                +$trainingPay;
      	
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
                  "trainingpay"=>$trainingPay);
      	
      				 
      				if($data[$i]['income']==1){
      					$grossPay=$grossPay+$basicPay+$regHoliday+$splHoliday+$livingAllowance+ $data[$i]['amount']
                +$trainingPay;
      	
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
      	return $records;
      	 
      }

  }

?>
