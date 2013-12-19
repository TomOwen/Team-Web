<?php
$teamtitle = "TEAM Audit User";
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
$user=$_GET['user'];
if ($search != 'y') { // set default from and to times
$fromdate = date("Y-m-d 00:00"); 
$todate = date("Y-m-d 23:59");
}
?>

<table style="margin: auto;" border="0" cellpadding="2" cellspacing="2" width="75%">
<tbody>
<form action="audituser.php" method="get">
<tr>
<td valign="top" align=center>
TEAM User:
<?php
echo "<input name=\"user\"  size=10 type=\"edit\" value=\"$user\">";
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
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, patient_teamid, patient_id, patient_name, patient_study_id ,patient_doctor  from patient where patient_teamid = $user_teamid and audit_user_name = '$user' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
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
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, lesion_teamid, lesion_patient_id, lesion_scan_date, lesion_number, lesion_size, lesion_comment, lesion_target, lesion_media_type, lesion_media_online, lesion_node  from lesion where lesion_teamid = $user_teamid and audit_user_name = '$user' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
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
  if ($audit_type == 'v3') $action = "Viewed Patient $lesion_patient_id, Lesion#$lesion_number ($web_datescan), type=$lesion_media_type, online=$lesion_media_online, node=$lesion_node, comment= $lesion_comment";
  if ($audit_type == 'a3') $action = "Added Lesion#$lesion_number, Patient $lesion_patient_id, for ($web_datescan), type=$lesion_media_type, online=$lesion_media_online, node=$lesion_node, comment= $lesion_comment";
  if ($audit_type == 'm3') $action = "Changed Lesion#$lesion_number, Patient $lesion_patient_id, for ($web_datescan), type=$lesion_media_type, online=$lesion_media_online, node=$lesion_node, comment= $lesion_comment";
  if ($audit_type == 'd3') $action = "Deleted Lesion#$lesion_number, Patient $lesion_patient_id, from $web_datescan";
  
  $tempquery = "INSERT INTO tempdata (ip_number, audit_user_name, audit_datetime, audit_type, action) VALUES ('$ip_number', '$audit_user_name', '$audit_datetime', '$audit_type', '$action')";
  //echo "tempquery=$tempquery<br><br>";
  $dbresult2 = mysql_query($tempquery, $dbauditconnect);
  //$num2=mysql_numrows($dbresult2);
  $i++;
}
//
// for scan table
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, scan_patient_id, scan_date, scan_report_online  from scan where scan_teamid = $user_teamid and audit_user_name = '$user' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
while ($i < $num) {
  $ip_number=mysql_result($dbresult,$i,"ip_number");
  $audit_user_name=mysql_result($dbresult,$i,"audit_user_name");
  $audit_datetime=mysql_result($dbresult,$i,"audit_datetime");
  $audit_type=mysql_result($dbresult,$i,"audit_type");
  $scan_patient_id=mysql_result($dbresult,$i,"scan_patient_id");
  $scan_date=mysql_result($dbresult,$i,"scan_date");
  $scan_report_online=mysql_result($dbresult,$i,"scan_report_online");
  $web_datescan = date("m/d/y",strtotime($scan_date));
  $action = "not available";
  // v/a/d/m/l
  if ($audit_type == 'v2') $action = "Viewed Patient $scan_patient_id Scan $web_datescan, Report online=$scan_report_online";
  if ($audit_type == 'a2') $action = "Added a Scan for Patient $scan_patient_id ($web_datescan), Report online=$scan_report_online";
  if ($audit_type == 'm2') $action = "Changed a Scan for Patient $scan_patient_id ($web_datescan), Report online=$scan_report_online";
  if ($audit_type == 'd2') $action = "Deleted a Scan for Patient $scan_patient_id ($web_datescan)";
  if ($audit_type == 'l2') $action = "Listed all scan dates for Patient $scan_patient_id";
  
  $tempquery = "INSERT INTO tempdata (ip_number, audit_user_name, audit_datetime, audit_type, action) VALUES ('$ip_number', '$audit_user_name', '$audit_datetime', '$audit_type', '$action')";
  //echo "tempquery=$tempquery<br><br>";
  $dbresult2 = mysql_query($tempquery, $dbauditconnect);
  //$num2=mysql_numrows($dbresult2);
  $i++;
}
//
// for report table
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, patient_id, description  from reports where teamid = $user_teamid and audit_user_name = '$user' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
while ($i < $num) {
  $ip_number=mysql_result($dbresult,$i,"ip_number");
  $audit_user_name=mysql_result($dbresult,$i,"audit_user_name");
  $audit_datetime=mysql_result($dbresult,$i,"audit_datetime");
  $audit_type=mysql_result($dbresult,$i,"audit_type");
  $patient_id=mysql_result($dbresult,$i,"patient_id");
  $description=mysql_result($dbresult,$i,"description");
  $action = $description;

  
  $tempquery = "INSERT INTO tempdata (ip_number, audit_user_name, audit_datetime, audit_type, action) VALUES ('$ip_number', '$audit_user_name', '$audit_datetime', '$audit_type', '$action')";
  //echo "tempquery=$tempquery<br><br>";
  $dbresult2 = mysql_query($tempquery, $dbauditconnect);
  //$num2=mysql_numrows($dbresult2);
  $i++;
}
//
// for doctor table
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, doctor_name, doctor_info  from doctor where doctor_teamid = $user_teamid and audit_user_name = '$user' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
while ($i < $num) {
  $ip_number=mysql_result($dbresult,$i,"ip_number");
  $audit_user_name=mysql_result($dbresult,$i,"audit_user_name");
  $audit_datetime=mysql_result($dbresult,$i,"audit_datetime");
  $audit_type=mysql_result($dbresult,$i,"audit_type");
  $doctor_name=mysql_result($dbresult,$i,"doctor_name");
  $doctor_info=mysql_result($dbresult,$i,"doctor_info");
  
  // v d m a 
  if ($audit_type == 'v4') $action = "Viewed Doctor $doctor_name, info=$doctor_info";
  if ($audit_type == 'd4') $action = "Deleted Doctor $doctor_name";
  if ($audit_type == 'm4') $action = "Changed Doctor $doctor_name, info=$doctor_info";
  if ($audit_type == 'a4') $action = "Added Doctor $doctor_name, info=$doctor_info";

  
  $tempquery = "INSERT INTO tempdata (ip_number, audit_user_name, audit_datetime, audit_type, action) VALUES ('$ip_number', '$audit_user_name', '$audit_datetime', '$audit_type', '$action')";
  //echo "tempquery=$tempquery<br><br>";
  $dbresult2 = mysql_query($tempquery, $dbauditconnect);
  //$num2=mysql_numrows($dbresult2);
  $i++;
}
//
// for studies table
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, study_id, study_owner, study_name, study_url, study_percentpr, study_percentpd, study_seats from studies where study_teamid = $user_teamid and audit_user_name = '$user' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
while ($i < $num) {
  $ip_number=mysql_result($dbresult,$i,"ip_number");
  $audit_user_name=mysql_result($dbresult,$i,"audit_user_name");
  $audit_datetime=mysql_result($dbresult,$i,"audit_datetime");
  $audit_type=mysql_result($dbresult,$i,"audit_type");
  $study_id=mysql_result($dbresult,$i,"study_id");
  $study_owner=mysql_result($dbresult,$i,"study_owner");
  $study_name=mysql_result($dbresult,$i,"study_name");
  $study_url=mysql_result($dbresult,$i,"study_url");
  $study_percentpr=mysql_result($dbresult,$i,"study_percentpr");
  $study_percentpd=mysql_result($dbresult,$i,"study_percentpd");
  $study_seats=mysql_result($dbresult,$i,"study_seats");
 
  
  // v d m a
  if ($audit_type == 'v5') $action = "Viewed Study $study_id, Sponser=$study_owner,n=$study_name,fn=$study_url,pr=$study_percentpr,pd=$study_percentpd,seats=$study_seats";
  if ($audit_type == 'd5') $action = "Deleted Study $study_id";
  if ($audit_type == 'm5') $action = "Changed Study $study_id, Sponser=$study_owner,n=$study_name,fn=$study_url,pr=$study_percentpr,pd=$study_percentpd,seats=$study_seats";
  if ($audit_type == 'a5') $action = "Added Study $study_id, Sponser=$study_owner,n=$study_name,fn=$study_url,pr=$study_percentpr,pd=$study_percentpd,seats=$study_seats";

  
  $tempquery = "INSERT INTO tempdata (ip_number, audit_user_name, audit_datetime, audit_type, action) VALUES ('$ip_number', '$audit_user_name', '$audit_datetime', '$audit_type', '$action')";
  //echo "tempquery=$tempquery<br><br>";
  $dbresult2 = mysql_query($tempquery, $dbauditconnect);
  //$num2=mysql_numrows($dbresult2);
  $i++;
}
// for settings table
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, teamid, company_name, db_server, db_user, db_password, imagedoc_server, imagedoc_user, imagedoc_password, image_type, doc_type, web_server from settings where teamid = $user_teamid and audit_user_name = '$user' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
while ($i < $num) {
  $ip_number=mysql_result($dbresult,$i,"ip_number");
  $audit_user_name=mysql_result($dbresult,$i,"audit_user_name");
  $audit_datetime=mysql_result($dbresult,$i,"audit_datetime");
  $audit_type=mysql_result($dbresult,$i,"audit_type");
  $company_name=mysql_result($dbresult,$i,"company_name");
  $db_server=mysql_result($dbresult,$i,"db_server");
  $db_user=mysql_result($dbresult,$i,"db_user");
  $db_password=mysql_result($dbresult,$i,"db_password");
  $imagedoc_server=mysql_result($dbresult,$i,"imagedoc_server");
  $imagedoc_user=mysql_result($dbresult,$i,"imagedoc_user");
  $imagedoc_password=mysql_result($dbresult,$i,"imagedoc_password");
  $image_type=mysql_result($dbresult,$i,"image_type");
  $doc_type=mysql_result($dbresult,$i,"doc_type");
  $web_server=mysql_result($dbresult,$i,"web_server");
 
  
  // v  m 
  if ($audit_type == 'v6') $action = "Viewed Settings - cn=$company_name, dbs=$db_server, dbu=$db_user, dpp=$db_password, ids=$imagedoc_server, idu=$imagedoc_user, idp=$imagedoc_password, it=$image_type, dt=$doc_type, ws=$web_server";
  if ($audit_type == 'm6') $action = "Update Settings - cn=$company_name, dbs=$db_server, dbu=$db_user, dpp=$db_password, ids=$imagedoc_server, idu=$imagedoc_user, idp=$imagedoc_password, it=$image_type, dt=$doc_type, ws=$web_server";
  

  
  $tempquery = "INSERT INTO tempdata (ip_number, audit_user_name, audit_datetime, audit_type, action) VALUES ('$ip_number', '$audit_user_name', '$audit_datetime', '$audit_type', '$action')";
  //echo "tempquery=$tempquery<br><br>";
  $dbresult2 = mysql_query($tempquery, $dbauditconnect);
  //$num2=mysql_numrows($dbresult2);
  $i++;
}
// for users table
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, user_name, user_password, user_email, user_admin_access from users where user_teamid = $user_teamid and audit_user_name = '$user' and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
//echo "$query";
$dbresult = mysql_query($query, $dbauditconnect);
$num=mysql_numrows($dbresult);
while ($i < $num) {
  $ip_number=mysql_result($dbresult,$i,"ip_number");
  $audit_user_name=mysql_result($dbresult,$i,"audit_user_name");
  $audit_datetime=mysql_result($dbresult,$i,"audit_datetime");
  $audit_type=mysql_result($dbresult,$i,"audit_type");
  $user_name=mysql_result($dbresult,$i,"user_name");
  $user_password=mysql_result($dbresult,$i,"user_password");
  $user_email=mysql_result($dbresult,$i,"user_email");
  $user_admin_access=mysql_result($dbresult,$i,"user_admin_access");
 
  
  // a l v d m
  if ($audit_type == 'a7') $action = "Added User $user_name, pw=$user_password, email=$user_email, edit=$user_admin_access";
  if ($audit_type == 'm7') $action = "Changed User $user_name, pw=$user_password, email=$user_email, edit=$user_admin_access";
  if ($audit_type == 'v7') $action = "Viewed User $user_name, pw=$user_password, email=$user_email, edit=$user_admin_access";
  if ($audit_type == 'l7') $action = "Listed all Current Users";
  if ($audit_type == 'd7') $action = "Deleted User $user_name";
  if ($audit_type == 'g7') $action = "Logged ON to TEAM";
  if ($audit_type == 'p7') $action = "User Changed Password";
  if ($audit_type == 'r7') $action = "User Reset Password";
  
  

  
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