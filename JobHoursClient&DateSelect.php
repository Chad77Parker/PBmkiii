<?php
session_start();
if ($_SESSION['loggedin']!=true){
die('You are not authorised to view this page');
}
include 'GlobalFunctions.php';
if (checktimeout()){
	die('Your connection has expired please log back in.<a href="ParkerBros.php">Return Home</a>');
}
echo '
<html>
<head>
<title>Job Hours Client & Date Select</title>';
echo MobileDetect();
?>
<script type="text/javascript" language="javascript"> 
function NewJobEnable(){
	document.getElementById("NewJobDescription").disabled=true
	if (document.getElementById("NewJob").value=true){
		document.getElementById("NewJobDescription").disabled=false
		}
}
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


if ($_SESSION['loggedin']){
	include 'LoggedInMenu.php';
}
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

echo '
	<div id="scroller">
	<h3>Enter Your Client and Date</h3>
	<p class="general">
	<form name="form1" action="JobHoursJobSelect.php" method="post">
	<table border="1">
	<tr><td class="general">Employee<td colspan="2" class="general">'.$_SESSION['FirstName'].' '.$_SESSION['LastName'];
echo 	'<tr><tr><td class="general">Client<td class="general">';
$query='select contacts.ContactFirstName, ContactLastName, Company, contacts.Ind from parkerbros.contacts left outer join parkerbros.jobs
on jobs.clientind=contacts.ind where contactselect="CLIENT" group by contacts.ind order by max(startdate) desc ;';
$res=mysql_query($query, $link);
if (!$res){
	die(mysql_error());
}
echo '<select class="fifty" name="ClientInd">';
while ($row = mysql_fetch_assoc($res)) {
		$htmlstring=$htmlstring. '<option value="'.$row['Ind'].'">'.$row['Company'].'.  Person in charge: '.$row['ContactFirstName'].' '.$row['ContactLastName'].'</option>';
	}
	mysql_free_result($res2);
echo $htmlstring;
echo '<td><input type="button" class="menubutton" value="New Contact" onclick="">';
echo	'<tr><td class="general">Date';

$now=getdate();
$now['hours']=07;
$now['minutes']=00;
$now['seconds']=00;
 echo '<td class="general"><select name="'.'EmployeeHoursDate'.'day">';
    $x=1;
    while($x<32){
        echo '<option';
        if ($x==$now['mday']){
            echo ' selected="selected"';
        }
	echo '>';
        if ($x<10){
	echo '0';
	}
	echo $x.'</option>';
    $x++;
    }
    echo '</select>/<select name="'.'EmployeeHoursDate'.'month">';
    $x=1;
    while($x<13){
        echo '<option';
        if ($x==$now['mon']){
            echo ' selected="selected"';
        }
	echo '>';
        if ($x<10){
	echo '0';
	}
	echo $x.'</option>';
    $x++;
    }
    echo '</select>/<select name="'.'EmployeeHoursDate'.'year">';
    $x=1930;
    while($x<2020){
        echo '<option';
        if ($x==$now['year']){
            echo ' selected="selected">'.$x.'</option>';
        }
        else{
            echo '>'.$x.'</option>';
        }
    $x++;
    }
echo '</select>';


	
	
echo '<tr><td colspan="3" align="center"><input type="submit" Value="Next" class="menubutton">';

?>
</table>
</form>


</p>
</div>


<img id="background">

</body>
</html>