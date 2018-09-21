<?php
/*** file to handle database integration ***/

//Global Variables
$link;
define('DBHOST', "localhost");
define('DBUSER', "root");
define('DBPASS', "keogh");

function dbconnect(){
global $link;

  //connect to database
$link = @mysql_connect(DBHOST,DBUSER,DBPASS);
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

  return($res);
}

function dbfetchassoc($res){
  $row = mysql_fetch_assoc($res);
  return($row);
}

function dbnumrows($res){
  return(mysql_num_rows($res));
}

function dbnumfields($res){
  return(mysql_num_fields($res));
}

function dbfieldname($res, $i){
  return(mysql_field_name($res, $i));
}

function dbfieldtype($res, $i){
  return(mysql_field_type($res,$i));
}

function dbfetchrow($res){
  return(mysql_fetch_row($res));
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

function backupdatabase(){
 //path to mysql dump utility
$dump_path = "C:\\xampp\\mysql\\bin\\";

// location to store backups
$save_path = "E:\\Documents\\ParkerBros\\Backups";

//connect to database
dbconnect();

// format dir name
$today = '\\'.date("m-d-Y");

//check if directory exists otherwise create it
if ( !file_exists ($save_path.$today) ){
    mkdir( $save_path.$today );
}

// do the dump
$error = "";
    $do = $dump_path . "mysqldump -h".DBHOST." -u".DBUSER." -p".DBPASS." --opt parkerbros > " . $save_path . $today . "\\parkerbros.sql";
    system($do, $retv);
    if($retv != 0){$error = $error.'<br>Error with '.$do;}
dbclose();
return($error);
}

//function for building table to add one record
function addrecord($table){
$res = dbquery('select * from '.$table);

$htmlstring = "";
$i = 0;
while($i < dbnumfields($res)) {
 switch (dbfieldname($res, $i)){
  case 'Ind':
	break;
  case (strpos(dbfieldname($res, $i), 'Select')>1):
	$htmlstring=$htmlstring. '<tr><td class="general">'.dbfieldname($res, $i).'<td>' ;
	$htmlstring=$htmlstring. '<select name="'.dbfieldname($res, $i).'">';

	$res2=dbquery('select * from '.dbfieldname($res, $i).'table');
	while ($row = dbfetchassoc($res2)) {
		$htmlstring=$htmlstring. '<option value="'.$row['VALUE'].'">'.$row['LABEL'].'</option>';
	}
 	$htmlstring=$htmlstring. '</select>';
	break;
  default:
   $htmlstring=$htmlstring. '<tr><td class="general">'.dbfieldname($res, $i) ;
   $now = getdate();

   switch (dbfieldtype($res, $i)){
    case 'datetime':
    $htmlstring=$htmlstring. '<td><select name="'.dbfieldname($res, $i).'day">';
    $x=1;
    while($x<32){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['mday']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
	$htmlstring=$htmlstring. '>';
        if ($x<10){
	$htmlstring=$htmlstring. '0';
	}
	$htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }
    $htmlstring=$htmlstring. '</select>/<select name="'.dbfieldname($res, $i).'month">';
    $x=1;
    while($x<13){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['mon']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
	$htmlstring=$htmlstring. '>';
        if ($x<10){
	$htmlstring=$htmlstring. '0';
	}
	$htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }
    $htmlstring=$htmlstring. '</select>/<select name="'.dbfieldname($res, $i).'year">';
    $x=1930;
    while($x<2020){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['year']){
            $htmlstring=$htmlstring. ' selected="selected">'.$x.'</option>';
        }
        else{
            $htmlstring=$htmlstring. '>'.$x.'</option>';
        }
    $x++;
    }
    $htmlstring=$htmlstring. '</select>  <select name="'.dbfieldname($res, $i).'hours">';
    $x=00;
    while($x<24){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['hours']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
        $htmlstring=$htmlstring. '>';
        if ($x<10){
            $htmlstring=$htmlstring. '0';
        }
        $htmlstring=$htmlstring. $x.'</option>';

    $x++;
    }$htmlstring=$htmlstring. '</select>:<select name="'.dbfieldname($res, $i).'minutes">';
    $x=00;
    while($x<60){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['minutes']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
        $htmlstring=$htmlstring. '>';
        if ($x<10){
            $htmlstring=$htmlstring. '0';
        }
        $htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }$htmlstring=$htmlstring. '</select>:<select name="'.dbfieldname($res, $i).'seconds">';
    $x=00;
    while($x<60){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['seconds']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
        $htmlstring=$htmlstring. '>';
        if ($x<10){
            $htmlstring=$htmlstring. '0';
        }
        $htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }
    $htmlstring=$htmlstring. '</select>';

    break;
    case 'date':
    $htmlstring=$htmlstring. '<td><select name="'.dbfieldname($res, $i).'day">';
    $x=1;
    while($x<32){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['mday']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
	$htmlstring=$htmlstring. '>';
        if ($x<10){
	$htmlstring=$htmlstring. '0';
	}
	$htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }
    $htmlstring=$htmlstring. '</select>/<select name="'.dbfieldname($res, $i).'month">';
    $x=1;
    while($x<13){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['mon']){
            $htmlstring=$htmlstring. ' selected="selected"';
        }
	$htmlstring=$htmlstring. '>';
        if ($x<10){
	$htmlstring=$htmlstring. '0';
	}
	$htmlstring=$htmlstring. $x.'</option>';
    $x++;
    }
    $htmlstring=$htmlstring. '</select>/<select name="'.dbfieldname($res, $i).'year">';
    $x=1930;
    while($x<2020){
        $htmlstring=$htmlstring. '<option';
        if ($x==$now['year']){
            $htmlstring=$htmlstring. ' selected="selected">'.$x.'</option>';
        }
        else{
            $htmlstring=$htmlstring. '>'.$x.'</option>';
        }
    $x++;
    }
    $htmlstring=$htmlstring. '</select>';
    break;
    default:
    $htmlstring=$htmlstring. '<td><input type="text" name="'.dbfieldname($res, $i).'">';
   }
  }
$i++;
}




return($htmlstring);
}
?>