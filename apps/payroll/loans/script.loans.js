/**
 * Created by Drew on 12/8/2015.
 */
var apiURL="../../../api/api.employees.php";
var apiLoansURL="../../../api/api.loans.php";
var sysURL="../../../api/api.systemconfig.php";
var apiPayroll="../../../api/api.payrollprocess.php";
var globalempid = "";
var globalEmpFullname = "";
var globalLoanBalance = "";
var globalAccountNo = "";

var config = {
    resultGrid : {
        name: 'resultgrid',
        header: 'List Of Employee',
        show: {
            header: true,
            footer: true,
            toolbar: false,
            toolbarReload: false,
            toolbarColumns: false,
          
            //toolbarAdd: false,
            toolbarSearch:false
        },
        multiSelect:false,
        columns: [
            { field: 'id', caption: 'Employee ID', size: '30%',  resizable: false},
            { field: 'name', caption: 'Full Name', size: '70%', resizable: false}
        ],
       
        onSelect: function(event) {
            console.log(event);
            var grid = this;
         
                var sel = event.recid;

                //console.log(sel);
                var id=event.recid;
                var rec = w2ui['resultgrid'].get(id);
                console.log(rec);

                if (sel.length >= 1) {
                  //  $('#right-grid-newloans').show();
                    processAjax(apiLoansURL, "cmd=loadselected&rid="+rec.id);
                    processAjax(apiLoansURL, "cmd=loadloans&rid="+rec.id);
                    processAjax(apiURL,"cmd=loadphoto&id="+rec.id);
                    processAjax(apiLoansURL, "cmd=displayemploans&rid="+rec.id);
                    form.recid  = sel[0];
                    form.record = $.extend(true, {}, grid.get(sel[0]));
                     //form.refresh();
                } else {
                  //  $('#right-grid-newloans').hide();
                    $('#loans-employee-id').html("");
                    $('#loans-employee-name').html("");
                    //$('#loans-employee-branch').html(jobj.records[0].tax_code);
                    //$('#loans-employee-status').html(jobj.records[0].column_no);
                }
         
        }
    },
     toolbar:{
           name:'toolbar',
           items:[
             {type:'html',id:'tool1',html:'<input type="text" id="txt-search" value="" style="width:125px;" />'},
             {type:'html',id:'tool2',html:'<select id="txt-branch" ><option value="">-Select Branch-</option> </select>'},
             {type:'html',id:'tool3',html:'<select id="txt-status" ><option value="">-Select Status-</option> </select>'},
             {type:'button',id:'tool4',caption:'Search'}
            
           ],
           onClick:function(e){
               processAjax(apiURL,"cmd=searchemployee&term="+$("#txt-search").val()+"&status="+$("#txt-status").val()+"&location="+$("#txt-branch").val());
           }

        },
};

