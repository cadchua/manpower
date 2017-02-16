<?php

  class SQLconstructor{
    
      private $tblInfo;
      private $tblName;
      private $keyFields;
      private $dbQ;
      function __construct($dbQ){
         $this->dbQ=$dbQ;
         //var_dump($this->tblInfo)
      }      
      
      function setTableInfo($table){
         $this->tblName=$table;
         $this->dbQ->sqlStatement="DESCRIBE $table";
         $this->dbQ->querySQL("");
         $this->tblInfo=$this->dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
          
      }
      
      function setKeyFields($fields){//Array of Key Fields
          $this->keyFields=null;
          $this->keyFields=$fields;
      }
      
      
      function insertStatement($customFields){
         $sql="";    
         if(count($customFields)>0){//------CUSTOME FIELDS
            $sql="INSERT INTO ".$this->tblName."(".$customFields[0];
            $vSQL="VALUES(?";
            for($i=1;$i<count($customFields);$i++){
              $sql=$sql.",".$customFields[$i];
              $vSQL=$vSQL.",?";
            }
             $sql=$sql.") ";
            $vSQL=$vSQL.")";
             $sql=$sql." ".$vSQL;  
         }else{//-----FROM DB----------  
          $sql="INSERT INTO ".$this->tblName."(".$this->tblInfo[0]["Field"];
          $vSQL="VALUES(?";

        for($i=1;$i<count($this->tblInfo);$i++){
          if(($this->tblInfo[$i]["Type"]!="timestamp")&&($this->tblInfo[$i]["Extra"]!="auto_increment")){
            $sql=$sql.",".$this->tblInfo[$i]["Field"];
            $vSQL=$vSQL.",?";
          }
        }
         $sql=$sql.") ";
         $vSQL=$vSQL.")";
         $sql=$sql." ".$vSQL;
        }
        return $sql; 
       
      }
      
      function updateStatement($customFields){
          $sql="UPDATE $this->tblName SET ";
          if(count($customFields)>0){
            
             $sql=$sql. $customFields[0]." = ?";
              for($cf=1;$cf<count($customFields);$cf++){
                  $sql=$sql.",".$customFields[$cf]." = ?";
                
              } 
          }else{//end of customFields
            $fcount=0;
            for($i=0;$i<count($this->tblInfo);$i++){
              if(($this->tblInfo[$i]["Type"]!="timestamp")&&($this->tblInfo[$i]["Extra"]!="auto_increment")){
                     
                 if(!in_array($this->tblInfo[$i]["Field"], $this->keyFields)){
                   if($fcount==0) 
                     $sql=$sql.$this->tblInfo[$i]["Field"]."= ? ";
                   else
                     $sql=$sql.",".$this->tblInfo[$i]["Field"]."= ? ";
                   $fcount++;
                 }
           
              }  
            }
          }
          
          
          //---ADD CONDITIONS---
          $sql=$sql." WHERE ".$this->keyFields[0]."= ? ";
          for($i=1;$i<count($this->keyFields);$i++){
              $sql=$sql." AND ".$this->keyFields[$i]."= ? ";
          }
          
          return $sql;
          
      }
      
      function deleteStatement(){
          $sql="DELETE FROM $this->tblName WHERE ".$this->keyFields[0]."= ? ";
          for($i=1;$i<count($this->keyFields);$i++){
              $sql=$sql." AND ".$this->keyFields[$i]."= ? ";
          }
          return $sql;
      }
      
      
      
  }
  
  

?>