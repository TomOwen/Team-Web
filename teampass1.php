<?php
$m = $_GET['m'];
$u = $_GET['u'];
$e = $_GET['e'];
?>
<html>
<head>
<title>TEAM Password Maintenance</title>
<script>
function validateForm1(f)
{
var x=document.forms["form1"]["user_name"].value;
if (x==null || x=="")
  {
  alert("Login Name must be filled out");
  return false;
  }
x=document.forms["form1"]["user_password"].value;
if (x==null || x=="")
  {
  alert("Current password must be filled out");
  return false;
  }
x=document.forms["form1"]["new_password1"].value;
if (x==null || x=="")
  {
  alert("New password must be filled out");
  return false;
  }
y=document.forms["form1"]["new_password2"].value;
if (y==null || y=="")
  {
  alert("Repeat New password must be filled out");
  return false;
  }
if (x != y)
  {
  alert("New password values are not equal, please re=enter them");
  return false;
  }
}
function validateForm2(f)
{
var x=document.forms["form2"]["user_email"].value;
if (x==null || x=="")
  {
  alert("Please enter your TEAM registered Email address");
  return false;
  }
}
</script>
</head>
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
<HR WIDTH="50%" COLOR="#bebebe" SIZE="4">
 <table align="center" border="0" cellpadding="0" cellspacing="1"
width="400">
<tbody>
<tr>
<form name="form1" method="post" action="teampass2.php" onsubmit="return validateForm1(this)">
<td>
<table align="center" border="0" cellpadding="0" cellspacing="1" width="400">
<tbody>
<input type="hidden" name="p" value="1">
<tr>
<td colspan="3" align="center"><strong>Change My Password</strong></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td width="200">User Login<br>
</td>
<td width="6"> <br>
</td>
<?php
echo "<td width=\"200\"><input name=\"user_name\" id=\"user_name\" value=\"$u\" type=\"text\"></td>";
?>
</tr>
<tr>
<td width="200">Current Password<br>
</td>
<td width="6"> <br>
</td>
<td width="200"><input name="user_password" id="user_password" type="password"></td>
</tr>
<tr>
<td width="200">New Password<br>
</td>
<td width="6"> <br>
</td>
<td width="200"><input name="new_password1" id="new_password1" type="password"></td>
</tr>
<tr>
<td>Repeat New Password<br>
</td>
<td><br>
</td>
<td><input name="new_password2" id="new_password2" type="password"></td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input name="Submit" value="Change My Password" type="submit"></td>
</tr>
<?php
echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
$message = "";
if ($m == 'x') $message = "<font color=red>Invalid URL</font>";
if ($m == 'nf1') $message = "<font color=red>Invalid User/Password</font>";
if ($m == 'u1') $message = "<b>Password Updated</b>";
if ($message != "") echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>$message</td>";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td><a href=index.php>Back to TEAM Login</td></tr>";
?>
</form>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<HR WIDTH="50%" COLOR="#bebebe" SIZE="4">
<table align="center" border="0" cellpadding="0" cellspacing="1" width="400">
<tbody>
<tr>
<form name="form2" method="post" action="teampass2.php" onsubmit="return validateForm2(this)">
<td>
<table bgcolor="#FFFFFF" border="0" cellpadding="3" cellspacing="1" height="170" width="403">
<tbody>
<input type="hidden" name="p" value="2">
<tr>
<td colspan="3" align="center"><strong>I forgot my Password or Login ID<br>
</strong></td>
</tr>
<tr>
<td width="200">TEAM Email Address<br>
</td>
<td width="6"> <br>
</td>
<?php
echo "<td width=\"200\"><input name=\"user_email\" id=\"user_email\" value=\"$e\" type=\"text\"></td>";
?>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input name="Submit" value="Send Me Instructions" type="submit"></td>
</tr>
<?php

$message = "";
if ($m == 's2') $message = "<b>Email sent, Check your email for instructions</b>";
if ($m == 'nf2') $message = "<font color=red>Email Not in TEAM</font>";
if ($message != "") echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>$message</td>";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td><a href=index.php>Back to TEAM Login</td></tr>";
?>
</form>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<HR WIDTH="50%" COLOR="#bebebe" SIZE="4">
</body>
</html>
