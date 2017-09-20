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
echo '

<div id="scroller">

<p class="general">
<form action="CommitNewServiceItem.php" method="POST">
<table border="1">
<tr><td class="general">Service Item<td colspan="3"><input type="textbox" name="Item" class="menu2button">';

$htmlstring='';
echo '<td class="general">Vehicle<td>';
echo '<select name="Vehicle">';
$query='select vehicles.Name, Make, Model, vehicles.Ind from parkerbros.vehicles left outer join parkerbros.vehiclehours
on vehiclehours.vehicle=vehicles.ind where StatusSelect="ACTIVE" group by vehicles.ind order by max(OperationDate) desc ;';
$res=mysql_query($query);
if (!$res){
	die(mysql_error());
}
echo '<option value="false">Please Select Vehicle</option>';
while ($row = mysql_fetch_assoc($res)) {
		$htmlstring=$htmlstring.'<option value="'.$row['Ind'].'">'.$row['Name'].'.  '.$row['Make'].' '.$row['Model'].'</option>';
	}
	mysql_free_result($res);
echo $htmlstring.'</select>';
echo '<tr><td class="general">Service Unit Type<td ><select name="Units"><option value="Hours">Hours</option><option value="Months">Months</option><option value="Kilometers">Kilometers</option></select><td class="general">Interval between services<td width="20%"><input type="textbox" class="menu2button" name="Interval">';

$htmlstring='';
echo '<td class="general">PartNumber<td>';
echo '<select name="PartNumberRef">';
$query='select * from parkerbros.SpareParts order by Vehicle;';
$res=mysql_query($query);
if (!$res){
	die(mysql_error());
}
echo '<option value="false">Please Select Part</option>';
while ($row = mysql_fetch_assoc($res)) {
		$queryVehicle='select * from parkerbros.Vehicles where Ind='.$row['Vehicle'].';';
		$res2=mysql_query($queryVehicle);
		if (!$res2){
			die(mysql_error());
		}
		while($row2=mysql_fetch_assoc($res2)){
			$htmlstring=$htmlstring.'<option value="'.$row['PBInd'].'">'.$row2['Make'].' '.$row2['Model'].'.  '.$row['Description'].' '.$row['PartNumber'].'</option>';
		}
		mysql_free_result($res2);
	}
	mysql_free_result($res);
echo $htmlstring.'</select>';
echo '<tr><td class="general">Procedure<td colspan="5"><input type="textbox" name="Procedure" class="menu2button"><tr><td colspan="6" align="center"><input type="submit" value="Submit" class="menu3button"></table></form>';
?>
</p>
</div>

<div id="background">&nbsp</div>

</body>
</html>