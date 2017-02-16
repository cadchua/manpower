<?php
require_once "../../../header/h.development.php";
?>
</head>
<body style="font-size: 12px">
   <div id="progressbar" style="display:none"><div class="progress-label">Processing...</div></div>
     Check Payroll
     <div id="div-tabs-approvepayroll" style="border:1px solid silver;  overflow: auto;"></div>
      
      <div id="div-tab-approvepayroll" style="border: 1px solid silver;height: 400px;width: 100%;">
        <div style="margin-left:5px;margin-top: 5px">
         <div id="grid-payrollsummary" style="width: 800px;height:375px"></div>
        </div>
        
        
      </div>
      
      <div id="div-tab-payrolldetails" style="display:none;border: 1px solid silver;height: 400px;width: 100%;">
        <div style="margin-left:5px;margin-top: 5px">
     
          <div id="grid-payrolldetails" style="width: 900px;height:375px"></div>
             <img id="img-employee" width="150" height="150" style="border: 1px solid silver;position:absolute; margin-top: -325px;margin-left: 920px"/>
             <span id="lbl-employee-id" style="position: absolute;margin-top: -160px;margin-left: 920px;font-size:12px"></span>
             <span id="lbl-employee" style="position: absolute;margin-top: -145px;margin-left: 920px;font-size:12px"></span>
             <span id="lbl-jobtitle" style="position: absolute;margin-top: -130px;margin-left: 920px;color: #00356A;font-size:12px"></span>
         </div>
       </div>
       <div style="margin-left:5px;margin-top:5px">
            <input type="button" id="btn-check" value="CHECKED" style="width:180px;height:25px"/>
            <input type="button" id="btn-recheck" value="UN-CHECKED" style="width:180px;height:25px"/>
            
        </div>
       <script type="text/javascript" src="script.check.js"></script>
</body>
</html>