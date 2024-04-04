<?php session_start();
include 'includes/config.php';
$status = "logged-out";
$email = $_COOKIE['email'];
$query = $conn->prepare("UPDATE members SET status=:status WHERE email=:email");
$query = $conn->prepare("UPDATE santi_data SET status=:status WHERE email=:email");
$query->bindParam(':email', $email);
$query->bindParam(':status', $status);
$query->execute();
setcookie("fname", '', time() - 3600);
setcookie("sname", '', time() - 3600);
setcookie("email", '', time() - 3600);
session_unset();
session_destroy();
header("location:index.php");
?>