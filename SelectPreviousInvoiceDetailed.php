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

echo '<form action="ViewPreviousInvoiceDetailed.php" method="post"><table class="threequarterwidth" border="2">';
echo '<tr><td colspan="6"><input type="submit" value="View Invoice"><tr><td class="Subheading3">Select Invoice<td class="Subheading3">Invoice<td class="Subheading3">Invoice Date<td class="Subheading3">Client<td class="Subheading3">Job Description<td class="Subheading3">Date Completed</td>';
$InvoiceQuery='select * from invoices order by InvoiceNumber desc;';
$Invoiceres=mysql_query($InvoiceQuery);
if(!$Invoiceres){
	die(mysql_error());
}
$PrevInvoice=0;
$PrevClient=0;
$Firstjob=true;
$PrevJob=0;
$firstInvoiceFlag=true;
while ($row = mysql_fetch_assoc($Invoiceres)){
	if ($row['InvoiceNumber']!=$PrevInvoice){
		if (!$firstInvoiceFlag){
			echo '<tr>';
		}
		$firstInvoiceFlag=false;
		echo '<tr><tr><td class="general"><input type="radio" name="Invoice" value="'.$row['InvoiceNumber'].'"><td class="general">'.$row['InvoiceNumber'].'<td class="general">'.date("d/m/Y", strtotime($row['InvoiceDate']));
		$FirstClient=true;
		$PrevInvoice=$row['InvoiceNumber'];
	}
	$JobQuery='select * from jobs where Ind="'.$row['JobInd'].'";';
	$JobRes=mysql_query($JobQuery);
	if(!$JobRes){
		die(mysql_error());
	}
	while ($JobRow = mysql_fetch_assoc($JobRes)){
		if($PrevClient!=$JobRow['ClientInd'] or $FirstClient){
			if(!$FirstClient){
				echo '<tr><td class="general" colspan="3">';
			}
			
			$ClientQuery='select ContactFirstName, ContactLastName, ContactTitleOrPosition, Company from contacts where Ind="'.$JobRow['ClientInd'].'";';
			$ClientRes=mysql_query($ClientQuery);
			if (!$ClientRes){
				die(mysql_error());
			}
			while ($ClientRow = mysql_fetch_assoc($ClientRes)){
				echo '<td class="general">'.$ClientRow['Company'].'. '.$ClientRow['ContactTitleOrPosition'].', '.$ClientRow['ContactFirstName'].' '.$ClientRow['ContactLastName'].'.';
				$FirstClient=false;
				$FirstJob=true;
				$PrevClient=$JobRow['ClientInd'];
			
			}
		}
		if(!$FirstJob){
			echo '<tr><td class="general" colspan="4">';
			
		}
		echo '<td class="general">'.$JobRow['JobDescription'].'<td class="general">'.$JobRow['EndDate'];
		$FirstJob=false;
	}
			
$FirstClient=false;	
}

?>
<tr><tr><td colspan="6"><input type="submit" value="View Invoice"></table></form>










</div>






<div id="background">&nbsp</div>

</body>
</html>