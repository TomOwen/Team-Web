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
$doc_type = $storedVars[5];
$image_type = $storedVars[6];
$db_server = $storedVars[7];
$imagedoc_user = $storedVars[8];
$imagedoc_password = $storedVars[9];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
echo "<title>$teamtitle</title>\n";
?>
<?php
$admin_menu = 'N';
$pos=strpos($user_name,"admin");
// if user is adminxxxxx and user_admin_access = 'Y' then allow menu
if ($pos === 0 and $user_admin_access == 'Y') { $admin_menu = 'Y';} else { $admin_menu = 'N';}
if ($admin_menu == 'Y') {
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"cssmenu/style.css\" />";
	} else {
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"cssmenu/styleshort.css\" />";
	}
?>
<link rel="stylesheet" type="text/css" href="cssmenu/reset.css" />
<style>
#logo {text-align:center;}
</style>