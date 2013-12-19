<?php
$teamtitle = "TEAM Doctor Add";
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
var x=document.forms["doctoradd"]["doctor_name"].value;
if (x==null || x=="")
  {
  alert("Doctor Name must be filled out");
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

<form name="doctoradd" method="Post"
action="doctoradd2.php" onsubmit="return validateForm(this)">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody>

<?php
echo "<tr><td>&nbsp;Doctor Name</td><td><input name=\"doctor_name\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Doctor Information</td><td><input name=\"doctor_info\"  size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
?>
<tr><td ><input name="Submit" value="Add Doctor" type="submit"></td></tr>
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