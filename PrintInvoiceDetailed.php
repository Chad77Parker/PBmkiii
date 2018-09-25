<?php
require_once 'GlobalFunctions.php';
require_once 'data/dbintegration.php';

echo '<html>
      <head>
      <title>Parker Bros Earthmoving Pty Ltd</title>';
echo '<link href="data/standardprint.css" type="text/css" rel="stylesheet" />';
echo '</head>
     <body>';

if (checktimeout()){die('<div id= "scroller">You are not authorised to view this page or your session has expired please log in. <a href="ParkerBros.php">Return Home</a></div>');}

echo '<div id="scroller">';


/* variables for page height. */
$page=180;
$topbanner=26;
$normal=12;
$Subheading1=15;
$Subheading2=7;
$Subheading3=7;
$general=6;
$PTotals=8;
$PMasterTotals=8;
$htmlstring = '';
$query='select FirstName, LastName from employees where Ind = 1;';
$res=dbquery($query);
while ($row = dbfetchassoc($res)){
	$Name=$row['FirstName'].' '.$row['LastName'];
}
$InvoiceNumber=$_POST['InvoiceNumber'];
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
	$htmlClient='<tr><td class="SubHeading1" colspan="3"><br>'.$rowC['ContactTitleOrPosition'].' '.$rowC['ContactFirstName'].' '.$rowC['ContactLastName'].', '.$rowC['Company'].'</td><tr><td class="general" colspan="3">'.$rowC['PostalAddressNumber'].' '.$rowC['PostalAddressStreet'].', '.$rowC['PostalAddressSuburb'].', '.$rowC['PostalAddressState'].', '.$rowC['PostalAddressPostCode'].'</td>';
	$htmlstring=$htmlstring.$htmlClient;
	$newClient='true';
	}
$PrevClient=$rowJ['ClientInd'];
}
$content+=$Subheading2+$general;
$htmlstring=$htmlstring.'<tr><td class="SubHeading2" colspan="3">'.$rowJ['JobDescription'].'</td>
	<tr><td class="general" colspan="3">Comenced: '.date('j/n/Y',strtotime($rowJ['StartDate'])).'&nbsp &nbsp &nbsp &nbsp Completed: '.date('j/n/Y',strtotime($rowJ['EndDate']));
$query='select JobInd, Vehicle, sum(HoursForVehicle) from vehiclehours where JobInd='.$rowJ['Ind'].' group by Vehicle;';
$VehicleHoursRes=dbquery($query);
$PlantTotal=0;
if (dbnumrows($VehicleHoursRes)!=0){
	$content+=$Subheading3;
	$htmlstring=$htmlstring.'<tr><td class="SubHeading3" colspan="2">Plant Hire<td class="SubHeading3">hrs';
	
	while ($rowVH=dbfetchassoc($VehicleHoursRes)){
	 $query='select Name, Make, Model from vehicles where Ind='.$rowVH['Vehicle'].';';
	 $VehicleRes=dbquery($query);
	 $rowV=dbfetchassoc($VehicleRes);
	 $content+=$general;
	 $htmlstring=$htmlstring.'<tr><td class="general" colspan="2">'.$rowV['Name'].', '.$rowV['Make'].' '.$rowV['Model'].'<td class="general">'.$rowVH['sum(HoursForVehicle)'];
  	}
	
}
$query='select Material, max(MaterialPrice), sum(MaterialQuantity), JobInd from materialsused where JobInd='.$rowJ['Ind'].'	group by Material;';
$MaterialsRes=dbquery($query);
$MaterialTotal=0;
if (dbnumrows($MaterialsRes)!=0){
	$content+=$Subheading3;
	$htmlstring=$htmlstring.'<tr><td class="SubHeading3" colspan="2">Materials<td class="SubHeading3">Qty';
	while ($rowM=dbfetchassoc($MaterialsRes)){
		$content+=$general;
		$htmlstring=$htmlstring.'<tr><td class="general" colspan="2">'.$rowM['Material'].'<td class="general">'.$rowM['sum(MaterialQuantity)'];
		 
	}
	

}
// start of details
$htmlstring=$htmlstring. '<tr><td class="SubHeading2" colspan="3">Details</td>';
$content+=$Subheading2;
$query='select JobInd, Vehicle, HoursForVehicle, OperationDate from vehiclehours where JobInd='.$rowJ['Ind'].' order by OperationDate;';
$VehicleHoursRes=dbquery($query);
if (dbnumrows($VehicleHoursRes)!=0){
	$htmlstring=$htmlstring. 	'<tr><td class="SubHeading3">Plant Hire<td class="SubHeading3">Date<td class="SubHeading3">hrs';
  $content+=$Subheading3;
  while ($rowVH=dbfetchassoc($VehicleHoursRes)){
	 $query='select Name, Make, Model, PricePerHour from vehicles where Ind='.$rowVH['Vehicle'].';';
	 $VehicleRes=dbquery($query);
	 $rowV=dbfetchassoc($VehicleRes);
	 $htmlstring=$htmlstring. 	'<tr><td class="general">'.$rowV['Name'].', '.$rowV['Make'].' '.$rowV['Model'].'<td class="general">'.date('j/n/Y',strtotime($rowVH['OperationDate'])).'<td class="general">'.$rowVH['HoursForVehicle'];
   $content+=$general;
	}
}
$query='select Material, SupplyDate, MaterialQuantity, JobInd from materialsused where JobInd='.$rowJ['Ind'].'	order by SupplyDate;';
$MaterialsRes=dbquery($query);
if (dbnumrows($MaterialsRes)!=0){
	$htmlstring=$htmlstring. 	'<tr><td class="SubHeading3">Materials<td class="SubHeading3">Date<td class="SubHeading3">Qty';
  $content+=$general;
  while ($rowM=dbfetchassoc($MaterialsRes)){
		$htmlstring=$htmlstring. 	'<tr><td class="general">'.$rowM['Material'].'<td class="general">'.date('j/n/Y',strtotime($rowM['SupplyDate'])).'<td class="general">'.$rowM['MaterialQuantity'];
    $content+=$general;
	}

}

//end of details
$htmlstring=$htmlstring. '<tr><td class="general" colspan="3">.';
$content+=$general;

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



<script type="text/javascript" language="javascript"> 
window.print();
</script>



</body>
</html>