<?php
session_start();
if(!session_is_registered(team1)){
header("location:index.php");
}
$storedVars = $_SESSION['team1'];
$user_name = $storedVars[1];
$user_teamid = $storedVars[2];
$user_admin_access = $storedVars[3];
//$user_name = $_SESSION['user_name'];
//$user_teamid = $_SESSION['user_teamid'];
//$user_admin_access = $_SESSION['user_admin_access'];
?>
<html>
<body>
Login Successful for 
<?php 
Print_r ($_SESSION);
echo "<br>$user_name/$user_teamid/$user_admin_access" ?>
</body>
</html>