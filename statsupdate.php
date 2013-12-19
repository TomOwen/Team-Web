<?php
$teamtitle = "TEAM Reload Reports and Graphs DB";
include 'teamtop1.php';
include 'teamtop2.php';
?>
<?php

//$teamid = $_GET['teamid'];
$teamid = $storedVars[2];
$updateall = $_GET['updateall'];
// for testing only
$debug = 0; // 1 does echo's 0 no debug
//$updateall = '';
//$teamid = 1000;


// delete stats records for all or just this teamid
if ($updateall == 'Y') {
	$query = "delete from stats";
	} else {
	$query = "delete from stats where stats_teamid = $teamid";
}
$dbresult = mysql_query($query, $dbconnect);
if (!mysql_error()) {
   $return_code = 1;
} else {
   $return_code = 0;
}
//$num=mysql_numrows($dbresult);  
if ($debug) echo "return code =  $return_code, rows deleted = $num\n"; 

// begin building the stats records for graphing
// select all stats records to be updated ot just for this teamid
if ($updateall == 'Y') {
	$query2 = "select patient_teamid, patient_id, patient_name, patient_study_id from patient";
	} else {
	$query2 = "select patient_teamid, patient_id, patient_name, patient_study_id from patient where patient_teamid = $teamid";
}

$dbresult2 = mysql_query($query2, $dbconnect);
$num2=mysql_numrows($dbresult2);              
$i=0;
while ($i < $num2) {
  $patient_teamid=mysql_result($dbresult2,$i,"patient_teamid");
  $patient_id=mysql_result($dbresult2,$i,"patient_id");
  $patient_name=mysql_result($dbresult2,$i,"patient_name");
  $patient_study_id=mysql_result($dbresult2,$i,"patient_study_id");
  if ($debug) echo "Processing $patient_teamid/$patient_id/$patient_study_id\n";
  // read scans and get baseline date and current date
  	$query3 = "select scan_date, min(scan_date) as 'baseline_date' from scan where scan_teamid = $patient_teamid and scan_patient_id = '$patient_id'";
	//if ($debug) echo "$query3\n";
	$dbresult3 = mysql_query($query3, $dbconnect);
	$num3=mysql_numrows($dbresult3);
	if ($debug) echo "patient $patient_id num3 = $num3\n";              
	$i3=0;
	while ($i3 < $num3) {
  		$baseline_date=mysql_result($dbresult3,$i3,"baseline_date");
  		$i3++;
	}

	$query4 = "select scan_date, max(scan_date) as 'current_date' from scan where scan_teamid = $patient_teamid and scan_patient_id = '$patient_id'";
	$dbresult4 = mysql_query($query4, $dbconnect);
	$num4=mysql_numrows($dbresult4);              
	$i4=0;
	while ($i4 < $num4) {
  		$current_date=mysql_result($dbresult4,$i4,"current_date");
  		$i4++;
	}
	if ($debug) echo "baseline_date=$baseline_date, current_date=$current_date \n";
  	// read all lesions in baseline scan and get baseline_total
  	$query5 = "select lesion_size,lesion_node from lesion where lesion_teamid = $patient_teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$baseline_date'";

	$dbresult5 = mysql_query($query5, $dbconnect);
	$num5=mysql_numrows($dbresult5);
	$baseline_total = 0;              
	$i5=0;
	while ($i5 < $num5) {
  		$lesion_size=mysql_result($dbresult5,$i5,"lesion_size");
  		$lesion_node=mysql_result($dbresult5,$i5,"lesion_node");
  		// lesion node is defined as lesion at least 15mm and a normal node is 10mm
  		// total size will only include tumor so for a lymph node subtract out the normal 10mm node size
  		if ($lesion_node == "Y") {
     			$lesion_size = $lesion_size - 10;
     			if ($lesion_size < 10) {
        			// reset size to normal 10mm minimum for lymph node
       				 $lesion_size = 0;
     			}
  		}
  		$baseline_total = $baseline_total + $lesion_size;
  		$i5++;
	}
	if ($debug) echo "baseline_total=$baseline_total\n";
  	// read all lesions in current scan and get current_total
  	$query6 = "select lesion_size,lesion_node from lesion where lesion_teamid = $patient_teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$current_date'";

	$dbresult6 = mysql_query($query6, $dbconnect);
	$num6=mysql_numrows($dbresult6);
	$current_total = 0;              
	$i6=0;
	while ($i6 < $num6) {
  		$lesion_size=mysql_result($dbresult6,$i6,"lesion_size");
  		$lesion_node=mysql_result($dbresult6,$i6,"lesion_node");
  		// lesion node is defined as lesion at least 15mm and a normal node is 10mm
  		// total size will only include tumor so for a lymph node subtract out the normal 10mm node size
  		if ($lesion_node == "Y") {
     			$lesion_size = $lesion_size - 10;
     			if ($lesion_size < 10) {
        			// reset size to normal 10mm minimum for lymph node
       				 $lesion_size = 0;
     			}
  		}
  		$current_total = $current_total + $lesion_size;
  		$i6++;
	}
  	// calculate percent_increase
  	$difference = $current_total - $baseline_total;
  	if ($baseline_total > 0) {
  		$percent_increase = ($difference / $baseline_total) * 100;
  	}
  	if ($current_total == 0) {
  		$percent_increase = -100;
  	}
  	$percent_increase2 = number_format($percent_increase,1, '.', '');
  	// insert new stats record for this patient
  	if ($debug) echo "patient $patient_id num3 = $num3\n";
  	if ($current_date == '' ) {
  	    // no scans so set to 0 increase
  	    $percent_increase2 = 0;
  	}
	$query7 = "INSERT INTO stats (stats_teamid,stats_patient_id,stats_patient_name,stats_study_id,stats_baseline_scandate,stats_current_scandate,stats_baseline_total,stats_current_total,stats_percent_increase,stats_response) VALUES ($patient_teamid, '$patient_id' ,'$patient_name', '$patient_study_id', '$baseline_date', '$current_date', $baseline_total, $current_total,$percent_increase2, 'NA')";
	if ($debug) echo "$query7\n";
	$dbresult7 = mysql_query($query7, $dbconnect);
  $i++;
}
// done
// now calculate the response for each of the patients
require 'teamresponsefunctions.php';



