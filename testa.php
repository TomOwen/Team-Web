<?php
$user_name = "tadxmin1000";
$pos=strpos($user_name,"admin");
//echo "$pos";
$beforeimage = "100|123112|1|jpg|32.5";
list($patient_id,$date1,$lesion_number,$rest) = sscanf($beforeimage, "%s\-%s\-%s\#%s");
//echo "$patient_id,$date1,$lesion_number,$rest";
$result = sscanf($beforeimage, "%s\-%s\-%s\#%s");
 
$result_array = preg_split( "/[-]/", $beforeimage );
//print_r($results_array);
//print_r(preg_split("/[,. ]/", $beforeimage));
//print_r(preg_split("/[-. ]/", $beforeimage));
print_r(preg_split("/[|]/", $beforeimage));
?>