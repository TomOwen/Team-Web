<?php
$teamtitle = "TEAM Studies";
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
var x=document.forms["studyadd"]["study_id"].value;
if (x==null || x=="")
  {
  alert("Study ID must be filled out");
  return false;
  }
if (x.indexOf(' ') >= 0)
  {
  alert("Study ID must not have any spaces in the ID");
  return false;
  }
x=document.forms["studyadd"]["study_name"].value;
if (x==null || x=="")
  {
  message = message + "Study name must be entered\n";
  }

x=document.forms["studyadd"]["study_owner"].value;
if (x==null || x=="")
  {
  message = message + "Study sponser must be entered\n";
  }
var mypr = document.forms["studyadd"]["study_percentpr"].value;
if (mypr != "") {
		if (isNaN(mypr) || mypr==null || mypr=="")
		{
		message = message + mypr + " is not a valid number for partial response\n";
		}
	}
var mypd = document.forms["studyadd"]["study_percentpd"].value;
if (mypd != "") {
		if (isNaN(mypd) || mypd==null || mypd=="")
		{
		message = message + mypd + " is not a valid number for progressive disease\n";
		}
	}
var seats = document.forms["studyadd"]["study_seats"].value;
if (seats != "") {
		if (isNaN(seats) || seats==null || seats=="")
		{
		message = message + seats + " is not a valid number for allocated seats\n";
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
include 'teamtop2.php';
if ($user_admin_access == 'N') {
echo "Sorry your user name is not allowed to add/change/delete. Hit the Back key to return to menu.";
exit();
}
?>

<table style="margin: auto;" border="1" cellpadding="10" cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><br>

<form name="studyadd" method="Post"
action="studyadd2.php" onsubmit="return validateForm(this)">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody>

<?php
echo "<tr><td>&nbsp;Study ID</td><td><input name=\"study_id\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Sponser</td><td><input name=\"study_owner\"  size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Name</td><td><input name=\"study_name\"  size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Study File Name</td><td><input name=\"study_url\"  size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Percent Partial Response</td><td><input name=\"study_percentpr\" value=\"30\" size=\"10\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Percent Progressive Disease</td><td><input name=\"study_percentpd\" value=\"20\" size=\"10\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Number of Allocated Slots</td><td><input name=\"study_seats\" value=\"0\" size=\"10\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
?>
<tr><td ><input name="Submit" value="Add Study" type="submit"></td></tr>
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