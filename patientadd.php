<?php
$teamtitle = "TEAM Add Patient";
include 'teamtop1.php';
?>

        <script type="text/javascript">
        function validateForm()
{
var adm = "<?php echo $user_admin_access; ?>";
if (adm=="N")
	{
	alert("TEAM Editing only available to users with edit option.");
  	return false;
	}
var x=document.forms["patientedit"]["patient_id"].value;
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
var x=document.forms["patientedit"]["patient_name"].value;
if (x==null || x=="")
  {
  alert("Patient Name must be entered");
  return false;
  }
}
</script>
<?php
include 'teamtop2.php';
?>
        
<table style="margin: auto;" border="1" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><br>

<form name="patientedit" method="Post"
action="patientadd2.php" onsubmit="return validateForm()">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody>
<tr>
<td>&nbsp;Patient ID</td>
<td><input name="patient_id" value="" size="20"
type="edit"></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td>&nbsp;Name</td>
<td><input name="patient_name" value="" size="60" type="edit"></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td>&nbsp;Study</td>
<td>
<select name="study_id">
<option value="n/a" selected="selected">n/a</option>
<?php
// get all the studies
//<option value="number 1">number 2</option>
$query = "select study_id  from studies where study_teamid = $user_teamid order by study_id";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i = 0;
while ($i < $num) {
  $study_id=mysql_result($dbresult,$i,"study_id");
  echo "<option value=\"$study_id\">$study_id</option>";
  $i++;
  }
?>
</select>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td>&nbsp;Doctor</td>
<td>
<select name="doctor">
<option value="n/a" selected="selected">n/a</option>
<?php
// get all the doctors
$query = "select doctor_name  from doctor where doctor_teamid = $user_teamid order by doctor_name";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i = 0;
while ($i < $num) {
  $doctor_name=mysql_result($dbresult,$i,"doctor_name");
  echo "<option value=\"$doctor_name\">$doctor_name</option>";
  $i++;
  }
?>

</select>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td colspan="2"><input name="Submit" value="Add New Patient"
type="submit"></td>
</tr>
<tr><td>&nbsp;</td></tr>
</tbody>
</table>
</form>

</td>
</tr>
</tbody>
</table>
</body>
</html>
