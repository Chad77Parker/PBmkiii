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
$StartDate=$_POST['StartDateyear'].'-'.$_POST['StartDatemonth'].'-'.$_POST['StartDateday'];
$EndDate=$_POST['EndDateyear'].'-'.$_POST['EndDatemonth'].'-'.$_POST['EndDateday'];
if($_POST['Vehicle']=='All'){
    $query = 'Select * from parkerbros.servicehistory where ServDate between "'.$StartDate.'" and "'.$EndDate.'" order by ServDate DESC;';
}else{
$query = 'Select * from parkerbros.servicehistory where Vehicle="'.$_POST['Vehicle'].'" and ServDate between "'.$StartDate.'" and "'.$EndDate.'" order by ServDate DESC;';
}

$res = dbquery($query);
$query2 = 'select * from parkerbros.Vehicles where Ind="'.$_POST['Vehicle'].'";';
$res2 = dbquery($query2);
$row2=dbfetchassoc($res2);

echo '<form action="PrintServiceHistory.php" method="post"><table><tr><td colspan="8" class="SubHeading1">Service History for '.$row2['Make'].' '.$row2['Model'].', '.$row2['Name'].'<tr>'.
     '<td colspan="8" class="general">From '.date('l jS \of F, Y.',strtotime($StartDate)).' to '.date('l jS \of F, Y.',strtotime($EndDate)).
     '<tr><td class="general">Date<td class="general">Amount<td class="general">Units<td class="general">Schedueled<td class="general">DailyChecklist#<td class="general">Item serviced<td class="general">Work Completed  <td class="general">Comments';
while($row=dbfetchassoc($res)){
     echo '<tr><td class="general">'.$row['ServDate'].'<td class="general">'.$row['ServUnitsAmount'].'<td class="general">'.$row['ServUnitsType'].'<td class="general">'.$row['SchedueledService'].'<td class="general">'.$row['DailyChecklistInd'].'<td class="general">'.$row['ServItem'].'<td class="general">'.$row['WorkCompleted'].'<td class="general">'.$row['Comments'];


}

echo '<input name="StartDate" type="hidden" value="'.$StartDate.'"><input name="EndDate" type="hidden" value="'.$EndDate.'"><input name="Vehicle" type="hidden" value="'.$_POST['Vehicle'].'">
  <tr><td><input type="submit" value="Print"></td>
  </table></form>';



?>
</p>
</div>
</body>
</html>