<?php
require_once 'GlobalFunctions.php';
require_once 'data/dbintegration.php';

echo '<html>
      <head>
      <title>Parker Bros Earthmoving Pty Ltd</title>';
echo '<link href="data/standardprint.css" type="text/css" rel="stylesheet" />';
echo '</head>
     <body>';

if (checktimeout()){die('<div id= "scroller">You are not authorised to view this page or your session has expired please log in. <a href="ParkerBros.php">Return Home</a></div>');}

echo '<div id="scroller">
     <img id="topbanner" src="images\pbprintbanner1.jpg"  border="0">';

?>

<div id="scroller">
<p class="general">
<?php
$StartDate=$_POST['StartDate'];
$EndDate=$_POST['EndDate'];
if($_POST['Vehicle']=='All'){
    $query = 'Select * from parkerbros.servicehistory where ServDate between "'.$StartDate.'" and "'.$EndDate.'" order by ServDate DESC;';
}else{
$query = 'Select * from parkerbros.servicehistory where Vehicle="'.$_POST['Vehicle'].'" and ServDate between "'.$StartDate.'" and "'.$EndDate.'" order by ServDate DESC;';
}

$res = dbquery($query);
$query2 = 'select * from parkerbros.Vehicles where Ind="'.$_POST['Vehicle'].'";';
$res2 = dbquery($query2);
$row2=dbfetchassoc($res2);

echo '<form><table border="1"><tr><td colspan="8" class="SubHeading1">Service History for '.$row2['Make'].' '.$row2['Model'].', '.$row2['Name'].'<tr>'.
     '<td colspan="8" class="SubHeading2">From '.date('l jS \of F, Y.',strtotime($StartDate)).' to '.date('l jS \of F, Y.',strtotime($EndDate)).
     '<tr><td class="SubHeading3">Date<td class="SubHeading3">Amount<td class="SubHeading3">Units<td class="SubHeading3">Schedueled<td class="SubHeading3">Daily Checklist#<td class="SubHeading3">Item serviced<td class="SubHeading3">Work Completed  <td class="SubHeading3">Comments';
while($row=dbfetchassoc($res)){
     echo '<tr><td class="general">'.$row['ServDate'].'<td class="general">'.$row['ServUnitsAmount'].'<td class="general">'.$row['ServUnitsType'].'<td class="general">'.$row['SchedueledService'].'<td class="general">'.$row['DailyChecklistInd'].'<td class="general">'.$row['ServItem'].'<td class="general">'.$row['WorkCompleted'].'<td class="general">'.$row['Comments'];


}

echo '</table></form>
     <br><a href="ParkerBros.php">Home</a>
     <script type="text/javascript" language="javascript">
     window.print();
     </script>
';



?>
</p>
</div>
</body>
</html>