<?php
$teamtitle = "TEAM Add VIP Alert";
require '../db/dbauditaccess.php';
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
var x=document.forms["vipalertadd"]["patient_id"].value;
if (x==null || x=="")
  {
  alert("Patient ID must be filled out");
  return false;
  }
if (x.indexOf(' ') >= 0)
  {
  alert("Patient ID must not have any spaces in the ID");
  return false;
  }
var x=document.forms["vipalertadd"]["alert_email"].value;
if (x==null || x=="")
  {
  alert("Please enter a valid email address");
  return false;
  }
if (x.indexOf(' ') >= 0)
  {
  alert("The email must not contain spaces");
  return false;
  }

var mypr = document.forms["vipalertadd"]["minutes_between"].value;
if (mypr == "") {
		message = message + "Please enter number of minutes between alert notifications\n";
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
if ($user_admin_access == 'N') {
echo "Sorry your user name is not allowed to add/change/delete. Hit the Back key to return to menu.";
exit();
}
?>

<table style="margin: auto;" border="1" cellpadding="10" cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><br>

<form name="vipalertadd" method="Post"
action="vipalertadd2.php" onsubmit="return validateForm(this)">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody>

<?php
echo "<tr><td>&nbsp;Patient ID</td><td><input name=\"patient_id\" size=\"20\" type=\"edit\">&nbsp;&nbsp;<a href=\"patients.php\">Back To TEAM</a></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Minutes between Alerts</td><td><input name=\"minutes_between\"  size=\"10\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Email to be Alerted</td><td><input name=\"alert_email\"  size=\"30\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
?>
<tr><td ><input name="Submit" value="Add VIP Alert" type="submit"></td></tr>
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