<?php
$admin_menu = 'N';
$pos=strpos($user_name,"admin");
// if user is adminxxxxx and user_admin_access = 'Y' then allow menu
if ($pos === 0 and $user_admin_access == 'Y') { $admin_menu = 'Y';} else { $admin_menu = 'N';}
if ($admin_menu != 'Y') {
	exit();
	}
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
						<li><a href="auditpatient.php">Audit</a></li>
						<li><a href="vipalerts.php?list=all&s=n">List_VIPs</a></li>
						<li><a href="vipalertadd.php">New_VIP</a></li>
						
					</ul>
				</li>
            			<li><a href="audituser.php">User</a></li>
            			<li><a href="auditdrstudy.php">Dr/Study</a></li>
            			<li><a href="auditreports.php">Reports</a></li>
            			<li><a href="auditadmin.php">Admin</a></li>
            			<li><a href="index.php?reset=y">Log Out</a></li>
				
			</ul>
	</div>
	<br>