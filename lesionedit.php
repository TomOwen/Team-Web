<?php
require '../db/dbaccess.php';
session_start();
if(!session_is_registered(team1)){
header("location:index.php");
}
$storedVars = $_SESSION['team1'];
$user_name = $storedVars[1];
$user_teamid = $storedVars[2];
$user_admin_access = $storedVars[3];
$imagedoc_server = $storedVars[4];
$doc_type = $storedVars[5];
$patient_id = $_GET['patient_id'];
$patient_name = $_GET['patient_name'];
$scan_date = $_GET['scan_date'];
$lesion_number = $_GET['lesion_number'];


?>
<html>
    <head>
        <title>TEAM Lesion Detail</title>
<?php

include 'menu.php';
?>
        <script type="text/javascript">
        function validateForm()
{
var adm = "<?php echo $user_admin_access; ?>";
if (adm=="N")
	{
	alert("TEAM Editing only available to users with edit option.");
  	return false;
	}
if (x.indexOf(' ') >= 0)
  {
  alert("Patient ID must not have any spaces in the ID");
  return false;
  }
var x=document.forms["patientedit"]["patient_name"].value;
if (x==null || x=="")
  {
  alert("Patient Name must be entered");
  return false;
  }
}
</script>
<table style="margin: auto;" border="0" cellpadding="2" cellspacing="2" width="50%">
<tbody>
<form name="lesionedit" method="Post"
action="lesionedit2.php" onsubmit="return validateForm()">
<?php
echo "<input type=\"hidden\" name=\"patient_id\" value=\"$patient_id\">";
echo "<input type=\"hidden\" name=\"patient_name\" value=\"$patient_name\">";
$query = "select lesion_teamid,lesion_number,lesion_size,lesion_comment,lesion_target,lesion_media_type,lesion_media_online,lesion_node from lesion where lesion_teamid = $user_teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$scan_date' and lesion_number = $lesion_number";

//echo "$query";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i = 0;
while ($i < $num) {
  $lesion_size=mysql_result($dbresult,$i,"lesion_size");
  $lesion_comment=mysql_result($dbresult,$i,"lesion_comment");
  $lesion_target=mysql_result($dbresult,$i,"lesion_target");
  $lesion_media_type=mysql_result($dbresult,$i,"lesion_media_type");
  $lesion_media_online=mysql_result($dbresult,$i,"lesion_media_online");
  $lesion_node=mysql_result($dbresult,$i,"lesion_node");
 

echo "<tr><td valign=\"top\">$patient_name<br></td>";
echo "<td valign=\"top\">$scan_date<br></td></tr>";

echo "<tr><td valign=\"top\">Lesion #<br></td>";
echo "<td valign=\"top\">$lesion_number<br></td></tr>";

echo "<tr><td valign=\"top\">Description<br></td>";
echo "<td valign=\"top\">$lesion_comment<br></td></tr>";

echo "<tr><td valign=\"top\">Size in MM<br></td>";
echo "<td valign=\"top\">$lesion_size<br></td></tr>";

echo "<tr><td valign=\"top\">Target Lesion<br></td>";
echo "<td valign=\"top\">$lesion_target<br></td></tr>";

echo "<tr><td valign=\"top\">Lymph Node<br></td>";
echo "<td valign=\"top\">$lesion_node<br></td></tr>";

echo "<tr><td valign=\"top\">Image Online<br></td>";
echo "<td valign=\"top\">$lesion_media_online<br></td></tr>";

echo "<tr><td valign=\"top\">Image File Type<br></td>";
echo "<td valign=\"top\">$lesion_media_type<br></td></tr>";

echo "<tr><td valign=\"top\"><br></td>";
echo "<td valign=\"top\"><br></td></tr>";


$i++;
}
echo "<tr><td valign=\"top\"><input type=\"submit\" value=\"Submit Lesion Changes\"><br></td>";
$newdate = date("mdy",strtotime($scan_date));
//lesionedit.php?patient_id=100&scan_date=2011-12-08&lesion_number=2&patient_name=Reed, Catherine
$fn = "$patient_id-$newdate-$lesion_number.$lesion_media_type";
$myurl = "lesionview.php?fn=$fn&patient_id=$patient_id&scan_date=$scan_date&lesion_number=$lesion_number&patient_name=$patient_name";
if ($lesion_media_online == 'Y') {
echo "<td valign=\"top\"><a href=\"$myurl\">View Image</a><br><br></td></tr>";
} else {
echo "<td valign=\"top\">No Image to View<br><br></td></tr>";
}
?>
</form>
</tbody>
</table>
<?php
// insert form
?>

</body>
</html>