<?php
$teamtitle = "TEAM Add Patient";
include 'teamtop1.php';
?>
        <script type="text/javascript">
        function validateForm()
{
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
<?php
$patient_id=$_POST['patient_id'];
$patient_name=$_POST['patient_name'];
$patient_study_id=$_POST['study_id'];
$patient_doctor=$_POST['doctor'];
?>
  

<table style="margin: auto; border="1" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><br>

<form name="patientedit" method="Post"
action="patientadd2.php" onsubmit="return validateForm()">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody>
<tr>
<td>Patient ID</td>
<?php
echo "<td><input name=\"patient_id\" value=\"$patient_id\" size=\"20\"";
?>
type="edit"></td>
</tr>
<tr>
<td>Name</td>
<?php
echo "<td><input name=\"patient_name\" value=\"$patient_name\" size=\"60\" type=\"edit\"></td>";
?>
</tr>
<tr>
<td>Study</td>
<td>
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
</tr>
<tr>
<td>Doctor</td>
<td>
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
</tr>
<tr>
<td colspan="2"><input name="Submit" value="Add New Patient"
type="submit"></td>
</tr>
</tbody>
</table>
</form>

</td>
</tr>
</tbody>
</table>
<?php
$query = "select patient_id from patient where patient_teamid = $user_teamid and patient_id = '$patient_id'";
$table_id = 'patient';
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);

if ($num > 0) {
// return 0 indicating patient_id already exists
	echo "<div align=\"center\">$patient_id already exists, try another</div>";
	exit ();
} else
{
// insert new patient
$query = "INSERT INTO patient (patient_teamid,patient_id,patient_name,patient_study_id,patient_doctor) VALUES ($user_teamid, '$patient_id', '$patient_name', '$patient_study_id', '$patient_doctor')";
$dbresult = mysql_query($query, $dbconnect);
$return_code = 1;
$q1 = "SELECT ROW_COUNT()as rc";
$dbresult2 = mysql_query($q1, $dbconnect);
$num2=mysql_numrows($dbresult2);
$j = 0;
$rc = 0;
while ($j < $num2) {
  	$rc=mysql_result($dbresult2,$j,"rc");
  	$j++;
}
if ($rc > 0) {
	$audit_type = 'a1';
	include 'auditlog.php';
	};
}
echo "<div align=\"center\">$patient_name ID=$patient_id was successfully added to TEAM<br><br><a href=\"patientedit.php?patient_id=$patient_id\">Edit $patient_name</a></div>";
?>
</body>
</html>
