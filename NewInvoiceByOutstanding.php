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
<?php

$query='select FirstName, LastName from employees where Ind = '.$_POST['EmployeeInd'].';';
$res=dbquery($query);
while ($row = dbfetchassoc($res)){
	$Name=$row['FirstName'].' '.$row['LastName'];
}
$query='select max(InvoiceNumber) from Invoices;';
$res=dbquery($query);
while ($row = dbfetchassoc($res)){
	$InvoiceNumber=$row['max(InvoiceNumber)']+1;
}
echo '<h3>Invoice: '.$InvoiceNumber.'                Employee: '.$Name.'</h3>';
$x=0;
$firstjobflag=true;
$queryjobs='select * from jobs where Ind in (';
while ($_POST['RecNumber']>$x){
	if ($firstjobflag==false and isset($_POST['CheckBox'.$x])){
		$queryjobs= $queryjobs.', ';
	}
	if (isset($_POST['CheckBox'.$x])){
		$queryjobs= $queryjobs.$_POST['CheckBox'.$x];
		$firstjobflag=false;
	}
	$x+=1;
}
if ($firstjobflag==true){
	die('<h3> No Jobs Selected !</h3></div><div id="background">&nbsp</div></body></html>');
}
$queryjobs= $queryjobs.') order by ClientInd, EndDate;';
$JobsRes=dbquery($queryjobs);
$PrevClient=0;
echo 	'<br><form method="post" action="Save&PrintInvoice.php"><table class="threequarterwidth" border="2">';
while ($rowJ = dbfetchassoc($JobsRes)){
if ($PrevClient!=$rowJ['ClientInd']){
	$query = 'select * from contacts where Ind = '.$rowJ['ClientInd'].';';
	$ClientRes=dbquery($query);
 	while ($rowC = dbfetchassoc($ClientRes)){
	 echo 	'<tr><td class="SubHeading1" colspan="5"><br>'.$rowC['ContactTitleOrPosition'].' '.$rowC['ContactFirstName'].' '.$rowC['ContactLastName'].', '.$rowC['Company'].'</td><tr><td class="general" colspan="5">'.$rowC['PostalAddressNumber'].' '.$rowC['PostalAddressStreet'].', '.$rowC['PostalAddressSuburb'].', '.$rowC['PostalAddressState'].', '.$rowC['PostalAddressPostCode'].'</td>';
	}
$PrevClient=$rowJ['ClientInd'];
}
echo 	'<tr><td class="SubHeading2" colspan="5">'.$rowJ['JobDescription'].'</td>
	<tr><td class="general" colspan="5">Comenced: '.date('j/n/Y',strtotime($rowJ['StartDate'])).'&nbsp &nbsp &nbsp &nbsp Completed: '.date('j/n/Y',strtotime($rowJ['EndDate']));
$query='select JobInd, Vehicle, sum(HoursForVehicle) from vehiclehours where JobInd='.$rowJ['Ind'].' group by Vehicle;';
$VehicleHoursRes=dbquery($query);
$PlantTotal=0;
if (dbnumrows($VehicleHoursRes)!=0){
	echo 	'<tr><td class="SubHeading3">Plant Hire<td class="SubHeading3">$/hr<td class="SubHeading3">hrs<td class="SubHeading3">Sub<td class="SubHeading3">Total';
	while ($rowVH=dbfetchassoc($VehicleHoursRes)){
	 $query='select Name, Make, Model, PricePerHour from vehicles where Ind='.$rowVH['Vehicle'].';';
	 $VehicleRes=dbquery($query);
	 $rowV=dbfetchassoc($VehicleRes);
	 echo 	'<tr><td class="general">'.$rowV['Name'].', '.$rowV['Make'].' '.$rowV['Model'].'<td class="general">$'.$rowV['PricePerHour'].'<td class="general">'.$rowVH['sum(HoursForVehicle)'].'<td class="general">$'.$rowV['PricePerHour']*$rowVH['sum(HoursForVehicle)'].'<td class="general">';
  $PlantTotal=$PlantTotal+($rowV['PricePerHour']*$rowVH['sum(HoursForVehicle)']);
	}
	echo 	'<tr><td class="Totals" colspan="4">Plant Hire Total<td class="Totals">$'.$PlantTotal;
}
$query='select Material, max(MaterialPrice), sum(MaterialQuantity), JobInd from materialsused where JobInd='.$rowJ['Ind'].'	group by Material;';
$MaterialsRes=dbquery($query);
$MaterialTotal=0;
if (dbnumrows($MaterialsRes)!=0){
	echo 	'<tr><td class="SubHeading3">Materials<td class="SubHeading3">$/Unit<td class="SubHeading3">Qty<td class="SubHeading3">Sub<td class="SubHeading3">Total';
	while ($rowM=dbfetchassoc($MaterialsRes)){
		echo 	'<tr><td class="general">'.$rowM['Material'].'<td class="general">$'.$rowM['max(MaterialPrice)'].'<td class="general">'.$rowM['sum(MaterialQuantity)'].'<td class="general">$'.$rowM['max(MaterialPrice)']*$rowM['sum(MaterialQuantity)'].'<td class="general">';
		$MaterialTotal+=($rowM['max(MaterialPrice)']*$rowM['sum(MaterialQuantity)']);
	
	}
	echo	'<tr><td class="Totals" colspan="4">Material Total<td class="Totals">$'.$MaterialTotal;

}
$PlantTotal+=$MaterialTotal;
echo '<tr><td class="MasterTotals" colspan="4">TOTAL<td class="MasterTotals">$'.$PlantTotal;
}
echo '<tr><td colspan="5" class="general"><input type="hidden" name="JobQuery" value="'.$queryjobs.'"><input type="hidden" value="'.$_POST['EmployeeInd'].'" name="EmployeeInd"><center><input type="submit" value="Save Invoice and Print">';
echo '</table></form>';
?>

</div>
</body>
</html>