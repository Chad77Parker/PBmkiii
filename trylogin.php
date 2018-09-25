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
?>
<div id="background">&nbsp</div>
<div id="scroller">
<?php
$res = dbquery('select * from employees');

$_SESSION['loggedin']=false;
while ($row = dbfetchassoc($res)) {
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
		$res1=dbquery($query);
  }
}
$htmlstring = "";
if ($_SESSION['loggedin']==false){
	$htmlstring='<h3>Failed Login! Please make sure you are entering your details correctly and your authorised to enter this area</h3>
	 	<p class="notice"><a href="login.php">Retry</a></p>
		</div>';
}
else{
$htmlstring='<h3>Your Last Log In was at '.Date("g:i A",strtotime($LastLogin)).' on '.Date("l jS F Y",strtotime($LastLogin)).'<br><br>Welcome '.$FirstName.' '.$LastName.'</h3>';


$_SESSION['FirstName']=$FirstName;
$_SESSION['LastName']=$LastName;
$_SESSION['EmployeeInd']=$EmployeeInd;
$_SESSION['Permission']=$Permission;
setcookie('FirstName', $FirstName, time()+86400);
setcookie('LastName', $LastName, time()+86400);

if ($_SESSION['Permission'] == 'ADMIN'){
 $Dailycheck= DailyCheckListCheck();
 if ($Dailycheck > 0){
   $htmlstring=$htmlstring.'<h3>There are '.$Dailycheck.' faults reported in Daily Checklists</h3>';
   $htmlstring=$htmlstring.'<form action="ViewDailyChecklist.php" method="post"><input name="query" type="hidden" value="CurrentFaults"><input type="submit" value="View Daily Checklists"></form>';
 }
 if(RequireBackup()){
  $htmlstring=$htmlstring.'<br><h3>Database requires backup. <a href="Backup.php">Click here to execute backup. </a></h3><br>';
}
}
$htmlstring=$htmlstring.'</div>';
echo $htmlstring;

LoggedInMenu();
}

?>		



</body>
</html>