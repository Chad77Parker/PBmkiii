<?php
require_once 'GlobalFunctions.php';
require_once 'data/dbintegration.php';
$_SESSION['loggedin']=false;
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
?>
<div id="background">&nbsp</div>
<div id="scroller">
<h3>You have successfully logged out.<br>Thank you.<br></h3>
<p class="general">

</div>
</body>
</html>