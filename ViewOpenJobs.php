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
<!--Begin page specific code-->
<?php
echo '<h3>Jobs that are currently open.<br><br>Select jobs that have been completed<br></h3><form action="CompletedJobs.php" method="post" ><table border=2 class="threequarterwidth">';
$query='select * from jobs where Status="OPEN";';
$res=dbquery($query);
if(!$res){
	die(mysql_error());
}
$x=0;
while($row=dbfetchassoc($res)){
	$ClientQuery='select * from contacts where Ind="'.$row['ClientInd'].'";';
	$ClientRes=dbquery($ClientQuery);
	if(!$ClientRes){
		die(mysql_error());
	}
	while($ClientRow=dbfetchassoc($ClientRes)){
		echo 	'<tr><td><input type="checkbox" name="check'.$x.'" value="'.$row['Ind'].'">
			<td class="general">'.$row['JobDescription'];
		echo 	'<td class="general">'.$ClientRow['Company'].'.  '.$ClientRow['ContactTitleOrPosition'].', '.$ClientRow['ContactFirstName'].' '.$ClientRow['ContactLastName'];
	}
  $x++;
}
echo 	'<input type="hidden" name="recnumber" value="'.$x.'"><tr><td colspan="3"><center><input type="submit" value = "Save jobs as completed."></center></table></form>';

?>

</div>






<div id="background">&nbsp</div>

</body>
</html>