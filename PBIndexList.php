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


if (checktimeout()){die('<div id= "scroller">You are not authorised to view this page or your session has expired please log in. <a href="ParkerBros.php">Return Home</a></div>');}

?>
<div id="background">&nbsp</div>
<div id="scroller">
<?php
echo '<table><tr><td class="SubHeading3">PBIndex<td class="SubHeading3">Part Number<td 	class="SubHeading3">Vehicle<td class="SubHeading3">Description';
$query='select spareparts.*, concat(vehicles.Make," ",vehicles.Model,", ",vehicles.Name," REG:",vehicles.Registration) as V from spareparts left outer join Vehicles on Vehicle=vehicles.Ind;';
$res=dbquery($query);
while ($row = dbfetchassoc($res)) {
	echo '<tr><td class="general">'.$row['PBIndex'].'
		<td class="general">'.$row['PartNumber'].'
		<td class="general">'.$row['V'].'
		<td class="general">'.$row['Description'];
}
echo '</table>';

?>
</div>



</body>
</html>