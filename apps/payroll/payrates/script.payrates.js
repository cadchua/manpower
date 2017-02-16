$("document").ready(function(){
   //--------API URL--------------
   var sysURL="../../../api/api.systemconfig.php";
   var empURL="../../../api/api.employees.php";
   var sssApiUrl= '../../../api/api.sss.php';
   var philhealthURL="../../../api/api.govtables.php";
   var birURL="../../../api/api.govtables.php";
   //----------W2UI and JQueryUI CONFIGURATION-----------
   var payrateConfig={
      //----W2UI---------
      payrateTab:{
          name:'payratetab',
          active:'tab1',
          tabs:[
          {id:'tab1',caption:'Pay Rates'}
          ]
          
      },
      employeeGrid:{
          name:'employeegrid',
          show:{lineNumbers:true,toolbar:true,toolbarColumns:false,
            toolbarReload:false,toolbarSearch:false,header:true},
       header:'Employee List',
        multiSelect:false,
        columns:[
           {field:'id',caption:'Employee ID',size:'140px'},
           {field:'name',caption:'Employee Name',size:'260px'},
           {field:'job',caption:'Job Title',size:'220px'},
          ],
        toolbar:{  
          items:[
          {type:'html',id:'txt-search',html:'<input type="text" id="txt-search" value="" placeholder="SEARCH EMPLOYEE" style="width:180px"/>'},
          {type:'html',id:'txt-search',html:'<select id="txt-branch"><option value="">-Select Branch-</option> </select>'},
          {type:'button',id:'btn-search',caption:'SEARCH'}
          ],
          onClick:function(e){
              console.log(e);
              if(e.target=="btn-search"){
                  processAjax(empURL,"cmd=searchemployee&term="+$("#txt-search").val());
              }
          } 
        },
        onDblClick:function(e){
            
            var id=w2ui['employeegrid'].getSelection();
            var rec=w2ui['employeegrid'].get(id);
            $("#lbl-employee-id").html(rec.id);
            $("#lbl-employee").html(rec.name);
            $("#lbl-jobtitle").html(rec.job);
            $("input[id^=txt-]").val("");
            w2ui['payrategrid'].refresh();
            processAjax(sysURL,"cmd=codesbygrp&group=033");
            processAjax(sysURL,"cmd=codesbygrp&group=035");
            processAjax(empURL,"cmd=loadphoto&id="+rec.id);
            $("#div-newpayrate").css("display","block");
            $("#dlg-payrates").dialog("open");
        }
      },
       payrateEffectiveTab:{
          name:'effectivetab',
          active:'tab1',
          tabs:[
          {id:'tab1',caption:'New Pay Rate'},
          {id:'tab2',caption:'Pay Rate History'}
          ],
          onClick:function(e){
               $("#div-newpayrate").css("display","none");
               $("#div-payratehistory").css("display","none");
              if(e.target=="tab1"){
                  $("#div-newpayrate").css("display","block");
              }else
              if(e.target=="tab2"){
                   w2ui['payrategrid'].refresh();
                   processAjax(empURL,"cmd=payratehistory&eid="+$("#lbl-employee-id").html());
                  $("#div-payratehistory").css("display","block");
              }
          }
           
       },
      
      SSSGrid:{
           
                    name: 'SSSgrid',
                    url: sssApiUrl,
                    header: "SSS Contributions",
                    show: {
                        header: true,
                    },
                    columns: [
                       {field: 'bracket_no', caption: 'Bracket', size: '20%'},
                       {field: 'salary', caption: 'Salary', size: '20%', render: 'number:2'},
                       {field: 'monthly_credit', caption: 'Monthly Credit', size: '20%', render: 'number:2'},
                       {field: 'employer_share', caption: 'Employer Share', size: '20%', render: 'number:2'},
                       {field: 'employee_share', caption: 'Employee Share', size: '20%', render: 'number:2'},
                       {field: 'total_contribution', caption: 'Total Contribution', size: '20%', render: 'number:2'},
                    ],
                    onDblClick:function(e){
                        var id=w2ui['SSSgrid'].getSelection();
                        var rec=w2ui['SSSgrid'].get(id);
                        console.log(rec);
                        $("#txt-sss").val(rec.bracket_no);
                        $("#dlg-sss").dialog("close");
                    }
               
            
    },
     philHealthGrid : {
        name: 'philhealthgrid',
        header: 'Philhealth',
        show: {
            header: true,
         
        },
      
        columns: [
            { field: 'bracket_no', caption: 'Pay Frequency', size: '20%', sortable: true, resizable: false},
            { field: 'salary_base', caption: 'Tax Code', size: '20%', resizable: false},
            { field: 'employer_share', caption: 'Column No', size: '20%', resizable: false},
            { field: 'employee_share', caption: 'Pay Rate', size: '20%', resizable: false},
            { field: 'total_contribution', caption: 'Tax Amount', size: '20%', resizable: false}
        ],
        onDblClick:function(e){
            var id=w2ui['philhealthgrid'].getSelection();
            var rec=w2ui['philhealthgrid'].get(id);
            console.log(rec);
            $("#txt-philhealth").val(rec.bracket_no);
            $("#dlg-philhealth").dialog("close");
            
        }
    },
    
    birGrid : {
        name: 'birgrid',
        header: 'BIR',
        show: {
            header: true,
            footer: true,
       
        },
        columns: [
            { field: 'row_id', caption: 'Bracket', size: '70px', sortable: true, attr: 'align=center', resizable: false},
            { field: 'pay_frequency', caption: 'Pay Frequency', size: '30%', sortable: true, resizable: false},
            { field: 'tax_code', caption: 'Tax Code', size: '30%', resizable: false},
            { field: 'column_no', caption: 'Column No', size: '120px', resizable: false},
            { field: 'pay_rate', caption: 'Pay Rate', size: '120px', resizable: false},
            { field: 'tax_amount', caption: 'Tax Amount', size: '20%', resizable: false},
            { field: 'over_tax_percent', caption: 'Over Tax Percent', size: '20%', resizable: false}
        ],
        onDblClick:function(e){
            var id=w2ui['birgrid'].getSelection();
            var rec=w2ui['birgrid'].get(id);
            console.log(rec);
            $("#txt-bir").val(rec.row_id);
            $("#dlg-bir").dialog("close");
        }
               
        },
        payrateGrid:{
          name:'payrategrid',
          header:'Pay Rate History',
          columns:[
            {field:'rateid',hidden:true},
            {field:'edate',caption:'Effective Date',size:'100%'},
            {field:'paytype',caption:'Pay Type',size:'100%'},
            {field:'freq',caption:'Frequency',size:'100%'},
            {field:'daily',caption:'Daily Rate',size:'100%',render:'number:2'},
            {field:'monthly',caption:'Monthly Rate',size:'100%',render:'number:2'},
            
            
          ]  
        },
      //--------JQueryUI----
      payrateDlg:{
          height:550,
          width:500,
          autoOpen:false,
          modal:true,
          buttons:{
              "Save":function(e){
                  var param="cmd=savepayrate&eid="+$("#lbl-employee-id").html()+"&"+$("#payrate-frm").serialize();
                  processAjax(empURL,param);
               },
               "Cancel":function(e){
                  $(this).dialog("close"); 
               }
          }
          
      },
      govDlg:{
          height:550,
          width:800,
          autoOpen:false,
          modal:true,
        
      } ,
       birDlg:{
          height:550,
          width:1100,
          autoOpen:false,
          modal:true,
        
      }   
      
      
       
   };
   
   
   
   //----Process Ajax-----------
   function processAjax(url,param){
     $.ajax({
        type:"post",
        url:url,
        data:param,
          
        success:function(response){
        //  console.log(response);
          jobj=JSON.parse(response);
          if(jobj.command=="searchemployee"){
              w2ui['employeegrid'].clear();
              w2ui['employeegrid'].records=jobj.employees;
              w2ui['employeegrid'].refresh();
              
          }else
          if(jobj.command=="loadphoto"){
             $("#img-employee").attr("src","data:image/jpeg;base64,"+jobj.img);
              
          }else
          if(jobj.command=="codesbygrp"){
              if(jobj.group=="033"){
                 loadJSONToCombo(jobj,"txt-paytype","-Select Pay Type-");
              }else
               if(jobj.group=="035"){
                 loadJSONToCombo(jobj,"txt-frequency","-Select Pay Frequency-");
              }
              
          }else
          if(jobj.command=="displayrecords") {
                for(i=0;i<jobj.data.length;i++) {
                    if (jobj.data[i].pay_frequency == "035001") {
                        jobj.data[i].pay_frequency = "Daily";
                    }else if (jobj.data[i].pay_frequency == "035002") {
                        jobj.data[i].pay_frequency = "Weekly";
                    }else if (jobj.data[i].pay_frequency == "035004") {
                        jobj.data[i].pay_frequency = "Semi-Monthly";
                    }
                }
                    w2ui['birgrid'].clear();
                    w2ui['birgrid'].records=jobj.data;
                    w2ui['birgrid'].refresh();
          }else
          if(jobj.command=="savepayrate"){
              if(jobj.status=="success"){
                $("#dlg-payrates").dialog("close");   
                $("input[id^=txt-]").val("");
              }
              notifymessage(jobj.message,jobj.status,"topRight",3000);
          }else
          if(jobj.command=="payratehistory"){
              w2ui['payrategrid'].clear();
              w2ui['payrategrid'].records=jobj.data;
              w2ui['payrategrid'].refresh();
              
          }else{
              notifymessage(jobj.message,jobj.status,"topRight",1500);
          }
         
          
        }
      });  
        
   }
   
   //----------INITIALIZATION----------------
   $("#div-tabs-payrates").w2tabs(payrateConfig.payrateTab);
   $("#tbl-employee-list").w2grid(payrateConfig.employeeGrid);
   $("#div-tabs-payrate-settings").w2tabs(payrateConfig.payrateEffectiveTab);
   $("#dlg-sss").dialog(payrateConfig.govDlg);
   $("#dlg-philhealth").dialog(payrateConfig.govDlg);
   $("#sss-grid").w2grid(payrateConfig.SSSGrid);
   $("#philhealth-grid").w2grid(payrateConfig.philHealthGrid);
   $("#dlg-payrates").dialog(payrateConfig.payrateDlg);
   $("#dlg-bir").dialog(payrateConfig.birDlg);
   $("#bir-grid").w2grid(payrateConfig.birGrid);
   $("#payrate-grid").w2grid(payrateConfig.payrateGrid);
   createDatePicker("txt-date-effective");
   
   
   
   //----EVENTS-----
   $("input[id^=btn-]").click(function(e){
       
      if(e.target.id=="btn-sss"){
          $("#dlg-sss").dialog("open");
          w2ui['SSSgrid'].reload();
          w2ui['SSSgrid'].refresh();
      }else
      if(e.target.id=="btn-philhealth"){
          w2ui['philhealthgrid'].refresh();
          $("#dlg-philhealth").dialog("open");
      }else
      if(e.target.id=="btn-bir"){
           processAjax(birURL,"cmd=displayrecords&grp=bir");
          w2ui['birgrid'].refresh();
          $("#dlg-bir").dialog("open");
          
      } 
   });
   
   
    
});
