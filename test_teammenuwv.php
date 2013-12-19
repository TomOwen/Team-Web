<?php
session_start();
if(!session_is_registered(user_name)){
header("location:test_index.php");
}
$user_name = $_SESSION['user_name'];
$user_teamid = $_SESSION['user_teamid'];
$user_admin_access = $_SESSION['user_admin_access'];
?>
<html>
<body>
Login Successful for 
<?php 
//Print_r ($_SESSION);
echo "<br>$user_name/$user_teamid/$user_admin_access" ?>
</body>
</html>