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
<p class="general">

<?php
/* Code for saving Newjob information */
if($_POST['NewJob']=="true"){
	$query='insert into jobs (JobDescription, ClientInd, StartDate, Status, Employee) values ("'.$_POST['JobDescription'].'", "'.$_POST['ClientInd'].'", "'.$_POST['JobDate'].'", "OPEN", "'.$_SESSION['EmployeeInd'].'");';
	$res=dbquery($query);
}
/* Code for saving Job completed information */
if(isset($_POST['JobCompleted'])){
	$query='update jobs set EndDate='.$_POST['JobDate'].', Status="COMPLETED" where JobDescription="'.$_POST['JobDescription'].'";';
	$res=dbquery($query);
}

/* Code for saving Vehicle hours */
$query='select Ind from jobs where JobDescription="'.$_POST['JobDescription'].'";';
$res=dbquery($query);
while ($row = dbfetchassoc($res)){
	$Jobind=$row['Ind'];
}
$x=1;
while($x<16){
	if ($_POST['Vehicle'.$x]!=='false'){
		$query='insert into vehiclehours (JobInd, Vehicle, HoursForVehicle, FuelLitres, OperationDate, EmployeeInd) values ("'.$Jobind.'", "'.$_POST['Vehicle'.$x].'", "'.$_POST['VehicleHours'.$x].'", "'.$_POST['VehicleFuel'.$x].'", "'.$_POST['JobDate'].'", "'.$_SESSION['EmployeeInd'].'");';

		$res=dbquery($query);
  }
$x++;
}

/* Code for saving Material information */

$x=1;
while($x<16){
	if ($_POST['Material'.$x]!==''){
		$query='insert into materialsused (JobInd, Material, MaterialQuantity, MaterialPrice, SupplyDate, EmployeeInd) values ("'.$Jobind.'", "'.$_POST['Material'.$x].'", "'.$_POST['Quantity'.$x].'", "'.$_POST['Price'.$x].'", "'.$_POST['JobDate'].'", "'.$_SESSION['EmployeeInd'].'");';
		$res=dbquery($query);
  }
$x++;
}

/* Code for saving daily checklist information */
$x=1;
while($x<16){
	if ($_POST['Vehicle'.$x]!=='false' and isset($_POST['DailyCheckAllOK'.$x])){
		$query='insert into DailyChecklist (Vehicle, Date, Fluids, Wear_or_Damage, Wheels_Tracks_Tyres, Hydraulics, Attachments, Cabin, Load_Capacity_Plate, Brakes, Controls, Warning_Devices, Other, JobInd) values ("'.$_POST['Vehicle'.$x].'", "'.$_POST['JobDate'].'", "OK", "OK", "OK", "OK", "OK", "OK", "OK", "OK", "OK", "OK", "OK", '.$Jobind.');';
		$res=dbquery($query);
  }
$x++;
}

echo '<h3> Vehicle and Material Details successfully saved </h3>';
?>

</p>
</div>
</body>
</html>