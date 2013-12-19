<?php
require '../db/dbaccess.php';
//print_r($_POST);

// username and password sent from form
$user_name=$_POST['user_name'];
$user_password=$_POST['user_password'];
//echo "$user_name/$user_password";

// To protect MySQL injection (more detail about MySQL injection)
$user_name = stripslashes($user_name);
$user_password = stripslashes($user_password);
$user_name = mysql_real_escape_string($user_name);
$user_password = mysql_real_escape_string($user_password);

$user_password_md5 = md5($user_password);

$query = "select user_teamid,user_admin_access from users where user_name = '$user_name' and user_password = '$user_password_md5'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  $user_teamid=mysql_result($dbresult,$i,"user_teamid");
  $user_admin_access=mysql_result($dbresult,$i,"user_admin_access");
  $i++;
  }

// If result matched $user_name and $User_password, table row must be 1 row
if ($num==0) {
   $myurl="index.php?v=n";
   header("Location: ".$myurl);
   exit();
}



// now get all settings
// web_server is where all web programs are  
$query1 = "select web_server,db_server,imagedoc_server,imagedoc_user,imagedoc_password,doc_type,image_type from settings where teamid = $user_teamid";
$dbresult1 = mysql_query($query1, $dbconnect);
$num=mysql_numrows($dbresult1);              
$i=0;
while ($i < $num) {
  	$web_server=mysql_result($dbresult1,$i,"web_server");
  	$db_server=mysql_result($dbresult1,$i,"db_server");
  	$imagedoc_server=mysql_result($dbresult1,$i,"imagedoc_server");
  	$imagedoc_user=mysql_result($dbresult1,$i,"imagedoc_user");
  	$imagedoc_password=mysql_result($dbresult1,$i,"imagedoc_password");
  	$doc_type=mysql_result($dbresult1,$i,"doc_type");
  	$image_type=mysql_result($dbresult1,$i,"image_type");
  	$i++;
}
// establish session and display main menu with welcome
session_start();
session_destroy();
session_start();
$storedVars = array(1 => $user_name, $user_teamid, $user_admin_access,$imagedoc_server,$doc_type,$image_type,$db_server,$imagedoc_user,$imagedoc_password);
$_SESSION['team1']=$storedVars;
$audit_type = 'g7';
include 'auditlog.php';
$myurl="patients.php";
header("Location: ".$myurl);
?>
