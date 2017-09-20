<?php
session_start();
include 'GlobalFunctions.php';
echo '<html>
      <head>
      <title>Commit Job Hours</title>';
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
/* Code for saving Newjob information */
if($_POST['NewJob']=="true"){
	$query='insert into jobs (JobDescription, ClientInd, StartDate, Status, Employee) values ("'.$_POST['JobDescription'].'", "'.$_POST['ClientInd'].'", "'.$_POST['JobDate'].'", "OPEN", "'.$_SESSION['EmployeeInd'].'");';
	$res=mysql_query($query, $link);
	if (!$res){
		die(mysql_error());
	}
}
/* Code for saving Job completed information */
if(isset($_POST['JobCompleted'])){
	$query='update jobs set EndDate='.$_POST['JobDate'].', Status="COMPLETED" where JobDescription="'.$_POST['JobDescription'].'";';
	$res=mysql_query($query, $link);
	if (!$res){
		die(mysql_error());
	}
}

/* Code for saving Vehicle hours */
$query='select Ind from jobs where JobDescription="'.$_POST['JobDescription'].'";';
$res=mysql_query($query, $link);
	if (!$res){
		die(mysql_error());
	}
while ($row = mysql_fetch_assoc($res)){
	$Jobind=$row['Ind'];
}


$x=1;

while($x<16){
	if ($_POST['Vehicle'.$x]!=='false'){
		$query='insert into vehiclehours (JobInd, Vehicle, HoursForVehicle, FuelLitres, OperationDate, EmployeeInd) values ("'.$Jobind.'", "'.$_POST['Vehicle'.$x].'", "'.$_POST['VehicleHours'.$x].'", "'.$_POST['VehicleFuel'.$x].'", "'.$_POST['JobDate'].'", "'.$_SESSION['EmployeeInd'].'");';

		$res=mysql_query($query, $link);
		if (!$res){
			die(mysql_error());
		}

	}
$x++;
}

/* Code for saving Material information */

$x=1;
while($x<16){
	if ($_POST['Material'.$x]!==''){
		$query='insert into materialsused (JobInd, Material, MaterialQuantity, MaterialPrice, SupplyDate, EmployeeInd) values ("'.$Jobind.'", "'.$_POST['Material'.$x].'", "'.$_POST['Quantity'.$x].'", "'.$_POST['Price'.$x].'", "'.$_POST['JobDate'].'", "'.$_SESSION['EmployeeInd'].'");';
		$res=mysql_query($query, $link);
		if (!$res){
			die(mysql_error());
		}
	}
$x++;
}

/* Code for saving daily checklist information */
$x=1;
while($x<16){
	if ($_POST['Vehicle'.$x]!=='false' and isset($_POST['DailyCheckAllOK'.$x])){
		$query='insert into DailyChecklist (Vehicle, Date, Fluids, Wear_or_Damage, Wheels_Tracks_Tyres, Hydraulics, Attachments, Cabin, Load_Capacity_Plate, Brakes, Controls, Warning_Devices, Other, JobInd) values ("'.$_POST['Vehicle'.$x].'", "'.$_POST['JobDate'].'", "OK", "OK", "OK", "OK", "OK", "OK", "OK", "OK", "OK", "OK", "OK", '.$Jobind.');';
		$res=mysql_query($query, $link);
		if (!$res){
			die(mysql_error());
		}
 	}
$x++;
}


echo '<h3> Vehicle and Material Details successfully saved </h3>';

?>

</p>
</div>




<div id="background">&nbsp</div>
</body>
</html>