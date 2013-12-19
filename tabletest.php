<?php
$teamtitle = "TEAM Patient Edit";
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
<?php
$patient_id = $_GET['patient_id'];
// get patient data
$query = "select patient_name, patient_study_id, patient_doctor from patient where patient_teamid = $user_teamid and patient_id = '$patient_id'";
//echo "$query";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i = 0;
while ($i < $num) {
  $patient_name=mysql_result($dbresult,$i,"patient_name");
  $patient_study_id=mysql_result($dbresult,$i,"patient_study_id");
  $patient_doctor=mysql_result($dbresult,$i,"patient_doctor");
  $i++;
  }
$audit_type = 'v1';
//include 'auditlog.php';
?>

<form name="patientedit" method="Post"
action="patientedit2.php" onsubmit="return validateForm()">
<?php
echo "<input type=\"hidden\" name=\"patient_id\" value=\"$patient_id\">";
?>


<br>
<HR WIDTH="50%" COLOR="#bebebe" SIZE="4">
<br>
<table style="margin: auto;" border="0" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr>
<td>&nbsp;</td>
<td valign="top" width=25% ><b>Patient ID</b><br>
</td>
<?php
echo "<td valign=\"top\" width=75% >$patient_id<br>";
?>
</td>
<td>&nbsp;</td>
</tr>

<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>

<tr>
<td>&nbsp;</td>
<td valign="top" width=25%><b>Patient Name</b><br>
</td>
<?php
echo "<td valign=\"top\" width=75%><input name=\"patient_name\" value=\"$patient_name\" size=\"30\" type=\"edit\"></td>";
?>
<td>&nbsp;</td>
</tr>

<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>

<tr>
<td>&nbsp;</td>

<td valign="top" width=25%><b>Study/Trial</b><br>
</td>
<td valign="top">
<select name="study_id">
<?php
echo "<option value=\"$patient_study_id\" selected=\"selected\">$patient_study_id</option>";
?>
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


<td>&nbsp;</td>
</tr>

<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>

<tr>
<td>&nbsp;</td>
<td valign="top" width=25%><b>Doctor</b><br>
</td>
<td valign="top">
<select name="doctor">
<?php
echo "<option value=\"$patient_doctor\" selected=\"selected\">$patient_doctor</option>";
?>
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
<td>&nbsp;</td>
</tr>
<?php
if ($user_admin_access == 'Y') {
 echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
 echo "<tr><td>&nbsp;</td><td ><input name=\"Submit\" value=\"Update Patient\" type=\"submit\"></td>";
 
 echo "<td><form action=\"patientdelete.php\" method=\"get\" onsubmit=\"return confirm('Deleting a patient will also delete all of his scans and lesions.\n\nClick OK to proceed\n\nClick Cancel to Cancel');\">";
 echo "<input type=\"submit\" value=\"Delete Patient\"></td><td>&nbsp;</td>";
 echo "</tr>";
}
?>
</form>
</tbody>
</table>
<br>
<HR WIDTH="50%" COLOR="#bebebe" SIZE="4">

<br>



<table style="margin: auto;" border="0" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<?php
echo "<form action=\"http://$db_server/team/teamresponse.php\" target=\"_blank\" method=\"get\">";
echo "<tr><td align=center width=\"33%\"><input type=\"image\" src=\"responsethumb.png\" height=55 width=55 alt=\"Submit button\"></td>";
echo "<input type=\"hidden\" name=\"patient_id\" value=\"$patient_id\">";
echo "<input type=\"hidden\" name=\"patient_name\" value=\"$patient_name\">";
echo "<input type=\"hidden\" name=\"user_name\" value=\"$user_name\">";
echo "<input type=\"hidden\" name=\"user_teamid\" value=\"$user_teamid\">";
?>
</form>
<?php
echo "<form action=\"scans.php\" method=\"get\">";
echo "<td align=center width=\"33%\"><input type=\"image\" src=\"icon-scans.png\" height=55 width=55 alt=\"Submit button\"></td>";
echo "<input type=\"hidden\" name=\"patient_id\" value=\"$patient_id\">";
echo "<input type=\"hidden\" name=\"patient_name\" value=\"$patient_name\">";
echo "<input type=\"hidden\" name=\"user_name\" value=\"$user_name\">";
?>
</form>
<?php
echo "<form action=\"compareimages.php\"  method=\"get\">";
echo "<td align=center width=\"33%\"><input type=\"image\" src=\"icon-twodoctors.png\" height=55 width=55 alt=\"Submit button\"></td>";
echo "<input type=\"hidden\" name=\"patient_id\" value=\"$patient_id\">";
echo "<input type=\"hidden\" name=\"patient_name\" value=\"$patient_name\">";
echo "<input type=\"hidden\" name=\"user_name\" value=\"$user_name\">";
?>
</form>
</tr>
</tbody>
</table>

<table style="margin: auto;" border="0" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr><td align="center" width=33%>Response</td><td align="center" width=33%>Scans</td><td align="center" width=33%>Compare Images</td></tr>
</tbody>
</table>



</body>
</html>
