<?php
date_default_timezone_set('Africa/Nairobi');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookie', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.entropy_length', 32);
ini_set('session.hash_function', 'sha256');

session_start();
include 'includes/config.php';
$mail = new PHPMailer(true);
$emailErr = $passwordErr = $formErr = '';
function test_inputs($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	if (isset($_POST['submit'])){
		if (empty($_POST['email'])){
			$emailErr = "Please enter your email address*";
		} elseif(!empty($_POST['email'])){
				$email = test_inputs($_POST['email']);
				$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			}
		}
	if (isset($_POST['submit'])){
		if (empty($_POST['password']) && strlen($_POST['password']) < 8){
			$passwordErr = "Please check your password*";
		}elseif(!empty($_POST['password']) && strlen($_POST['password']) >= 8){
			$password = $_POST['password'];
		}
	}
	if(empty($emailErr) && empty($passwordErr)){
		// search if the email exists in the db
		try{
			$query = $conn -> prepare("SELECT fname, sname, email, password  FROM members WHERE email=:email");
			$query->bindParam(':email', $email);
			$query->execute();
			$result = $query -> fetch(PDO::FETCH_ASSOC);
			if(empty($result['email'])){
				$emailErr = "Email doesn't exist, please <a style='color:grey' href='register.php'>register</a>";
			}elseif(!empty($result['email'])) {
				$hash = $result['password'];
				$fname = $result['fname'];
				$sname = $result['sname'];
				$email = $result['email'];
				if (password_verify($password, $hash) == true){
					try{
						$mail->isSMTP();                                           // Set mailer to use SMTP
						$mail->Host       = 'smtp.gmail.com';                      // Specify main and backup SMTP servers
						$mail->SMTPAuth   = true;                                  // Enable SMTP authentication
						$mail->Username   = 'info.santihealth@gmail.com';          // SMTP username
						$mail->Password   = 'nbhf szmz qjnl tqqk';                 		// SMTP password
						$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
						$mail->Port      = 587;                                   // TCP port to connect to
						// Recipients
						$mail->setFrom('info.santihealth@gmail.com', 'Santi Admin');
						$mail->addAddress($email);    // Add a recipient
						// $mail->addAddress('ellen@example.com');                 // Name is optional
						// $mail->addReplyTo('info@example.com', 'Information');
						// $mail->addCC('cc@example.com');
						// $mail->addBCC('bcc@example.com');
						// Attachments (optional)
						// $mail->addAttachment('/var/tmp/file.tar.gz');           // Add attachments
						// $mail->addAttachment('includes/images/white.jpg', 'new.jpg');      // Optional name
						// Content
						$mail->isHTML(true);                                       // Set email format to HTML
						$mail->Subject = 'Account login';
						$mail->Body    = "<div class='clientmail'>Hello, You're receiving this email because we noticed some activity in your acoount. If this was you please ignore this email.</div>";
						$mail->AltBody = "You're receiving this mail because we noticed some activity in your email. If this was you please ignore this.";
						if ($mail->send() == true){
							$method = "AES-256-ECB";
							$key = 'ab1cde2fg3hi4jk5lmn8opqrstuvwxyz';
							$encryptingfname = openssl_encrypt($fname, $method, $key, OPENSSL_RAW_DATA);
							$cipherfname = base64_encode($encryptingfname);
							$encryptingsname = openssl_encrypt($sname, $method, $key, OPENSSL_RAW_DATA);
							$ciphersname = base64_encode($encryptingsname);
							$encryptingemail = openssl_encrypt($email, $method, $key, OPENSSL_RAW_DATA);
							$cipheremail = base64_encode($encryptingemail);
							setcookie('fname', $cipherfname, time() + 3600, secure:true, httponly:true);
							setcookie('sname', $ciphersname, time() + 3600, secure:true, httponly:true);
							setcookie('email', $cipheremail, time() + 3600, secure:true, httponly:true);
							$_SESSION["email"] = $email;
							$status = "logged-in";
							$query = $conn->prepare("UPDATE members SET status=:status WHERE email=:email");
							$query->bindParam(':status', $status);
							$query->bindParam(':email', $email);
							$query->execute();
							header('location:dashboard.php');	
							}
						}catch(Exception $e) {
							$emailErr = "Verification email not sent, try again.";
						}
				}else {
					$passwordErr = 'Invalid password.';
			}
		}
		}catch(PDOException $e){
			$formErr = "Internal server error";  
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="The best telemedicine platform that links you to your desired doctor ASAP. Your one stop healthcare provider.">
	<meta name="robots" content="index, follow">
	<link rel="icon" type="images/x-icon" href="includes/images/santi2.png">
	<link rel="stylesheet" type="text/css" href="includes/styles/index.css">
	<link rel="stylesheet" type="text/css" href="includes/styles/responsive_index.css">
	<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
	<title>Santi Health Homepage</title>
</head>
<body>
<div class="main" style="background-repeat: no-repeat;background-image: url('uploads/index8.jpg');background-size:cover;">
	<div class="navbar">
		<div class="icon">
			<img src="includes/images/santi23.png" alt="logo" width="80%" height="200%">
		</div>
		<div class="menu">
			<ul>
				<td><li><a href="index.php">HOME</a></li></td>
				<td><li><a href="about.php">ABOUT</a></li></td>
				<td><li>
					<div class="message">
						<div class="dropdown">
				<button class="dropbtn" style="	color: #007bff;">SERVICES</button>
				<div class="dropdown-content">
					<table>
						<tr>
							<td><a href="login.php">Senior Consultant</a></td>
							<td><a href="login.php">Nutritionist</a></td>
							<td><a href="login.php">Diabetologist</a></td>
							<td><a href="login.php">Pulmonologist</a></td>
						</tr>
						<tr>
							<td><a href="login.php">Obs/Gyn</a></td>
							<td><a href="login.php">Cardiologist</a></td>
							<td><a href="login.php">Oncologist</a></td>
							<td><a href="login.php">Peaditrician</a></td>
						</tr>
						<tr>
							<td><a href="login.php">General Physician</a></td>
							<td><a href="login.php">Neurologist</a></td>
							<td><a href="login.php">Psychiatrist</a></td>
							<td><a href="login.php">Orthopedic</a></td>
						</tr>
						<tr>
							<td><a href="login.php">Urologist</a></td>
							<td><a href="login.php">Nephrologist</a></td>
							<td><a href="login.php">Phlebotomist</a></td>
							<td><a href="login.php">Home-care services</a></td>
						</tr>
					</table>
    			</div>
				</div>
				</div>
				</li></td>
				<!-- <td><li><a href="#">DESIGN</a></li></td>
				<td><li><a href="#">CONTACT</a></li></td> -->
			</ul>
		</div>
	</div>
	<div class="content">
		<h1>Medical Teleconsultation &<br><span>Online Pharmacy</span></h1>
		<p class="par">
			We offer top-tier medical care to everyone, anywhere. Our board-certified medical practitioners ensure
			<br> your call doesn't return without a solution to your medical needs. Is it a routine checkup,<br> a consultation
			an emergency that needs an immediate mediacl attention, worry not,<br> Santi Health is here to ensure your health is 
			sorted wherever you are.
		</p>
		<button class="cn"><a href="register.php">JOIN US</a></button>
		<div class="formy">
			<form action="index.php" method="post" enctype="multipart/form-data">
			<h2>Login Here</h2>
			<input type="email" name="email" required class="form-group<?php echo $emailErr ?? NULL?>" placeholder="name@example.com">
			<br>
			<div class="errormessage" style="color: #007bff;">
					<?php echo $emailErr ?>
			</div>
			<input type="password" name="password" required class="form-group<?php echo $passwordErr ?? NULL ?>" placeholder="Password here">
			<br>
			<div class="errormessage" style="color: #007bff;">
					<?php echo $passwordErr ?>
				</div>
			<button type="submit" name="submit" class="btnn">Log in</button>
			</form>
			<p class="link">Don't have an account<br>
			<a href="register.php">Sign up</a> here</p>
			<p class="liw">Visit our socials</p>
			<div class="icon">
				<a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
				<a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
				<a href="https://x.com/HealthSanti?t=nXkYn64y10AC6nw1Vlk_Rg&s=08"><ion-icon name="logo-twitter"></ion-icon></a>
				<a href="#"><ion-icon name="logo-Tiktok"></ion-icon></a>
				<a href="#"><ion-icon name="logo-whatsapp"></ion-icon></a>
			</div>
		</div>
		</div>
		<h2 class="home-header" style="color:#007bff">Home care services</h2>
	<p class="par">
		Worried about <span style="color: #007bff;" class="pillow"> your old loved</span> ones, or a patient discharged from the hospital and needs medical care <span class="pillow" style="color: #007bff;"> while</span> <br>
		at the comfort of their home? Worry less Santi Health got you covered.<br>
		<b>We offer personalised:</b>
		<marquee behavior="" direction="left" style="background-color:#007bff;color:yellow;">
				Wound dressing services ||
				Home care services || Wellness support || Sample collection ||
				Medical Escort || Palliative Services || Private care in Hospitals || Physiotherapy || IV administration || Drug administration...
		</marquee>
</p>
</div>
</body>
</html>
