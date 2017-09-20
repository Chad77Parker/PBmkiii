<?php
session_start();
session_start();
if ($_SESSION['loggedin']!=true){
die('You are not authorised to view this page');
}
include 'GlobalFunctions.php';
if (checktimeout()){
	die('Your connection has expired please log back in.<a href="ParkerBros.php">Return Home</a>');
}
echo' 
<html>
<head>
<title>Commit to '.$_GET['table'].' Table</title>';
echo MobileDetect();
?>
<head>
<body>
<img id="topbanner" src="images\pbbanner1.jpg"  border="0">
<div id="topbanner">
Parker Bros Earthmoving Pty Ltd.
</div>

<?php
//connect to database
$link = @mysql_connect('localhost', $_SESSION['user'], $_SESSION['pass']);
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
echo '<div id="scroller">
	<p class="general">';


$res = mysql_query('select * from '.$_GET['table'], $link);




		
		$query='INSERT INTO '.$_GET['table'].' (';
		$i = 0;
	


		while($i < mysql_num_fields($res)) {
		if (mysql_field_name($res, $i) != 'Ind'){
			$query=$query.mysql_field_name($res, $i);
			if (($i+1) < mysql_num_fields($res)){
				$query=$query.', ';
				}
			}
		$i++;
		}

		$query=$query.') VALUES (';
		$i=0;
	

		$errorflag=0;
		while($i < mysql_num_fields($res)){
		if (mysql_field_name($res, $i) != 'Ind'){
		switch (mysql_field_type($res, $i)){
		case 'blob':
			if ($_POST[mysql_field_name($res, $i)]==""){
			echo '<a href="adddata.php?table='.$_GET['table'].'&returnaddress='.$_GET['returnaddress'].'">';
			echo mysql_field_name($res, $i).' Field not filled in.  Please reenter information</a><br>';
			$errorflag=1;
			break;
			}
			$query=$query.'"'.$_POST[mysql_field_name($res, $i)].'"';
			break;
		case 'string':
			if ($_POST[mysql_field_name($res, $i)]==""){
			echo '<a href="adddata.php?table='.$_GET['table'].'&returnaddress='.$_GET['returnaddress'].'">';
			echo mysql_field_name($res, $i).' Field not filled in.  Please reenter information</a><br>';
			$errorflag=1;
			break;
			}
			$query=$query.'"'.$_POST[mysql_field_name($res, $i)].'"';
			break;
		case 'datetime':
			$querytemp=$_POST[mysql_field_name($res, $i).'year'].
				$_POST[mysql_field_name($res, $i).'month'].
				$_POST[mysql_field_name($res, $i).'day'].
				$_POST[mysql_field_name($res, $i).'hours'].
				$_POST[mysql_field_name($res, $i).'minutes'].
				$_POST[mysql_field_name($res, $i).'seconds'];
			if ($querytemp==""){
			echo '<a href="adddata.php?table='.$_GET['table'].'&returnaddress='.$_GET['returnaddress'].'">';
			echo mysql_field_name($res, $i).' Field not filled in.  Please reenter information</a><br>';
			$errorflag=1;
			break;
			}
			$query=$query.$querytemp;
			break;
		case 'date':
			$querytemp=$_POST[mysql_field_name($res, $i).'year'].
				$_POST[mysql_field_name($res, $i).'month'].
				$_POST[mysql_field_name($res, $i).'day'];
			if ($querytemp==""){
			echo '<a href="adddata.php?table='.$_GET['table'].'&returnaddress='.$_GET['returnaddress'].'">';
			echo mysql_field_name($res, $i).' Field not filled in.  Please reenter information</a><br>';
			$errorflag=1;
			break;
			}
			$query=$query.$querytemp;
			break;
		default:
			if ($_POST[mysql_field_name($res, $i)]==""){
			echo '<a href="adddata.php?table='.$_GET['table'].'&returnaddress='.$_GET['returnaddress'].'">';
			echo mysql_field_name($res, $i).' Field not filled in.  Please reenter information</a><br>';
			$errorflag=1;
			break;
			}
			$query=$query.$_POST[mysql_field_name($res, $i)];
		}
		if (($i+1)<mysql_num_fields($res)){
		$query=$query.', ';
		}
		}
		$i++;
		}
		$query=$query.');';


	


if ($errorflag==0){
	echo $query;
	$result = mysql_query($query, $link) or die("Could not execute query: " . mysql_error());
	echo '<h3>DONE!<br>';
	echo '<h3><a href="'.$_GET['returnaddress'].'">Return to '.$_GET['returnaddress'].'</a></h3>';
	
}
?>


</p>
</div>


<div id="background" >&nbsp</div>



</body>
</html>