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

// Register $myusername, $mypassword and redirect to file "teammenuwv.php"

//session_register("user_name");
session_start();
session_destroy();
session_start();
$storedVars = array(1 => $user_name, $user_teamid, $user_admin_access);
$_SESSION['team1']=$storedVars;
//session_register("user_teamid");
//session_register("user_admin_access");
//$_SESSION['user_name'] = "$user_name";
//$_SESSION['user_teamid'] = '$user_teamid';
//$_SESSION['user_admin_access'] = '$user_admin_access';
header("location:teammenuwv.php");
}
else {
echo "Wrong Username or Password";
}
?>