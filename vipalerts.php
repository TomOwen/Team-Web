<?php
$teamtitle = "TEAM VIP Alerts";
include 'teamtop1.php';
include 'teamaudit2.php';
require '../db/dbauditaccess.php';
?>

<table style="margin: auto;" border="0" cellpadding="2"
cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><b>Patient ID</b><br>
</td>
<td valign="top"><b>Minutes between Alerts</b><br>
</td>
<td valign="top"><b>Alert Email Address</b><br>
</td>
<td valign="top"><b>Last Alert&nbsp;&nbsp;<a href="patients.php">Back To TEAM</a></b><br>
</td>

</tr>
<tr><td>&nbsp;</td></tr>
<?php 
$query = "select patient_id, minutes_between, alert_email, last_email from vipalert where teamid = $user_teamid order by patient_id";
//echo "query=$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);             
$i=0;
while ($i < $num) {
  $patient_id=mysql_result($dbresult,$i,"patient_id");
  $minutes_between=mysql_result($dbresult,$i,"minutes_between");
  $alert_email=mysql_result($dbresult,$i,"alert_email");
  $last_email=mysql_result($dbresult,$i,"last_email");
  
  //$urledit = "http://$imagedoc_server/$study_url";
  $urledit = "teamredirect.php?fn=$study_url";
  
  $studyedit = "vipalertedit.php?patient_id=$patient_id";
  
  echo "<tr>";
  echo "<td valign=\"top\"><a href=\"$studyedit\">$patient_id</a><br>";
  echo "</td>";
  echo "<td valign=\"top\">$minutes_between<br>";
  echo "</td>";
  echo "<td valign=\"top\">$alert_email<br>";
  echo "</td>";
  echo "<td valign=\"top\">$last_email<br>";
  echo "</td>";
  
  echo "</tr>";
  $i++;
  }
?>
</tbody>
</table>
<br>
</body>
</html>