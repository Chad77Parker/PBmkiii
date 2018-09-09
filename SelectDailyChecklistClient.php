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
</script>';

<?php
echo '<form name="DateSelect" action="SelectDailyChecklist.php" method="post">
     <table border ="1"><tr><td class="general">Select Checklist by: <tr><td class="general">Client
     <tr>><td class="general">';
$query='select contacts.ContactFirstName, ContactLastName, Company, contacts.Ind from parkerbros.contacts left outer join parkerbros.jobs
on jobs.clientind=contacts.ind where contactselect="CLIENT" group by contacts.ind order by max(jobs.startdate) desc;';
$res=dbquery($query);
echo '<select class="fifty" name="ClientInd"><option value="All">All Clients</option>';
while ($row = dbfetchassoc($res)) {
		$htmlstring=$htmlstring. '<option value="'.$row['Ind'].'">'.$row['Company'].'.  Person in charge: '.$row['ContactFirstName'].' '.$row['ContactLastName'].'</option>';
	}
echo $htmlstring;
echo '<tr><td><input type="submit">';
echo '</table></form>';
 $Dailycheck= DailyCheckListCheck($link);
echo '<br><br><h3>There are '.$Dailycheck.' faults reported in Daily Checklists</h3>';
     '<form action="ViewDailyChecklist.php" method="post"><input name="query" type="hidden" value="CurrentFaults"><input type="submit" value="View Daily Checklists"></form>';

?>
</div>
</body>
</html>