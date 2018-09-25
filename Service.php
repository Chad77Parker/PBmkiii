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
if (checktimeout()){die('<div id= "scroller">You are not authorised to view this page or your session has expired please log in. <a href="ParkerBros.php">Return Home</a></div>');}
LoggedInMenu();
?>
<div id="background">&nbsp</div>
<div id="scroller">
<script type="text/javascript" language="javascript"> 
function NewSparePart(){
	window.location.assign("NewSparePart.php");
}
function NewServiceItem(){
	window.location.assign("NewServiceItem.php");
}
function LastServCheck(){
	window.location.assign("LastServiceCheck.php");
}
function ServiceVehicleHistory(){
	window.location.assign("ServiceVehicleHistory.php");
}
function ServiceReport(){
  window.location.assign("ServiceReport.php");
}
function DailyChecklistReport(){
  window.location.assign("SelectDailyChecklistClient.php");
}
function EnterDailyChecklist(){
  window.location.assign("NewDailyChecklist.php");
}
function NewVehicle() {
		       window.location.assign("adddata.php?table=vehicles&returnaddress=ParkerBros.php")
}
</script>   
<p class="general">
<h3>Select Service Option</h3>
<input type="button" value="Enter Service/Repair Report" onclick=ServiceReport() class="menu3button"><br>
<input type="button" value="Enter New Daily Checklist" onclick=EnterDailyChecklist() class="menu3button"><br>
<input type="button" value="Vehicle Service History" onclick=ServiceVehicleHistory() class="menu3button"><br>
<input type="button" value="View Daily Checklists" onclick=DailyChecklistReport() class="menu3button"><br>
<input type="button" value="Last Service Check" onclick=LastServCheck() class="menu3button"><br>
<?php
$Dailycheck= DailyCheckListCheck();
echo '<form action="ViewDailyChecklist.php" method="post"><input name="query" type="hidden" value="CurrentFaults"><input type="submit" class="menu3button" value="View Daily Checklist, Current Faults = '.$Dailycheck.'"></form>';

echo '<input type="button" value="New Spare Part" onclick=NewSparePart() class="menu3button"><br>
<input type="button" value="New Service Item" onclick=NewServiceItem() class="menu3button"><br>
<input type="button" onclick=NewVehicle() value="New Vehicle" class="menu3button"><br>
</p>
</div>
</body>
</html>
';
?>