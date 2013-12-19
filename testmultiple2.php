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
$patient_id = $_POST['patient_id'];
$scan_date = $_POST['scan_date'];
$patient_name = $_POST['patient_name'];
$scan_report_online = $_POST['scan_report_online'];

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
    
     if ($lesion_media_type == '') $lesion_media_type = 'jpg';
     if ($lesion_comment == '') $lesion_comment = 'n/a';
     
     
     if ($ldelete == 'Y') {
        $query = "delete from lesion  where lesion_teamid = $user_teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$scan_date' and lesion_number = $lesion_number";
        //echo "\nDelete Lesion $i = $lesion_target $lesion_node $lesion_media_online $ldelete\n";
	$dbresult = mysql_query($query, $dbconnect);
     	//echo "query=$query<br>Deleting Lesion#$i - Lesion $lesion_number, $target, $node, $online, $ldelete, $lesion_size, $lesion_media_type, $lesion_comment<br>";
     }
     if ($ldelete == 'N') {
       // update
       //echo "\nUpadate Lesion $i = $lesion_target $lesion_node $lesion_media_online $ldelete\n";
       $query = "update lesion set lesion_size = $lesion_size, lesion_comment = '$lesion_comment', lesion_target = '$lesion_target', lesion_media_type = '$lesion_media_type', lesion_media_online = '$lesion_media_online', lesion_node = '$lesion_node' where lesion_teamid = $user_teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$scan_date' and lesion_number = $lesion_number";
$dbresult = mysql_query($query, $dbconnect);
     //echo "query=$query<br>Updating Lesion#$i - Lesion $lesion_number, $target, $node, $online, $ldelete, $lesion_size, $lesion_media_type, $lesion_comment<br>";
     }
    $i++;
}
$mynum = $_POST['lesion_number'][$num_updates];
if ($mynum > 0) {
	//echo "<br>perform insert on $mynum";
	$lesion_number = $_POST['lesion_number'][$num_updates];
    	$lesion_size = $_POST['lesion_size'][$num_updates];
    	$lesion_media_type = $_POST['lesion_media_type'][$num_updates];
    	$lesion_comment = $_POST['lesion_comment'][$num_updates];
    	
	$lesion_target = $_POST['checks'][$num_updates*4];
    	$lesion_node = $_POST['checks'][$num_updates*4 + 1];
    	$lesion_media_online = $_POST['checks'][$num_updates*4 + 2];

    	
    	if ($lesion_media_type == '') $lesion_media_type = 'jpg';
     	if ($lesion_comment == '') $lesion_comment = 'n/a';
	//echo "\nAdding Lesion $i = $lesion_target $lesion_node $lesion_media_online $ldelete\n";
    	
	$query = "INSERT INTO lesion (lesion_teamid,lesion_patient_id,lesion_scan_date,lesion_number,lesion_size,lesion_comment,lesion_target,lesion_media_type,lesion_media_online,lesion_node) VALUES ($user_teamid,'$patient_id','$scan_date',$lesion_number,$lesion_size,'$lesion_comment','$lesion_target','$lesion_media_type','$lesion_media_online','$lesion_node')";
$dbresult = mysql_query($query, $dbconnect);
}

$myurl="lesions.php?patient_id=$patient_id&scan_date=$scan_date&patient_name=$patient_name&scan_report_online=$scan_report_online&updated=Y";
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>
