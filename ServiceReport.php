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
<script type="text/javascript" language="javascript">
function reloadwithvehicle(Ind){
  newurl =  document.getElementById( "Vehicle").value;
  window.location.assign("ServiceReport.php?Vehicle=" +newurl);
}
</script>
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
<?php
echo '	<form action="CommitServiceReport.php" method="post">
	<table border="1"><tr><td class="general">Vehicle<td class="general">';
$htmlstring='';
$query='select vehicles.Name, Make, Model, vehicles.Ind from parkerbros.vehicles left outer join parkerbros.vehiclehours
on vehiclehours.vehicle=vehicles.ind where StatusSelect="ACTIVE" group by vehicles.ind order by max(OperationDate) desc ;';
$res=mysql_query($query);
if (!$res){
	die(mysql_error());
}
echo '<select name="Vehicle" id="Vehicle" onChange=reloadwithvehicle()><option value="false">Please Select Vehicle</option>';
while ($row = mysql_fetch_assoc($res)) {
    if($_GET['Vehicle'] == $row['Ind']){
      $htmlstring=$htmlstring.'<option value="'.$row['Ind'].'" selected="selected">'.$row['Name'].'.  '.$row['Make'].' '.$row['Model'].'</option>';
    }else{
    $htmlstring=$htmlstring.'<option value="'.$row['Ind'].'">'.$row['Name'].'.  '.$row['Make'].' '.$row['Model'].'</option>';
    }
	}
	mysql_free_result($res);
echo $htmlstring.'</select>';
echo	'<td class="general">Date';

$now=getdate();
$now['hours']=07;
$now['minutes']=00;
$now['seconds']=00;
 echo '<td class="general"><select name="'.'ServiceReportDate'.'day">';
    $x=1;
    while($x<32){
        echo '<option';
        if ($x==$now['mday']){
            echo ' selected="selected"';
        }
	echo '>';
        if ($x<10){
	echo '0';
	}
	echo $x.'</option>';
    $x++;
    }
    echo '</select>/<select name="'.'ServiceReportDate'.'month">';
    $x=1;
    while($x<13){
        echo '<option';
        if ($x==$now['mon']){
            echo ' selected="selected"';
        }
	echo '>';
        if ($x<10){
	echo '0';
	}
	echo $x.'</option>';
    $x++;
    }
    echo '</select>/<select name="'.'ServiceReportDate'.'year">';
    $x=1930;
    while($x<2020){
        echo '<option';
        if ($x==$now['year']){
            echo ' selected="selected">'.$x.'</option>';
        }
        else{
            echo '>'.$x.'</option>';
        }
    $x++;
    }
echo '</select></td><td class="general">Service Units Amount<td class="general"><input type"Textbox" name="ServiceUnitsAmount"><td class="general">Service Units Type<td class="general"><select name="UnitsType"><option selected="selected">Date</option><option>Hours</option><option>Kilometers</option><select>';
$htmlstring = "";
if (isset($_GET['Vehicle'])){
  $query = 'select * from parkerbros.dailychecklist where Vehicle = '.$_GET['Vehicle'].' and  (Fluids in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Wear_or_Damage in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Wheels_Tracks_Tyres in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Hydraulics in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Attachments in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Cabin in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Load_Capacity_Plate in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Brakes in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Controls in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Warning_Devices in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Other in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE"));';
   $res = mysql_query($query);
   if (!$res){
	    die(mysql_error());
   }
   $htmlstring = '<select name="DailyCheckListNumber"><option>No Daily Checklist</option>';
   while ($row = mysql_fetch_assoc($res)) {
         $htmlstring =$htmlstring.' <option>'.$row['Ind'].'</option>';
   }
   $htmlstring = $htmlstring.'</select>';
}
echo '<tr><td class="general">Schedueled Service<td class="general"><select name="SchedueledService"><option selected="selected">NO</option><option>YES</option></select><td class="general">DailyChecklist Number<td class="general">'.$htmlstring;
$x = 0;
while($x<5){
echo '<tr><td class="general">Item Serviced<td class="general" colspan="7"><input class="menufullbutton" type="Textbox" name="ItemServiced'.$x.'">';
echo '<tr><td class="general">Work Completed<td class="general" colspan="7"><input class="menufullbutton" type="Textbox" name="WorkCompleted'.$x.'">
<tr><td class="general">Comments<td class="general" colspan="7"><input class="menufullbutton" type="Textbox" name="Comments'.$x.'">';
$x++;
}
echo '<tr><td colspan="8"><input type="submit"></table>
     </form>';
?>

<p class="general">
This site is under construction and is only for testing purposes no information contained herein is of any factual events or persons

</p>
</div>

//end page specific code********************************************************
<div id="background">&nbsp</div>

</body>
</html>