function addtocombo(str,objname){
   var tmpstr=new String();
   
   var cb=document.getElementById(objname);
   cb.options.length=0;
   tmpstr=str.split(";");
   for(var i=0;i<tmpstr.length;i++){
      var datastr=new String();
      datastr=tmpstr[i].split("*");
      var opt=new Option(datastr[1],datastr[0]);
      
      cb.options.add(opt);
     }
 }
 function metroDialog(selector,Width,Title,Content){
 	$("#"+selector).Dialog({
 		shadow:true,
 		overlay:false,
 		title:Title,
 		width:Width,
 		padding:10,
 		content:Content
 	});
 	
 }
 function YearRange(){
 	var currDate=new Date();
 	return currDate.getFullYear()-100 +":"+(currDate.getFullYear()+1);
 }

function locateTableData(selector,col,value){
    //return true or false
   var row_count=$("#"+selector+" tr").size();
   var found=false;
               
   for(i=1;i<=row_count;i++){
      var data=$("#"+selector+" tr:nth-child("+i+") td:nth-child("+col+")").text();
      if(value==data){
           found=true;
           break; 
        }
   }
   return found;
}

function locateTableRowData(selector,col,value){
     //return row location       
   var row_count=$("#"+selector+" tr").size();
   var rw=0;
               
   for(i=1;i<=row_count;i++){
      var data=$("#"+selector+" tr:nth-child("+i+") td:nth-child("+col+")").text();
      if(value==data){
           rw=i;
           break; 
        }
   }
   return rw;
}


function locateCheckedRow(tableSelector,checkBoxSelector){
   
   var row_count=$("#"+tableSelector+" tr").size();
   var rw=0;
             
   for(i=1;i<=row_count;i++){
       
    
      if($("#"+checkBoxSelector+i).is(":checked")){
           rw=i;
           break; 
        }
   }
   
    return rw;
}

function unCheckRow(tableSelector,checkBoxSelector,selectedCheckbox){
   
   var row_count=$("#"+tableSelector+" tr").size();
  
                 
   for(i=1;i<=row_count;i++){
      var selector=checkBoxSelector+i;
     
       if(selector!=selectedCheckbox)
         $("#"+selector).attr("checked",false);
         
           
   }
   
}

function clearCombo(selector){
	$("#"+selector)
    .find('option')
    .remove()
    .end();
}
//===Customize for System Codes============
 function loadSystemCodeToCombo(jobj,selector){
 	var scode=JSON.parse(jobj.data);
 	var ln = scode.length;
 	//console.log(atype);
 	clearCombo(selector);
 	console.log(scode.length);
 	addDataToCombo(selector,"","-Select-");
 	      //console.log(ix);
 	if(scode.length>0){
 		
 		 
 	    
 	    for(ix=0;ix<ln;ix++){
 		  addDataToCombo(selector,scode[ix].code_id,scode[ix].description);
 		 }
 		
 		//console.log(atype[0].description);
 	}
 }
 	


//$("#inside-list").append("<option value="+patient_id+">"+patient_name+"</option>");

function addDataToCombo(selector,value,textDisplay){
	$("#"+selector).append("<option value="+value+">"+textDisplay+"</option>");
}
// $("#pending-list option[value='"+patient_id+"']").remove();
function removeComboItem(selector){
  $("#"+selector+" option:selected").remove();	
}

function getComboItem(selector){
  var cval = $("#"+selector+" option:selected").attr("value");
  var ctxt = $("#"+selector+" option:selected").text();	
  var json = {"value":cval,"text":ctxt};
  
  return json;
}

function swapArrayData(curr,prev){
	var temp=new Array();
	for(i=0;i<curr.length;i++)
	  temp[i]=curr[i];
	
	for(i=0;i<prev.length;i++)
	  curr[i]=prev[i];
	
	for(i=0;i<prev.length;i++)
	  prev[i]=temp[i];
	  
	  
}
//Note: Table Row starts at 1 which is the header. data row starts at row 2.

function moveUpTableItem(selector,row,startCol){
	var row_count=$("#"+selector+" tr").size();
	var col_count=$("#"+selector+" tr:nth-child(1) td").size();
	var currData = new Array();
	var prevData = new Array();
	var success=true;
	console.log(row_count+","+col_count);
	console.log("move up:"+row);
	var ix=0;
	if((row-1)>1){
	for(cx=startCol;cx<col_count;cx++){
	   currData[ix]=$("#"+selector+" tr:nth-child("+(row)+") td:nth-child("+cx+")").text();		
	   prevData[ix]=$("#"+selector+" tr:nth-child("+(parseInt(row)-1)+") td:nth-child("+cx+")").text();
	 //  alert(currData[ix]);
	 // console.log("current:"+$("#"+selector+" tr:nth-child("+(row)+") td:nth-child("+cx+")").text());
	//  console.log("prev:"+$("#"+selector+" tr:nth-child("+(row-1)+") td:nth-child("+cx+")").text());
	  
	  ix++;
	   		
	}
	
	//swapArrayData(currData,prevData);
	 var ii=0;
     for(cx=startCol;cx<col_count;cx++){
	    $("#"+selector+" tr:nth-child("+(parseInt(row)-1)+") td:nth-child("+(cx)+")").html(currData[ii]);
	    $("#"+selector+" tr:nth-child("+(row)+") td:nth-child("+(cx)+")").html(prevData[ii]);
	   // alert(currData[ii]+","+prevData[ii]);
	   	ii++;	
 	}
     	
	
	}else{
		success=false;
		console.log("You have reached top row!");
	}	
	
	return success;
		
	
	
}


