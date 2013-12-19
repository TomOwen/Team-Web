<?php
// TEAM audit logger
//require '../db/dbauditaccess.php';
$mysql_host = "localhost";
$mysql_database = "team_audit";
$mysql_user = "team_audit";
$mysql_password = "Teamdb143143";
if(!$dbauditconnect = mysql_connect($mysql_host,$mysql_user,$mysql_password)) {
   echo "Connection failed to the host 'localhost'.";
   exit;
} 
@mysql_select_db($mysql_database) or die( "Unable to select database");
// if coming in from web teamid may be via GET
if ($user_teamid == '') {
	$user_teamid = $_GET['teamid'];
	}
$auditlogging = 'Y';
$vipalert = 'Y';
$ip = $_SERVER['REMOTE_ADDR'];
//echo "patient_id = $patient_id";
if ($vipalert == 'Y' and $patient_id != "") {
// check if a vip patient
	$queryvip = "select minutes_between, alert_email, last_email from vipalert where teamid = $user_teamid and patient_id = '$patient_id'";
	//echo "queryvip=$queryvip";
	$dbresultvip = mysql_query($queryvip, $dbauditconnect);
	$numvip=mysql_numrows($dbresultvip);
	$ivip=0;
	while ($ivip < $numvip) {
  		$minutes_between=mysql_result($dbresultvip,$ivip,"minutes_between");
  		$alert_email=mysql_result($dbresultvip,$ivip,"alert_email");
  		$last_email=mysql_result($dbresultvip,$ivip,"last_email");
  		$ivip++;
  	}
  	if ($numvip == 1) {
  		/*
  		$current_datetime = date('Y-m-d H:i');
  		echo "current = $current_datetime<br>";
  		//$datets = strtotime(date("Y-m-d H:i", strtotime($current_datetime)) . " +1 minute");
  		$datets = strtotime(date("Y-m-d H:i", strtotime($current_datetime)) . " +$minutes_between minute");
  		$dateformatted = date("Y-m-d H:i",$datets);
  		echo "current +$minutes_between minutes is $dateformatted<br><br>";
  		
  		
  		
  		$lastplusbetween = strtotime(date("Y-m-d H:i:s", strtotime($last_email)) . " +$minutes_between minute");
  		$newtimetoemail = date("Y-m-d H:i:s",$lastplusbetween);
  		echo "last email was sent at $last_email<br>";
  		echo "new time to email is $newtimetoemail<br><br>";
  		
  		echo "datets=$datets and new time = $lastplusbetween<br>";
  		*/
  		// get current timestamp
  		$current_datetime = date('Y-m-d H:i:s');
  		$datets = strtotime(date("Y-m-d H:i:s", strtotime($current_datetime)));
  		//echo "current ts = $datets<br>";
  		$lastplusbetweents = strtotime(date("Y-m-d H:i:s", strtotime($last_email)) . " +$minutes_between minute");
  		$newtimetoemail = date("Y-m-d H:i:s",$lastplusbetweents);
  		//echo "comparing (current) $datets > (next time) $lastplusbetweents<br>";
  		
  		if ($datets > $lastplusbetweents) {
  			//echo "updating time to $current_datetime";
  			// update time and send email
  			$queryvip = "update vipalert set last_email = '$current_datetime' where teamid = $user_teamid and patient_id = '$patient_id'";
			$dbresultvip = mysql_query($queryvip, $dbauditconnect);
			$to = $alert_email;
			$body = "At $current_datetime $user_name accessed Patient ID $patient_id.\n\nThe access came from from IP address $ip.\n\nAnother alert will be sent in $minutes_between minutes if activity continues.\n\nClick on link to see where the IP address is located.\n\nhttp://whatismyipaddress.com/ip/$ip\n\n\nSent from the TEAM VIP Alert System";
  			$subject = "TEAM - VIP Patient ID $patient_id Accessed";
  			$headers = 'From: Admin@team.com' . "\r\n" . 'Reply-To: webmaster@team.com' . "\r\n" .'X-Mailer: PHP/' . phpversion();
  			mail($to, $subject, $body, $headers);
  			//echo "sending email and set last_email = $current_datetime<br>";
  			
  		}
  	}
}
// audit_type
// 1 - patient view v = view, a = add, d = delete, m = modify, l = list all
// 2 - scan
// 3 - lesion
// 4 - doctor

