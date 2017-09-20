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

  $res=mysql_query($query);
     if(!$res){
     	   die(mysql_error());
         }
         $qJob=' JobInd in(';
         $first=true;
         while($row=mysql_fetch_assoc($res)){
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
$res=mysql_query($query);
if(!$res){
	die(mysql_error());
}
echo '<form><table>';
$JobInd='hi';
$VehicleInd='hi';
$Client = $Job ='Unknown';
while($row=mysql_fetch_assoc($res)){
    if ($JobInd!=$row['JobInd']){
        $query2='select JobDescription, ContactFirstName, ContactLastName, Company from parkerbros.jobs left outer join parkerbros.contacts on jobs.clientind=contacts.ind where jobs.ind='.$row['JobInd'].';';

        $res2=mysql_query($query2);
        if(!$res2){
        	die(mysql_error());
        }
        while($row2=mysql_fetch_assoc($res2)){
        $Client =$row2['ContactFirstName'].' '.$row2['ContactLastName'].'.  '.$row2['Company'];
        $Job =$row2['JobDescription'];
        }
        echo '<tr><td><tr><td class="SubHeading1">'.$Client.'<td colspan="3" class="SubHeading2">'.$Job;
        $JobInd = $row['JobInd'];
    }
    $query3='select Name, Make, Model from parkerbros.vehicles where Ind='.$row['Vehicle'].';';

    $res3=mysql_query($query3);
        if(!$res3){
        	die(mysql_error());
        }
    while($row3=mysql_fetch_assoc($res3)){
    $Vehicle =$row3['Name'].'.  '.$row3['Make'].' '.$row3['Model'];
    }
    $Date = $row['Date'];
    echo '<tr><td class="SubHeading2">Daily Checklist: '.$row['Ind'].'<td colspan="3" class="SubHeading2">'.date('l jS \of F, Y.',strtotime($Date)).'<tr><td class="SubHeading2" colspan="4">'.$Vehicle;
    $VehicleInd=$row['Vehicle'];

    $i = 0;
    $l=0;
    $htmlstring='';
    while($i < mysql_num_fields($res)) {

 switch (mysql_field_name($res, $i)){
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

     $queryd = mysql_query($sql) or die(mysql_error());
     $v = mysql_fetch_row($queryd);
     if($v){
         $description= $v[0];
         }
      $Comment ='';
      if($row[mysql_field_name($res, $i)]!='OK'){
      $querycomment='select * from parkerbros.dailychecklistfaults where DailyChecklistInd="'.$row['Ind'].'" and CheckField="'.mysql_field_name($res, $i).'";';
      $rescomment=mysql_query($querycomment) or die(mysql_error());
      while($rowcomment=mysql_fetch_assoc($rescomment)){
      $Comment = $rowcomment['Comments'];
      }
    }

      $htmlstring=$htmlstring. '<tr><td class="general"><b>'.mysql_field_name($res, $i).'</b>.  '.$description.
	'<td class="general"><input type="textbox" name="'.mysql_field_name($res, $i).'" value="'.$row[mysql_field_name($res, $i)].'">
  <td class="general">Description<td class="general"><input type="text" name="Comment'.mysql_field_name($res, $i).'" value="'.$Comment.'">' ;
 }
$i++;
}
echo $htmlstring;

}

echo '</table></form>';
?>

</div>






<div id="background">&nbsp</div>

</body>
</html>