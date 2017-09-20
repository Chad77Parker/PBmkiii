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
	function ListPBIndex() {
		window.open('PBIndexList.php','','location=no,menubar=no,status=no,toolbar=no')
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
<form action="CommitNewSparePart.php" method="POST">
<table border="1">
<tr><td class="general">Manufacturers Part Number<td width="30%"><input type="textbox" name="PartNumber" class="menu2button">';

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
echo '<tr><td class="general">Description<td colspan="3"><input type="textbox" class="menu2button" name="Description" >';
echo '<tr><td class="general">PBIndex<td width="30%">';
$query='select max(PBIndex) from parkerbros.SpareParts;';
$res=mysql_query($query);
if (!$res){
	die(mysql_error());
}
while ($row = mysql_fetch_assoc($res)) {
		$htmlstring=$row['max(PBIndex)']+1;
	}
	mysql_free_result($res);
echo '<input type="textbox" value="'.$htmlstring.'" class="menu2button" name="PBIndex">';
echo '<td colspan="2" align="center"><input type="button" value="View Existing PBIndex list" class="menu2button" onclick=ListPBIndex()><tr><td colspan="4" align="center"><input type="submit" value="Submit" class="menu3button"></table></form>';
?>
</p>
</div>

<div id="background">&nbsp</div>

</body>
</html>