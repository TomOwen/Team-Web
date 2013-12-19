<?php
$zoomwidth = 500;
$width1 = 512;
$height1 = 350;

If ($width1 <= $zoomwidth){
	$width1n = $width1;
	$height1n - $height1;
}
if ($width1 > $zoomwidth) {
	$diff1 = $width1 - $zoomwidth;
	$perc1 = $diff1 / $width1;
	$width1n = $width1 - ($width1 * $perc1);
	$height1n = $height1 - ($height1 * $perc1);
	$width1n = number_format($width1n,0);
	$height1n = number_format($height1n,0);
	echo ("$width1 x $height1 reduce by $perc1 becomes $width1n x $height1n" );
}
?>