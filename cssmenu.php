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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
echo "<title>$teamtitle</title>\n";
?>

<link rel="stylesheet" type="text/css" href="cssmenu/reset.css" />
<link rel="stylesheet" type="text/css" href="cssmenu/style.css" />
<style>
#logo {text-align:center;}
</style>
</head>
<body>
	<div id="logo">
		<img src="teamlogo.png" alt="Team Logo" width="109" height="98" />
        	<img src="teamtext2.png" alt="Team Text" width="408" height="67" />
	</div>
	<div id="page-wrap">
		<div id="content">
            
			<ul id="menu">
				<li class="freebies">
					<a >Patients</a>
					<ul>
						<li><a href="patients.php?s=n">List Page</a></li>
						<li><a href="patients.php?list=all&s=n">List all</a></li>
						<li><a href="patientadd.php">New_Patient</a></li>
						
					</ul>
				</li>
				<li class="freebies">
					<a >Studies</a>
					<ul>
						<li><a href="studies.php">List</a></li>
						<li><a href="studyadd.php">New_Study</a></li>
					</ul>
				</li>
				<li class="freebies">
					<a >Doctors</a>
					<ul>
						<li><a href="doctors.php">List</a></li>
						<li><a href="doctoradd.php">New_Doctor</a></li>
					</ul>
				</li>
				<li class="freebies">
					<a >Admin</a>
					<ul>
						<li><a href="users.php">List Users</a></li>
						<li><a href="useradd.php">New_User</a></li>
						<li><a href="statsupdate.php?teamid=$user_teamid">Reload_Graph</a></li>
						<li><a href="settingsedit.php">Server_Settings</a></li>
						<li><a href="teamaudit.php">Audit</a></li>
					</ul>
				</li>
				<li><a href="../db/team/teamreports.php?user_name=$user_name" target="_blank">Reports</a></li>
				<li><a href="index.php?reset=y">Logout</a></li>
			</ul>
	</div>
	<br>
