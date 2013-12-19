<?php
$teamtitle = "TEAM Patient Scan Details";
include 'teamtop1.php';
?>

<?php
$patient_id = $_GET['patient_id'];
$scan_date = $_GET['scan_date'];
$patient_name = $_GET['patient_name'];
$scan_report_online = $_GET['scan_report_online'];
$updated = $_GET['updated'];
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
//var num = f['lesion_number[]'].length;
//alert ("number of lesions=" + num);
var num = f['numoflesions'].value;
//alert ("numoflesions=" + num);
var num_add = num; // used to get to the add line
var lesion_number;
var message = "";
var i = 0;
while (num > 0)
	{
	lesion_size = f['lesion_size[]'][i].value;
        if (f['lesion_number[]'][i].value != "") {
		if (isNaN(lesion_size) || lesion_size==null || lesion_size=="")
		{
		message = message + "Lesion #" + f['lesion_number[]'][i].value + " Size " + lesion_size + " is not a valid number\n";
		}
	}
    num--;
    i++;
    }

// now check the add line for valid data
// next line is crashing when no lesions
// if the add line is the only entry (i.e. num = 0) then access values with no index (ie. f['lesion_size[]'].value not f['lesion_size[]'][num_add].value)
//  just omit the [num_add]
num = f['numoflesions'].value;
if (num > 0) {
	var newlesion_size = f['lesion_size[]'][num_add].value;
	var lesionnum = f['lesion_number[]'][num_add].value;
	if (f['lesion_number[]'][num_add].value != "") {
		if (isNaN(lesionnum))
		{
		message = message + "New Lesion #" + f['lesion_number[]'][num_add].value + " Is not a valid number\n";
		}
		if (isNaN(newlesion_size) || newlesion_size==null || newlesion_size=="")
		{
		message = message + "New Lesion #" + f['lesion_number[]'][num_add].value + " Size " + newlesion_size + " is not a valid number\n";
		}
	}
}
if (num == 0) {
	var newlesion_size2 = f['lesion_size[]'].value;
	var lesionnum2 = f['lesion_number[]'].value;
	if (f['lesion_number[]'].value != "") {
		if (isNaN(lesionnum2))
		{
		message = message + "New Lesion #" + f['lesion_number[]'].value + " Is not a valid number\n";
		}
		if (isNaN(newlesion_size2) || newlesion_size2==null || newlesion_size2=="")
		{
		message = message + "New Lesion #" + f['lesion_number[]'].value + " Size " + newlesion_size2 + " is not a valid number\n";
		}
	}
}


// check add lesion number for duplicate before sending
num = f['numoflesions'].value - 1;
i = 0;
while (num > 0) {
	if (f['lesion_number[]'][num_add].value == f['lesion_number[]'][i].value) {
		message = message + "New Lesion #" + f['lesion_number[]'][num_add].value + " already exists, try another number.";
	}
	num--;
	i++;	
}
// flip thru all of the check boxes and set the corresponding text field to send to server (unchecked checkbox's dont get sent)
// cb is the checkbox 4 per each lesion 0 - Target, 1 - Lymph, 2 - Online, 3 - Delete
// checks is the hidden variable sent from browser with a Y or N for the check box.
num = f.cb.length;

i = 0;
var target_check;
var tempd;
while (num > 0) {
	// flip through each entry in cb and set the hidden "checks" to send to server
	//alert ("checking cb " + i);
	if (f.cb[i].checked)
      		{
         	f.checks[i].value = 'Y';
         	//alert ("set to Y");
      		} else 
      		{
        	f.checks[i].value = 'N';
        	//alert ("set to N");
     		}
	num--;
	i++;	
}


