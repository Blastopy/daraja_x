<?php 
include 'includes/config.php';
$ofnameErr = $osnameErr = $pnumberErr = $emailErr = $residenceErr = $facultyErr = $priceErr = $genderErr = $passwordErr = $notesErr = '';
function test_inputs($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		if (isset($_POST['submit'])){
			if (empty($_POST['ofname'])){
				$ofnameErr = 'Please type the first name of the officer';
			} else {
				$ofname = test_inputs($_POST['ofname']);
				$ofname = filter_input(INPUT_POST, 'ofname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}
		}
		if (isset($_POST['submit'])){
			if (empty($_POST['osname'])){
				$osnameErr = 'Please type the second name of the officer';
			} else {
				$osname = test_inputs($_POST['osname']);
				$osname = filter_input(INPUT_POST, 'osname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}
		}
		if (isset($_POST['submit'])){
			if (empty($_POST['tel'])){
				$pnumberErr = 'Please type the phone number of the officer';
			} else {
				$pnumber = test_inputs($_POST['tel']);
				$pnumber = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}
		}
		if (isset($_POST['submit'])){
			if (empty($_POST['email'])){
				$emailErr = 'Please type the email address of the officer';
			} else {
				$email = test_inputs($_POST['email']);
				$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			}
		}
		if (isset($_POST['submit'])){
			if (!empty($_POST['licence'])){
				$licence = test_inputs($_POST['licence']);
				$licence = filter_input(INPUT_POST, 'licence', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}
		}
		if (isset($_POST['submit'])){
			if (empty($_POST['residence'])){
				$residenceErr = 'Please type the residence of the officer';
			} else {
				$residence = test_inputs($_POST['residence']);
				$residence = filter_input(INPUT_POST, 'residence', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}
		}
		if (isset($_POST['submit'])){
			if (empty($_POST['faculty'])){
				$facultyErr = 'Please select the faculty';
			} else {
				$faculty = test_inputs($_POST['faculty']);
				$faculty = filter_input(INPUT_POST, 'faculty', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}
		}
		if (isset($_POST['submit'])){
			if (empty($_POST['price'])){
				$priceErr = 'Please type the residence of the officer';
			} else {
				$price = test_inputs($_POST['price']);
				$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}
		}
		
		if (isset($_POST['submit'])){
			if (empty($_POST['gender'])){
				$genderErr = 'Please select the gender of the officer';
			} else {
				$gender = $_POST['gender'];
			}
		}
		if (isset($_POST['submit'])){
			if (empty($_POST['doctors_notes'])){
				$notesErr = "Doctor' notes cannot be empty";
			} else {
				$notes = test_inputs($_POST['doctors_notes']);
				$notes = filter_input(INPUT_POST, 'doctors_notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}
		}
		if (isset($_POST['submit'])){
			if (empty($_POST['password'])){
				$passwordErr = 'Please type your password here';
			} else {
				$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			}
		}

		if (empty($ofnameErr) && empty($osnameErr) && empty($pnumberErr) && empty($emailErr) && empty($residenceErr) && empty($facultyErr) && empty($priceErr) && empty($genderErr) && empty($passwordErr) && empty($notesErr)) {
				$stmt = $conn->prepare("SELECT email FROM santi_data WHERE email=:email");
				$stmt -> bindParam(':email', $email);
				$stmt -> execute();
				$result = $stmt -> fetch(PDO::FETCH_ASSOC);
				if (empty($result['email'])){
					try{
						$query = $conn->prepare("INSERT INTO santi_data (fname, sname, email, tel, licence, gender, residence ,faculty, doctor_notes, price, password)
						VALUES (:fname, :sname, :email, :tel, :licence, :gender, :faculty, :residence, :doctor_notes, :price, :password)");
						$query->bindParam(':fname', $ofname);
						$query->bindParam(':sname', $osname);
						$query->bindParam(':email', $email);
						$query->bindParam(':tel', $pnumber);
						$query->bindParam(':licence', $licence);
						$query->bindParam(':residence', $residence);
						$query->bindParam(':gender', $gender);
						$query->bindParam(':faculty', $faculty);
						$query->bindParam(':price', $price);
						$query->bindParam(':doctor_notes', $notes);
						$query->bindParam(':password', $password);
						if($query->execute() == TRUE){
							header('location:adminpanel.php');
						} else {
							echo '<script>alert("Officical not registered...")</script>';
						}
					} catch(PDOException $e){
						$formErr = "Internal server error.";
					}
			} else {
				$emailErr = "Email already exists";
			}
			}
}
?>