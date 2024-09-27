<?php
include 'includes/config.php';
$fnameErr = $snameErr = $telErr = $emailErr = $residenceErr = $genderErr = $passwordErr = $confirmErr = $ageErr = "";
function validate($data) {
	$data = htmlspecialchars($data);
	$data = trim($data);
	$data = stripcslashes($data);
	return $data;
}
$counties = array('Mombasa', 'Kwale', 'Kilifi', 'Tana River', 'Lamu', 'Taita/Taveta,', 'Garissa', 
'Wajir', 'Mandera', 'Marsabit', 'Isiolo', 'Meru', 'Tharaka-Nithi', 'Embu', 'Kitui', 'Machakos',
 'Makueni', 'Nyandarua', 'Nyeri', 'Kirinyaga', "Murang'a", 'Kiambu', 'Turkana', 'West Pokot', 
 'Samburu','Trans Nzoia', 'Uasin Gishu', 'Elgeyo/Marakwet', 'Nandi', 'Baringo', 'Laikipia', 'Nakuru', 
 'Narok', 'Kajiado', 'Kericho', 'Bomet', 'Kakamega', 'Vihiga', 'Bungoma',
 'Busia', 'Siaya', 'Kisumu', 'Homa Bay', 'Migori', 'Kisii', 'Nyamira', 'Nairobi');


 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
 
 require 'vendor/autoload.php';
 
 $mail = new PHPMailer(true);

