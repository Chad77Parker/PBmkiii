<?php
session_start();
include 'GlobalFunctions.php';
if ($_SESSION['loggedin']!=true){
die('You are not authorised to view this page');
}
if (checktimeout()){
	die('Your connection has expired please log back in.<a href="ParkerBros.php">Return Home</a>');
}
echo '<html>
      <head>
      <title>Parker Bros Earthmoving Pty Ltd</title>';
echo MobileDetect();
?>
<script type="text/javascript" language="javascript"> 
function NewSparePart(){
	window.location.assign("NewSparePart.php");
}
function NewServiceItem(){
	window.location.assign("NewServiceItem.php");
}
function ServiceVehicle(){
	window.location.assign("ServiceVehicle.php");
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
</script>   
</head>
<body>

<img id="topbanner" src="images\pbbanner1.jpg"  border="0">
<div id="topbanner">
Parker Bros Earthmoving Pty Ltd.
</div>


<?php
//connect to database
$link = @mysql_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['pass']);
if (!$link) {
    die('Could not connect to MySQL server: ' . mysql_error());
}
$dbname = $_SESSION['datab'];
$db_selected = mysql_select_db($dbname, $link);
if (!$db_selected) {
    die("Could not set $dbname: " . mysql_error());
}



StandardMenu();
if ($_SESSION['loggedin'] and !checktimeout()){
	LoggedInMenu();
}

echo '
<div id="scroller">
<p class="general">
<h3>Select Service Option</h3>
<input type="button" value="Enter Service/Repair Report" onclick=ServiceReport() class="menu3button"><br>
<input type="button" value="Service Vehicle" onclick=ServiceVehicle() class="menu3button"><br>
<input type="button" value="Vehicle Service History" onclick=ServiceVehicleHistory() class="menu3button"><br>
<input type="button" value="Enter New Daily Checklist" onclick=EnterDailyChecklist() class="menu3button"><br>
<input type="button" value="View Daily Checklists" onclick=DailyChecklistReport() class="menu3button"><br>';
$Dailycheck= DailyCheckListCheck($link);
echo '<form action="ViewDailyChecklist.php" method="post"><input name="query" type="hidden" value="CurrentFaults"><input type="submit" class="menu3button" value="View Daily Checklist, Current Faults = '.$Dailycheck.'"></form>';

echo '<input type="button" value="New Spare Part" onclick=NewSparePart() class="menu3button"><br>
<input type="button" value="New Service Item" onclick=NewServiceItem() class="menu3button"><br>

</p>
</div>






<div id="background">&nbsp</div>

</body>
</html>
';
?>