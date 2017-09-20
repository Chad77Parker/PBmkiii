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
<p class="general">
<table border="1" onchange=UpdateVolume()>
<tr>
<td colspan="4" class="SubHeading1">
Dam Volume Calculator
</td>
</tr>
<tr>
<td  colspan="4" class="general">
Dam Volume is calculated by first working out the surface area, then the depth, then multiplying by 0.4 to allow for the batters of dam.
</td>
</tr>
<tr>
<td  colspan="4" class="SubHeading3">
Surface area calculators
</td>
</tr>
<tr>
<td class="SubHeading2">
Circular Dam
</td>
<td  colspan="3" class="general">
<img src="images/circledam.gif">
</td>
</tr>
<tr>
<td  colspan="3" class="general">
<input type="text" id="Diameter" value="Diameter" onchange="UpdateVolume()">
</td>
<td class="general">
<input type="text" id="CircleSA" value="Surface Area">
</td>
</tr>
<tr>
<td class="SubHeading2">
Rectangle Dam
</td>
<td  colspan="3" class="general">
<img src="images/rectangledam.gif">
</td>
</tr>
<tr>
<td class="general">
<input type="text" id="RectLength" value="Length">
</td>
<td  colspan="2" class="general">
<input type="text" id="RectWidth" value="Width">
</td><td class="general">
<input type="text" id="RectSA" value="Surface Area">
</td>
</tr>
<tr>
<td class="SubHeading2">
Triangle Dam
</td>
<td  colspan="3" class="general">
<img src="images/triangledam.gif">
</td>
</tr>
<tr>
<td class="general">
<input type="text" id="TriLength" value="Length">
</td>
<td  colspan="2" class="general">
<input type="text" id="TriWidth" value="Width">
</td><td class="general">
<input type="text" id="TriSA" value="Surface Area">
</td>
</tr>
<tr>
<td class="SubHeading2">
Depth
</td>
<td  colspan="3" class="general">
<input type="text" id="Depth" value="Depth">
</td>
</tr>
<tr>
<td class="SubHeading1">
Volume
</td>
<td  colspan="3" class="SubHeading1">
<input type="text" id="Volume" value="Volume" >
</td>
</tr>
</table>

</p>
</div>
<script>
function UpdateVolume(){
    if(document.getElementById("Diameter").value != "Diameter"{
         document.getElementById("CircleSA").value = pow((document.getElementById("Diameter").value/2),2)*Math.PI
    }
}
</script>
//end page specific code********************************************************
<div id="background">&nbsp</div>

</body>
</html>