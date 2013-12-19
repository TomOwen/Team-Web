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

$company_name=$_POST['company_name'];
$db_server=$_POST['db_server'];
$db_user=$_POST['db_user'];
$db_password=$_POST['db_password'];
$imagedoc_server=$_POST['imagedoc_server'];
$imagedoc_user=$_POST['imagedoc_user'];
$imagedoc_password=$_POST['imagedoc_password'];
$image_type=$_POST['image_type'];
$doc_type=$_POST['doc_type'];
$web_server=$_POST['web_server'];

$query = "update settings set company_name = '$company_name', db_server = '$db_server', db_user = '$db_user', db_password = '$db_password', imagedoc_server = '$imagedoc_server', imagedoc_user = '$imagedoc_user', imagedoc_password = '$imagedoc_password', image_type = '$image_type', doc_type = '$doc_type' , web_server = '$web_server' where teamid = $user_teamid";
//echo "query=$query";
$dbresult = mysql_query($query, $dbconnect);
$myurl="settingsedit.php";
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
	$audit_type = 'm6';
	include 'auditlog.php';
	};
// end audit log entry
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>
