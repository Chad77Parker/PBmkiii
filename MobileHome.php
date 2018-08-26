<?php
require_once 'GlobalFunctions.php';
require_once 'data/dbintegration.php';
if($_COOKIE['MobileDev']=='true'){
setcookie('MobileDev', 'false', time()+432000);
$_COOKIE['MobileDev']='fasle';
}else{
 setcookie('MobileDev', 'true', time()+432000);
 $_COOKIE['MobileDev']='true';
}


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
if (!checktimeout()){LoggedInMenu();}
?>

<div id="background">&nbsp</div>
<div id="scroller">
<p class="general">
This site is under construction and is only for testing purposes no information contained herein is of any factual events or persons

</p>
</div>
</body>
</html>