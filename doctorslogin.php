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
			$query = $conn -> prepare("SELECT fname, sname, email, password  FROM santi_data WHERE email=:email");
			$query->bindParam(':email', $email);
			$query->execute();
			$result = $query -> fetch(PDO::FETCH_ASSOC);
			if(empty($result['email'])){
				$emailErr = "Email doesn't exist";
			}elseif(!empty($result['email'])) {
				$hash = $result['password'];
				$fname = $result['fname'];
				$sname = $result['sname'];
				$email = $result['email'];
				if (password_verify($password, $hash) == true){
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
					$_SESSION["doctors_session"] = $email;
					$status = "logged-in";
					$query = $conn->prepare("UPDATE santi_data SET status=:status WHERE email=:email");
					$query->bindParam(':status', $status);
					$query->bindParam(':email', $email);
					$query->execute();
					header('location:doctorspanel.php');
				} else {
					$passwordErr = 'Invalid password';
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
	<meta name="http-equiv" content="30">
	<link rel="icon" type="images/x-icon" href="includes/images/white.JPG">
	<script src="includes/main.js"></script>
	<link rel="stylesheet" href="w3.css" />	<link type="text/css" rel="stylesheet" href="includes/styles/main.css">
	<title>Santi Health - Doctor's Login</title>
</head>
<body class="form-body">
	<main class="forms">
		<h1>Santi Health Doctors' Login panel</h1>
	<div class="form">
		<form action="doctorslogin.php" method="POST" enctype="multipart/form-data">
			<fieldset>
				<h1>Hello Doc, Please enter your details to login</h1>
				<div class="form-input">
				<label for="fname">Email Address:</label><br>
				<input type="email" placeholder="Email Address" required name="email" class="form-group<?php echo $emailErr ?? NULL?>" autocomplete="on">
				<div class="errormessage">
					<?php echo $emailErr ?>
				</div>
			</div>
				<div class="form-input">
				<label for="password">Password:</label><br>
				<input type="password" id="psw" name="password" placeholder="Password" required  class="form-group<?php echo $passwordErr ?? NULL ?>" pattern="(?=.*\d)(?=.*[a-z])(?=,*[A-Z].{8,}">
				<div class="errormessage">
					<?php echo $passwordErr ?>
				</div>
				<br>
				<input type="checkbox" onclick="showpsd()">Show password
				<script>
				function showpsd() {
					var x = document.getElementById("psw");
					if (x.type === "password") {
						x.type = "text";
					} else {
						x.type = "password";
					}
				}
				</script>
			</div>
				<div id="message">
					<h3>Password must contain the following:</h3>
					<p id="letter" class="invalid">A <b>lowecase</b> letter</p>
					<p id="capital" class="invalid">An <b>Uppercase</b> letter</p>
					<p id="number" class="invalid">A <b>number</b></p>
					<p id="length" class="invalid">Minimum <b>characters</b></p>
				</div>
				<center>
				<input type="submit" name="submit" value="Submit">
				</center>
			</div>
			</fieldset>
			</form>
	</main>
	<script>
				//validate password
var myInput = document.getElementById("psw");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

myInput.onfocus = function() { 
	document.getElementById("message").style.display = "block";
}

myInput.onblur = function() {
	document.getElementById("message").style.display = "none";
}

myInput.onkeyup = function() {
	// validate lowecase letters
	var lowerCaseLetters = /[a-z]/g;
	if (myInput.value.match(lowerCaseLetters)) {
		letter.classList.remove("invalid");
		letter.classList.add("valid");
	} else {
		letter.classList.remove("valid");
		letter.classList.add("invalid");
	}
	// Validate numbers
	var upperCaseLetters = /[A-Z]/g;
	if (myInput.value.match(upperCaseLetters)) {
		capital.classList.remove("invalid");
		capital.classList.add("valid");
	} else {
		capital.classList.remove("valid");
		capital.classList.add("invalid");
	}
	 
	//validate numbers
	var numbers = /[0-9]/g;
	if (myInput.value.match(numbers)) {
		number.classList.remove("invalid");
		number.classList.add("valid");
	} else {
		number.classList.remove("valid");
		number.classList.add("invalid");
	}

	//validate length
	if (myInput.value.length >= 8) {
		length.classList.remove("invalid");
		length.classList.add("valid");
	} else {
		length.classList.remove("valid");
		length.classList.add("invalid");
	}
}
			</script>
	<footer>
		<?php include 'includes/footer.php' ?>
	</footer>
</body>
</html>