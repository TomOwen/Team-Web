<?php
?>
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
					<a href="#">Patients</a>
					<ul>
						<li><a href="patients.php?s=n">List_Page</a></li>
						<li><a href="patients.php?list=all&s=n">List_all</a></li>
						<li><a href="patientadd.php">New_Patient</a></li>
						
					</ul>
				</li>
				<li class="freebies">
					<a href="#">Studies</a>
					<ul>
						<li><a href="studies.php">List</a></li>
						<li><a href="studyadd.php">New_Study</a></li>
					</ul>
				</li>
				<li class="freebies">
					<a href="#">Doctors</a>
					<ul>
						<li><a href="doctors.php">List</a></li>
						<li><a href="doctoradd.php">New_Doctor</a></li>
					</ul>
				</li>
<?php
$admin_menu = 'N';
$pos=strpos($user_name,"admin");
// if user is adminxxxxx and user_admin_access = 'Y' then allow menu
if ($pos === 0 and $user_admin_access == 'Y') { $admin_menu = 'Y';} else { $admin_menu = 'N';}
if ($admin_menu == 'Y') {
				echo "<li class=\"freebies\">";
				echo "	<a href=\"#\">Admin</a>";
				echo "	<ul>";
				echo "		<li><a href=\"users.php\">List_Users</a></li>";
				echo "		<li><a href=\"useradd.php\">New_User</a></li>";

echo "<li><a href=\"statsupdate.php?teamid=$user_teamid\">Reload_Graph</a></li>";
				
				echo "		<li><a href=\"settingsedit.php\">Server_Settings</a></li>";
						
				echo "	</ul>";
				echo "</li>";
}		
?>
<?php
echo "<li><a href=\"../db/team/teamreports.php?user_name=$user_name&teamid=$user_teamid\" target=\"teamreports\">Reports</a></li>";
?>
<?php
$admin_menu = 'N';
$pos=strpos($user_name,"admin");
// if user is adminxxxxx and user_admin_access = 'Y' then allow menu
if ($pos === 0 and $user_admin_access == 'Y') { $admin_menu = 'Y';} else { $admin_menu = 'N';}
if ($admin_menu == 'Y') {
				echo "<li><a href=\"teamaudit.php?reset=y\">Audit</a></li>";
}
?>
				<li><a href="index.php?reset=y">Logout</a></li>
			</ul>
	</div>
	<br>