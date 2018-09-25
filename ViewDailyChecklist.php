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
if ($_POST['query']=="ClientJobDate"){
  $qVehicle ='';
  $qJob ='';
  if($_POST['Vehicle']!='All'){
  $qVehicle=' vehicle='.$_POST['Vehicle'].' and';
  }
  if($_POST['JobInd']!='All'){
  $qJob=' JobInd='.$_POST['JobInd'].' and';
  }else if($_POST['ClientInd']!='All'){
  $query='select Ind from parkerbros.jobs where ClientInd='.$_POST['ClientInd'].' and StartDate between "'.$_POST['StartDateyear'].'-'.$_POST['StartDatemonth'].'-'.$_POST['StartDateday'].'" and "'.$_POST['EndDateyear'].'-'.$_POST['EndDatemonth'].'-'.$_POST['EndDateday'].'";';

  $res=dbquery($query);
         $qJob=' JobInd in(';
         $first=true;
         while($row=dbfetchassoc($res)){
         if($first){
                   $qJob=$qJob.$row['Ind'];
                   $first=false;
         }else{
                   $qJob=$qJob.', '.$row['Ind'];
         }
         }
         $qJob = $qJob.') and ';
                             }

$query='select * from parkerbros.dailychecklist where'.$qVehicle.$qJob.' date between "'.$_POST['StartDateyear'].'-'.$_POST['StartDatemonth'].'-'.$_POST['StartDateday'].'" and "'.$_POST['EndDateyear'].'-'.$_POST['EndDatemonth'].'-'.$_POST['EndDateday'].'" order by Jobind, vehicle, date;';

}elseif($_POST['query']='CurrentFaults') {
  $query='select * from parkerbros.dailychecklist where Fluids in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Wear_or_Damage in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Wheels_Tracks_Tyres in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Hydraulics in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Attachments in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Cabin in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Load_Capacity_Plate in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Brakes in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Controls in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Warning_Devices in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Other in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE");';
}
$res=dbquery($query);
echo '<form><table>';
$JobInd='hi';
$VehicleInd='hi';
$Client = $Job ='Unknown';
while($row=dbfetchassoc($res)){
    if ($JobInd!=$row['JobInd']){
        $query2='select JobDescription, ContactFirstName, ContactLastName, Company from parkerbros.jobs left outer join parkerbros.contacts on jobs.clientind=contacts.ind where jobs.ind='.$row['JobInd'].';';

        $res2=dbquery($query2);
        while($row2=dbfetchassoc($res2)){
        $Client =$row2['ContactFirstName'].' '.$row2['ContactLastName'].'.  '.$row2['Company'];
        $Job =$row2['JobDescription'];
        }
        echo '<tr><td><tr><td class="SubHeading1">'.$Client.'<td colspan="3" class="SubHeading2">'.$Job;
        $JobInd = $row['JobInd'];
    }
    $query3='select Name, Make, Model from parkerbros.vehicles where Ind='.$row['Vehicle'].';';

    $res3=dbquery($query3);
    while($row3=dbfetchassoc($res3)){
    $Vehicle =$row3['Name'].'.  '.$row3['Make'].' '.$row3['Model'];
    }
    $Date = $row['Date'];
    echo '<tr><td class="SubHeading2">Daily Checklist: '.$row['Ind'].'<td colspan="3" class="SubHeading2">'.date('l jS \of F, Y.',strtotime($Date)).'<tr><td class="SubHeading2" colspan="4">'.$Vehicle;
    $VehicleInd=$row['Vehicle'];

    $i = 0;
    $l=0;
    $htmlstring='';
    while($i < dbnumfields($res)) {

 switch (dbfieldname($res, $i)){
  case 'Ind':
	break;
  case 'Vehicle':
	break;
  case 'Date':
	break;
  case 'JobInd':
	break;
  case 'DailyChecklistInd':
	break;
  case 'CheckField':
	break;
  case 'Comments':
 	break;
  default:
     $sql = 'SELECT column_comment FROM information_schema.columns where table_name = "dailychecklist" and column_name="'.mysql_field_name($res, $i).'";';

     $queryd = dbquery($sql);
     $v = dbfetchrow($queryd);
     if($v){
         $description= $v[0];
         }
      $Comment ='';
      if($row[dbfieldname($res, $i)]!='OK'){
      $querycomment='select * from parkerbros.dailychecklistfaults where DailyChecklistInd="'.$row['Ind'].'" and CheckField="'.mysql_field_name($res, $i).'";';
      $rescomment=dbquery($querycomment);
      while($rowcomment=dbfetchassoc($rescomment)){
      $Comment = $rowcomment['Comments'];
      }
    }

      $htmlstring=$htmlstring. '<tr><td class="general"><b>'.dbfieldname($res, $i).'</b>.  '.$description.
	'<td class="general"><input type="textbox" name="'.dbfieldname($res, $i).'" value="'.$row[dbfieldname($res, $i)].'">
  <td class="general">Description<td class="general"><input type="text" name="Comment'.dbfieldname($res, $i).'" value="'.$Comment.'">' ;
 }
$i++;
}
echo $htmlstring;

}

echo '</table></form>';
?>
</div>
</body>
</html>