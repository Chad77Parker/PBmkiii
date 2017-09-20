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
      <title>View Employee Hours</title>';
echo MobileDetect();
?>

</head>
<body>

<img id="topbanner" src="images\pbbanner1.jpg" height="100%" border="0">
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
$query='select FirstName, LastName from employees where Ind='.$_POST['EmployeeInd'].';';
$res=mysql_query($query);
while ($row = mysql_fetch_assoc($res)){
	$Name=$row['FirstName'].' '.$row['LastName'];
}
mysql_free_result($res);
echo '<h3>Hours For '.$Name.'.<br> 
	Between '.date("l jS F", strtotime($_POST['StartDateyear'].$_POST['StartDatemonth'].$_POST['StartDateday'])).
	' and <br>'.date("l jS F Y", strtotime($_POST['EndDateyear'].$_POST['EndDatemonth'].$_POST['EndDateday'])).'.</h3>';

echo 	'<table class="threequarterwidth" border="2"><tr><td class="general">Start<td class="general">Finish<td class="general">Lunch<td class="general">Date<td class="general">Hours<td class="general">OverTime<td class="general">Total</td>';
$query='select StartTime, EndTime, Lunch, EmployeeHoursDate from employeehours2 where Employee='.$_POST['EmployeeInd'].' and EmployeeHoursDate between '.$_POST['StartDateyear'].$_POST['StartDatemonth'].$_POST['StartDateday'].' and '.$_POST['EndDateyear'].$_POST['EndDatemonth'].$_POST['EndDateday'].' Group by StartTime order by StartTime;';
$res=mysql_query($query);
if(!$res){
	die(mysql_error());
}
$TimeTotalAcc=0;
$TimeOverAcc=0;
while ($row = mysql_fetch_assoc($res)){
  $EHDate=strtotime($row['EmployeeHoursDate']);
	 $STime=strtotime($row['StartTime']);
	 $ETime=strtotime($row['EndTime']);
	 $Lunch=strtotime($row['Lunch']);
	 $TimeTotal=$ETime-$STime-$Lunch+$EHDate-date("Z",$STime)+(3600*date("I",$STime));
	 if(date("l",$STime)=='Saturday' or date("l",$STime)=='Sunday'){
	 $TimeOver=$TimeTotal;
	 }
	 else{
	 $TimeOver=$TimeTotal-28800;
	 }
	echo '<tr><td class="general">';
	echo date("H:i", $STime);
	echo '<td class="general">';
	echo date("H:i", $ETime);
	echo '<td class="general">';
	echo date("H:i", $Lunch);
	echo '<td class="general">';
	echo date("l jS F", $EHDate);
	echo '<td class="general">';

	if(date("l",$STime)=='Saturday' or date("l",$STime)=='Sunday'){
	 echo '0:00<td class="general">';
	 echo date("H:i", $TimeTotal);
	}
	else{
	 if($TimeTotal<(28800-date("Z",$STime))){
	 echo  date("H:i", $TimeTotal);
	 echo '<td class="general">00:00';
	 }
	 else{
	 echo '8:00<td class="general">';
	 echo date("H:i", $TimeOver);
 
	 }
	}
	echo '<td class="general">'.date("H:i",$TimeTotal);

  $TimeTotalAcc=$TimeTotalAcc+$TimeTotal+date("Z",$STime)-(3600*date("I",$STime));
  $TimeOverAcc=$TimeOverAcc+$TimeOver+date("Z",$STime)-(3600*date("I",$STime));
}
echo '<tr><td><td><td><td class="Totals">Totals<td class="Totals">';
$THour=intval(($TimeTotalAcc-$TimeOverAcc)/3600);
$Tmin=intval((($TimeTotalAcc-$TimeOverAcc)-($THour*3600))/60);
if($Tmin<10){$Tmin='0'.$Tmin;}
echo $THour.':'.$Tmin.':00';
echo '<td class="Totals">';
$THour=intval($TimeOverAcc/3600);
$Tmin=intval(($TimeOverAcc-($THour*3600))/60);
if($Tmin<10){$Tmin='0'.$Tmin;}
echo $THour.':'.$Tmin.':00<td class="Totals">';
$THour=intval($TimeTotalAcc/3600);
$Tmin=intval(($TimeTotalAcc-($THour*3600))/60);
if($Tmin<10){$Tmin='0'.$Tmin;}
echo $THour.':'.$Tmin.':00';


echo 	'</table></form><br>
	<form name="submitform" action="PrintEmployeeHours.php" method="post">
	<input type="hidden" name="EmployeeInd" value="'.$_POST['EmployeeInd'].'">
	<input type="hidden" name="StartDateyear" value="'.$_POST['StartDateyear'].'"> 
	<input type="hidden" name="StartDatemonth" value="'.$_POST['StartDatemonth'].'">
	<input type="hidden" name="StartDateday" value="'.$_POST['StartDateday'].'">
	<input type="hidden" name="EndDateyear" value="'.$_POST['EndDateyear'].'">
	<input type="hidden" name="EndDatemonth" value="'.$_POST['EndDatemonth'].'">
	<input type="hidden" name="EndDateday" value="'.$_POST['EndDateday'].'">
	<input type="submit" value="Print Hours"></form>';

?>
<p class="general">

</p>
</div>


<div id="background">&nbsp</div>

</body>
</html>