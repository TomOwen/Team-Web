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
$imagedoc_server = $storedVars[4];
$doc_type = $storedVars[5];
$image_type = $storedVars[6];
$patient_id = $_POST['patient_id'];
$scan_date = $_POST['scan_date'];
$patient_name = $_POST['patient_name'];
$scan_report_online = $_POST['scan_report_online'];
$scanonline = $_POST['scanonline'];

$temp = $_POST['lesion_number'];
$num = sizeof($temp);
//echo "num = $num<br>";
$num_updates = $num - 1;
//print_r($_POST);

$i=0;
while ($i < $num_updates) {
    $lesion_number = $_POST['lesion_number'][$i];
    $lesion_size = $_POST['lesion_size'][$i];  
    $lesion_media_type = $_POST['lesion_media_type'][$i];
    $lesion_comment = $_POST['lesion_comment'][$i];
    
    $lesion_target = $_POST['checks'][$i*4];
    $lesion_node = $_POST['checks'][$i*4 + 1];
    $lesion_media_online = $_POST['checks'][$i*4 + 2];
    $ldelete = $_POST['checks'][$i*4 + 3];
    
     if ($lesion_media_type == '') $lesion_media_type = $image_type;
     if ($lesion_comment == '') $lesion_comment = 'n/a';
     
     
     if ($ldelete == 'Y') {
        $query = "delete from lesion  where lesion_teamid = $user_teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$scan_date' and lesion_number = $lesion_number";
        //echo "\nDelete Lesion $i = $lesion_target $lesion_node $lesion_media_online $ldelete\n";
	$dbresult = mysql_query($query, $dbconnect);
	$audit_type = 'd3';
	include 'auditlog.php';
     	//echo "query=$query<br>Deleting Lesion#$i - Lesion $lesion_number, $target, $node, $online, $ldelete, $lesion_size, $lesion_media_type, $lesion_comment<br>";
     }
     if ($ldelete == 'N') {
       // update
       //echo "\nUpdate Lesion $i = $lesion_target $lesion_node $lesion_media_online $ldelete\n";
       $query = "update lesion set lesion_size = $lesion_size, lesion_comment = '$lesion_comment', lesion_target = '$lesion_target', lesion_media_type = '$lesion_media_type', lesion_media_online = '$lesion_media_online', lesion_node = '$lesion_node' where lesion_teamid = $user_teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$scan_date' and lesion_number = $lesion_number";
	$dbresult = mysql_query($query, $dbconnect);
	
	$q1 = "SELECT ROW_COUNT()as rc";
	$dbresult2 = mysql_query($q1, $dbconnect);
	$num2=mysql_numrows($dbresult2);
	$j=0;
	$rc=0;
	while ($j < $num2) {
  		$rc=mysql_result($dbresult2,$j,"rc");
  		$j++;
	}
	if ($rc > 0) {
		$audit_type = 'm3';
		include 'auditlog.php';
	};
	//echo "\nback from auditlog for $i";
        //echo "\nquery=$query<br>Updating Lesion#$i - Lesion $lesion_number, $target, $node, $online, $ldelete, $lesion_size, $lesion_media_type, $lesion_comment<br>";
     }
    $i++;
}
$yesno = 'N';
if ($scanonline == 'Y') $yesno = 'Y';
//echo "scanonlin=$scanonline";
$scan_report_online = $yesno;
$query = "update scan set scan_report_online = '$yesno' where scan_teamid = $user_teamid and scan_patient_id = '$patient_id' and scan_date = '$scan_date'";
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
	$audit_type = 'm2';
	include 'auditlog.php';
	};
// end audit log entry
	
$mynum = $_POST['lesion_number'][$num_updates];
//echo "mynum = $mynum";
if ($mynum > 0) {
	//echo "<br>perform insert on $mynum";
	$lesion_number = $_POST['lesion_number'][$num_updates];
    	$lesion_size = $_POST['lesion_size'][$num_updates];
    	$lesion_media_type = $_POST['lesion_media_type'][$num_updates];
    	$lesion_comment = $_POST['lesion_comment'][$num_updates];
    	
	$lesion_target = $_POST['checks'][$num_updates*4];
    	$lesion_node = $_POST['checks'][$num_updates*4 + 1];
    	$lesion_media_online = $_POST['checks'][$num_updates*4 + 2];

    	
    	if ($lesion_media_type == '') $lesion_media_type = $image_type;
     	if ($lesion_comment == '') $lesion_comment = 'n/a';
	//echo "\nAdding Lesion $i = $lesion_target $lesion_node $lesion_media_online $ldelete\n";
    	
	$query = "INSERT INTO lesion (lesion_teamid,lesion_patient_id,lesion_scan_date,lesion_number,lesion_size,lesion_comment,lesion_target,lesion_media_type,lesion_media_online,lesion_node) VALUES ($user_teamid,'$patient_id','$scan_date',$lesion_number,$lesion_size,'$lesion_comment','$lesion_target','$lesion_media_type','$lesion_media_online','$lesion_node')";
	//echo "query = $query";	
$dbresult = mysql_query($query, $dbconnect);

}
if ($mynum > 0) {
	$audit_type = 'a3';
	include 'auditlog.php';
}
//echo "b4 scanonline=$scanonline and scan_report_online=$scan_report_online";
if ($scanonline == '') $scanonline = 'N';
if ($scanonline == 'Y' or $scanonline == 'N') $scan_report_online = $scanonline;
//echo "after scanonline=$scanonline and scan_report_online=$scan_report_online";
//print_r($_POST);
$myurl="lesions.php?patient_id=$patient_id&scan_date=$scan_date&patient_name=$patient_name&scan_report_online=$scan_report_online&updated=Y";
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>
