<?php
require_once 'GlobalFunctions.php';
require_once 'data/dbintegration.php';

echo '<html>
      <head>
      <title>Parker Bros Earthmoving Pty Ltd</title>';
echo '<link href="data/standardprint.css" type="text/css" rel="stylesheet" />';
echo '</head>
     <body>';

?>


<form name="ViewInvoice" method="post" action="PrintInvoice.php">
<?php
/* variables for page height. */
$page=186;
$topbanner=26;
$normal=12;
$Subheading1=15;
$Subheading2=7;
$Subheading3=7;
$general=6;
$PTotals=8;
$PMasterTotals=8;
$htmlstring='';
$query='select FirstName, LastName from employees where Ind = '.$_POST['EmployeeInd'].';';
$res=dbquery($query);
while ($row = dbfetchassoc($res)){
	$Name=$row['FirstName'].' '.$row['LastName'];
}
if (isset($_POST['InvoiceNumber'])){
   $InvoiceNumber=$_POST['InvoiceNumber'];
}else{$InvoiceNumber ='For Information only';}
$InvoiceDate=date("Ymd",time());
$htmlHeader='<img id="topbanner" src="images\pbprintbanner1.jpg"  border="0"><br>Invoice: '.$InvoiceNumber.'&nbsp &nbsp Date: '.date("d/m/Y", time()).'&nbsp &nbsp Employee: '.$Name.'<br>';
$htmlStartTable='<table class="threequarterwidth" border="2">';
$htmlEndTable='</table>';
$PageBreak='<p class="pagebreakhere"></p><br>';
echo $htmlHeader.$htmlStartTable;
$queryjobs=$_POST['JobQuery'];
$JobsRes=dbquery($queryjobs);
$PrevClient=0;
$content=38;
$pcontent=0;
while ($rowJ = dbfetchassoc($JobsRes)){

$newClient='false';
if ($PrevClient!=$rowJ['ClientInd']){
	$query = 'select * from contacts where Ind = '.$rowJ['ClientInd'].';';
	$ClientRes=dbquery($query);
	while ($rowC = dbfetchassoc($ClientRes)){
	$content+=$Subheading1+$general;
	$htmlClient='<tr><td class="SubHeading1" colspan="5"><br>'.$rowC['ContactTitleOrPosition'].' '.$rowC['ContactFirstName'].' '.$rowC['ContactLastName'].', '.$rowC['Company'].'</td><tr><td class="general" colspan="5">'.$rowC['PostalAddressNumber'].' '.$rowC['PostalAddressStreet'].', '.$rowC['PostalAddressSuburb'].', '.$rowC['PostalAddressState'].', '.$rowC['PostalAddressPostCode'].'</td>';
	$htmlstring=$htmlstring.$htmlClient;
	$newClient='true';
	}
$PrevClient=$rowJ['ClientInd'];
}
$content+=$Subheading2+$general;
$htmlstring=$htmlstring.'<tr><td class="SubHeading2" colspan="5">'.$rowJ['JobDescription'].'</td>
	<tr><td class="general" colspan="5">Comenced: '.date('j/n/Y',strtotime($rowJ['StartDate'])).'&nbsp &nbsp &nbsp &nbsp Completed: '.date('j/n/Y',strtotime($rowJ['EndDate']));
$query='select JobInd, Vehicle, sum(HoursForVehicle) from vehiclehours where JobInd='.$rowJ['Ind'].' group by Vehicle;';
$VehicleHoursRes=dbquery($query);
$PlantTotal=0;
if (dbnumrows($VehicleHoursRes)!=0){
	$content+=$Subheading3;
	$htmlstring=$htmlstring.'<tr><td class="SubHeading3">Plant Hire<td class="SubHeading3">$/hr<td class="SubHeading3">hrs<td class="SubHeading3">Sub<td class="SubHeading3">Total';
	
	while ($rowVH=dbfetchassoc($VehicleHoursRes)){
	 $query='select Name, Make, Model, PricePerHour from vehicles where Ind='.$rowVH['Vehicle'].';';
	 $VehicleRes=dbquery($query);
	 $rowV=dbfetchassoc($VehicleRes);
	 $content+=$general;
	 $htmlstring=$htmlstring.'<tr><td class="general">'.$rowV['Name'].', '.$rowV['Make'].' '.$rowV['Model'].'<td class="general">$'.$rowV['PricePerHour'].'<td class="general">'.$rowVH['sum(HoursForVehicle)'].'<td class="general">$'.$rowV['PricePerHour']*$rowVH['sum(HoursForVehicle)'].'<td class="general">';
  $PlantTotal=$PlantTotal+($rowV['PricePerHour']*$rowVH['sum(HoursForVehicle)']);
	}
	$content+=$PTotals;
	$htmlstring=$htmlstring.'<tr><td class="Totals" colspan="4">Plant Hire Total<td class="Totals">$'.$PlantTotal;
}
$query='select Material, max(MaterialPrice), sum(MaterialQuantity), JobInd from materialsused where JobInd='.$rowJ['Ind'].'	group by Material;';
$MaterialsRes=dbquery($query);
$MaterialTotal=0;
if (dbnumrows($MaterialsRes)!=0){
	$content+=$Subheading3;
	$htmlstring=$htmlstring.'<tr><td class="SubHeading3">Materials<td class="SubHeading3">$/Unit<td class="SubHeading3">Qty<td class="SubHeading3">Sub<td class="SubHeading3">Total';
	while ($rowM=dbfetchassoc($MaterialsRes)){
		$content+=$general;
		$htmlstring=$htmlstring.'<tr><td class="general">'.$rowM['Material'].'<td class="general">$'.$rowM['max(MaterialPrice)'].'<td class="general">'.$rowM['sum(MaterialQuantity)'].'<td class="general">$'.$rowM['max(MaterialPrice)']*$rowM['sum(MaterialQuantity)'].'<td class="general">';
		$MaterialTotal+=($rowM['max(MaterialPrice)']*$rowM['sum(MaterialQuantity)']);
 
	}
	$content+=$PTotals;
	$htmlstring=$htmlstring.'<tr><td class="Totals" colspan="4">Material Total<td class="Totals">$'.$MaterialTotal;

}
$PlantTotal+=$MaterialTotal;
$content+=$PMasterTotals;
$htmlstring=$htmlstring.'<tr><td class="MasterTotals" colspan="4">TOTAL<td class="MasterTotals">$'.$PlantTotal.'</td>';



if ($pcontent+$content>$page and $pcontent!=0 and $newClient=='false'){
	echo $htmlEndTable.$PageBreak.$htmlHeader.$htmlStartTable.$htmlClient.$htmlstring;
	$htmlstring='';
	$pcontent=$content+$Subheading2+$general+$topbanner+$normal;
	$content=0;
}
elseif ($pcontent+$content>$page and $pcontent!=0){
	echo $htmlEndTable.$PageBreak.$htmlHeader.$htmlStartTable.$htmlstring;
	$htmlstring='';
	$pcontent=$content+$topbanner+$normal;
	$content=0;
}
else {
	echo $htmlstring;
	$htmlstring='';
	$pcontent+=$content;
	$content=0;
}
while ($pcontent>$page){
	$pcontent-=$page;
}
}


?>



</table></form><br><a href="ParkerBros.php">Home</a>







</body>
</html>