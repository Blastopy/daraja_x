<?php

date_default_timezone_set('Africa/Nairobi');
include 'includes/config.php';

$dt = new DateTime("now", new DateTimeZone('Africa/Nairobi'));
$docs = $confirm = $lseo = $lseo0 = '';
$sessionBookings = array();
$doctorslist = array();
$notification = array();


$todays = date('Y-m-d H:i:s');
$etdate = substr($todays, 0, 10);

$query = $conn->prepare('SELECT date_of_schedule, patient_email, doc_email, doc_name, confirmation FROM schedules');
$query ->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
foreach($query as $results){
	//These gets all the dates of schedules from the database abd finds all the dates matching the date of today
	$edate = $results['date_of_schedule'];
	$confirm = $results['confirmation'];
	$docs = $results['doc_name'];
	$todays_schedule = substr($edate, 0, 10);
	$result = array_filter($results, function($item) use ($etdate) {
		return strpos($item, $etdate) !== false;
	});
	if(empty($result['date_of_schedule'])){
		$leo = NULL;
	}else $leo = $result['date_of_schedule'];
}

$date_query = $conn -> prepare('SELECT * FROM schedules WHERE date_of_schedule LIKE :result');
$searchDate = "%$etdate%";
$date_query->bindParam(':result', $searchDate, PDO::PARAM_STR);
$date_query->execute();
$date_querys = $date_query->fetchAll(PDO::FETCH_ASSOC);
foreach($date_querys as $todays_jobs){
	// $leo = $todays_jobs['date_of_schedule'];
	$lseo = $todays_jobs['patient_email'];
	$lseo0 = $todays_jobs['doc_email'];
	$lseo1 = $todays_jobs['doc_name'];
	$lseo2 = $todays_jobs['confirmation'];
}
// Check from the db of members who've paid for their consultation and raise a notification on the date of appointment and open the conferencing video api.

$notifications = array_merge_recursive($doctorslist, $sessionBookings, $notification);

$notificationJSON = json_encode($notifications);
if (!empty($notificationJSON)) {
	NULL;
} else {
	echo $notificationJSON ?? NULL;
}
?>