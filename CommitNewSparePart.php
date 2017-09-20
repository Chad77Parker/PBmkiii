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
?>

<div id="scroller">

<p class="general">
<?php
$query='INSERT INTO parkerbros.SpareParts (PBIndex, PartNumber, Description, Vehicle) VALUES ("'.$_POST["PBIndex"].'", "'.$_POST["PartNumber"].'", "'.$_POST["Description"].'", "'.$_POST["Vehicle"].'");';

$result = mysql_query($query) or die("Could not execute query: ".$query."  Error: ". mysql_error());
	echo '<h3>New Spare Part successfully saved !</h3><br>';
?>
</p>
</div>

<div id="background">&nbsp</div>

</body>
</html>