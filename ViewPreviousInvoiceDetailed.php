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


$query='select * from Invoices where InvoiceNumber="'.$_POST['Invoice'].'";';
$res=mysql_query($query);
if(!$res){
	die(mysql_error());
}
$firstjobflag=true;
$queryjobs='select * from jobs where Ind in (';
while ($row = mysql_fetch_assoc($res)){
	if ($firstjobflag==false and isset($_POST['Invoice'])){
		$queryjobs= $queryjobs.', ';
	}
	if (isset($_POST['Invoice'])){
		$queryjobs= $queryjobs.$row['JobInd'];
		$firstjobflag=false;
	}
	if ($firstjobflag==true){
		die('<h3> No Jobs Selected !</h3></div><div id="background">&nbsp</div></body></html>');
	}
}
$queryjobs= $queryjobs.') order by ClientInd, EndDate;';
$JobsRes=mysql_query($queryjobs);
if(!$JobsRes){
	die(mysql_error());
}

$InvoiceNumber=$_POST['Invoice'];
echo '<h3>Invoice: '.$InvoiceNumber.'</h3>';
$PrevClient=0;
echo 	'<br><form method="post" action="PrintInvoiceDetailed.php"><table class="threequarterwidth" border="2">';
while ($rowJ = mysql_fetch_assoc($JobsRes)){
if ($PrevClient!=$rowJ['ClientInd']){
	$query = 'select * from contacts where Ind = '.$rowJ['ClientInd'].';';
	$ClientRes=mysql_query($query);
	if(!$ClientRes){
	die(mysql_error());
	}
	while ($rowC = mysql_fetch_assoc($ClientRes)){
	 echo 	'<tr><td class="SubHeading1" colspan="5"><br>'.$rowC['ContactTitleOrPosition'].' '.$rowC['ContactFirstName'].' '.$rowC['ContactLastName'].', '.$rowC['Company'].'</td><tr><td class="general" colspan="5">'.$rowC['PostalAddressNumber'].' '.$rowC['PostalAddressStreet'].', '.$rowC['PostalAddressSuburb'].', '.$rowC['PostalAddressState'].', '.$rowC['PostalAddressPostCode'].'</td>';
	}
$PrevClient=$rowJ['ClientInd'];
}
echo 	'<tr><td class="SubHeading2" colspan="5">'.$rowJ['JobDescription'].'</td>
	<tr><td class="general" colspan="5">Comenced: '.date('j/n/Y',strtotime($rowJ['StartDate'])).'&nbsp &nbsp &nbsp &nbsp Completed: '.date('j/n/Y',strtotime($rowJ['EndDate']));
$query='select JobInd, Vehicle, sum(HoursForVehicle) from vehiclehours where JobInd='.$rowJ['Ind'].' group by Vehicle;';
$VehicleHoursRes=mysql_query($query);
if(!$VehicleHoursRes){
	die(mysql_error());
}
$PlantTotal=0;
if (mysql_num_rows($VehicleHoursRes)!=0){
	echo 	'<tr><td class="SubHeading3">Plant Hire<td class="SubHeading3">$/hr<td class="SubHeading3">hrs<td class="SubHeading3">Sub<td class="SubHeading3">Total';
	while ($rowVH=mysql_fetch_assoc($VehicleHoursRes)){
	 $query='select Name, Make, Model, PricePerHour from vehicles where Ind='.$rowVH['Vehicle'].';';
	 $VehicleRes=mysql_query($query);
	 if(!$VehicleRes){
	 die(mysql_error());
	 }
	 $rowV=mysql_fetch_assoc($VehicleRes);
	 echo 	'<tr><td class="general">'.$rowV['Name'].', '.$rowV['Make'].' '.$rowV['Model'].'<td class="general">$'.$rowV['PricePerHour'].'<td class="general">'.$rowVH['sum(HoursForVehicle)'].'<td class="general">$'.$rowV['PricePerHour']*$rowVH['sum(HoursForVehicle)'].'<td class="general">';
  $PlantTotal=$PlantTotal+($rowV['PricePerHour']*$rowVH['sum(HoursForVehicle)']);
	}
	echo 	'<tr><td class="Totals" colspan="4">Plant Hire Total<td class="Totals">$'.$PlantTotal;
}
$query='select Material, max(MaterialPrice), sum(MaterialQuantity), JobInd from materialsused where JobInd='.$rowJ['Ind'].'	group by Material;';
$MaterialsRes=mysql_query($query);
if(!$MaterialsRes){
	die(mysql_error());
}
$MaterialTotal=0;
if (mysql_num_rows($MaterialsRes)!=0){
	echo 	'<tr><td class="SubHeading3">Materials<td class="SubHeading3">$/Unit<td class="SubHeading3">Qty<td class="SubHeading3">Sub<td class="SubHeading3">Total';
	while ($rowM=mysql_fetch_assoc($MaterialsRes)){
		echo 	'<tr><td class="general">'.$rowM['Material'].'<td class="general">$'.$rowM['max(MaterialPrice)'].'<td class="general">'.$rowM['sum(MaterialQuantity)'].'<td class="general">$'.$rowM['max(MaterialPrice)']*$rowM['sum(MaterialQuantity)'].'<td class="general">';
		$MaterialTotal+=($rowM['max(MaterialPrice)']*$rowM['sum(MaterialQuantity)']);
	
	}
	echo	'<tr><td class="Totals" colspan="4">Material Total<td class="Totals">$'.$MaterialTotal;

}
$PlantTotal+=$MaterialTotal;
echo '<tr><td class="MasterTotals" colspan="4">TOTAL<td class="MasterTotals">$'.$PlantTotal;

// start of details
echo '<tr><td class="SubHeading2" colspan="5">Details</td>';
$query='select JobInd, Vehicle, HoursForVehicle, OperationDate from vehiclehours where JobInd='.$rowJ['Ind'].' order by OperationDate;';
$VehicleHoursRes=mysql_query($query);
if(!$VehicleHoursRes){
	die(mysql_error());
}
if (mysql_num_rows($VehicleHoursRes)!=0){
	echo 	'<tr><td class="SubHeading3">Plant Hire<td class="SubHeading3">Date<td class="SubHeading3" colspan="3">hrs';
	while ($rowVH=mysql_fetch_assoc($VehicleHoursRes)){
	 $query='select Name, Make, Model, PricePerHour from vehicles where Ind='.$rowVH['Vehicle'].';';
	 $VehicleRes=mysql_query($query);
	 if(!$VehicleRes){
	 die(mysql_error());
	 }
	 $rowV=mysql_fetch_assoc($VehicleRes);
	 echo 	'<tr><td class="general">'.$rowV['Name'].', '.$rowV['Make'].' '.$rowV['Model'].'<td class="general">'.date('j/n/Y',strtotime($rowVH['OperationDate'])).'<td class="general" colspan="3">'.$rowVH['HoursForVehicle'];

	}
}
$query='select Material, SupplyDate, MaterialQuantity, JobInd from materialsused where JobInd='.$rowJ['Ind'].'	order by SupplyDate;';
$MaterialsRes=mysql_query($query);
if(!$MaterialsRes){
	die(mysql_error());
}
if (mysql_num_rows($MaterialsRes)!=0){
	echo 	'<tr><td class="SubHeading3">Materials<td class="SubHeading3">Date<td class="SubHeading3" colspan="3">Qty';
	while ($rowM=mysql_fetch_assoc($MaterialsRes)){
		echo 	'<tr><td class="general">'.$rowM['Material'].'<td class="general">'.date('j/n/Y',strtotime($rowM['SupplyDate'])).'<td class="general" colspan="3">'.$rowM['MaterialQuantity'];

	}

}

//end of details

}
echo '<tr><td colspan="5" class="general"><input type="hidden" name="JobQuery" value="'.$queryjobs.'"><input type="hidden" value="'.$_POST['EmployeeInd'].'" name="EmployeeInd"><input type="hidden" name="InvoiceNumber" value="'.$_POST['Invoice'].'"><center><input type="submit" value="Print">';
echo '</table></form>';
?>

</div>






<div id="background">&nbsp</div>

</body>
</html>