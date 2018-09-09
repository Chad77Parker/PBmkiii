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
<script type="text/javascript" language="javascript">
function reloadwithvehicle(Ind){
  newurl =  document.getElementById( "Vehicle").value;
  window.location.assign("ServiceReport.php?Vehicle=" +newurl);
}
</script>
<?php
echo '	<form action="CommitServiceReport.php" method="post">
	<table border="1"><tr><td class="general">Vehicle<td class="general">';
$htmlstring='';
$query='select vehicles.Name, Make, Model, Registration, vehicles.Ind from parkerbros.vehicles left outer join parkerbros.vehiclehours
on vehiclehours.vehicle=vehicles.ind where StatusSelect="ACTIVE" group by vehicles.ind order by max(OperationDate) desc ;';
$res=dbquery($query);
echo '<select name="Vehicle" id="Vehicle" onChange=reloadwithvehicle()><option value="false">Please Select Vehicle</option>';
while ($row = dbfetchassoc($res)) {
    if($_GET['Vehicle'] == $row['Ind']){
      $htmlstring=$htmlstring.'<option value="'.$row['Ind'].'" selected="selected">'.$row['Name'].'.  '.$row['Make'].' '.$row['Model'].' REG:'.$row['Registration'].'</option>';
    }else{
    $htmlstring=$htmlstring.'<option value="'.$row['Ind'].'">'.$row['Name'].'.  '.$row['Make'].' '.$row['Model'].' REG:'.$row['Registration'].'</option>';
    }
	}
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
   $res = dbquery($query);
   $htmlstring = '<select name="DailyCheckListNumber"><option>No Daily Checklist</option>';
   while ($row = dbfetchassoc($res)) {
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
</div>
</body>
</html>