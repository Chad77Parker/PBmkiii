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

<script type="text/javascript" language="javascript">
function DailyChecklistNotOK(VehicleNum){
      VehicleInd = document.getElementById( "Vehicle"+VehicleNum).value;
      JobDesc = document.getElementById("JobDesc").value;
      window.open('NewDailyChecklist.php?Vehicle='+VehicleInd+'&JobDesc='+JobDesc,'','location=no,menubar=no,status=no,toolbar=no,height=1050,width=1680');

}
</script>
<?php
echo '<div id="header">';

/*Code to add employee hours */
 if ($_POST['StartTime']!=="0") {
	$query='insert into employeehours2 (Employee, StartTime, EndTime, Lunch, EmployeeHoursDate) values ('.$_SESSION['EmployeeInd'].', '.$_POST['EmployeeHoursDate'].$_POST['StartTime'].', '.$_POST['EmployeeHoursDate'].$_POST['EndTime'].', '.$_POST['EmployeeHoursDate'].$_POST['Lunch'].', '.$_POST['EmployeeHoursDate'].');';

	$res=dbquery($query);
echo '<h3>Saved employee Daily Hours for '.$_SESSION['FirstName'].' '.$_SESSION['LastName'].'.</h3>';
}

/* Code to test if new job descrition already exists */
if (isset($_POST['NewJob'])) {
	$query='select * from jobs where JobDescription="'.$_POST['NewJobDescription'].'";';
	$res=dbquery($query);
	if (dbnumrows($res)!=0){
		echo '</table><br><h3>Job description '.$_POST['NewJobDescription'].' already exists please choose another description. <a href="JobHoursClient&DateSelect.php"> Enter Job Details only.</a></h3></div><div id="background"></div>';
		die();
	}
} 
/* Code for Header table */
echo	'<h3>Enter Vehicle and Material details</h3>';	
echo	'<form action="CommitJobHours.php" method="post" >
	<table border="1">
	<tr><td class="general">Client<td colspan="7" class="general">';
$query='select ContactFirstName, ContactLastName, ContactTitleOrPosition, Ind, Company from contacts where Ind='.$_POST['ClientInd'].';';
$res=dbquery($query);
while ($row = dbfetchassoc($res)) {
		$htmlstring=$row['Company'].'.   Person in charge '.$row['ContactTitleOrPosition'].', '.$row['ContactFirstName'].' '.$row['ContactLastName'];
	}
echo $htmlstring;
echo '<input type="hidden" name="ClientInd" value="'.$_POST['ClientInd'].'"><td rowspan="2"><input type="submit" value="Submit">';
echo '<tr><td class="general">Job Description<td class="general"><input name="JobDescription" id="JobDesc" value="';
 if (isset($_POST['NewJob'])) {
	echo $_POST['NewJobDescription'].'"><input type="hidden" name="NewJob" value="true">';
}
else {
	echo $_POST['JobDescription'].'"><input type="hidden" name="NewJob" value="false">';
}
echo	'<td class="general">Date: <td class="general">'.date("j/n/Y", strtotime($_POST['EmployeeHoursDate'])).'<input type="hidden" name="JobDate" value="'.$_POST['EmployeeHoursDate'].'">';
	
echo 	'<td class="general">Status: <td class="general">';
if (isset($_POST['NewJob'])) {
	echo'New Job OPENED';
}
else {
	echo 'OPEN';
}
echo '<td class="general">Job Completed: <td class="general"><input type="checkbox" value="true" name="JobCompleted" >';
echo	' </table>
	</div>';
	
/* Code for main table */
echo '
	<div id="scroller1">
	<table border="1">';
$n=1;
while($n<16){
$htmlstring='';
echo '<tr><tr><td class="general">Vehicle '.$n.'<td class="general" colspan="2">';
echo '<select name="Vehicle'.$n.'" id="Vehicle'.$n.'">';
$query='select vehicles.Name, Make, Model, vehicles.Ind from parkerbros.vehicles left outer join parkerbros.vehiclehours
on vehiclehours.vehicle=vehicles.ind where StatusSelect="ACTIVE" group by vehicles.ind order by max(OperationDate) desc ;';
$res=dbquery($query);
echo '<option value="false">Please Select Vehicle</option>';
while ($row = dbfetchassoc($res)) {
		$htmlstring=$htmlstring.'<option value="'.$row['Ind'].'">'.$row['Name'].'.  '.$row['Make'].' '.$row['Model'].'</option>';
	}
echo $htmlstring.'</select>';
echo '<tr><td class="general">Vehicle '.$n.' Hours<td class="general">';
echo '<select name="VehicleHours'.$n.'">';
$x=0;
    while($x<24){
        echo '<option>';
        echo $x.'</option>';
    $x++;
    }echo '</select>';

echo '<td class="general"><input type="checkbox" id= "DCAllOK'.$n.'" value="true" name="DailyCheckAllOK'.$n.'" checked="checked" onclick="DailyChecklistNotOK('.$n.')">Daily Checklist All Systems Checked and OK</td>';

echo '<tr><td class="general">Vehicle '.$n.' Fuel (Litres)<td class="general" colspan="2">';
echo '<select name="VehicleFuel'.$n.'">';
$x=0;
    while($x<500){
        echo '<option>';
        echo $x.'</option>';
    $x=$x+25;
    }echo '</select>';


$n++;
}



echo '<tr><td colspan="2"><input type="submit" Value="Submit">';
echo	'</table>
	</div>
	<div id="scroller2">
	<table border="1">';
$n=1;
while($n<16){
echo '<tr><tr><td class="general">Material '.$n.'<td class="general"><input type="text" name="Material'.$n.'">';
echo '<tr><td class="general">Quantity '.$n.'<td class="general"><input type="text" name="Quantity'.$n.'">';
echo '<tr><td class="general">Price per Quantity'.$n.'<td class="general"><input type="text" name="Price'.$n.'">';
$n++;
}
echo '<tr><td colspan="2"><input type="submit" Value="Submit">';
echo	'</table>
	</div></form>';
?>

</p>

<div id="background">&nbsp</div>
</body>
</html>