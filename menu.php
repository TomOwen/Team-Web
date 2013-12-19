<?php
?>
        <link rel="stylesheet" type="text/css" href="./js/lm/themes/example_theme_3/example_themet.css" />
        <script type="text/javascript" src="./js/lm/liveMenu.js"></script>
        <script type="text/javascript">
            liveMenu.initOnLoad('liveMenu1', { effect: 'smooth', duration: 400,
                beforeShow: function () {
                    this.opener.className = 'lm-selected-item';
                },
                beforeHide: function () {
                    this.opener.className = '';
                }
            });
        </script>
        <style>
            h1 { text-align: center; }
            #horizontal-menu { padding: 0 100px;}
            #main-content { margin: 25px 100px 0 100px; }
            /*
            img { float: left; margin:0px 70px; }
            */
            td.teamimg {
		text-align: center;
		}
        </style>
    </head>
    <body>
        
        
        </div>
        <div align="center">
	<table align="center" border="0" cellpadding="2" cellspacing="2" width="100%">
	<tbody>
	<tr>
		<td class="teamimg">
		<img src="teamlogo.png" alt="Team Logo" width="109" height="98" />
        	<img src="teamtext2.png" alt="Team Text" width="408" height="67" />
		</td>
	</tr>
	</tbody>
	</table>

	</div>
       
        <div id="horizontal-menu">
            <ul id="liveMenu1" class="lm-horizontal lm-menu">
            	<li><a href="#"><span class="down">Patients</a>
            			<ul class="lm-vertical lm-down lm-submenu">
            			    <li><a href="patients.php?s=n">A Page at a time</a></li>
            			    <li><a href="patients.php?list=all&s=n">All Patients</a></li>
                                    <li><a href="patientadd.php">New Patient</a></li>
                                </ul>
            	</li>	   
            	            	<li><a href="#"><span class="down">Studies</a>
            			<ul class="lm-vertical lm-down lm-submenu">
            			    <li><a href="studies.php">List Studies</a></li>
                                    <li><a href="studyadd.php">New Study</a></li>
                                </ul>
            	</li>	  
            	            	<li><a href="#"><span class="down">Doctors</a>
            			<ul class="lm-vertical lm-down lm-submenu">
            			    <li><a href="doctors.php">List Doctors</a></li>
                                    <li><a href="doctoradd.php">New Doctor</a></li>
                                </ul>
            	</li>
            	            	<li><a href="#"><span class="down">Administration</a>
            			<ul class="lm-vertical lm-down lm-submenu">
            			    <li><a href="doctors.php">List Doctors</a></li>
                                    <li><a href="doctoradd.php">New Doctor</a></li>
                                </ul>
            	</li>
<?php
$admin_menu = 'N';
$pos=strpos($user_name,"admin");
// if user is adminxxxxx and user_admin_access = 'Y' then allow menu
if ($pos === 0 and $user_admin_access == 'Y') { $admin_menu = 'Y';} else { $admin_menu = 'N';}
if ($admin_menu == 'Y') {
echo "            	            	<li><a href=\"#\"><span class=\"down\">Admin</a>";
echo "            			<ul class=\"lm-vertical lm-down lm-submenu\">";
echo "            			    <li><a href=\"users.php\">List Users</a></li>";
echo "                                    <li><a href=\"useradd.php\">New User</a></li>";
echo "                                    <li><a href=\"statsupdate.php?teamid=$user_teamid\">Reload Graph DB</a></li>";
echo "                                    <li><a href=\"settingsedit.php\">Server Settings</a></li>";
echo "                                    <li><a <a target=\"_blank\" href=\"teamaudit.php\">TEAM Audit</a></li>";
echo "                                </ul>";
echo "           	</li>";
}
	   
 echo"           	<li><a href=\"../db/team/teamreports.php?user_name=$user_name\" target=\"_blank\">Reports</a></li>";
?>
            	<li><a target="_blank" href="http://www.websoftmagic.com">Help</a></li>
            	<li><a href="index.php?reset=y">Log Out</a></li>
            	
                
            </ul>
         
        </div>