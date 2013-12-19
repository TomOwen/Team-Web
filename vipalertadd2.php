<?php

require '../db/dbauditaccess.php';
session_start();
if(!session_is_registered(team1)){
header("location:index.php");
}

$storedVars = $_SESSION['team1'];
$user_name = $storedVars[1];
$user_teamid = $storedVars[2];
$user_admin_access = $storedVars[3];
//print_r($_POST);
$patient_id = $_POST['patient_id'];
$minutes_between = $_POST['minutes_between'];
$alert_email = $_POST['alert_email'];


$query = "select patient_id from vipalert where teamid = $user_teamid and patient_id = '$patient_id'";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
if ($num > 0) {
//  indicating study_id already exists
$return_code = 0;
echo "VIP Alert for  $patient_id already exists, Hit back button to re-enter";
exit();
}
// insert new study
$start = date("Y-m-d H:i:s");
$query = "INSERT INTO vipalert (teamid, patient_id, minutes_between, alert_email, last_email ) VALUES ($user_teamid, '$patient_id', $minutes_between, '$alert_email', '$start')";
//echo "query=$query";
$dbresult = mysql_query($query, $dbauditconnect);
$myurl="vipalerts.php";
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>