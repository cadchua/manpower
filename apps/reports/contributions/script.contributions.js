$("document").ready(function(){    
    var sysURL="../../../api/api.systemconfig.php";
    var empURL="../../../api/api.employees.php";
    var payURL="../../../api/api.payrollprocess.php";
    var wotuConfig={
       mainTab:{
           name:'maintab',
           tabs:[
            {id:'tab1',caption:'Employee Days Work Report'}
           ]
           
       } ,
       branchGrid:{
       	name:'branchgrid',
        show:{toolbar:true,header:true,selectColumn:true,footer:true,toolbarColumns:false},
       	multiSelect:false,
       	header:'List of Branches',
       
       	columns:[
       	 {field:'id',hidden:true},
       	 {field:'branch',caption:'Name',size:'185px'}
       	],
       	
        
     } 
  };  
  //--------Initialize W2UI-----------
  $("#div-tab-dayswork").w2tabs(wotuConfig.mainTab);
 

});