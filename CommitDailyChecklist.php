<?php
session_start();
include 'GlobalFunctions.php';
echo '<html>
      <head>
      <title>Parker Bros Earthmoving PTY LTD</title>';
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
<p class="general">

<?php

/* Code for saving Daily Checklist */
	if ($_POST['Vehicle']!=='false'){
   	$query='insert into dailychecklist (Vehicle, Date, JobInd, Fluids, Wear_or_Damage, Wheels_Tracks_Tyres, Hydraulics, Attachments, Cabin, Load_Capacity_Plate, Brakes, Controls, Warning_Devices, Other)
     values ("'.$_POST['Vehicle'].'", "'.$_POST['DailyChecklistDate'.'year'].$_POST['DailyChecklistDate'.'month'].$_POST['DailyChecklistDate'.'day'].'", "'.$_POST['JobInd'].'"';


     $query2='select * from dailychecklist;';
     $res=mysql_query($query2);
     if (!$res){
     	die(mysql_error());
      }
  $ChecklistInd = mysql_num_rows($res)+1;
      $i = 0;
     while($i < mysql_num_fields($res)) {
       switch (mysql_field_name($res, $i)){
       case 'Ind':
     	      break;
       case 'Vehicle':
            break;
       case 'Date':
     	      break;
       case 'JobInd';
            break;
       default:
            if( $_POST[mysql_field_name($res, $i)]=="OK"){
                $query =$query .',"'.$_POST[mysql_field_name($res, $i)].'"';
            }
            else{
                 $query =$query .',"'.$_POST['action'.mysql_field_name($res, $i)].'"';
                 $comments = $_POST['Comment'.mysql_field_name($res, $i)];
                 $query3='insert into dailychecklistfaults (DailyChecklistInd, CheckField, Comments) values("'.$ChecklistInd.'", "'.mysql_field_name($res, $i).'", "'.$comments .'");';
                 $res2=mysql_query($query3, $link);
		             if (!$res2){
                    echo $query3 ;
			              die(mysql_error());
                 }
            }
      }
      $i++;
     }
$query =$query .');';
/* debug  echo $query ;  */
		$res=mysql_query($query, $link);
		if (!$res){
      echo $query ;
			die(mysql_error());
		}
echo '<h3> Daily Checklist successfully saved </h3>';
}
else{
  echo '<h3> Please select vehicle </h3>';
}
?>

</p>
</div>




<div id="background">&nbsp</div>
</body>
<?php
if ($_POST['JobInd']!=0){
  echo '<script type="text/javascript" language="javascript">
  window.close();
  </script>';
}
?>
</html>