function moveDownTableItem(selector,row,startCol){
	var row_count=$("#"+selector+" tr").size();
	var col_count=$("#"+selector+" tr:nth-child(1) td").size();
	var currData = new Array();
	var prevData = new Array();
    // alert(row_count+","+col_count);
    var ok=true;
	var ix=0;
	console.log("row:"+row+",row_count:"+row_count);
	
	if(((row)<row_count)&&(row>1)){
	for(cx=startCol;cx<col_count;cx++){
	   currData[ix]=$("#"+selector+" tr:nth-child("+(row)+") td:nth-child("+cx+")").text();		
	   prevData[ix]=$("#"+selector+" tr:nth-child("+(row+1)+") td:nth-child("+cx+")").text();
	  // alert(currData[ix]);
	  ix++;
	   		
	}
	
	//swapArrayData(currData,prevData);
	 var ii=0;
     for(cx=startCol;cx<col_count;cx++){
	    $("#"+selector+" tr:nth-child("+(row+1)+") td:nth-child("+(cx)+")").html(currData[ii]);
	    $("#"+selector+" tr:nth-child("+(row)+") td:nth-child("+(cx)+")").html(prevData[ii]);
	   // alert(currData[ii]+","+prevData[ii]);
	   	ii++;	
 	}
	
	
	}else{
		ok=false;
		console.log("You have reached bottom row!");
	}	
		
	return ok;
	
	
}

function removeTableItem(selector,rowSelector,row,startCol){
	//Note: Will move row items on selected columns only
	var row_count=$("#"+selector+" tr").size();
	var currData = new Array();
	var startRow=0;
	console.log("REMOVE:"+row);
	console.log(row_count);
	  startRow=parseInt(row) +1;
	  
	 console.log("START:"+startRow);  
	for(ix=startRow;ix<=row_count+1;ix++){
		console.log("rw:"+row_count+" ix:"+ix);
		moveUpTableItem(selector,ix,startCol);
	}
	removeTableRow(rowSelector+row_count,0);
	
}


function moveUpComboItem(selector){
	$op= $("#"+selector+" option:selected");
	$op.first().prev().before($op); 
	
        
}

function moveDownComboItem(selector){
		$op= $("#"+selector+" option:selected");
		$op.last().next().after($op);

}


function tableRowCount(selector){
  var row_count=$("#"+selector+" tr").size();
  return row_count;	
}

function clearTable(selector,rowSelector){
//Will not remove header. Row Data start at row=2
  var row_count=$("#"+selector+" tr").size();
  for(i=2;i<=row_count;i++){
  	removeTableRow(rowSelector+i,0);
  }	
}

function addTableRowData(selector,rowdata){
	//Note: Add unique ID for each ROW
	
	$("#"+selector+" tbody").append(rowdata); 
}

function getTableData(selector,rw,col){
	return $("#"+selector+" tr:nth-child("+rw+") td:nth-child("+col+")").text();
}

function getTableRowData(selector,col){
	return $("#"+selector+" td:nth-child("+col+")").text();
}

function removeTableRow(selector,msg){ //Will Delete Entire Row without changing ID name
	//Note: Each Table Row Must have unique ID
	if(msg==1){
	if(confirm("Remove Item?"))
		$("#"+selector).remove();
	}else
	    $("#"+selector).remove();
}

function jsonToTableRow(selector,jsondata,header,action,action_script){
	
	var rcount=tableRowCount("selector");
	if(header)
	  rcount=rcount+1;
  	var id="trw"+(rcount);
 // 	var data=JSON.parse(jsondata);
  //	data=
    var data=$.parseJSON(jsondata.stringify);
  //	var parent=data.PartList;
  	
  	
     
}

function createDatePicker(selector){
	$("#"+selector).datepicker({
  	  dateFormat:"yy-mm-dd",
  	  changeMonth:true,
  	   changeYear:true,
  	    yearRange:YearRange()
  });
}


 function notifymessage(message,note_type,layout,displayLength) {
        var n = noty({
            text        : message,
            type        : note_type, //success,error
            dismissQueue: true,
            timeout     : displayLength, //1000,2000
           
            layout      : layout, //topRigh,topCenter
            maxVisible  : 5,
            theme       : 'defaultTheme'
        });
        console.log('html: ' + n.options.id);
    }

