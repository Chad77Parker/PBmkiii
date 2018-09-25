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

<p class="general">
<?php
$query='INSERT INTO parkerbros.SpareParts (PBIndex, PartNumber, Description, Vehicle) VALUES ("'.$_POST["PBIndex"].'", "'.$_POST["PartNumber"].'", "'.$_POST["Description"].'", "'.$_POST["Vehicle"].'");';

$result = dbquery($query);
	echo '<h3>New Spare Part successfully saved !</h3><br>';
?>
</p>
</div>
</body>
</html>