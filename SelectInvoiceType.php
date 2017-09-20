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
echo MobileDetect();
?>
<script type="text/javascript" language="javascript"> 
function ClientDate(){
	window.location.assign("SelectInvoiceByDate&Client.php");
}
function OSJobs(){
	window.location.assign("SelectInvoiceByOutstandingJobs.php");
}
function Previous(){
	window.location.assign("SelectPreviousInvoice.php");
}
function PreviousDetailed(){
	window.location.assign("SelectPreviousInvoiceDetailed.php");
}
function OpenJobs(){
	window.location.assign("SelectInvoiceByOpenJobs.php");
}
</script>  
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



StandardMenu();
if ($_SESSION['loggedin'] and !checktimeout()){
	LoggedInMenu();
}

echo '<div id="scroller">
	<p class="general">


<h3>Select Invoice Type</h3><br>
<input type="button" value="Create New Invoice by Outstanding Jobs" onclick=OSJobs() class="menu2button"><br>
<input type="button" value="Create New Invoice by Client and Date" onclick=ClientDate() class="menu2button"><br>
<input type="button" value="Create New Invoice by Open Jobs" onclick=OpenJobs() class="menu2button"><br>
<hr>
<input type="button" value="View Previous Invoice by Client and Date" onclick=Previous() class="menu2button"><br>
<input type="button" value="View Previous Invoice by Client and Date with Details" onclick=PreviousDetailed() class="menu2button"><br>
';
?>



</p>
</div>






<div id="background">&nbsp</div>

</body>
</html>