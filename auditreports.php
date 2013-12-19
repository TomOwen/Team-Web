<?php
$teamtitle = "TEAM Audit Reports";
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
//print_r($_GET);
if ($search != 'y') { // set default from and to times
$fromdate = date("Y-m-d 00:00"); 
$todate = date("Y-m-d 23:59");
}
?>

<table style="margin: auto;" border="0" cellpadding="2" cellspacing="2" width="75%">
<tbody>
<form action="auditreports.php" method="get">
<tr>
<?php
echo "<td  align=center>From:&nbsp;<input type=\"edit\" name=\"fromdate\" id=\"fromdate\" readonly=\"readonly\" value=\"$fromdate\">";
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

//
// for report table
$i=0;
$query = "select ip_number, audit_user_name, audit_datetime, audit_type, patient_id, description  from reports where teamid = $user_teamid and audit_datetime >= '$fromdate' and audit_datetime <= '$todate'";
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