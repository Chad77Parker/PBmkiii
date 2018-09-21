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
<?php

//query database for field names
$res = dbquery('select * from '.$_GET['table']);
echo '<form action="commit.php?table='.$_GET['table'].'&returnaddress='.$_GET['returnaddress'].'" method="post">';

//build table
echo '<table border="2">';

echo addrecord($_GET['table']);
echo '<tr><td colspan="2" style="text-align: center;"><input type="submit" name="submit" value="Submit">';
echo "</table></form>";


?>

</div>
</body>
</html>