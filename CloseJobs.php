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
echo '<h3>The following jobs have been closed:</h3><table border=2 class="threequarterwidth">';
/* Code for saving Job completed information */
$firstflag=true;
$x=$_POST['recnumber'];
$query='update jobs set EndDate="'.date("Y-m-d").'", Status="COMPLETED" where Ind in (';
$query2='select * from jobs where Ind in (';
while($x>-1){
	if ($firstflag!=true){
		$query=$query.', ';
		$query2=$query2.', ';
	}
	$firstflag=false;
	$query=$query.$_POST['check'.$x];
	$query2=$query2.$_POST['check'.$x];
	$x-=1;
}
$query=$query.');';
$query2=$query2.');';
echo $query;
echo $query2;
/* $res=mysql_query($query, $link);
if (!$res){
	die(mysql_error());
}

$res=mysql_query($query2);
if(!$res){
	die(mysql_error());
}
while($row=mysql_fetch_assoc($res)){
	$ClientQuery='select * from contacts where Ind="'.$row['ClientInd'].'";';
	$ClientRes=mysql_query($ClientQuery);
	if(!$ClientRes){
		die(mysql_error());
	}
	while($ClientRow=mysql_fetch_assoc($ClientRes)){
		echo 	'<tr><td class="general">'.$row['JobDescription'];
		echo 	'<td class="general">'.$ClientRow['Company'].'.  '.$ClientRow['ContactTitleOrPosition'].', '.$ClientRow['ContactFirstName'].' '.$ClientRow['ContactLastName'];
	}

}
echo 	'</table>';
*/
?>

</div>






<div id="background">&nbsp</div>

</body>
</html>