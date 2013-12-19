<?php
$teamtitle = "TEAM Audit Patient";
include 'teamtop1.php';
require '../db/dbauditaccess.php';

?>
        <script src="js/datetime/Javascript-DateTimePicker/Scripts/DateTimePicker.js" type="text/javascript"></script>
<?php
include 'teamaudit2.php';
?>
<?php
$search=$_GET['search'];
$fromdate=$_GET['fromdate'];
$todate=$_GET['todate'];
$patient_id=$_GET['patient_id'];
if ($search != 'y') { // set default from and to times
$fromdate = date("Y-m-d 00:00"); 
$todate = date("Y-m-d 23:59");
}
?>

<table style="margin: auto;" border="0" cellpadding="2" cellspacing="2" width="75%">
<tbody>
<form action="auditpatient.php" method="get">
<tr>
<td valign="top" align=center>
Patient ID:
<?php
echo "<input name=\"patient_id\"  size=10 type=\"edit\" value=\"$patient_id\">";
?>
&nbsp;&nbsp;&nbsp;From:&nbsp;
<?php
echo "<input type=\"edit\" name=\"fromdate\" id=\"fromdate\" readonly=\"readonly\" value=\"$fromdate\">";
?>
 <img src="js/datetime/Javascript-DateTimePicker/Image/cal.gif" style="cursor: pointer;" onclick="javascript:NewCssCal('fromdate','yyyyMMdd','dropdown',true,'24')" />
&nbsp;&nbsp;&nbsp;To:&nbsp;
<?php
echo "<input type=\"edit\" name=\"todate\" id=\"todate\" readonly=\"readonly\" value=\"$todate\">";
?>
 <img src="js/datetime/Javascript-DateTimePicker/Image/cal.gif" style="cursor: pointer;" onclick="javascript:NewCssCal('todate','yyyyMMdd','dropdown',true,'24')" />&nbsp;&nbsp;<a href="patients.php">Back To TEAM</a>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr><td align=center>
<input type="submit" value="Get Log Entries">
<input type="hidden" name="search" value="y"> 
</form>
</td></tr>
<tr><td>&nbsp;</td></tr>
</tbody>
</table>
<?php
if ($search != 'y') {
echo "</body></html>";
exit();
}
//echo "Processing $patient_id, from $fromdate, to $todate";
// create temporary table
$query = "CREATE TEMPORARY TABLE IF NOT EXISTS `tempdata` (
  `ip_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `audit_user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `audit_datetime` datetime NOT NULL,
  `audit_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  KEY `audit_datetime` (`audit_datetime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$dbresult = mysql_query($query, $dbauditconnect);
