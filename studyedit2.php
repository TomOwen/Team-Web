<?php

require '../db/dbaccess.php';
session_start();
if(!session_is_registered(team1)){
header("location:index.php");
}

$storedVars = $_SESSION['team1'];
$user_name = $storedVars[1];
$user_teamid = $storedVars[2];
$user_admin_access = $storedVars[3];
//print_r($_POST);
$study_id = $_POST['study_id'];
$study_owner = $_POST['study_owner'];
$study_name = $_POST['study_name'];
$study_url = $_POST['study_url'];
$study_percentpr = $_POST['study_percentpr'];
$study_percentpd = $_POST['study_percentpd'];
$study_seats = $_POST['study_seats'];
if ($study_url == "") $study_url = 'n/a';
if ($study_percentpr == "") $study_percentpr = '30';
if ($study_percentpd == "") $study_percentpd = '20';
if ($study_seats == "") $study_seats = '0';

$study_owner = stripslashes($study_owner);
$study_owner = mysql_real_escape_string($study_owner);
$study_name = stripslashes($study_name);
$study_name = mysql_real_escape_string($study_name);
$study_id = str_replace("'","",$study_id);
$study_owner = str_replace("'","",$study_owner);
$study_name = str_replace("'","",$study_name);
$study_url = str_replace("'","",$study_url);

$query = "update studies set study_name = '$study_name', study_owner = '$study_owner', study_percentpd = $study_percentpd, study_percentpr = $study_percentpr, study_url = '$study_url', study_seats = '$study_seats'  where study_teamid = $user_teamid and study_id = '$study_id'";
//echo "query=$query";
$dbresult = mysql_query($query, $dbconnect);
// audit log entry
$q1 = "SELECT ROW_COUNT()as rc";
$dbresult2 = mysql_query($q1, $dbconnect);
$num2=mysql_numrows($dbresult2);
$j = 0;
$rc = 0;
while ($j < $num2) {
  	$rc=mysql_result($dbresult2,$j,"rc");
  	$j++;
}
if ($rc > 0) {
	$audit_type = 'm5';
	include 'auditlog.php';
	};
// end audit log entry
$myurl="studies.php";
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>
