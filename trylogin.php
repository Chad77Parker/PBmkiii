<?php
session_start();
include 'GlobalFunctions.php';
$htmlstring='<html>
	<head>
	<title>Logging in...</title>';
$htmlstring=$htmlstring.MobileDetect();

$htmlstring=$htmlstring.'</head>
<body>
';

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

$res = mysql_query('select * from employees', $link);
if(!$res){
	die(mysql_error());
}

$_SESSION['loggedin']=false;
while ($row = mysql_fetch_assoc($res)) {
	if ($row['FirstName']==$_POST['FirstName'] and $row['LastName']==$_POST['LastName'] and $row['Password']==$_POST['Password']){
		$_SESSION['loggedin']=true;
		$_SESSION['SessionStartTime']=time();
		$LastLogin=$row['LastLogin'];
		$FirstName=$row['FirstName'];
		$LastName=$row['LastName'];
		$EmployeeInd=$row['Ind'];
		$Permission = $row['Permission'];
		$ThisLogin=date("YmdHis",time());
		$query='update employees set LastLogin='.$ThisLogin.' where Ind='.$row['Ind'];
		$res=mysql_query($query, $link);
		if(!$res){
			die(mysql_error());
		}
	}
}

$htmlstring=$htmlstring.'
<img id="topbanner" src="images\pbbanner1.jpg" height="100%" border="0">
<div id="topbanner">
Parker Bros Earthmoving Pty Ltd.
</div>
';

if ($_SESSION['loggedin']==false){
	$htmlstring=$htmlstring.'<div id="scroller">
		<h3>Failed Login! Please make sure you are entering your details correctly and your authorised to enter this area</h3>
	 	<p class="notice"><a href="login.php">Retry</a></p>
		</div>';
}
else{
$htmlstring=$htmlstring.'<div id="scroller">
	<h3>Your Last Log In was at '.Date("g:i A",strtotime($LastLogin)).' on '.Date("l jS F Y",strtotime($LastLogin)).'<br><br>Welcome '.$FirstName.' '.$LastName.'</h3>';


$_SESSION['FirstName']=$FirstName;
$_SESSION['LastName']=$LastName;
$_SESSION['EmployeeInd']=$EmployeeInd;
$_SESSION['Permission']=$Permission;
setcookie('FirstName', $FirstName, time()+832000);
setcookie('LastName', $LastName, time()+832000);

if ($_SESSION['Permission'] == 'ADMIN'){
 $Dailycheck= DailyCheckListCheck($link);
 if ($Dailycheck > 0){
   $htmlstring=$htmlstring.'<h3>There are '.$Dailycheck.' faults reported in Daily Checklists</h3>';
   $htmlstring=$htmlstring.'<form action="ViewDailyChecklist.php" method="post"><input name="query" type="hidden" value="CurrentFaults"><input type="submit" value="View Daily Checklists"></form>';
 }
}
$htmlstring=$htmlstring.'</div>';
echo $htmlstring;

StandardMenu();
LoggedInMenu();
}

?>		
<div id="background">$nbsp</div>


</body>
</html>