var loan_config = {
    resultGridLoans : {
        name: 'resultgridloans',
        header: 'Loan Accounts',
        show: {
            header: true,
            toolbar: true,
            toolbarReload: false,
            toolbarColumns: false,
            footer: true,
            toolbarAdd: false
        },
        /*searches: [
            { field: 'name', caption: 'Last Name', type: 'text' },
            { field: 'accountno', caption: 'Account Number', type: 'text' }
        ],*/
        columns: [
            { field: 'accountno', caption: 'Account Number', size: '20%', sortable: true, resizable: false},
            { field: 'name', caption: 'Loan', size: '40%', resizable: false},
            { field: 'loanamount', caption: 'Loan Amount', size: '20%', resizable: false,render:"number:2"},
            { field: 'amortization', caption: 'Amortization', size: '20%', resizable: false,render:"number:2"},
            
            { field: 'balance', caption: 'Balance', size: '20%', resizable: false,render:"number:2"},
            { field: 'holdstatus', caption: 'Hold Status', size: '20%', resizable: false}
        ],
        toolbar: {
            items: [
                {type:'button',id:'btn-hold-loan',text:'Hold'},
                {type:'button',id:'btn-unhold-loan',text:'Unhold'}
            ],
            onClick: function (event) {
                var id=w2ui['resultgrid'].getSelection();
                var rec = w2ui['resultgrid'].get(id);

                var loanid = w2ui['resultgridloans'].getSelection();
                var loanrec = w2ui['resultgridloans'].get(loanid);
                var accountno = loanrec.accountno;
                console.log(rec.id);
                if(event.target == 'btn-hold-loan'){
                    processAjax(apiLoansURL, "cmd=updateholdstatus&acctno="+accountno+"&stat=1");
                    w2ui['resultgridloans'].clear();
                    w2ui['resultgridloans'].refresh();
                    w2ui['resultgridloans'].toolbar.disable('btn-unhold-loan');
                    w2ui['resultgridloans'].toolbar.disable('btn-hold-loan');
                }else if(event.target == 'btn-unhold-loan'){
                    processAjax(apiLoansURL, "cmd=updateholdstatus&acctno="+accountno+"&stat=0");
                    w2ui['resultgridloans'].clear();
                    w2ui['resultgridloans'].refresh();
                    w2ui['resultgridloans'].toolbar.disable('btn-unhold-loan');
                    w2ui['resultgridloans'].toolbar.disable('btn-hold-loan');
                }

                processAjax(apiLoansURL, "cmd=displayemploans&rid="+rec.id);
            }
        },
        onClick: function(event){
            var loangrid = this;
            //console.log(event);
            event.onComplete = function () {
                var sel = loangrid.getSelection();
                //console.log(sel);
                var loanid = w2ui['resultgridloans'].getSelection();
                var loanrec = w2ui['resultgridloans'].get(loanid);
                var accountno = loanrec.accountno;
                processAjax(apiLoansURL, "cmd=getloanbalance&acctno="+accountno);
                //console.log(loanrec);

                if (sel.length == 1) {
                    if(loanrec.holdstatus == "Clear"){
                        /*$('#btn-unhold-loan').attr("disabled",false);
                        $('#btn-hold-loan').show();*/
                        w2ui['resultgridloans'].toolbar.disable('btn-unhold-loan');
                        w2ui['resultgridloans'].toolbar.enable('btn-hold-loan');
                    }else if(loanrec.holdstatus == "On Hold"){
                        w2ui['resultgridloans'].toolbar.enable('btn-unhold-loan');
                        w2ui['resultgridloans'].toolbar.disable('btn-hold-loan');
                    }
                }else{
                    w2ui['resultgridloans'].toolbar.disable('btn-unhold-loan');
                    w2ui['resultgridloans'].toolbar.disable('btn-hold-loan');
                }
            }
        },
        onDblClick: function(event){
            var loangrid = this;
            //console.log(event);
            event.onComplete = function () {
                var sel = loangrid.getSelection();
                //console.log(sel);
                var loanid = w2ui['resultgridloans'].getSelection();
                var loanrec = w2ui['resultgridloans'].get(loanid);
                var accountno = loanrec.accountno;
                //console.log(accountno);
                //processAjax(apiLoansURL, "cmd=getloanbalance&acctno="+accountno);
                //processAjax(apiLoansURL,"cmd=displayadjusments&acctno="+accountno);
                processAjax(apiLoansURL,"cmd=displayadjusments&acctno="+accountno);
                if (sel.length == 1) {
                    /*if(loanrec.holdstatus == "Clear"){
                        *//**//*$('#btn-unhold-loan').attr("disabled",false);
                         $('#btn-hold-loan').show();*//**//*
                        w2ui['resultgridloans'].toolbar.disable('btn-unhold-loan');
                        w2ui['resultgridloans'].toolbar.enable('btn-hold-loan');
                    }else if(loanrec.holdstatus == "On Hold"){
                        w2ui['resultgridloans'].toolbar.enable('btn-unhold-loan');
                        w2ui['resultgridloans'].toolbar.disable('btn-hold-loan');
                    }*/

                    $("#loan-adjust-dlg").dialog("open");
                    //processAjax(apiLoansURL, "cmd=getloanbalance&acctno="+accountno);
                    //$("#loansadj-employee-id").html(globalempid);
                    //$("#loansadj-employee-name").html(globalEmpFullname);
                    //$("#loansadj-balance").html(globalLoanBalance);
                }else{
                    /*w2ui['resultgridloans'].toolbar.disable('btn-unhold-loan');
                    w2ui['resultgridloans'].toolbar.disable('btn-hold-loan');*/
                }
            }
        }

        /*,
         onClick: function(event) {
             var grid = this;
             console.log(event);
             event.onComplete = function () {
                 var sel = grid.getSelection();
                 //console.log(sel);
                 var id=w2ui['resultgridloans'].getSelection();
                 var rec = w2ui['resultgridloans'].get(id);
                 console.log(rec);

                 if (sel.length == 1) {
                     ('btn-hold-loans').hide();
                     *//*$('#bir-form').hide();
                     $('#bir-form-mod').show();*//*
                     //processAjax(apiURL, "cmd=loadselected&rid="+rec.row_id+"&type=bir");
                     form.recid  = sel[0];
                     form.record = $.extend(true, {}, grid.get(sel[0]));
                     //form.refresh();
                 } else {
                     $('btn-hold-loans').show();
                     *//*$('#bir-form').show();
                     $('#bir-form-mod').hide();*//*
                 }
             }
         }*/
    }
};

