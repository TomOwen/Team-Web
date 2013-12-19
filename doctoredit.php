<?php
$teamtitle = "TEAM Doctors";
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

}
</script>
<?php
include 'teamtop2.php';
$doctor_name = $_GET['doctor_name'];
$query = "select doctor_name,doctor_info from doctor where doctor_teamid = $user_teamid and doctor_name = '$doctor_name' ";
//echo "$query";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i = 0;
while ($i < $num) {
  $doctor_name=mysql_result($dbresult,$i,"doctor_name");
  $doctor_info=mysql_result($dbresult,$i,"doctor_info");
  $i++;
  }

// audit log entry
	$audit_type = 'v4';
	include 'auditlog.php';
// end audit log entry
?>
<table style="margin: auto;" border="1" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><br>

<form name="doctoredit" method="Post"
action="doctoredit2.php" onsubmit="return validateForm(this)">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody>
<tr>
<td>&nbsp;Doctor Name</td>
<?php

echo "<input type=\"hidden\" name=\"doctor_name\" value=\"$doctor_name\">";
echo "<td>$doctor_name";
?>
</td>
</tr>
<?php
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Doctor Information</td><td><input name=\"doctor_info\" value=\"$doctor_info\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
?>
<tr><td ><input name="Submit" value="Update Doctor" type="submit"></td></tr>
</form>
<tr><td>&nbsp;</td></tr>
<tr><td>
<form action="doctordelete.php" method="get" onsubmit="return confirm('You are about to delete this doctor.\n\nClick OK to proceed\n\nClick Cancel to Cancel');">	
<input type="submit" value="Delete Doctor">
<?php
echo "<input type=\"hidden\" name=\"doctor_name\" value=\"$doctor_name\">";
echo "<tr><td>&nbsp;</td></tr>";
?>
</form></td><td>
<td valign="top" align="right">
</td>
</tr>

</tbody>
</table>


</td>
</tr>
</tbody>
</table>
<br>

</body>
</html>