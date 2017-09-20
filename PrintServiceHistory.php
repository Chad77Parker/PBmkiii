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
      <title>Parker Bros Earthmoving Pty Ltd</title>
      <link href="data/standardprint.css" type="text/css" rel="stylesheet" />'; /*must be in html header*/
?>
  
</head>
<body>

<img id="topbanner" src="images\pbprintbanner1.jpg"  border="0">

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




?>

<div id="scroller">
<p class="general">
<?php
$StartDate=$_POST['StartDate'];
$EndDate=$_POST['EndDate'];
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

echo '<form><table border="1"><tr><td colspan="8" class="SubHeading1">Service History for '.$row2['Make'].' '.$row2['Model'].', '.$row2['Name'].'<tr>'.
     '<td colspan="8" class="SubHeading2">From '.date('l jS \of F, Y.',strtotime($StartDate)).' to '.date('l jS \of F, Y.',strtotime($EndDate)).
     '<tr><td class="SubHeading3">Date<td class="SubHeading3">Amount<td class="SubHeading3">Units<td class="SubHeading3">Schedueled<td class="SubHeading3">Daily Checklist#<td class="SubHeading3">Item serviced<td class="SubHeading3">Work Completed  <td class="SubHeading3">Comments';
while($row=mysql_fetch_assoc($res)){
     echo '<tr><td class="general">'.$row['ServDate'].'<td class="general">'.$row['ServUnitsAmount'].'<td class="general">'.$row['ServUnitsType'].'<td class="general">'.$row['SchedueledService'].'<td class="general">'.$row['DailyChecklistInd'].'<td class="general">'.$row['ServItem'].'<td class="general">'.$row['WorkCompleted'].'<td class="general">'.$row['Comments'];


}

echo '</table></form>
     <br><a href="ParkerBros.php">Home</a>
     <script type="text/javascript" language="javascript">
     window.print();
     </script>
';



?>
</p>
</div>


<div id="background">&nbsp</div>

</body>
</html>