var loan_adjustment = {
    resultGridLoanAdjust : {
        name: 'resultgridloanadjust',
        header: 'Adjustments',
        show: {
            header: true,
            toolbar: false,
            toolbarReload: false,
            toolbarColumns: false,
            footer: true,
            toolbarAdd: false
        },
        /*searches: [
         { field: 'name', caption: 'Last Name', type: 'text' },
         { field: 'accountno', caption: 'Account Number', type: 'text' }
         ],*/
        columns: [
            { field: 'date', caption: 'Date', size: '20%', sortable: true, resizable: false},
            { field: 'tr_type', caption: 'Adjustment Type', size: '40%', resizable: false},
            { field: 'dr', caption: 'Debit', size: '20%', resizable: false},
            { field: 'cr', caption: 'Credit', size: '20%', resizable: false}
        ]
    },
    changeDlg:{
        height:225,
        width:300,
        autoOpen:false,
        modal:true,
        buttons:{
            "Save":function(e){
               var loanid = w2ui['resultgridloans'].getSelection();
                var loanrec = w2ui['resultgridloans'].get(loanid);
                var accountno = loanrec.accountno;

                var param={cmd:"changeamort",account:accountno,amort:$("#txt-new-amort").val()};
                if(parseFloat(param.amort)>=0)
                  processAjax(apiPayroll,param);
                else
                  notifymessage("Empty not allowed!","error","topRight",3000);
            },
            "Close":function(e){
                $(this).dialog("close");
            }

        }

    }
};

