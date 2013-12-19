<?php
$teamtitle = "TEAM VIP Alert Edit";
include 'teamtop1.php';
require '../db/dbauditaccess.php';
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
var x=document.forms["vipalertedit"]["alert_email"].value;
if (x==null || x=="")
  {
  message = message + "An alert email address must be entered.\n";
  }


var mypr = document.forms["vipalertedit"]["minutes_between"].value;
if (mypr == "") {
	message = message + "Number of minutes between alerts must be entered\n";
	}

if (mypr != "") {
		if (isNaN(mypr) || mypr==null || mypr=="")
		{
		message = message + mypr + " is not a valid number for minutes\n";
		}
	}
if (message == "") {
   return true;
 } else {
   alert(message);
   return false;
 }
}
</script>
<?php
include 'teamaudit2.php';
?>
<?php
$patient_id = $_GET['patient_id'];
// get study data
$query = "select minutes_between,alert_email,last_email from vipalert where teamid = $user_teamid and patient_id = '$patient_id' ";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
$i = 0;
while ($i < $num) {
  $minutes_between=mysql_result($dbresult,$i,"minutes_between");
  $alert_email=mysql_result($dbresult,$i,"alert_email");
  $last_email=mysql_result($dbresult,$i,"last_email");
  
  $i++;
  }
?>
<table style="margin: auto;" border="1" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><br>

<form name="vipalertedit" method="Post"
action="vipalertedit2.php" onsubmit="return validateForm(this)">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody>
<tr>
<td>&nbsp;Patient ID</td>
<?php
echo "<td>$patient_id&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"patients.php\">Back To TEAM</a></td>";
?>
</tr>
<?php
echo "<tr><td>&nbsp;</td>";
echo "<tr><td width=50%>&nbsp;Minutes between Alerts</td><td width=50%><input name=\"minutes_between\" value=\"$minutes_between\" size=\"10\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td>";
echo "<tr><td width=50%>&nbsp;Alert Email Address</td><td width=50%><input name=\"alert_email\" value=\"$alert_email\" size=\"30\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td>";
echo "<tr><td width=50%>&nbsp;Last Alert Sent</td><td width=50% >$last_email</td></tr>\n";
echo "<tr><td>&nbsp;</td>";
echo "<tr><td>&nbsp;</td>";
echo "<input type=\"hidden\" name=\"patient_id\" value=\"$patient_id\">";
?>
<tr><td ><input name="Submit" value="Update VIP Alert" type="submit"></td></tr>
</form>
<tr><td>&nbsp;</td>
<tr><td>
<form action="vipalertdelete.php" method="get" onsubmit="return confirm('You are about to delete this VIP Alert.\n\nClick OK to proceed\n\nClick Cancel to Cancel');">
<input type="submit" value="Remove VIP Alert">
<?php
echo "<input type=\"hidden\" name=\"patient_id\" value=\"$patient_id\">";
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