if (message == "") {
   if (document.getElementById('status').textContent){
      document.getElementById('status').textContent = 'Updating TEAM.....Please Wait.';
   }else{
      document.getElementById('status').innerText = 'Updating TEAM.....Please Wait.';
   }

   //document.getElementById('status').innerHTML = 'Updating TEAM.....Please Wait.';
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
<form name="lesions" action="lesionsupdate.php" onsubmit="return validateForm(this)" method="post">
<?php


echo "<table style=\"margin: auto;\" border=\"0\" cellpadding=\"2\"cellspacing=\"2\" width=\"75%\">";


echo "<tr><td align=center colspan=\"9\"><b>Patient $patient_name, Scan Date $scan_date</b></td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";

echo "<tr><td colspan=9><HR WIDTH=\"100%\" COLOR=\"#bebebe\" SIZE=\"4\"></td></tr>";

echo "<tr><td><b>Lesion #</b></td><td><b>Size (MM)</b></td><td align=center><b>Target</b></td><td align=center><b>Lymph Node</b></td><td align=center><b>Image Online</b></td><td align=center><b>Image</b></td><td align=center><b>File Type</b></td><td><b>Comments</b></td><td><font color=\"#ff0000\"><b>Delete Lesion</b></font></td>\n";
echo "</tr>\n";
echo "<tr><td>&nbsp;</td></tr>";


$web_date = date("m/d/y",strtotime($scan_date));
$query = "select lesion_teamid,lesion_number,lesion_size,lesion_comment,lesion_target,lesion_media_type,lesion_media_online,lesion_node from lesion where lesion_teamid = $user_teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$scan_date' order by lesion_number";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
echo "<input type=\"hidden\" name=\"numoflesions\" id=\"numoflesions\" value=\"$num\" />\n";
$i=0;
while ($i < $num) {
  $lesion_number=mysql_result($dbresult,$i,"lesion_number");
  $lesion_size=mysql_result($dbresult,$i,"lesion_size");
  $lesion_comment=mysql_result($dbresult,$i,"lesion_comment");
  $lesion_target=mysql_result($dbresult,$i,"lesion_target");
  $lesion_media_type=mysql_result($dbresult,$i,"lesion_media_type");
  $lesion_media_online=mysql_result($dbresult,$i,"lesion_media_online");
  $lesion_node=mysql_result($dbresult,$i,"lesion_node");

  
  $newdate = date("mdy",strtotime($scan_date));
  //lesionedit.php?patient_id=100&scan_date=2011-12-08&lesion_number=2&patient_name=Reed, Catherine
  $fn = "$patient_id-$newdate-$lesion_number.$lesion_media_type";
  $myurl = "lesionview.php?fn=$fn&patient_id=$patient_id&scan_date=$scan_date&lesion_number=$lesion_number&patient_name=$patient_name&lesion_size=$lesion_size&user_name=$user_name";
  
  echo "<tr><td align=center>$lesion_number</td>\n";
  echo "<td align=center><input type=\"text\" size=6 name=\"lesion_size[]\" value=\"$lesion_size\" id=\"lesion_size[]\" /></td>\n";
  echo "<input type=\"hidden\" name=\"lesion_number[]\" id=\"lesion_number[]\" value=\"$lesion_number\" />\n";
  
  if ($lesion_target == 'Y') { $chk = " checked=on"; } else { $chk = ""; }
  echo "<td align=center><input type=\"checkbox\" name=\"cb[]\" id=\"cb\" value=\"Y\" $chk>&nbsp;Target Lesion</td>\n";
  echo "<input type=\"hidden\" name=\"checks[]\" id=\"checks\" value=\"N\" />\n";
  //echo "<td align=center><input type=\"checkbox\" name=\"lesion_target[]\" value=\"Y\" $chk>Target Lesion</td>\n";
  
  if ($lesion_node == 'Y') { $chk = " checked=on"; } else { $chk = ""; }
  echo "<td align=center><input type=\"checkbox\" name=\"cb[]\" id=\"cb\" value=\"Y\" $chk>&nbsp;Lymph Node</td>\n";
  echo "<input type=\"hidden\" name=\"checks[]\" id=\"checks\" value=\"N\" />\n";
  //echo "<td align=center><input type=\"checkbox\" name=\"lesion_node[]\" value=\"Y\" $chk>Lymph Node</td>\n";
  
  if ($lesion_media_online == 'Y') { $chk = " checked=on"; } else { $chk = ""; }
  echo "<td align=center><input type=\"checkbox\" name=\"cb[]\" id=\"cb\" value=\"Y\" $chk>&nbsp;Online</td>\n";
  echo "<input type=\"hidden\" name=\"checks[]\" id=\"checks\" value=\"N\" />\n";
  //echo "<td align=center><input type=\"checkbox\" name=\"lesion_online[]\" value=\"Y\" $chk>Online</td>\n";
  
  
  
  
  //echo "<input type=\"hidden\" name=\"target[]\" id=\"target[]\" value=\"N\" />\n";
  //echo "<input type=\"hidden\" name=\"node[]\" id=\"node[]\" value=\"N\" />\n";
  //echo "<input type=\"hidden\" name=\"online[]\" id=\"online[]\" value=\"N\" />\n";
  //echo "<input type=\"hidden\" name=\"rmlesion[]\" id=\"rmlesion[]\" value=\"N\" />\n";
  //echo "<input type=\"hidden\" name=\"ldelete[]\" id=\"ldelete[]\" value=\"N\" />\n";
  
  
  // set link if image online
  if ($lesion_media_online == 'Y') {
     echo "<td align=center><a href=\"$myurl\">$fn</a></td>\n";
     } else {
     echo "<td align=center>$fn</td>\n";
     }
  
  
  echo "<td align=center><input type=\"text\" size=6 name=\"lesion_media_type[]\" id=\"lesion_media_type[]\" value=\"$lesion_media_type\" /></td>\n";
  echo "<td><input type=\"text\" name=\"lesion_comment[]\" id=\"lesion_comment[]\" value=\"$lesion_comment\"/></td>\n";
  
  echo "<td align=center><input type=\"checkbox\" name=\"cb[]\" id=\"cb\" value=\"Y\" ><font color=\"#ff0000\"><b>&nbsp;Yes</b></font></td></tr>\n";
  echo "<input type=\"hidden\" name=\"checks[]\" id=\"checks\" value=\"N\" />\n";
  //echo "<td align=center><input type=\"checkbox\" name=\"lesion_delete[]\" value=\"Y\">Yes</td></tr>\n";
  
  
  
  echo "<tr><td>&nbsp;</td></tr>";
  $i++;
  }
  echo "<tr><td colspan=9><HR WIDTH=\"100%\" COLOR=\"#bebebe\" SIZE=\"4\"></td></tr>";
// do the insert 
  echo "<tr><td>&nbsp;</td></tr>";
  echo "<tr><td align=center colspan=\"9\"><b>Enter New Lesion Information Below</b></td></tr><tr><td>&nbsp;</td></tr>";
  echo "<tr><td  align=center><input type=\"text\" name=\"lesion_number[]\" size=3 id=\"lesion_number[]\" /></td>\n";
  echo "<td align=center><input type=\"text\" size=6 name=\"lesion_size[]\" id=\"lesion_size[]\" /></td>\n";
  
  echo "<td align=center><input type=\"checkbox\" name=\"cb[]\" id=\"cb\" value=\"Y\" checked=on>Target Lesion</td>\n";
  echo "<input type=\"hidden\" name=\"checks[]\" id=\"checks\" value=\"N\" />\n";
  //echo "<td align=center><input type=\"checkbox\" name=\"lesion_target[]\" value=\"Y\">Target Lesion</td>\n";
  
  echo "<td align=center><input type=\"checkbox\" name=\"cb[]\" id=\"cb\" value=\"Y\" >Lymph Node</td>\n";
  echo "<input type=\"hidden\" name=\"checks[]\" id=\"checks\" value=\"N\" />\n";
  //echo "<td align=center><input type=\"checkbox\" name=\"lesion_node[]\" value=\"Y\">Lymph Node</td>\n";
  
  echo "<td align=center><input type=\"checkbox\" name=\"cb[]\" id=\"cb\" value=\"Y\" >Online</td>\n";
  echo "<input type=\"hidden\" name=\"checks[]\" id=\"checks\" value=\"N\" />\n";
  //echo "<td align=center><input type=\"checkbox\" name=\"lesion_online[]\" value=\"Y\">Online</td>\n";
  
  echo "<td align=center>$filename</td>\n";
  echo "<td align=center><input type=\"text\" size=6 name=\"lesion_media_type[]\" id=\"lesion_media_type[]\" value=\"$image_type\" /></td>\n";
  echo "<td><input type=\"text\" name=\"lesion_comment[]\" id=\"lesion_comment[]\" /></td></tr>\n";
  echo "<td align=center></td>\n";
  echo "<tr><td>&nbsp;</td></tr>";
  
  //echo "<input type=\"hidden\" name=\"target[]\" id=\"target[]\" value=\"N\" />\n";
  //echo "<input type=\"hidden\" name=\"node[]\" id=\"node[]\" value=\"N\" />\n";
  //echo "<input type=\"hidden\" name=\"online[]\" id=\"online[]\" value=\"N\" />\n";
  //echo "<input type=\"hidden\" name=\"remove[]\" id=\"remove[]\" value=\"N\" />\n";
  //echo "<input type=\"hidden\" name=\"ldelete[]\" id=\"ldelete[]\" value=\"N\" />\n";
  
  
  echo "<input type=\"hidden\" name=\"patient_id\" value=\"$patient_id\">";
  echo "<input type=\"hidden\" name=\"scan_date\" value=\"$scan_date\">";
  echo "<input type=\"hidden\" name=\"patient_name\" value=\"$patient_name\">";
  echo "<input type=\"hidden\" name=\"scan_report_online\" value=\"$scan_report_online\">";

  $newdate = date("mdy",strtotime($scan_date));
  $fn = "$patient_id-$newdate.$doc_type";
  $onlinecheck = "";
  if ($scan_report_online == 'Y') $onlinecheck = "checked=on";
echo "<tr><td align=center colspan=\"9\"><input type=\"checkbox\" name=\"scanonline\" value=\"Y\" $onlinecheck>Scan Report ($fn) is Online</td></tr>";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td colspan=9><HR WIDTH=\"100%\" COLOR=\"#bebebe\" SIZE=\"4\"></td></tr>";
?>
<tr><td align=center colspan="9"><input type="submit" name="Submit" value="Submit Changes/Adds to Lesions"></td></tr>
</table>
</form>
<table style="margin: auto;" border="0" cellpadding="2"
cellspacing="2" width="75%">
<tbody>
<tr><td>&nbsp;</td></tr>
<tr><td align=center>
<form action="scandelete.php" method="get" onsubmit="return confirm('Deleting a scan date will also delete all of his/her lesions.\n\nClick OK to proceed\n\nClick Cancel to Cancel');">
<input type="submit" value="Delete This Entire Scan and All its lesions">

<?php
echo "<input type=\"hidden\" name=\"patient_id\" value=\"$patient_id\">";
echo "<input type=\"hidden\" name=\"scan_date\" value=\"$scan_date\">";
echo "<input type=\"hidden\" name=\"patient_name\" value=\"$patient_name\">";
echo "<tr><td>&nbsp;</td></tr>";
?>
</form>
</td></tr>
</tbody>
</table>

<table style="margin: auto;" border="0" cellpadding="2"
cellspacing="2" width="75%">
<tbody>
<tr><td align=center>
<?php
// check if doc is online then set link or no link
$newdate = date("mdy",strtotime($scan_date));
$fn = "$patient_id-$newdate.$doc_type";
//$myurl = "http://$imagedoc_server/$fn";
$myurl = "teamredirect.php?fn=$fn";
if ($scan_report_online == 'Y') {
echo "<td align=center valign=\"top\"><a target=\"_blank\" href=\"$myurl\">View Patient Scan Document $fn</a><br><br></td></tr>";
} else {
echo "<td align=center valign=\"top\"><font color=\"#33cc00\">No Scan Document Online</font><br><br></td></tr>";
}
echo "<tr><td>&nbsp;</td></tr>";
?>
</td></tr>
</tbody>
</table>

<table style="margin: auto;" border="0" cellpadding="2"
cellspacing="2" width="75%">
<tbody>
<tr><td align=center>
<?php
if ($updated != 'Y') {
	$audit_type = 'v2';
	include 'auditlog.php';
	}
if ($updated == 'Y') {
echo "<td align=center valign=\"top\"><b id='status'><font color=\"#33cc00\">TEAM Successfully Updated</font></b></td></tr>";
} else {
echo "<td align=center valign=\"top\"><b id='status'></b></td></tr>";
}
//echo "<tr><td><HR WIDTH=\"100%\" COLOR=\"#bebebe\" SIZE=\"4\"></td></tr>";
?>
</td></tr>
</tbody>
</table>
</body>
</html>
