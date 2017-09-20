<?php
session_start();
include 'GlobalFunctions.php';
echo '<html>
      <head>
      <title>Parker Bros Earthmoving Pty Ltd</title>';
echo MobileDetect();

?>
  <meta name="description" content="Earthmoving">
  <meta name="keywords" content="earthmoving, dams, driveways, soil conservation, erosion control, water cartage, water delivery, road construction, earthworks">
</head>
<body>

<img id="topbanner" src="images\pbbanner1.jpg"  border="0">
<div id="topbanner">
Parker Bros Earthmoving Pty Ltd.
</div>


<div id="scroller">
<p class="general">
This site is under construction and is only for testing purposes no information contained herein is of any factual events or persons

</p>
<?php
//Only display the following sectin if user is logged in and Permission = ADMIN ***********************
if (isset($_SESSION['loggedin']) && !checktimeout() && $_SESSION['Permission']=='ADMIN' && $_SESSION['loggedin']){
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
 if (DailyCheckListCheck($link) > 0){
   echo '<h3>There are '.DailyCheckListCheck($link).' faults reported in Daily Checklists</h3>';
   echo '<form action="ViewDailyChecklist.php" method="post"><input name="query" type="hidden" value="CurrentFaults"><input type="submit" value="View Daily Checklists"></form>';
 }
if(RequireBackup()){
  echo '<br><h3>Database requires backup. <a href="Backup.php">Click here to execute backup. </a></h3><br>';
}
}
//End of logged in display******************************************************
?>
</div>
<?php
StandardMenu();
if (isset($_SESSION['loggedin']) and !checktimeout() and ($_SESSION['loggedin'])){
	LoggedInMenu();
}


?>
<div id="background">&nbsp</div>

</body>
</html>