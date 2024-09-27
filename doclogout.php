<?php
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookie', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.entropy_length', 32);
ini_set('session.hash_function', 'sha256');
session_start();
include 'includes/config.php';
$status = "logged-out";
$email = $_COOKIE['email'];
$query1 = $conn->prepare("UPDATE santi_data SET status=:status WHERE email=:email");
$query1->bindParam(':email', $email);
$query1->bindParam(':status', $status);
$query1->execute();
$_SESSION = [];
if (ini_get('session.use_cookies')){
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000, 
	$params['path'], $params['domain'], $params['secure'], $params['httponly']
);
}
setcookie("fname", '', time() - 3600);
setcookie("sname", '', time() - 3600);
setcookie("email", '', time() - 3600);
session_unset();
session_destroy();
header("location:index.php");
?>