function formatNumber(value){
	var sign=value.charAt(0);
	if(sign=="-")
	   value=value.replace("-","");
	else
	   sign="";
	   
	var data=value.split("."); 
	var number=parseFloat(data[0]);
        var numStr="";
        var trail=0;
        var ln=data[0].length;
        var pos=[];
        var ix=0;
	while(Math.abs(number/1000)>=1){
		number=Math.abs(number/1000);
                var tmp=number.toString().split(".");
                trail=ln-tmp[0].length;
                if(number>0)
                   pos[ix]=trail;
                ix++;      
	}
        ix=pos.length-1;
  
        for(i=0;i<ln;i++){
           if((i+1)==(ln-pos[ix])){
             numStr=numStr+data[0].charAt(i)+",";
             ix--;
            }else
             numStr=numStr+data[0].charAt(i);
           
       }
       if(data.length>1)
         numStr=numStr+"."+data[1];
       else
         numStr=numStr+".00";
       return(sign+numStr); 
       
      
}

function filterNumber(value){
	//This will remove characters from formatted numbers. ex: P3,500.00, result=3500.00
	var tmp="";
	for(i=0;i<value.length;i++){
		if((value[i]!=",")&&(value[i]!="P")&&(value[i]!=" "))
		 tmp=tmp+value[i];
	}
	return tmp;
}

function strToFloat(id){
	return parseFloat(filterNumber($("#"+id).val()));
}

function formatElementToNumber(selector){
	$("#"+selector).val(formatNumber(filterNumber($("#"+selector).val())));
}

//---For W2UI Components--------
function sumVGrid(gridname,col){
	var total=0.00;
	for(i=0;i<w2ui[gridname].records.length;i++){
		var amount=w2ui[gridname].getCellValue(i,col);
		total=total+amount;
	}
	return parseFloat(total);
}

function addSystemCodeToCombo(id,jobj){
	var data=JSON.parse(jobj.data);
   clearCombo(id);
   addDataToCombo(id,"","-select site-");
   for(i=0;i<data.length;i++){
       addDataToCombo(id,data[i].code_id,data[i].code_desc);
   }
}

function getCurrentDate(){
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();

	if(dd<10) {
   	 dd='0'+dd;
	} 
	if(mm<10) {
       mm='0'+mm;
	} 

   return yyyy+'-'+mm+'-'+dd;
	
}

function decodeMySQLDate(mydate){
	var yy=mydate.substr(0,4);
	var mm=mydate.substr(5,2);
	var dd=mydate.substr(8,2);
	var decoded={"month":mm,"year":yy,"day":dd};
	return decoded;
}

 function loadJSONToCombo(jobj,selector,defaultSelect){
     //Required JSON Format: var data=[{id:"",name:""}]
     /*
      * USAGE: loadJSONToCombo(jsonObj,"id of select element","-Select Your Choice-")
      */
    var scode=jobj.data;
    var ln = scode.length;
    //console.log(atype);
    clearCombo(selector);
   // console.log(scode.length);
    addDataToCombo(selector,"",defaultSelect);
          //console.log(ix);
    if(scode.length>0){
        
         
        
        for(ix=0;ix<ln;ix++){
          addDataToCombo(selector,scode[ix].id,scode[ix].name);
         }
        
        //console.log(atype[0].description);
    }
  }
  

 function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel,reportFileName) {
    //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
    var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
    
    var CSV = '';    
    //Set Report title in first row or line
    
    CSV += ReportTitle + '\r\n\n';

    //This condition will generate the Label/Header
    if (ShowLabel) {
        var row = "";
        
        //This loop will extract the label from 1st index of on array
        for (var index in arrData[0]) {
            
            //Now convert each value to string and comma-seprated
            row += index + ',';
        }

        row = row.slice(0, -1);
        
        //append Label row with line break
        CSV += row + '\r\n';
    }
    
    //1st loop is to extract each row
    for (var i = 0; i < arrData.length; i++) {
        var row = "";
        
        //2nd loop will extract each column and convert it in string comma-seprated
        for (var index in arrData[i]) {
            row += '"' + arrData[i][index] + '",';
        }

        row.slice(0, row.length - 1);
        
        //add a line break after each row
        CSV += row + '\r\n';
    }

    if (CSV == '') {        
        alert("Invalid data");
        return;
    }   
    
    //Generate a file name
    var fileName = reportFileName;
    //this will remove the blank-spaces from the title and replace it with an underscore
    //fileName += ReportTitle.replace(/ /g,"_");   
    
    //Initialize file format you want csv or xls
    var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
    
    // Now the little tricky part.
    // you can use either>> window.open(uri);
    // but this will not work in some browsers
    // or you will not get the correct file extension    
    
    //this trick will generate a temp <a /> tag
    var link = document.createElement("a");    
    link.href = uri;
    
    //set the visibility hidden so it will not effect on your web-layout
    link.style = "visibility:hidden";
    link.download = fileName + ".csv";
    
    //this part will append the anchor tag and remove it afttxt-num-maf-dueer automatic click
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}