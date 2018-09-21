<?php
require_once 'data/dbintegration.php';
if (!isset($_SESSION)) { session_start(); }

//*********************************************************************
// function for checking if user has been logged in for more than 30 mins
function checktimeout(){
$timeout = (($_SESSION['SessionStartTime']+1800)<time() ? true : false);
if ($timeout||!$_SESSION['loggedin']){
  $_SESSION['loggedin']=false;
	return(true);
 }
$_SESSION['SessionStartTime']=time();
return(false);
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
  <input type="button" onclick=Home() value="Home" class="menu1button">&nbsp
	<input type="button" onclick=Login() value="Employee Login" class="menu1button">&nbsp
  <input type="checkbox" onclick=MobileDev() '.$MobileDev.' class="menu1button" id="MobileDevCheck">Mobile Site
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
		window.location.assign("OHS.php")
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
	<input type="button" onclick=OHS() value="OH&S" class="menu2button"><br>
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
	<input type="button" onclick=OHS() value="OH&S" class="menu2button"><br>
	<input type="button" onclick=NewContact() value="New Contact" class="menu2button"><br>
 	<input type="button" onclick=LogOut() value="Log Out" class="menu2button"><br>
	</div>';
       $htmlfunc = $htmlfunc.'
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
function DailyCheckListCheck(){
  $query = 'select count(*) as NumberOfFaults from parkerbros.dailychecklist where Fluids in("FAULT", "LOW HAZARD/ASSESMENT REQUIRED", "DO NOT OPERATE")
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

$res = dbquery($query);
return(dbfetchassoc($res)['NumberOfFaults']);
}

//****************************************************************
function MobileDetect($type='Standard'){

         $DeviceType="PC";
         if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),'iphone')){
              $DeviceType="MOBILE";
         }
         if(isset($_COOKIE['MobileDev'])){
            if($_COOKIE['MobileDev']=='true'){
              $DeviceType="MOBILE";
              }
         }
         if($DeviceType=="MOBILE"){
              $htmlstring= '<link href="data/iPhonestandard.css" type="text/css" rel="stylesheet" />
                           <meta name="viewport" content="width = 480" />';
         }else{
	            $htmlstring= '<link href="data/standard.css" type="text/css" rel="stylesheet" />';
         }
         if($type=='Dual'){
              if($DeviceType=="MOBILE"){
                 $htmlstring= '<link href="data/iPhoneDualScrollWithHeader.css" type="text/css" rel="stylesheet" />
                               <meta name="viewport" content="width = 480" />';
              }else{
	               $htmlstring= '<link href="data/DualScrollWithHeader.css" type="text/css" rel="stylesheet" />';
         }
         }
         return($htmlstring);
}

//****************************************************************
//function for checking if backup required
function RequireBackup(){
  $query = 'select Permission, Ind, LastBackup from parkerbros.employees where Ind="'.$_SESSION['EmployeeInd'].'";';
  $res = dbquery($query);
  $RequireBackup = false;
  while($row=dbfetchassoc($res)){
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

?>