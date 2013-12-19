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
$doctor_name = $_POST['doctor_name'];
$doctor_info = $_POST['doctor_info'];

if ($doctor_info == "") $doctor_info = 'n/a';
$query = "update doctor set doctor_info = '$doctor_info' where doctor_teamid = $user_teamid and doctor_name = '$doctor_name'";
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
	$audit_type = 'm4';
	include 'auditlog.php';
	};
// end audit log entry
$myurl="doctors.php";
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>