if ($_SERVER['REQUEST_METHOD'] == "POST"){
	if (isset($_POST['submit'])){
		if (empty($_POST['fname'])){
			$fnameErr = "Please type your first name";
		} else {
			$fname = ucwords($_POST['fname']);
			$fname = validate($_POST['fname']);
			$fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
	if (isset($_POST['submit'])){
		if (empty($_POST['sname'])){
			$snameErr = "Please type your second name";
		} else {
			$sname = ucwords($_POST['sname']);
			$sname = validate($_POST['sname']);
			$sname = filter_input(INPUT_POST, 'sname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
	if (isset($_POST['submit'])){
		if (empty($_POST['tel'])){
			$telErr = "Phone number goes here";
		} else {
			$tel = validate($_POST['tel']);
			$tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
	if (isset($_POST['submit'])) {
		if (empty($_POST['email'])){
		$emailErr = "Email can't be empty";
		} else {
			$email = validate($_POST['email']);
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		}
	}
	if (isset($_POST['submit'])){
		if (empty($_POST['residence'])) {
			$residenceErr = "Please enter your county of residence";
		} elseif(!empty($_POST['residence'])) {
			if (in_array($_POST['residence'], $counties) == false) {
				$residenceErr = "Please enter a valid county";
			} else {
				$residence = ucwords($_POST['residence']);
				$residence = validate($_POST['residence']);
				$residence = filter_input(INPUT_POST, 'residence', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
	}
	if (isset($_POST['submit'])){
		if (empty($_POST['age'])) {
			$ageErr = "Please enter your age";
		} else {
			if (strlen($_POST['age']) > 2) {
				$ageErr = "Please enter your correct age";
			}elseif($_POST['age'] < 0){
				$ageErr = "Please enter your correct age";
			} 
			else {
				$age = validate($_POST['age']);
				$age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
		}
	}
	}
	if (isset($_POST['submit'])){
		if (empty($_POST['gender'])){
			$genderErr = "Select either of the genders";
		} else {
			$gender = validate($_POST['gender']);
		}
	}

	if (isset($_POST['submit'])){
		if (empty($_POST['password'])){
			$passwordErr = "Password can't be empty";
		} else {
			$passwordx = $_POST['password'];
			$password1 = $_POST['password_confirm'];
			if (strcmp($passwordx, $password1) != 0){
				$confirmErr = "Password do not match";
			} else{
				if (strlen($_POST['password']) < 8){
					$passwordErr = "Your password is less than 8 characters";
				}
				$password = $_POST['password'];
				$password = password_hash($password, PASSWORD_DEFAULT);
			}
		}
	}
	
	if (empty($fnameErr) && empty($snameErr) && empty($telErr) && empty($emailErr) && empty($residenceErr) && empty($genderErr) && empty($passwordErr) && empty($confirmErr)){
		$stmt = $conn->prepare("SELECT email FROM members WHERE email=:email");
		$stmt -> bindParam(':email', $email);
		$stmt -> execute();
		$result = $stmt -> fetch(PDO::FETCH_ASSOC);
		if (empty($result['email'])){
			try{
				$status = "logged-in";
				$query = $conn->prepare("INSERT INTO members (fname, sname, email, tel, residence, age, gender, password, status)
				VALUES (:fname, :sname, :email, :tel, :residence, :age, :gender, :password, :status)");
				$query->bindParam(':fname', $fname);
				$query->bindParam(':sname', $sname);
				$query->bindParam(':email', $email);
				$query->bindParam(':tel', $tel);
				$query->bindParam(':residence', $residence);
				$query->bindParam(':age', $age);
				$query->bindParam(':gender', $gender);
				$query->bindParam(':password', $password);
				$query->bindParam(':status', $status);
				if($query->execute() == TRUE){
					$mail->isSMTP();                                           // Set mailer to use SMTP
						$mail->Host       = 'smtp.gmail.com';                      // Specify main and backup SMTP servers
						$mail->SMTPAuth   = true;                                  // Enable SMTP authentication
						$mail->Username   = 'info.santihealth@gmail.com';          // SMTP username
						$mail->Password   = 'nbhf szmz qjnl tqqk';                 		// SMTP password
						$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
						$mail->Port       = 587;                                   // TCP port to connect to
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
						$mail->Subject = 'Successfull Account registration.';
						$mail->Body    = "<div class='clientmail'>Hello ☺, Thank you for registering with Santi Health as your healthcare partner. We are here to take care of your healthcare concerns and ensure better health everyday.</div>";
						$mail->AltBody = "Hello ☺, Thank you for registering with Santi Health as your healthcare partner. We are here to take care of your healthcare concerns and ensure better health everyday";
						if ($mail->send() == true){
							header('location:login.php');
						}
				}
			} catch(PDOException $e){
				$formErr = "Internal server error.";
			}
	} else {
		$emailErr = "Email already exists, please <a style='color:grey;' href='login.php'>login</a>";
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
	<link rel="icon" type="images/x-icon" href="includes/images/santi2.png">
	<link rel="stylesheet" href="w3.css" />
	<script src="includes/main.js"></script>
	<link rel="stylesheet" type="text/css" href="includes/styles/main.css">
	<title>Register</title>
</head>
<body class="form-body">
	<main class="forms">
		<h1 class="form-note">Santi health registration panel</h1>
		<br>
		<div class="just-image">
		<img src="includes/images/santi2.png" alt="avatar" width="20%" style="padding: -100px;float:left;position: absolute;left:10%; top:30%">
		</div>
		<br>
		<div class="just-image">
		<img src="includes/images/santi2.png" alt="avatar" width="20%" style="padding: -100px;float:left;position: absolute;left:10%; top:30%">
		</div>
	<div class="form">
		<form method="POST" action="register.php" enctype="multipart/form-data">
			<fieldset>
			<h1>Registration form</h1>
				<div class="form-input">
				<label for="fname">First Name:</label><br>
				<input type="text" placeholder="First name" required name="fname" class="form-group<?php echo $fnameErr ?? NULL ?>" autocomplete="on">
				<div class="errormessage">
					<?php echo $fnameErr ?>
				</div>	
			</div>
				<div class="form-input">
				<label for="sname">Second Name:</label><br>
				<input type="text" placeholder="Second name" required name="sname" class="form-group<?php echo $snameErr ?? NULL ?>" autocomplete="on">
				<div class="errormessage">
					<?php echo $snameErr ?>
				</div>	
			</div>
				<div class="form-input">
				<label for="tel">Phone number:</label><br>
				<input type="tel" placeholder="07xxxxxxxx" maxlength="13" required maxlength="13" name="tel" class="form-group<?php echo $telErr ?? NULL ?>" autocomplete="on">
				<div class="errormessage">
					<?php echo $telErr ?>
				</div>
			</div>
				<div class="form-input">
				<label for="email">Email Address:</label><br>
				<input type="email" placeholder="name@example.com" required name="email" class="form-group<?php echo $emailErr ?? NULL ?>" autocomplete="on">
				<div class="errormessage">
					<?php echo $emailErr ?>
				</div>
			</div>
				<div class="form-input">
				<label for="residence">County of residence:</label><br>
				<input type="text" placeholder="County of residence" required name="residence" class="form-group<?php echo $residenceErr ?? NULL?>" autocomplete="on">
				<div class="errormessage">
					<?php echo $residenceErr ?>
				</div>
			</div>
			<div class="form-input">
				<label for="residence">Age:</label><br>
				<input type="text" placeholder="Age" required name="age" class="form-group<?php echo $ageErr ?? NULL?>" autocomplete="on" maxlength="02">
				<div class="errormessage">
					<?php echo $ageErr ?>
				</div>
			</div>
				<div class="form-input">
				<label for="gender">Gender</label><br>
				<label for="male">Male:</label>
				<input type="radio" name="gender" required value="male" class="form-group<?php echo $genderErr ?? NULL ?>">
				<div class="errormessage">
					<?php echo $genderErr ?>
				</div>
				<br>
				<label for="male">Female:</label>
				<input type="radio" required name="gender" value="female" class="form-group<?php echo $genderErr ?? NULL ?>">
				<div class="errormessage">
					<?php echo $genderErr ?>
				</div>
			</div>
			<div class="form-input">
				<label for="password">Password:</label><br>
				<input type="password" id="psw" name="password" placeholder="Password" required id="male"  class="" autocomplete="on" pattern="(?=.*\d)(?=.*[a-z])(?=,*[A-Z].{8,}">
				<input type="checkbox" onclick="showpsd()">Show password
				<div class="form-input">
				<div id="message">
					<h3>Password must contain the following:</h3>
					<p id="letter" class="invalid">A <b>lowercase</b> letter</p>
					<p id="capital" class="invalid">An <b>Uppercase</b> letter</p>
					<p id="number" class="invalid">A <b>number</b></p>
					<p id="length" class="invalid">Minimum <b>characters</b></p>
				</div>
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
				<div class="form-input">
				<label for="password">Confirm Password:</label><br>
				<input type="password" id="psw1" title="Must contain at least one number and one uppercase and lowercase letter and at least 8 characters" required name="password_confirm" placeholder="Confirm Password" required id="male"  class="" autocomplete="on">
				</div>
				<?php if ($passwordErr || $confirmErr != ''): ?>
				<div class="errormessage">
					<?php echo $passwordErr . $confirmErr ?>
				</div>
				<?php endif ?>
				<div class="form-input">
				<p>By registering you agree to our <a href="" style="color: blue;">terms and conditions</a> and our <a href="" style="color: blue;">privacy policy</a></p>
					<center>
				<input type="submit" name="submit" value="submit">
					</center>
				</div>
				<p>Already registered?<a href="login.php" style="color:grey"> Login</a></p>
			</fieldset>
		</form>
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
	</div>
	</main>
	</div>
	<div class="footer">
			<?php include 'includes/footer.php' ?>
	</div>
</body>
</html>