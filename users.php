<?php
$teamtitle = "TEAM Users";
include 'teamtop1.php';
include 'teamtop2.php';
?>

<table style="margin: auto;" border="0" cellpadding="2"
cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><b>Login Name</b><br>
</td>
<td valign="top"><b>Email Address</b><br>
</td>
<td align=center valign="top"><b>TEAM Edit Authorization</b><br>
</td>

</tr>
<tr><td></td></tr>
<?php
$query = "select user_teamid,user_name,user_password,user_email,user_admin_access from users where user_teamid = '$user_teamid' order by user_name";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  $user_name2=mysql_result($dbresult,$i,"user_name");
  $user_password=mysql_result($dbresult,$i,"user_password");
  $user_email=mysql_result($dbresult,$i,"user_email");
  $user_admin_access=mysql_result($dbresult,$i,"user_admin_access");
  
  $urledit = "useredit.php?user_name=$user_name2";
  echo "<tr>";
  echo "<td valign=\"top\"><a href=\"$urledit\">$user_name2</a><br>";
  
  echo "</td>";
  echo "<td valign=\"top\">$user_email<br>";
  echo "</td>";
  if ($user_admin_access == 'Y') { $chk = "checked=on"; } else { $chk = ""; }
  echo "<td align=center><input type=\"checkbox\" name=\"user_admin_access\" value=\"Y\" $chk>Allow Editing</td>\n";
  echo "</tr>";
  $i++;
  }
$audit_type = 'l7';
include 'auditlog.php';

?>
</tbody>
</table>
<br>
</body>
</html>