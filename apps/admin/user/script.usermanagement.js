/**
 * Created by Drew on 11/5/2015.
 */
var apiURL="../../../api/api.usermanagement.php";
var result="";

var config = {
    resultGrid : {
        name: 'resultgrid',
        header: 'User Management',
        show: {
            header: true,
            toolbar: true,
            toolbarReload: false,
            toolbarColumns: false,
            footer: true,
            toolbarAdd: true,
            toolbarEdit: true
        },items: [
            {type: 'button',id:'btn-edit',text:'Edit'}
        ],
        searches: [
            { field: 'fullname', caption: 'Last Name', type: 'text' },
            { field: 'username', caption: 'Username', type: 'text' }
        ],
        columns: [
            { field: 'user_id', caption: 'User ID', size: '150px', sortable: true, attr: 'align=center', resizable: false},
            { field: 'fullname', caption: 'Full Name', size: '30%', sortable: true, resizable: false, editable: { type: 'text' }},
            { field: 'username', caption: 'User Name', size: '30%', resizable: false, editable: { type: 'text' }},
            { field: 'workgroup', caption: 'Workgroup', size: '120px', resizable: false},
            { field: 'is_active', caption: 'Active', size: '120px', resizable: false},
            { field: 'space', caption: '', size: '20%', resizable: false}
        ],
        onAdd: function (e) {
            //w2alert('add');
            console.log(e);
            $('#add-user-dlg').dialog("open");
        },
        onEdit: function (event) {
            console.log(event);
            var id=w2ui['resultgrid'].getSelection();
            var rec = w2ui['resultgrid'].get(id);
            processAjax(apiURL, "cmd=loadcustomerinfo&cid="+rec.user_id);
            $('#edit-user-dlg').dialog("open");
        }/*,
        onClick: function(event) {
            var grid = this;
            console.log(event);
            event.onComplete = function () {
                var sel = grid.getSelection();
                console.log(sel);
                if (sel.length == 1) {
                    form.recid  = sel[0];
                    form.record = $.extend(true, {}, grid.get(sel[0]));
                    form.refresh();
                } else {
                    form.clear();
                }
            }
        }*/
    }
};

$("document").ready(function(){
    processAjax(apiURL,"cmd=displayallusers");
    processAjax(apiURL,"cmd=searchworkgroup");

    $('#usergrid').w2grid(config.resultGrid);

    $("#btn-save").click(function(e){
        console.log(e);
        saveData();
    });

    $('#btn-reset').click(function(e){
        console.log(e);
        clearAddUserField();
    });

    $("#btn-update-mod").click(function(e){
        console.log(e);
        updateData();
    });

    $('#btn-reset-mod').click(function(e){
        console.log(e);
        clearFields();
    });

    $("#add-user-dlg").dialog({
        height:550,
        width:450,
        autoOpen:false,
        modal:true,

        close: function(event, ui){
            clearAddUserField();
        }
    });

    $("#edit-user-dlg").dialog({
        height:550,
        width:450,
        autoOpen:false,
        modal:true,

        close: function(event, ui){
            clearFields();
        }
    });

    $("select[id^=sel-]").change(function(e){
        console.log(e);
    });

    $('#username').keyup(function (e){
        //checkUserIfExist();
        var usrname = $('#username').val();
        processAjax(apiURL,"cmd=checkuserifexist&grp="+usrname);
    });

    //$('#form').clear;


});

