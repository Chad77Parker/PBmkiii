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

/* Code for saving Service Report*/
	if ($_POST['Vehicle']!=='false'){
     $x = 0;
     while($x<5){
       $ServDate=$_POST['ServiceReportDate'.'year'].$_POST['ServiceReportDate'.'month'].$_POST['ServiceReportDate'.'day'];
       if($_POST['UnitsType']=="Date"){
          $ServUnitAmount=$ServDate ;
       }else{
         $ServUnitAmount =$_POST['ServiceUnitsAmount'];
       }
       
       if($_POST['DailyCheckListNumber']!=='No Daily Checklist' and $_POST['DailyCheckListNumber'] > 0){
         $DailyChecklistInd=$_POST['DailyCheckListNumber'];
       }else{
         $DailyChecklistInd = '0';
       }

     $query='insert into ServiceHistory (Vehicle, ServDate, ServUnitsType, ServUnitsAmount, SchedueledService,
      DailyChecklistInd,  ServItem, WorkCompleted, Comments)
     values ("'.$_POST['Vehicle'].'", "'.$ServDate.'", "'.$_POST['UnitsType'].'", "'.
     $ServUnitAmount.'", "'.$_POST['SchedueledService'].'", "'.$DailyChecklistInd.'", "'.
     $_POST['ItemServiced'.$x ].'", "'.$_POST['WorkCompleted'.$x ].'", "'.$_POST['Comments'.$x ].'");';

     if($_POST['ItemServiced'.$x ]){
//debug echo $query ;
      $res=mysql_query($query);
        if (!$res){
     	     die(mysql_error());
        }
       echo '<br><h3> Service History Updated </h3>';
     if($DailyChecklistInd){
       $query2='select * from dailychecklist where Ind='.$DailyChecklistInd.';';
       $res2=mysql_query($query2);
       if (!$res2){
          	die(mysql_error());
       }
       $i = 3;
       $row = mysql_fetch_row($res2);
       while($i < mysql_num_fields($res2)-1) {

//debug       echo ' ROW:'.$row[$i];
       if($row[$i]=='LOW HAZARD/ASSESMENT REQUIRED' OR $row[$i]=='FAULT' OR $row[$i]=='DO NOT OPERATE'){
       $query3='update dailychecklist set  '.mysql_field_name($res2, $i).'="REPAIRED" where Ind='.$DailyChecklistInd.';';
//debug       echo $query3 ;
       $res3=mysql_query($query3);
       if (!$res3){
          	die(mysql_error());
       }
       echo '<br><h3> Daily Checklists Updated </h3>';
       $query5='select max(Ind) from servicehistory;';
       $res5=mysql_query($query5);
       if (!$res5){
          	die(mysql_error());
       }
       $row1=mysql_fetch_row($res5);
       $query4='insert into dailychecklistfaults (DailyChecklistInd, CheckField, Comments) values('.$DailyChecklistInd.', "'.mysql_field_name($res2, $i).
       '", "Item:'.$_POST['ItemServiced'.$x ].'. '.$_POST['WorkCompleted'.$x ].'.  See Service History Ind:'.$row1[0].'");';
//debug       echo '  '.$query4.'  ';
       $res4=mysql_query($query4);
       if (!$res4){
          	die(mysql_error());
       }
       echo '<br><h3> Daily Checklist Faults Updated </h3>';

    }
     $i++;
    }
    }
    }


   $x++;
}


echo '<br><br><h3> Service Report successfully saved </h3>';
}
else{
  echo '<h3> Please select vehicle </h3>';
}
?>

</p>
</div>




<div id="background">&nbsp</div>
</body>

</html>