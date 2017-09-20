<?php
session_start();
if ($_SESSION['loggedin']!=true){
die('You are not authorised to view this page');
}
include 'GlobalFunctions.php';
if (checktimeout()){
	die('Your connection has expired please log back in.<a href="ParkerBros.php">Return Home</a>');
}
?> 
<html>
<head>
<title>Add Data to 
<?php
echo $_GET['table'];
?>
 Table</title>
<?php
echo MobileDetect();

?>

</script>
</head>
<body>

<img id="topbanner" src="images\pbbanner1.jpg" height="100%" border="0">
<div id="topbanner">
Parker Bros Earthmoving Pty Ltd.
</div>



<?php
StandardMenu();
if ($_SESSION['loggedin']){
	LoggedInMenu();
}
?>

<div id="scroller">

<?php
$tablename=$_GET['table'];
echo '<form action="commit.php?action=add&table='.$tablename.'&returnaddress='.$_GET['returnaddress'].'" method="post">';

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

//query database for field names
$res = mysql_query('select * from '.$_GET['table'], $link);

//build table
echo '<table border="2">';

echo buildrecord($_GET['table'],$link,'add');
echo '<tr><td colspan="2" style="text-align: center;"><input type="submit" name="submit" value="Submit">';
echo "</table>";


?>

</div>

</form>
<div id="background">&nbsp</div>

</body>
</html>