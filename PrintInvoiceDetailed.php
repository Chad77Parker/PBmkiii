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
      <title>Print Invoice</title>';
if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),'iphone')){
	echo '<link href="data/iPhonestandard.css" type="text/css" rel="stylesheet" />
	 <meta name="viewport" content="width = 480" />';
}
else{
	echo '<link href="data/standardprint.css" type="text/css" rel="stylesheet" />';
}
?>
  
</head>
<body>

<form name="ViewInvoice" method="post" action="PrintInvoice.php">
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
echo $query;
$res=mysql_query($query);
if(!$res){
  echo "select name QUERY";
	die(mysql_error());
}
while ($row = mysql_fetch_assoc($res)){
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
$JobsRes=mysql_query($queryjobs);
if(!$JobsRes){
	die(mysql_error());
}
$PrevClient=0;
$content=38;
$pcontent=0;
while ($rowJ = mysql_fetch_assoc($JobsRes)){

$newClient='false';
if ($PrevClient!=$rowJ['ClientInd']){
	$query = 'select * from contacts where Ind = '.$rowJ['ClientInd'].';';
	$ClientRes=mysql_query($query);
	if(!$ClientRes){
	die(mysql_error());
	}
	while ($rowC = mysql_fetch_assoc($ClientRes)){
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
$VehicleHoursRes=mysql_query($query);
if(!$VehicleHoursRes){
	die(mysql_error());
}
$PlantTotal=0;
if (mysql_num_rows($VehicleHoursRes)!=0){
	$content+=$Subheading3;
	$htmlstring=$htmlstring.'<tr><td class="SubHeading3" colspan="2">Plant Hire<td class="SubHeading3">hrs';
	
	while ($rowVH=mysql_fetch_assoc($VehicleHoursRes)){
	 $query='select Name, Make, Model from vehicles where Ind='.$rowVH['Vehicle'].';';
	 $VehicleRes=mysql_query($query);
	 if(!$VehicleRes){
	 die(mysql_error());
	 }
	 $rowV=mysql_fetch_assoc($VehicleRes);
	 $content+=$general;
	 $htmlstring=$htmlstring.'<tr><td class="general" colspan="2">'.$rowV['Name'].', '.$rowV['Make'].' '.$rowV['Model'].'<td class="general">'.$rowVH['sum(HoursForVehicle)'];
  	}
	
}
$query='select Material, max(MaterialPrice), sum(MaterialQuantity), JobInd from materialsused where JobInd='.$rowJ['Ind'].'	group by Material;';
$MaterialsRes=mysql_query($query);
if(!$MaterialsRes){
	die(mysql_error());
}
$MaterialTotal=0;
if (mysql_num_rows($MaterialsRes)!=0){
	$content+=$Subheading3;
	$htmlstring=$htmlstring.'<tr><td class="SubHeading3" colspan="2">Materials<td class="SubHeading3">Qty';
	while ($rowM=mysql_fetch_assoc($MaterialsRes)){
		$content+=$general;
		$htmlstring=$htmlstring.'<tr><td class="general" colspan="2">'.$rowM['Material'].'<td class="general">'.$rowM['sum(MaterialQuantity)'];
		 
	}
	

}
// start of details
$htmlstring=$htmlstring. '<tr><td class="SubHeading2" colspan="3">Details</td>';
$content+=$Subheading2;
$query='select JobInd, Vehicle, HoursForVehicle, OperationDate from vehiclehours where JobInd='.$rowJ['Ind'].' order by OperationDate;';
$VehicleHoursRes=mysql_query($query);
if(!$VehicleHoursRes){
	die(mysql_error());
}
if (mysql_num_rows($VehicleHoursRes)!=0){
	$htmlstring=$htmlstring. 	'<tr><td class="SubHeading3">Plant Hire<td class="SubHeading3">Date<td class="SubHeading3">hrs';
  $content+=$Subheading3;
  while ($rowVH=mysql_fetch_assoc($VehicleHoursRes)){
	 $query='select Name, Make, Model, PricePerHour from vehicles where Ind='.$rowVH['Vehicle'].';';
	 $VehicleRes=mysql_query($query);
	 if(!$VehicleRes){
	 die(mysql_error());
	 }
	 $rowV=mysql_fetch_assoc($VehicleRes);
	 $htmlstring=$htmlstring. 	'<tr><td class="general">'.$rowV['Name'].', '.$rowV['Make'].' '.$rowV['Model'].'<td class="general">'.date('j/n/Y',strtotime($rowVH['OperationDate'])).'<td class="general">'.$rowVH['HoursForVehicle'];
   $content+=$general;
	}
}
$query='select Material, SupplyDate, MaterialQuantity, JobInd from materialsused where JobInd='.$rowJ['Ind'].'	order by SupplyDate;';
$MaterialsRes=mysql_query($query);
if(!$MaterialsRes){
	die(mysql_error());
}
if (mysql_num_rows($MaterialsRes)!=0){
	$htmlstring=$htmlstring. 	'<tr><td class="SubHeading3">Materials<td class="SubHeading3">Date<td class="SubHeading3">Qty';
  $content+=$general;
  while ($rowM=mysql_fetch_assoc($MaterialsRes)){
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