<?php
session_start();
include 'GlobalFunctions.php';
$_SESSION['host']="localhost";
$_SESSION['user']="root";
$_SESSION['pass']="keogh";
$_SESSION['datab']="parkerbros";
echo '
<html>
<head>
<title>Login</title>';
echo MobileDetect();
?>
</head>
<body>

<img id="topbanner" src="images\pbbanner1.jpg"  border="0">
<div id="topbanner">
Parker Bros Earthmoving Pty Ltd.
</div>

<div id="scroller">
<h3>Please enter your name and password below</h3>

<form action="trylogin.php" method="post">
<table border="3" color="white">
<tr><td class="general">First Name<td>



<?php
if (isset($_COOKIE['FirstName'])){
echo '<input name="FirstName" type="text" value="'.$_COOKIE['FirstName'].'">';
}
else{
echo '<input name="FirstName" type="text">';
}
echo '<tr><td class="general">Surname<td>';
if (isset($_COOKIE['LastName'])){
echo '<input name="LastName" type="text" value="'.$_COOKIE['LastName'].'">';
}
else{
echo '<input name="LastName" type="text">';
}
?>

<tr><td class="general">Password<td><input name="Password" type="password">
</table>
<input type="submit" value="Enter Members Area">
</form>
<p class="general">
The above are case sensitive so please make sure your "caps lock" is off and you enter your name with the first letter capitalised.</p>
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