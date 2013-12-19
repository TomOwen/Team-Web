<?php
$teamtitle = "TEAM Patients";
include 'teamtop1.php';
// add javascript here
include 'teamtop2.php';

// after header displayed and session validated
$sort=$_GET['s'];
if ($sort == "") $sort = "n";
$sort = stripslashes($sort);
$sort = mysql_real_escape_string($sort);

$list=$_GET['list'];
$list = stripslashes($list);
$list = mysql_real_escape_string($list);

$search=$_GET['search'];
$search = stripslashes($search);
$search = mysql_real_escape_string($search);

$limitnumber = 25;
//check if the starting row variable was passed in the URL or not
if (!isset($_GET['startrow']) or !is_numeric($_GET['startrow'])) {
  //we give the value of the starting row to 0 because nothing was found in URL
  $startrow = 0;
//otherwise we take the value from the URL
} else {
  $startrow = (int)$_GET['startrow'];
}
// 
$star_patient = $star_study = $star_doctor = $star_id = "2";
if ($sort == 'n') $star_patient = "1";
if ($sort == 's') $star_study = "1";
if ($sort == 'd') $star_doctor = "1";
if ($sort == 'i') $star_id = "1";
$limit = "limit $startrow, $limitnumber";
if ($list == 'all') $limit = "";

$field = "patient_name";
if ($sort == 's') $field = "patient_study_id, patient_name";
if ($sort == 'd') $field = "patient_doctor, patient_name";
if ($sort == 'i') $field = "patient_id, patient_name";
$whereclause = "";
if ($sort == 'x') $query = "select patient_name, patient_id, patient_study_id, patient_doctor  from patient where ( (patient_teamid = $user_teamid) and ( (patient_id like '%$search%') or (patient_name like '%$search%')or (patient_doctor like '%$search%') or (patient_study_id like '%$search%') ) ) order by patient_name $limit"; 
// let get all the patients
if ($sort != 'x') $query = "select patient_name, patient_id, patient_study_id, patient_doctor  from patient where patient_teamid = $user_teamid order by $field $limit";
//echo "query=$query";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
?>



<table style="margin: auto;" border="0" cellpadding="2" cellspacing="2" width="60%">
<tbody>
<tr align=center>
<td valign="top" align="right">
&nbsp;</td><td>
<td valign="top" align="right">
&nbsp;</td><td>
<td valign="top" align="right">
&nbsp;&nbsp;&nbsp;</td><td valign="top" align="right">
<form action="patients.php" method="get">
<input type="submit" value="Search Patients">
<?php
echo "<input type=\"text\" name=\"search\" id=\"search\" size=\"10\" value=\"$search\">";
?>
<input type="hidden" name="s" value="x">
</form>
</td>

<td valign="top">
<?php
if ($list != 'all') {
$newstart = $startrow + $limitnumber;
echo "&nbsp;&nbsp;<a href=\"patients.php?list=$list&s=$sort&startrow=$newstart&search=$search\">Next $limitnumber</a>";
$prev = $startrow - $limitnumber;
if ($prev >= 0) echo "&nbsp;&nbsp;<a href=\"patients.php?list=$list&s=$sort&startrow=$prev&search=$search\">Previous $limitnumber</a>";
}
?>
</td>

</tr>
<tr><td>&nbsp;</td></tr>
</tbody>
</table>


</div> 

<table style="margin: auto;" border="0" cellpadding="2"
cellspacing="2" width="60%">
<tbody>

<tr>

<td valign="top">
<form action="patients.php" method="get">
<?php
echo "<INPUT TYPE=\"image\" SRC=\"sortp$star_patient.png\" HEIGHT=\"34\" WIDTH=\"139\" BORDER=\"0\" ALT=\"Submit Form\">";
?>
<input type="hidden" name="s" value="n">
</form></td>
</td>

<?php
echo "<td  valign=\"top\"><form action=\"patients.php\" method=\"get\">";
echo "<INPUT TYPE=\"image\" SRC=\"sorti$star_id.png\" HEIGHT=\"34\" WIDTH=\"139\" BORDER=\"0\" ALT=\"Submit Form\">";
?>
<input type="hidden" name="s" value="i">
</form>
</td>

<?php
echo "<td  valign=\"top\"><form action=\"patients.php\" method=\"get\">";
echo "<INPUT TYPE=\"image\" SRC=\"sorts$star_study.png\" HEIGHT=\"34\" WIDTH=\"139\" BORDER=\"0\" ALT=\"Submit Form\">";
?>
<input type="hidden" name="s" value="s">
</form>
</td>
<?php
echo "<td  valign=\"top\"><form action=\"patients.php\" method=\"get\">";
echo "<INPUT TYPE=\"image\" SRC=\"sortd$star_doctor.png\" HEIGHT=\"34\" WIDTH=\"139\" BORDER=\"0\" ALT=\"Submit Form\">";
?>
<input type="hidden" name="s" value="d">
</form>
</td>

</tr>
<tr><td>&nbsp;</td></tr>
<?php 
$sid = session_id();             
$i=0;
while ($i < $num) {
  $patient_name=mysql_result($dbresult,$i,"patient_name");
  $patient_id=mysql_result($dbresult,$i,"patient_id");
  $patient_study_id=mysql_result($dbresult,$i,"patient_study_id");
  $patient_doctor=mysql_result($dbresult,$i,"patient_doctor");
  $urledit = "patientedit.php?patient_id=$patient_id&sid=$sid";
  echo "<tr>";
  echo "<td valign=\"top\">&nbsp;&nbsp;<a href=\"$urledit\">$patient_name</a><br>";
  echo "</td>";
  echo "<td valign=\"top\">&nbsp;&nbsp;$patient_id<br>";
  echo "</td>";
  echo "<td valign=\"top\">&nbsp;&nbsp;$patient_study_id<br>";
  echo "</td>";
  echo "<td valign=\"top\">&nbsp;&nbsp;$patient_doctor<br>";
  echo "</td>";
  
  echo "</tr>";
  $i++;
  }
// check for next/prev links
if ($list != 'all') {
$newstart = $startrow + $limitnumber;
echo "<tr><td>&nbsp;&nbsp;</td><td></td><td></td><td></td></tr>\n";
echo "<tr><td valign=\"top\">";
echo "&nbsp;&nbsp;<a href=\"patients.php?list=$list&s=$sort&startrow=$newstart&search=$search\">Next $limitnumber</a>";
$prev = $startrow - $limitnumber;
if ($prev >= 0) echo "&nbsp;&nbsp;<a href=\"patients.php?list=$list&s=$sort&startrow=$prev&search=$search\">Previous $limitnumber</a>";
echo "</td><td</td><td</td><td</td></tr>";
}


$audit_type = 'l1';
//include 'auditlog.php';

?>
</tbody>
</table>
<br>
</body>
</html>