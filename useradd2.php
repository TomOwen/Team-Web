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
$user_name2=$_POST['user_name2'];
$user_password=$_POST['user_password'];
$user_email=$_POST['user_email'];
$user_admin_access2=$_POST['user_admin_access'];
$user_password_md5 = md5($user_password);
if ($user_admin_access2 == 'Y') {
	// all ok
	} else {
	$user_admin_access2 = 'N';
	}
$query = "select user_name from users where user_name = '$user_name2'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
if ($num > 0) {
//  indicating user already exists
$return_code = 0;
echo "Login $user_name2 already exists, Hit back button to re-enter";
exit();
}
// insert new user
$query = "INSERT INTO users (user_teamid,user_name,user_password,user_email,user_admin_access) VALUES ($user_teamid, '$user_name2', '$user_password_md5', '$user_email', '$user_admin_access2')";
$dbresult = mysql_query($query, $dbconnect);

$audit_type = 'a7';
include 'auditlog.php';
$myurl="users.php";
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>