<?php
$teamtitle = "TEAM Patient Scans";
include 'teamtop1.php';
include 'teamtop2.php';
?>

<?php
$patient_id=$_GET['patient_id'];
$patient_name=$_GET['patient_name'];
$search=$_GET['search'];
$field = "patient_name";
?>
<table style="margin: auto;" border="0" cellpadding="2"
cellspacing="2" width="75%">
<tbody>
<tr>
<?php
echo "<td valign=\"top\" align=\"center\"><h2>Scan Dates for $patient_name ($patient_id)</h2>";
?>
</td></tr>
<tr><td>&nbsp;</td></tr>

<?php 
$query = "select scan_date,scan_report_online from scan where scan_teamid = $user_teamid and scan_patient_id = '$patient_id' order by scan_date DESC";             
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i=0;
while ($i < $num) {
  $scan_date=mysql_result($dbresult,$i,"scan_date");
  $scan_report_online=mysql_result($dbresult,$i,"scan_report_online");
  $web_date = date("l F d, Y",strtotime($scan_date));
  $urledit = "lesions.php?patient_id=$patient_id&scan_date=$scan_date&patient_name=$patient_name&scan_report_online=$scan_report_online";
  echo "<tr>";
  echo "<td valign=\"top\" align=\"center\"><a href=\"$urledit\">$web_date</a><br>";
  echo "</td>";
  echo "</tr>";
  $i++;
  }
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td valign=\"top\" align=\"center\">";
echo "<form action=\"scangetdate.php\" method=\"get\">";
echo "<input type=\"submit\" value=\"Add New Scan Date\">";
echo "<input type=\"hidden\" name=\"patient_id\" value=\"$patient_id\">";
echo "<input type=\"hidden\" name=\"patient_name\" value=\"$patient_name\">";
echo "</form></td></tr>";
$audit_type = 'l2';
include 'auditlog.php';
?>

</tbody>
</table>
<br>
</body>
</html>