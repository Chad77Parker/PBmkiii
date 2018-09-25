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

$res = dbquery('select * from '.$_GET['table']);




		
		$query='INSERT INTO '.$_GET['table'].' (';
		$i = 0;
	


		while($i < dbnumfields($res)) {
		if (dbfieldname($res, $i) != 'Ind'){
			$query=$query.dbfieldname($res, $i);
			if (($i+1) < dbnumfields($res)){
				$query=$query.', ';
				}
			}
		$i++;
		}

		$query=$query.') VALUES (';
		$i=0;
	

		$errorflag=0;
		while($i < dbnumfields($res)){
		if (dbfieldname($res, $i) != 'Ind'){
		switch (dbfieldtype($res, $i)){
		case 'blob':
			if ($_POST[dbfieldname($res, $i)]==""){
			echo '<a href="adddata.php?table='.$_GET['table'].'&returnaddress='.$_GET['returnaddress'].'">';
			echo dbfieldname($res, $i).' Field not filled in.  Please reenter information</a><br>';
			$errorflag=1;
			break;
			}
			$query=$query.'"'.$_POST[dbfieldname($res, $i)].'"';
			break;
		case 'string':
			if ($_POST[dbfieldname($res, $i)]==""){
			echo '<a href="adddata.php?table='.$_GET['table'].'&returnaddress='.$_GET['returnaddress'].'">';
			echo dbfieldname($res, $i).' Field not filled in.  Please reenter information</a><br>';
			$errorflag=1;
			break;
			}
			$query=$query.'"'.$_POST[dbfieldname($res, $i)].'"';
			break;
		case 'datetime':
			$querytemp=$_POST[dbfieldname($res, $i).'year'].
				$_POST[dbfieldname($res, $i).'month'].
				$_POST[dbfieldname($res, $i).'day'].
				$_POST[dbfieldname($res, $i).'hours'].
				$_POST[dbfieldname($res, $i).'minutes'].
				$_POST[dbfieldname($res, $i).'seconds'];
			if ($querytemp==""){
			echo '<a href="adddata.php?table='.$_GET['table'].'&returnaddress='.$_GET['returnaddress'].'">';
			echo dbfieldname($res, $i).' Field not filled in.  Please reenter information</a><br>';
			$errorflag=1;
			break;
			}
			$query=$query.$querytemp;
			break;
		case 'date':
			$querytemp=$_POST[dbfieldname($res, $i).'year'].
				$_POST[dbfieldname($res, $i).'month'].
				$_POST[dbfieldname($res, $i).'day'];
			if ($querytemp==""){
			echo '<a href="adddata.php?table='.$_GET['table'].'&returnaddress='.$_GET['returnaddress'].'">';
			echo dbfieldname($res, $i).' Field not filled in.  Please reenter information</a><br>';
			$errorflag=1;
			break;
			}
			$query=$query.$querytemp;
			break;
		default:
			if ($_POST[dbfieldname($res, $i)]==""){
			echo '<a href="adddata.php?table='.$_GET['table'].'&returnaddress='.$_GET['returnaddress'].'">';
			echo dbfieldname($res, $i).' Field not filled in.  Please reenter information</a><br>';
			$errorflag=1;
			break;
			}
			$query=$query.$_POST[dbfieldname($res, $i)];
		}
		if (($i+1)<dbnumfields($res)){
		$query=$query.', ';
		}
		}
		$i++;
		}
		$query=$query.');';


	


if ($errorflag==0){
 	$result = dbquery($query);
	echo '<h3>DONE!<br>';
	echo '<h3><a href="'.$_GET['returnaddress'].'">Return to '.$_GET['returnaddress'].'</a></h3>';
	
}
?>


</p>
</div>


<div id="background" >&nbsp</div>



</body>
</html>