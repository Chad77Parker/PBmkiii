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

echo '<form action="ViewPreviousInvoice.php" method="post"><table class="threequarterwidth" border="2">';
echo '<tr><td colspan="6"><input type="submit" value="View Invoice"><tr><td class="Subheading3">Select Invoice<td class="Subheading3">Invoice<td class="Subheading3">Invoice Date<td class="Subheading3">Client<td class="Subheading3">Job Description<td class="Subheading3">Date Completed</td>';
$InvoiceQuery='select * from invoices order by InvoiceNumber desc;';
$Invoiceres=dbquery($InvoiceQuery);
$PrevInvoice=0;
$PrevClient=0;
$Firstjob=true;
$PrevJob=0;
$firstInvoiceFlag=true;
while ($row = dbfetchassoc($Invoiceres)){
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
	$JobRes=dbquery($JobQuery);
	while ($JobRow = dbfetchassoc($JobRes)){
		if($PrevClient!=$JobRow['ClientInd'] or $FirstClient){
			if(!$FirstClient){
				echo '<tr><td class="general" colspan="3">';
			}
			
			$ClientQuery='select ContactFirstName, ContactLastName, ContactTitleOrPosition, Company from contacts where Ind="'.$JobRow['ClientInd'].'";';
			$ClientRes=dbquery($ClientQuery);
			while ($ClientRow = dbfetchassoc($ClientRes)){
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
</body>
</html>