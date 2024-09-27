<?php
$embedUrl = '';
date_default_timezone_set('Africa/Nairobi');

require_once 'notification.php';
$meetingID = '';
$apiKey = 'Mh75JZKD7pIyBM575tqwqOp3RBWyDbkWrK4fcDB5VAEh0KFFZ6YKKGOxB8q4y5e5';    
$teamId = '777fbba3-cfe1-4e81-b795-36b0ff072a8a';
$roomId = 'santihealth';
$username = $_COOKIE['fname'].''.$_COOKIE['sname'];


$header = json_encode([
    'typ' => 'JWT', 
    'alg' => 'HS256'
]);

// Create token payload as a JSON string
$payload = json_encode([
    'td' => $teamId,
    'rd' => $roomId,
    'u' => ''.$username.'',
    'role' => 'moderator'
]);

// Encode Header to Base64Url String
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
// Encode Payload to Base64Url String
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
// Create Signature Hash
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $apiKey, true);
// Encode Signature to Base64Url String
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
$token = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

function callDigitalSambaAPI($endpoint, $data = [], $method = 'GET') {
    $apiUrl = 'https://api.digitalsamba.com/api/v1/'.$endpoint;
    $apiKey = 'Mh75JZKD7pIyBM575tqwqOp3RBWyDbkWrK4fcDB5VAEh0KFFZ6YKKGOxB8q4y5e5';    

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer '. $apiKey,
        'Accept: Application/json',
    ];

    $ch = curl_init();

    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method == 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo "<center><span style='background-color:pink;width:100%;left:0px;'>You are offline</span></center>";
    }
    curl_close($ch);

    return json_decode($result, true);
};

$meetingData = [
    'title' => 'Doc-patient Meeting',
    'start_time' => '2024-05-30T10:00:00Z',
    'duration' => 60,
    'privacy' => 'public',
    'settings' => [
        'host_video' => true,
        'participant_video' => true,
    ],
    ];

$response = callDigitalSambaAPI('rooms', $meetingData, 'POST');

if ($response && isset($response['id'])) {
    $meetingID = $response['id'] ?? NULL;
} else {
    $meetingIDErr = "Failed to create meeting.";
}
$embedUrl = "https://santihealth.digitalsamba.com/".$meetingID;

// Get all available rooms and participants for admin
$requestUrl = 'https://api.digitalsamba.com/api/v1/rooms';
$ch = curl_init($requestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer '.$apiKey,
    'Content-Type: application/json',
    'Accept: Application/json',
]);
$rooms = curl_exec($ch);
curl_close($ch);
$rooms = json_decode($rooms, true) ?? NULL;

if ($rooms && isset($rooms['total_count'])) {
    $totalRooms = $rooms['total_count'].' Rooms created' ?? NULL;
} else {
    $meetingIDErr = "Failed to create meeting.";
}
?>