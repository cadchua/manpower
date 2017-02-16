<?php
require_once "../../../header/h.development.php";
?>

</head>
<body style="font-size: 11px">
     <div id="div-tabs-payrates" style="border:1px solid silver;  overflow: auto;"></div>
     <!---LIST OF EMPLOYEES--->
     <div style="border: 1px solid silver;height: 600px;width: 100%">
     <div id="tbl-employee-list" style="margin-top:10px;margin-left:10px; width:95%;height:550px;"></div>
     </div>
     
     <!---Pay Rate Dialog---->
     <div id="dlg-payrates" title="Employee Pay Rates" style="display: none">
         <div style="width:100%;height:135px;margin-top: 5px;margin-left: 5px;">
             <img id="img-employee" width="130" height="130" style="border: 1px solid silver;position:absolute; margin-top: 5px;margin-left: 5px"/>
             <span id="lbl-employee-id" style="position: absolute;margin-top: 5px;margin-left: 145px"></span>
             <span id="lbl-employee" style="position: absolute;margin-top: 25px;margin-left: 145px"></span>
             <span id="lbl-jobtitle" style="position: absolute;margin-top: 45px;margin-left: 145px"></span>
             
             
             
         </div>
         <div>
             <hr>
             
             <div id="div-tabs-payrate-settings" ></div>
             
             <div id="div-newpayrate" style="display:none">
             <form id="payrate-frm">
             <table style="margin-top: 10px">
                 <tr>
                     <td align="right">Effective Date</td>
                     <td><input type="text" id="txt-date-effective" name="edate" value=""/></td>
                 </tr>
                 <tr>
                     <td align="right">Pay Type</td>
                     <td><select id="txt-paytype" name="paytype"></select></td>
                 </tr>
                 <tr>
                     <td align="right">Account No.</td>
                     <td><input type="text" id="txt-accountno" name="accountno" value=""/></td>
                 </tr>
                 <tr>
                     <td align="right">Daily Rate</td>
                     <td><input type="text" id="txt-num-dailyrate" name="dailyrate" value="0.00"/></td>
                 </tr>
                 <tr>
                     <td align="right">Monthly Rate</td>
                     <td><input type="text" id="txt-num-monthlyrate" name="monthlyrate" value="0.00"/></td>
                 </tr>
                 <tr>
                     <td align="right">Pay Frequency</td>
                     <td><select id="txt-frequency" name="payfrequency"></select></td>
                 </tr>
                 <!--
                 <tr>
                     <td align="right">SSS Bracket</td>
                     <td>
                         <input type="text" id="txt-sss" name="sss" value="" readonly="readonly" style="width: 50px" />
                         <input type="button" id="btn-sss" value="..."/>
                         
                     </td>
                 </tr>
                 <tr>
                     <td align="right">PhilHealth Bracket</td>
                     <td>
                       <input type="text" id="txt-philhealth" name="philhealth" value="" readonly="readonly" style="width: 50px" />
                       <input type="button" id="btn-philhealth" value="..."/>
                     </td>
                 </tr>
                 <tr>
                     <td align="right">BIR Tax Bracket</td>
                     <td>
                        <input type="text" id="txt-bir" name="bir" value="" readonly="readonly" style="width: 50px" />
                        <input type="button" id="btn-bir" value="..."/>
                     </td>
                 </tr>
                 --->
             </table>
             </form>
             </div><!--DIV NEW PAYRATE-->
             
             <div id="div-payratehistory" style="margin-top: 10px;margin-left: 10px;display:none">
                <div id="payrate-grid" style="width:100%;height:250px"></div>
             </div><!---PAYRATE HISTORY---->
         </div>
         
     </div>
     
     <div id="dlg-sss" style="display:none" title="SSS Bracket">
         <div id="sss-grid" style="width:100%;height:100%"></div>
     </div>
     <div id="dlg-philhealth" style="display:none" title="PhilHealth Bracket">
         <div id="philhealth-grid" style="width:100%;height:100%"></div>
     </div>
     
     <div id="dlg-bir" style="display:none" title="BIR Bracket">
         <div id="bir-grid" style="width:100%;height:100%"></div>
     </div>
     
     
    <script type="text/javascript" src="script.payrates.js"></script>
</body>