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
$update_password = $_POST['update_password'];

if ($user_admin_access2 == 'Y') {
	// all ok
	} else {
	$user_admin_access2 = 'N';
	}
//echo "user_admin_access2 = $user_admin_access2";
$setpass = "";
if ($update_password == 'Y') {
	$user_password_md5 = md5($user_password);
	$setpass = "user_password = '$user_password_md5',";
	}

$query = "update users set $setpass user_email = '$user_email', user_admin_access = '$user_admin_access2' where user_name = '$user_name2'";
//echo "query=$query";
$dbresult = mysql_query($query, $dbconnect);
$myurl="users.php";
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
	$audit_type = 'm7';
	include 'auditlog.php';
	};
// end audit log entry
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>
