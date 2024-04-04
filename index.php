<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="">
	<link rel="icon" type="images/x-icon" href="includes/images/white.JPG">
	<link rel="stylesheet" type="text/css" href="includes/styles/main.css">
	<link rel="stylesheet" type="text/css" href="includes/styles/index.css">
	<title>Santi Health Homepage</title>
</head>
	<body onload="myFunction()" style="margin: 0;">
		<div id="loader">
			<img src="includes/images/santi2.png" alt="profile">
		</div>
	<div class="animate-bottom" style="display: none;" id="myDiv">
		<div class="indexNav">
			<img src="includes/images/santi2.png" alt="profile.jpg">
			<div class="buttons">
				<a href="register.php">Register</a>
				<a href="login.php">Login</a>
				</div>
	</div>
		<main>
		<div class="main">
			<center>
			<div class="present">
				<img src="includes/images/steth.jpg" alt="avatar" width="100%" height="800px">
				<div class="index-notes-3">
				<p>
				Welcome to Santi health, we are your number one telemedical institution in Kenya.
				We ensure the services we offer here suits your needs, weather in the office, at home, 
				on transit, Daktari is always one click away. We encoporate variety of mechanisms to 
				ensures your medical needs satisfied with utmost proffessionalism and in detail.
				<br>
				<a href="register.php" class="homelink">Register</a> or 
				<a href="login.php" class="homelink">Login</a>
</center>
<h2>Some of our services include:</h2>
			<div class="home-images">
				<div class="image-container">
					<img src="includes/images/pregnant.jpg" alt="pregnant_woman" width="100%">
				<div class="image-content">
					<h1>Obs/Gyn Services</h1>
					<p>Have a chance to meet our well-experienced gynaecologists who'll take care of you and the little one through out the journey</p>
				</div>
				</div>
				<div class="image-container">
					<img src="includes/images/skin.jpg" alt="pregnant_woman" width="100%">
				<div class="image-content">
					<h1>Dermatologist</h1>
					<p>For all your skin problems come talk to our dermatologist</p>
				</div>
				</div>
				<div class="image-container">
					<img src="includes/images/doctorsteth.jpg" alt="pregnant_woman" width="100%">
				<div class="image-content">
					<h1>General Physician</h1>
					<p>Our well traversed general practitioners come in handy to ensure you get the right prescription and the right medication</p>
				</div>
				</div>
				<div class="image-container">
					<img src="includes/images/ear.jpg" alt="pregnant_woman" width="100%">
				<div class="image-content">
					<h1>ENT and neck Surgeon</h1>
					<p>Our commited ENT surgeon is available to ensure all your ear, nose, throat and neck discomforts are taken care of</p>
				</div>
				</div>
			</div>
			</p>
			</div>
	</center>
			</span>
			<div class="devices">
			<h1>Accessible across all devices</h1>
			<div class="smartphone">
				<div class="content">
					<img src="includes/images/white.JPG" style="width:100%;border:none;height:90%;float:left;" />
				</div>
				</div>
			</div>
		<span class="ditch-1">
		<img src="includes/images/schedule.jpeg" alt="schedule.png">
		<center>
		<p><a href="register.php" style="color: white;">Register Today</a> to utilize	</p>
		<p class="index-notes">The appointment scheduler</p>
		</center>
		</span>
		<center>
		<span class="note-1">
			<p>
				Talk to us at any time of the day with support from our 24/7 response team
			</p>
		</span>
		</center>
		<span class="ditch-2">
		<img src="includes/images/teleconsultation.jpg" alt="schedule.png">
			<p>Are you depressed? Talk to our experienced psychologists and gain full control of your mental health</p>
		</span>
			<marquee width="100%">
			<h1>Accepted Insurances</h1>
			<img src="includes/images/nhif.png" alt="nhif" width="20%">
			<img src="includes/images/jubilee.png" alt="jubilee" width="20%">
			<img src="includes/images/pacis.png" alt="pacis" width="20%" height="10%">
			<img src="includes/images/ga.png" alt="ga" width="20%" height="10%">
			<img src="includes/images/britam.png" alt="britam" width="20%" height="10%">
			</marquee>
		</main>
</body>
<footer>
<div class="footer">
			<?php include 'includes/footer.php' ?>
	</div>
</footer>
	</div>
<script>
		var myVar;
		function myFunction(){
			myVar = setTimeout(showPage, 3000);
		}
		function showPage(){
			document.getElementById("loader").style.display = "none";
			document.getElementById("myDiv").style.display = "block";
		}
	</script>
</html>
