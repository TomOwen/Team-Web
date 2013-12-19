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
            	<li><a href="auditpatient.php">Patient</a></li>
            	<li><a href="audituser.php">User</a></li>
            	<li><a href="auditdrstudy.php">Dr/Study</a></li>
            	<li><a href="auditreports.php">Reports</a></li>
            	<li><a href="auditadmin.php">Admin</a></li>
            	<li><a target="_blank" href="http://www.websoftmagic.com">Help</a></li>
            	<li><a href="index.php?reset=y">Log Out</a></li>
            	
                
            </ul>
         
        </div>