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
$imagedoc_server = $storedVars[4];

$imagedoc_user = $storedVars[8];
$imagedoc_password = $storedVars[9];

$fn=$_GET['fn'];
//print_r($_POST);

$audit_type = 'vf8';
include 'auditlog.php';

$myurl="http://$imagedoc_server/$fn";
header("Location: ".$myurl);
?>