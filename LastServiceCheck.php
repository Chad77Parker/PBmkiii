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
//***begin page specific code***//
<div id="scroller">
<?php
$query = 'select vehicles.Ind, Name, Make, Model, Registration, E.LastServ as LastServDate, datediff(curdate(),E.LastServ) as DaysSinceService, E.HoursSinceService, E.ServUnitsAmount as Amount, E.ServUnitsType as Units
from parkerbros.vehicles left outer join
	(select VInd, LastServ, ServUnitsAmount, ServUnitsType,  sum(C.HoursForVehicle) as HoursSinceService
	from
		(select A.*, B.HoursForVehicle, B.OperationDate
		from
			(select x.Vehicle as VInd, max(x.servdate) as LastServ, max(x.ServUnitsAmount) as ServUnitsAmount, ServUnitsType
		from
			(select Vehicle, ServDate, ServUnitsAmount, ServUnitsType
			from parkerbros.servicehistory
			where  schedueledservice="YES" ) x
		group by vehicle) A
	left outer join vehiclehours B
	on A.VInd = B.Vehicle) C
	where C.OperationDate > LastServ
 group by VInd
 union
 select VInd, LastServ, ServUnitsAmount, ServUnitsType, HoursForVehicle
 from
 (select VInd, LastServ, ServUnitsAmount, ServUnitsType, max(C.OperationDate) as LastOperationDate, if(sum(HoursForVehicle),0,null) as HoursForVehicle
 		from
 			(select A.*, B.HoursForVehicle, B.OperationDate
 		from
 				(select x.Vehicle as VInd, max(x.servdate) as LastServ, max(x.ServUnitsAmount) as ServUnitsAmount, ServUnitsType
       	from
 			  	(select Vehicle, ServDate, ServUnitsAmount, ServUnitsType
 			    from parkerbros.servicehistory
 			    where  schedueledservice="YES" ) x
	      group by vehicle) A
    left outer join vehiclehours B
	  on A.VInd = B.Vehicle) C
 group by VInd) D
 where LastOperationDate is null or LastOperationDate < LastServ
 ) E
on vehicles.Ind = E.VInd
where vehicles.StatusSelect = "ACTIVE" or vehicles.StatusSelect = "REPAIR"
order by HoursSinceService is null desc, HoursSinceService desc;';

echo buildtable($query);





?>
<p class="general">


</p>
</div>

//***end page specific code***//


</body>
</html>