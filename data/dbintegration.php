<?php
/*** file to handle database integration ***/

//Global Variables
$link;

function dbconnect(){
global $link;
  //connect to database
$link = @mysql_connect('localhost','root','keogh');
if (!$link) {
    die('Could not connect to MySQL server: ' . mysql_error());
}
$dbname = 'parkerbros';
$db_selected = mysql_select_db($dbname, $link);
if (!$db_selected) {
    die("Could not set $dbname: " . mysql_error());
}
}

function dbclose(){
  global $link;
   //close connection to database
   mysql_close($link);
}

function dbquery($sql){
  global $link;
  dbconnect();
  $res = mysql_query($sql,$link);
  if(!$res){
    die(mysql_error());
  }
  dbclose();
  return($res);
}

function dbfetchassoc($res){
  $row = mysql_fetch_assoc($res);
  return($row);
}


function buildtable($sql){
$res = dbquery($sql);

$htmlstring = "<table><tr>";
$i = 0;
while($i < mysql_num_fields($res)) {
   $htmlstring=$htmlstring. '<td class="SubHeading2">'.mysql_field_name($res, $i).'</td>' ;
   $i++;
}

while ($row =  mysql_fetch_row($res)){
      $r = 0;
      $htmlstring = $htmlstring.'<tr>';
      while($r < mysql_num_fields($res)){
      $htmlstring = $htmlstring.'<td class="general">'.$row[$r].'</td>';
      $r++;
    }
    $htmlstring = $htmlstring.'</tr>';
}


$htmlstring = $htmlstring.'</table>';
return($htmlstring);
}
?>