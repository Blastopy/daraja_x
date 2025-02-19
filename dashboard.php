<?php 
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookie', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.entropy_length', 32);
ini_set('session.hash_function', 'sha256');
session_start();
if (empty($_SESSION['email']) || empty($_COOKIE['fname']) || empty($_COOKIE['sname'])) {
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
	include 'santiapi.php';
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8"> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
	<meta name="viewport" content="width=device-width,  initial-scale=1.0"> 
	<meta name="description" content="The best telemedicine platform that links you to your desired doctor ASAP. Your one stop healthcare provider.">
	<meta name="robots" content="index, follow">
	<title>Santi Health - Dashboard</title>
	<link rel="stylesheet" href="includes/styles/dashboard2.css">
	<link rel="stylesheet" href="includes/styles/responsiive.css">
	<link rel="icon" type="images/x-icon" href="includes/images/santi2.png">
	<script src="includes/main.js"></script>
	<script src="includes/offline/offline.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/offline-js/0.7.19/themes/offline-theme-default.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/offline-js/0.7.19/themes/offline-language-english.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/offline-js/0.7.19/offline.min.js"></script>
</head> 
<body>
	<header>
	<div class="icn menuicn" id="menuicn" alt='menu-icon'>
	<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
</svg> 
</div>
		<div class="logosec">
			<div class="logo"><img src="includes/images/santi23.png" alt="santilogo" width="15%"></div> 
		<script>
		const offlineTag = document.getElementById('offline');
		Offline.options = {
			checkOnLoad: true,
            interceptRequests: true,
            requests: true,
			reconnect: {
				initialDelay : 3,
				delay: 10,
			},
            checks: {xhr: {url: '/heartbeat'}}
        };

		Offline.on('up', function() {
			offlineTag.style.display = "none";
			offlineTag.innerText = "You are now connected";
            Offline.retryAll();
        });

        Offline.on('down', function() {
			offlineTag.style.display = 'block';
			offlineTag.innerHTML = "You are now offline";
        });

			function checkNotification() {
				const datapoint = document.getElementById('notification');
				$.ajax({
					url: 'notification.php',
					type: 'GET',
					dataType: 'json',
				});
			}
			setInterval(checkNotification, 500);
		</script>
		<?php if (strcmp($lseo, $cookiemail) === 0): ?>
			<div class="alert success"><span class="closebtn">You have scheduled appointment for today</span></div>
		<?php endif ?>
</div>
<div class="searchbar">
	<form>
			<input type="text" placeholder="Search..." onkeyup="searchFunction()" id="textinput">
			<script>
				function searchFunction() {
					var input = document.getElementById('textinput').value
					input = input.toLowerCase();
					var x = document.getElementsByClassName('main_container');

					for (i = 0; i < x.length; i++) {
						if (!x[i].innerHTML.toLowerCase().includes(input)) {
							x[i].style.display = 'none';
						}else {
							x[i].style.display = "list-item";
						}
					}
				}
			</script>
	</form>
		</div>
		<span class="notification" id="notification" onclick="document.getElementById('notification').style.display = 'none'">
	<div id="offline"></div>
	</span>
		<div class="message">
	<div class="dropdown">
    <button class="dropbtn">			
	<div class="name" id="profilename" style="display: none;"><?php echo htmlspecialchars($profile)?></div>
	<img src= "includes/images/avatar.jpeg" title="<?php echo htmlspecialchars($profile) ?>" class="dpicn" alt="dp" style="border: 4px solid green;border-radius:20px">
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href="changepassword.php">Change Password</a>
	  <hr>
	  <center>
	  <a href="logout.php">Logout</a>
	  </center>
    </div>
  </div> 
		</div>
	</header>
	<div class="main-container" id="main_container">
		<p id="offline"></p>
		<div class="navcontainer">
			<script>
				var menuicn = document.querySelector(".menuicn");
				var nav = document.querySelector(".navcontainer");
				menuicn.addEventListener("click", () => {
					nav.classList.toggle("navclose");
				});

				function setCookie(name, value, days){
	const expires = new Date(Date.now() + days * 864e5).toUTCString();
	document.cookie = name + "=" + encodeURIComponent(value) + "; expires=" + expires + "; path=/";
}

function getCookie(name){
	return document.cookie.split('; ').find(row => row.startsWith(name + "="))?.split("=")[1];
}

document.addEventListener("DOMContentLoaded", function() {
	const buttons = getCookie("button");
	if(buttons == 'doctors'){
		showDoctors();
	}else if(buttons === 'dashboard'){
		Dashboard();
	}else if(buttons === 'reports'){
		reports();
	}else if (buttons === 'sessions') {
		sessions();
	}else if (buttons === 'schedules') {
		schedules();
	}else if (buttons === 'payment') {
		payment();
}
});
			</script>
			<nav class="nav">
				<div class="nav-upper-options"> 
				<button class="nav-option option1" onclick="Dashboard()">
				<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
  <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
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
						<h3>Live Sessions</h3>
					</button> 
					<button class="nav-option option6" onclick="schedules()">
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-capsule-pill" viewBox="0 0 16 16">
  <path d="M11.02 5.364a3 3 0 0 0-4.242-4.243L1.121 6.778a3 3 0 1 0 4.243 4.243l5.657-5.657Zm-6.413-.657 2.878-2.879a2 2 0 1 1 2.829 2.829L7.435 7.536zM12 8a4 4 0 1 1 0 8 4 4 0 0 1 0-8m-.5 1.042a3 3 0 0 0 0 5.917zm1 5.917a3 3 0 0 0 0-5.917z"/>
</svg>
			<h3>Pharmacy and Laboratory</h3>
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
				<p style="display:none" class="dash-intro">
					Here you book your appointment with your doctor, make payment, check on the status of your account and interact with our 24/7 support team 
				</p>
			<div class="box-container"> 
				<div class="box box1">
					<div class="text"> 
						<h2 class="topic-heading">60.5k</h2> 
						<h2 class="topic">Consultations done</h2> 
					</div> 
					<svg style="color: white;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-activity" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2"/>
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
					<svg style="color: white;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-heart-pulse-fill" viewBox="0 0 16 16">
  <path d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9z"/>
  <path d="M.88 8C-2.427 1.68 4.41-2 7.823 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C11.59-2 18.426 1.68 15.12 8h-2.783l-1.874-4.686a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8z"/>
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
					<h1 class="recent-Articles">How to navigate santi App</h1> 
					<button class="view"><?php echo htmlspecialchars(date('H:i'))?></button> 
				</div> 
				<div class="report-body2"> 
						<ul class="tree">
						<h2>Consultation</h2>
							<li>
								<details open>
									<summary> <span class="tree-option">Sign in into Santi Health</span></summary>
									<ul>
										<li>
									<details open>
										<summary><span class="tree-option-1">Find Doctors</span></summary>
										<ul>
											<li><span class="tree-option-2">Doctors</span></li>
											<li><span class="tree-option-2">Nurses, Nurse-Aides and Medical Officers</span></li>
										</ul>
									</details>
									</li>
									<li>
										<details open>
											<summary><span class="tree-option-3">Pay for Consultation</span></summary>
											<ul>
												<li><span class="tree-option-4">Schedule for a date</span></li>
												<li><span class="tree-option-5">Consult with your prefered Doctor</span></li>
											</ul>
										</details>
									</li>
									</ul>
								</details>
							</li>
						</ul>

						<ul class="tree">
						<h2>Pharmacy and Laboratory</h2>
							<li>
								<details open>
									<summary><span class="tree-option">Sign in into Santi Health</span></summary>
									<ul>
										<li>
									<details open>
										<summary><span class="tree-option-1">Click the pharmacy and Lab button</span></summary>
										<ul>
											<li><span class="tree-option-2">Search for the medicine</span></li>
											<li><span class="tree-option-2">Add to your cart</span></li>
										</ul>
									</details>
									</li>
									<li>
										<details open>
											<summary><span class="tree-option-3">Choose location to be delivered</span></summary>
											<ul>
												<li><span class="tree-option-4">Make payment upon delivery</span></li>
											</ul>
										</details>
									</li>
									</ul>
								</details>
							</li>
						</ul>
				</div>
				<h5>Watch the video below to know how to navigate Santi Health.</h5>
				<center>
				<video autoplay muted controls>
					<source src="includes/images/santivid.mp4" type="video/mp4">
					<source src="includes/images/santivid.ogg" type="video/ogg">
				</video>
				</center>
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
				$getAll = $conn->prepare("SELECT * FROM santi_data WHERE faculty='Obs/Gyn' OR faculty='Diabetologist' OR faculty='Senior consultant' OR faculty='General Physician' OR faculty='General Surgeon' OR faculty='Peaditrician' OR faculty='Neurologist' OR faculty='Cardiologist' OR faculty='Psychiatrist' OR faculty='Orthopedic' OR faculty='Nutritionist/Dietician' OR faculty='Urologist'");
				$getAll->execute();
				$getAll->setFetchMode(PDO::FETCH_ASSOC);
				foreach($getAll as $santidata){
				?>
		<span class="ditch ditching doctors">
			<?php if(htmlspecialchars($santidata['gender'] == 'male')) { echo '<img src="includes/images/OIG2.jpeg" alt="doctor" width="100%" style="object-fit:cover;">';} 
			else echo '<img src="includes/images/doctorslaptop2.jpg" alt="doctor" width="100%" style="object-fit:cover;">';?>
			<form action="payment.php" method="get">
			<h3>Name: <b>Dr.<input hidden name="name" value="<?php echo htmlspecialchars($santidata['fname'].' '. $santidata['sname']) ?? NULL;?>">
			<?php echo htmlspecialchars($santidata['fname'].' '. $santidata['sname']) ?? NULL;?>
			<?php if ($santidata['status'] == 'logged-in') { echo '<span id="logged-in" title="online"></span>';}else echo '<span id="logged-out" title="offline"></span>'; ?></b></h4>
			<h3>Specialist:<input hidden name="speciality" value="<?php echo htmlspecialchars($santidata['faculty'])?>">  
			<?php echo htmlspecialchars($santidata['faculty']) ?? NULL?></h4>
			<h3>Gender: <?php echo ucfirst($santidata['gender']) ?? NULL ?></h4>
			<h3>Consultation Fee:<input hidden name="price" value="<?php echo htmlspecialchars($santidata['price']) ?? NULL?>">
			<?php echo htmlspecialchars($santidata['price'])?></h4>
			<input type="submit" id="ditchbutton" value="Schedule">
			</form>
		</span>
		<?php } 
		$getAll = $conn->prepare("SELECT * FROM santi_data WHERE faculty='Nurse' OR faculty='Nurse Aide' OR faculty='Clinician'");
		$getAll->execute();
		$getAll->setFetchMode(PDO::FETCH_ASSOC);
		foreach($getAll as $santidata){
		?>
		<span class="ditch ditching nurses">
		<img src="includes/images/nurses.jpg" alt="" width="100%">
			<form action="payment.php" method="get">
			<h3>Name: <b>Dr.<input hidden name="name" value="<?php echo $santidata['fname'].' '. $santidata['sname'] ?? NULL;?>"> 
			<?php echo $santidata['fname'].' '. $santidata['sname'] ?? NULL;?></b></h4>
			<h3>Specialist:<input hidden name="speciality" value="<?php echo $santidata['faculty']?>">  
			<?php echo $santidata['faculty']?></h4>
			<h3>Gender: <?php echo ucfirst($santidata['gender']) ?? NULL ?></h4>
			<h3>Consultation Fee:<input hidden name="price" value="<?php echo $santidata['price']?>">
			<?php echo $santidata['price']?></h4>
			<input type="submit" id="ditchbutton" value="Schedule">
			</form>
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
							<th>S. No</th>
							<th>Payment For</th>
							<th>Time of payment</th>
							<th>Name of payer</th>
							<th>Attending Officer</th>
							<th>Mode of payment</th>
							<th>Status of payment</th>
							<th>Sum paid</th>
							<th>Pending amount</th>
						</tr>
						<?php
						$cnt = 1;
						$query = $conn->prepare("SELECT date_of_report, date_of_upload, patient_name, laboratory, report_status, report_amount FROM reports WHERE patient_email=:patient_email");
						$query->bindParam(':patient_email', $cookiemail);
						$query->execute();
						$query->setFetchMode(PDO::FETCH_ASSOC);
						foreach($query as $reportdata){
						?>
						<tr>
							<td><?php echo $cnt++?></td>
							<td><?php echo htmlspecialchars($reportdata['date_of_report']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['date_of_upload']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['patient_name']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['laboratory']) ?? NULL; ?></td>
							<td>Mpesa</td>
							<td><?php echo htmlspecialchars($reportdata['report_status']) ?? NULL; ?></td>
							<td>5,400</td>
							<td><?php echo 'Ksh.'.htmlspecialchars($reportdata['report_amount']) ?? NULL; }if (empty($reportdata))echo '<center><tr><td> No bill reports to show </td></tr></center>';?></td>
						</tr>
				</table>
			</div>
			<div class="lab-reviews" style="display: none;" id="lab-reviews">
			<h1>Lab reports</h1>
				<table>
						<tr>
							<th>S. No</th>
							<th>Date of test</th>
							<th>Date of entry</th>
							<th>Testing reason</th>
							<th>Place of test</th>
							<th>Status</th>
							<th>Sum paid</th>
						</tr>
						<?php
						$cnt = 1;
						$query = $conn->prepare("SELECT date_of_report, date_of_upload, patient_name, patient_email, laboratory, report_status, report_amount FROM reports WHERE patient_email=:patient_email");
						$query->bindParam(':patient_email', $cookiemail);
						$query->execute();
						$query->setFetchMode(PDO::FETCH_ASSOC);
						foreach($query as $reportdata){
						?>
						<tr>
							<td><?php echo $cnt++?></td>
							<td><?php echo htmlspecialchars($reportdata['date_of_report']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['date_of_upload']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['patient_name']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['laboratory']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['report_status']) ?? NULL; ?></td>
							<td><?php echo 'Ksh.'.htmlspecialchars($reportdata['report_amount']) ?? NULL;} if (empty($reportdata))echo '<center><td> No laboratory reports to show </td></center>';?></td>
						</tr>
				</table>
			</div>
			</div>
			<div class="sessions" id="sessions">
			<center>
			<?php if (strcmp($lseo, $cookiemail) === 0): ?>
			<iframe src="<?php echo $embedUrl?>" style="border:none;width:100%;height:460px;background-color: #cad7fda4;" class="digitalsamba" allow="camera; microphone; display-capture; autoplay;"  allowfullscreen="true">
    		</iframe>
			<?php else : ?>
			<center><p id='sessionErr'>No sessions for today</p></center>
			<br>
			<p>If you booked for a session and you can't get the link please lias with your doctor to share with you the invite link.</p>
			<?php endif; ?>
			</center>
			</div>
			<div class="schedules" id="schedules">
				<center>
				<p style="font-size: 150px;font-style:oblique;color:tomato" class="glow">
					Page coming soon...
				</p>
				</center>
			</div>
	</div>
</body>
</html>
<?php } ?>