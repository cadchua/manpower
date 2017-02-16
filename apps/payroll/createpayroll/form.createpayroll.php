<?php
require_once "../../../header/h.secure.php";
?>

</head>
<body style="font-size: 11px">
	  <div id="div-tabs-createpayroll" style="border:1px solid silver;  overflow: auto;"></div>
	  
	  <div id="div-tab-createpayroll" style="border: 1px solid silver;height: 500px;width: 100%;">
	    <div style="margin-left:5px;margin-top: 5px">
	  	<div id="toolbar-createpayroll" style="padding: 4px; border: 1px solid silver; border-radius: 3px"></div>
	  	<div id="grid-createpayroll" style="width: 400px;height:450px"></div>
	  	</div>
	  	
	  </div>
	  
	  <div id="div-tab-checkpayroll" style="display:none;border: 1px solid silver;height: 500px;width: 100%;">
	    <div style="margin-left:5px;margin-top: 5px">
          <div id="toolbar-checkpayroll" style="padding: 4px; border: 1px solid silver; border-radius: 3px"></div>
          <div id="grid-checkpayroll" style="width: 900px;height:450px"></div>
           
             <img id="img-employee" width="150" height="150" style="border: 1px solid silver;position:absolute; margin-top: -425px;margin-left: 920px"/>
             <span id="lbl-employee-id" style="position: absolute;margin-top: -260px;margin-left: 920px;font-size:12px"></span>
             <span id="lbl-employee" style="position: absolute;margin-top: -245px;margin-left: 920px;font-size:12px"></span>
             <span id="lbl-jobtitle" style="position: absolute;margin-top: -230px;margin-left: 920px;color: #00356A;font-size:12px"></span>
           
        
         </div>
       </div>
	  
	  <!---DIALOGS--->
	  <div id="dlg-newpayroll" title="New Payroll" style="display:none">
	  	<table>
	  		<tr>
	  			<th align="right">From:</th>
	  			<td><input type="text" id="txt-date-from" value="" /></td>
	  		</tr>
	  		<tr>
	  			<th align="right">To:</th>
	  			<td><input type="text" id="txt-date-to" value="" /></td>
	  		</tr>
	  		<tr>
	  			<th align="right">No. of Reg. Work Days:</th>
	  			<td><input type="text" id="txt-work-days" value="" /></td>
	  		</tr>
	  	</table>
	  	
	  </div>
	  <script type="text/javascript" src="script.createpayroll.js"></script>
</body>
</html>