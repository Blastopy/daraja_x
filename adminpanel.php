<?php 
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookie', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.entropy_length', 32);
ini_set('session.hash_function', 'sha256');
session_start();
if (empty($_SESSION['admin'])){
	header('location:adminlogin.php');
}elseif(empty($_SESSION['email']) && empty($_COOKIE['fname']) && empty($_COOKIE['sname'])) {
	setcookie('fname', '', time() - 3600);
	setcookie('email', '',time() - 3600);
	setcookie('sname', '', time() - 3600);
	setcookie('admin', '', time() - 3600);
	session_destroy();
	header('location:logout.php');
}elseif(!empty($_SESSION['email']) && !empty($_COOKIE['fname']) && !empty($_COOKIE['sname']) && !empty($_SESSION['admin'])) {
include 'includes/config.php';
include 'newmember.php';
include 'initiatepayment.php';
include 'santiapi.php';
$totalRooms = $booking = '';
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
$reportErr = $reportAmountErr = $reportnameErr = $reportDateErr = $laboratoryErr = $reportFileErr = $reportnameErr = $statusErr = $reportemailErr = $reportcommentErr = $priorityErr = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if (isset($_POST['upload-report'])){
		if (empty($_POST['report-name'])){
			$reportnameErr = 'Patient name cannot be empty';
		}elseif(!empty($_POST['report-name'])){
			$reportName = test_inputs($_POST['report-name']);
			$reportName = filter_input(INPUT_POST, 'report-name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
	if (isset($_POST['upload-report'])){
		if(empty($_POST['report-priority'])){
			$priorityErr = 'The priority must be set.';
		}elseif(!empty($_POST['report-priority'])){
			$reportPriority = test_inputs($_POST['report-priority']);
			$reportPriority = filter_input(INPUT_POST, 'report-priority', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
	if (isset($_POST['upload-report'])){
		if(empty($_POST['report-status'])){
			$statusErr = 'The status must be set.';
		}elseif(!empty($_POST['report-status'])){
			$reportStatus = test_inputs($_POST['report-status']);
			$reportStatus = filter_input(INPUT_POST, 'report-status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
	if (isset($_POST['upload-report'])){
		if(empty($_POST['report-date'])){
			$reportDateErr = 'Please fill the report date';
		}elseif(!empty($_POST['report-date'])){
			$reportDate = test_inputs($_POST['report-date']);
			$reportDate = filter_input(INPUT_POST, 'report-date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$reportDate = str_replace('T', ' ', $reportDate);
		}
	}
	if (isset($_POST['upload-report'])){
		if(empty($_POST['laboratory'])){
			$laboratoryErr = 'Lab cannot be empty';
		}elseif(!empty($_POST['laboratory'])){
			$lab = test_inputs($_POST['laboratory']);
			$lab = filter_input(INPUT_POST, 'laboratory', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
	if (isset($_POST['upload-report'])){
		if(empty($_POST['report-amount'])){
			$reportAmountErr = 'Lab cannot be empty';
		}elseif(!empty($_POST['report-amount'])){
			$reportAmount = test_inputs($_POST['report-amount']);
			$reportAmount = filter_input(INPUT_POST, 'report-amount', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
	if (isset($_POST['upload-report'])){
		if(empty($_FILES['report-file'])){
			$reportErr = 'Please upload a file';
		}elseif(!empty($_FILES['report-file'])){
			$reportFile = basename($_FILES['report-file']['name']);
			$target_dir = 'uploads/';
			$target_file = $target_dir.basename($_FILES['report-file']['name']);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
			if(isset($_POST['upload_report'])){
				$check = getimagesize($_FILES['report-file']['tmp_name']);
				if($check !== false){
					$uploadOk = 1;
					// Check if file already exists
					if (file_exists($target_file)){
						$uploadOk = 0;
						// Check file size
						if ($_FILES['report-file']['size'] > 500000) {
							$reportFileErr = 'File too large to be uploaded, compress or upload another file';
							$uploadOk = 0;
						}else {
							// Allow images and pdf files
							if($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'pdf' && $imageFileType != 'jpeg'){
								$reportFileErr = 'Only images and pdf files are allowed';
								$uploadOk = 0;
							}else {
								// Check if error is set to 0 by an error
								if ($uploadOk == 0){
									$reportFileErr = 'Your file was not uploaded';
								}else {
									//Upload the file if everything is okay
									if (move_uploaded_file($_FILES['report-file']['tmp_name'], $target_file)){
										$reportFile = basename($_FILES['report-file']['name']);
									}else{
										$reportFileErr = 'Sorry there was an error uploading your file';
									}
								}
							}
						}
					}else{
						$reportFileErr = 'Report already exists';
						$uploadOk = 1;
					}
				}else{
					$reportFileErr = 'Please upload an image or a document';
					$uploadOk = 0;
				}
			}
		}
	}
	if (isset($_POST['upload-report'])){
		if(empty($_POST['report-email'])){
			$reportemailErr = 'Please enter the email address of the patient';
		}elseif(!empty($_POST['report-email'])){
			$reportemail = test_inputs($_POST['report-email']);
			$reportemail = filter_input(INPUT_POST, 'report-email', FILTER_SANITIZE_EMAIL);
		}
	}
	if (isset($_POST['upload-report'])){
		if(empty($_POST['report-comment'])){
			$reportcommentErr = 'Please write a comment regarding the report';
		}elseif(!empty($_POST['report-comment'])){
			$reportComment = test_inputs($_POST['report-comment']);
			$reportComment = filter_input(INPUT_POST, 'report-comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
	if(empty($reportErr) && empty($reportAmountErr) && empty($reportnameErr) && empty($reportDateErr) && empty($laboratoryErr) && empty($reportFileErr) && empty($reportnameErr) && empty($statusErr) && empty($reportemailErr) && empty($reportcommentErr))
	{
		try{
			$request = "INSERT INTO reports (patient_name, priority ,date_of_report, patient_email, laboratory, report_comment, report_amount, report_status, report_image)
			VALUES (:patient_name,:priority,:date_of_report,:patient_email, :laboratory,:report_comment,:report_amount, :report_status, :report_image)";
			$query = $conn->prepare($request);
			$query->bindParam(':patient_name', $reportName);
			$query->bindParam(':priority', $reportPriority);
			$query->bindParam(':patient_email', $reportemail);
			$query->bindParam(':laboratory', $lab);
			$query->bindParam(':report_status', $reportStatus);
			$query->bindParam(':report_comment', $reportComment);
			$query->bindParam(':report_amount', $reportAmount);
			$query->bindParam(':report_image', $reportFile);
			$query->bindParam(':date_of_report', $reportDate);
			if ($query->execute() == TRUE)
			{
				$booking = '<div class="alert success"><span class="closebtn">Report uploaded succesfully</span></div>';
			}else {
				$booking =  '<div class="alert warning"><span class="closebtn">Report not uploaded, please try again</span></div>';
			}
		} catch(PDOException $e){
			$formErr = "Internal server error.";
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
	<title>Santi Health - Adminpanel</title> 
	<link rel="stylesheet" href="includes/styles/dashboard2.css"> 
	<link rel="stylesheet" href="includes/styles/responsiive.css">
	<link rel="icon" type="images/x-icon" href="includes/images/white.JPG">
	<script src="includes/main.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head> 
<body> 
	<header>
	<div class="icn menuicn" id="menuicn" alt='menu-icon'>
	<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
</svg> 
</div>
		<div class="logosec"> 
			<div class="logo"><img src="includes/images/white.JPG" width="15%"></div> 
		</div>
		<span class="notification" id="notification" onclick="document.getElementById('notification').style.display = 'none'">
		</span>
		<?php if ($booking != '' || !empty($booking)): ?>
			<?php echo $booking ?? NULL ?>
		<?php endif ?>
		</center>
		<div class="searchbar">
		<form method="GET" action="adminpanel.php">
			<input type="text" placeholder="Search..." onkeyup="searchFunction(this.value)" id="textinput">
			</form>
			<p>
				<span id="txtHint"></span>	
			</p>
		</div>
		<div class="message">
	<div class="dropdown">
    <button class="dropbtn">			
	<div class="name" id="profilename" style="display: none;"><?php echo htmlspecialchars($profile)?></div>
	<img src= "includes/images/avatar.jpeg" title="<?php echo htmlspecialchars($profile) ?>" class="dpicn" alt="dp" style="border: 4px solid green;border-radius:20px">
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href="doctorchangepassword.php">Change Password</a>
	  <hr>
	  <center>
	  <a href="logout.php">Logout</a>
	  </center>
    </div>
  </div> 
		</div> 
	</header> 
	<div class="main-container" id="content"> 
		<div class="navcontainer">
			<script>
				var menuicn = document.querySelector(".menuicn");
				var nav = document.querySelector(".navcontainer");
				menuicn.addEventListener("click", () => {
					nav.classList.toggle("navclose");
				});
			</script>
			<nav class="nav"> 
				<div class="nav-upper-options"> 
				<button class="nav-option option1" onclick="Dashboard()">
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-menu-app" viewBox="0 0 16 16">
  <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h2A1.5 1.5 0 0 1 5 1.5v2A1.5 1.5 0 0 1 3.5 5h-2A1.5 1.5 0 0 1 0 3.5zM1.5 1a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5zM0 8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm1 3v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2zm14-1V8a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2zM2 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5"/>
</svg> 
						<h3>Dashboard</h3> 
						</button>
					<button class="option2 nav-option" onclick="showDoctors()">
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
  <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
</svg>
						<h3>Registered Members</h3>
					</button> 
					<button class="nav-option option3" onclick="reports()"> 
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-paperclip nav-img" viewBox="0 0 16 16">
  <path d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z"/>
</svg>
						<h3>Registered staff</h3>
					</button> 
					<button class="nav-option option4" onclick="sessions()"> 
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-webcam" viewBox="0 0 16 16">
  <path d="M0 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H9.269c.144.162.33.324.531.475a7 7 0 0 0 .907.57l.014.006.003.002A.5.5 0 0 1 10.5 13h-5a.5.5 0 0 1-.224-.947l.003-.002.014-.007a5 5 0 0 0 .268-.148 7 7 0 0 0 .639-.421c.2-.15.387-.313.531-.475H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1z"/>
  <path d="M8 6.5a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m7 0a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
</svg>
						<h3>Ongoing Sessions</h3> 
					</button> 

					<button class="nav-option option6" onclick="schedules()"> 
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
  <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
</svg>
						<h3> Register Member</h3> 
					</button>
					<button class="nav-option option6" onclick="labreports()"> 
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-calendar2-range" viewBox="0 0 16 16">
  <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/>
  <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5zM9 8a1 1 0 0 1 1-1h5v2h-5a1 1 0 0 1-1-1m-8 2h4a1 1 0 1 1 0 2H1z"/>
</svg>
						<h3>Upload Lab reports</h3> 
					</button>
					<button class="nav-option option6" onclick="payment()">
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-credit-card-fill" viewBox="0 0 16 16">
  <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1"/>
</svg>
						<h3>Initiate payment</h3>
					</button>
				<a href="logout.php"><div class="nav-option logout"> 
						<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="nav-img bi bi-box-arrow-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
  <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
</svg>
						<h3>Logout</h3> 
					</div> 
				</a>
				</div> 
			</nav> 
		</div> 
			<div class="main" id="main_content">
			<div class="box-container"> 
				<div class="box box1"> 
					<div class="text">
						<h2 class="topic-heading"><?php echo htmlspecialchars($totalRooms ?? NULL);?></h2> 
						<h2 class="topic"></h2>
					</div> 
					<svg style="color: white;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
</svg>
				</div> 
				<div class="box box2"> 
					<div class="text"> 
						<h2 class="topic-heading">150</h2> 
						<h2 class="topic">Logged-in staff</h2> 
					</div> 
					<svg style="color: white;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
  <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
</svg>
				</div> 
				<div class="box box3"> 
					<div class="text"> 
						<h2 class="topic-heading">320</h2> 
						<h2 class="topic">Patient-doctor interactions</h2> 
					</div> 
					<svg style="color: white;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-chat-right-text-fill" viewBox="0 0 16 16">
  <path d="M16 2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h9.586a1 1 0 0 1 .707.293l2.853 2.853a.5.5 0 0 0 .854-.353zM3.5 3h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1 0-1m0 2.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1 0-1m0 2.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1"/>
</svg>
				</div>
				<div class="box box4">
					<div class="text"> 
						<h2 class="topic-heading">60.5k Online sessions</h2> 
						<h2 class="topic">300 Patients seen as of today</h2> 
					</div> 
					<svg style="color: white;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-check-all" viewBox="0 0 16 16">
  <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z"/>
</svg>
				</div>
				<hr>
				<h2>Doctors data</h2>
				<?php 
					$getAll = $conn->prepare("SELECT * FROM santi_data");
					$getAll->execute();
					$getAll->setFetchMode(PDO::FETCH_ASSOC);
					foreach($getAll as $officialdata){
						?>
				<div class="box box4"id="doctors_box">
				<div class="text" >
						<h2 class="topic-heading"><?php echo  'Dr. '.htmlspecialchars($officialdata['fname'].' '.$officialdata['sname']) ?? NULL; ?></h2> 
						<h2 class="topic"><?php echo htmlspecialchars($officialdata['faculty']) ?? NULL; ?></h2> 
					</div> 
				</div>
				<?php } ?>
			</div>
			<div class="report-container"> 
				<div class="report-header"> 
					<h1 class="recent-Articles">Today's bookings</h1> 
					<button class="view">View All</button> 
				</div> 
				<div class="report-body"> 
					<div class="report-topic-heading"> 
						<h3 class="t-op">Doctor's name</h3> 
						<h3 class="t-op">Date of visit</h3> 
						<h3 class="t-op">Priority</h3> 
					</div> 
					<?php 
					foreach($date_querys as $todays_jobs){
						$leo = $todays_jobs['date_of_schedule'];
						$lseo1 = $todays_jobs['doc_name'];
						$lseo2 = $todays_jobs['confirmation'];
					?>
							<div class="item1">
							<h3 class="t-op-nextlvl"><?php echo htmlspecialchars($lseo1)?></h3> 
							<h3 class="t-op-nextlvl"><?php echo htmlspecialchars($leo)?></h3> 
							<h3 class="t-op-nextlvl label-tag"><?php echo htmlspecialchars($lseo2)?></h3> 
						</div> 
						<?php } if (empty($leo)) echo '<center>No bookings for today</center>'; ?>
					</div> 
				</div> 
			</div>
			<div class="doctors-profile" id="doctors-profile">
			<table id="santiTable">
						<tr>
							<th>S. No</th>
							<th>Date of registration</th>
							<th>Reg. no</th>
							<th>Name</th>
							<th>Phone number</th>
							<th>Email Address</th>
							<th>County of residence</th>
							<th>Status</th>
							<th>Delete</th>
						</tr>
						<?php 
							$cnt = 1;
							$getAll = $conn->prepare("SELECT * FROM members");
							$getAll->execute();
							$getAll->setFetchMode(PDO::FETCH_ASSOC);
							foreach($getAll as $santidata){
						?>
						<tr>
							<td><?php echo $cnt++ ?></td>
							<td><?php echo htmlspecialchars($santidata['registration_date']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($santidata['id']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($santidata['fname'].' '.$santidata['sname']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($santidata['tel']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($santidata['email']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($santidata['residence']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($santidata['status']) ?? NULL ?></td>
							<td>
								<form method="POST" action="adminpanel.php">
								<input type="submit" name="delete_member" value="Delete" style="width: 100%;" onclick="return confirm('Do you really want to delete <?php echo htmlspecialchars($santidata['fname']).'\'s data?'?>')" >
							</form>
							</td>
							<?php
							$id = htmlspecialchars($santidata['id']) ?? NULL; } 
							if(isset($_POST['delete_member'])){
							$sql1=$conn->prepare("DELETE FROM members where id='$id'");
							if($sql1 -> execute() == TRUE){
								$booking =  '<div class="alert success"><span class="closebtn">Staff data deleted succesfully</span></div>';
							}else {
								$booking = '<div class="alert warning"><span class="closebtn">Data not deleted.</span></div>';
							}
							}?>
						</tr>
				</table>
			</div>
			<div class="reports" id="reports">
			<table>
						<tr>
							<th>S. No</th>
							<th>Date of registration</th>
							<th>Official's name</th>
							<th>Reg. no.</th>
							<th>Email</th>
							<th>Specialist</th>
							<th>Residence</th>
							<th>Licence number</th>
							<th>Gender</th>
							<th>Status</th>
							<th>Delete</th>
						</tr>
						<?php 
							$add = 1;
							$getAll = $conn->prepare("SELECT * FROM santi_data");
							$getAll->execute();
							$getAll->setFetchMode(PDO::FETCH_ASSOC);
							foreach($getAll as $officialdata){
						?>
						<tr>
							<td><?php echo $add++ ?></td>
							<td><?php echo htmlspecialchars($officialdata['registration_date']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($officialdata['fname'] .' '. $officialdata['sname']) ?? NULL ?></td>
							<td><?php htmlspecialchars($id = $officialdata['id']); echo htmlspecialchars($officialdata['id']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($officialdata['email']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($officialdata['faculty']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($officialdata['residence']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($officialdata['licence']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($officialdata['gender']) ?? NULL ?></td>
							<td><?php echo htmlspecialchars($officialdata['status']) ?? NULL ?></td>
							<td>
								<form method="post" action="adminpanel.php">
								<input type="submit" name="delete_staff" value="Delete" style="width: 100%;" onclick="return confirm('Do you really want to delete <?php echo $santidata['fname'].'\'s data?'?>')" >
							</form>
							</td>
							<?php
							} 
							if(isset($_POST['delete_staff'])){
							$sql1=$conn->prepare("DELETE FROM santi_data where id='$id'");
							if($sql1 -> execute() == TRUE){
								$booking = '<div class="alert success"><span class="closebtn">Staff data deleted succesfully</span></div>';
							}else {
								$booking = '<div class="alert warning"><span class="closebtn">Data not deleted.</span></div>';
							}
							}?>
						</tr>
				</table>
			</div>
			<div class="sessions" id="sessions">
				<div class="box box2" style="background-color: orange;"> 
					<div class="text"> 
						<h2 class="topic-heading"><?php echo htmlspecialchars($totalRooms) ?? NULL ?></h2> 
					</div> 
					<svg style="color: white;" id="live-pop" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-broadcast" viewBox="0 0 16 16">
  <path d="M3.05 3.05a7 7 0 0 0 0 9.9.5.5 0 0 1-.707.707 8 8 0 0 1 0-11.314.5.5 0 0 1 .707.707m2.122 2.122a4 4 0 0 0 0 5.656.5.5 0 1 1-.708.708 5 5 0 0 1 0-7.072.5.5 0 0 1 .708.708m5.656-.708a.5.5 0 0 1 .708 0 5 5 0 0 1 0 7.072.5.5 0 1 1-.708-.708 4 4 0 0 0 0-5.656.5.5 0 0 1 0-.708m2.122-2.12a.5.5 0 0 1 .707 0 8 8 0 0 1 0 11.313.5.5 0 0 1-.707-.707 7 7 0 0 0 0-9.9.5.5 0 0 1 0-.707zM10 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0"/>
</svg>
				</div>
				<div class="report-header"> 
					<h1 class="recent-Articles">Available rooms</h1> 
				</div> 
				<div class="report-body"> 
					<div class="report-topic-heading">
						<h3 class="t-op">Room Id</h3> 
					</div>
					<?php
					$getRooms = $rooms['data'] ?? NULL;
					foreach($getRooms as $all) {
						if($getRooms == NULL){
							echo 'No rooms available';
						}
					?>
					<div class="item1"> 
							<h3 class="t-op-nextlvl"><?php echo $all['id'] ?? NULL?></h3>
							<input type="submit" name="delete" value="Delete"><h3 class="t-op-nextlvl label-tag"></h3> 
						</div> 
					<?php } ?>
				</div>
			</div>
			<div class="schedules" id="schedules">
			<h1>Enter official's details in this form</h1>
			<form method="POST" action="adminpanel.php" enctype="multipart/form-data" id="doctor_form">
					<label for="Officials first">Official's first name:</label><br>
					<input type="text" name="ofname"  required placeholder="Official's first name" title="Doctor's first name goes here" autocomplete="on" class="form-group <?php echo $ofnameErr ?? NULL?>">
					<div class="errormessage">
						<?php echo $ofnameErr ?>
					</div>
					<br>
					<label for="Officials second name">Official's Second name:</label><br>
					<input type="text" name="osname"  required placeholder="Official's second name" title="Doctor's second name goes here" autocomplete="on" class="form-group<?php echo $osnameErr ?? NULL ?>">
					<div class="errormessage">
						<?php echo $osnameErr ?>
					</div>
					<br>
					<label for="Officials phone number">Doctor's phone number:</label><br>
					<input type="text" name="tel"  required placeholder="07xxxxxxxx" title="Doctor's phone number goes here" autocomplete="on" class="form-group<?php echo $pnumberErr ?? NULL?>">
						<div class="errormessage">
							<?php echo $pnumberErr ?>
						</div>
					<br>
					<label for="Officials email address">Doctor's Email Address:</label><br>
					<input type="text" name="email"  required placeholder="name@example.com" title="Doctor's Email Address goes here" autocomplete="on" class="form-group<?php echo $emailErr ?? NULL?>">
						<div class="errormessage">
							<?php echo $emailErr ?>
						</div>
					<br>
					<label for="official's_licence">Doctor's license number:</label><br>
					<input type="text" name="licence"  required placeholder="Official's license number" title="Official's licence number goes here">
					<br>
					<label for="Official's residence">County of residence:</label><br>
					<input type="text" name="residence"  required placeholder="Official's county of residence" title="Official's licence number goes here">
					<br>
					<label for="Official's">Official's Faculty</label></br>
					<select class="doctors-selection" id="Faculty" name="faculty" required style="float: left; width:50%">
							<option selected disabled>Specialty</option>
							<option value="Diabetologist">Diabetologist</option>
							<option value="Obs/Gyn">Obs/Gyn</option>
							<option value="Senior consultant">Senior consultant</option>
							<option value="Psychiatrist">Psychiatrist</option>
							<option value="Neurologist">Neurologist</option>
							<option value="Urologist">Urologist</option>
							<option value="General surgeon">General surgeon</option>
							<option value="Nutritionist/Dietician">Nutritionist/Dietician</option>
							<option value="Peaditrician">Peaditrician</option>
							<option value="Nephrologist">Nephrologist</option>
							<option value="Orthopedic">Orthopedic</option>
							<option value="Cardiologist">Cardiologist</option>
							<option value="Nurse">Nurse</option>
							<option value="Nurse-aid">Nurse-aid</option>
							<option value="Phlebotomist">Phlebotomist</option>
							<option value="Clinician">Clinician</option>
						</select><br>
						<div class="errormessage">
							<?php echo $facultyErr ?>
						</div>
					<br>
					<label for="Official's residence">Charges:</label><br>
					<input type="text" name="price"  required placeholder="Charges in Ksh." title="Official's charge of service goes here">
					<br>
					<label for="Gender">Doctor's gender:</label><br>
					<label for="male">Male:</label>
					<input type="radio" name="gender" value="male" required class="form-group<?php echo $genderErr ?? NULL ?>"><br>
					<label for="female">Female</label>
					<input type="radio" name="gender" value="female" required class="form-group<?php echo $genderErr ?? NULL ?>">
						<div class="errormessage">
							<?php echo $genderErr ?>
						</div>
					<br>
					<label for="Official's password">Officer's temporary password:</label><br>
					<input type="password" id="psw" name="password" required placeholder="Password" title="Official's licence number goes here" class="form-group<?php echo $passwordErr ??  NULL ?>">
					<div class="errormessage">
						<?php echo $passwordErr ?>
					</div>
					<br>
					<input type="checkbox" onclick="showpsd()">Show password
				<div id="message">
					<h3>Password must contain the following:</h3>
					<p id="letter" class="invalid">A <b>lowecase</b> letter</p>
					<p id="capital" class="invalid">An <b>Uppercase</b> letter</p>
					<p id="number" class="invalid">A <b>number</b></p>
					<p id="length" class="invalid">Minimum of <b>8 characters</b></p>
					<script>
						//Content to reload page
						$(document).ready(function(){
							$("#loadContent").click(function(){
								$("content").load("adminpanel.php");
							});
						});

						var close = document.getElementsByClassName('closebtn');
						var i;
						for (i=0;i<close.length;i++){
							close[i].onclick = function(){
								var div = this.parentElement;
								div.style.opacity = '0';
								setTimeout(function(){ div.style.display = "none"}, 600);
							}
						}

function searchFunction(str){
			if (str.length == 0){
				document.getElementById("txtHint").innerHTML == "";
				return;
			} else {
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("txtHint").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET", "search.php?q=" + str, true);
				xmlhttp.send();
			}
		}
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
					<center>
						<input type="submit" name="submit" value="submit" id="loadContent">
					</center>
				</form>
			</div>
			<div class="labreports" id="labreports">	
			<form action="adminpanel.php" method="POST" id="doctor_form" enctype="multipart/form-data">
				<center>
					<h1>Upload patient report</h1>
				</center>
				<label for="task name">Reason for test:</label>
            <input type="text" id="task" placeholder="Enter reason for test..." autocomplete="off" name="report-name" required>
			<label for="priority">Priority</label>
            <select id="priority" name="report-priority">
                <option value="Top priority">Top Priority</option>
                <option value="Middle priority">Middle Priority</option>
                <option value="Low priority">Less Priority</option>
            </select>
			<label for="date">Date of report:</label>
            <input type="datetime-local" id="deadline"  name="report-date" required class="form-group<?php echo $reportnameErr ?? NULL?>">
			<div class="errormessage">
				<?php echo $reportnameErr ?>
			</div>
			<br>
			<label for="date">Laboratory:</label>
            <input type="text" id="deadline" placeholder="Laboratory" name="laboratory" required class="form-group<?php echo $laboratoryErr ?? NULL?>">
			<div class="errormessage">
				<?php echo $laboratoryErr?>
			</div>
			<label for="date">Status:</label>
            <select id="priority" name="report-status" required class="form-group<?php echo $statusErr ?? NULL ?>">
                <option value="Approved">Approved</option>
                <option value="Not Approved">Not Approved</option>
            </select>
			<div class="errormessage">
				<?php echo $statusErr ?>
			</div>
			<label for="date">Amount:</label>
            <input type="text" id="deadline" placeholder="Approximate amount paid" name="report-amount" required class="form-group<?php echo $reportAmountErr ?? NULL ?>">
			<div class="errormessage">
				<?php echo $reportAmountErr ?>
			</div>
			<br>
			<label for="date">Upload file:</label>
            <input type="file" id="deadline"  name="report-file" required class="form-group<?php echo $reportErr ?? NULL?>">
			<div class="errormessage">
				<?php echo $reportErr ?>
			</div>
			<br>
			<label for="date">Patient-email:</label>
            <input type="email" width="200px" height="200px" id="deadline"  name="report-email" placeholder="name@example.com" required class="form-group<?php echo $reportemailErr ?? NULL ?>">
			<div class="errormessage">
				<?php echo $reportemailErr ?>
			</div>
			<br>
			<label for="date">Comments:</label>
            <input type="textarea" width="200px" height="200px" id="deadline" placeholder="Comments regarding the report..."  name="report-comment" required class="form-group<?php echo $reportcommentErr ?? NULL ?>">
			<div class="errormessage">
				<?php echo $reportcommentErr ?>
			</div>
			<center>
            <input type="submit" value="Upload report" id="add-task" name="upload-report">
			</center>
			</form>
			</div>
			<div class="payment" id="payment">
			<form action="adminpanel.php" method="POST" id="doctor_form" enctype="multipart/form-data">
				<center>
					<h1>Initiate patient payment</h1>
					<br>
					<div class="errormessage">
						<?php echo $reasonErr1 ?>
					</div>
				</center>
				<label for="task name">Reason for payment:</label>
            <input type="text" id="task" placeholder="Enter reason for payment..." autocomplete="on" name="payment_reason" required class="form-group<?php echo $reasonErr ?? NULL?>">
			<div class="errormessage">
				<?php echo $reasonErr ?>
			</div>
			<label for="priority">Priority</label>
            <select id="priority" name="meeting_priority" class="form-group<?php echo $meeting_priorityErr ?? NULL?>">
                <option value="Top priority">Top Priority</option>
                <option value="Middle priority">Middle Priority</option>
                <option value="Low priority">Less Priority</option>
            </select>
			<div class="errormessage">
				<?php echo $meeting_priorityErr ?>
			</div>
			<br>
			<label for="date">Date of payment:</label>
            <input type="datetime-local" id="deadline"  name="payment_date" required class="form-group<?php echo $payment_dateErr ?? NULL?>">
			<div class="errormessage">
				<?php echo $payment_dateErr ?>
			</div>
			<label for="date">Patient email:</label>
            <input type="text" id="deadline" placeholder="name@example.com" name="patient_email" required class="form-group<?php echo $patient_emailErr ?? NULL?>">
			<div class="errormessage">
				<?php echo $patient_emailErr ?>
			</div>
			<br>
			<label for="date">Payment Status:</label>
            <select id="priority" name="payment_status" required class="form-group<?php echo $payment_statusErr ?? NULL ?>">
                <option value="Approved">Approved</option>
                <option value="Not Approved">Not Approved</option>
            </select>
			<div class="errormessage">
				<?php echo $payment_statusErr ?>
			</div>
			<label for="date">Amount paid:</label>
            <input type="text" id="deadline" placeholder="Approximate amount paid" name="paid_amount" required class="form-group<?php echo $paid_amountErr ?? NULL ?>">
			<div class="errormessage">
				<?php echo $paid_amountErr ?>
			</div>
			<br>
			<label for="date">Doctors' email:</label>
            <input type="email" width="200px" height="200px" id="deadline"  name="doctors_email" placeholder="name@example.com" required class="form-group<?php echo $doctors_emailErr ?? NULL ?>">
			<div class="errormessage">
				<?php echo $doctors_emailErr ?>
			</div>
			<br>
			<label for="date">Confirmation code:</label>
            <input type="text" width="200px" height="200px" id="deadline" maxlength="10" name="confirmation_code" placeholder="Payment confirmation code" required class="form-group<?php echo $confirmation_codeErr ?? NULL ?>">
			<div class="errormessage">
				<?php echo $confirmation_codeErr ?>
			</div>
			<br>
			<label for="date">Patient's phone number:</label>
            <input type="text" width="200px" height="200px" id="deadline"  name="payment_phone" placeholder="07xxxxxxxx" required class="form-group<?php echo $payment_phoneErr ?? NULL ?>">
			<div class="errormessage">
				<?php echo $payment_phoneErr ?>
			</div>
			<br>
			<center>
            <input type="submit" value="Approve payment" id="add-task" name="approve_payment" id="loadContent">
			</center>
			</form>
			</div>
	</div>
</body>
</html>
<?php } ?>