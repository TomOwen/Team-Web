<?php
require '../db/dbaccess.php';
//print_r($_GET);
$m = $_GET['m'];
$m = stripslashes($m);
$m = mysql_real_escape_string($m);

$e = $_GET['e'];
$e = stripslashes($e);
$e = mysql_real_escape_string($e);

$t = $_GET['t'];
$t = stripslashes($t);
$t = mysql_real_escape_string($t);

$teamid = $_GET['tid'];
$teamid = stripslashes($teamid);
$teamid = mysql_real_escape_string($teamid);

//user name from teampass4
$u = $_GET['u'];
$u = stripslashes($u);
$u = mysql_real_escape_string($u);
?>
<html>
<head>
<title>TEAM Password Reset</title>
<script>
function validateForm1(f)
{
var x=document.forms["form1"]["user_name"].value;
if (x==null || x=="")
  {
  alert("Login Name must be filled out");
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
<?php
if ($m == 'u3') {
	echo "<center><h2>Password Sucessfully Updated for $u</h2></center></body></html>";
	echo "<br><center><a href=index.php>Back to TEAM Login</center>";
	exit();
}
if ($m == 'nf3') {
	echo "<center><h2>Sorry that Link has already been used or is invalid.</h2></center></body></html>";
	echo "<br><center><a href=index.php>Back to TEAM Login</center>";
	exit();
}
// get all the user names for this email user
$query = "select teamid, email from forgotpw where teamid = $tid and email_md5 = '$e' and requested = $t";
//echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  	$teamid=mysql_result($dbresult,$i,"teamid");
  	$email=mysql_result($dbresult,$i,"email");
  	$i++;
  	}
if ($num == 0) {
	//echo "got zero";
	echo "<center><h2>Sorry that Link has already been used or is invalid.</h2></center></body></html>";
	echo "<br><center><a href=index.php>Back to TEAM Login</center>";
	exit();
}
// here if good url, get all the user_names
$query = "select user_name from users where user_teamid = $tid and user_email = '$email' order by user_name";
//echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult); 
$user_names = "";             
$i=0;
while ($i < $num) {
  	$user_name=mysql_result($dbresult,$i,"user_name");
  	$user_names = $user_names . " " . $user_name;
  	$i++;
  	}
echo "<center><b>User Login(s): $user_names</b></center><br>";

?>
 <table align="center" border="0" cellpadding="0" cellspacing="1"
width="400">
<tbody>
<tr>
<form name="form1" method="post" action="teampass4.php" onsubmit="return validateForm1(this)">
<?php
echo "<input type=\"hidden\" name=\"tid\" value=\"$tid\">";
echo "<input type=\"hidden\" name=\"t\" value=\"$t\">";
echo "<input type=\"hidden\" name=\"e\" value=\"$e\">";
?>
<td>
<table align="center" border="0" cellpadding="0" cellspacing="1" width="400">
<tbody>
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
echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
$message = "";
if ($m == 'u3') $message = "Password Updated";
if ($message != "") echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>$message</td>";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td><a href=index.php>Back to TEAM Login</td></tr>";
?>
</form>
</tbody>
</table>
</body>
</html>
