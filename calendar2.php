<?php
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookie', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.entropy_length', 32);
ini_set('session.hash_function', 'sha256');
session_start();
if (empty($_SESSION['email']) && empty($_COOKIE['fname']) && empty($_COOKIE['sname'])) {
	setcookie('fname', '', time() - 3600);
	setcookie('email', '',time() - 3600);
	setcookie('sname', '', time() - 3600);
	session_destroy();
	header('location:logout.php');
}else {
	if (isset($_SESSION['email'])){
		session_regenerate_id(true);
		$_SESSION['email'] = true;
	}
	if (!isset($_SESSION['user_agent'])){
		$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	} elseif($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']){
		session_unset();
		session_destroy();
	}
	if (!isset($_SESSION['ip_address'])) {
		$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
	} elseif ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
		session_unset();
		session_destroy();
	}
	include 'includes/config.php';
	$fname = ucfirst($_COOKIE['fname']);
	$name = $_COOKIE['fname'];
	$sname = ucfirst($_COOKIE['sname']);
	$profile = substr($fname, 0, 1) . substr($sname, 0, 1);
	$email = $_COOKIE['email'];
	//---------//
	$fname = ucfirst($_COOKIE['fname']);
	$sname = ucfirst($_COOKIE['sname']);
	$email = $_COOKIE['email'];
	$method = "AES-256-ECB";
	$key = 'ab1cde2fg3hi4jk5lmn8opqrstuvwxyz';
	$cipherfname = base64_decode($fname);
	$ciphersname = base64_decode($sname);
	$cipheremail = base64_decode($email);
	$iv_size = openssl_cipher_iv_length($method);
	$firstname = openssl_decrypt($cipherfname, $method, $key, OPENSSL_RAW_DATA);
	$secondname = openssl_decrypt($ciphersname, $method, $key, OPENSSL_RAW_DATA);
	$cookiemail = openssl_decrypt($cipheremail, $method, $key, OPENSSL_RAW_DATA);
	$profile = $firstname.' '.$secondname;
	$dateErr = $returnFeedback = '';
	// Ensure you confirm the data retrieved from the database
	$patient_number = "0716065893";
	$doc_email = "ronnie@gmail.com";
	$patient_name = ucfirst($_COOKIE['fname']).' '.ucfirst($_COOKIE['sname']);
	$confirmation = "Online booking";
function test_inputs($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
if (empty($_SESSION['email']) && empty($_COOKIE['fname']) && empty($_COOKIE['sname'])) {
	setcookie('fname', '', time() - 3600);
	setcookie('email', '',time() - 3600);
	setcookie('sname', '', time() - 3600);
	session_destroy();
	header('location:logout.php');
}else {
	include 'includes/config.php';
	$fname = ucfirst($_COOKIE['fname']);
	$name = $_COOKIE['fname'];
	$sname = ucfirst($_COOKIE['sname']);
	include 'includes/config.php';
	$fname = ucfirst($_COOKIE['fname']);
	$sname = ucfirst($_COOKIE['sname']);
	$email = $_COOKIE['email'];
	$method = "AES-256-ECB";
	$key = 'ab1cde2fg3hi4jk5lmn8opqrstuvwxyz';
	$cipherfname = base64_decode($fname);
	$ciphersname = base64_decode($sname);
	$cipheremail = base64_decode($email);
	$iv_size = openssl_cipher_iv_length($method);
	$firstname = openssl_decrypt($cipherfname, $method, $key, OPENSSL_RAW_DATA);
	$secondname = openssl_decrypt($ciphersname, $method, $key, OPENSSL_RAW_DATA);
	$cookiemail = openssl_decrypt($cipheremail, $method, $key, OPENSSL_RAW_DATA);
	$email = $cookiemail;
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if (isset($_POST['patient_date'])){
			if(empty($_POST['patient_date']))
			{
				$dateErr = 'The appointment cannot be empty';
			}elseif(!empty($_POST['patient_date'])){
				test_inputs($_POST['patient_date']);
				$scheduleTime = filter_input(INPUT_POST, 'patient_date', FILTER_SANITIZE_NUMBER_INT);
				$scheduleTime =  str_replace('T', ' ', $_POST['patient_date']);
			}
		}
		if (empty($dateErr)){
			$sql = "INSERT INTO schedules (doc_email, patient_name, patient_email, patient_number, date_of_schedule, confirmation)
			VALUES (:doc_email, :patient_name, :patient_email, :patient_number, :date_of_schedule, :confirmation)";
			$query = $conn->prepare($sql);
			$query -> bindParam(':doc_email', $doc_email);
			$query -> bindParam(':patient_email', $email);
			$query -> bindParam(':patient_number', $patient_number);
			$query -> bindParam(':patient_name', $patient_name);
			$query -> bindParam(':date_of_schedule', $scheduleTime);
			$query -> bindParam(':confirmation', $confirmation);
			$query -> execute();
			if ($query == TRUE){
				$returnFeedback = "You've booked for the session succefully.";
				header('location:dashboard.php');
			}else {
				$returnFeedback = "Failed to book session, please try again.";
			}
		}
}
?>
<!DOCTYPE html> 
<html lang="en">
<head> 
	<meta charset="UTF-8"> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
	<meta name="viewport" content="width=device-width,  initial-scale=1.0"> 
	<link rel="stylesheet" href="includes/styles/dashboard2.css"> 
	<link rel="stylesheet" href="includes/styles/responsiive.css">
	<link rel="icon" type="images/x-icon" href="includes/images/white.JPG">
	<title>Santi Health - Calendar</title> 
	<script src="includes/main.js"></script>
</head> 
<body> 
	<header>
		<div class="logosec"> 
			<div class="logo"><img src="includes/images/white.JPG" width="15%"></div> 
		</div>
		<?php if ($returnFeedback != ''): ?>
            <span class='notification' id='notification' onclick="document.getElementById('notification').style.display = 'none'">
        <?php endif; ?>
		<h1>Calendar</h1>
		<div class="message">
	<div class="dropdown">
    <button class="dropbtn">
	<div class="name" id="profilename" style="display: none;"><?php echo htmlspecialchars($profile)?></div>
	<img src= "includes/images/avatar.jpeg" title="<?php echo htmlspecialchars($profile) ?>" class="dpicn" alt="dp" style="border: 4px solid green;border-radius:20px">
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href="#">Update Profile</a>
      <a href="changepassword.php">Change Password</a>
    </div>
  </div> 
		</div>
	</header> 
	<div class="main-container">
	<center>
	<form action="calendar2.php" method="POST" id="doctor_form">
	<h1>Select the best date and time <?php echo htmlspecialchars($profile)?> for your appointment with your doctor from the datepicker below</h1>
	<label for="datepicker" style="padding:20px">Datepicker</label><br>
	<input type="datetime-local" name="patient_date" required style="padding: 20px;border-radius:10px;margin:20px;" min="<?php echo date('Y-m-d H:i')?>"><br>
	<input type="submit" name="submit" style="padding:20px;width:30%">
	</form>
		</center>
	</div>
</body>
</html>
<?php } } ?>