$("document").ready(function(){
    processAjax(apiURL,"cmd=searchemployee");
    processAjax(sysURL,"cmd=empstatus");
    processAjax(sysURL,"cmd=locations");

    $('#employeelist-grid').w2grid(config.resultGrid);
    $('#loan-list-grid').w2grid(loan_config.resultGridLoans);
    $('#loan-adjustment-grid').w2grid(loan_adjustment.resultGridLoanAdjust);
    $("#toolbar-employees").w2toolbar(config.toolbar);
    w2ui['resultgridloans'].toolbar.disable('btn-unhold-loan');
    w2ui['resultgridloans'].toolbar.disable('btn-hold-loan');
    $("#dlg-change-amort").dialog(loan_adjustment.changeDlg);
    
    //$('#loansadj-employee-id').html("asdasd");

   //$('#right-grid-newloans').hide();

    $('#btn-new-loans').click(function(e) {
        console.log(e);
        $('#new-loan-dlg').dialog("open");
        processAjax(apiLoansURL, "cmd=getpayconfig");
    });

    $('#btn-new-loans').click(function(e) {
        console.log(e);
        $('#new-loan-dlg').dialog("open");
        processAjax(apiLoansURL, "cmd=getpayconfig");
    });

    $('#add-memo-btn').click(function(e) {
        console.log(e);
        $('#drcrmemoloans-popup').dialog("open");
        createDatePicker("memo-date");
        //var lb = $('#loansadj-balance').val();
        //$('#memo-lastbal').val(lb);

        //processAjax(apiLoansURL, "cmd=getpayconfig");
    });
    $("#change-amort-btn").click(function(e){
      $("#dlg-change-amort").dialog("open");
    });

    $("#btn-save").click(function(e){
        console.log(e);
        saveData();
    });

    $("#btn-reset").click(function(e){
        console.log(e);
        clearNewLoansDlg();
    });

    $('#new-loan-dlg').dialog({
        height:300,
        width:450,
        autoOpen:false,
        modal:true,

        close: function(event, ui){
            clearNewLoansDlg();
        }
    });

    $('#loan-adjust-dlg').dialog({
        height:500,
        width:550,
        autoOpen:false,
        modal:true,

        close: function(event, ui){
            clearNewLoansDlg();
        }
    });

    //drcrmemoloans-popup
    $('#drcrmemoloans-popup').dialog({
        height:230,
        width:500,
        autoOpen:false,
        modal:true,

        close: function(event, ui){
            clearDRCRpopup();
            //processAjax(apiLoansURL,"cmd=getloanbalance&acctno="+$('#memo-acctno').val());
        }
    });

    $("#memoloans-save-btn").click(function(e){
        console.log(e);
        saveDRCR();
    });
});

function processAjax(url,param){
    $.ajax({
        type:"post",
        url:url,
        data:param,
        success:function(response){
            console.log(response);
            jobj=JSON.parse(response);

            if(jobj.command=="searchemployee") {
                w2ui['resultgrid'].clear();
                w2ui['resultgrid'].records=jobj.employees;
                w2ui['resultgrid'].refresh();
            }else if(jobj.command=="loadselected"){
                console.log(jobj);
                //globalempid = jobj.records[0].id;
                //$("#loans-employee-id").html(jobj.records[0].id);
                //globalEmpFullname = jobj.records[0].name;
                $('#loans-employee-id').html(jobj.records[0].id);
                $('#loans-employee-name').html(jobj.records[0].name);
                $("#loansadj-employee-id").html(jobj.records[0].id);
                $("#loansadj-employee-name").html(jobj.records[0].name);

                //$('#loans-employee-branch').html(jobj.records[0].tax_code);
                $('#loans-employee-status').html(jobj.records[0].status);
                $('#employeeid').val(jobj.records[0].id);
            }else if(jobj.command=="loadloans"){
                console.log(jobj);
                w2ui['resultgridloans'].clear();
                w2ui['resultgridloans'].records=jobj.records;
                w2ui['resultgridloans'].refresh();
            }else if(jobj.command=="displayemploans"){

                console.log(jobj);
                for(i=0;i<jobj.records.length;i++) {
                    if (jobj.records[i].holdstatus == "1") {
                        jobj.records[i].holdstatus = "On Hold";
                    }else if (jobj.records[i].holdstatus == "0") {
                        jobj.records[i].holdstatus = "Clear";
                    }
                }

                w2ui['resultgridloans'].clear();
                w2ui['resultgridloans'].records=jobj.records;
                w2ui['resultgridloans'].refresh();
                globalAccountNo = jobj.records[0].accountno;
            }else if(jobj.command=="getpayconfig"){
                loadJSONToCombo(jobj,"sel-payconfig","Select Loan Type");
            }else if(jobj.command=="loadphoto"){
                $("#img-employee").attr("src","data:image/jpeg;base64,"+jobj.img);
                  $("#img-employee2").attr("src","data:image/jpeg;base64,"+jobj.img);

            }else if(jobj.command=="saverecord"){
                notifymessage(jobj.message,jobj.status,"topRight",3000);
                //processAjax(apiURL,"cmd=displayallusers");
            }else if(jobj.command=="updateholdstatus"){
                notifymessage(jobj.message,jobj.status,"topRight",3000);
                //processAjax(apiURL,"cmd=displayallusers");
            }else if(jobj.command=="getloanbalance"){
                //globalLoanBalance = jobj.data[0].balance;
                $("#loansadj-balance").html(formatNumber(jobj.data[0].balance));
                $('#memo-lastbal').val(jobj.data[0].balance);
                //notifymessage(jobj.message,jobj.status,"topRight",3000);
                //processAjax(apiURL,"cmd=displayallusers");
            }else if(jobj.command=="saverecordmemo"){
                notifymessage(jobj.message,jobj.status,"topRight",3000);
                 processAjax(apiLoansURL,"cmd=getloanbalance&acctno="+$('#memo-acctno').val());
                //processAjax(apiURL,"cmd=displayallusers");
            }else if(jobj.command=="displayadjusments"){
                console.log(jobj);
                w2ui['resultgridloanadjust'].clear();
                w2ui['resultgridloanadjust'].records=jobj.records;
                w2ui['resultgridloanadjust'].refresh();
                $("#memo-acctno").val(jobj.records[0].loan_account);
            }else
            if(jobj.command=="empstatus"){
             loadJSONToCombo(jobj,"txt-status","-Select Status-");    
            
            }else
            if(jobj.command=="locations"){
             loadJSONToCombo(jobj,"txt-branch","-Select Department-");
            
            }else
            if(jobj.command=="changeamort"){
                notifymessage(jobj.message,jobj.status,"topRight",3000);
                var id=w2ui['resultgrid'].getSelection();
                var rec=w2ui['resultgrid'].get(id)
               processAjax(apiLoansURL, "cmd=displayemploans&rid="+rec.id);
            }


        }
    });
}

