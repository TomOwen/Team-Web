<?php
require '../db/dbaccess.php';
//print_r($_POST);
$user_name=$_POST['user_name'];
$user_name = stripslashes($user_name);
$user_name = mysql_real_escape_string($user_name);

$user_password=$_POST['user_password'];
$user_password = stripslashes($user_password);
$user_password = mysql_real_escape_string($user_password);

$new_password=$_POST['new_password1'];
$new_password = stripslashes($new_password);
$new_password = mysql_real_escape_string($new_password);

$p=$_POST['p']; // 1=change password 2=forgot password so send URL if a valid TEAM email
$p = stripslashes($p);
$p = mysql_real_escape_string($p);

$user_email = $_POST['user_email'];
$user_email = stripslashes($user_email);
$user_email = mysql_real_escape_string($user_email);


if ($p == '1') {
	// 1 check user_name and md5 password to see if match, then update password and pass back message
	$user_password_md5 = md5($user_password);
	$new_password_md5 = md5($new_password);
	$query = "select user_teamid from users where user_name = '$user_name' and user_password = '$user_password_md5'";
	$dbresult = mysql_query($query, $dbconnect);
	$num=mysql_numrows($dbresult);              
	$i=0;
	while ($i < $num) {
  		$user_teamid=mysql_result($dbresult,$i,"user_teamid");
  		$i++;
  	}
  	if ($num==0) {
   		$myurl="teampass1.php?m=nf1&u=$user_name";
   		header("Location: ".$myurl);
   		exit();
   	}
   	$query = "update users set user_password = '$new_password_md5' where user_name = '$user_name'";
	$dbresult = mysql_query($query, $dbconnect);
	$user_name2 = $user_name;
	$audit_type = 'p7';
	include 'auditlog.php';
	$myurl="teampass1.php?m=u1&u=$user_name";
   	header("Location: ".$myurl);
   	exit();	
}
if ($p != '2') {
	$myurl="teampass1.php?m=x&u=$user_name";
	header("Location: ".$myurl);
	exit();
}
// here to check if email is a valid TEAM email
$query = "select user_teamid, user_name from users where user_email = '$user_email'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  	$user_teamid=mysql_result($dbresult,$i,"user_teamid");
  	$i++;
  	}
if ($num == 0) {
	$myurl="teampass1.php?m=nf2&e=$user_email";
	header("Location: ".$myurl);
	exit();
}
// now create a new entry in the forgotpw table with email,md5_email,datetime
$current_datetime = date('Y-m-d H:i:s');
$datets = strtotime(date("Y-m-d H:i:s", strtotime($current_datetime)));
$user_email_md5 = md5($user_email);
$query = "INSERT INTO forgotpw (teamid, email, email_md5, requested ) VALUES ($user_teamid, '$user_email', '$user_email_md5', $datets)";
//echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);
$query = "select web_server from settings where teamid = $user_teamid";
//echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  	$web_server=mysql_result($dbresult,$i,"web_server");
  	$i++;
  	}
$myurl = "http://$web_server/teampass3.php?e=$user_email_md5&t=$datets&tid=$user_teamid";
//echo "myurl=$myurl\n";
$to = $user_email;
$body = "A request was made to retrieve/reset a user/password in the TEAM system.\n\nYour email address $user_email was entered.\n\nUse the following link to reset your TEAM password.\n\n$myurl\n\nThis link can only be used once and will only be valid today.\n\nPlease disregard this message if you did not request a password reset in TEAM.\n\nContact your TEAM Administrator for additional help.";
$subject = "TEAM - Password Reset Requested";
$headers = 'From: Admin@team.com' . "\r\n" . 'Reply-To: webmaster@team.com' . "\r\n" .'X-Mailer: PHP/' . phpversion();
mail($to, $subject, $body, $headers);
$myurl="teampass1.php?m=s2&e=$user_email";
header("Location: ".$myurl);
exit();
?>
