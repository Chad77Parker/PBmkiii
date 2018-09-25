<?php

require_once 'GlobalFunctions.php';
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
if (!checktimeout()){LoggedInMenu();}


?>
<div id="background">&nbsp</div>
<div id="scroller">
<?php
//Only display the following sectin if user is logged in and Permission = ADMIN ***********************
if (isset($_SESSION['loggedin']) && !checktimeout() && $_SESSION['Permission']=='ADMIN' && $_SESSION['loggedin']){
if (DailyCheckListCheck() > 0){
   echo '<h3>There are '.DailyCheckListCheck().' faults reported in Daily Checklists</h3>';
   echo '<form action="ViewDailyChecklist.php" method="post"><input name="query" type="hidden" value="CurrentFaults"><input type="submit" value="View Daily Checklists"></form>';
 }
if(RequireBackup()){
  echo '<br><h3>Database requires backup. <a href="Backup.php">Click here to execute backup. </a></h3><br>';
}
}
//End of logged in display******************************************************
?>
</div>


</body>
</html>