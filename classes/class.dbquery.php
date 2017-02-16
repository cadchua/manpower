<?php
class dbQuery{
	 private $dbconn;
	 var $sqlStatement;
	 var $stmt;
	 var $connected;
	 
	 function connect($config){
		//echo "eat";
		$this->connected=false;
   	    $host=$config['host'];
		  $db=$config['db'];
		$user=$config['user'];
		$pass=$config['pass'];
		
		try{	
		  $this->dbconn=new PDO("mysql:host=$host;dbname=$db",$user,$pass);
	      $this->dbconn->exec("set names utf8");
	      $this->connected=true;
		}catch (PDOException $e) {
     			print "Error!: " . $e->getMessage() . "<br/>";
    			die();
   		}
		
	} //end of function
	
	function beginTransaction(){
		$this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbconn->beginTransaction();
	}
	
	function commitTransaction(){
		$this->dbconn->commit();
	}
	
	function rollBack(){
			 $this->dbconn->rollBack();
    }
	
	function execSQL($values){
		$paramCount=count($values);
		 $this->stmt = $this->dbconn->prepare($this->sqlStatement);
		 if($paramCount>0){
		    for($i=0;$i<$paramCount;$i++){
		      $this->stmt->bindParam($i+1,$values[$i]);
		    }	
		 }
		 
		 $this->stmt->execute();
		 
	}
	
	function querySQL($values){
		$paramCount=count($values);
		
  		 $this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $this->stmt = $this->dbconn->prepare($this->sqlStatement);
		  
		  if($paramCount>0){
		    for($i=0;$i<$paramCount;$i++){
		      $this->stmt->bindParam($i+1,$values[$i]);
		    }	
		  }
		
		    
		    $this->stmt->execute();
        	 
           
       
	}
	
	function lastInsertId($name = null){
		return $this->dbconn->lastInsertId($name);
	}
	
	function closeConnection(){
		$this->dbconn=null;
	}
} //end of class dbQuery

?>
