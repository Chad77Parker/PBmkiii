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


function UpdateWeekDay(){
var Startday = document.DateSelect.StartDateday
var Startmonth = document.DateSelect.StartDatemonth
var Startyear = document.DateSelect.StartDateyear
var Endday = document.DateSelect.EndDateday
var Endmonth = document.DateSelect.EndDatemonth
var Endyear = document.DateSelect.EndDateyear
var StartdaySelected = Startday.options[Startday.options.selectedIndex].value;
Startday.options.length=0;
var weekday=new Array(7);
weekday[0]="Sunday";
weekday[1]="Monday";
weekday[2]="Tuesday";
weekday[3]="Wednesday";
weekday[4]="Thursday";
weekday[5]="Friday";
weekday[6]="Saturday";
eom=0;
var selected = false;
i = 0;
var mydate = new Date();
mydate.setFullYear(Startyear.options[Startyear.options.selectedIndex].value)
mydate.setMonth((Startmonth.options[Startmonth.options.selectedIndex].value-1))
mydate.setDate(01);
currentmonth = mydate.getMonth();
while(eom!=1){
	if (i == (StartdaySelected - 1)){
	 selected=true;
	}
	NewText =  weekday[mydate.getDay()] + " " + mydate.getDate();
	NewValue = mydate.getDate();
	Startday.options[i] = new Option(NewText, NewValue, selected, selected);
	selected=false;
	i++;
	mydate.setDate(mydate.getDate()+1);
	if (mydate.getMonth()!=currentmonth){
	 eom=1;
	}
}
eom=0;
selected = false;
i = 0;
EnddaySelected = Endday.options[Endday.options.selectedIndex].value;
var myenddate = new Date()
myenddate.setFullYear(Endyear.options[Endyear.options.selectedIndex].value)
myenddate.setMonth((Endmonth.options[Endmonth.options.selectedIndex].value)-1)
myenddate.setDate(01);
currentmonth = myenddate.getMonth();
while(eom!=1){
	if (i == (EnddaySelected - 1)){
	 selected=true;
	}
	NewText =  weekday[myenddate.getDay()] + " " + myenddate.getDate();
	NewValue = myenddate.getDate();
	Endday.options[i] = new Option(NewText, NewValue, selected, selected);
	selected=false;
	i++;
	myenddate.setDate(myenddate.getDate()+1);
	if (myenddate.getMonth()!=currentmonth){
	 eom=1;
	}
}
}
</script>
<?php
	echo '<p class="general">

	<h3>Select Criteria To View Employee Hours</h3>
	<form name="DateSelect" action="ViewEmployeeHours.php" method="post">
	<table border="2"><tr>
	<td class="general">Start Date
	<td class="general">End Date';
/*** Can be added for admin to access all employees
echo '<td class="general">Employee';
***/
$now=getdate(time()-(13*24*60*60));
 echo '<tr><td class="general"><select name="'.'StartDate'.'year" onChange=UpdateWeekDay()>';
    $d['year']=2009;
    while($d['year']<2020){
        echo '<option';
        if ($d['year']==$now['year']){
            echo ' selected="selected"';
        }
	echo ' value="';
        echo $d['year'].'">'.$d['year'].'</option>';
 
    $d['year']++;
    }

    echo '</select><select name="'.'StartDate'.'month" onChange=UpdateWeekDay()>';
    $d['mon']=1;
    while($d['mon']<13){
        echo '<option';
        if ($d['mon']==$now['mon']){
            echo ' selected="selected"';
        }
	echo ' value="';
        if ($d['mon']<10){
	echo '0';
	}
	echo $d['mon'].'">'.$d['mon'].'</option>';
    $d['mon']++;
    }
    echo '</select>/<select name="'.'StartDate'.'day">';
    $d['mday']=1;
    while($d['mday']<32){
        echo '<option';
        if ($d['mday']==$now['mday']){
            echo ' selected="selected"';
        }
        echo ' value="';
        if ($d['mday']<10){
	echo '0';
	}
	echo $d['mday'].'">'.date("l j", mktime(0,0,0,$now['mon'], $d['mday'], $now['year'])).'</option>';
    $d['mday']++;
    }

$now=getdate();
 echo '<td class="general"><select name="'.'EndDate'.'year" onChange=UpdateWeekDay()>';
    $d['year']=2009;
    while($d['year']<2020){
        echo '<option';
        if ($d['year']==$now['year']){
            echo ' selected="selected"';
        }
	echo ' value="';
        echo $d['year'].'">'.$d['year'].'</option>';
 
    $d['year']++;
    }

    echo '</select><select name="'.'EndDate'.'month" onChange=UpdateWeekDay()>';
    $d['mon']=1;
    while($d['mon']<13){
        echo '<option';
        if ($d['mon']==$now['mon']){
            echo ' selected="selected"';
        }
	echo ' value="';
        if ($d['mon']<10){
	echo '0';
	}
	echo $d['mon'].'">'.$d['mon'].'</option>';
    $d['mon']++;
    }
    echo '</select>/<select name="'.'EndDate'.'day">';
    $d['mday']=1;
    while($d['mday']<32){
        echo '<option';
        if ($d['mday']==$now['mday']){
            echo ' selected="selected"';
        }
        echo ' value="';
        if ($d['mday']<10){
	echo '0';
	}
	echo $d['mday'].'">'.date("l j", mktime(0,0,0,$now['mon'], $d['mday'], $now['year'])).'</option>';
    $d['mday']++;
    }


/* can be added for admin to access all employees
echo '<td class="general">'; 
$query='select Ind, FirstName, LastName from employees;';
$res=dbquery($query);
echo '<select name="EmployeeInd">';
$htmlstring='';
while ($row = dbfetchassoc($res)) {
	 $htmlstring=$htmlstring. '<option value="'.$row['Ind'].'">'.$row['FirstName'].' '.$row['LastName'].'</option>';
	}
echo $htmlstring;
*/
echo '<input type="hidden" name="EmployeeInd" value="'.$_SESSION['EmployeeInd'].'">';
echo '<tr><td class="general">Today is : <td class="general" colspan="2">';
$now=getdate();
echo date("l jS, F Y", $now[0]);
echo '<tr><td colspan="3"><input type="submit" Value="View Hours"></table>';
?>

</p>
</div>
</body>
</html>