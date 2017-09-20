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
<div id="scroller">
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


echo '<table><tr><td class="SubHeading3">PBIndex<td class="SubHeading3">Part Number<td 	class="SubHeading3">Vehicle<td class="SubHeading3">Description';
$query='select * from parkerbros.SpareParts;';
$res=mysql_query($query);
if (!$res){
	die(mysql_error());
}
while ($row = mysql_fetch_assoc($res)) {
	echo '<tr><td class="general">'.$row['PBIndex'].'
		<td class="general">'.$row['PartNumber'].'
		<td class="general">'.$row['Vehicle'].'
		<td class="general">'.$row['Description'];
}
echo '</table>';

?>
</div>
<div id="background">&nbsp</div>


</body>
</html>