// for testing only
$debug = 0; // 1 does echo's 0 no debug
$debug1 = 0;
global $debug;
$xmloption = 'N';
global $xmloption;
//$teamid = 1000;
//$patient_id = 'irc100';
// end testing
$lesion_target = 'Y';


// globals set by functions
$comapny_name = "";
$patient_name = "";
$patient_study_id = "";
$patient_doctor = "";
$study_percentpr = 0;
$study_percentpd = 0;
$study_owner = "";
$study_name = "";
$baseline_date = "";
$current_date = "N/A";
$tc_total_size = $tb_total_size = $ts_total_size = $ntc_total_size = $ntb_total_size = $nts_total_size = 0;
// individual lesion variables 
$lesion_number = 0;
$lesion_target = "";
$current_size = 0;
$baseline_size = 0;
$baseline_percent_change = 0;
$smallest_date = "";
$smallest_size = 0;
$smallest_percent_change = 0;
$new_lesion = "";
$lymph_node = "";
$target_response_code = $non_target_response_code = $overall_response_code = '';
global $target_response_code,$tb_response,$ts_response;
global $non_target_response_code,$ntb_response,$nts_response;
global $overall_response_code;
global $xmlbody,$xmloption,$company_name;
global $include_new; // set to Y if irRC study type
global $new_tumor_total;
global $teamid,$patient_id;
$xmlheader = $xmlbody = $xmltrailer = "";
// get all patients or just from this teamid
if ($updateall == 'Y') {
	$queryx = "select patient_teamid, patient_id from patient";
	} else {
	$queryx = "select patient_teamid, patient_id from patient where patient_teamid = $teamid";
}


$dbresultx = mysql_query($queryx, $dbconnect);
$numx=mysql_numrows($dbresultx);              
$x=0;
while ($x < $numx) {
  $patient_id=mysql_result($dbresultx,$x,"patient_id");
  $teamid=mysql_result($dbresultx,$x,"patient_teamid");
  getpatient();
  $tc_total_size = gettotalsize ('Y',$current_date);
  $tb_total_size = gettotalsize('Y',$baseline_date);
  $ts_total_size = getsmallestsize('Y',$current_date);
  $ntc_total_size = gettotalsize('N',$current_date);
  $ntb_total_size = gettotalsize('N',$baseline_date);
  $nts_total_size = getsmallestsize('N',$current_date);
  $tb_response = $ts_response = $ntb_response = $nts_response = 'N/A';
  list ($tb_percent_change, $tb_response) = percent_changed($tc_total_size,$tb_total_size);
  list ($ts_percent_change, $ts_response) = percent_changed($tc_total_size,$ts_total_size);
  list ($ntb_percent_change, $ntb_response)  = percent_changed($ntc_total_size,$ntb_total_size);
  list ($nts_percent_change, $nts_response) = percent_changed($ntc_total_size,$nts_total_size);
  getallcurrent(); // determines new lesion, gets all data for table view of current lesions
  calculateresponses(); // determine Overall, Target and Non-Target response based upon baseline and smallest response
  if ($debug1) echo "\n***Summary Patient=$patient_id *** overall_response_code=$overall_response_code, target_response_code=$target_response_code, non_target_response_code=$non_target_response_code\n";
  if ($debug1) echo "\nAfter Calcs\nTarget: Current $tc_total_size mm Baseline $tb_total_size $tb_percent_change% $tb_response\n";
  if ($debug1) echo "Target: Current $tc_total_size mm Smallest $ts_total_size $ts_percent_change% $ts_response\n";
  if ($debug1) echo "NON Target: Current $ntc_total_size mm Baseline $ntb_total_size $ntb_percent_change% $ntb_response\n";
  if ($debug1) echo "NON Target: Current $ntc_total_size mm Smallest $nts_total_size $nts_percent_change% $nts_response\n";
  // now update the stats record with response
  $query1 = "update stats set stats_response = '$overall_response_code'  where stats_teamid = $teamid and stats_patient_id = '$patient_id' and stats_current_scandate > '1900-01-01'";
  $dbresult1 = mysql_query($query1, $dbconnect);
  $x++;
}
//header('Content-type: text/html');
?>
<html>
<head>
<meta content="text/html; charset=ISO-8859-1"
http-equiv="Content-Type">
<title></title>
</head>
<body>
<?php
echo "<div align='center'><br><br><br><big><big><big><b>TEAM Graph Database<br><br> $numx Patients Updated</b></big></big><br>";
$audit_type = 'vr8';
include 'auditlog.php';
?>
</div>
</body>
</html>


