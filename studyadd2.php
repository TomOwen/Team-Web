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
$study_id = $_POST['study_id'];
$study_owner = $_POST['study_owner'];
$study_name = $_POST['study_name'];
$study_url = $_POST['study_url'];
$study_percentpr = $_POST['study_percentpr'];
$study_percentpd = $_POST['study_percentpd'];
$study_seats = $_POST['study_seats'];
if ($study_url == "") $study_url = 'n/a';
if ($study_percentpr == "") $study_percentpr = '30';
if ($study_percentpd == "") $study_percentpd = '20';
if ($study_seats == "") $study_seats = '0';

$study_owner = stripslashes($study_owner);
$study_owner = mysql_real_escape_string($study_owner);
$study_name = stripslashes($study_name);
$study_name = mysql_real_escape_string($study_name);
$study_id = str_replace("'","",$study_id);
$study_owner = str_replace("'","",$study_owner);
$study_name = str_replace("'","",$study_name);
$study_url = str_replace("'","",$study_url);

$query = "select study_id from studies where study_teamid = $user_teamid and study_id = '$study_id'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
if ($num > 0) {
//  indicating study_id already exists
$return_code = 0;
echo "Study $study_id already exists, Hit back button to re-enter";
exit();
}
// insert new study
$query = "INSERT INTO studies (study_teamid,study_id,study_owner,study_name,study_url,study_percentpr,study_percentpd,study_seats) VALUES ($user_teamid, '$study_id', '$study_owner', '$study_name', '$study_url', $study_percentpr,$study_percentpd,$study_seats)";
//echo "query=$query";
$dbresult = mysql_query($query, $dbconnect);
$audit_type = 'a5';
include 'auditlog.php';
$myurl="studies.php";
//echo "myurl=$myurl";
header("Location: ".$myurl);
?>