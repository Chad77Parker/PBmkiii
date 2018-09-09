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
<script type="text/javascript" language="javascript"> 
function ClientDate(){
	window.location.assign("SelectInvoiceByDate&Client.php");
}
function OSJobs(){
	window.location.assign("SelectInvoiceByOutstandingJobs.php");
}
function Previous(){
	window.location.assign("SelectPreviousInvoice.php");
}
function PreviousDetailed(){
	window.location.assign("SelectPreviousInvoiceDetailed.php");
}
function OpenJobs(){
	window.location.assign("SelectInvoiceByOpenJobs.php");
}
</script>  

<div id="scroller">
<p class="general">
<h3>Select Invoice Type</h3><br>
<input type="button" value="Create New Invoice by Outstanding Jobs" onclick=OSJobs() class="menu2button"><br>
<input type="button" value="Create New Invoice by Client and Date" onclick=ClientDate() class="menu2button"><br>
<input type="button" value="Create New Invoice by Open Jobs" onclick=OpenJobs() class="menu2button"><br>
<hr>
<input type="button" value="View Previous Invoice by Client and Date" onclick=Previous() class="menu2button"><br>
<input type="button" value="View Previous Invoice by Client and Date with Details" onclick=PreviousDetailed() class="menu2button"><br>
</p>
</div>
</body>
</html>