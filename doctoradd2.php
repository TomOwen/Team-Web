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

if ($user_admin_access == 'N') {
echo "Sorry your user name is not allowed to add/change/delete. Hit the Back key to return to menu.";
exit();
}

if ($doctor_info == "") $doctor_info = 'n/a';

$query = "select doctor_name from doctor where doctor_teamid = $user_teamid and doctor_name = '$doctor_name'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
if ($num > 0) {
//  indicating doctor already exists
$return_code = 0;
echo "Doctor $doctor_name already exists, Hit back button to re-enter";
exit();
}
// insert new study
$query = "INSERT INTO doctor (doctor_teamid,doctor_name,doctor_info) VALUES ($user_teamid, '$doctor_name', '$doctor_info')";
$dbresult = mysql_query($query, $dbconnect);
// audit log entry

	$audit_type = 'a4';
	include 'auditlog.php';

// end audit log entry

$myurl="doctors.php";
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>