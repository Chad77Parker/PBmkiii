<?php
session_start();
include 'GlobalFunctions.php';
if($_COOKIE['MobileDev']=='true'){
setcookie('MobileDev', 'false', time()+432000);
$MobileDev='false';
}else{
 setcookie('MobileDev', 'true', time()+432000);
 $MobileDev='true';
}

echo '<html>
      <head>
      <title>Parker Bros Earthmoving Pty Ltd</title>';
if($MobileDev=='true'){
              $htmlstring= '<link href="data/iPhonestandard.css" type="text/css" rel="stylesheet" />
                           <meta name="viewport" content="width = 480" />';
         }else{
	            $htmlstring= '<link href="data/standard.css" type="text/css" rel="stylesheet" />';
         }
         echo($htmlstring);
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


if($MobileDev=='true'){$MobileDevcheck='checked=true';}else{$MobileDevcheck='';}
echo
  '<script type="text/javascript" language="javascript">
	function Home() {
		window.location.assign("ParkerBros.php")
	}
	function Login() {
	window.location.assign("Login.php")
  }
  function MobileDev(){
    window.location.assign("MobileHome.php")
  }
	</script>
	<div id="menu1">
	<p class="general">
	&nbsp<input type="button" onclick=Home() value="Home" class="menu1button">&nbsp
	<input type="button" onclick=Login() value="Employee Login" class="menu1button">&nbsp
  <input type="checkbox" onclick=MobileDev() '.$MobileDevcheck.' class="menu1button">Mobile Site<br>
  </p>
	</div>';
	
if ($_SESSION['loggedin'] and !checktimeout()){
	LoggedInMenu();
}
echo $_COOKIE['MobileDev'];
?>

<div id="scroller">

<p class="general">
This site is under construction and is only for testing purposes no information contained herein is of any factual events or persons

</p>
</div>
<?php
var_dump($_COOKIE['MobileDev']);
var_dump($_SESSION['MobileDev']);
?>
<div id="background">&nbsp</div>

</body>
</html>