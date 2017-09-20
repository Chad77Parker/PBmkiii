
<?php
session_start();
include 'GlobalFunctions.php';
if ($_SESSION['loggedin']!=true){
die('You are not authorised to view this page');
}
if (checktimeout()){
	die('Your connection has expired please log back in.<a href="ParkerBros.php">Return Home</a>');
}
echo '<html>
      <head>
      <title>Parker Bros Earthmoving Pty Ltd</title>';
echo MobileDetect(); /*must be in html header*/
?>

</head>
<body>

<img id="topbanner" src="images\pbbanner1.jpg"  border="0">
<div id="topbanner">
Parker Bros Earthmoving Pty Ltd.
</div>


<?php

StandardMenu();
if ($_SESSION['loggedin'] and !checktimeout()){
	LoggedInMenu();
}
?>

<div id="scroller">

<p class="general">
<?php

//path to mysql dump utility
$dump_path = "C:\\xampp\\mysql\\bin\\";

// location to store backups
$save_path = "E:\\Documents\\ParkerBros\\Backups";

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
// mysql credentials
$host = "localhost";
$user = "root";
$pass = "keogh";

// mysql connection
mysql_connect( $host , $user , $pass );

// format dir name
$today = '\\'.date("m-d-Y");

//check if directory exists otherwise create it
if ( !file_exists ( is_dir ($save_path.$today) ) )
{
    mkdir( $save_path . $today );
}

// list all mysql dbs
$result = mysql_list_dbs();
//debug echo 'databases;'.$result;
// init counter var
$i = 0;
   // list all databases in mysql
while ( $i < mysql_num_rows ( $result ) )
{
    $tb_names[$i] = mysql_tablename ( $result, $i );
          $i++;
}

// loop through table names and do the dump
for ( $i=0; $i<count($tb_names); $i++ )
{
    $do = $dump_path . "mysqldump -h" . $host . " -u" . $user . " -p" . $pass . " --opt " . $tb_names[$i] . " > " . $save_path . $today . "\\" . $tb_names[$i] . ".sql";
   echo '<br>'.$do;
    system($do);
}


$query='update parkerbros.employees set LastBackup="'.date('Y/m/d H:i').'" where Ind='.$_SESSION['EmployeeInd'].';';
$res=mysql_query($query);
if(!$res){
  die(mysql_error());
}
?>
<h3> <a href="ParkerBros.php">DataBase successfully backed up. Return Home</a></h3>
</p>
</div>

<div id="background">&nbsp</div>






</body>
</html>