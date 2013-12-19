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
$patient_id = $_GET['patient_id'];
$scan_date = $_GET['scan_date'];
$patient_name = $_GET['patient_name'];

if ($user_admin_access == 'N') {
	echo "<div align=\"center\">You are not authorized to delete scans. You must have admin edit priveledges.</div>";
	exit();
}
$query = "delete from scan  where scan_teamid = $user_teamid and scan_patient_id = '$patient_id' and scan_date = '$scan_date'";
$dbresult = mysql_query($query, $dbconnect);
if (!mysql_error()) {
   $return_code = 1;
} else {
   echo "<div align=\"center\">Could not delete this scan, he/she may have already been deleted</div>";
	exit();
}
$query = "delete from lesion  where lesion_teamid = $user_teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$scan_date'";
$dbresult = mysql_query($query, $dbconnect);
if (!mysql_error()) {
   $return_code = 1;
} else {
   echo "<div align=\"center\">Could not delete this patient's lesions, they may have already been deleted</div>";
	exit();
}
$audit_type = 'd2';
include 'auditlog.php';
$myurl="scans.php?patient_id=$patient_id&patient_name=$patient_name";
header("Location: ".$myurl);
?>
</html>
