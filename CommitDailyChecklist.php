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
<p class="general">

<?php

/* Code for saving Daily Checklist */
	if ($_POST['Vehicle']!=='false'){
   	$query='insert into dailychecklist (Vehicle, Date, JobInd, Fluids, Wear_or_Damage, Wheels_Tracks_Tyres, Hydraulics, Attachments, Cabin, Load_Capacity_Plate, Brakes, Controls, Warning_Devices, Other)
     values ("'.$_POST['Vehicle'].'", "'.$_POST['DailyChecklistDate'.'year'].$_POST['DailyChecklistDate'.'month'].$_POST['DailyChecklistDate'.'day'].'", "'.$_POST['JobInd'].'"';


     $query2='select * from dailychecklist;';
     $res=dbquery($query2);

  $ChecklistInd = dbnumrows($res)+1;
      $i = 0;
     while($i < dbnumfields($res)) {
       switch (dbfieldname($res, $i)){
       case 'Ind':
     	      break;
       case 'Vehicle':
            break;
       case 'Date':
     	      break;
       case 'JobInd';
            break;
       default:
            if( $_POST[dbfieldname($res, $i)]=="OK"){
                $query =$query .',"'.$_POST[dbfieldname($res, $i)].'"';
            }
            else{
                 $query =$query .',"'.$_POST['action'.dbfieldname($res, $i)].'"';
                 $comments = $_POST['Comment'.dbfieldname($res, $i)];
                 $query3='insert into dailychecklistfaults (DailyChecklistInd, CheckField, Comments) values("'.$ChecklistInd.'", "'.mysql_field_name($res, $i).'", "'.$comments .'");';
                 $res2=dbquery($query3);
            }
      }
      $i++;
     }
$query =$query .');';
$res=dbquery($query);

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