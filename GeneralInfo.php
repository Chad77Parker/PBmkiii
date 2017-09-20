<?php
//load global functions, check that user is logged in, and initialise database***********
session_start();
include 'GlobalFunctions.php';

echo '<html>
      <head>
      <title>Parker Bros Earthmoving Pty Ltd</title>';
echo MobileDetect(); /*must be in html header*/
?>
  
</head>
<body>

<img id="topbanner" src="images\pbbanner1.jpg"  border="0">
<div id="topbanner">
Parker Bros Earthmoving Pty Ltd.
</div>


<?php
StandardMenu();
if ($_SESSION['loggedin'] and !checktimeout()){
	LoggedInMenu();
}
?>
//begin page specific code******************************************************
<div id="scroller">
<a href="trailerwiring.php">Trailer Plug wiring diagram</a>



<p class="general">
This site is under construction and is only for testing purposes no information contained herein is of any factual events or persons

</p>
</div>

//end page specific code********************************************************
<div id="background">&nbsp</div>

</body>
</html>