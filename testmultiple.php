<!DOCTYPE html>
<html>
<head>
<script >
function validateForm(f)
{
var num = f['lesion_number[]'].length;
var num_add = num -1; // used to get to the add line
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
var newlesion_size = f['lesion_size[]'][num_add].value;
var lesionnum = f['lesion_number[]'][num_add].value;
if (f['lesion_number[]'][num_add].value != "") {
		if (isNaN(lesionnum))
		{
		message = message + "New Lesion #" + f['lesion_number[]'][num_add].value + " Is not a valid number\n";
		}
		if (isNaN(newlesion_size) || newlesion_size==null || newlesion_size=="")
		{
		message = message + "New Lesion #" + f['lesion_number[]'][num_add].value + " Size " + lesionnum + " is not a valid number\n";
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
</head>

<body>
<form name="lesions" action="testmultiple2.php" onsubmit="return validateForm(this)" method="post">
<?php
$num = 3;
$i=0;
echo "<table style=\"margin: auto;\" border=\"0\" cellpadding=\"2\"cellspacing=\"2\" width=\"75%\">";
echo "<tr><td align=center colspan=\"9\"><b>Patient $patient_name, Scan Date $scan_date</b></td></tr><tr></tr><tr></tr>";
echo "<tr><td><b>Lesion #</b></td><td><b>Size (MM)</b></td><td align=center><b>Target</b></td><td align=center><b>Lymph Node</b></td><td align=center><b>Image Online</b></td><td align=center><b>Image</b></td><td align=center><b>File Type</b></td><td><b>Comments</b></td>\n";
echo "</tr>\n";
echo "<tr></tr>";
while ($i < $num) {
  $lesion_number = $i + 1;
  $filename = "100-121312-1.jpg";
  $lesion_media_type = "jpg";
  $patient_id = '100';
  $scan_date = "20130216";
  $lesion_media_online = 'Y';
  
  $newdate = date("mdy",strtotime($scan_date));
  //lesionedit.php?patient_id=100&scan_date=2011-12-08&lesion_number=2&patient_name=Reed, Catherine
  $fn = "$patient_id-$newdate-$lesion_number.$lesion_media_type";
  $myurl = "lesionview.php?fn=$fn&patient_id=$patient_id&scan_date=$scan_date&lesion_number=$lesion_number&patient_name=$patient_name";
  
  echo "<tr><td align=center>$lesion_number</td>\n";
  echo "<td align=center><input type=\"text\" size=6 name=\"lesion_size[]\" id=\"lesion_size[]\" /></td>\n";
  echo "<input type=\"hidden\" name=\"lesion_number[]\" id=\"lesion_number[]\" value=\"$lesion_number\" />\n";
  echo "<td align=center><input type=\"checkbox\" name=\"lesion_target[]\" value=\"Y\">Target Lesion</td>\n";
  echo "<td align=center><input type=\"checkbox\" name=\"lesion_node[]\" value=\"Y\">Lymph Node</td>\n";
  echo "<td align=center><input type=\"checkbox\" name=\"lesion_online[]\" value=\"Y\">Online</td>\n";
  
  echo "<input type=\"hidden\" name=\"lesion_target[]\" id=\"lesion_target[]\" value=\"N\" />\n";
  echo "<input type=\"hidden\" name=\"lesion_node[]\" id=\"lesion_node[]\" value=\"N\" />\n";
  echo "<input type=\"hidden\" name=\"lesion_online[]\" id=\"lesion_online[]\" value=\"N\" />\n";
  
  // set link if image online
  if ($lesion_media_online == 'Y') {
     echo "<td align=center><a href=\"$myurl\">$fn</a></td>\n";
     } else {
     echo "<td align=center>$fn</td>\n";
     }
  
  
  echo "<td align=center><input type=\"text\" size=6 name=\"lesion_media_type[]\" id=\"lesion_media_type[]\" value=\"$lesion_media_type\" /></td>\n";
  echo "<td><input type=\"text\" name=\"lesion_comment[]\" id=\"lesion_comment[]\" /></td></tr>\n";
  echo "<tr></tr>";
  $i++;
  }
// do the insert 
  echo "<tr></tr><tr></tr><tr></tr><tr></tr>";
  echo "<tr><td align=center colspan=\"9\"><b>Enter New Lesion Information Below</b></td></tr><tr></tr><tr></tr>";
  echo "<tr><td  align=center><input type=\"text\" name=\"lesion_number[]\" size=3 id=\"lesion_number[]\" /></td>\n";
  echo "<td align=center><input type=\"text\" size=6 name=\"lesion_size[]\" id=\"lesion_size[]\" /></td>\n";
  echo "<td align=center><input type=\"checkbox\" name=\"lesion_target[]\" value=\"Y\">Target Lesion</td>\n";
  echo "<td align=center><input type=\"checkbox\" name=\"lesion_node[]\" value=\"Y\">Lymph Node</td>\n";
  echo "<td align=center><input type=\"checkbox\" name=\"lesion_online[]\" value=\"Y\">Online</td>\n";
  echo "<td align=center>$filename</td>\n";
  echo "<td align=center><input type=\"text\" size=6 name=\"lesion_media_type[]\" id=\"lesion_media_type[]\" value=\"$lesion_media_type\" /></td>\n";
  echo "<td><input type=\"text\" name=\"lesion_comment[]\" id=\"lesion_comment[]\" /></td></tr>\n";
  echo "<tr></tr>";
?>
<tr><td align=center colspan="9"><input type="submit" name="Submit" value="Submit Changes/Adds to Lesions"></td></tr>
</table>
</form>
</body>

</html>