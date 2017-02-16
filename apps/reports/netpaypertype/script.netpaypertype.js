    var payrolURL="../../../api/api.payrollprocess.php";
    var sysURL="../../../api/api.systemconfig.php";
    var empURL="../../../api/api.employees.php";
    var paydates="";
    var selectedBranch="";
    var approveConfig={
        tabs:{
            name:'netpaytabs',
            active:'tab-netpay',
            tabs:[
              {id:'tab-netpay',caption:'Net Pay Report'},
              
            ],
            onClick:function(e){
                $("div[id^=div-tab-]").hide();
                $("#div-"+e.target).show();
                w2ui['netpaygrid'].refresh();
              
            }
        },
    
       netPayGrid:{
            name:'netpaygrid',
            show:{toolbar:false,selectColumn:true,header:true},
            header:'Net Pay Report',
            multiSelect:false,
            columns:[
              {field:'id',caption:'Employee ID',size:'100%'},
              {field:'name',caption:'Name',size:'100%'},
              {field:'type',caption:'Pay Type',size:'100%'},
              {field:'accnt',caption:'Account #',size:'100%'},
              {field:'net',caption:'Net Pay',size:'100%',render:'number:2'},
              
              
            ]   
         
            
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
          if(jobj.command=="loadpayrollheader"){
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
          }else
          if(jobj.command=="getpaytypes"){
          	clearCombo("txt-pay-types");
          	var data=[];
              for(i=0;i<jobj.data.length;i++){
                var tmp={id:"",name:""};
                console.log(jobj.data[i]);
                tmp.id=jobj.data[i];
                tmp.name=jobj.data[i];
                data[i]=tmp;	
              }
              var list={"data":data};
              loadJSONToCombo(list,"txt-pay-types","-Select Pay Types-");
          }else
          if(jobj.command=="displaynetperpaytype"){
          	w2ui['netpaygrid'].clear();
          	w2ui['netpaygrid'].records=jobj.data;
          	w2ui['netpaygrid'].refresh();
          }
       
         
          
        }
      });  
   }
  $("document").ready(function(){
   $("#div-tabs-netpay").w2tabs(approveConfig.tabs);
   $("#grid-netpay").w2grid(approveConfig.netPayGrid);
   
   processAjax(payrolURL,"cmd=loadpayrollheader");
   processAjax(sysURL,{cmd:"getpaytypes"});
   
   $("select").change(function(e){
   	 if(e.target.id=="txt-pay-types"){
   	 	processAjax(payrolURL,{cmd:'displaynetperpaytype',id:$("#txt-paydates").val(),type:$("#txt-pay-types").val()});
   	 }
   });
   
   $("input[id^=btn-]").click(function(e){
          console.log(e);
        
          if(e.target.id=="btn-download"){
            if(confirm("Download?")){
                    var item="";
               for(row=0;row<w2ui['netpaygrid'].records.length;row++){
                  var field="",value="",caption="";
              
                  var rec="";
                  field=w2ui['netpaygrid'].columns[2].field;
                  caption=w2ui['netpaygrid'].columns[2].caption;
                  value=w2ui['netpaygrid'].records[row][field];
                  rec='"'+caption+'":"'+value+'"';
               //   console.log(field+","+caption+","+value);
                  item=item+'{'+rec;
                  for(col=1;col<w2ui['netpaygrid'].columns.length;col++){
                          field=w2ui['netpaygrid'].columns[col].field;
                          caption=w2ui['netpaygrid'].columns[col].caption;
                          value=w2ui['netpaygrid'].records[row][field];
                          if((field!="id")&&(field!="status_id")){
                            if(value==null)
                              value="";
                          rec=',"'+caption+'":"'+value+'"';
                 //           console.log(rec);
                          item=item+rec;
                          }
                  } 
                  if(row<w2ui['netpaygrid'].records.length-1)
                    item=item+'},';
                  else
                    item=item+'}';
                }   
                
                
                
                //------------------
                
                var records='['+item+']';
                console.log(records);
                var recObj = JSON.parse(records);
                var reportTitle=w2ui['netpaygrid'].header;
                
               JSONToCSVConvertor(recObj,reportTitle,true,reportTitle); 
             }
          }
          
   });
   
   
    
});
