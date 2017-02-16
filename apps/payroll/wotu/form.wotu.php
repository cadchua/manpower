<?php
require_once "../../../header/h.secure.php";
?>

</head>
<body style="font-size: 10px">
     
      <div id="div-tab-wotu" style="border:1px solid silver;  overflow: auto;"></div>
      <div style="border: 1px solid silver;height: 500px;width: 100%">
            <span id="lbl-payid" style="display:none"></span>
            <div id="progressbar" style="display:none"><div class="progress-label">Processing...</div></div>
            <h3 style="margin-left: 10px;font-size: 14px">  
                Acitve Payroll Date: <span id="lbl-paydate" style="font-weight: 100"></span> 
              
                <span id="lbl-processmessage"></span>
            </h3>
            <hr>
            <div id="toolbar-employees" style=" margin-left:5px; padding: 4px; border: 1px solid silver; border-radius: 4px;width:800px;"></div>
            <div id="grid-employees" style=" margin-left:5px;height:400px;width:800px;"></div>
            
            
            <div style="position:absolute; width:300px;height:135px;margin-top: 5px;margin-left: 750px;font-size: 16px">
             <img id="img-employee" width="200" height="200" style="border: 1px solid silver;position:absolute; margin-top: 5px;margin-left: 0px"/>
             <span id="lbl-employee-id" style="position: absolute;margin-top: 5px;margin-left: 210px;font-size:24px"></span>
             <span id="lbl-employee" style="position: absolute;margin-top: 25px;margin-left: 210px;font-size:24px"></span>
             <span id="lbl-jobtitle" style="position: absolute;margin-top: 55px;margin-left: 210px;color: #00356A;font-size:24px"></span>
            </div>
            <!--
            <div id="toolbar-wotu" style="position:absolute; margin-left:515px;margin-top:150px; padding: 4px; border: 1px solid silver; border-radius: 3px;width:400px"></div>
            <div id="grid-wotu" style="position:absolute;margin-top:185px; margin-left:515px;height:350px;width:400px;"></div>
          -->
      </div>
      
      <!---Dialogs---->
      <div id="div-confirm" title="Confirm Action" style="display:none">
          <p id="p-confirm-message"></p>
      </div>
      <script type="text/javascript" src="script.wotu.js"></script>
</body>
</html>