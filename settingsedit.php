<?php
$teamtitle = "TEAM Server Settings";
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
var x=document.forms["settingsedit"]["company_name"].value;
if (x==null || x=="")
  {
  message = message + "Company name must be entered\n";
  }
x=document.forms["settingsedit"]["db_server"].value;
if (x==null || x=="")
  {
  message = message + "Data Base Server must be entered\n";
  }
x=document.forms["settingsedit"]["db_user"].value;
if (x==null || x=="")
  {
  message = message + "Data Base User must be entered\n";
  }
x=document.forms["settingsedit"]["db_password"].value;
if (x==null || x=="")
  {
  message = message + "Data Base Password must be entered\n";
  }
x=document.forms["settingsedit"]["imagedoc_server"].value;
if (x==null || x=="")
  {
  message = message + "Image/Document Server must be entered\n";
  }
x=document.forms["settingsedit"]["imagedoc_user"].value;
if (x==null || x=="")
  {
  message = message + "Image/Document User must be entered\n";
  }
x=document.forms["settingsedit"]["imagedoc_password"].value;
if (x==null || x=="")
  {
  message = message + "Image/Document Password must be entered\n";
  }
x=document.forms["settingsedit"]["image_type"].value;
if (x==null || x=="")
  {
  message = message + "Image type must be entered\n";
  }
x=document.forms["settingsedit"]["doc_type"].value;
if (x==null || x=="")
  {
  message = message + "Document type must be entered\n";
  }
x=document.forms["settingsedit"]["web_server"].value;
if (x==null || x=="")
  {
  message = message + "WEB Server must be entered\n";
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
// get settings data
$query = "select teamid,company_name,db_server,db_user,db_password,imagedoc_server,imagedoc_user,imagedoc_password,image_type,doc_type,web_server from settings where teamid = '$user_teamid'";
//echo "$query";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i = 0;
while ($i < $num) {
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
  $i++;
  }
?>

<table style="margin: auto;" border="1" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<tr>
<td valign="top"><br>

<form name="settingsedit" method="Post"
action="settingsedit2.php" onsubmit="return validateForm(this)">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody>
<?php
echo "<tr><td>&nbsp;Company Name</td><td><input name=\"company_name\" value=\"$company_name\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Data Base Server</td><td><input name=\"db_server\" value=\"$db_server\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Data Base User</td><td><input name=\"db_user\" value=\"$db_user\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Data Base Password</td><td><input name=\"db_password\" value=\"$db_password\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Image/Document Server</td><td><input name=\"imagedoc_server\" value=\"$imagedoc_server\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Image/Document User</td><td><input name=\"imagedoc_user\" value=\"$imagedoc_user\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Image/Document Password</td><td><input name=\"imagedoc_password\" value=\"$imagedoc_password\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;WEB Server URL</td><td><input name=\"web_server\" value=\"$web_server\" size=\"60\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Default Image Type</td><td><input name=\"image_type\" value=\"$image_type\" size=\"10\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;Default Documentation Type</td><td><input name=\"doc_type\" value=\"$doc_type\" size=\"10\" type=\"edit\"></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>";

$audit_type = 'v6';
include 'auditlog.php';
?>
<tr><td ><input name="Submit" value="Update TEAM Settings" type="submit"></td></tr>
</form>
<td valign="top" align="right">
</td>
</tr>
<tr><td>&nbsp;</td></tr>
</tbody>
</table>


</td>
</tr>
</tbody>
</table>
<br>

</body>
</html>