<?php

require '../db/dbaccess.php';
session_start();
if(!session_is_registered(team1)){
//header("location:index.php");
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

$temp = $_POST['checks'];
$num = sizeof($temp);
echo "num = $num<br>";

print_r($_POST);
?>