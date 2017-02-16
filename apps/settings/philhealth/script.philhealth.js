/**
 * Created by Drew on 11/17/2015.
 */
var apiURL="../../../api/api.govtables.php";

var config = {
    resultGrid : {
        name: 'resultgrid',
        header: 'Philhealth',
        show: {
            header: true,
            toolbar: false,
            toolbarReload: false,
            toolbarColumns: false,
            footer: true,
            toolbarAdd: false
        },
        searches: [
            { field: 'fullname', caption: 'Last Name', type: 'text' },
            { field: 'username', caption: 'Username', type: 'text' }
        ],
        columns: [
            { field: 'bracket_no', caption: 'Salary Bracket', size: '20%', sortable: true, resizable: false},
            { field: 'salary_base', caption: 'Salary Base', size: '20%', resizable: false},
            { field: 'employer_share', caption: 'Employer Share', size: '20%', resizable: false},
            { field: 'employee_share', caption: 'Employee Share', size: '20%', resizable: false},
            { field: 'total_contribution', caption: 'Total Contribution', size: '20%', resizable: false}
        ],
        onClick: function(event) {
            var grid = this;
            console.log(event);
            event.onComplete = function () {
                var sel = grid.getSelection();
                //console.log(sel);
                var id=w2ui['resultgrid'].getSelection();
                var rec = w2ui['resultgrid'].get(id);
                console.log(rec);

                if (sel.length == 1) {
                    $('#philhealth-form').hide();
                    $('#philhealth-form-mod').show();
                    processAjax(apiURL, "cmd=loadselected&rid="+rec.row_id+"&type=philhealth");
                } else {
                    $('#philhealth-form').show();
                    $('#philhealth-form-mod').hide();
                }
            }
        }
    }
};

$("document").ready(function(){
    processAjax(apiURL,"cmd=displayrecords&grp=philhealth");

    $('#philhealth-grid').w2grid(config.resultGrid);
    $('#philhealth-form-mod').hide();

    $("#btn-save").click(function(e){
        console.log(e);
        var type = "philhealth";
        saveData(type);
        //clearSSSform();
        //location.reload();
    });

    $('#btn-reset').click(function(e){
        console.log(e);
        clearSSSform();
    });

    $("#btn-update-mod").click(function(e){
        console.log(e);
        var type = "philhealth";
        updateData(type);
        //clearSSSform();
        //location.reload();
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

            if(jobj.command=="displayrecords") {
                if(jobj.group=="philhealth"){
                    w2ui['resultgrid'].clear();
                    w2ui['resultgrid'].records=jobj.data;
                    w2ui['resultgrid'].refresh();
                }

            }else if(jobj.command=="saverecordphilhealth"){
                    notifymessage(jobj.message,jobj.status,"topRight",3000);
                    processAjax(apiURL,"cmd=displayrecords&grp=philhealth");
                    document.getElementById("philhealth-form").reset();
            }else if(jobj.command=="loadselected"){
                console.log(jobj);
                if(jobj.type="philhealth"){
                    $('#row-id-mod').val(jobj.records[0].row_id);
                    $('#bracketno-mod').val(jobj.records[0].bracket_no);
                    $('#salarybase-mod').val(jobj.records[0].salary_base);
                    $('#employershare-mod').val(jobj.records[0].employer_share);
                    $('#employeeshare-mod').val(jobj.records[0].employee_share);
                    $('#totalcontribution-mod').val(jobj.records[0].total_contribution);
                }
            }else if(jobj.command=="updaterecordphilhealth"){
                notifymessage(jobj.message,jobj.status,"topRight",3000);
                processAjax(apiURL,"cmd=displayrecords&grp=philhealth");
                document.getElementById("philhealth-form-mod").reset();
                $('#philhealth-form').show();
                $('#philhealth-form-mod').hide();
            }
        }
    });
}

function saveData(type){
    if($('#bracketno').val() == "" || $('#salarybase').val() == "" || $('#employershare').val() == "" || $('#employeeshare').val() == "" || $('#totalcontribution').val() == ""){
        //if($('#firstname').val() == "" || $('#lastname').val() == "" || $('#username').val() == "" || $('#password').val() == "" || $('#password2').val() == ""){
        w2alert("Fill in required fields.");
    }else{
        var param={cmd:'saverecord'+type,data:$("#philhealth-form").serializeArray()};
        processAjax(apiURL,param);
        console.log(param);
        //clearSSSform();
        //location.reload();
    }
}

function updateData(type){
    if($('#bracketno-mod').val() == "" || $('#salarybase-mod').val() == "" || $('#employershare-mod').val() == "" || $('#employeeshare-mod').val() == "" || $('#totalcontribution-mod').val() == ""){
        //if($('#firstname').val() == "" || $('#lastname').val() == "" || $('#username').val() == "" || $('#password').val() == "" || $('#password2').val() == ""){
        //w2alert("Fill in required fields.");
        w2alert("Fill in required fields.");
    }else{
        var param={cmd:'updaterecord'+type,data:$("#philhealth-form-mod").serializeArray()};
        processAjax(apiURL,param);
        console.log(param);
        //clearSSSform();
        //location.reload();
    }
}

function clearSSSform(){
    $('#bracketno').val("");
    $('#salarybase').val("");
    $('#employershare').val("");
    $('#employeeshare').val("");
    $('#totalcontribution').val("");
}