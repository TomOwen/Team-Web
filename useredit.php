<?php
$teamtitle = "TEAM Users";
include 'teamtop1.php';
?>

<script >
        function validateForm(f)
{
var adm = "<?php echo $user_admin_access; ?>";
if (adm=="N")
	{
	alert("TEAM Editing only available to users with edit option.");
  	return false;
	}
if (f.update_password.checked) {
	var x=document.forms["useredit"]["user_password"].value;
	if (x==null || x=="")
  		{
 		alert("Because you checked Update Password, a password must be entered.");
  		return false;
  		}
    }
}



</script>
<?php

$user_name2 = $_GET['user_name'];
// get user data
//echo "user_admin_access=$user_admin_access";
$query = "select user_teamid,user_name,user_password,user_email,user_admin_access from users where user_teamid = '$user_teamid' and user_name = '$user_name2'";
//echo "$query";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i = 0;
while ($i < $num) {
  $user_name2=mysql_result($dbresult,$i,"user_name");
  $user_password=mysql_result($dbresult,$i,"user_password");
  $user_email=mysql_result($dbresult,$i,"user_email");
  $user_admin_access2=mysql_result($dbresult,$i,"user_admin_access");
  $i++;
  }
include 'teamtop2.php';
?>

<table style="margin: auto;" border="1" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><br>

<form name="useredit" method="Post"
action="useredit2.php" onsubmit="return validateForm(this)">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody>
<tr>
<td>&nbsp;Login Name</td>
<?php
echo "<input type=\"hidden\" name=\"user_name2\" value=\"$user_name2\">";
echo "<td>$user_name2";
?>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<?php
echo "<tr><td>&nbsp;Password</td><td><input name=\"user_password\" value=\"\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;User Email Address</td><td><input name=\"user_email\" value=\"$user_email\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
if ($user_admin_access2 == 'Y') { $chk = "checked=on"; } else { $chk = ""; }
echo "<tr><td>&nbsp;Edit TEAM Data</td><td align=left><input type=\"checkbox\" name=\"user_admin_access\" value=\"Y\" $chk>Allow Editing</td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Update Password</td><td align=left><input type=\"checkbox\" id=\"update_password\" name=\"update_password\" value=\"Y\">Check to Update Password</td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";  
?>
<tr><td ><input name="Submit" value="Update User" type="submit"></td></tr>
<tr><td>&nbsp;</td></tr>
</form>
<tr><td>
<form action="userdelete.php" method="get" onsubmit="return confirm('You are about to delete this user.\n\nClick OK to proceed\n\nClick Cancel to Cancel');">
<input type="submit" value="Delete User Login">
<?php
echo "<input type=\"hidden\" name=\"user_name2\" value=\"$user_name2\">";
//echo "user_admin_access2 = $user_admin_access2";
$audit_type = 'v7';
include 'auditlog.php';
?>
</form></td><td>
<td valign="top" align="right">
</td>
</tr>
<tr><td>&nbsp;</td></tr>
</tbody>
</table>


</td>
</tr>
</tbody>
</table>
<br>

</body>
</html>