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
if ($user_admin_access == 'N') {
echo "Sorry your user name is not allowed to add/change/delete. Hit the Back key to return to menu.";
exit();
}
//print_r($_POST);
$doctor_name = $_GET['doctor_name'];
$query = "delete from doctor  where doctor_teamid = $user_teamid and doctor_name = '$doctor_name'";
//echo "query=$query";
$dbresult = mysql_query($query, $dbconnect);
$myurl="doctors.php";
$audit_type = 'd4';
include 'auditlog.php';
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>