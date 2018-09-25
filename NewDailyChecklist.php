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
if (!$_GET["JobDesc"]){
StandardMenu();
if (checktimeout()){die('<div id= "scroller">You are not authorised to view this page or your session has expired please log in. <a href="ParkerBros.php">Return Home</a></div>');}
LoggedInMenu();
}
?>
<div id="background">&nbsp</div>
<div id="scroller">
<script type="text/javascript" language="javascript">
function reloadwithvehicle(Ind){
  newurl =  document.getElementById( "Vehicle").value;
  window.location.assign("NewDailychecklist.php?Vehicle=" +newurl+"&JobDesc='.$_GET['JobDesc'].'");
}
</script>
<?php
echo ' 	<p class="general">';
echo '	<form action="CommitDailyChecklist.php" method="post">
	<table border="1"><tr><td class="general">Vehicle';
$htmlstring='';
$query='select vehicles.Name, Make, Model, vehicles.Ind from parkerbros.vehicles left outer join parkerbros.vehiclehours
on vehiclehours.vehicle=vehicles.ind where StatusSelect="ACTIVE" group by vehicles.ind order by max(OperationDate) desc ;';
$res=dbquery($query);
echo '<select name="Vehicle" id="Vehicle" onChange=reloadwithvehicle()><option value="false">Please Select Vehicle</option>';
while ($row = dbfetchassoc($res)) {
    if($_GET['Vehicle'] == $row['Ind']){
      $htmlstring=$htmlstring.'<option value="'.$row['Ind'].'" selected="selected">'.$row['Name'].'.  '.$row['Make'].' '.$row['Model'].'</option>';
    }else{
    $htmlstring=$htmlstring.'<option value="'.$row['Ind'].'">'.$row['Name'].'.  '.$row['Make'].' '.$row['Model'].'</option>';
    }
	}
echo $htmlstring.'</select>';
echo	'<td class="general">Date';

$now=getdate();
$now['hours']=07;
$now['minutes']=00;
$now['seconds']=00;
 echo '<td class="general"><select name="'.'DailyChecklistDate'.'day">';
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
    echo '</select>/<select name="'.'DailyChecklistDate'.'month">';
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
    echo '</select>/<select name="'.'DailyChecklistDate'.'year">';
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
echo '</select>';
if($_GET['JobDesc']!=''){
  $query = 'select Ind, JobDescription from parkerbros.jobs;';
  $res=dbquery($query);
  while($row = dbfetchassoc($res)){
    $JobInd =$row['Ind']+1;
    if ($row['JobDescription']==$_GET['JobDesc']){
      $JobInd= $row['Ind'];
      break;
    }
  }
}else{
  $JobInd='0';
}
echo '<input type="hidden" name="JobInd" value="'.$JobInd.'">';
$query='select * from dailychecklist;';
$res=dbquery($query);
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
  default: 
     $sql = 'SELECT column_comment FROM information_schema.columns where table_name = "dailychecklist" and column_name="'.mysql_field_name($res, $i).'";';
     $queryd = dbquery($sql);
     $v = dbfetchrow($queryd);
     if($v){
         $description= $v[0];
         }

      $htmlstring=$htmlstring. '<tr><td class="general"><b>'.dbfieldname($res, $i).'</b>.  '.$description.
	'<td class="general"><select name="'.dbfieldname($res, $i).'"><option value= "OK">OK</option><option value="FAULT">FAULT</option></select><td class="general"><select name="action'.mysql_field_name($res, $i).'"><option>NO ACTION REQUIRED</option><option>OPERATOR REPAIRED/REFILLED</option><option>LOW HAZARD/ASSESMENT REQUIRED</option><option>DO NOT OPERATE</option></select><td class="general">Description<td class="general"><input type="text" name="Comment'.mysql_field_name($res, $i).'">' ;
 }
$i++;
}
echo $htmlstring;
   

echo '<tr><td colspan="5"><input type="submit" value="Submit">';
echo '</table></form>';

?>


</p>
</div>

<div id="background">&nbsp</div>

</body>
</html>