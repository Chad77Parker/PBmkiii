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
<title>Daily Hours</title>';
echo MobileDetect();

?>
<script type="text/javascript" language="javascript"> 

function Dayoff(){
	
	var x=document.getElementById("StartTimehours")
    	x.options[x.selectedIndex].text="07"
	var x=document.getElementById("StartTimeminutes")
    	x.options[x.selectedIndex].text="00"
	var x=document.getElementById("EndTimehours")
    	x.options[x.selectedIndex].text="15"
	var x=document.getElementById("EndTimeminutes")
    	x.options[x.selectedIndex].text="00"
	var x=document.getElementById("Lunchhours")
    	x.options[x.selectedIndex].text="00"
	var x=document.getElementById("Lunchminutes")
    	x.options[x.selectedIndex].text="00"
	
	
}
function NewContact1() {
		window.location.assign("adddata.php?table=contacts&returnaddress=DailyHoursPart1.php")
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
	<h3>Enter Your Hours For The Day</h3>
	<p class="general">
	<form name="form1" action="DailyHoursPart2.php" method="post">
	<table class="normal" border="1">
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
echo '<td><input type="button" class="menubutton" value="New Contact" onclick="NewContact1()">';
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
echo '</select><td><input type="button" class="menubutton" value="Holiday or Sickday" onclick="Dayoff()"><tr><td class="general">Start Time  ';


    echo '<td class="general"><select id="StartTimehours" name="'.'StartTime'.'hours">';
    $x=00;
    while($x<24){
        echo '<option';
        if ($x==$now['hours']){
            echo ' selected="selected"';
        }
        echo '>';
        if ($x<10){
            echo '0';
        }
        echo $x.'</option>';
        
    $x++;
    }
echo '</select>:<select id="StartTimeminutes" name="'.'StartTime'.'minutes">';
    $x=00;
    while($x<60){
        echo '<option';
        if ($x==$now['minutes']){
            echo ' selected="selected"';
        }
        echo '>';
        if ($x<10){
            echo '0';
        }
        echo $x.'</option>';
    $x=$x+5;
    }
echo '</select>';
echo '<tr><td class="general"> Finish Time ';
$now=getdate();
$now['hours']=17;
$now['minutes']=00;
$now['seconds']=00;
 echo '<td class="general"><select id="EndTimehours" name="'.'EndTime'.'hours">';
    $x=00;
    while($x<24){
        echo '<option';
        if ($x==$now['hours']){
            echo ' selected="selected"';
        }
        echo '>';
        if ($x<10){
            echo '0';
        }
        echo $x.'</option>';
        
    $x++;
    }echo '</select>:<select id="EndTimeminutes" name="'.'EndTime'.'minutes">';
    $x=00;
    while($x<60){
        echo '<option';
        if ($x==$now['minutes']){
            echo ' selected="selected"';
        }
        echo '>';
        if ($x<10){
            echo '0';
        }
        echo $x.'</option>';
    $x=$x+5;
    }echo '</select>';

echo '<tr><td class="general">Lunch break <td class="general">';
echo '<select id="Lunchhours" name="'.'Lunch'.'hours'.'">';
	$now['hours']=00;
	$now['minutes']=30;
	$now['seconds']=00;
    $x=00;
    while($x<24){
        echo '<option';
        if ($x==$now['hours']){
            echo ' selected="selected"';
        }
        echo '>';
        if ($x<10){
            echo '0';
        }
        echo $x.'</option>';
        
    $x++;
    }echo '</select>:<select id="Lunchminutes" name="'.'Lunch'.'minutes">';
    $x=00;
    while($x<60){
        echo '<option';
        if ($x==$now['minutes']){
            echo ' selected="selected"';
        }
        echo '>';
        if ($x<10){
            echo '0';
        }
        echo $x.'</option>';
    $x=$x+5;
    }echo '</select>';
	
echo '<tr><td colspan="3" align="center"><input type="submit" Value="Next" class="menubutton">';

?>
</table>
</form>


</p>
</div>


<img id="background" >

</body>
</html>