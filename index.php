<?php
$v=$_GET['v'];
$reset=$_GET['reset'];
if ($reset == 'y') {
	session_start();
	session_destroy();
	}
?>
<html>
<head>
<title>TEAM Login</title>
<body>
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
<br><br>
<table width="400" border="0" align="center" cellpadding="0" cellspacing="1" >
<tr>
<form name="form1" method="post" action="teamlogin.php">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="3" align="center"><strong>Welcome to the TEAM System</strong></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td width="98">User Name</td>
<td width="6"> </td>
<td width="294"><input name="user_name" type="text" id="user_name"></td>
</tr>
<tr>
<td>Password</td>
<td></td>
<td><input name="user_password" type="password" id="user_password"></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input type="submit" name="Submit" value="Login"></td>
</tr>
</form>
<tr><td>&nbsp;</td></tr>
<tr>
<td colspan="3" align="center">
<form name="form1" method="post" action="teampass1.php">
<input type="submit" name="Submit" value="Change and Forgotten Passwords">
</form>
</td>
</tr>

</table>
</td>

</tr>

</table>
<div align="center">
<br>
<font color="#ff0000">
<?php
$invalid = "";
if ($v == 'n') $invalid = "Invalid user/password, please try again!";
echo $invalid;
?>
</font> </div>
</body>
</html>
