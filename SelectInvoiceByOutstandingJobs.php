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

echo '<form action="NewInvoiceByOutstanding.php" method="post"><table class="threequarterwidth" border="2">';
echo '<tr><td class="Subheading3">Select Jobs<td class="Subheading3">Job Description<td class="Subheading3">Client<td class="Subheading3">Date Completed</td><td class="Subheading3">Employee</td>';
$JobQuery='select ClientInd, EndDate, Status, JobDescription, Ind, Employee from jobs where Status="COMPLETED" order by ClientInd, EndDate;';
$Jobsres=mysql_query($JobQuery);
if(!$Jobsres){
	die(mysql_error());
}
$x=0;

while ($row = mysql_fetch_assoc($Jobsres)){
	$JobInd=$row['Ind'];
	$ClientQuery='select Ind, ContactFirstName, ContactLastName, Company, ContactTitleOrPosition from contacts where Ind="'.$row['ClientInd'].'";';
	$Clientres=mysql_query($ClientQuery);
	if(!$Clientres){
		die(mysql_error());
	}
	$EmployeeQuery='select * from employees where Ind="'.$row['Employee'].'";';
	$Employeeres=mysql_query($EmployeeQuery);
  if(!$Employeeres){
		die(mysql_error());
	}
	while($ClientRow = mysql_fetch_assoc($Clientres)){
	while($EmployeeRow= mysql_fetch_assoc($Employeeres)){
 	echo 	'<tr><td class="general"><input type="Checkbox" value="'.$JobInd.'" name="CheckBox'.$x.
		'"><td class="general">'.$row['JobDescription'].
		'<td class="general">'.$ClientRow['Company'].'.  '.$ClientRow['ContactTitleOrPosition'].', '.$ClientRow['ContactFirstName'].' '.$ClientRow['ContactLastName'].'.'.
		'<td class="general">'.date("d/m/Y", strtotime($row['EndDate'])).'</td>'.
    '<td class="general">'.$EmployeeRow['FirstName'].' '.$EmployeeRow['LastName'].'</td>';

	}
	}
$x+=1;
}
echo '<input type="hidden" value="'.$x.'" name="RecNumber"><input type="hidden" name="EmployeeInd" value="'.$_SESSION['EmployeeInd'].'"><tr><td colspan="4" align="center"><input type="submit" value="Create Invoice"></table></form>';

?>











</div>






<div id="background">&nbsp</div>

</body>
</html>