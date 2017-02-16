<?php
require_once "../../../header/h.development.php";
?>
</head>
<body style="font-size: 12px">
    <h3>Payroll Summary Report</h3>
     Payroll Dates <select id="txt-paydates"></select>
     <div id="div-tabs-approvepayroll" style="border:1px solid silver;  overflow: auto;"></div>
      
      <div id="div-tab-approvepayroll" style="border: 1px solid silver;height: 420px;width: 100%;">
        <div style="margin-left:5px;margin-top: 5px">
         <div id="grid-payrollsummary" style="width: 800px;height:375px"></div>
        </div>
         <div style="margin-left:5px;margin-top:5px">
            <input type="button" id="btn-download-summary" value="DOWNLOAD SUMMARY" style="width:180px;height:25px"/>
            
        </div> 
        
      </div>
        
      <div id="div-tab-payrolldetails" style="display:none;border: 1px solid silver;height: 420px;width: 100%;">
        <div style="margin-left:5px;margin-top: 5px">
     
          <div id="grid-payrolldetails" style="width: 1100px;height:375px"></div>
         </div>
         
         <div style="margin-left:5px;margin-top:5px">
            <input type="button" id="btn-download-details" value="DOWNLOAD DETAILS" style="width:180px;height:25px"/>
            
        </div> 
       </div>
        
       <script type="text/javascript" src="script.payrollsummary.js"></script>
</body>
</html>