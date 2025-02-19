<?php 
ob_start();
date_default_timezone_set('Africa/Nairobi');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);
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
include 'includes/config.php';
$fname = $_COOKIE['fname'];
$sname = $_COOKIE['sname'];
$email = $_COOKIE['email'];
//Decrypt the encrypted data
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
$returnMail = $booking = $returnMailErr = '';
function test_inputs($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
if (isset($_GET['patient_email_select'])){
	// try{	
	// 	$sql = "SELECT patient_email FROM schedules";
	// 	$stmt1 = $conn->prepare($sql);
	// 	$stmt1 -> execute();
	// 	$result = $stmt1 -> fetchAll(PDO::FETCH_ASSOC);
	// 	if (empty($result['patient_email'])){
	// 		$returnMailErr = "<div class='alert warning'><span class='closebtn'>Email is not registered or the user has not paid for the consultation.</span></div>";
	// 	}else {
			$returnMail = $_GET['patient_email_select'];
// 		}
// 	}catch(PDOException $e){
// 		echo 'Err'.$e;
// }
}
if (isset($_POST['share_link'])){
    $useremail = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    $mail->isSMTP();                                           // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';                      // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                  // Enable SMTP authentication
    $mail->Username   = 'info.santihealth@gmail.com';          // SMTP username
    $mail->Password   = 'nbhf szmz qjnl tqqk';                 		// SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 587;                                   // TCP port to connect to
    // Recipients
    $mail->setFrom('info.santihealth@gmail.com', 'Santi Admin');
    $mail->addAddress($useremail);
    // Content
    $mail->isHTML(true);                                       // Set email format to HTML
    $mail->Subject = 'Meeting link from Dr. '.$profile;
    $mail->Body    = 'Click the link below to join the meeting <br>'.$_POST['pasted_link'];
    $mail->AltBody = 'Click this link below to join the meeting <br>'.$_POST['pasted_link'];
	if (empty($returnMailErr)){
		if($mail->send() == true){
			$returnMailErr = '<div class="alert success"><span class="closebtn">Link shared succefully.</span></div>';
		} else {
			$returnMailErr = '<div class="alert warning"><span class="closebtn">Link not shared please try again.</span></div>';
		}
	}else $returnMailErr = '<div class="alert warning"><span class="closebtn">Link not shared please try again...</span></div>';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="includes/styles/dashboard2.css">
	<link rel="stylesheet" href="includes/styles/responsiive.css">
	<link rel="icon" type="images/x-icon" href="includes/images/white.JPG">
	<script src="includes/main.js"></script>
	<title>Santi - Share link</title>
</head>
<body>
	<div id="changepassword">
	<form action="invite.php" method="POST" id="changepassword">
		<img src="includes/images/santi23.png" width="30%">
		<p><h4>Hi <?php echo htmlspecialchars($profile).', ' ?? NULL?>Paste the link below to share with the user.</h4></p>
		<div class="errormessage">
						<?php echo $returnMailErr ?? null ?>
					</div>
        <h5>If the email of the user does not show, then he/she has not paid for the consultation.</h5>
		<label for="email">Please enter the email addres below to search for the user:</label>
					<input type='email' autocomplete="off" value="<?php echo htmlspecialchars($returnMail)?>" required name='user_email' id='email_search' width='20%' onkeyup='searchFunction(this.value)' placeholder='name@example.com'>
					<span style="width:100%;" id='txtHint1'></span>
                <script>
					function searchFunction(str){
					if (str.length == 0){
						document.getElementById("txtHint1").innerHTML == "";
						return;
					} else {
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("txtHint1").innerHTML = this.responseText;
							}
						};
						xmlhttp.open("GET", "getmails.php?q=" + str, true);
						xmlhttp.send();
					}
				}
				</script>
		<label for="link">Paste link👇:</label>
		<input type="text" name="pasted_link" placeholder="Link" pattern="(?=.*\d)(?=.*[a-z])(?=,*[A-Z].{8,}" required title="Password has to have capital letters, small letters, numbers and special characters" class="form-group<?php echo $oldpasswordErr ?? NULL ?>">
		<input type="submit" value="Share link" name="share_link">
	</form>
	<p style="color:grey;line-height:1.7;margin-top:2rem;position:relative;">Copyright @ <?php echo date("Y")?>  | All Rights Reserved | Santi Health Ltd.</p>
	</div>
</body>
</html>
<?php } ?>