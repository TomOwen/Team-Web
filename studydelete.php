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
$study_id = $_GET['study_id'];
$query = "delete from studies  where study_teamid = $user_teamid and study_id = '$study_id'";
//echo "query=$query";
$dbresult = mysql_query($query, $dbconnect);
$myurl="studies.php";
//echo "myurl=$myurl";
$audit_type = 'd5';
include 'auditlog.php';
header("Location: ".$myurl);
?>