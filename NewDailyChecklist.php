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

echo '
<script type="text/javascript" language="javascript">
function reloadwithvehicle(Ind){
  newurl =  document.getElementById( "Vehicle").value;
  window.location.assign("NewDailychecklist.php?Vehicle=" +newurl+"&JobDesc='.$_GET['JobDesc'].'");
}
</script>
</head>
<body>

<img id="topbanner" src="images\pbbanner1.jpg"  border="0">
<div id="topbanner">
Parker Bros Earthmoving Pty Ltd.
</div>
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

if (!$_GET["JobDesc"]){

StandardMenu();
if ($_SESSION['loggedin'] and !checktimeout()){
	LoggedInMenu();
}
}
echo '	<div id="scroller">
	<p class="general">';
echo '	<form action="CommitDailyChecklist.php" method="post">
	<table border="1"><tr><td class="general">Vehicle';
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
  $res=mysql_query($query);
  while($row = mysql_fetch_assoc($res)){
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
$res=mysql_query($query);
if (!$res){
	die(mysql_error());
}
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
  default: 
     $sql = 'SELECT column_comment FROM information_schema.columns where table_name = "dailychecklist" and column_name="'.mysql_field_name($res, $i).'";';
     $queryd = mysql_query($sql) or die(mysql_error());
     $v = mysql_fetch_row($queryd);
     if($v){
         $description= $v[0];
         }

      $htmlstring=$htmlstring. '<tr><td class="general"><b>'.mysql_field_name($res, $i).'</b>.  '.$description.
	'<td class="general"><select name="'.mysql_field_name($res, $i).'"><option value= "OK">OK</option><option value="FAULT">FAULT</option></select><td class="general"><select name="action'.mysql_field_name($res, $i).'"><option>NO ACTION REQUIRED</option><option>OPERATOR REPAIRED/REFILLED</option><option>LOW HAZARD/ASSESMENT REQUIRED</option><option>DO NOT OPERATE</option></select><td class="general">Description<td class="general"><input type="text" name="Comment'.mysql_field_name($res, $i).'">' ;
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