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


$query = "update vipalert set minutes_between = $minutes_between, alert_email = '$alert_email' where teamid = $user_teamid and patient_id = '$patient_id'";
//echo "query=$query";
$dbresult = mysql_query($query, $dbauditconnect);

$myurl="vipalerts.php";
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>
