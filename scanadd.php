<?
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

$scan_date = date("Y-m-d", strtotime($scan_date));
//echo "temp1=$scan_date";
//print_r($_POST);
//print_r($_GET);
if ($user_admin_access == 'N') {
	echo "<div align=\"center\">You are not authorized to add scans. You must have admin edit priveledges.</div>";
	exit();
}
// first get the most recent scan_date so we can copy the lesions to the new scan
$query = "select scan_date, max(scan_date) from scan where scan_teamid = $user_teamid and scan_patient_id = '$patient_id'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i=0;
$current_scan = '';
while ($i < $num) {
$current_scan=mysql_result($dbresult,$i,"max(scan_date)");
$i++;
}
// now lets check if the scan_date user entered already exists
$query = "select scan_date from scan where scan_patient_id = '$patient_id' and scan_teamid = $user_teamid and scan_date = '$scan_date'";
$table_id = 'scan';
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$date_exist = 0;
if ($num > 0) {
// return 0 indicating scan_date already exists
$date_exist = 1;
}
// insert new scan only if it doesnt exist
$scan_report_online = 'N';
if ($date_exist == 0) {
   $query = "INSERT INTO scan (scan_teamid,scan_patient_id,scan_date,scan_report_online) VALUES ($user_teamid, '$patient_id', '$scan_date', 'N')";
   $dbresult = mysql_query($query, $dbconnect);
}

// now copy the most current lesions into the new scan
if ($date_exist == 0) {
   $query = "select lesion_number,lesion_size,lesion_comment,lesion_target,lesion_media_type,lesion_media_online,lesion_node from lesion where lesion_teamid = $user_teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$current_scan'";
   $dbresult = mysql_query($query, $dbconnect);
   $num=mysql_numrows($dbresult);
   $i=0;
   while ($i < $num) {
    $lesion_number=mysql_result($dbresult,$i,"lesion_number");
    $lesion_size=mysql_result($dbresult,$i,"lesion_size");
    $lesion_comment=mysql_result($dbresult,$i,"lesion_comment");
    $lesion_target=mysql_result($dbresult,$i,"lesion_target");
    $lesion_media_type=mysql_result($dbresult,$i,"lesion_media_type");
    $lesion_media_online=mysql_result($dbresult,$i,"lesion_media_online");
    $lesion_node=mysql_result($dbresult,$i,"lesion_node");
    $query = "INSERT INTO lesion (lesion_teamid,lesion_patient_id,lesion_scan_date,lesion_number,lesion_size,lesion_comment,lesion_target,lesion_media_type,lesion_media_online,lesion_node) VALUES ($user_teamid,'$patient_id','$scan_date',$lesion_number,$lesion_size,'$lesion_comment','$lesion_target','$lesion_media_type','$lesion_media_online','$lesion_node')";
    //echo "query=$query\n<br>";
    $dbresult2 = mysql_query($query, $dbconnect);
    $i++;
    }
}
// return 0 or 1
if ($date_exist == '1') {
	echo "<div align=\"center\">Scan date $scan_date already exists.</div>";
	exit();
	}
$audit_type = 'a2';
include 'auditlog.php';

$myurl="scans.php?patient_id=$patient_id&patient_name=$patient_name";
header("Location: ".$myurl);	

?>