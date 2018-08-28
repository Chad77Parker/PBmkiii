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
<script type="text/javascript" language="javascript"> 
function NewJobEnable(){
	document.getElementById("NewJobDescription").disabled=true
	if (document.getElementById("NewJob").value=true){
		document.getElementById("NewJobDescription").disabled=false
		}
}
</script>
	<div id="scroller">
<?php
echo	'<h3>Enter Your Client and Date</h3>
	<p class="general">
	<form name="form1" action="JobHoursJobSelect.php" method="post">
	<table border="1">
	<tr><td class="general">Employee<td colspan="2" class="general">'.$_SESSION['FirstName'].' '.$_SESSION['LastName'];
echo 	'<tr><tr><td class="general">Client<td class="general">';
$query='select contacts.ContactFirstName, ContactLastName, Company, contacts.Ind from parkerbros.contacts left outer join parkerbros.jobs
on jobs.clientind=contacts.ind where contactselect="CLIENT" group by contacts.ind order by max(startdate) desc ;';
$res=dbquery($query);
echo '<select class="fifty" name="ClientInd">';
while ($row = dbfetchassoc($res)) {
		$htmlstring=$htmlstring. '<option value="'.$row['Ind'].'">'.$row['Company'].'.  Person in charge: '.$row['ContactFirstName'].' '.$row['ContactLastName'].'</option>';
	}
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
</body>
</html>