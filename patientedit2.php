<?php
//print_r($_POST);
require '../db/dbaccess.php';
session_start();
if(!session_is_registered(team1)){
header("location:index.php");
}
$storedVars = $_SESSION['team1'];
$user_name = $storedVars[1];
$user_teamid = $storedVars[2];
$user_admin_access = $storedVars[3];
$imagedoc_server = $storedVars[4];
$doc_type = $storedVars[5];
$image_type = $storedVars[6];
$db_server = $storedVars[7];
$patient_id=$_POST['patient_id'];
$patient_name=$_POST['patient_name'];
$patient_study_id=$_POST['study_id'];
$patient_doctor=$_POST['doctor'];

$query = "select patient_id from patient where patient_teamid = $user_teamid and patient_id = '$patient_id'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);

if ($num == 0) {
// return 0 indicating patient_id already exists
	echo "<div align=\"center\">Could not find $patient_id , may have been deleted</div>";
	exit ();
}

$query = "update patient set patient_name = '$patient_name', patient_study_id = '$patient_study_id', patient_doctor = '$patient_doctor' where patient_teamid = $user_teamid and patient_id = '$patient_id'";
//echo "$query";
$dbresult = mysql_query($query, $dbconnect);
if (!mysql_error()) {
   $up = "ok";
} else {
   $up = "";
}
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
	$audit_type = 'm1';
	include 'auditlog.php';
	};
$myurl="patientedit.php?patient_id=$patient_id&up=$up";
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>