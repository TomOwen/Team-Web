<?php
$teamtitle = "TEAM Patient Delete";
include 'teamtop1.php';
include 'teamtop2.php';
$patient_id = $_POST['patient_id'];
?>

<?php
if ($user_admin_access == 'N') {
	echo "<div align=\"center\">You are not authorized to delete patients. You must have admin edit priveledges.</div>";
	exit();
}
$query = "delete from patient  where patient_teamid = $user_teamid and patient_id = '$patient_id'";
//echo "$query\n";
$dbresult = mysql_query($query, $dbconnect);
if (!mysql_error()) {
   $return_code = 1;
} else {
   echo "<div align=\"center\">Could not delete this patient, he/she may have already been deleted</div>";
	exit();
}
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
	$audit_type = 'd1';
	include 'auditlog.php';
	};
// end audit log entry

$query = "delete from scan  where scan_teamid = $user_teamid and scan_patient_id = '$patient_id'";
$dbresult = mysql_query($query, $dbconnect);
if (!mysql_error()) {
   $return_code = 1;
} else {
   echo "<div align=\"center\">Could not delete this patient's scans, they may have already been deleted</div>";

}
$query = "delete from lesion  where lesion_teamid = $user_teamid and lesion_patient_id = '$patient_id'";
$dbresult = mysql_query($query, $dbconnect);
if (!mysql_error()) {
   $return_code = 1;
} else {
   echo "<div align=\"center\">Could not delete this patient's lesions, they may have already been deleted</div>";

}
echo "<div align=\"center\"><br><br>Successfully deleted patient ID $patient_id and all his/her scans.</div>";
	
?>
