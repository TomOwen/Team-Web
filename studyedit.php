<?php
$teamtitle = "TEAM Study Edit";
include 'teamtop1.php';
?>
<script >
        function validateForm(f)
{
var adm = "<?php echo $user_admin_access; ?>";
if (adm=="N")
	{
	alert("TEAM Editing only available to users with edit option.");
  	return false;
	}
var message = '';
var x=document.forms["studyedit"]["study_name"].value;
if (x==null || x=="")
  {
  message = message + "Study name must be entered\n";
  }

x=document.forms["studyedit"]["study_owner"].value;
if (x==null || x=="")
  {
  message = message + "Study sponser must be entered\n";
  }
var mypr = document.forms["studyedit"]["study_percentpr"].value;
if (mypr != "") {
		if (isNaN(mypr) || mypr==null || mypr=="")
		{
		message = message + mypr + " is not a valid number for partial response\n";
		}
	}
var mypd = document.forms["studyedit"]["study_percentpd"].value;
if (mypd != "") {
		if (isNaN(mypd) || mypd==null || mypd=="")
		{
		message = message + mypd + " is not a valid number for progressive disease\n";
		}
	}
var seats = document.forms["studyedit"]["study_seats"].value;
if (seats != "") {
		if (isNaN(seats) || seats==null || seats=="")
		{
		message = message + seats + " is not a valid number for allocated seats\n";
		}
	}
if (message == "") {
   return true;
 } else {
   alert(message);
   return false;
 }
}
</script>
<?php
include 'teamtop2.php';
?>
<?php
$study_id = $_GET['study_id'];
// get study data
$query = "select study_teamid,study_id,study_owner,study_name,study_url,study_percentpr,study_percentpd,study_seats from studies where study_teamid = $user_teamid and study_id = '$study_id' ";
//echo "$query";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i = 0;
while ($i < $num) {
  $study_owner=mysql_result($dbresult,$i,"study_owner");
  $study_name=mysql_result($dbresult,$i,"study_name");
  $study_url=mysql_result($dbresult,$i,"study_url");
  $study_percentpr=mysql_result($dbresult,$i,"study_percentpr");
  $study_percentpd=mysql_result($dbresult,$i,"study_percentpd");
  $study_seats=mysql_result($dbresult,$i,"study_seats");
  $i++;
  }
?>
<table style="margin: auto;" border="1" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><br>

<form name="studyedit" method="Post"
action="studyedit2.php" onsubmit="return validateForm(this)">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody>
<tr>
<td>&nbsp;Study ID</td>
<?php
echo "<input type=\"hidden\" name=\"study_id\" value=\"$study_id\">";
echo "<td>$study_id";
?>
</td>
</tr>
<?php
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Sponser</td><td><input name=\"study_owner\" value=\"$study_owner\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Name</td><td><input name=\"study_name\" value=\"$study_name\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Study File Name</td><td><input name=\"study_url\" value=\"$study_url\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Percent Partial Response</td><td><input name=\"study_percentpr\" value=\"$study_percentpr\" size=\"10\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Percent Progressive Disease</td><td><input name=\"study_percentpd\" value=\"$study_percentpd\" size=\"10\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Number of Allocated Slots</td><td><input name=\"study_seats\" value=\"$study_seats\" size=\"10\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;</td></tr>";
?>
<tr><td ><input name="Submit" value="Update Study" type="submit"></td></tr>
</form>
<tr><td>&nbsp;</td></tr>
<tr><td>
<form action="studydelete.php" method="get" onsubmit="return confirm('You are about to delete this study.\n\nClick OK to proceed\n\nClick Cancel to Cancel');">
<input type="submit" value="Delete Study">
<?php
echo "<input type=\"hidden\" name=\"study_id\" value=\"$study_id\">";
$audit_type = 'v5';
include 'auditlog.php';
?>
</form></td><td>
<td valign="top" align="right">
</td>
</tr>

</tbody>
</table>


</td>
</tr>
</tbody>
</table>

<table style="margin: auto;" border="0" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr><td>&nbsp;</td><tr>
<?php
echo "<form action=\"http://$db_server/team/graphstudy.php\" target=\"studygraphs\" method=\"get\">";
echo "<tr><td align=center width=\"25%\"><input type=\"image\" src=\"bar.jpg\" height=48 width=75 alt=\"Submit button\"></td>";
echo "<input type=\"hidden\" name=\"study_id\" value=\"$study_id\">";
echo "<input type=\"hidden\" name=\"patient_name\" value=\"$patient_name\">";
echo "<input type=\"hidden\" name=\"user_name\" value=\"$user_name\">";
echo "<input type=\"hidden\" name=\"user_teamid\" value=\"$user_teamid\">";
?>
</form>
<?php
echo "<form action=\"http://$db_server/team/graphstudypie.php\" target=\"studygraphs\" method=\"get\">";
echo "<td align=center width=\"25%\"><input type=\"image\" src=\"pie.jpg\" height=58 width=75 alt=\"Submit button\"></td>";
echo "<input type=\"hidden\" name=\"study_id\" value=\"$study_id\">";
echo "<input type=\"hidden\" name=\"patient_name\" value=\"$patient_name\">";
echo "<input type=\"hidden\" name=\"user_name\" value=\"$user_name\">";
?>
</form>
</tr>
</tbody>
</table>

<table style="margin: auto;" border="0" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr><td align="center" width=50%>Patient Waterfall Bar Chart</td><td align="center" width=50%>Summary PIE Chart</td></tr>


</tbody>
</table>


<br>

</body>
</html>