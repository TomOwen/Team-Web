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
//print_r($_POST);
$user_name2 = $_GET['user_name2'];
$query = "delete from users  where user_teamid = $user_teamid and user_name = '$user_name2'";
//echo "query=$query";
$dbresult = mysql_query($query, $dbconnect);
$myurl="users.php";
//echo "myurl=$myurl";
$audit_type = 'd7';
include 'auditlog.php';
header("Location: ".$myurl);
?>