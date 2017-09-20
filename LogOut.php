<?php
session_start();
$_SESSION['loggedin']=false;
include 'GlobalFunctions.php';
echo '
<html>
<head>
<title>Parker Bros Earthmoving Pty Ltd</title>';
echo MobileDetect();
?>

  <meta name="description" content="Earthmoving">
  <meta name="keywords" content="earthmoving, dams, driveways, soil conservation, erosion control, water cartage, water delivery, road construction, earthworks">
</head>
<body>

<img id="topbanner" src="images\pbbanner1.jpg" height="100%" border="0">
<div id="topbanner">
Parker Bros Earthmoving Pty Ltd.
</div>


<div id="scroller">
<h3>You have successfully logged out.<br>Thank you.<br></h3>
<p class="general">
This site is under construction and is only for testing purposes no information contained herein is of any factual events or persons
</p>
</div>
<?php
StandardMenu();
if ($_SESSION['loggedin'] and !checktimeout()){
	LoggedInMenu();
}
?>

<div id="background">&nbsp</div>


</body>
</html>