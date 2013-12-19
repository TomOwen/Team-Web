<?php
require '../db/dbaccess.php';
//print_r($_POST);
$user_name=$_POST['user_name'];
$user_name = stripslashes($user_name);
$user_name = mysql_real_escape_string($user_name);

$new_password=$_POST['new_password1'];
$new_password = stripslashes($new_password);
$new_password = mysql_real_escape_string($new_password);

$e = $_POST['e'];
$e = stripslashes($e);
$e = mysql_real_escape_string($e);

$t = $_POST['t'];
$t = stripslashes($t);
$t = mysql_real_escape_string($t);

$teamid = $_POST['tid'];
$teamid = stripslashes($teamid);
$teamid = mysql_real_escape_string($teamid);

$new_password_md5 = md5($new_password);

$query = "select teamid, email from forgotpw where teamid = $teamid and email_md5 = '$e' and requested = $t";
//echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  	$teamid=mysql_result($dbresult,$i,"teamid");
  	$email=mysql_result($dbresult,$i,"email");
  	$i++;
  	}
if ($num == 0) {
	//echo "got zero";
	echo "<center><h2>Sorry that Link has already been used or is invalid.</h2></center></body></html>";
	echo "<br><center><a href=index.php>Back to TEAM Login</center>";
	exit();
}

$query = "select user_email from users where user_teamid = $teamid and user_name = '$user_name' and user_email = '$email'";
//echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  	$user_email=mysql_result($dbresult,$i,"user_email");	
  	$i++;
  	}
if ($num == 0) {
	//echo "got zero";
	echo "<center><h2>Sorry $user_name is not one of your user logins.</h2></center></body></html>";
	echo "<br><center><a href=index.php>Back to TEAM Login</center>";
	exit();
}




$query = "update users set user_password = '$new_password_md5' where user_name = '$user_name'";
//echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);


$query = "delete from forgotpw where teamid = $tid and email_md5 = '$e' and  requested = $t";
//echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);
// remove any entries more than 24 hours 24*60*60
$current_datetime = date('Y-m-d H:i:s');
$datets = strtotime(date("Y-m-d H:i:s", strtotime($current_datetime)));
$datets = $datets - 86400;
$query = "delete from forgotpw where teamid = $tid and  requested < $datets";
//echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);

$user_name2 = $user_name;
$user_teamid = $teamid;
$audit_type = 'r7';
include 'auditlog.php';
$myurl="teampass3.php?m=u3&u=$user_name";
header("Location: ".$myurl);
exit();	
?>
