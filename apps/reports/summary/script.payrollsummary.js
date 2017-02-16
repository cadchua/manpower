$("document").ready(function(e){
    var payrolURL="../../../api/api.payrollprocess.php";
	var sysURL="../../../api/api.systemconfig.php";
	var empURL="../../../api/api.employees.php";
	var paydates="";
	var selectedBranch="";
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
					  processAjax(payrolURL,"cmd=loadcreatedpayroll&branch="+rec.id+"&payid="+$("#txt-paydates").val());
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
			 cmd:'loadpayrollsummary',
			 payid:''
			 
			},
			onLoad:function(e){
				//console.log(e);
				var json=JSON.parse(e.xhr.responseText);
				paydates=json.paydates;
				console.log(paydates);
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
              // processAjax(empURL,"cmd=loadphoto&id="+rec.eid);
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
              
          
          }if(jobj.command=="loadpayrollheader"){
              clearCombo("txt-paydates");
              var data=[];
              for(i=0;i<jobj.records.length;i++){
                var tmp={id:"",name:""};
                tmp.id=jobj.records[i].id;
                tmp.name=jobj.records[i].from+" to "+jobj.records[i].to+" ["+jobj.records[i].status+"]";
                data[i]=tmp;	
              }
              var list={"data":data};
              loadJSONToCombo(list,"txt-paydates","-Select Paryoll Dates-");
          }else{
          	   w2ui['summarypaygrid'].reload();
               notifymessage(jobj.message,jobj.status,"topRight",1500);
          } 
         
          
        }
      });  
   }
   $("#div-tabs-approvepayroll").w2tabs(approveConfig.tabs);
   $("#grid-payrollsummary").w2grid(approveConfig.summaryPayGrid);
   $("#grid-payrolldetails").w2grid(approveConfig.detailGrid);
   
   processAjax(payrolURL,"cmd=loadpayrollheader");
   
   $("input[id^=btn-]").click(function(e){
   	      console.log(e);
   	      if(e.target.id=="btn-download-details"){
   	  	     if(confirm("Download?")){
   	  	         var id=w2ui['summarypaygrid'].getSelection();
				      var rec=w2ui['summarypaygrid'].get(id);
				      console.log(rec);
				      var item="";
   	  	        for(row=0;row<w2ui['detailgrid'].records.length;row++){
   	  	          var field="",value="",caption="";
   	  	          
   	  	          var rec="";
   	  	          field=w2ui['detailgrid'].columns[0].field;
   	  	          caption=w2ui['detailgrid'].columns[0].caption;
   	  	          value=w2ui['detailgrid'].records[row][field];
   	  	          rec='"'+caption+'":"'+value+'"';
   	  	          item=item+'{'+rec;
   	  	          for(col=1;col<w2ui['detailgrid'].columns.length;col++){
   	  	                  field=w2ui['detailgrid'].columns[col].field;
   	  	                  caption=w2ui['detailgrid'].columns[col].caption;
   	  	                  value=w2ui['detailgrid'].records[row][field];
   	  	                  if((field!="eid")&&(field!="headerid")){
   	  	          	      rec=',"'+caption+'":"'+value+'"';
   	  	          	      item=item+rec;
   	  	          	      }
   	  	          }	
   	  	          if(row==w2ui['detailgrid'].records.length-1)
   	  	            item=item+'}';
   	  	          else
   	  	            item=item+'},';
   	  	        }	
   	  	     	var records='['+item+']';
   	  	     	console.log(records);
   	  	     	var recObj = JSON.parse(records);
   	  	     	var reportTitle=w2ui['detailgrid'].header +" ["+paydates+"]";
   	  	     	
			   JSONToCSVConvertor(recObj,reportTitle,true,reportTitle);	
			 }
			 
		  }else
		  if(e.target.id=="btn-download-summary"){
		  	if(confirm("Download Summary?")){
		  		    var item="";
   	  	       for(row=0;row<w2ui['summarypaygrid'].records.length;row++){
   	  	          var field="",value="",caption="";
   	  	      
   	  	          var rec="";
   	  	          field=w2ui['summarypaygrid'].columns[2].field;
   	  	          caption=w2ui['summarypaygrid'].columns[2].caption;
   	  	          value=w2ui['summarypaygrid'].records[row][field];
   	  	          rec='"'+caption+'":"'+value+'"';
   	  	       //   console.log(field+","+caption+","+value);
   	  	          item=item+'{'+rec;
   	  	          for(col=3;col<w2ui['summarypaygrid'].columns.length;col++){
   	  	                  field=w2ui['summarypaygrid'].columns[col].field;
   	  	                  caption=w2ui['summarypaygrid'].columns[col].caption;
   	  	                  value=w2ui['summarypaygrid'].records[row][field];
   	  	                  if((field!="id")&&(field!="status_id")){
   	  	                  	if(value==null)
   	  	                  	  value="";
   	  	          	      rec=',"'+caption+'":"'+value+'"';
   	  	         // 	      console.log(rec);
   	  	          	      item=item+rec;
   	  	          	      }
   	  	          }	
   	  	        
   	  	            item=item+'},';
   	  	        }	
   	  	        
   	  	        //--------SUMMARY---------------
   	  	        
   	  	          field=w2ui['summarypaygrid'].columns[2].field;
   	  	          caption=w2ui['summarypaygrid'].columns[2].caption;
   	  	          value=w2ui['summarypaygrid'].summary[0][field];
   	  	          rec='"'+caption+'":"'+value+'"';
   	  	          console.log(field+","+caption+","+value);
   	  	          item=item+'{'+rec;
   	  	        
   	  	        for(col=2;col<w2ui['summarypaygrid'].columns.length;col++){
   	  	                  field=w2ui['summarypaygrid'].columns[col].field;
   	  	                  caption=w2ui['summarypaygrid'].columns[col].caption;
   	  	                  value=w2ui['summarypaygrid'].summary[0][field];
   	  	                  if((field!="id")&&(field!="status_id")){
   	  	                  	if(value==null)
   	  	                  	  value="";
   	  	          	      rec=',"'+caption+'":"'+value+'"';
   	  	          	      console.log(rec);
   	  	          	      item=item+rec;
   	  	          	      }
   	  	          }	
   	  	        
   	  	            item=item+'}';
   	  	        
   	  	        
   	  	        //------------------
   	  	        
   	  	     	var records='['+item+']';
   	  	     	console.log(records);
   	  	     	var recObj = JSON.parse(records);
   	  	     	var reportTitle=w2ui['summarypaygrid'].header;
   	  	     	
			   JSONToCSVConvertor(recObj,reportTitle,true,reportTitle);	
			 }
		  }
		  
   });
   
   $("#txt-paydates").change(function(e){
   	 w2ui['summarypaygrid'].postData['payid']=$(this).val();
   	 w2ui['summarypaygrid'].reload();
   });
		
	
});
