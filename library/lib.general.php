<?php
  function addTrailingZero($curLen,$seqL){
    	$zero="";
		//echo $seqL;
    	for($i=0;$i<$seqL-$curLen;$i++)
		  $zero=$zero."0";
		return $zero;
    }	

 function getid($tbl,$fldid,$fld,$val,$dbquery){
     
	 
	
	  $dbquery->sqlStatement="select $fldid from $tbl where $fld='$val'";
	  $dbquery->querySQL();
	  $data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);
	    
      
      return $data[$fldid];
  }
  
  function getid2($tbl,$fldid,$fld1,$val1,$fld2,$val2,$dbquery){
     
	  //$tmp="";
	// echo "select $fldid from  $tbl where $fld='$val'";
	   $dbquery->sqlStatement="select $fldid from $tbl where $fld1='$val1' and $fld2='$val2'";
	   $dbquery->querySQL();
	   $data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);
	   return $data[$fldid];
  }
  
  function getid3($tbl,$fldid,$fld1,$val1,$fld2,$val2,$fld3,$val3, $dbquery){
     
      //$tmp="";
    // echo "select $fldid from  $tbl where $fld='$val'";
       $dbquery->sqlStatement="select $fldid from $tbl where $fld1='$val1' and $fld2='$val2' and $fld3='$val3' ";
       $dbquery->querySQL();
       $data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);
       return $data[$fldid];
  }
  
  
  function idnum($init,$table,$field,$dbquery,$seqLen){
    //
  
	$curdate=getdate();
	$tmpyr=$curdate['year'];
	$year=substr($tmpyr,-2);
	$prefix=$init.$year;
	$sql="select $field from $table where $field like '$prefix%'  order by $field desc limit 1";
	$dbquery->sqlStatement=$sql;
	$dbquery->querySQL();
	$data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);
	$tmpid="";
	$curid="";
	$curid=$data[$field];
	 
	 if($curid!=""){
	  // echo "ID:".$curid;	
	   $curid=substr($curid,-$seqLen);
	   $curid=$curid+1;
	   
	   $tmp=addTrailingZero(strlen($curid),$seqLen).$curid;
	   
	   
	   $tmpid=$prefix . $tmp;
	   
	}else{
	  $tmpid=$prefix . addTrailingZero(1,$seqLen)."1";
	}
	
	
	return $tmpid;
     
	 
  }
  
   function idnumSEQ($init,$table,$field,$dbquery,$seqLen,$curSEQ){
    //
  
    $curdate=getdate();
    $tmpyr=$curdate['year'];
    $year=substr($tmpyr,-2);
    $prefix=$init.$year;
    $sql="select $field from $table where $field like '$prefix%'  order by $field desc limit 1";
    $dbquery->sqlStatement=$sql;
    $dbquery->querySQL();
    $data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);
    $tmpid="";
    $curid="";
    $curid=$data[$field];
     
     if($curid!=""){
      // echo "ID:".$curid; 
       $curid=substr($curid,-$seqLen);
       $curid=$curid+1;
       $curid=$curid+$curSEQ;
    //   echo "xx:".$curid;
       //str_pad($curid, $seqLen);
       $tmp=addTrailingZero(strlen($curid),$seqLen).$curid;
       
       
       $tmpid=$prefix . $tmp;
       
    }else{
      $tmpid=$prefix . addTrailingZero(1,$seqLen).$curSEQ+1;
    }
    
    
    return $tmpid;
     
     
  }
  
  
  
  
  function idnumNoYear($init,$table,$field,$dbquery,$seqLen){
    //
  
	$prefix=$init;
	$sql="select $field from $table where $field like '$prefix%'  order by $field desc limit 1";
	$dbquery->sqlStatement=$sql;
	$dbquery->querySQL();
	$data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);
	$tmpid="";
	$curid="";
	$curid=$data[$field];
	 
	 if($curid!=""){
	  // echo "ID:".$curid;	
	   $curid=substr($curid,-$seqLen);
	   $curid=$curid+1;
	   
	   $tmp=addTrailingZero(strlen($curid),$seqLen).$curid;
	   
	   
	   $tmpid=$prefix . $tmp;
	   
	}else{
	  $tmpid=$prefix . addTrailingZero(1,$seqLen)."1";
	}
	
	
	return $tmpid;
     
	 
  }
  
  
  
  function getCurrentDateTime(){
     $curdate=getdate();
	 return $curdate['year']."-".$curdate['mon']."-".$curdate['mday']." ".$curdate["hours"].":".$curdate["minutes"].":".$curdate["seconds"];
  }
  
   function getCurrentDate(){
     $curdate=getdate();
	 return $curdate['year']."-".$curdate['mon']."-".$curdate['mday'];
  }
	
  
	
  function transID($init,$table,$field,$dbquery,$seqLen){
    //
   
	$curdate=getdate();
	$yr=$curdate['year'];
	$mm=$curdate['mon'];
	$dd=$curdate['mday'];
	$dateVal=$yr+$mm+$dd;
	$prefix=$init.$dateVal;
	$sql="select $field from $table where $field like '$prefix%'  order by $field desc limit 1";
	$dbquery->sqlStatement=$sql;
	$dbquery->querySQL();
	$data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);
	$tmpid="";
	$curid="";
	$curid=$data[$field];
	 
	 if($curid!=""){
	  // echo "ID:".$curid;	
	   $curid=substr($curid,-$seqLen);
	   $curid=$curid+1;
	   
	   $tmp=addTrailingZero(strlen($curid),$seqLen).$curid;
	   
	   
	   $tmpid=$prefix . $tmp;
	   
	}else{
	  $tmpid=$prefix . addTrailingZero(1,$seqLen)."1";
	}
	
	
	return $tmpid;
     
	 
  }	
  
  function transCode($init,$table,$field,$dbquery,$seqLen){
    //
   
	
	 $dt=getdate();
	 $dateTime=$dt['year']+$dt['mon']+$dt['mday']+$dt['hours']+$dt['minutes']+$dt['seconds'];
 	 $prefix=$init.$dateTime;
	$sql="select $field from $table where $field like '$prefix%'  order by $field desc limit 1";
	$dbquery->sqlStatement=$sql;
	$dbquery->querySQL();
	$data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);
	$tmpid="";
	$curid="";
	$curid=$data[$field];
	 
	 if($curid!=""){
	  // echo "ID:".$curid;	
	   $curid=substr($curid,-$seqLen);
	   $curid=$curid+1;
	   
	   $tmp=addTrailingZero(strlen($curid),$seqLen).$curid;
	   
	   
	   $tmpid=$prefix . $tmp;
	   
	}else{
	  $tmpid=$prefix . addTrailingZero(1,$seqLen)."1";
	}
	
	
	return $tmpid;
     
	 
  }	
  
  function getFieldValues($tbl,$flds,$fldid,$fldvalue,$dbquery){
		
		 $dbquery->sqlStatement="select $flds from $tbl where $fldid='$fldvalue'";
		 
		 $dbquery->querySQL("");
	     $data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);
		
		return json_encode($data);
	}
  
  function getFieldValues2($tbl,$flds,$fldid,$fldvalue,$fldid2,$fldvalue2,$dbquery){
		
		 $dbquery->sqlStatement="select $flds from $tbl where $fldid='$fldvalue'".
		                  " and $fldid2='$fldvalue2' ";
		 
		 $dbquery->querySQL("");
	     $data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);
		
		return json_encode($data);
	}
	
 function deleteItem($tbl,$fld,$value,$dbquery){
 	 $sql="delete from $tbl where $fld='$value'";
		
		    $dbquery->sqlStatement = $sql;
			try{
  	 		 $dbquery->beginTransaction();
	     	 $dbquery->execSQL();
	 		 $dbquery->commitTransaction();
			 $response=array("status"=>"0","message"=>"Delete successfull.","sql"=>$sql);
    		}catch (Exception $e){
     	 	 $dbQuery->rollBack();
			 $response=array("status"=>"1","message"=>"Failed: " . $e->getMessage());
     		 //$this->errorMessage= ;
    		}
			return json_encode($response);
			
 }
 
 function isLeapYear($yr){
 		return ((($yr % 4) == 0) && ((($yr % 100) != 0) || (($yr %400) == 0)));
 }
 
 function getDayInMonth($date){
 	   $newDate=strtotime(date("Y-m-d", strtotime($date)));
    		  $day=date("d",$newDate);
	  return $day;  	
 }
 
 
  function getArrayValues($data){
  	$values=array();
	for($i=0;$i<count($data);$i++){
		$values[]=$data[$i]['value'];
	}
	return $values;
  }
  
  function filterNumber($numStr){
  	$val="";
  	for($i=0;$i<strlen($numStr);$i++){
  		if(($numStr[$i]!=",")&&($numStr[$i]!="P")&&($numStr[$i]!=" ")){
  			$val=$val.$numStr[$i];
  		}
  	}
	return $val;
  }
  
  function actionLogs($values,$dbQ){
      $dbQ->sqlStatement="  INSERT INTO payroll_logs(payroll_id,header_id,detail_id,payconfig_id,user_id,status,action_cmd)".
                          " VALUES(?,?,?,?,?,?,?)";
      $dbQ->execSQL($values);
  
  }
  
  function sumGovernmentContrib($payrollID,$branch,$govID,$dbquery){
            $itemID= getid("payroll_config", "payconfig_id","government_req" , $govID, $dbquery);
            
            $sql=" SELECT SUM(a.amount+a.employer_share) as total FROM payroll_details a,payroll_header b ".
                 " WHERE a.header_id=b.header_id AND b.payroll_id='$payrollID' AND b.branch_id=$branch AND a.payconfig_id='$itemID' "; 
            $dbquery->sqlStatement = $sql;
            $dbquery->querySQL("");  
            $data=$dbquery->stmt->fetch(PDO::FETCH_ASSOC);
            if($data['total']=='')
              $data['total']=0.00;
            return $data["total"];
            
 }
function isJSON($string){
   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}
?>
