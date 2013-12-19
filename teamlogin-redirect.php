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

$query = "select user_teamid,user_admin_access from users where user_name = '$user_name' and user_password = '$user_password'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  $user_teamid=mysql_result($dbresult,$i,"user_teamid");
  $user_admin_access=mysql_result($dbresult,$i,"user_admin_access");
  $i++;
  }

// If result matched $user_name and $User_password, table row must be 1 row

if($num==1){

	// now get all settings
 	// web_server is where all web programs are 
 	// 
	$query1 = "select web_server,imagedoc_server from settings where teamid = $user_teamid";
	$dbresult1 = mysql_query($query1, $dbconnect);
	$num=mysql_numrows($dbresult1);              
	$i=0;
	while ($i < $num) {
  		$web_server=mysql_result($dbresult1,$i,"web_server");
  		$imagedoc_server=mysql_result($dbresult1,$i,"imagedoc_server");
  		$i++;
  	}
  	// now send data to web server to establish a valid session and to return a menu
  	$myurl="http://$web_server/teamsession.php?user_name=$user_name&user_teamid=$user_teamid&user_admin_access=$user_admin_access&imagedoc_server=$imagedoc_server";
  	//echo "$myurl";
  	//sleep (10);
    	header("Location: ".$myurl);
    	exit();
}
else {
echo "Wrong Username or Password";
}
?>