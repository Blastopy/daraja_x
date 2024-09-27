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
<div class="responsive">
	<center>
	<img src="includes/images/santi2.png" alt="profile" width="300px">
	</center>
			<p>Page not configured for your device, please reach us on our whatsapp by clicking this icon 
			<br>
			<center>	
			<a href="https://chat.whatsapp.com/FOcNZQSDgpoEnRC2FtreyZ">
			<svg xmlns="http://www.w3.org/2000/svg" style="color:green" width="60" height="60" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
			<path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
			</svg></a>
			or reach us through our email address: 
			<b>info.santihealth@gmail.com</b> 
			or call us via our 24/7 response tel no: <b>+254(0)710444964</b>
			</p>
		</center>
		</div>
	<header>
	<div class="icn menuicn" id="menuicn" alt='menu-icon'>
	<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
</svg> 
</div>
		<div class="logosec">
			<div class="logo"><img src="includes/images/white.JPG" width="15%"></div> 
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
					<h1 class="recent-Articles">Emergency Ambulance services in Kenya</h1> 
					<button class="view">View All</button> 
				</div> 
				<div class="report-body"> 
					<div class="report-topic-heading"> 
						<h3 class="t-op">S. No</h3> 
						<h3 class="t-op">Agency name.</h3> 
						<h3 class="t-op">Telephone number</h3> 
					</div> 
						<div class="item1"> 
							<h3 class="t-op-nextlvl">1.</h3> 
							<h3 class="t-op-nextlvl">Ivory Ambulance</h3> 
							<h3 class="t-op-nextlvl">0726998880</h3> 
						</div> 
						<div class="item1"> 
							<h3 class="t-op-nextlvl">2.</h3> 
							<h3 class="t-op-nextlvl">Nairobi East Hospital</h3> 
							<h3 class="t-op-nextlvl">0111435797</h3> 
						</div> 
						<div class="item1"> 
							<h3 class="t-op-nextlvl">3.</h3> 
							<h3 class="t-op-nextlvl">Eplus</h3> 
							<h3 class="t-op-nextlvl">0700395395</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">4.</h3> 
							<h3 class="t-op-nextlvl">A.A.R</h3> 
							<h3 class="t-op-nextlvl">0725225225</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">5.</h3> 
							<h3 class="t-op-nextlvl">St. John Ambulance</h3> 
							<h3 class="t-op-nextlvl">0721225285</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">6.</h3> 
							<h3 class="t-op-nextlvl">RFH</h3> 
							<h3 class="t-op-nextlvl">0741574782</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">7.</h3> 
							<h3 class="t-op-nextlvl">OCOA</h3> 
							<h3 class="t-op-nextlvl">0758692000</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">8.</h3> 
							<h3 class="t-op-nextlvl">Eureka</h3> 
							<h3 class="t-op-nextlvl">0712222547</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">9.</h3> 
							<h3 class="t-op-nextlvl">Moshi Ambulance</h3> 
							<h3 class="t-op-nextlvl">0792274997</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">10.</h3> 
							<h3 class="t-op-nextlvl">Lifemed</h3> 
							<h3 class="t-op-nextlvl">0708188085</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">11.</h3> 
							<h3 class="t-op-nextlvl">EOC</h3> 
							<h3 class="t-op-nextlvl">1508</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">12.</h3> 
							<h3 class="t-op-nextlvl">Eagle Rescue Ambulance</h3> 
							<h3 class="t-op-nextlvl">0727498805</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">13.</h3> 
							<h3 class="t-op-nextlvl">Arrow Ambulance</h3> 
							<h3 class="t-op-nextlvl">0723623880</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">14.</h3> 
							<h3 class="t-op-nextlvl">Nyanchwa Ambulance Kisii</h3> 
							<h3 class="t-op-nextlvl">0723623880</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">15.</h3> 
							<h3 class="t-op-nextlvl">Prodigy Ambulance</h3> 
							<h3 class="t-op-nextlvl">0713644686</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">16.</h3> 
							<h3 class="t-op-nextlvl">Lifeline Ambulance</h3> 
							<h3 class="t-op-nextlvl">0700024764</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">17.</h3> 
							<h3 class="t-op-nextlvl">VHS Ambulance</h3> 
							<h3 class="t-op-nextlvl">0702610657</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">18.</h3> 
							<h3 class="t-op-nextlvl">Red cross</h3> 
							<h3 class="t-op-nextlvl">1199</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">19.</h3> 
							<h3 class="t-op-nextlvl">Neema Ambulance Kahawa Sukari</h3> 
							<h3 class="t-op-nextlvl">0759263586</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">20.</h3> 
							<h3 class="t-op-nextlvl">Morning star Ambulance</h3> 
							<h3 class="t-op-nextlvl">0758803688</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">21.</h3> 
							<h3 class="t-op-nextlvl">Ladnan Hospital</h3> 
							<h3 class="t-op-nextlvl">0707000730</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">22.</h3> 
							<h3 class="t-op-nextlvl">Nairobi Women's Kitengela</h3> 
							<h3 class="t-op-nextlvl">0717723677</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">23.</h3> 
							<h3 class="t-op-nextlvl">King David Hospital Ambulance</h3> 
							<h3 class="t-op-nextlvl">0798672797</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">24.</h3> 
							<h3 class="t-op-nextlvl">Quick Safe Ambulance Nairobi</h3> 
							<h3 class="t-op-nextlvl">0721853796</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">25.</h3> 
							<h3 class="t-op-nextlvl">Nairobi Women's Nakuru</h3> 
							<h3 class="t-op-nextlvl">0707957840</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">26.</h3> 
							<h3 class="t-op-nextlvl">First Responder</h3> 
							<h3 class="t-op-nextlvl">0792005351</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">27.</h3> 
							<h3 class="t-op-nextlvl">Radiant Hospital Ambulance</h3> 
							<h3 class="t-op-nextlvl">0725532000</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">28.</h3> 
							<h3 class="t-op-nextlvl">Avenue Parklands</h3> 
							<h3 class="t-op-nextlvl">0711060150/0711060175</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">29.</h3> 
							<h3 class="t-op-nextlvl">Avenue Thika</h3> 
							<h3 class="t-op-nextlvl">0711060800/0715869147</h3> 
						</div>
						<div class="item1"> 
							<h3 class="t-op-nextlvl">30.</h3> 
							<h3 class="t-op-nextlvl">Rescuemed</h3>
							<h3 class="t-op-nextlvl">0722805645</h3>
						</div>
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
				$getAll = $conn->prepare("SELECT * FROM santi_data WHERE faculty='Obs/Gyn' OR faculty='Diabetologist' OR faculty='Senior consultant' OR faculty='General Physician' OR faculty='Peaditrician' OR faculty='Neurologist' OR faculty='Cardiologist' OR faculty='Psychiatrist' OR faculty='Orthopedic' OR faculty='Nutritionist/Dietician'");
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
						<?php
						$cnt = 1;
						$mail = $_COOKIE['email'];
						$query = $conn->prepare("SELECT * FROM reports WHERE patient_email=:email");
						$query->bindParam(':email', $email);
						$query->execute();
						$query->setFetchMode(PDO::FETCH_ASSOC);
						foreach($query as $reportdata){
							if ($query == NULL){
								echo "You don't have any laboratory reports";
							}
						?>
						<tr>
							<td><?php echo $cnt++?></td>
							<td><?php echo htmlspecialchars($reportdata['date_of_report']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['date_of_upload']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['patient_name']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['laboratory']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['report_status']) ?? NULL; ?></td>
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
						$mail = $_COOKIE['email'];
						$query = $conn->prepare("SELECT * FROM reports WHERE patient_email=:email");
						$query->bindParam(':email', $email);
						$query->execute();
						$query->setFetchMode(PDO::FETCH_ASSOC);
						foreach($query as $reportdata){
							if ($query == NULL){
								echo "You don't have any laboratory reports";
							}
						?>
						<tr>
							<td><?php echo $cnt++?></td>
							<td><?php echo htmlspecialchars($reportdata['date_of_report']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['date_of_upload']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['patient_name']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['laboratory']) ?? NULL; ?></td>
							<td><?php echo htmlspecialchars($reportdata['report_status']) ?? NULL; ?></td>
							<td><?php echo 'Ksh.'.htmlspecialchars($reportdata['report_amount']) ?? NULL; }if (empty($reportdata))echo '<center><tr><td> No laboratory reports to show </td></tr></center>';?></td>
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
						$search_doc = array_search($email, $notify['patient_email'], strict:true);
						if (!empty($search_doc)){
							echo $session;
						}
					} else echo $session = "<center><p id='sessionErr'>No sessions for today</p></center>";
			?>
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