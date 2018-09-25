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

echo '<form action="NewInvoiceByOpenJob.php" method="post"><table class="threequarterwidth" border="2">';
echo '<tr><td class="Subheading3">Select Jobs<td class="Subheading3">Job Description<td class="Subheading3">Client<td class="Subheading3">Date Completed</td>';
$JobQuery='select ClientInd, EndDate, Status, JobDescription, Ind from jobs where Status="OPEN" order by ClientInd, EndDate;';
$Jobsres=dbquery($JobQuery);
$x=0;
while ($row = dbfetchassoc($Jobsres)){
	$JobInd=$row['Ind'];
	$ClientQuery='select Ind, ContactFirstName, ContactLastName, Company, ContactTitleOrPosition from contacts where Ind="'.$row['ClientInd'].'";';
	$Clientres=dbquery($ClientQuery);
	while($ClientRow = dbfetchassoc($Clientres)){
	
	echo 	'<tr><td class="general"><input type="Checkbox" value="'.$JobInd.'" name="CheckBox'.$x.
		'"><td class="general">'.$row['JobDescription'].
		'<td class="general">'.$ClientRow['Company'].'.  '.$ClientRow['ContactTitleOrPosition'].', '.$ClientRow['ContactFirstName'].' '.$ClientRow['ContactLastName'].'.'.
		'<td class="general">'.date("d/m/Y", strtotime($row['EndDate'])).'</td>';

	}
$x+=1;
}
echo '<input type="hidden" value="'.$x.'" name="RecNumber"><input type="hidden" name="EmployeeInd" value="'.$_SESSION['EmployeeInd'].'"><tr><td colspan="4" align="center"><input type="submit" value="Create Invoice"></table></form>';

?>
</div>
</body>
</html>