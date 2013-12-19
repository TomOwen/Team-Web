<?php
$teamtitle = "TEAM Doctors";
include 'teamtop1.php';
include 'teamtop2.php';
?>

<table style="margin: auto;" border="0" cellpadding="2"
cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><b>Doctor Name</b><br>
</td>
<td valign="top"><b>Additional Information</b><br>
</td>

</tr>
<tr><td></td></tr>
<?php 
$query = "select doctor_teamid,doctor_name,doctor_info from doctor where doctor_teamid = $user_teamid order by doctor_name";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);             
$i=0;
while ($i < $num) {
  $doctor_name=mysql_result($dbresult,$i,"doctor_name");
  $doctor_info=mysql_result($dbresult,$i,"doctor_info");
  
  $urledit = "doctoredit.php?doctor_name=$doctor_name";
  echo "<tr>";
  echo "<td valign=\"top\"><a href=\"$urledit\">$doctor_name</a><br>";
  
  echo "</td>";
  echo "<td valign=\"top\">$doctor_info<br>";
  echo "</td>";
  
  echo "</tr>";
  $i++;
  }
// audit log entry
	$audit_type = 'l4';
	//include 'auditlog.php';
// end audit log entry
?>
</tbody>
</table>
<br>
</body>
</html>