// for each table
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, patient_teamid, patient_id, patient_name, patient_study_id ,patient_doctor  from patient where patient_teamid = $user_teamid and patient_id = '$patient_id' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
//echo "got $num rows";
while ($i < $num) {
  $ip_number=mysql_result($dbresult,$i,"ip_number");
  $audit_user_name=mysql_result($dbresult,$i,"audit_user_name");
  $audit_datetime=mysql_result($dbresult,$i,"audit_datetime");
  $audit_type=mysql_result($dbresult,$i,"audit_type");
  $patient_id=mysql_result($dbresult,$i,"patient_id");
  $patient_name=mysql_result($dbresult,$i,"patient_name");
  $patient_study_id=mysql_result($dbresult,$i,"patient_study_id");
  $patient_doctor=mysql_result($dbresult,$i,"patient_doctor");
  $action = "$audit_type,$patient_id,$patient_name,$patient_study_id,$patient_doctor";
  // v/a/d/m
  if ($audit_type == 'v1') $action = "Viewed Patient - $patient_name/$patient_doctor/$patient_study_id";
  if ($audit_type == 'a1') $action = "Added Patient - $patient_name/$patient_doctor/$patient_study_id";
  if ($audit_type == 'm1') $action = "Changed Patient - $patient_name/$patient_doctor/$patient_study_id";
  if ($audit_type == 'd1') $action = "Deleted Patient - $patient_id";
  $tempquery = "INSERT INTO tempdata (ip_number, audit_user_name, audit_datetime, audit_type, action) VALUES ('$ip_number', '$audit_user_name', '$audit_datetime', '$audit_type', '$action')";
  $dbresult2 = mysql_query($tempquery, $dbauditconnect);
  //$num2=mysql_numrows($dbresult2);
  $i++;
}
//
// for lesion table
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, lesion_teamid, lesion_patient_id, lesion_scan_date, lesion_number, lesion_size, lesion_comment, lesion_target, lesion_media_type, lesion_media_online, lesion_node  from lesion where lesion_teamid = $user_teamid and lesion_patient_id = '$patient_id' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
//echo "got $num rows";
while ($i < $num) {
  $ip_number=mysql_result($dbresult,$i,"ip_number");
  $audit_user_name=mysql_result($dbresult,$i,"audit_user_name");
  $audit_datetime=mysql_result($dbresult,$i,"audit_datetime");
  $audit_type=mysql_result($dbresult,$i,"audit_type");
  $lesion_patient_id=mysql_result($dbresult,$i,"lesion_patient_id");
  $lesion_scan_date=mysql_result($dbresult,$i,"lesion_scan_date");
  $lesion_number=mysql_result($dbresult,$i,"lesion_number");
  $lesion_comment=mysql_result($dbresult,$i,"lesion_comment");
  $lesion_media_type=mysql_result($dbresult,$i,"lesion_media_type");
  $lesion_media_online=mysql_result($dbresult,$i,"lesion_media_online");
  $lesion_node=mysql_result($dbresult,$i,"lesion_node");
  $web_datescan = date("m/d/y",strtotime($lesion_scan_date));
  $action = "not available";
  // v/a/d/m
  if ($audit_type == 'v3') $action = "Viewed Lesion#$lesion_number ($web_datescan), type=$lesion_media_type, online=$lesion_media_online, node=$lesion_node, comment= $lesion_comment";
  if ($audit_type == 'a3') $action = "Added Lesion#$lesion_number, ($web_datescan), type=$lesion_media_type, online=$lesion_media_online, node=$lesion_node, comment= $lesion_comment";
  if ($audit_type == 'm3') $action = "Changed Lesion#$lesion_number, ($web_datescan), type=$lesion_media_type, online=$lesion_media_online, node=$lesion_node, comment= $lesion_comment";
  if ($audit_type == 'd3') $action = "Deleted Lesion#$lesion_number, from $web_datescan";
  
  $tempquery = "INSERT INTO tempdata (ip_number, audit_user_name, audit_datetime, audit_type, action) VALUES ('$ip_number', '$audit_user_name', '$audit_datetime', '$audit_type', '$action')";
  //echo "tempquery=$tempquery<br><br>";
  $dbresult2 = mysql_query($tempquery, $dbauditconnect);
  //$num2=mysql_numrows($dbresult2);
  $i++;
}
//
// for scan table
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, scan_date, scan_report_online  from scan where scan_teamid = $user_teamid and scan_patient_id = '$patient_id' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
//echo "got $num rows";
while ($i < $num) {
  $ip_number=mysql_result($dbresult,$i,"ip_number");
  $audit_user_name=mysql_result($dbresult,$i,"audit_user_name");
  $audit_datetime=mysql_result($dbresult,$i,"audit_datetime");
  $audit_type=mysql_result($dbresult,$i,"audit_type");
  $scan_date=mysql_result($dbresult,$i,"scan_date");
  $scan_report_online=mysql_result($dbresult,$i,"scan_report_online");
  $web_datescan = date("m/d/y",strtotime($scan_date));
  $action = "not available";
  // v/a/d/m/l
  if ($audit_type == 'v2') $action = "Viewed Scan $web_datescan, Report online=$scan_report_online";
  if ($audit_type == 'a2') $action = "Added Scan $web_datescan, Report online=$scan_report_online";
  if ($audit_type == 'm2') $action = "Changed Scan $web_datescan, Report online=$scan_report_online";
  if ($audit_type == 'd2') $action = "Deleted Scan $web_datescan";
  if ($audit_type == 'l2') $action = "Listed all scan dates";
  
  $tempquery = "INSERT INTO tempdata (ip_number, audit_user_name, audit_datetime, audit_type, action) VALUES ('$ip_number', '$audit_user_name', '$audit_datetime', '$audit_type', '$action')";
  //echo "tempquery=$tempquery<br><br>";
  $dbresult2 = mysql_query($tempquery, $dbauditconnect);
  //$num2=mysql_numrows($dbresult2);
  $i++;
}
//
// for report table
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, description  from reports where teamid = $user_teamid and patient_id = '$patient_id' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
//echo "got $num rows";
while ($i < $num) {
  $ip_number=mysql_result($dbresult,$i,"ip_number");
  $audit_user_name=mysql_result($dbresult,$i,"audit_user_name");
  $audit_datetime=mysql_result($dbresult,$i,"audit_datetime");
  $audit_type=mysql_result($dbresult,$i,"audit_type");
  $description=mysql_result($dbresult,$i,"description");
  $action = $description;

  
  $tempquery = "INSERT INTO tempdata (ip_number, audit_user_name, audit_datetime, audit_type, action) VALUES ('$ip_number', '$audit_user_name', '$audit_datetime', '$audit_type', '$action')";
  //echo "tempquery=$tempquery<br><br>";
  $dbresult2 = mysql_query($tempquery, $dbauditconnect);
  //$num2=mysql_numrows($dbresult2);
  $i++;
}
//
// now retrieve all the actions sorted for display
?>
<table style="margin: auto;" border="0" cellpadding="2"
cellspacing="2" width="60%">
<tbody>
<tr>
<td valign="top">IP Address<br>
</td>
<td valign="top">User<br>
</td>
<td valign="top">Date/Time<br>
</td>
<td valign="top">Action<br>
</td>

</tr>

<?php
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, action from tempdata order by audit_datetime ";
$dbresult3 = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult3);
while ($i < $num) {
  $ip_number=mysql_result($dbresult3,$i,"ip_number");
  $audit_user_name=mysql_result($dbresult3,$i,"audit_user_name");
  $audit_datetime=mysql_result($dbresult3,$i,"audit_datetime");
  $web_date = date("m/d H:i",strtotime($audit_datetime));
  $audit_type=mysql_result($dbresult3,$i,"audit_type");
  $action=mysql_result($dbresult3,$i,"action");
  //echo "working.....$ip_number,$audit_user_name,$audit_datetime,$action<br>";
  echo "<tr>";
  echo "<td valign=\"top\">$ip_number&nbsp;<br>";
  echo "</td>";
  echo "<td valign=\"top\">$audit_user_name&nbsp;<br>";
  echo "</td>";
  echo "<td nowrap valign=\"top\">$web_date&nbsp;<br>";
  echo "</td>";
  echo "<td valign=\"top\">$action<br>";
  echo "</td>";
  
  echo "</tr>";
  $i++;
}
?>
</tbody>
</table>
</body>
</html>