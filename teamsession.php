<?php
require '../db/dbaccess.php';
// validate the user_name is really from user_team
$user_name=$_GET['user_name'];
$user_teamid=$_GET['user_teamid'];
$user_admin_access=$_GET['user_admin_access'];
$imagedoc_server=$_GET['imagedoc_server'];
session_start();
session_destroy();
session_start();
$storedVars = array(1 => $user_name, $user_teamid, $user_admin_access,$imagedoc_server);
$_SESSION['team1']=$storedVars;
//echo "$user_name, $user_teamid, $user_admin_access,$imagedoc_server";
// session now established push out the main menu
?>
<html>
    <head>
        <title>TEAM</title>
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
            	<li><a href="http://www.websoftmagic.com/wv/index.php">Patients</a></li>
            	<li><a href="#">Studies</a></li>
            	<li><a href="#">Doctors</a></li>
            	<li><a href="#">Reports</a></li>
            	<li><a href="#">Help</a></li>
            	<li><a href="#">Log Out</a></li>
            	
                
            </ul>
         
        </div>
        <div id="main-content">
            <h2>TEAM Content</h2>
            

        </div>
    </body>
</html>