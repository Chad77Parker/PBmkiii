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
      $res=dbquery($query);
      echo '<br><h3> Service History Updated </h3>';
     if($DailyChecklistInd){
       $query2='select * from dailychecklist where Ind='.$DailyChecklistInd.';';
       $res2=dbquery($query2);
       $i = 3;
       $row = dbfetchrow($res2);
       while($i < dbnumfields($res2)-1) {

//debug       echo ' ROW:'.$row[$i];
       if($row[$i]=='LOW HAZARD/ASSESMENT REQUIRED' OR $row[$i]=='FAULT' OR $row[$i]=='DO NOT OPERATE'){
       $query3='update dailychecklist set  '.dbfieldname($res2, $i).'="REPAIRED" where Ind='.$DailyChecklistInd.';';
//debug       echo $query3 ;
       $res3=dbquery($query3);
       echo '<br><h3> Daily Checklists Updated </h3>';
       $query5='select max(Ind) from servicehistory;';
       $res5=dbquery($query5);
       $row1=dbfetchrow($res5);
       $query4='insert into dailychecklistfaults (DailyChecklistInd, CheckField, Comments) values('.$DailyChecklistInd.', "'.dbfieldname($res2, $i).
       '", "Item:'.$_POST['ItemServiced'.$x ].'. '.$_POST['WorkCompleted'.$x ].'.  See Service History Ind:'.$row1[0].'");';
//debug       echo '  '.$query4.'  ';
       $res4=dbquery($query4);
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
</body>
</html>