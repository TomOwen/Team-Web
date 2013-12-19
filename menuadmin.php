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
            	<li><a href="patients.php?s=n">Patients</a>
            			<ul class="lm-vertical lm-down lm-submenu">
            			    <li><a href="patients.php?s=n">List Patients</a></li>
                                    <li><a href="patientadd.php">New Patient</a></li>
                                </ul>
            	</li>	   
            	            	<li><a href="studies.php?s=n">Studies</a>
            			<ul class="lm-vertical lm-down lm-submenu">
            			    <li><a href="studies.php">List Studies</a></li>
                                    <li><a href="studyadd.php">New Study</a></li>
                                </ul>
            	</li>	  
            	            	<li><a href="doctors.php?s=n">Doctors</a>
            			<ul class="lm-vertical lm-down lm-submenu">
            			    <li><a href="doctors.php">List Doctors</a></li>
                                    <li><a href="doctoradd.php">New Doctor</a></li>
                                </ul>
            	</li>	   
            	            	<li><a href="users.php?s=n">Doctors</a>
            			<ul class="lm-vertical lm-down lm-submenu">
            			    <li><a href="users.php">List Users</a></li>
                                    <li><a href="useradd.php">New User</a></li>
                                    <li><a href="settings.php">Refresh Graph DB</a></li>
                                    <li><a href="settings.php">Server Settings</a></li>
                                </ul>
            	</li>	  
            	<li><a href="../db/team/teamreports.php" target="_blank">Reports</a></li>
            	<li><a target="_blank" href="http://www.websoftmagic.com">Help</a></li>
            	<li><a href="index.php?reset=y">Log Out</a></li>
            	
                
            </ul>
         
        </div>