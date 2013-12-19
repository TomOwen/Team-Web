<?php
function percent_changed($num_amount, $num_total) {
global $debug;
global $study_percentpr,$study_percentpd;
if ($debug) echo "Calc % change using $num_amount, $num_total\n";
// may not work
if ($num_amount == 0) {
   return array ("-100", "CR");
   }

$difference = $num_amount - $num_total;
if ($num_total != 0) {
   $count1 = $difference / $num_total;
   $count2 = $count1 * 100;
   } else {
   //$count2 = -100;
   $count2 = 0; // set for new lesion normally
   }
$percent_changed = number_format($count2,0);
// now calulate response
$response = 'SD';
if ($debug) echo "percent_changed = $percent_changed using $study_percentpr,$study_percentpd\n";
if ($percent_changed <=  -100) {
 $response = 'CR';
 return array ($percent_changed, $response); 
}
if ($percent_changed <= $study_percentpr) {
 $response = 'PR';
 return array ($percent_changed, $response); 
}
if ($percent_changed >= $study_percentpd) {
 if ($difference > 5) {
   $response = 'PD';
   return array ($percent_changed, $response); 
 }
}
return array ($percent_changed, $response);
}
function response($percent_changed,$totalsize,$currentsize) {
global $study_percentpr,$study_percentpd;
if ($percent_changed <=  -100) {
 return 'CR'; 
}
if ($percent_changed <= $study_percentpr) {
 return 'PR'; 
}
if ($percent_changed >= $study_percentpd) {
 $diff = $totalsize - $currentsize;
 if ($diff > 5) {
   return 'PD'; 
 }
return 'SD';
}
}
function getpatient()
{
// need to get patient_name,patient_study_id,patient_doctor then from study get 
// study_owner study_name, study_url, study_percentpr, study_percentpd
global $teamid,$patient_id,$patient_name,$patient_study_id,$patient_doctor,$study_percentpr,$study_percentpd,$study_owner,$study_name,$dbconnect;
global $baseline_date,$current_date;
global $debug,$include_new;
$query = "select patient_name, patient_study_id, patient_doctor from patient where patient_teamid = $teamid and patient_id = '$patient_id'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  $patient_name=mysql_result($dbresult,$i,"patient_name");
  $patient_study_id=mysql_result($dbresult,$i,"patient_study_id");
  $patient_doctor=mysql_result($dbresult,$i,"patient_doctor");
  $i++;
}
// set defaults
$study_percentpr = -30;
$study_percentpd = 20;
$study_owner = "N/A";
$study_name = "N/A";

$query = "select study_percentpd, study_percentpr, study_owner, study_name from studies where study_teamid = $teamid and study_id = '$patient_study_id'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  $study_percentpr=mysql_result($dbresult,$i,"study_percentpr");
  $study_percentpr = $study_percentpr * -1; // needed for the if statement in response calc
  $study_percentpd=mysql_result($dbresult,$i,"study_percentpd");
  $study_owner=mysql_result($dbresult,$i,"study_owner");
  $study_name=mysql_result($dbresult,$i,"study_name");
  $i++;
}
// determine if this study is irRC - immune related response criteria (does not make Progressive disease with new tumors)
$include_new = 'Y';
if (preg_match("/irrc/i", $patient_study_id)) {
 if ($debug)  echo "set include new to N\n";
 $include_new = 'N';
}      
$query = "select scan_date, min(scan_date) as 'baseline_date' from scan where scan_teamid = $teamid and scan_patient_id = '$patient_id'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  $baseline_date=mysql_result($dbresult,$i,"baseline_date");
  $i++;
}

