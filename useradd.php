<?php
$teamtitle = "TEAM User Add";
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
var message = '';
var x=document.forms["useradd"]["user_name2"].value;
if (x==null || x=="")
  {
  alert("Login Name must be filled out");
  return false;
  }
x=document.forms["useradd"]["user_password"].value;
if (x==null || x=="")
  {
  alert("Password is required");
  return false;
  }
x=document.forms["useradd"]["user_email"].value;
if (x==null || x=="")
  {
  alert("User email is required");
  return false;
  }
return true;
}
</script>
<?php
include 'teamtop2.php';
?>

<table style="margin: auto;" border="1" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><br>

<form name="useradd" method="Post"
action="useradd2.php" onsubmit="return validateForm(this)">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody>

<?php
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Login Name</td><td><input name=\"user_name2\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Password</td><td><input name=\"user_password\"  size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;User Email Address</td><td><input name=\"user_email\"  size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Edit TEAM Data</td><td align=left><input type=\"checkbox\" name=\"user_admin_access\" value=\"Y\">Allow Editing</td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
?>
<tr><td ><input name="Submit" value="Add User Login" type="submit"></td></tr>
</form>
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