function processAjax(url,param){
    $.ajax({
        type:"post",
        url:url,
        data:param,
        success:function(response){
            console.log(response);
            jobj=JSON.parse(response);

            if(jobj.command=="displayallusers") {
                for(i=0;i<jobj.users.length;i++) {
                    if (jobj.users[i].is_active == "1") {
                        jobj.users[i].is_active = "Active";
                    }else if (jobj.users[i].is_active == "0") {
                        jobj.users[i].is_active = "In-Active";
                    }

                    jobj.users[i].username = jobj.users[i].username.toLowerCase();
                }
                w2ui['resultgrid'].clear();
                w2ui['resultgrid'].records=jobj.users;
                w2ui['resultgrid'].refresh();
            }else if(jobj.command=="saveuser"){
                notifymessage(jobj.message,jobj.status,"topRight",3000);
                processAjax(apiURL,"cmd=displayallusers");
            }else if(jobj.command=="updateuser"){
                notifymessage(jobj.message,jobj.status,"topRight",3000);
                processAjax(apiURL,"cmd=displayallusers");
            }else if(jobj.command=="searchworkgroup"){
                    console.log(jobj);
                    loadJSONToCombo(jobj,"sel-workgroup","Select Workgroup");
                    loadJSONToCombo(jobj,"sel-workgroup-mod","Select Workgroup");
            }else if(jobj.command=="checkuserifexist"){
                console.log(jobj);
                result = jobj.result;
            }else if(jobj.command=="loadcustomerinfo"){
                //userInfo=jobj.users;

                console.log(jobj.users[0].fullname);
                $('#userid').val(jobj.users[0].user_id);
                $('#edit-fullname').val(jobj.users[0].fullname);
                $('#edit-username').val(jobj.users[0].username);
                $('#sel-workgroup-mod').val(jobj.users[0].workgroup);
                $('#sel-status-mod').val(jobj.users[0].is_active);
            }
        }
    });
}

function saveData(){
    if($('#fullname').val() == "" || $('#username').val() == "" || $('#password').val() == "" || $('#password2').val() == "" || $('#sel-workgroup').val() == "" || $('#sel-status').val == ""){
    //if($('#firstname').val() == "" || $('#lastname').val() == "" || $('#username').val() == "" || $('#password').val() == "" || $('#password2').val() == ""){
    //    w2alert("Fill in required fields.");
    }else{
        if(!validatePassword("save") || !checkUserIfExist()){

        }else{
            var param={cmd:'saveuser',data:$("#adduser-form").serializeArray()};
            processAjax(apiURL,param);
            console.log(param);
            $('#add-user-dlg').dialog("close");
            //clearAddUserField();
            //location.reload();
        }
    }
}

function updateData(){
    if($('#edit-password').val() != "" || $('#edit-password' != "")){
        if(!validatePassword("update")){

        }else{
            var param={cmd:'updateuser',data:$("#edituser-form").serializeArray()};
            processAjax(apiURL,param);
            console.log(param);
            $('#edit-user-dlg').dialog("close");
        }
    }else{
        var param={cmd:'updateuser',data:$("#edituser-form").serializeArray()};
        processAjax(apiURL,param);
        console.log(param);
        $('#edit-user-dlg').dialog("close");
    }
}

function clearAddUserField(){
    $('#fullname').val("");
    $('#username').val("");
    $('#password').val("");
    $('#password2').val("");
    $('#sel-workgroup').val("");
    $('#sel-status').val("");
}

function clearFields(){
    $('#edit-fullname').val("");
    $('#edit-username').val("");
    $('#edit-password').val("");
    $('#edit-password2').val("");
    $('#sel-workgroup-mod').val("");
    $('#sel-status-mod').val("");
}

function validatePassword(ptype){
    var type=ptype;
    var pass1 = "";
    var pass2 = "";
    if(type=="save"){
        pass1 = $('#password').val();
        pass2 = $('#password2').val();
    }else if(type=="update") {
        pass1 = $('#edit-password').val();
        pass2 = $('#edit-password2').val();
    }

    var ok = true;

    if(pass1 != pass2){
        if(type=="save") {
            document.getElementById("password").style.borderColor = "#E34234";
            document.getElementById("password2").style.borderColor = "#E34234";
        }else if(type=="update"){
            document.getElementById("edit-password").style.borderColor = "#E34234";
            document.getElementById("edit-password2").style.borderColor = "#E34234";
        }
        w2alert("Password does not match");
        ok = false;
    }else{
        return ok;
    }
    return ok;
}


function checkUserIfExist(){
    //var usrname = $('#username').val();
    //processAjax(apiURL,"cmd=checkuserifexist&grp="+usrname);
    var ok = true;

    if(result == "taken"){
        document.getElementById("username").style.borderColor = "#E34234";
        w2alert("Username already exist.");
        ok = false;
    }else{

    }
    return ok;
}