$query = "select scan_date, max(scan_date) as 'current_date' from scan where scan_teamid = $teamid and scan_patient_id = '$patient_id'";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);              
$i=0;
while ($i < $num) {
  $current_date=mysql_result($dbresult,$i,"current_date");
  $i++;
}
}
function getsmallestsize ($target,$datein) {
global $teamid,$patient_id,$lesion_target,$dbconnect;
//echo "smallest function got target=$target,scandate=$datein\n";
// $target is Y or N to include meaning to select only lesion_target = Y or only lesion_target = N
// read all lesions from date specified
$query = "select lesion_number,lesion_node from lesion where lesion_teamid = $teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$datein' and lesion_target = '$target'";
if ($debug) echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$totalsize = 0;
$totalsmallestsize = 0;              
$i=0;
while ($i < $num) {
  // for each lesion_number select the smallest size it ever was
  $lesion_number=mysql_result($dbresult,$i,"lesion_number");
  $lesion_node=mysql_result($dbresult,$i,"lesion_node");
  //$query2 = "select lesion_size from lesion where lesion_teamid = $teamid and lesion_patient_id = '$patient_id' and lesion_target = '$target' and lesion_number = $lesion_number and lesion_scan_date != '$datein' order by lesion_size,lesion_scan_date";
  $query2 = "select lesion_size from lesion where lesion_teamid = $teamid and lesion_patient_id = '$patient_id' and lesion_target = '$target' and lesion_number = $lesion_number order by lesion_size,lesion_scan_date";
  if ($debug) echo "query2=$query2\n";
  $dbresult2 = mysql_query($query2, $dbconnect);
  $num2=mysql_numrows($dbresult2);
  $j=0;
  while ($j < $num2) {
    // total up the size
    //echo "j=$j, num2=$num2\n";
    $smallest_size=mysql_result($dbresult2,$j,"lesion_size");
    //echo "got smallest_size=$smallest_size with j=$j\n";
    if ($lesion_node == "Y") {
     $smallest_size = $smallest_size - 10;
     if ($smallest_size < 10) {
        // reset size to normal 10mm minimum for lymph node
        $smallest_size = 0;
     }
    }
    $totalsmallestsize = $totalsmallestsize + $smallest_size;
    $j = $num2; // breaks after first hit
  }
  $i++;
}
return $totalsmallestsize;
}
function gettotalsize ($target,$scandate) {
global $teamid,$patient_id,$lesion_target,$dbconnect;
//echo "in function got target=$target,scandate=$scandate\n";
// $target is Y or N to include meaning to select only lesion_target = Y or only lesion_target = N
$query = "select lesion_size,lesion_node from lesion where lesion_teamid = $teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$scandate' and lesion_target = '$target'";
//echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$totalsize = 0;              
$i=0;
while ($i < $num) {
  $lesion_size=mysql_result($dbresult,$i,"lesion_size");
  $lesion_node=mysql_result($dbresult,$i,"lesion_node");
  // lesion node is defined as lesion at least 15mm and a normal node is 10mm
  // total size will only include tumor so for a lymph node subtract out the normal 10mm node size
  if ($lesion_node == "Y") {
     $lesion_size = $lesion_size - 10;
     if ($lesion_size < 10) {
        // reset size to normal 10mm minimum for lymph node
        $lesion_size = 0;
     }
  }
  $totalsize = $totalsize + $lesion_size;
  $i++;
}
return $totalsize;
}
function getallcurrent() {
// this function will retrieve all current lesions
// determine if it is a new lesion
// set response to PD for target and non-target response if new lesion
// create an xml line entry for transmission
global $debug,$include_new,$new_tumor_total,$current_tumor_total;
global $teamid,$patient_id,$dbconnect;
global $baseline_date,$current_date,$current_size,$baseline_percent_change,$baseline_size;
global $smallest_size,$smallest_date,$smallest_percent_change;
global $tb_response,$ts_response,$ntb_response,$nts_response;
global $xmlheader,$xmlbody,$xmltrailer,$xmloption;
$new_tumor_total = 0;
$current_tumor_total = 0;
$query = "select lesion_number,lesion_target,lesion_size,lesion_node from lesion where lesion_teamid = $teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$current_date' order by lesion_number";
if ($debug) echo "query check on current lesions = $query\n";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i=0;
while ($i < $num) {
    $temp = $i + 1;
    if ($debug) {echo "\n\nworking lesion #$temp, debug = $debug\n";}
    $lesion_number=mysql_result($dbresult,$i,"lesion_number");
    $lesion_target=mysql_result($dbresult,$i,"lesion_target");
    $lesion_size=mysql_result($dbresult,$i,"lesion_size");
    $lesion_node=mysql_result($dbresult,$i,"lesion_node");
    $new_lesion = 'N';
    $current_size = $lesion_size;
    getallsmallest($lesion_number);
    if ($debug){ echo "smallest_size=$smallest_size,smallest_date=$smallest_date,smallest_percent_change=$smallest_percent_change\n";}
    $new_lesion = islesionnew($lesion_number);
    if ($debug) echo "new lesion = $new_lesion, lesion_target = $lesion_target, current_size = $current_size\n";
    // a study that has irrc in its name will not cause a PD when a new tumor is present
    if ($new_lesion == 'Y') {
       $new_tumor_total = $new_tumor_total + $lesion_size;
    }
    if ($new_lesion == 'N') {
       $current_tumor_total = $current_tumor_total + $lesion_size;
    }
    if ($new_lesion == 'Y' and $lesion_target == 'Y' and $current_size > 0 and $include_new == 'Y') {
       //echo "setting target to PD\n";
       $tb_response = 'PD';
       $ts_response = 'PD';
    }
    if ($new_lesion == 'Y' and $lesion_target == 'N' and $current_size > 0 and $include_new == 'Y') {
       //echo "setting non target to PD\n";
       $ntb_response = 'PD';
       $nts_response = 'PD';
    }
    if ($debug) echo "\nlesion_number=$lesion_number,lesion_target=$lesion_target,current_size=$current_size\n";
    if ($debug) echo "baseline_date=$baseline_date,baseline_size=$baseline_size,baseline_percent_change=$baseline_percent_change\n";
    if ($xmloption == 'Y') {
        // build the lesion xml
        $mdy_current_date = date('m/d/y',strtotime($current_date));
        $mdy_baseline_date = date('m/d/y',strtotime($baseline_date));
        $mdy_smallest_date = date('m/d/y',strtotime($smallest_date));
        $xmlbody = $xmlbody . "<lesion>";
        $xmlbody = $xmlbody . "<lesion_number>$lesion_number</lesion_number>";
        $xmlbody = $xmlbody . "<lesion_target>$lesion_target</lesion_target>";
        $xmlbody = $xmlbody . "<current_date>$mdy_current_date</current_date>";
        $xmlbody = $xmlbody . "<baseline_date>$mdy_baseline_date</baseline_date>";
        $xmlbody = $xmlbody . "<current_size>$current_size</current_size>";
        $xmlbody = $xmlbody . "<baseline_size>$baseline_size</baseline_size>";
        $xmlbody = $xmlbody . "<baseline_percent_change>$baseline_percent_change</baseline_percent_change>";
        $xmlbody = $xmlbody . "<smallest_date>$mdy_smallest_date</smallest_date>";
        $xmlbody = $xmlbody . "<smallest_size>$smallest_size</smallest_size>";
        $xmlbody = $xmlbody . "<smallest_percent_change>$smallest_percent_change</smallest_percent_change>";
        $xmlbody = $xmlbody . "<new_lesion>$new_lesion</new_lesion>";
        $xmlbody = $xmlbody . "<lesion_node>$lesion_node</lesion_node></lesion>\n";
    }
    $i++;
}	
}

