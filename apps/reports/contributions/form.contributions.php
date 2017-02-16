<?php
require_once "../../../header/h.development.php";
?>

</head>
<body style="font-size: 12px">
     
      <div id="div-tab-dayswork" style="border:1px solid silver;  overflow: auto;"></div>
      <div style="border: 1px solid silver;height: 550px;width: 100%">
            <span id="lbl-payid" style="display:none"></span>
            <h3 style="margin-left: 10px;font-size: 14px">  Acitve Payroll Date: <span id="lbl-paydate" style="font-weight: 100">2016-02-01 to 2016-02-15</span> </h3>
            <hr>
            <div id="grid-branches" style="position:absolute;margin-top:35px; margin-left:5px;height:450px;width:730px;"></div>
            
            
          
      </div>
      
      <!---Dialogs---->
      <div id="div-confirm" title="Confirm Action" style="display:none">
          <p id="p-confirm-message"></p>
      </div>
      <script type="text/javascript" src="script.dayswork.js"></script>
</body>
</html>