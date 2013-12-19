<?php
$teamtitle = "TEAM Studies";
include 'teamtop1.php';
include 'teamtop2.php';
?>

<table style="margin: auto;" border="0" cellpadding="2"
cellspacing="2" width="75%">
<tbody>
<tr>
<td valign="top"><b>Sponser</b><br>
</td>
<td valign="top"><b>Study ID</b><br>
</td>
<td valign="top"><b>Description</b><br>
</td>
<td valign="top"><b>Study Slots</b><br>
</td>
<td valign="top"><b>PR %</b><br>
</td>
<td valign="top"><b>PD %</b><br>
</td>

</tr>
<tr><td></td></tr>
<?php 
$query = "select study_teamid,study_id,study_owner,study_name,study_url,study_percentpr,study_percentpd,study_seats from studies where study_teamid = $user_teamid order by study_owner";

$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);             
$i=0;
while ($i < $num) {
  $study_id=mysql_result($dbresult,$i,"study_id");
  $study_owner=mysql_result($dbresult,$i,"study_owner");
  $study_name=mysql_result($dbresult,$i,"study_name");
  $study_url=mysql_result($dbresult,$i,"study_url");
  $study_percentpr=mysql_result($dbresult,$i,"study_percentpr");
  $study_percentpd=mysql_result($dbresult,$i,"study_percentpd");
  $study_seats=mysql_result($dbresult,$i,"study_seats");
  
  //$urledit = "http://$imagedoc_server/$study_url";
  $urledit = "teamredirect.php?fn=$study_url";
  
  $studyedit = "studyedit.php?study_id=$study_id";
  
  echo "<tr>";
  echo "<td valign=\"top\"><a href=\"$studyedit\">$study_owner</a><br>";
  echo "</td>";
  echo "<td valign=\"top\">$study_id<br>";
  echo "</td>";
  echo "<td valign=\"top\">$study_name<br>";
  echo "</td>";
  echo "<td valign=\"top\">$study_seats<br>";
  echo "</td>";
  echo "<td valign=\"top\">$study_percentpr<br>";
  echo "</td>";
  echo "<td valign=\"top\">$study_percentpd<br>";
  echo "</td>";
  if ($study_url == "n/a") {
  	echo "<td valign=\"top\">No Study Doc Online<br>";
  	echo "</td>";
  } else {
  	 echo "<td valign=\"top\"><a target=\"_blank\" href=\"$urledit\">Click to View Study Document</a><br>";
  	 echo "</td>";
  }
  
  echo "</tr>";
  $i++;
  }
$audit_type = 'l5';
//include 'auditlog.php';
?>
</tbody>
</table>
<br>
</body>
</html>