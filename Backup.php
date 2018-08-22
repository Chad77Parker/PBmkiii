<?php

require 'GlobalFunctions.php';
require_once 'data/dbintegration.php';

echo '<html>
      <head>
      <title>Parker Bros Earthmoving Pty Ltd</title>';
echo MobileDetect(); /*must be in html header*/
echo '</head>
     <body>

     <img id="topbanner" src="images\pbbanner1.jpg"  border="0">
     <div id="topbanner">
     Parker Bros Earthmoving Pty Ltd.
     </div>

     <div id="background">&nbsp</div>';

StandardMenu();
if (checktimeout()){die('<div id= "scroller">You are not authorised to view this page or your session has expired please log in. <a href="ParkerBros.php">Return Home</a></div>');}
LoggedInMenu();

?>
//begin page specific code******************************************************
<div id="background">&nbsp</div>
<div id="scroller">


<p class="general">
<?php
if (backupdatabase() == ""){
   $query='update parkerbros.employees set LastBackup="'.date('Y/m/d H:i').'" where Ind='.$_SESSION['EmployeeInd'].';';
   $res=dbquery($query);
   if(!$res){
      die('An error occured');
   }
   echo '<h3> <a href="ParkerBros.php">DataBase successfully backed up. Return Home</a></h3>';
}
?>
<h3> <a href="ParkerBros.php">DataBase successfully backed up. Return Home</a></h3>
</p>
</div>







</body>
</html>