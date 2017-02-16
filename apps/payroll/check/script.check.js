$("document").ready(function(e){
    var payrolURL="../../../api/api.payrollprocess.php";
	var sysURL="../../../api/api.systemconfig.php";
	var empURL="../../../api/api.employees.php";
	
	
	var approveConfig={
		tabs:{
			name:'approvetabs',
			active:'tab-approvepayroll',
			tabs:[
			  {id:'tab-approvepayroll',caption:'Summary'},
			  {id:'tab-payrolldetails',caption:'Details'}
			],
			onClick:function(e){
				$("div[id^=div-tab-]").hide();
				$("#div-"+e.target).show();
				w2ui['summarypaygrid'].refresh();
				w2ui['detailgrid'].refresh();
				if(e.target=="tab-payrolldetails"){
					  var id=w2ui['summarypaygrid'].getSelection();
				      var rec=w2ui['summarypaygrid'].get(id);
					  w2ui['detailgrid'].header="Payroll Details of "+rec.branch;
					  processAjax(payrolURL,"cmd=loadcreatedpayroll&branch="+rec.id);
				}
			}
		},
	
		summaryPayGrid:{
			name:'summarypaygrid',
			url:payrolURL,
			show:{toolbar:false,selectColumn:true,header:true},
			header:'Payroll Summary',
			multiSelect:false,
			postData:{
			 cmd:'loadpayrollsummary'
			}
			
		},
		//---Process Payroll Config---
		detailGrid:{
			name:'detailgrid',
			show:{toolbar:true,selectColumns:true,header:true,footer:true,toolbarColumns:false},
			multiSelect:false,
			header:'Payroll Details',
			onSelect:function(e){
			   var rec=w2ui['detailgrid'].get(e.recid);
               $("#lbl-employee-id").html(rec.eid);
               $("#lbl-employee").html(rec.employee);
               processAjax(empURL,"cmd=loadphoto&id="+rec.eid);
			}
			
		},
   };//end of config
   
    function processAjax(url,param){
     $.ajax({
        type:"post",
        url:url,
        data:param,
          
        success:function(response){
        //  console.log(response);
          jobj=JSON.parse(response);
       
         
          if(jobj.command=="loadcreatedpayroll"){
          	 $("#img-employee").attr("src","");
          	 $("#lbl-employee-id").html("");
             $("#lbl-employee").html("");
          	 w2ui['detailgrid'].clear();
             w2ui['detailgrid'].columnGroups=jobj.colgrp;
             w2ui['detailgrid'].columns=jobj.col;
             w2ui['detailgrid'].records=jobj.records;
             w2ui['detailgrid'].refresh();
          }else
          if(jobj.command=="loadphoto"){
             $("#img-employee").attr("src","data:image/jpeg;base64,"+jobj.img);
              
          
          }else{
          	   w2ui['summarypaygrid'].reload();
              notifymessage(jobj.message,jobj.status,"topRight",1500);
          }
          $("#progressbar").css("display","none");
          
        }
      });  
   }
   $("#div-tabs-approvepayroll").w2tabs(approveConfig.tabs);
   $("#grid-payrollsummary").w2grid(approveConfig.summaryPayGrid);
   $("#grid-payrolldetails").w2grid(approveConfig.detailGrid);
   $("#progressbar").progressbar({value:false});
   
   
   
   $("input[id^=btn-]").click(function(e){
   	      console.log(e);
   	      if(e.target.id=="btn-check"){
   	  	     if(confirm("Payroll Checked?")){
   	  	    	 		$("#progressbar").css("display","");
   	  	    	          var id=w2ui['summarypaygrid'].getSelection();
   	  	    	          var rec=w2ui['summarypaygrid'].get(id);
				  		  param={cmd:"payrollchecked",branch:rec.id};
						  processAjax(payrolURL,param);
					console.log("TOTAL:"+w2ui['detailgrid'].records.length);
			 }
			 
		  }
   });
		
	
});