$current_datetime = date('Y-m-d H:i:s');
//echo "processing $audit_type\n";
switch($audit_type)
{
// patients
case 'l1':
$audit_query = "INSERT DELAYED INTO patient (ip_number, audit_user_name, audit_datetime, audit_type, patient_teamid, patient_id, patient_name, patient_study_id ,patient_doctor) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, 'List All', 'n/a', 'n/a', 'n/a')";
  break;
case 'v1':
$audit_query = "INSERT DELAYED INTO patient (ip_number, audit_user_name, audit_datetime, audit_type, patient_teamid, patient_id, patient_name, patient_study_id ,patient_doctor) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$patient_name', '$patient_study_id', '$patient_doctor')";
  break;
case 'a1':
$audit_query = "INSERT DELAYED INTO patient (ip_number, audit_user_name, audit_datetime, audit_type, patient_teamid, patient_id, patient_name, patient_study_id ,patient_doctor) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$patient_name', '$patient_study_id', '$patient_doctor')";
  break;

case 'd1':
$audit_query = "INSERT DELAYED INTO patient (ip_number, audit_user_name, audit_datetime, audit_type, patient_teamid, patient_id, patient_name, patient_study_id ,patient_doctor) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', 'n/a', 'n/a', 'n/a')";
  break;
case 'm1':
$audit_query = "INSERT DELAYED INTO patient (ip_number, audit_user_name, audit_datetime, audit_type, patient_teamid, patient_id, patient_name, patient_study_id ,patient_doctor) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$patient_name', '$patient_study_id', '$patient_doctor')";
  break;	

// scans
case 'v2':
$audit_query = "INSERT DELAYED INTO scan (ip_number, audit_user_name, audit_datetime, audit_type, scan_teamid, scan_patient_id, scan_date, scan_report_online) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$scan_date', '$scan_report_online')";
  break;
case 'm2':
$audit_query = "INSERT DELAYED INTO scan (ip_number, audit_user_name, audit_datetime, audit_type, scan_teamid, scan_patient_id, scan_date, scan_report_online) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$scan_date', '$scan_report_online')";
  break;
case 'a2':
$audit_query = "INSERT DELAYED INTO scan (ip_number, audit_user_name, audit_datetime, audit_type, scan_teamid, scan_patient_id, scan_date, scan_report_online) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$scan_date', '$scan_report_online')";
  break;
case 'd2':
$audit_query = "INSERT DELAYED INTO scan (ip_number, audit_user_name, audit_datetime, audit_type, scan_teamid, scan_patient_id, scan_date, scan_report_online) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$scan_date', 'n/a')";
  break;
case 'l2':
$audit_query = "INSERT DELAYED INTO scan (ip_number, audit_user_name, audit_datetime, audit_type, scan_teamid, scan_patient_id, scan_date, scan_report_online) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$current_datetime', 'n/a')";
  break;
  
// lesions
case 'a3':
$audit_query = "INSERT INTO lesion (ip_number, audit_user_name, audit_datetime, audit_type, lesion_teamid, lesion_patient_id, lesion_scan_date, lesion_number, lesion_size, lesion_comment, lesion_target, lesion_media_type, lesion_media_online, lesion_node) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$scan_date', $lesion_number, $lesion_size, '$lesion_comment', '$lesion_target', '$lesion_media_type', '$lesion_media_online', '$lesion_node')";
  break;
case 'm3':
$audit_query = "INSERT INTO lesion (ip_number, audit_user_name, audit_datetime, audit_type, lesion_teamid, lesion_patient_id, lesion_scan_date, lesion_number, lesion_size, lesion_comment, lesion_target, lesion_media_type, lesion_media_online, lesion_node) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$scan_date', $lesion_number, $lesion_size, '$lesion_comment', '$lesion_target', '$lesion_media_type', '$lesion_media_online', '$lesion_node')";
  break;
case 'd3':
$audit_query = "INSERT INTO lesion (ip_number, audit_user_name, audit_datetime, audit_type, lesion_teamid, lesion_patient_id, lesion_scan_date, lesion_number, lesion_size, lesion_comment, lesion_target, lesion_media_type, lesion_media_online, lesion_node) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$scan_date', $lesion_number, 0, 'n/a', 'n/a', 'n/a', 'n/a', 'n/a')";
  break;
case 'v3':
$audit_query = "INSERT INTO lesion (ip_number, audit_user_name, audit_datetime, audit_type, lesion_teamid, lesion_patient_id, lesion_scan_date, lesion_number, lesion_size, lesion_comment, lesion_target, lesion_media_type, lesion_media_online, lesion_node) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$patient_id', '$scan_date', $lesion_number, 0, 'n/a', 'n/a', 'n/a', 'n/a', 'n/a')";
  break;
  
// doctors
case 'l4':
$audit_query = "INSERT INTO doctor (ip_number, audit_user_name, audit_datetime, audit_type, doctor_teamid, doctor_name, doctor_info) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, 'List', 'n/a')";
  break;
case 'v4':
$audit_query = "INSERT INTO doctor (ip_number, audit_user_name, audit_datetime, audit_type, doctor_teamid, doctor_name, doctor_info) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$doctor_name', '$doctor_info')";
  break;
case 'd4':
$audit_query = "INSERT INTO doctor (ip_number, audit_user_name, audit_datetime, audit_type, doctor_teamid, doctor_name, doctor_info) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$doctor_name', '$doctor_info')";
  break;
case 'm4':
$audit_query = "INSERT INTO doctor (ip_number, audit_user_name, audit_datetime, audit_type, doctor_teamid, doctor_name, doctor_info) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$doctor_name', '$doctor_info')";
  break;
case 'a4':
$audit_query = "INSERT INTO doctor (ip_number, audit_user_name, audit_datetime, audit_type, doctor_teamid, doctor_name, doctor_info) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$doctor_name', '$doctor_info')";
  break;
  
// studies
case 'l5':
$audit_query = "INSERT INTO studies (ip_number, audit_user_name, audit_datetime, audit_type, study_teamid,  study_id, study_owner, study_name, study_url, study_percentpr, study_percentpd, study_seats) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, 'List', 'n/a', 'n/a', 'n/a', 0, 0, 0)";
  break;
case 'v5':
$audit_query = "INSERT INTO studies (ip_number, audit_user_name, audit_datetime, audit_type, study_teamid,  study_id, study_owner, study_name, study_url, study_percentpr, study_percentpd, study_seats) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$study_id', '$study_owner', '$study_name', '$study_url', $study_percentpr, $study_percentpd, $study_seats)";
  break;
case 'd5':
$audit_query = "INSERT INTO studies (ip_number, audit_user_name, audit_datetime, audit_type, study_teamid,  study_id, study_owner, study_name, study_url, study_percentpr, study_percentpd, study_seats) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$study_id', 'n/a', 'n/a', 'n/a', 0, 0, 0)";
  break;
case 'm5':
$audit_query = "INSERT INTO studies (ip_number, audit_user_name, audit_datetime, audit_type, study_teamid,  study_id, study_owner, study_name, study_url, study_percentpr, study_percentpd, study_seats) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$study_id', '$study_owner', '$study_name', '$study_url', $study_percentpr, $study_percentpd, $study_seats)";
  break;
case 'a5':
$audit_query = "INSERT INTO studies (ip_number, audit_user_name, audit_datetime, audit_type, study_teamid,  study_id, study_owner, study_name, study_url, study_percentpr, study_percentpd, study_seats) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$study_id', '$study_owner', '$study_name', '$study_url', $study_percentpr, $study_percentpd, $study_seats)";
  break;

// settings
case 'v6':
$audit_query = "INSERT INTO settings (ip_number, audit_user_name, audit_datetime, audit_type, teamid, company_name, db_server, db_user, db_password, imagedoc_server, imagedoc_user, imagedoc_password, image_type, doc_type, web_server) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$company_name', '$db_server', '$db_user', '$db_password', '$imagedoc_server', '$imagedoc_user', '$imagedoc_password', '$image_type', '$doc_type', '$web_server')";
  break;
case 'm6':
$audit_query = "INSERT INTO settings (ip_number, audit_user_name, audit_datetime, audit_type, teamid, company_name, db_server, db_user, db_password, imagedoc_server, imagedoc_user, imagedoc_password, image_type, doc_type, web_server) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$company_name', '$db_server', '$db_user', '$db_password', '$imagedoc_server', '$imagedoc_user', '$imagedoc_password', '$image_type', '$doc_type', '$web_server')";
  break;

// users
case 'a7':
$audit_query = "INSERT INTO users (ip_number, audit_user_name, audit_datetime, audit_type, user_teamid, user_name, user_password, user_email, user_admin_access) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$user_name2', '$user_password', '$user_email', '$user_admin_access2')";
  break;
case 'l7':
$audit_query = "INSERT INTO users (ip_number, audit_user_name, audit_datetime, audit_type, user_teamid, user_name, user_password, user_email, user_admin_access) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, 'List Only', 'n/a', 'n/a', 'n/a')";
  break;
case 'v7':
$audit_query = "INSERT INTO users (ip_number, audit_user_name, audit_datetime, audit_type, user_teamid, user_name, user_password, user_email, user_admin_access) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$user_name2', '$user_password', '$user_email', '$user_admin_access2')";
  break;
case 'm7':
$audit_query = "INSERT INTO users (ip_number, audit_user_name, audit_datetime, audit_type, user_teamid, user_name, user_password, user_email, user_admin_access) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$user_name2', '$user_password', '$user_email', '$user_admin_access2')";
  break;
case 'd7':
$audit_query = "INSERT INTO users (ip_number, audit_user_name, audit_datetime, audit_type, user_teamid, user_name, user_password, user_email, user_admin_access) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$user_name2', 'n/a', 'n/a', 'n/a')";
  break;
case 'g7':
$audit_query = "INSERT INTO users (ip_number, audit_user_name, audit_datetime, audit_type, user_teamid, user_name, user_password, user_email, user_admin_access) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$user_name2', 'n/a', 'n/a', 'n/a')";
  break;
case 'p7':
$audit_query = "INSERT INTO users (ip_number, audit_user_name, audit_datetime, audit_type, user_teamid, user_name, user_password, user_email, user_admin_access) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$user_name2', 'n/a', 'n/a', 'n/a')";
  break;
case 'r7':
$audit_query = "INSERT INTO users (ip_number, audit_user_name, audit_datetime, audit_type, user_teamid, user_name, user_password, user_email, user_admin_access) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, '$user_name2', 'n/a', 'n/a', 'n/a')";
  break;
default:

// reports
case 'vla8':
$audit_query = "INSERT INTO reports (ip_number, audit_user_name, audit_datetime, audit_type, teamid, description, patient_id, study_id) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $teamid, 'Patients and Studies Reports and Graphs', 'n/a', 'n/a')";
  break;
case 'vsb8':
$audit_query = "INSERT INTO reports (ip_number, audit_user_name, audit_datetime, audit_type, teamid, description, patient_id, study_id) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $teamid, 'BAR Chart for Study - $study_id', 'n/a', '$study_id')";
  break;
case 'vsp8':
$audit_query = "INSERT INTO reports (ip_number, audit_user_name, audit_datetime, audit_type, teamid, description, patient_id, study_id) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $teamid, 'PIE Chart for Study - $study_id', 'n/a', '$study_id')";
  break;
case 'vpr8':
$audit_query = "INSERT INTO reports (ip_number, audit_user_name, audit_datetime, audit_type, teamid, description, patient_id, study_id) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $teamid, 'Response Report for Patient - $patient_id', '$patient_id', 'n/a')";
  break;
case 'vpb8':
$audit_query = "INSERT INTO reports (ip_number, audit_user_name, audit_datetime, audit_type, teamid, description, patient_id, study_id) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $teamid, 'BAR Chart for Patient - $patient_id', '$patient_id', 'n/a')";
  break;
case 'vr8':
$audit_query = "INSERT INTO reports (ip_number, audit_user_name, audit_datetime, audit_type, teamid, description, patient_id, study_id) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $teamid, 'Refreshed Graph and Report data', 'n/a', 'n/a')";
  break;
case 'vf8':
$audit_query = "INSERT INTO reports (ip_number, audit_user_name, audit_datetime, audit_type, teamid, description, patient_id, study_id) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, 'Viewed File $fn', '$patient_id', '$study_id')";
  break;
case 'vfm8':
$audit_query = "INSERT INTO reports (ip_number, audit_user_name, audit_datetime, audit_type, teamid, description, patient_id, study_id) VALUES ('$ip', '$user_name', '$current_datetime', '$audit_type', $user_teamid, 'Compared Images $fn', '$patient_id', 'n/a')";
  break;
  $audit_type = "0";
}
//echo "audit query = $audit_query";
if ($auditlogging == 'Y') $dbauditresult = mysql_query($audit_query, $dbauditconnect);
?>