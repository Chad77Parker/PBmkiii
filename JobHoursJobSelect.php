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
<h3>Enter Job Description</h3>
<p class="general">
<form action="JobHoursVehiclesMaterials.php" method="post">
<table border="1">
<tr><td class="general">Employee<td colspan="1" class="general"><?php echo $_SESSION['FirstName'].' '.$_SESSION['LastName']; ?>
<tr><td class="general">Date<td colspan="1" class="general"><input type="hidden" name="StartTime" value="0">
<?php
echo $_POST['EmployeeHoursDateday'].'/'.$_POST['EmployeeHoursDatemonth'].'/'.$_POST['EmployeeHoursDateyear'].'<input type="hidden" name="EmployeeHoursDate" value="'.$_POST['EmployeeHoursDateyear'].$_POST['EmployeeHoursDatemonth'].$_POST['EmployeeHoursDateday'].'">';

echo '<tr><tr><td colspan="2" align="center" class="general">Client<tr><td colspan="2" class="general">';
$query='select ContactFirstName, ContactLastName, ContactTitleOrPosition, Ind, Company from contacts where Ind='.$_POST['ClientInd'].';';
$res=dbquery($query);
while ($row = dbfetchassoc($res)) {
	 $htmlstring=$row['Company'].'.   Person in charge '.$row['ContactTitleOrPosition'].', '.$row['ContactFirstName'].' '.$row['ContactLastName'];
	}
echo $htmlstring;
echo '<input type="hidden" name="ClientInd" value="'.$_POST['ClientInd'].'">';

echo '<tr><td colspan="2" align="center" class="general">Job Description<tr><td class="general" colspan="2"><select name="JobDescription">';
$query='select JobDescription, ClientInd, StartDate, Employee, Status from jobs where ClientInd = '.$_POST['ClientInd'].' and Status="OPEN" and Employee='.$_SESSION['EmployeeInd'].' group by JobDescription order by StartDate desc;';
$res=dbquery($query);
$htmlstring='';
$PreviousJD='';
while ($row = dbfetchassoc($res)) {
	 if($row['JobDescription']!=$PreviousJD and $row['JobDescription']!=''){
	 $htmlstring=$htmlstring.'<option value="'.$row['JobDescription'].'">'.$row['JobDescription'].'</option>';
	 $PreviousJD=$row['JobDescription'];
	 }
	}
echo $htmlstring;
echo '</select>';
echo '<tr><td class="general">New Job<td class="general" colspan="1"><input type="checkbox" value="false" id="NewJob" name="NewJob" onchange=NewJobEnable()>';
echo '<tr><td class="general">New Job Description<td class="general" colspan="1"><input type="text" id="NewJobDescription" name="NewJobDescription" disabled="true">';
echo '<tr><td colspan="4"><input type="submit" Value="Next" class="menubutton">';
?>
</table>
</form>
</p>
</div>
</body>
</html>