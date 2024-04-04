<?php session_start();
if (empty($_SESSION['email']) && empty($_COOKIE['fname']) && empty($_COOKIE['sname'])) {
	setcookie('fname', '', time() - 3600);
	setcookie('email', '',time() - 3600);
	setcookie('sname', '', time() - 3600);
	session_destroy();
	header('location:logout.php');
}else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="http-equiv" content="30">
	<link rel="icon" type="images/x-icon" href="includes/images/white.JPG">
	<link rel="stylesheet" href="w3.css" />
	<link rel="stylesheet" href="includes/styles/main.css"/>
	<link rel="stylesheet" href="includes/styles/dashboard.css"/>
	<script src="includes/main.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>Doctors Profiles</title>
</head>
<body>
		<h1>Doctor's profiles</h1>		
		<form class="searchbar">
          <input type="text" name="search" placeholder="Search...">
		  <button type="submit"><i class="fa fa-search"></i></button>
		 <a href="dashboard.php" class="homebtn">Home</a>
		</form>
	<main>
		<span class="ditch">
			<img src="includes/images/doctor.jpeg" alt="" width="100%">
			<h3>Name: <b>Dr. Mito Harris</b></h3>
			<h3>Specialist: General practirioner</h3>
			<button onclick="document.getElementById('id01').style.display ='block'" style="width:auto">Read more</button>
		</span>
		<div class="modal" id="id01">
				<div class="imgcontainer">
					<span onclick="document.getElementById('id01').style.display = 'none'" class="close" title="close modal">$times;</span>
					<img src="includes/images/doctor.jpeg" alt="Avatar" class="avatar">
				</div>
				<div class="container">
					<p>
						<h3>Name: Dr. Mito Harris</h3> <br>
						<h3>Speciality:General practitioner</h3>
						<p>
							Dr. Mito Harris is a qualified medical practitioner who has both studied and practiced
							medicine in Kenya. He graduated from the Nairobi university school of medicine class of 
							2017 and upon graduation Dr. Mito practiced his medicine at Kenyatta uiversity teaching and 
							referal hospital in Nairobi(KUTRH), Kenyatta national hospital, The Agha Khan University Hospital(Nairobi)
						</p>
					</p>
				<button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbutton">Book appointment</button>
			</div>
				</div>
			<script>
				var modal = document.getElementById('id01');
				window.onclick = function(event) {
					if (event.target == modal) {
						modal.style.display = "none";
					}
				}
			</script>
		</div>
	</main>
</body>
</html>
<?php } ?>