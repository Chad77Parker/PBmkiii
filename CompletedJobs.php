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

/* Code for saving Job completed information */
$firstflag=true;
$x=$_POST['recnumber'];

$query='update jobs set EndDate="'.date("Y-m-d").'", CloseDate="'.date("Y-m-d").'", Status="COMPLETED" where Ind in (';
$query2='select * from jobs where Ind in (';
while($x >= 0){
  if(isset($_POST['check'.$x])){
  if (!$firstflag){
		$query=$query.', ';
		$query2=$query2.', ';
	}
	$firstflag=false;
	$query=$query.$_POST['check'.$x];
	$query2=$query2.$_POST['check'.$x];
	}
	$x--;
}
$query=$query.');';
$query2=$query2.');';
if($firstflag){
   die('<h3>No Jobs were selected! '.var_dump($_POST).'</h3></div><div id="background">&nbsp</div></body></html>');
}
$res=dbquery($query);
$res=dbquery($query2);
echo '<h3>The following jobs have been saved as completed:</h3><table border=2 class="threequarterwidth">';
while($row=dbfetchassoc($res)){
	$ClientQuery='select * from contacts where Ind="'.$row['ClientInd'].'";';
	$ClientRes=dbquery($ClientQuery);
 	while($ClientRow=dbfetchassoc($ClientRes)){
		echo 	'<tr><td class="general">'.$row['JobDescription'];
		echo 	'<td class="general">'.$ClientRow['Company'].'.  '.$ClientRow['ContactTitleOrPosition'].', '.$ClientRow['ContactFirstName'].' '.$ClientRow['ContactLastName'];
	}

}

echo 	'</table>';

?>

</div>
</body>
</html>