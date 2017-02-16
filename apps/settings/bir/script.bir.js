/**
 * Created by Drew on 11/16/2015.
 */
var apiURL="../../../api/api.govtables.php";

var config = {
    resultGrid : {
        name: 'resultgrid',
        header: 'BIR',
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
            { field: 'row_id', caption: 'Row ID', size: '150px', sortable: true, attr: 'align=center', resizable: false},
            { field: 'pay_frequency', caption: 'Pay Frequency', size: '30%', sortable: true, resizable: false},
            { field: 'tax_code', caption: 'Tax Code', size: '30%', resizable: false},
            { field: 'column_no', caption: 'Column No', size: '120px', resizable: false},
            { field: 'pay_rate', caption: 'Pay Rate', size: '120px', resizable: false},
            { field: 'tax_amount', caption: 'Tax Amount', size: '20%', resizable: false},
            { field: 'over_tax_percent', caption: 'Over Tax Percent', size: '20%', resizable: false}
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
                    $('#bir-form').hide();
                    $('#bir-form-mod').show();
                    processAjax(apiURL, "cmd=loadselected&rid="+rec.row_id+"&type=bir");
                   /* form.recid  = sel[0];
                    form.record = $.extend(true, {}, grid.get(sel[0]));
                    form.refresh();*/
                } else {
                    $('#bir-form').show();
                    $('#bir-form-mod').hide();
                }
            }
        }
    }
};

$("document").ready(function(){
    processAjax(apiURL,"cmd=displayrecords&grp=bir");
    processAjax(apiURL,"cmd=getpayfrequency");

    $('#bir-grid').w2grid(config.resultGrid);
    $('#bir-form-mod').hide();

    $("#btn-save").click(function(e){
        console.log(e);
        var type = "bir";
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
        var type = "bir";
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
                for(i=0;i<jobj.data.length;i++) {
                    if (jobj.data[i].pay_frequency == "035001") {
                        jobj.data[i].pay_frequency = "Daily";
                    }else if (jobj.data[i].pay_frequency == "035002") {
                        jobj.data[i].pay_frequency = "Weekly";
                    }else if (jobj.data[i].pay_frequency == "035004") {
                        jobj.data[i].pay_frequency = "Semi-Monthly";
                    }
                }
                    w2ui['resultgrid'].clear();
                    w2ui['resultgrid'].records=jobj.data;
                    w2ui['resultgrid'].refresh();
            }else if(jobj.command=="saverecordbir"){
                    notifymessage(jobj.message,jobj.status,"topRight",3000);
                    processAjax(apiURL,"cmd=displayrecords&grp=bir");
                    document.getElementById("bir-form").reset();
            }else if(jobj.command=="getpayfrequency"){
                console.log(jobj);
                loadJSONToCombo(jobj,"sel-payfrequency","Select Period");
                loadJSONToCombo(jobj,"sel-payfrequency-mod","Select Period");
            }else if(jobj.command=="loadselected"){
                console.log(jobj);
                if(jobj.type="bir"){
                    $('#row-id-mod').val(jobj.records[0].row_id);
                    $('#sel-payfrequency-mod').val(jobj.records[0].pay_frequency);
                    $('#sel-taxcode-mod').val(jobj.records[0].tax_code);
                    $('#sel-columno-mod').val(jobj.records[0].column_no);
                    $('#payrate-mod').val(jobj.records[0].pay_rate);
                    $('#taxamount-mod').val(jobj.records[0].tax_amount);
                    $('#overtaxpercent-mod').val(jobj.records[0].over_tax_percent);
                }
            }else if(jobj.command=="updaterecordbir"){
                notifymessage(jobj.message,jobj.status,"topRight",3000);
                processAjax(apiURL,"cmd=displayrecords&grp=bir");
                document.getElementById("bir-form-mod").reset();
                $('#bir-form').show();
                $('#bir-form-mod').hide();
            }
        }
    });
}

function saveData(type){
    if($('#sel-payfrequency').val() == "" || $('#sel-taxcode').val() == "" || $('#columnno').val() == "" || $('#payrate').val() == "" || $('#taxamount').val() == "" || $('#overtaxpercent').val == ""){
        //if($('#firstname').val() == "" || $('#lastname').val() == "" || $('#username').val() == "" || $('#password').val() == "" || $('#password2').val() == ""){
            w2alert("Fill in required fields.");
    }else{
        var param={cmd:'saverecord'+type,data:$("#bir-form").serializeArray()};
        processAjax(apiURL,param);
        console.log(param);
        //clearSSSform();
        //location.reload();
    }
}

function updateData(type){
    if($('#sel-payfrequency-mod').val() == "" || $('#sel-taxcode-mod').val() == "" || $('#columnno-mod').val() == "" || $('#payrate-mod').val() == "" || $('#taxamount-mod').val() == "" || $('#overtaxpercent-mod').val == ""){
        //if($('#firstname').val() == "" || $('#lastname').val() == "" || $('#username').val() == "" || $('#password').val() == "" || $('#password2').val() == ""){
        w2alert("Fill in required fields.");
    }else{
        var param={cmd:'updaterecord'+type,data:$("#bir-form-mod").serializeArray()};
        processAjax(apiURL,param);
        console.log(param);
        //clearSSSform();
        //location.reload();
    }
}

function clearSSSform(){
    $('#sel-payfrequency').val("");
    $('#sel-taxcode').val("");
    $('#columnno').val("");
    $('#payrate').val("");
    $('#taxamount').val("");
    $('#overtaxpercent').val("");
}