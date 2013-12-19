<?php
$teamtitle = "TEAM Compare Images";
include 'teamtop1.php';
?>
<style type="text/css">

/*Default CSS for pan containers*/
.pancontainer{
position:relative; /*keep this intact*/
overflow:hidden; /*keep this intact*/
width:300px;
height:300px;
border:1px solid black;

}

</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<script type="text/javascript" src="js/imagepanner.js">

/***********************************************
* Simple Image Panner and Zoomer- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/

</script>
<?php
include 'teamtop2.php';
?>
<table style="margin: auto;" border="0" cellpadding="2"
cellspacing="2" width="75%">
<tbody>
<tr>
<?php
$patient_id=$_POST['patient_id'];
$patient_name=$_POST['patient_name'];
$beforeimage=$_POST['beforeimage'];
$afterimage=$_POST['afterimage'];
$patient_name=$_POST['patient_name'];
echo "<td colspan=2 valign=\"top\" align=\"center\"><h2>Images for $patient_name ($patient_id)</h2>";
?>
</td></tr>
<?php

// beforeimage and afterimage - xxxx|yyyymmdd|nn|xxx|xxxxx
$array1 = preg_split("/[|]/", $beforeimage);
$patient_id1 = $array1[0];
$lesion_scan_date1 = $array1[1];
$newdate1 = date("mdy",strtotime($lesion_scan_date1));
$datedisplay1 = date("m/d/y",strtotime($lesion_scan_date1));
$lesion_number1 = $array1[2];
$lesion_type1 = $array1[3];
$lesion_size1 = $array1[4];
$file1 = "$patient_id1-$newdate1-$lesion_number1.$lesion_type1";

$array2 = preg_split("/[|]/", $afterimage);
$patient_id2 = $array2[0];
$lesion_scan_date2 = $array2[1];
$newdate2 = date("mdy",strtotime($lesion_scan_date2));
$datedisplay2 = date("m/d/y",strtotime($lesion_scan_date2));
$lesion_number2 = $array2[2];
$lesion_type2 = $array2[3];
$lesion_size2 = $array2[4];
$file2 = "$patient_id2-$newdate2-$lesion_number2.$lesion_type2";

$fn="$file1 and $file2";
$audit_type = 'vfm8';
include 'auditlog.php';
echo "<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
$filepw1 = "http://$imagedoc_user:$imagedoc_password@$imagedoc_server/$file1";
$filepw2 = "http://$imagedoc_user:$imagedoc_password@$imagedoc_server/$file2";

// internet explorer does not work with user/password so npw instead of pw
$filenpw1 = "http://$imagedoc_server/$file1";
$filenpw2 = "http://$imagedoc_server/$file2";

list($width1, $height1, $type, $attr) = getimagesize($filepw1);
list($width2, $height2, $type, $attr) = getimagesize($filepw2);

// calculate pan and zoom size
$zoomwidth = 500;
If ($width1 <= $zoomwidth){
	$width1n = $width1;
	$height1n = $height1;
}
if ($width1 > $zoomwidth) {
	$diff1 = $width1 - $zoomwidth;
	$perc1 = $diff1 / $width1;
	$width1n = $width1 - ($width1 * $perc1);
	$height1n = $height1 - ($height1 * $perc1);
	$width1n = number_format($width1n,0);
	$height1n = number_format($height1n,0);
	//echo ("$width1 x $height1 reduce by $perc1 becomes $width1n x $height1n" );
}
If ($width2 <= $zoomwidth){
	$width2n = $width2;
	$height2n = $height2;
}
if ($width2 > $zoomwidth) {
	$diff2 = $width2 - $zoomwidth;
	$perc2 = $diff2 / $width2;
	$width2n = $width2 - ($width2 * $perc2);
	$height2n = $height2 - ($height2 * $perc1);
	$width2n = number_format($width2n,0);
	$height2n = number_format($height2n,0);
	//echo ("$width2 x $height2 reduce by $perc2 becomes $width2n x $height2n" );
}


$sw1 = "width:" . $width1n . "px; height:" . $height1n . "px";
$sw2 = "width:" . $width2n . "px; height:" . $height2n . "px";
echo "<tr><td align=center valign=\"top\"><h3>Lesion #$lesion_number1 on $datedisplay1 ($lesion_size1 mm)</h2></td><td align=center valign=\"top\"><h3>Lesion #$lesion_number2 on $datedisplay2 ($lesion_size2 mm)</h2></td>";

//echo "<tr><td align=center><div id=\"my_zoom1\" class=\"zoompic\" data-src=\"http://$imagedoc_server/$file1\" data-animate=\"true\" data-width=\"500\" data-height=\"500\" data-zoomicon=\"gray\"></div></td><td align=center><div id=\"my_zoom2\" class=\"zoompic\" data-src=\"http://$imagedoc_server/$file2\" data-animate=\"true\" data-width=\"500\" data-height=\"500\" data-zoomicon=\"gray\"></div> </td></tr>";

echo "<tr><td valign=top align=center>";

//echo "<div class=\"pancontainer\" data-orient=\"center\" data-canzoom=\"yes\" style=\"width:400px; height:400px;\"><img src=\"http://$imagedoc_server/$file1\" style=\"width:400px; height:400px\" /></div></td>";
echo "<div class=\"pancontainer\" data-orient=\"center\" data-canzoom=\"yes\" style=\"$sw1\"><img src=\"$filenpw1\" width=\"$width1n\" height=\"$height1n\"  /></div></td>";

//echo "<td align=center><div class=\"pancontainer\" data-orient=\"center\" data-canzoom=\"yes\" style=\"width:400px; height:400px;\"><img src=\"http://$imagedoc_server/$file2\" style=\"width:400px; height:400px\" /></div></td></tr>";
echo "<td valign=top align=center><div class=\"pancontainer\" data-orient=\"center\" data-canzoom=\"yes\" style=\"$sw2\"><img src=\"$filenpw2\" width=\"$width2n\" height=\"$height2n\"  /></div></td></tr>";


// remove 
//echo "<tr><td align=center>";
//echo "<div class=\"pancontainer\" data-orient=\"center\" data-canzoom=\"yes\" style=\"width:400px; height:400px;\">";
//echo "<img src=\"http://$imagedoc_server/$fn\" style=\"width:400px; height:400px\" />";
////echo "<img src=\"http://$imagedoc_server/$fn\" />";
//echo "</div>";
//echo "</td></tr>";

// end remove



?>
</tbody>
</table>
<br>
</body>
</html>