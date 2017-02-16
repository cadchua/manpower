<?php
require_once "../../../header/h.development.php";
?>
</head>
<body style="font-size: 12px">
    <h3>Net Pay Per Type</h3>
     Payroll Dates <select id="txt-paydates"></select>&nbsp;&nbsp;
     Pay Type <select id="txt-pay-types"></select>
     <div id="div-tabs-netpay" style="border:1px solid silver;  overflow: auto;"></div>
      
      <div id="div-tab-netpay" style="border: 1px solid silver;height: 420px;width: 100%;">
        <div style="margin-left:5px;margin-top: 5px">
         <div id="grid-netpay" style="width: 800px;height:375px"></div>
        </div>
         <div style="margin-left:5px;margin-top:5px">
            <input type="button" id="btn-download" value="DOWNLOAD" style="width:180px;height:25px"/>
            
        </div> 
        
      </div>
        
         
       <script type="text/javascript" src="script.netpaypertype.js"></script>
</body>
</html>