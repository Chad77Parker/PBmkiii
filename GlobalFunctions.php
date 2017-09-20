<?php
if (!isset($_SESSION)) { session_start(); }

//*********************************************************************
// function for checking if user has been logged in for more than 30 mins
function checktimeout(){
if (($_SESSION['SessionStartTime']+1800)>time()){
	return(false);
}
else{
	return(true);
}
$_SESSION['SessionStartTime']=time();
}
//************************************************************************
// Standard Menu
function StandardMenu(){
  $MobileDev='';
if(isset($_COOKIE['MobileDev'])){
  if($_COOKIE['MobileDev']=='true'){
    $MobileDev='checked=true';
    }
}
echo '<script type="text/javascript" language="javascript">
	function Home() {
		window.location.assign("ParkerBros.php")
	}
	function Login() {
	window.location.assign("Login.php")
  }
  function MobileDev(){
    window.location.assign("MobileHome.php")
  }
  function GeneralInfo(){
    window.location.assign("GeneralInfo.php")
  }
	</script>
	<div id="menu1">

	&nbsp<input type="button" onclick=Home() value="Home" class="menu1button">&nbsp
	<input type="button" onclick=Login() value="Employee Login" class="menu1button">&nbsp
  <input type="checkbox" onclick=MobileDev() '.$MobileDev.' class="menu1button">Mobile Site
  <input type="button" onclick=GeneralInfo() value="Information" class="menu1button">&nbsp
	</div>';

}
//************************************************************************
// Logged In Menu
function LoggedInMenu(){
$htmlfunc = '
<script type="text/javascript" language="javascript">

	function DailyHours() {
		window.location.assign("DailyHoursPart1.php")
	}
	function JobHours() {
		window.location.assign("JobHoursClient&DateSelect.php")
	}
	function NewContact() {
		window.location.assign("adddata.php?table=contacts&returnaddress=ParkerBros.php")
	}
	function ViewDailyHours() {
		window.location.assign("SelectEmployeeHours.php")
	}
  function Service(){
		window.location.assign("Service.php")
	}
 	function SWMS(){
		window.location.assign("SWMS.php")
	}function LogOut(){
		window.location.assign("LogOut.php")
	}
	';

switch($_SESSION['Permission']){
  case 'EMPLOYEE':
       $htmlbuttons = '
	<div id="menu2">
	<input type="button" onclick=DailyHours() value="Enter Daily Hours" class="menu2button"><br>
	<input type="button" onclick=JobHours() value="Enter Job Hours ONLY" class="menu2button"><br>
	<input type="button" onclick=ViewDailyHours() value="View Employee Hours" class="menu2button"><br>
	<input type="button" onclick=Service() value="Service" class="menu2button"><br>
	<input type="button" onclick=SWMS() value="SWMS" class="menu2button"><br>
	<input type="button" onclick=NewContact() value="New Contact" class="menu2button"><br>
	<input type="button" onclick=LogOut() value="Log Out" class="menu2button"><br>
	</div>';
       break;
  case 'ADMIN':
       $htmlbuttons = '
	<div id="menu2">
	<input type="button" onclick=DailyHours() value="Enter Daily Hours" class="menu2button"><br>
	<input type="button" onclick=JobHours() value="Enter Job Hours ONLY" class="menu2button"><br>
	<input type="button" onclick=ViewDailyHours() value="View Employee Hours" class="menu2button"><br>
	<input type="button" onclick=ViewInvoice() value="View Invoice" class="menu2button"><br>
	<input type="button" onclick=ViewOpenJobs() value="View Open Jobs" class="menu2button"><br>
  <input type="button" onclick=Service() value="Service" class="menu2button"><br>
	<input type="button" onclick=SWMS() value="SWMS" class="menu2button"><br>
	<input type="button" onclick=NewContact() value="New Contact" class="menu2button"><br>
	<input type="button" onclick=NewVehicle() value="New Vehicle" class="menu2button"><br>
	<input type="button" onclick=LogOut() value="Log Out" class="menu2button"><br>
	</div>';
       $htmlfunc = $htmlfunc.'
	     function NewVehicle() {
		       window.location.assign("adddata.php?table=vehicles&returnaddress=ParkerBros.php")
       }
       function ViewInvoice() {
           window.location.assign("SelectInvoiceType.php")
       }
	     function ViewOpenJobs(){
           window.location.assign("ViewOpenJobs.php")
      }
      ';
     break;
}
  $htmlfunc = $htmlfunc.'
  </script>';
  echo $htmlfunc.$htmlbuttons;
}
//****************************************************************
//function for checking dailychecklist
function DailyCheckListCheck($link){
  $query = 'select * from parkerbros.dailychecklist where Fluids in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Wear_or_Damage in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Wheels_Tracks_Tyres in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Hydraulics in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Attachments in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Cabin in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Load_Capacity_Plate in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Brakes in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Controls in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Warning_Devices in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
   or Other in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE");';

$res = mysql_query($query,$link);
return(mysql_num_rows($res));
}

//****************************************************************
function MobileDetect(){
         if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),'iphone')){
              $DeviceType="MOBILE";
         }elseif(isset($_COOKIE['MobileDev'])){
            if($_COOKIE['MobileDev']=='true'){
              $DeviceType="MOBILE";
              }
         }else{
              $DeviceType="PC";
         }
         if($DeviceType=="MOBILE"){
              $htmlstring= '<link href="data/iPhonestandard.css" type="text/css" rel="stylesheet" />
                           <meta name="viewport" content="width = 480" />';
         }else{
	            $htmlstring= '<link href="data/standard.css" type="text/css" rel="stylesheet" />';
         }
         return($htmlstring);
}

