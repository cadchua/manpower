 <?php

  session_start();
  include("../header/h.desktop.php");
  include("../config/conf.db.php");
  include("../classes/class.dbquery.php");

  $dbQ=new dbQuery();
  $dbQ->connect($conf);
 ?>
  <script type="text/javascript">
   function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
   $("document").ready(function(e){
         $('.btn-menu').sideNav({
           menuWidth: 300,
            closeOnClick: true,

         });
    
    });
  </script>
  
 </head>
 <body >
   <!--<a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>-->
   
  
  
   
  <div class="navbar-fixed">
   <nav>
    <div class="nav-wrapper grey" >
      
      <a href="#!" class="brand-logo white-text center" style="font-size: 24px;font-weight: 600" onclick="loadApp('home/ui.home.php')">Manpower System</a>
      <ul >
        <li> <a href="#!" id="btn-menu" data-activates="slide-out" class="btn-menu black-text" ><i class="material-icons">menu</i></a></li>
        
      </ul>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
       <li>
          <a href="#!" class="black-text">
            <i class="material-icons left">perm_identity</i> 
             <span id="lbl-userinfo">Jon Doe</span>
            </a>
           </li>
       </ul>
    </div>
  </nav>
  </div>
  
  <div class="section">

    <iframe id="app-container" src="home/ui.home.php" style="border:none;height: 85vh;" 
    width="100%"  align="middle" >
      
    </iframe>
  </div>
  
  <!--SLIDE OUT-->
  <ul id="slide-out" class="side-nav">
    <li>
      <div class="userView">
       <a href="#!user"><img class="circle" src="../images/ic_account_box_black_48dp.png"></a>
       <a href="#!name"><span class="black-text name" id="lbl-fullname">John Doe</span></a>
       <a href="#!email"><span class="black-text email" id="lbl-workgroup">Accounting</span></a>
      </div>
    </li>
    <!--ITERATE HERE-->
    <?php
      $dbQ->sqlStatement="SELECT category_id,category_name,materialize_icon FROM module_category ORDER BY sequence";
      $dbQ->querySQL("");
      $data=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);
      for($i=0;$i<count($data);$i++){
    ?>
    <li class="no-padding">
      <ul class="collapsible collapsible-accordion">
        <li>
        <a class="collapsible-header">
        <i class="material-icons"><?php print $data[$i]['materialize_icon']; ?></i>
           <?php print $data[$i]['category_name']; ?>
        </a> 
        <div class="collapsible-body">
         <ul>
           <?php
             $dbQ->sqlStatement="SELECT menu_name,file_location FROM module_menu ". 
             " WHERE menu_category='".$data[$i]['category_id']."' ORDER BY sequence ";
             $dbQ->querySQL("");
             $menuData=$dbQ->stmt->fetchAll(PDO::FETCH_ASSOC);

             for($mi=0;$mi<count($menuData);$mi++){
           ?>
           <li>
             <a href="#"  onclick="loadApp('<?php print $menuData[$mi]['file_location']; ?>')" >
             <?php print $menuData[$mi]['menu_name']; ?>
             </a>
            </li>
             <?php
             }
             ?>
         </ul>
        </div>
        </li>
      </ul>
    
    </li>
    <?php
     }//end of for data
    ?>
    <!--END OF DB DRIVEN MENU -->
    <li><div class="divider"></div></li>
    <li><a class="subheader">Change Password</a></li>
    <li><a class="waves-effect" href="../logout.php">Log Out</a></li>
  </ul>
  <!--END OF SLIDE OUT-->

    


   <script type="text/javascript" src="script.desktop.js"></script>
 
 </body>
 
 </html> 