
	var sysURL="../../../api/api.systemconfig.php";
    var empURL="../../../api/api.employees.php";
    var payURL="../../../api/api.payrollprocess.php";
    var wotuConfig={
       mainTab:{
           name:'maintab',
           tabs:[
            {id:'tab1',caption:'Employee Days Work'}
           ]
           
       } ,
       employeeGrid:{
       	name:'employeegrid',
       show:{toolbar:true,header:true,selectColumn:true,footer:true,toolbarColumns:false},
       	multiSelect:false,
       	header:'Active Employees',
       
       	columns:[
       	 {field:'id',hidden:true},
       	 {field:'name',caption:'Name',size:'180px'},
       	 {field:'job',caption:'Job Title',size:'160px'},
       	 {field:'days',caption:'Days Work',size:'80px',editable:{type:'float'}},
         {field:'training',caption:'Tr. Days',size:'80px',editable:{type:'float'}},
       	 {field:'regular',caption:'Reg. Hol',size:'80px',editable:{type:'float'}},
       	 {field:'special',caption:'Spl. Hol',size:'80px',editable:{type:'float'}},
       	  
       	  
       	],
       	onSelect:function(e){
       		
            var rec=w2ui['employeegrid'].get(e.recid);
            $("#lbl-employee-id").html(rec.id);
            $("#lbl-employee").html(rec.name);
            $("#lbl-jobtitle").html(rec.job);
            processAjax(empURL,"cmd=loadphoto&id="+rec.id);
          
       	},	
       	onKeydown:function(e){
      		console.log('kcode:'+e.originalEvent.keyCode);
      		var kcode=e.originalEvent.keyCode;
      		if(kcode==113){
      			w2ui['emptoolbar'].click('btn-save');
      		}else
      		if(kcode==46){
      			w2ui['emptoolbar'].click('btn-delete');
      		}
        }
      		
        
     } ,
     employeeToolbar:{
     	  name:'emptoolbar',  
          items:[
          {type:'html',id:'txt-search',html:'<input type="text" id="txt-search" value="" placeholder="SEARCH EMPLOYEE" />'},
          {type:'html',id:'txt-branch',html:'<select  id="txt-branch"  ><option value="">-Select Branch-</option> </select>'},
          {type:'html',id:'txt-status',html:'<select  id="txt-status"   ><option value="">-Select Status-</option> </select>'},
          {type:'button',id:'btn-search',caption:'SEARCH'},
          {type:'break'},
      	  {type:'button',id:'btn-save',caption:'[F2] - SAVE'},
      	  {type:'break'},
      	  {type:'button',id:'btn-delete',caption:'[DEL] - DELETE'},
          ],
          onClick:function(e){
              console.log(e);
              if(e.target=="btn-search"){
            	  $("#progressbar").css("display","");
              	  w2ui['employeegrid'].clear();
              	  $("#img-employee").attr("src","");
              	  $("#lbl-employee-id").html("");
                  $("#lbl-employee").html("");
                  $("#lbl-jobtitle").html(""); 
                  processAjax(empURL,"cmd=searchemployee&term="+$("#txt-search").val()+"&status="+$("#txt-status").val()+"&location="+$("#txt-branch").val());
              }else
              if(e.target=="btn-save"){
      			w2ui['employeegrid'].save();
      			$("#progressbar").css("display","");
      			//1$("#lbl-processmessage").html("Processing employees work please wait...");
      			var data={cmd:"savewotu",payid:$("#lbl-payid").html(),employee:w2ui['employeegrid'].records};
      			processAjax(payURL,data);
      		}else
      		if(e.target=="btn-delete"){
      			$("#progressbar").css("display","");
      			$("#p-confirm-message").html("Delete work details?");
      			$("#div-confirm").dialog("open");
      		}
          } 
      },
      
      wotuToolbar:{
      	name:'wotutoolbar',
      	items:[
      	  {type:'break'},
      	  {type:'button',id:'btn-save',caption:'[F2] - SAVE'},
      	  {type:'break'},
      	  {type:'button',id:'btn-delete',caption:'[DEL] - DELETE'},
      	  {type:'break'},
      	//  {type:'button',id:'btn-active',caption:'[<-] - Go To Active Employees'},
      	],
      	onClick:function(e){
      	//	if(e.target=="btn-save"){
      		//	w2ui['wotudetail'].save();
      	//		var data={cmd:"savewotu",payid:$("#lbl-payid").html(),employee:w2ui['employeegrid'].records};
      	//		processAjax(payURL,data);
      	//	}else
      		if(e.target=="btn-delete"){
      			$("#p-confirm-message").html("Delete payroll record?");
      			$("#div-confirm").dialog("open");
      		}//else
      		//if(e.target=="btn-active"){
      	//		w2ui['employeegrid'].columnClick('id');
      //		}
      	}
      },
      wotuDetail:{
      	name:'wotudetail',
      	header:'Work,Overtime,Tardiness and Undertime',
      	show:{header:true,columnHeaders:false},
      	columns:[
      	   {field:'id',hidden:true},
      	   {field:'payfield',hidden:true},
      	   {field:'name',caption:'Field',size: '150px', 
      	    style: 'background-color: #efefef; border-bottom: 1px solid white; padding-right: 5px;', 
      	    attr: "align=right" },
      	   {field:'value',caption: 'Value', size: '100%',editable:{type:"mone"}}
      	],
      	onKeydown:function(e){
      		console.log('kcode:'+e.originalEvent.keyCode);
      		var kcode=e.originalEvent.keyCode;
      		if(kcode==113){
      			w2ui['wotutoolbar'].click('btn-save');
      		}else
      		if(kcode==46){
      			w2ui['wotutoolbar'].click('btn-delete');
      		}else
      		if(kcode==37){
      			w2ui['wotutoolbar'].click('btn-active');
      		}
      		
      	}
      	
      },
      confirmDialog:{
      	autoOpen:false,
      	resizable:false,
      	height:160,
      	modal:true,
      	buttons:{
      		"Delete":function(e){
      			processAjax(payURL,"cmd=deletewotu&eid="+$("#lbl-employee-id").html()+"&payid="+$("#lbl-payid").html());
      			$(this).dialog("close");
      		},
      		"Cancel":function(e){
      			$(this).dialog("close");
      		}
      		
      	}
      	
      }
       
    };
    
    
    //---------AJAX
    
    //----Process Ajax-----------
   function processAjax(url,param){
     $.ajax({
        type:"post",
        url:url,
        data:param,
          
        success:function(response){
        //  console.log(response);
          jobj=JSON.parse(response);
          if(jobj.command=="loadactivepayroll"){
          	$("#lbl-payid").html(jobj.payid);
          	$("#lbl-paydate").html(jobj.from+" to "+jobj.to);
          }else
          if(jobj.command=="searchemployee"){
              w2ui['employeegrid'].clear();
              w2ui['employeegrid'].records=jobj.employees;
              w2ui['employeegrid'].total=jobj.employees.length;
              w2ui['employeegrid'].refresh();
              loadWOTU();
              
          }else
          if(jobj.command=="loadphoto"){
             $("#img-employee").attr("src","data:image/jpeg;base64,"+jobj.img);
              
          
          }else 
          if(jobj.command=="empstatus"){
            loadJSONToCombo(jobj,"txt-status","-Select Status-");	 
          }else
          if(jobj.command=="loadwotu"){
          	for(i=0;i<jobj.data.length;i++){
          		w2ui['employeegrid'].set(jobj.data[i].recid,{days:jobj.data[i].days});
          		w2ui['employeegrid'].set(jobj.data[i].recid,{regular:jobj.data[i].regular});
          		w2ui['employeegrid'].set(jobj.data[i].recid,{special:jobj.data[i].special});
                w2ui['employeegrid'].set(jobj.data[i].recid,{training:jobj.data[i].training});
          	}
       
          }else
          if(jobj.command=="savewotu"){
          	notifymessage(jobj.message,jobj.status,"topRight",3000);
          	$("#lbl-processmessage").html("");
          }else
          if(jobj.command=="deletewotu"){
          	  notifymessage(jobj.message,jobj.status,"topRight",1500);
          	  loadWOTU();
          }else
          if(jobj.command=="locations"){
          	loadJSONToCombo(jobj,"txt-branch","-Select Department-");
          }else{
              notifymessage(jobj.message,jobj.status,"topRight",1500);
          }
          $("#progressbar").css("display","none");
          
        }
      });  
        
   }
    
    function loadWOTU(){
    	  var param={cmd:"loadwotu",pid:$("#lbl-payid").html(),employee:w2ui['employeegrid'].records};
                processAjax(payURL,param);
    }
  $("document").ready(function(){  
    //---Initialize W2UI

    $("#div-tab-wotu").w2tabs(wotuConfig.mainTab);
    $("#grid-employees").w2grid(wotuConfig.employeeGrid);
    $("#toolbar-employees").w2toolbar(wotuConfig.employeeToolbar);
    $("#grid-wotu").w2grid(wotuConfig.wotuDetail);
    $("#toolbar-wotu").w2toolbar(wotuConfig.wotuToolbar);
    $("#progressbar").progressbar({value:false});
    //-JQUERY---
    $("#div-confirm").dialog(wotuConfig.confirmDialog);
    
    processAjax(payURL,"cmd=loadactivepayroll");
    processAjax(sysURL,"cmd=empstatus");
  
    processAjax(sysURL,"cmd=locations");
  
});
