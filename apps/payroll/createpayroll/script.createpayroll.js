$("document").ready(function(e){
	var payrolURL="../../../api/api.payrollprocess.php";
	var sysURL="../../../api/api.systemconfig.php";
	 var empURL="../../../api/api.employees.php";


	var createPayConfig={
		tabs:{
			name:'createpaytabs',
			active:'tab-createpayroll',
			tabs:[
			  {id:'tab-createpayroll',caption:'Create Payroll'},
			//  {id:'tab-checkpayroll',caption:'Check Payroll'}
			],
			onClick:function(e){
				$("div[id^=div-tab-]").hide();
				$("#div-"+e.target).show();
				w2ui['checkgrid'].refresh();
				w2ui['createpaygrid'].refresh();
			}
		},
		createPayToolbar:{
			name:'createpaytoolbar',
			items:[
			  {type:'button',id:'btn-new',caption:'New Payroll'},
			  {type:'break'},
			  {type:'button',id:'btn-activate',caption:'Activate Payroll'},
			  {type:'break'},
			  {type:'button',id:'btn-cancel',caption:'Cancel Payroll'},
			  {type:'break'},
			  {type:'button',id:'btn-reload',caption:'Reload'},

			],
			onClick:function(e){
				if(e.target=="btn-new"){
					$("#dlg-newpayroll").dialog("open");
				}else
				if(e.target=="btn-reload"){
					w2ui['createpaygrid'].reload();
				}else
				if(e.target=="btn-activate"){
					var id=w2ui['createpaygrid'].getSelection();
					var rec=w2ui['createpaygrid'].get(id);
					if(confirm("Activate selected payroll "+rec.from+" to "+rec.to)){
						processAjax(payrolURL,"cmd=activatepayroll&payid="+rec.id);
					}
				}
			}
		},
		createPayGrid:{
			name:'createpaygrid',
			url:payrolURL,
			show:{toolbar:false,selectColumns:true},
			multiSelect:false,

			columns:[
			  {field:'id',hidden:true},
			  {field:'from',caption:'From',size:'100%'},
			  {field:'to',caption:'To',size:'100%'},
			  {field:'days',caption:'Days Work',size:'100%'},
			  {field:'status',caption:'Status',size:'100%'},
			  
			],
			postData:{
			 cmd:'loadpayrollheader'
			}

		},
		//---Process Payroll Config---
		checkGrid:{
			name:'checkgrid',
			//  show:{toolbar:true,header:true,selectColumn:true,footer:true,toolbarColumns:false},
			show:{toolbar:true,selectColumn:true,header:true,footer:true,toolbarColumns:false},
			multiSelect:false,
			header:'Payroll',
			onSelect:function(e){
			   var rec=w2ui['checkgrid'].get(e.recid);
               $("#lbl-employee-id").html(rec.eid);
               $("#lbl-employee").html(rec.employee);
               //$("#lbl-jobtitle").html(rec.job);
               processAjax(empURL,"cmd=loadphoto&id="+rec.eid);
			}

		},
		checkPayToolbar:{
			name:'checktoolbar',
			items:[
			  {type:'html',id:'branchlist',html:'Branch <select id="txt-branch"></select>'},
			  {type:'break'},
			  {type:'button',id:'btn-load-branch',caption:'Load Payroll'},
			  {type:'break'},
			  {type:'button',id:'btn-check',caption:'Checked'},


			],
			onClick:function(e){
				if(e.target=="btn-load-branch"){
				   processAjax(payrolURL,"cmd=loadcreatedpayroll&branch="+$("#txt-branch").val());
				}else
				if(e.target=="btn-check"){
					if(confirm("Payroll Checked?")){
						param={cmd:"payrollchecked",payroll:w2ui['checkgrid'].records};
						processAjax(payrolURL,param);
					}
				}
			}
		},

		//---JQUERY---
		newPayDlg:{
			height:175,
			width:300,
			autoOpen:false,
			modal:true,
			buttons:{
				"Create":function(e){
					if(confirm("Create Payroll for "+$("#txt-date-from").val()+" to "+$("#txt-date-to").val()+"?")){
						var param="cmd=createpayroll&from="+$("#txt-date-from").val()+
						"&to="+$("#txt-date-to").val()+"&days="+$("#txt-work-days").val();
						processAjax(payrolURL,param);
					}
				},
				"Close":function(e){
					$(this).dialog("close");
				}

			}

		}

	};

	//--------AJAX PROCESSING--------
   function processAjax(url,param){
     $.ajax({
        type:"post",
        url:url,
        data:param,

        success:function(response){
        //  console.log(response);
          jobj=JSON.parse(response);

          if(jobj.command=="createpayroll"){
              notifymessage(jobj.message,jobj.status,"topRight",3000);

          }else
          if(jobj.command=="activatepayroll"){
          	if(jobj.status=="success")
          	w2ui['createpaygrid'].reload();
          	notifymessage(jobj.message,jobj.status,"topRight",3000);

          }else
          if(jobj.command=="locations"){
          	loadJSONToCombo(jobj,"txt-branch","-Select Department-");
          }else
          if(jobj.command=="loadcreatedpayroll"){
          	 $("#img-employee").attr("src","");
          	 $("#lbl-employee-id").html("");
             $("#lbl-employee").html("");
          	 w2ui['checkgrid'].clear();
             w2ui['checkgrid'].columnGroups=jobj.colgrp;
             w2ui['checkgrid'].columns=jobj.col;
             w2ui['checkgrid'].records=jobj.records;
             w2ui['checkgrid'].refresh();
          }else
          if(jobj.command=="loadphoto"){
             $("#img-employee").attr("src","data:image/jpeg;base64,"+jobj.img);


          }else
          if(jobj.command=="payrollchecked"){
          	notifymessage(jobj.message,jobj.status,"topRight",3000);
          	 processAjax(payrolURL,"cmd=loadcreatedpayroll&branch="+$("#txt-branch").val());

          }else{
              notifymessage(jobj.message,jobj.status,"topRight",1500);
          }


        }
      });

   }

	//-------Initiate W2UI-----
	$("#div-tabs-createpayroll").w2tabs(createPayConfig.tabs);
	$("#toolbar-createpayroll").w2toolbar(createPayConfig.createPayToolbar);
	$("#grid-createpayroll").w2grid(createPayConfig.createPayGrid);
	$("#grid-checkpayroll").w2grid(createPayConfig.checkGrid);
	$("#toolbar-checkpayroll").w2toolbar(createPayConfig.checkPayToolbar);

	//--initialize jquery---
	$("#dlg-newpayroll").dialog(createPayConfig.newPayDlg);
	createDatePicker("txt-date-from");
	createDatePicker("txt-date-to");
	//--------AJAX----
	processAjax(sysURL,"cmd=locations");


});
