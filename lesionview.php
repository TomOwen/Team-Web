<?php
$teamtitle = "TEAM Compare Images";
include 'teamtop1.php';
// old <script src="http://rvolve.com/js/zoom-pic-intro-3.0.js" type="text/javascript"></script>
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

$patient_id=$_GET['patient_id'];
$patient_name=$_GET['patient_name'];
$fn=$_GET['fn'];
$patient_name=$_GET['patient_name'];
$scan_date=$_GET['scan_date'];
$lesion_size=$_GET['lesion_size'];
$lesion_number=$_GET['lesion_number'];

$audit_type = 'v3';
include 'auditlog.php';

$datedisplay2 = date("m/d/y",strtotime($scan_date));
echo "<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
echo "<td valign=\"top\" align=\"center\"><h2>Image for $patient_name ($patient_id) - Lesion #$lesion_number on $datedisplay2 was ($lesion_size mm)</h2>";
?>
</td>
<?php
// get width and height
//list($width1, $height1, $type, $attr) = getimagesize("../teamdoc1000/$fn");
$filepw1 = "http://$imagedoc_user:$imagedoc_password@$imagedoc_server/$fn";
$filepw1 = "http://$imagedoc_user:$imagedoc_password@$imagedoc_server/$fn";
$filenpw1 = "http://$imagedoc_server/$fn";
list($width1, $height1, $type, $attr) = getimagesize($filepw1);
//echo "results=$width1, $height1";
$zoomwidth = 700;
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




// old echo "<tr><td align=center><div id=\"my_zoom1\" class=\"zoompic\" data-src=\"http://$imagedoc_server/$fn\" data-animate=\"true\" data-width=\"500\" data-height=\"500\" data-zoomicon=\"gray\"></div> </td></tr>";

$sw = "width:" . $width1n . "px; height:" . $height1n . "px";
echo "<tr><td align=center>";
echo "<div class=\"pancontainer\" data-orient=\"center\" data-canzoom=\"yes\" style=\"$sw\">";
//echo "<img src=\"http://$imagedoc_server/$fn\" style=\"width:400px; height:400px\" />";
echo "<img src=\"$filenpw1\" width=\"$width1n\" height=\"$height1n\"  />";
echo "</div>";
echo "</td></tr>";

//echo "<tr><td align=center>";
//echo "<img id=\"myimage\" src=\"http://$imagedoc_server/$fn\" style=\"width:300px; height:300px\" />";
//echo "</td></tr>";


?>
</tbody>
</table>
<br>
</body>
</html>