function islesionnew ($lesion_number) {
// see if lesion number existed at baseline
global $teamid,$patient_id,$dbconnect,$new_lesion,$baseline_size,$baseline_percent_change;
global $baseline_date,$current_date,$current_size;
global $study_percentpr,$study_percentpd;
global $debug;
$query = "select lesion_number,lesion_target,lesion_size,lesion_node from lesion where lesion_teamid = $teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$baseline_date' and lesion_number = $lesion_number";
if ($debug) echo "Is this a new lesion = $query";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
if ($debug) echo "got $num rows back";
$new_lesion = 'Y';
$baseline_size = 0;
$baseline_percent_change = 0;
$i=0;
while ($i < $num) {
   $baseline_size=mysql_result($dbresult,$i,"lesion_size");
   $new_lesion = 'N';
   $i++;
   }
if ($debug) echo "calling percent_changed with $current_size,$baseline_size and new_lesion = $new_lesion\n ";
list ($baseline_percent_change, $tempresponse) = percent_changed($current_size,$baseline_size); 
if ($debug) echo "got back $baseline_percent_change, $tempresponse";
return $new_lesion;
}
function getallsmallest ($lesion_number) {
// select the smallest size the lesion ever was in the past
global $debug;
global $teamid,$patient_id,$dbconnect,$new_lesion,$baseline_size,$baseline_percent_change;
global $baseline_date,$current_date,$current_size,$smallest_date,$smallest_size,$smallest_percent_change;
$query = "select lesion_size,lesion_scan_date from lesion where lesion_teamid = $teamid and lesion_patient_id = '$patient_id' and lesion_number = $lesion_number  order by lesion_size,lesion_scan_date";
//echo "query=$query\n";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$smallest_size = 0;
$smallest_date = $current_date;  
$i=0;
while ($i < $num) {
  $smallest_size=mysql_result($dbresult,$i,"lesion_size");
  $smallest_date=mysql_result($dbresult,$i,"lesion_scan_date");
  $i = $num; // breaks after first hit
  $i++;
}
if ($debug) echo "for smallest calling percent_changed with $current_size,$smallest_size\n ";
list ($smallest_percent_change, $tempresponse) = percent_changed($current_size,$smallest_size);
}
function calculateresponses(){
global $debug; 
global $target_response_code,$tb_response,$ts_response;
global $non_target_response_code,$ntb_response,$nts_response;
global $overall_response_code;
global $include_new,$new_tumor_total,$current_tumor_total;
if ($debug) echo "tb_response=$tb_response,ts_response=$ts_response\n";
if ($debug) echo "ntb_response=$ntb_response,nts_response=$nts_response\n";
// special code for irRC - set NT response to PD if new tumor burden is greater than 25% of current total
$skip_setting = 'N';
$percent = 0;
if ($debug)  echo "include new = $include_new\n";
if ($include_new == 'N') {
   // first calculate % new tumor against current tumor total
   if ($debug) echo "current_tumor_total = $current_tumor_total, new_tumor_total = $new_tumor_total\n";
   if ($current_tumor_total > 0) {
      $count1 = $new_tumor_total / $current_tumor_total;
      $percent = $count1 * 100;
   }
   if ($current_tumor_total == 0 and $new_tumor_total > 0) {
      // disease progression
      $ntb_response = 'PD';
      $nts_response = 'PD';
   }
   if ($percent > 24.99) {
      // disease progression
      $ntb_response = 'PD';
      $nts_response = 'PD';
   }
}
// table for Target and Non Target response
//TB Response	TS Response	Overall
//CR		CR		CR
//CR		SD		PR
//CR		PR		PR
//CR		PD		PD

//SD		SD		SD
//SD		CR		SD
//SD		PR		SD
//SD		PD		PD

//PR		PR		PR
//PR		CR		PR
//PR		PD		PD
//PR		SD		PR

//PD		PD		PD
//PD		CR		PD
//PD		SD		PD
//PD		PR		PD



if ($tb_response == 'CR' and $ts_response == 'CR') { $target_response_code = 'CR'; }
if ($tb_response == 'CR' and $ts_response == 'SD') { $target_response_code = 'PR'; }
if ($tb_response == 'CR' and $ts_response == 'PR') { $target_response_code = 'PR'; }
if ($tb_response == 'CR' and $ts_response == 'PD') { $target_response_code = 'PD'; }

if ($tb_response == 'SD' and $ts_response == 'SD') { $target_response_code = 'SD'; }
if ($tb_response == 'SD' and $ts_response == 'CR') { $target_response_code = 'SD'; }
if ($tb_response == 'SD' and $ts_response == 'PR') { $target_response_code = 'SD'; }
if ($tb_response == 'SD' and $ts_response == 'PD') { $target_response_code = 'PD'; }

if ($tb_response == 'PR' and $ts_response == 'PR') { $target_response_code = 'PR'; }
if ($tb_response == 'PR' and $ts_response == 'CR') { $target_response_code = 'PR'; }
if ($tb_response == 'PR' and $ts_response == 'PD') { $target_response_code = 'PD'; }
if ($tb_response == 'PR' and $ts_response == 'SD') { $target_response_code = 'PR'; }

if ($tb_response == 'PD' and $ts_response == 'PD') { $target_response_code = 'PD'; }
if ($tb_response == 'PD' and $ts_response == 'CR') { $target_response_code = 'PD'; }
if ($tb_response == 'PD' and $ts_response == 'SD') { $target_response_code = 'PD'; }
if ($tb_response == 'PD' and $ts_response == 'PR') { $target_response_code = 'PD'; }

if ($ntb_response == 'CR' and $nts_response == 'CR') { $non_target_response_code = 'CR'; }
if ($ntb_response == 'CR' and $nts_response == 'SD') { $non_target_response_code = 'PR'; }
if ($ntb_response == 'CR' and $nts_response == 'PR') { $non_target_response_code = 'PR'; }
if ($ntb_response == 'CR' and $nts_response == 'PD') { $non_target_response_code = 'PD'; }

if ($ntb_response == 'SD' and $nts_response == 'SD') { $non_target_response_code = 'SD'; }
if ($ntb_response == 'SD' and $nts_response == 'CR') { $non_target_response_code = 'SD'; }
if ($ntb_response == 'SD' and $nts_response == 'PR') { $non_target_response_code = 'SD'; }
if ($ntb_response == 'SD' and $nts_response == 'PD') { $non_target_response_code = 'PD'; }

if ($ntb_response == 'PR' and $nts_response == 'PR') { $non_target_response_code = 'PR'; }
if ($ntb_response == 'PR' and $nts_response == 'CR') { $non_target_response_code = 'PR'; }
if ($ntb_response == 'PR' and $nts_response == 'PD') { $non_target_response_code = 'PD'; }
if ($ntb_response == 'PR' and $nts_response == 'SD') { $non_target_response_code = 'PR'; }

if ($ntb_response == 'PD' and $nts_response == 'PD') { $non_target_response_code = 'PD'; }
if ($ntb_response == 'PD' and $nts_response == 'CR') { $non_target_response_code = 'PD'; }
if ($ntb_response == 'PD' and $nts_response == 'SD') { $non_target_response_code = 'PD'; }
if ($ntb_response == 'PD' and $nts_response == 'PR') { $non_target_response_code = 'PD'; }


// overall response
if ($target_response_code == 'CR' and $non_target_response_code == 'CR') { $overall_response_code = 'CR'; }
if ($target_response_code == 'CR' and $non_target_response_code == 'SD') { $overall_response_code = 'PR'; }
if ($target_response_code == 'CR' and $non_target_response_code == 'PR') { $overall_response_code = 'PR'; }
if ($target_response_code == 'CR' and $non_target_response_code == 'PD') { $overall_response_code = 'PD'; }

if ($target_response_code == 'SD' and $non_target_response_code == 'SD') { $overall_response_code = 'SD'; }
if ($target_response_code == 'SD' and $non_target_response_code == 'CR') { $overall_response_code = 'SD'; }
if ($target_response_code == 'SD' and $non_target_response_code == 'PR') { $overall_response_code = 'SD'; }
if ($target_response_code == 'SD' and $non_target_response_code == 'PD') { $overall_response_code = 'PD'; }

if ($target_response_code == 'PR' and $non_target_response_code == 'PR') { $overall_response_code = 'PR'; }
if ($target_response_code == 'PR' and $non_target_response_code == 'CR') { $overall_response_code = 'PR'; }
if ($target_response_code == 'PR' and $non_target_response_code == 'PD') { $overall_response_code = 'PD'; }
if ($target_response_code == 'PR' and $non_target_response_code == 'SD') { $overall_response_code = 'PR'; }

if ($target_response_code == 'PD' and $non_target_response_code == 'PD') { $overall_response_code = 'PD'; }
if ($target_response_code == 'PD' and $non_target_response_code == 'CR') { $overall_response_code = 'PD'; }
if ($target_response_code == 'PD' and $non_target_response_code == 'SD') { $overall_response_code = 'PD'; }
if ($target_response_code == 'PD' and $non_target_response_code == 'PR') { $overall_response_code = 'PD'; }


}
function createhtmlresponse() {
global $debug,$dbconnect,$baseline_date,$teamid;
global $company_name,$patient_id,$patient_name,$patient_doctor,$patient_study_id,$study_owner,$study_name;
global $study_percentpr,$study_percentpd,$overall_response_code,$target_response_code,$tc_total_size;
global $tb_total_size,$tb_percent_change,$tb_response,$ts_total_size,$ts_percent_change,$ts_response;
global $non_target_response_code,$ntc_total_size,$ntb_total_size,$ntb_percent_change,$ntb_response;
global $nts_total_size,$nts_percent_change,$nts_response;
// create header for report
$today = date("m/d/y");
$today = date("l F d, Y");
$web_overall_response_code = "Unknown...Contact Support";
//if ($overall_response_code == "CR") { $web_overall_response_code = "Complete Response";}
//if ($overall_response_code == "PR") { $web_overall_response_code = "Partial Response";}
//if ($overall_response_code == "SD") { $web_overall_response_code = "Stable Disease";}
//if ($overall_response_code == "PD") { $web_overall_response_code = "Progressive Disease";}
// color highlighted
if ($overall_response_code == "CR") { $web_overall_response_code = "<b><font style='BACKGROUND-COLOR: white;color:green'>Complete Response</font></b>";}
if ($overall_response_code == "PR") { $web_overall_response_code = "<b><font style='BACKGROUND-COLOR: black;color:yellow'>&nbsp;Partial Response&nbsp;</font></b>";}
if ($overall_response_code == "SD") { $web_overall_response_code = "<b><font style='BACKGROUND-COLOR: black;color:white'>&nbsp;Stable Disease&nbsp;</font></b>";}
if ($overall_response_code == "PD") { $web_overall_response_code = "<b><font style='BACKGROUND-COLOR: white;color:red'>Progressive Disease</font></b>";}
$web_target_response_code = "Unknown...Contact Support";
if ($target_response_code == "CR") { $web_target_response_code = "Complete Response";}
if ($target_response_code == "PR") { $web_target_response_code = "Partial Response";}
if ($target_response_code == "SD") { $web_target_response_code = "Stable Disease";}
if ($target_response_code == "PD") { $web_target_response_code = "Progressive Disease";}
$web_non_target_response_code = "Unknown...Contact Support";
if ($non_target_response_code == "CR") { $web_non_target_response_code = "Complete Response";}
if ($non_target_response_code == "PR") { $web_non_target_response_code = "Partial Response";}
if ($non_target_response_code == "SD") { $web_non_target_response_code = "Stable Disease";}
if ($non_target_response_code == "PD") { $web_non_target_response_code = "Progressive Disease";}

$target_current = "Current " . $tc_total_size . "mm " ."Baseline " . $tb_total_size . "mm " . $tb_percent_change . "% " . $tb_response;
$target_smallest = "Current " . $tc_total_size . "mm " ."Smallest " . $ts_total_size . "mm " . $ts_percent_change . "% " . $ts_response;

$nontarget_current = "Current " . $ntc_total_size . "mm " ."Baseline " . $ntb_total_size . "mm " . $ntb_percent_change . "% " . $ntb_response;
$nontarget_smallest = "Current " . $ntc_total_size . "mm " ."Smallest " . $nts_total_size . "mm " . $nts_percent_change . "% " . $nts_response;

//$header = @"<html><head><meta content=\"text/html; charset=ISO-8859-1\"http-equiv=\"Content-Type\"><title>TEAM Response Report</title></head><body><div align=\"center\"><big><b><big>$company_name</big></big></b><br><b><big>TEAM - Patient Response Report - as of $today<br>Patient: $patient_name Patient ID#: $patient_id</big></b><br><b><big>Doctor: $patient_doctor,  Study:$patient_study_id - $study_owner / $study_name<br>Partial Response $study_percentpr% or less<br>Progressive Disease $study_percentpd% or more</big></b><br><br><big><b><big>Overall Response - $web_overall_response_code<br><br></big>Target Response: $web_target_response_code<br>$target_current<br>$target_smallest<br><br>Non Target Response: $web_non_target_response_code<br>$nontarget_current<br>$nontarget_smallest</b></big><br>";

$header = @"<html><head><meta content=\"text/html; charset=ISO-8859-1\"http-equiv=\"Content-Type\"><title>TEAM Response Report</title></head><body><div align=\"center\"><big><b><big>$company_name</big></big></b><br><b><big>TEAM - Patient Response Report - as of $today<br>Patient: $patient_name Patient ID#: $patient_id</big></b><br><b><big>Doctor: $patient_doctor,  Study:$patient_study_id - $study_owner / $study_name<br>Study Criteria: Partial Response $study_percentpr%, Progressive Disease +$study_percentpd%</big></b><br><br><big><b><big>Overall Response - $web_overall_response_code<br><br></big>Target Response: $web_target_response_code<br>$target_current<br>$target_smallest<br><br>Non Target Response: $web_non_target_response_code<br>$nontarget_current<br>$nontarget_smallest</b></big><br>";

echo $header;

// get all scans for this patient
$query = "select scan_date from scan where scan_teamid = $teamid and scan_patient_id = '$patient_id' order by scan_date DESC";
$dbresult = mysql_query($query, $dbconnect);
$num=mysql_numrows($dbresult);
$i=0;
while ($i < $num) {
  $scan_date=mysql_result($dbresult,$i,"scan_date");
  $web_date = date("l F d, Y",strtotime($scan_date));
  $diff = strtotime($scan_date, 0) - strtotime($baseline_date, 0);
  $scanweek = floor($diff / 604800) +1;
  $scanHeader = "<br><br></font><font color=\"#3366ff\"><u><big><b>$patient_name - Scan Date: $web_date - Week #$scanweek</b></big></u></font><br><br><table align=\"center\" border=\"1\" cellpadding=\"2\" cellspacing=\"2\"width=\"75%%\"><tbody><tr><td align=\"center\" valign=\"top\">Target/Non-Target<br></td><td align=\"center\" valign=\"top\">Lesion/Lymph Node<br></td><td align=\"center\" valign=\"top\">Size in MM<br></td><td valign=\"top\">Comments<br></td></tr>";
  echo $scanHeader;
  // now get all the lesions for this scan date
  $query2 = "select lesion_number, lesion_size, lesion_target, lesion_node, lesion_comment from lesion where lesion_teamid = $teamid and lesion_patient_id = '$patient_id' and lesion_scan_date = '$scan_date' order by lesion_number";
  $dbresult2 = mysql_query($query2, $dbconnect);
  $num2=mysql_numrows($dbresult2);
  $j=0;
  $total_scansize = 0;
  while ($j < $num2) {
    $lesion_number=mysql_result($dbresult2,$j,"lesion_number");
    $lesion_size=mysql_result($dbresult2,$j,"lesion_size");
    $lesion_target=mysql_result($dbresult2,$j,"lesion_target");
    $lesion_node=mysql_result($dbresult2,$j,"lesion_node");
    $lesion_comment=mysql_result($dbresult2,$j,"lesion_comment");
    if ($lesion_target == "Y") {$web_target = "Target #";} else {$web_target = "Non Target #";}
    if ($lesion_node == "Y") {$web_lesion = "Lymph Node";} else {$web_lesion = "Lesion";}
    $total_scansize = $total_scansize + $lesion_size;
    $new = islesionnew($lesion_number);
    if ($new == "Y") $web_lesion = "*NEW* " . $web_lesion;
    $scanLesionDetail = "<tr><td align=\"center\" valign=\"top\">$web_target$lesion_number<br></td><td align=\"center\" valign=\"top\">$web_lesion<br></td><td align=\"center\" valign=\"top\">$lesion_size<br></td><td valign=\"top\">$lesion_comment<br></td></tr>";
    echo $scanLesionDetail;
    $j++;
    }
    // do total for the scan
    $scanLesionTotal = "<tr><td align=\"center\" valign=\"top\"> <br></td><td align=\"center\" valign=\"top\"><b><font color=\"#3366ff\">Total</font></b><br></td><td align=\"center\" valign=\"top\"><font color=\"#3366ff\">$total_scansize</font></b><br></td><td valign=\"top\"> <br></td></tr></table>";
    echo $scanLesionTotal;
  $i++;
}
$endHtml = "</tbody></table><br></body></html>";
echo $endHtml;
}
function getcompany() {
$mysql_hostx = "localhost";
$mysql_databasex = "team_db1";
$mysql_userx = "team_admin";
$mysql_passwordx = "Teamdb143143";
global $teamid,$company_name;
if(!$dbconnectx = mysql_connect($mysql_hostx,$mysql_userx,$mysql_passwordx)) {
   echo "Connection failed to the host 'localhost'.";
   exit;
} 
@mysql_select_db($mysql_databasex) or die( "Unable to select database");
$query = "select company_name from settings where teamid = $teamid";
$dbresultx = mysql_query($query, $dbconnectx);
$num=mysql_numrows($dbresultx);
$i=0;
while ($i < $num) {
  $company_name=mysql_result($dbresultx,$i,"company_name");
  $i = $num; // breaks after first hit
  $i++;
}
}
?>