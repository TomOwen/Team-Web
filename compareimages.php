<?php
$teamtitle = "TEAM Compare Images";
include 'teamtop1.php';
?>
 <script type="text/javascript">
        function validateForm()
{

var b4radio = document.getElementsByName('beforeimage');
var ischecked_method = false;
for ( var i = 0; i < b4radio.length; i++) {
    if(b4radio[i].checked) {
        ischecked_method = true;
    }
}
if(!ischecked_method)   { //payment method button is not checked
    alert("Please choose a BEFORE image");
    return false;
}
var afterradio = document.getElementsByName('afterimage');
ischecked_method = false;
for ( var j = 0; j < afterradio.length; j++) {
    if(afterradio[j].checked) {
        ischecked_method = true;
    }
}
if(!ischecked_method)   { //payment method button is not checked
    alert("Please choose an AFTER image");
    return false;
}
}
</script>
<?php
include 'teamtop2.php';
?>


<form action="viewmultiple.php" name="chooseimages" method="post" onsubmit="return validateForm()">

<table style="margin: auto;" border="0" cellpadding="2" cellspacing="2" width="75%">
<tbody>
<tr><td align=center>Choose Before Image</td><td></td><td align=center>Choose After Image</td>
<tr><td align=center></td><td></td><td align=center></td>
<tr>
<td valign="top">
<table border="0" cellpadding="2" cellspacing="2"
width="100%">
<tbody>


<?php
$query = "select lesion_teamid, lesion_scan_date, lesion_number, lesion_size, lesion_comment, lesion_target, lesion_media_type, lesion_media_online, lesion_node from lesion where lesion_teamid = $user_teamid and lesion_patient_id = '$patient_id' and lesion_media_online ='Y' order by lesion_number, lesion_scan_date ";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$savenum = 0;             
$i=0;
while ($i < $num) {
  $lesion_scan_date=mysql_result($dbresult,$i,"lesion_scan_date");
  $lesion_size=mysql_result($dbresult,$i,"lesion_size");
  $lesion_number=mysql_result($dbresult,$i,"lesion_number");
  $lesion_media_type=mysql_result($dbresult,$i,"lesion_media_type");
  $newdate1 = date("m/d/y",strtotime($lesion_scan_date));
  $beforeimage = "Lesion $lesion_number ($lesion_size mm) - Scan Date $newdate1";
  $newdate = date("mdy",strtotime($lesion_scan_date));
  $fn = "$patient_id|$lesion_scan_date|$lesion_number|$lesion_media_type|$lesion_size";
  if ($savenum != $lesion_number) {
  	$savenum = $lesion_number;
  	echo "<tr><td>&nbsp;</td></tr>";
  	}
  echo "<tr>";
  echo "<td align=center valign=\"top\"><input type=\"radio\" name=\"beforeimage\" value=\"$fn\">$beforeimage<br>";
  echo "</td>";
  echo "</tr>";
  $i++;
  }

?>
</tbody>
</table>
<br>
</td>
<td valign="top" width=5><br>
</td>
<td valign="top">
<table border="0" cellpadding="2" cellspacing="2"
width="100%">
<tbody>
<?php
$savenum = 0;
$i=0;
while ($i < $num) {
  $lesion_scan_date=mysql_result($dbresult,$i,"lesion_scan_date");
  $lesion_size=mysql_result($dbresult,$i,"lesion_size");
  $lesion_number=mysql_result($dbresult,$i,"lesion_number");
  $lesion_media_type=mysql_result($dbresult,$i,"lesion_media_type");
  $newdate1 = date("m/d/y",strtotime($lesion_scan_date));
  $afterimage = "Lesion $lesion_number ($lesion_size mm) - Scan Date $newdate1";
  $newdate = date("mdy",strtotime($lesion_scan_date));
  $fn = "$patient_id|$lesion_scan_date|$lesion_number|$lesion_media_type|$lesion_size";
  if ($savenum != $lesion_number) {
  	$savenum = $lesion_number;
  	echo "<tr><td>&nbsp;</td></tr>";
  	}
  echo "<tr>";
  echo "<td align=center valign=\"top\"><input type=\"radio\" name=\"afterimage\" value=\"$fn\">$afterimage<br>";
  echo "</td>";
  echo "</tr>";
  $i++;
  }
?>
</tbody>
</table>



<br>
</td>
</tr>

<tr><td align=center colspan=3><input type="submit" value="View Selected Images"></td></tr>
</tbody>
</table>

<?php
echo "<input type=\"hidden\" name=\"patient_id\" value=\"$patient_id\">";
echo "<input type=\"hidden\" name=\"patient_name\" value=\"$patient_name\">";
?>
</form>





<br>
</body>
</html>