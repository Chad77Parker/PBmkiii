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
<?php
echo '<h3>Jobs that are currently open.<br><br>Select jobs that have been completed<br></h3><form action="CompletedJobs.php" method="post" ><table border=2 class="threequarterwidth">';
$query='select * from jobs where Status="OPEN";';
$res=mysql_query($query);
if(!$res){
	die(mysql_error());
}
$x=0;
while($row=mysql_fetch_assoc($res)){
	$ClientQuery='select * from contacts where Ind="'.$row['ClientInd'].'";';
	$ClientRes=mysql_query($ClientQuery);
	if(!$ClientRes){
		die(mysql_error());
	}
	while($ClientRow=mysql_fetch_assoc($ClientRes)){
		echo 	'<tr><td><input type="checkbox" name="check'.$x.'" value="'.$row['Ind'].'">
			<td class="general">'.$row['JobDescription'];
		echo 	'<td class="general">'.$ClientRow['Company'].'.  '.$ClientRow['ContactTitleOrPosition'].', '.$ClientRow['ContactFirstName'].' '.$ClientRow['ContactLastName'];
	}

}
echo 	'<input type="hidden" name="recnumber" value="'.$x.'"><tr><td colspan="3"><center><input type="submit" value = "Save jobs as completed."></center></table></form>';

?>

</div>






<div id="background">&nbsp</div>

</body>
</html>