//****************************************************************
//function for checking if backup required
function RequireBackup(){
  $query = 'select Permission, Ind, LastBackup from parkerbros.employees where Ind="'.$_SESSION['EmployeeInd'].'";';
  $res = mysql_query($query);
  $RequireBackup = false;
  while($row=mysql_fetch_assoc($res)){
    //debug echo  date("d/m/Y H:i",(time()-2592000)).' time - 30 days > '.date("d/m/Y H:i", strtotime($row['LastBackup'])).'last backup date<br>';
    if((time()-2592000)>strtotime($row['LastBackup'])){
      if($row['Permission']='ADMIN'){
        $RequireBackup = true;
      }
    }
  }
  return($RequireBackup);
}


//****************************************************************
//function for date picker
function DatePicker(){



}
//****************************************************************
//function for building table for one record
function buildrecord($table, $link, $addedit){
$res = mysql_query('select * from '.$table, $link);

$htmlstring = "";
$i = 0;
while($i < mysql_num_fields($res)) {
 switch (mysql_field_name($res, $i)){
  case 'Ind':
	break;
  case (strpos(mysql_field_name($res, $i), 'Select')>1):
	$htmlstring=$htmlstring. '<tr><td class="general">'.mysql_field_name($res, $i).'<td>' ;
	$htmlstring=$htmlstring. '<select name="'.mysql_field_name($res, $i).'">';

	$res2=mysql_query('select * from '.mysql_field_name($res, $i).'table', $link);
	while ($row = mysql_fetch_assoc($res2)) {
		$htmlstring=$htmlstring. '<option value="'.$row['VALUE'].'">'.$row['LABEL'].'</option>';
	}
	mysql_free_result($res2);
	$htmlstring=$htmlstring. '</select>';
	break;
  default:
   $htmlstring=$htmlstring. '<tr><td class="general">'.mysql_field_name($res, $i) ;
   $now = getdate();

   switch (mysql_field_type($res, $i)){
    case 'datetime':
    $htmlstring=$htmlstring. '<td><select name="'.mysql_field_name($res, $i).'day">';
    $x=1;
    while($x<32){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['mday']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
	$htmlstring=$htmlstring. '>';
        if ($x<10){
	$htmlstring=$htmlstring. '0';
	}
	$htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }
    $htmlstring=$htmlstring. '</select>/<select name="'.mysql_field_name($res, $i).'month">';
    $x=1;
    while($x<13){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['mon']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
	$htmlstring=$htmlstring. '>';
        if ($x<10){
	$htmlstring=$htmlstring. '0';
	}
	$htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }
    $htmlstring=$htmlstring. '</select>/<select name="'.mysql_field_name($res, $i).'year">';
    $x=1930;
    while($x<2020){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['year']){
            $htmlstring=$htmlstring. ' selected="selected">'.$x.'</option>';
        }
        else{
            $htmlstring=$htmlstring. '>'.$x.'</option>';
        }
    $x++;
    }
    $htmlstring=$htmlstring. '</select>  <select name="'.mysql_field_name($res, $i).'hours">';
    $x=00;
    while($x<24){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['hours']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
        $htmlstring=$htmlstring. '>';
        if ($x<10){
            $htmlstring=$htmlstring. '0';
        }
        $htmlstring=$htmlstring. $x.'</option>';

    $x++;
    }$htmlstring=$htmlstring. '</select>:<select name="'.mysql_field_name($res, $i).'minutes">';
    $x=00;
    while($x<60){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['minutes']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
        $htmlstring=$htmlstring. '>';
        if ($x<10){
            $htmlstring=$htmlstring. '0';
        }
        $htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }$htmlstring=$htmlstring. '</select>:<select name="'.mysql_field_name($res, $i).'seconds">';
    $x=00;
    while($x<60){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['seconds']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
        $htmlstring=$htmlstring. '>';
        if ($x<10){
            $htmlstring=$htmlstring. '0';
        }
        $htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }
    $htmlstring=$htmlstring. '</select>';

    break;
    case 'date':
    $htmlstring=$htmlstring. '<td><select name="'.mysql_field_name($res, $i).'day">';
    $x=1;
    while($x<32){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['mday']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
	$htmlstring=$htmlstring. '>';
        if ($x<10){
	$htmlstring=$htmlstring. '0';
	}
	$htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }
    $htmlstring=$htmlstring. '</select>/<select name="'.mysql_field_name($res, $i).'month">';
    $x=1;
    while($x<13){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['mon']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
	$htmlstring=$htmlstring. '>';
        if ($x<10){
	$htmlstring=$htmlstring. '0';
	}
	$htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }
    $htmlstring=$htmlstring. '</select>/<select name="'.mysql_field_name($res, $i).'year">';
    $x=1930;
    while($x<2020){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['year']){
            $htmlstring=$htmlstring. ' selected="selected">'.$x.'</option>';
        }
        else{
            $htmlstring=$htmlstring. '>'.$x.'</option>';
        }
    $x++;
    }
    $htmlstring=$htmlstring. '</select>';
    break;
    default:
    $htmlstring=$htmlstring. '<td><input type="text" name="'.mysql_field_name($res, $i).'">';
   }
  }
$i++;
}




return($htmlstring);
}
?>