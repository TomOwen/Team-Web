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
if ($user_admin_access == 'N') {
echo "Sorry your user name is not allowed to add/change/delete. Hit the Back key to return to menu.";
exit();
}
//print_r($_POST);
$patient_id = $_GET['patient_id'];
$query = "delete from vipalert  where teamid = $user_teamid and patient_id = '$patient_id'";
//echo "query=$query";
$dbresult = mysql_query($query, $dbauditconnect);
$myurl="vipalerts.php";
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>