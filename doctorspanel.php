<?php 
date_default_timezone_set('Africa/Nairobi');
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookie', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.entropy_length', 32);
ini_set('session.hash_function', 'sha256');
session_start();
if (empty($_SESSION['doctors_session']) && empty($_COOKIE['fname']) && empty($_COOKIE['sname'])) {
	setcookie('fname', '', time() - 3600);
	setcookie('email', '',time() - 3600);
	setcookie('sname', '', time() - 3600);
	session_destroy();
	session_unset();
	header('location:doctorslogin.php');
}elseif(!empty($_SESSION['doctors_session']) && !empty($_COOKIE['fname']) && !empty($_COOKIE['sname'])){
	include 'includes/config.php';
	include 'santiapi.php';
	require_once 'notification.php';
	$notify = json_decode($notificationJSON, true);
	$taskErr = $task = $dateErr = $priorityErr = $priority = $booking = $session = '';
	$email = $_COOKIE['email'];
	$fname = ucfirst($_COOKIE['fname']);
	$sname = ucfirst($_COOKIE['sname']);
	$method = "AES-256-ECB";
	$key = 'ab1cde2fg3hi4jk5lmn8opqrstuvwxyz';
	$cipherfname = base64_decode($fname);
	$ciphersname = base64_decode($sname);
	$cipheremail = base64_decode($email);
	$iv_size = openssl_cipher_iv_length($method);
	$firstname = openssl_decrypt($cipherfname, $method, $key, OPENSSL_RAW_DATA);
	$secondname = openssl_decrypt($ciphersname, $method, $key, OPENSSL_RAW_DATA);
	$cookiemail = openssl_decrypt($cipheremail, $method, $key, OPENSSL_RAW_DATA);
	$email1 = $cookiemail;
	$profile = $firstname.$secondname;
	//Delete appointments that are already past today's date
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['submit'])){
		if (empty($_POST['task'])) {
			$taskErr = "Task title cannot be empty";
		} else {
			$task = $_POST['task'];
		}
	}
	if(isset($_POST['submit'])){
		if (empty($_POST['deadline'])){
			$dateErr = 'Date cannot be empty';
		}else {
			$date = $_POST['deadline'];
			$date1 = str_replace('T', ' ', $date);
			$date2 = $date1;
		}
	}
	if(isset($_POST['submit'])){
		if (empty($_POST['priority'])){
			$priorityErr = 'Priority cannot be empty';
		}else {
			$priority = $_POST['priority'];
		}
	}
		if(empty($taskErr) && empty($dateErr) && empty($priorityErr)){
				$sql = "SELECT patient_name FROM schedules WHERE doc_email=:email";
				$stmt1 = $conn->prepare($sql);
				$stmt1 -> bindParam(":email", $email1);
				$stmt1 -> execute();
				$result = $stmt1 -> fetchAll(PDO::FETCH_COLUMN);
				if(in_array($task, $result) == TRUE){
					$booking =  '<div class="alert warning"><span class="closebtn">Task of '.$task. ' '.'already exists, please use another name.</span></div>';
				} elseif(empty($result['patient_name']))
						try{
						$query = "INSERT INTO schedules (doc_email, doc_name, patient_name, date_of_schedule, confirmation)
						VALUES (:doc_email, :doc_name, :patient_name, :date_of_schedule, :confirmation)";
						$stmt = $conn->prepare($query);
						$stmt ->bindParam(':doc_email', $email1);
						$stmt ->bindParam(':doc_name', $profile);
						$stmt ->bindParam(':patient_name', $task);
						$stmt ->bindParam(':date_of_schedule', $date2);
						$stmt ->bindParam(':confirmation', $priority);
						if ($stmt->execute() == true){
							$booking = '<div class="alert success"><span class="closebtn">Task of '.$task.' on '. $date2.' has been succesfully booked</span></div>';
						} else {
							$booking =  '<div class="alert warning"><span class="closebtn">Task of '.$task.' on'. $date2. ' has not been booked, please try again</span></div>';
					}
			}catch(PDOException $e){
				$booking =  '<div class="alert warning"><span class="closebtn">Task not booked, kindly rebook</span></div>';
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
	<title>Santi Health - Doctor's panel</title> 
	<link rel="stylesheet" href="includes/styles/dashboard2.css"> 
	<link rel="stylesheet" href="includes/styles/responsiive.css">
	<link rel="icon" type="images/x-icon" href="includes/images/white.JPG">
	<script crossorigin src="https://unpkg.com/@digitalsamba/embedded-sdk"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="includes/main.js"></script>
</head> 
<body> 
	<header>
	<div class="icn menuicn" id="menuicn" alt='menu-icon'>
	<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
</svg> 
</div>
		<div class="logosec"> 
			<div class="name" id="profilename" style="display: none;"></div>
			<div class="logo"><img src="includes/images/white.JPG" width="15%"></div> 
		</div>
		<div class="searchbar">
			<form>
			<input type="text" placeholder="Search..." id="textinput">
			<button onclick="searchwords()">Search</button>
			</form>
			<p>
				<span id="txtHint"></span>	
			</p>
		</div>
		<script>
		function searchwords(){
			const keyword = document.getElementById('textinput').value;
			const searchpart = document.getElementById('main_content');
			const bodytext = searchpart.body.innerHTML;
			document.body.innerHTML = bodytext.replace(/<span class="highlight">(.*?)<\/span>/g, '$1');
			if (keyword) {
				const regex = new RegExp(`(${keyword})`, 'gi');
				document.body.innerHTML = bodytext.replace(regex, '<span class="highlight">$1</span>');
			}
		}
		function checkNotification() {
				const datapoint = document.getElementById('notification');
				$.ajax({
					url: 'notification.php',
					type: 'GET',
					dataType: 'json',
				});
			}
			setInterval(checkNotification, 5000);
		</script>
		<span class="notification" id="notification" onclick="document.getElementById('notification').style.display = 'none'">
		</span>
		<center>
		<?php if ($booking != ''): ?>
			<?php echo $booking ?? NULL ?>
		<?php endif ?>
		<?php if (strcmp($lseo0, $email1) === 0): ?>
			<div class="alert success"><span class="closebtn">You have pending scheduled appointment(s)</span></div>
		<?php endif ?>
		</center>
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
	  <a href="doclogout.php">Logout</a>
	  </center>
    </div>
  </div> 
		</div> 
	</header> 
	<div class="main-container"> 
		<div class="navcontainer"> 
			<nav class="nav">
			<script>
			var menuicn = document.querySelector(".menuicn");
			var nav = document.querySelector(".navcontainer");
			menuicn.addEventListener("click", () => {
				nav.classList.toggle("navclose");
			});
			</script>
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
						<h3>Doctors</h3>
					</button> 
					<button class="nav-option option3" onclick="reports()"> 
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-paperclip nav-img" viewBox="0 0 16 16">
  <path d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z"/>
</svg>
						<h3>Reports</h3> 
					</button> 
					<button class="nav-option option4" onclick="sessions()"> 
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-webcam" viewBox="0 0 16 16">
  <path d="M0 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H9.269c.144.162.33.324.531.475a7 7 0 0 0 .907.57l.014.006.003.002A.5.5 0 0 1 10.5 13h-5a.5.5 0 0 1-.224-.947l.003-.002.014-.007a5 5 0 0 0 .268-.148 7 7 0 0 0 .639-.421c.2-.15.387-.313.531-.475H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1z"/>
  <path d="M8 6.5a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m7 0a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
</svg>
						<h3>Sessions</h3> 
					</button> 

					<button class="nav-option option6" onclick="schedules()"> 
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-calendar2-range" viewBox="0 0 16 16">
  <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/>
  <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5zM9 8a1 1 0 0 1 1-1h5v2h-5a1 1 0 0 1-1-1m-8 2h4a1 1 0 1 1 0 2H1z"/>
</svg>
						<h3> Schedules</h3> 
					</button> 
				<a href="doclogout.php"><div class="nav-option logout"> 
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
						<h2 class="topic-heading">60.5k</h2> 
						<h2 class="topic">Consultations done</h2> 
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
						<h2 class="topic-heading">70</h2> 
						<h2 class="topic">Patients seen today</h2> 
					</div> 
					<svg style="color: white;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-check-all" viewBox="0 0 16 16">
  <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z"/>
</svg>
		</div> 
			</div>
			<div class="report-container"> 
				<div class="report-header"> 
					<h1 class="recent-Articles">Recent Bookings</h1> 
					<button class="view">View All</button> 
				</div> 
				<div class="report-body"> 
					<div class="report-topic-heading"> 
						<h3 class="t-op">Patients' names</h3> 
						<h3 class="t-op">Date of visit</h3> 
						<h3 class="t-op">Priority of visit</h3>
					</div> 
					<?php 
							$sql = $conn->prepare('SELECT patient_name, date_of_schedule, confirmation FROM schedules WHERE doc_email=:email');
							$sql->bindParam(':email', $email1);
							$sql->execute();
							$sql->setFetchMode(PDO::FETCH_ASSOC);
							foreach($sql as $schedules){
								if (in_array(date('Y-m-d'), $schedules) == 0){
									$booking = '<div class="alert warning"><span class="closebtn">You have some unfinished tasks for today.</span></div>';
								}
							?>
						<div class="item1">
							<h3 class="t-op-nextlvl"><?php echo htmlspecialchars($schedules['patient_name']) ?? NULL ?></h3>
							<h3 class="t-op-nextlvl"><?php echo htmlspecialchars($schedules['date_of_schedule']) ?? NULL ?></h3>
							<h3 class="t-op-nextlvl label-tag"><?php echo htmlspecialchars($schedules['confirmation']) ?? NULL ?></h3> 
						</div>
						<?php } if(empty($schedules)){echo htmlspecialchars('You have no bookings as of now');}?>
					</div>
				</div> 
			</div>
			<div class="doctors-profile" id="doctors-profile">
			<div class="filter" id="filter">
			Filter:
			<button onclick="filterSelection('all')" class="filterbtn active">Show all</button>
			<button onclick="filterSelection('doctors')" class="filterbtn">Doctors and Specialists</button>
			<button onclick="filterSelection('nurses')" class="filterbtn">Clinical Officers, Nurses and nurse aides</button>
			</div>	
		<script>
			filterSelection('all')
			function filterSelection(c) {
				var x, i;
				x = document.getElementsByClassName('ditching');
				if (c == 'all') c = "";
				for (i=0;i < x.length;i++){
					w3RemoveClass(x[i], "show");
					if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
				}
			}
			function w3AddClass(element, name){
				var i, arr1, arr2;
				arr1 = element.className.split(" ");
				arr2 = name.split(" ");
				for (i = 0; i < arr2.length; i++){
					if (arr1.indexOf(arr2[i]) == -1){
						element.className += " " + arr2[i];
					}
				}
			}
			function w3RemoveClass(element, name){
				var i, arr1, arr2;
				arr1 = element.className.split(" ");
				arr2 = name.split(" ");
				for (i = 0; i < arr2.length;i++){
					while (arr1.indexOf(arr2[i]) > -1) {
						arr1.splice(arr1.indexOf(arr2[i]), 1);
					}
				}
				element.className = arr1.join(" ");
			}
			var btnContainer = document.getElementById("filter");
			var btns = btnContainer.getElementsByClassName("filterbtn");
			for (var i =0; i < btns.length; i ++){
				btns[i].addEventListener("click", function(){
					var current = document.getElementsByClassName("active");
					current[0].className = current[0].className.replace("active", "");
					this.className += " active";
				});
			}
		</script>
				<?php 
				$getAll = $conn->prepare("SELECT * FROM santi_data WHERE faculty='Obs/Gyn' OR faculty='Diabetologist' OR faculty='Senior consultant' OR faculty='General Physician' OR faculty='Peaditrician' OR faculty='Neurologist' OR faculty='Cardiologist'");
				$getAll->execute();
				$getAll->setFetchMode(PDO::FETCH_ASSOC);
				foreach($getAll as $santidata){
				?>
		<span class="ditch ditching doctors">
			<img src="includes/images/doctorslaptop2.jpg" alt="" width="100%" style="object-fit:cover;">
			<h3>Name: <b>Dr.<input hidden name="name" value="<?php echo $santidata['fname'].' '. $santidata['sname'] ?? NULL;?>"> 
			<?php echo $santidata['fname'].' '. $santidata['sname'] ?? NULL;?></b></h4>
			<h3>Specialist:<input hidden name="speciality" value="<?php echo $santidata['faculty']?>">  
			<?php echo $santidata['faculty']?></h4>
			<h3>Gender: <?php echo ucfirst($santidata['gender']) ?? NULL ?></h4>
			<h3>Consultation Fee:<input hidden name="price" value="<?php echo $santidata['price']?>">
			<?php echo $santidata['price']?></h4>
		</span>
		<?php } 
		$getAll = $conn->prepare("SELECT * FROM santi_data WHERE faculty='Nurse' OR faculty='Nurse Aide' OR faculty='Clinician'");
		$getAll->execute();
		$getAll->setFetchMode(PDO::FETCH_ASSOC);
		foreach($getAll as $santidata){
		?>
		<span class="ditch ditching nurses">
		<img src="includes/images/nurses.jpg" alt="" width="100%">
			<h3>Name: <b><input hidden name="name" value="<?php echo $santidata['fname'].' '. $santidata['sname'] ?? NULL;?>"> 
			<?php echo $santidata['fname'].' '. $santidata['sname'] ?? NULL;?></b></h4>
			<h3>Specialist:<input hidden name="speciality" value="<?php echo $santidata['faculty']?>">  
			<?php echo $santidata['faculty']?></h4>
			<h3>Gender: <?php echo ucfirst($santidata['gender']) ?? NULL ?></h4>
			<h3>Consultation Fee:<input hidden name="price" value="<?php echo $santidata['price']?>">
			<?php echo $santidata['price']?></h4>
		</span>
		<?php } ?>
			</div>
			<div class="reports" id="reports">
			<div class="filter">
			<button onclick="showBills()" class="filterbtn">Bills</button>
			<button onclick="showReports()" class="filterbtn">Laboratory reports</button>
			</div>
			<script>
				function showReports(){
					const labreports = document.getElementById('lab-reviews');
					const activebtn = document.getElementsByClassName('filterbtn1');
					labreports.style.display = "block";
					document.getElementById('bills').style.display = "none";
				}
				function showBills() {
					const bills = document.getElementById('bills');
					bills.style.display = "block";
					const labreports = document.getElementById('lab-reviews');
					labreports.style.display = "none";
				}
			</script>
			<div class="bills" id='bills'>
				<h1>Bills</h1>
				<table>
						<tr>
							<th>Date of payment</th>
							<th>Payment For</th>
							<th>Time of payment</th>
							<th>Name of payer</th>
							<th>Attending Officer</th>
							<th>Mode of payment</th>
							<th>Status of payment</th>
							<th>Sum paid</th>
							<th>Pending amount</th>
						</tr>
						<tr>
							<td>12/06/2024</td>
							<td>Consultation</td>
							<td>12/06/2024 12:06:35</td>
							<td>Daniel Mwangi</td>
							<td>Simon Omondi</td>
							<td>M-pesa</td>
							<td>Paid</td>
							<td>12,000</td>
							<td>0</td>
						</tr>
				</table>
			</div>
			<div class="lab-reviews" style="display: none;" id="lab-reviews">
			<h1>Lab reviews</h1>
				<table>
						<tr>
							<th>Date of payment</th>
							<th>Payment For</th>
							<th>Time of payment</th>
							<th>Name of payer</th>
							<th>Attending Officer</th>
							<th>Mode of payment</th>
							<th>Status of payment</th>
							<th>Sum paid</th>
							<th>Pending amount</th>
						</tr>
						<tr>
							<td>12/06/2024</td>
							<td>Consultation</td>
							<td>12/06/2024 12:06:35</td>
							<td>Daniel Mwangi</td>
							<td>Simon Omondi</td>
							<td>M-pesa</td>
							<td>Paid</td>
							<td>12,000</td>
							<td>0</td>
						</tr>
				</table>
			</div>
			</div>
			<div class="sessions" id="sessions">
			<center>
			<?php
			$session = '<iframe src="'.$embedUrl.' "style="border:none;width:100%;height:460px;background-color: #cad7fda4;" class="digitalsamba" allow="camera; microphone; display-capture; autoplay;"  allowfullscreen="true">
    		</iframe>';
					if (!empty($notify)){
						$search_doc = array_search($email1, $notify['doctors_email'], strict:true);
						if (!empty($search_doc)){
							echo "<form method='post' action='santiapi.php'><input type='button' name='start_session' value='Start session'>".$session."</form>";
						}
					} else echo $session = "<center><p id='sessionErr'>No sessions for today</p></center>";
			?>
			</center>
			</div>
			<div class="schedules" id="schedules">
			<div class="task-form">
			<form action="doctorspanel.php" method="POST" id="doctor_form" enctype="multipart/form-data">
				<center>
					<h1>Task Scheduler</h1>
				</center>
				<label for="task name">Task name:</label>
            <input type="text" id="task" placeholder="Enter task name..." autocomplete="off" name="task" required>
			<label for="priority">Priority</label>
            <select id="priority" name="priority" >
                <option value="Top priority">Top Priority</option>
                <option value="Middle priority">Middle Priority</option>
                <option value="Low priority">Less Priority</option>
            </select>
			<label for="date">Date</label>
            <input type="datetime-local" id="deadline"  name="deadline" required min="<?php echo date('Y-m-d H:i')?>">
			<center>
            <input type="submit" value="Add Task" id="add-task" name="submit">
			</center>
			</form>
			</div>
			<div class="report-container"> 
				<div class="report-header"> 
					<h1 class="recent-Articles">Recent Schedules</h1> 
				</div>
				<br>
				<div class="report-body"> 
					<div class="report-topic-heading"> 
						<h3 class="t-op">Patients' names</h3> 
						<h3 class="t-op">Date of visit</h3> 
						<h3 class="t-op">Priority of visit</h3> 
					</div> 
					<?php 
							$sql = $conn->prepare('SELECT patient_name, date_of_schedule, confirmation FROM schedules WHERE doc_email=:email');
							$sql->bindParam(':email', $email);
							$sql->execute();
							$sql->setFetchMode(PDO::FETCH_ASSOC);
							foreach($sql as $schedules){
							?>
						<div class="item1">
							<h3 class="t-op-nextlvl"><?php echo $schedules['patient_name'] ?? NULL ?></h3>
							<h3 class="t-op-nextlvl"><?php echo $schedules['date_of_schedule'] ?? NULL ?></h3> 
							<h3 class="t-op-nextlvl label-tag"><?php echo $schedules['confirmation'] ?? NULL ?></h3> 
						</div> 
						<?php } if(empty($schedules)){echo 'You have no bookings as of now';}?>
					</div> 
				</div> 
        </div>
	</div>
</body>
<script>	
var close = document.getElementsByClassName('closebtn');
var i;
for (i=0;i<close.length;i++){
	close[i].onclick = function(){
		var div = this.parentElement;
		div.style.opacity = '0';
		setTimeout(function(){ div.style.display = "none"}, 600);
	}
}
</script> 
</html>
<?php } ?>