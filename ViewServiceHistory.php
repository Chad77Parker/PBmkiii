<?php
//load global functions, check that user is logged in, and initialise database***********
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
echo MobileDetect(); /*must be in html header*/
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
//begin page specific code******************************************************
<div id="scroller">
<p class="general">
<?php
$StartDate=$_POST['StartDateyear'].'-'.$_POST['StartDatemonth'].'-'.$_POST['StartDateday'];
$EndDate=$_POST['EndDateyear'].'-'.$_POST['EndDatemonth'].'-'.$_POST['EndDateday'];
if($_POST['Vehicle']=='All'){
    $query = 'Select * from parkerbros.servicehistory where ServDate between "'.$StartDate.'" and "'.$EndDate.'" order by ServDate DESC;';
}else{
$query = 'Select * from parkerbros.servicehistory where Vehicle="'.$_POST['Vehicle'].'" and ServDate between "'.$StartDate.'" and "'.$EndDate.'" order by ServDate DESC;';
}

$res = mysql_query($query);
if(!$res){
	die(mysql_error());
}
$query2 = 'select * from parkerbros.Vehicles where Ind="'.$_POST['Vehicle'].'";';
$res2 = mysql_query($query2);
if(!$res2){
	die(mysql_error());
}
$row2=mysql_fetch_assoc($res2);

echo '<form action="PrintServiceHistory.php" method="post"><table><tr><td colspan="8" class="SubHeading1">Service History for '.$row2['Make'].' '.$row2['Model'].', '.$row2['Name'].'<tr>'.
     '<td colspan="8" class="general">From '.date('l jS \of F, Y.',strtotime($StartDate)).' to '.date('l jS \of F, Y.',strtotime($EndDate)).
     '<tr><td class="general">Date<td class="general">Amount<td class="general">Units<td class="general">Schedueled<td class="general">DailyChecklist#<td class="general">Item serviced<td class="general">Work Completed  <td class="general">Comments';
while($row=mysql_fetch_assoc($res)){
     echo '<tr><td class="general">'.$row['ServDate'].'<td class="general">'.$row['ServUnitsAmount'].'<td class="general">'.$row['ServUnitsType'].'<td class="general">'.$row['SchedueledService'].'<td class="general">'.$row['DailyChecklistInd'].'<td class="general">'.$row['ServItem'].'<td class="general">'.$row['WorkCompleted'].'<td class="general">'.$row['Comments'];


}

echo '<input name="StartDate" type="hidden" value="'.$StartDate.'"><input name="EndDate" type="hidden" value="'.$EndDate.'"><input name="Vehicle" type="hidden" value="'.$_POST['Vehicle'].'">
  <tr><td><input type="submit" value="Print"></td>
  </table></form>';



?>
</p>
</div>

//end page specific code********************************************************
<div id="background">&nbsp</div>

</body>
</html>