function saveData(){
    if($('#sel-payconfig').val() == "" || $('#amount').val() == "" || $('#term').val() == "" || $('#amortization').val() == ""){
        //if($('#firstname').val() == "" || $('#lastname').val() == "" || $('#username').val() == "" || $('#password').val() == "" || $('#password2').val() == ""){
        w2alert("Fill in required fields.");
    }else{
        var param={cmd:'saverecord',data:$("#new-loan-form").serializeArray()};
        processAjax(apiLoansURL,param);
        console.log(param);
        $('#new-loan-dlg').dialog("close");
    }
}

function saveDRCR(){
    if($('#memo-type').val() == "" || $('#memo-date').val() == "" || $('#memo-amount').val() == ""){
        //if($('#firstname').val() == "" || $('#lastname').val() == "" || $('#username').val() == "" || $('#password').val() == "" || $('#password2').val() == ""){
        w2alert("Fill in required fields.");
    }else{

        var param={cmd:'saverecordmemo',data:$("#drcrmemoloans-form").serializeArray()};
        //w2alert($('#memo-type').val()+" - "+$('#memo-date').val()+" - "+$('#memo-amount').val());
        processAjax(apiLoansURL,param);
        console.log(param);
        $('#drcrmemoloans-popup').dialog("close");
    }
}

function clearNewLoansDlg(){
    $('#sel-payconfig').val("");
    $('#debit').val("");
    $('#credit').val("");
    $('#amount').val("");
    $('#term').val("");
    $('#amortization').val("");
    globalAccountNo = "";
    globalempid = "";
    globalLoanBalance = "";
}

function clearDRCRpopup(){

    $('#memo-type').val("");
    $('#memo-date').val("");
    $('#memo-amount').val("");
    globalAccountNo = "";
    globalempid = "";
    globalLoanBalance = "";
    w2ui['resultgridloanadjust'].clear();
    processAjax(apiLoansURL,"cmd=displayadjusments&acctno="+$('#memo-acctno').val());
    processAjax(apiLoansURL,"cmd=getloanbalance&acctno="+$('#memo-acctno').val());
    //w2alert($('#memo-acctno').val());

}