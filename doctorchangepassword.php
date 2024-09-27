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
$cookiemail = openssl_decrypt($cipheremail, $method, $key, OPENSSL_RAW_DATA);
$profile = $firstname;
	include 'includes/config.php';
	$emailErr = $newpasswordErr = $oldpasswordErr = '';
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		if (isset($_POST['submit'])){
			if (empty($_POST['email'])){
				$emailErr = 'Email address cannot be empty';
			}elseif(strcmp($_POST['email'], $cookiemail) != 0){
				$emailErr = 'Please enter your email address';
			}
			else {
				$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			}
		}
		if (isset($_POST['submit'])){
			if (empty($_POST['old_password'])){
				$oldpasswordErr = 'Password cannot be empty';
			} elseif(strlen($_POST['old_password']) < 8){
				$oldpasswordErr = 'Your password is less than 8 characters';
			} elseif(strlen($_POST['old_password']) > 8 && !empty($_POST['old_password'])){
				$oldpassword = $_POST['old_password'];
			}
		}
		if (isset($_POST['submit'])){
			if (empty($_POST['new_password'])){
				$newpasswordErr = 'Password cannot be empty';
			} elseif(strlen($_POST['new_password']) < 8){
				$newpasswordErr = 'Your password is less than 8 characters';
			} elseif(strlen($_POST['new_password']) > 8 && !empty($_POST['old_password'])){
				$newpassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
			}
		}
		if(empty($emailErr) && empty($oldpasswordErr) && empty($newpasswordErr)){
			try{
				$query = $conn -> prepare("SELECT email, password  FROM santi_data WHERE email=:email");
				$query->bindParam(':email', $email);
				$query->execute();
				$result = $query -> fetch(PDO::FETCH_ASSOC);
				if(empty($result['email'])){
					$emailErr = "Email doesn't exist, please enter the correct email.";
				}elseif(!empty($result['email'])) {
					$hash = $result['password'];
					$email = $result['email'];
					if (password_verify($oldpassword, $hash) == true){
						$query = $conn->prepare("UPDATE santi_data SET password=:password WHERE email=:email");
						$query->bindParam(':email', $email);
						$query->bindParam(':password', $newpassword);
						if($query->execute() == TRUE){
							header('location:doctorspanel.php');
						}
					} else {
						$oldpasswordErr = 'Invalid password';
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
	<link rel="stylesheet" href="includes/styles/dashboard2.css">
	<link rel="stylesheet" href="includes/styles/responsiive.css">
	<script crossorigin src="https://unpkg.com/@digitalsamba/embedded-sdk"></script>
	<link rel="icon" type="images/x-icon" href="includes/images/white.JPG">
	<script src="includes/main.js"></script>
	<title>Doctor Change Password</title>
</head>
<body>
	<div id="changepassword">
	<form action="doctorchangepassword.php" method="POST" id="changepassword">
		<img src="includes/images/santi2.png" width="30%">
		<p><h4>Hi <?php echo htmlspecialchars($profile).', ' ?? NULL?>Enter your details to change your password</h4></p>
		<label for="email">Email Address:</label>
		<input type="email" name="email" placeholder="name@example.com" required title="Type your registered email address here" class="form-group<?php echo $emailErr ?? NULL ?>">
		<div class="errormessage">
			<?php echo $emailErr ?? null ?>
		</div>
		<label for="password">Old Password:</label>
		<input type="password" name="old_password" placeholder="Old password" pattern="(?=.*\d)(?=.*[a-z])(?=,*[A-Z].{8,}" required title="Password has to have capital letters, small letters, numbers and special characters" class="form-group<?php echo $oldpasswordErr ?? NULL ?>">
		<div class="errormessage">
			<?php echo $oldpasswordErr ?? NULL ?>
		</div>
		<label for="password">New Password:</label>
		<input type="password" name="new_password" id="pswd" placeholder="New password" pattern="(?=.*\d)(?=.*[a-z])(?=,*[A-Z].{8,}" required title="Password has to have capital letters, small letters, numbers and special characters" class="form-group<?php echo $newpasswordErr ?? NULL ?>">
		<div class="errormessage">
			<?php echo $newpasswordErr ?? NULL ?>
		</div>
		<input type="checkbox" onclick="showpsd()">Show password
				<script>
				function showpsd() {
					var x = document.getElementById("pswd");
					if (x.type === "password") {
						x.type = "text";
					} else {
						x.type = "password";
					}
				}
				</script>
		<input type="submit" value="Submit" name="submit">
	</form>
	<p style="color:grey;line-height:1.7;margin-top:2rem;position:relative;">Copyright @ <?php echo date("Y")?>  | All Rights Reserved | Santi Health Ltd.</p>
	</div>
</body>
</html>
<?php } ?>