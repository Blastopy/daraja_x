<?php 
session_start();
if (empty($_SESSION['email']) && empty($_COOKIE['fname']) && empty($_COOKIE['sname'])) {
	setcookie('fname', '', time() - 3600);
	setcookie('email', '',time() - 3600);
	setcookie('sname', '', time() - 3600);
	session_destroy();
	header('location:logout.php');
}else {
	include 'includes/config.php';
	include 'newmember.php';
	$fname = ucfirst($_COOKIE['fname']);
	$name = $_COOKIE['fname'];
	$sname = ucfirst($_COOKIE['sname']);
	$profile = substr($fname, 0, 1) . substr($sname, 0, 1);
	$greeting = date('a');
	if ($greeting == 'am') {
		$greet = 'Good morning';
	} elseif ($greeting == 'pm') {
		$greet = 'Good evening';
	}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="http-equiv" content="30">
	<link rel="icon" type="images/x-icon" href="includes/images/white.JPG">
	<link rel="stylesheet" href="w3.css" />
	<link rel="stylesheet" href="includes/styles/dashboard.css" />
	<script src="includes/main.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>Santi Admin Dashboard</title>
</head>
<body>
<div class="parent">
	<div class="" id="mySidebar">
	  <button id="closeNav" class="w3-bar-item w3-button w3-large" onclick="w3_close()">&times;</button><br>
	 <ul>

	<h1><a href="" style="text-decoration: none;">Santi Health Admin panel</a></h1>
	 <li><button onclick="showPatients()">List of patients</button></li>
 	 <li><button onclick="showDoctors()">Medical Officials</button></li>
	  <li><button onclick="registerDoc()">Medical officers registration</button></li>
 	 <li><button onclick="viewRecords()">Patient's records</button></li>
 	 <li><button onclick="viewBills()">Bills and receipts</button></li>
 	 <li><button onclick="">Updates and uploads</button></li>
 	 <li><button onclick="liveSessions()">Live sessions</button></li>
 	 <li><button onclick="pharmacies()">Pharmacy and deliveries</button></li>
 	 <li><button onclick="showServices()">Homecare services</button></li>
</ul>
	</div>
	<div id="main">
	<div id="navbar">
	<button id="openNav" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
	</div>
	<header>
		<img src="includes/images/santi2.png" alt="avatar" width="10%">
		<a href="logout.php">Logout</a>
		<a href="logout.php">Notifications</a>
		<a href=""><b><?php echo $profile ?></b></a>
			<button onclick="darkmode()" class="theme-toggle"></button>
	</header>
		<main id="main_content">
			<div class="page" id="home-page">
			<div class="appointment">
			<div class="box-container"> 
  <div class="box box1" style="background-image: url('includes/images/patient.jpeg');background-position:center;background-size:cover;"> 
	  <div class="text"> 
		  <h2 class="topic-heading">Doctor of the day</h2> 
		  <h2 class="topic">Dr. Mito Harris</h2> 
	  </div> 
  </div> 
  <div class="box-container"> 
			<div class="box box2"> 
                    <div class="text"> 
                        <h2 class="topic-heading">150</h2> 
                        <h2 class="topic">Logged-in staff</h2> 
                    </div> 
			</div>
                <div class="box box3"> 
                    <div class="text"> 
                        <h2 class="topic-heading">320</h2> 
                        <h2 class="topic">Patients attended to today</h2> 
                    </div> 
                </div> 
  
                <div class="box box4"> 
                    <div class="text"> 
                        <h2 class="topic-heading">70</h2> 
                        <h2 class="topic">Articles Published</h2> 
                    </div> 
			</div>
			</div>
			</div>
			</div>
            <div class="report-container"> 
                <div class="report-header"> 
                    <h1 class="recent-Articles">Recent Articles</h1> 
                    <button class="view">View All</button> 
                </div> 
  
                <div class="report-body"> 
                    <div class="report-topic-heading"> 
                        <h3 class="t-op" style="color: purple;">Article</h3> 
                        <h3 class="t-op" style="color: purple;">Views</h3> 
                        <h3 class="t-op" style="color: purple;">Comments</h3> 
                        <h3 class="t-op" style="color: purple;">Status</h3> 
                    </div> 
  
                    <div class="items"> 
                        <div class="item1"> 
                            <h3 class="t-op-nextlvl" style="color: purple;">Article 73</h3> 
                            <h3 class="t-op-nextlvl" style="color: purple;">2.9k</h3> 
                            <h3 class="t-op-nextlvl" style="color: purple;">210</h3> 
                            <h3 class="t-op-nextlvl label-tag" style="color: purple;">Published</h3> 
                        </div> 
                        <div class="item1"> 
                            <h3 class="t-op-nextlvl">Article 72</h3> 
                            <h3 class="t-op-nextlvl">1.5k</h3> 
                            <h3 class="t-op-nextlvl">360</h3> 
                            <h3 class="t-op-nextlvl label-tag">Published</h3> 
                        </div> 
                        <div class="item1"> 
                            <h3 class="t-op-nextlvl">Article 71</h3> 
                            <h3 class="t-op-nextlvl">1.1k</h3> 
                            <h3 class="t-op-nextlvl">150</h3> 
                            <h3 class="t-op-nextlvl label-tag">Published</h3> 
                        </div> 
                        <div class="item1"> 
                            <h3 class="t-op-nextlvl">Article 70</h3> 
                            <h3 class="t-op-nextlvl">1.2k</h3> 
                            <h3 class="t-op-nextlvl">420</h3> 
                            <h3 class="t-op-nextlvl label-tag">Published</h3> 
                        </div> 
                        <div class="item1"> 
                            <h3 class="t-op-nextlvl">Article 69</h3> 
                            <h3 class="t-op-nextlvl">2.6k</h3> 
                            <h3 class="t-op-nextlvl">190</h3> 
                            <h3 class="t-op-nextlvl label-tag">Published</h3> 
                        </div> 
  
                        <div class="item1"> 
                            <h3 class="t-op-nextlvl">Article 68</h3> 
                            <h3 class="t-op-nextlvl">1.9k</h3> 
                            <h3 class="t-op-nextlvl">390</h3> 
                            <h3 class="t-op-nextlvl label-tag">Published</h3> 
                        </div> 
  
                        <div class="item1"> 
                            <h3 class="t-op-nextlvl">Article 67</h3> 
                            <h3 class="t-op-nextlvl">1.2k</h3> 
                            <h3 class="t-op-nextlvl">580</h3> 
                            <h3 class="t-op-nextlvl label-tag">Published</h3> 
                        </div> 
  
                        <div class="item1"> 
                            <h3 class="t-op-nextlvl">Article 66</h3> 
                            <h3 class="t-op-nextlvl">3.6k</h3> 
                            <h3 class="t-op-nextlvl">160</h3> 
                            <h3 class="t-op-nextlvl label-tag">Published</h3> 
                        </div> 
                        <div class="item1"> 
                            <h3 class="t-op-nextlvl">Article 65</h3> 
                            <h3 class="t-op-nextlvl">1.3k</h3> 
                            <h3 class="t-op-nextlvl">220</h3> 
                            <h3 class="t-op-nextlvl label-tag">Published</h3> 
                        </div> 
  
                    </div> 
                </div> 
			</div>
		</main>
		<div class="charge-sheet" id="charge-sheet">
			<h3>Hello <?php echo $fname ?>, here is a list of our registered patients</h3>

			<div class="charge-table">
				<table>
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
							<td><?php echo $santidata['registration_date'] ?? NULL ?></td>
							<td><?php echo $santidata['id'] ?? NULL ?></td>
							<td><?php echo $santidata['fname'].' '.$santidata['sname'] ?? NULL ?></td>
							<td><?php echo $santidata['tel'] ?? NULL ?></td>
							<td><?php echo $santidata['email'] ?? NULL ?></td>
							<td><?php echo $santidata['residence'] ?? NULL ?></td>
							<td><?php echo $santidata['status'] ?? NULL ?></td>
							<td><button>Delete</button></td>
							<?php } ?>
						</tr>
				</table>
			</div>
		</div>
		<div class="labtests" id="labtests">
		<h3>Hello <?php echo $fname ?>, here is a list of our doctors</h3>
				<table>
						<tr>
							<th>S. No</th>
							<th>Date of registration</th>
							<th>Official's name</th>
							<th>Specialist</th>
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
							<td><?php echo $officialdata['registration_date'] ?? NULL ?></td>
							<td><?php echo $officialdata['fname'] .' '. $officialdata['sname'] ?? NULL ?></td>
							<td><?php echo $officialdata['faculty'] ?? NULL ?></td>
							<td><?php echo $officialdata['licence'] ?? NULL ?></td>
							<td><?php echo $officialdata['gender'] ?? NULL ?></td>
							<td><?php echo $officialdata['status'] ?? NULL ?></td>
							<td><button>Delete</button></td>
							<?php } ?>
						</tr>
				</table>
			</div>
			<div class="imaging" id="imaging">
			<form method="POST" action="newmember.php" enctype="multipart/form-data">
					<fieldset>
					<h1>Enter official's details in this form</h1>
					<label for="Officials first">Official's first name:</label><br>
					<input type="text" name="ofname" required placeholder="Official's first name" title="Doctor's first name goes here" autocomplete="on" class="form-group <?php echo $ofnameErr ?? NULL?>">
					<div class="errormessage">
						<?php echo $ofnameErr ?>
					</div>
					<br>
					<label for="Officials second name">Official's Second name:</label><br>
					<input type="text" name="osname" required placeholder="Official's second name" title="Doctor's second name goes here" autocomplete="on" class="form-group<?php echo $osnameErr ?? NULL ?>">
					<div class="errormessage">
						<?php echo $osnameErr ?>
					</div>
					<br>
					<label for="Officials phone number">Doctor's phone number:</label><br>
					<input type="text" name="tel" required placeholder="07xxxxxxxx" title="Doctor's phone number goes here" autocomplete="on" class="form-group<?php echo $pnumberErr ?? NULL?>">
						<div class="errormessage">
							<?php echo $pnumberErr ?>
						</div>
					<br>
					<label for="Officials email address">Doctor's Email Address:</label><br>
					<input type="text" name="email" required placeholder="name@example.com" title="Doctor's Email Address goes here" autocomplete="on" class="form-group<?php echo $emailErr ?? NULL?>">
						<div class="errormessage">
							<?php echo $emailErr ?>
						</div>
					<br>
					<label for="official's_licence">Doctor's license number:</label><br>
					<input type="text" name="licence" required placeholder="Official's license number" title="Official's licence number goes here">
					<br>
					<label for="Official's residence">County of residence:</label><br>
					<input type="text" name="residence" required placeholder="Official's county of residence" title="Official's licence number goes here">
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
					<input type="text" name="price" required placeholder="Charges in Ksh." title="Official's charge of service goes here">
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
					<label for="doctor's note">Doctor's experience</label>
					<br>
					<input type="textarea" name="doctors_notes" placeholder="Type the officials work experience here..." autocomplete="off" width="100px" height="100px">
					<br>
					<label for="Official's residence">Your password:</label><br>
					<input type="password" id="psw" name="password" required placeholder="Password" title="Official's licence number goes here" class="form-group<?php echo $passwordErr ??  NULL ?>">
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
				<div id="message">
					<h3>Password must contain the following:</h3>
					<p id="letter" class="invalid">A <b>lowecase</b> letter</p>
					<p id="capital" class="invalid">An <b>Uppercase</b> letter</p>
					<p id="number" class="invalid">A <b>number</b></p>
					<p id="length" class="invalid">Minimum of <b>8 characters</b></p>
				</div>
					<center>
						<input type="submit" name="submit" value="submit">
					</center>
			</div>
				</fieldset>
				</form>
			<br>	
			</div>
			<div class="reviews" id="reviews">
			<h3>Hello <?php echo $fname ?>, Here are the medical records of our patients</h3>
			<table>
						<tr>
							<th>Date of registration</th>
							<th>Date of review</th>
							<th>Name of the patient</th>
							<th>Attending medical officer</th>
							<th>Reason for Attention</th>
							<th>Date of next review</th>
							<th>Recommendations</th>
						</tr>
						<tr>
							<td>12/06/2024</td>
							<td>14/09/2025</td>
							<td>Steven Oloo</td>
							<td>Dr. Daniel Omollo (specialist)</td>
							<td>Malaria</td>
							<td>04/10/2025</td>
							<td>Dosage of Doxycyclone 500mg</td>
						</tr>
				</table>
			</div>
			<div class="updates" id="updates">
			<h3>Hello <?php echo $fname ?>, here are live session </h3>
			<div class="liveNode">
				<div class="redDot"></div> Live<br>
				<span class="patientNode">
					<img src="includes/images/santi.PNG" alt="avatar.jpg" width="10%" height="10%">
				<p>Dr. Daniel Osoro</p>
				</span>
				<br>
				<center>
				<span class="pointer" style="color:white">
					<p>>>>><br>
					<<<<
					</p>
				</span>
				</center>
				<span class="doctorNode">
				<img src="includes/images/santi.PNG" alt="avatar.jpg" width="10%" height="10%">
				<p>Steven Mwaniki</p>	
			</span>
				<button>End Session</button>
			</div>
			</div>
			<div class="seminors" id="seminors">
			<h3>Hello <?php echo $fname ?>, Here is our Accounts</h3>
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
			<div class="pharmacy" id="pharmacy">
			<h3>Hello <?php echo $fname ?>, Here is a list of our pharmacies that we partner with to give you the best medication</h3>
			</div>
			<div class="nurses" id="nurses">
			<table>
						<tr>
							<th>Begin of service</th>
							<th>Name of Official</th>
							<th>Position</th>
							<th>Place of posting</th>
							<th>Name of Patient</th>
							<th>Number of visits</th>
						</tr>
						<tr>
							<td>12/06/2024</td>
							<td>John Karanja</td>
							<td>Nurse</td>
							<td>Kariandusi</td>
							<td>Wachira waruru</td>
							<td>13</td>
						</tr>
						<tr>
						<td>12/06/2024</td>
							<td>John Karanja</td>
							<td>Nurse</td>
							<td>Kariandusi</td>
							<td>Wachira waruru</td>
							<td>13</td>
						</tr>
				</table>
			</div>
		<div class="doctors-profile" id="doctors-profile">
		<h1>Doctor's profiles</h1>		
		<form class="searchbar">
          <input type="text" name="search" id="searchbox" placeholder="Search for a doctor or specialist"  onkeyup="livesearch()">
		</form>
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
		</div>
		</div>
</div>
	<script>
		// search funtion
		function livesearch(){
                var input, filter, table, tr, td, i ,txtValue;
                input = document.getElementById("myInput");
                filter = input.value.toUpperCase();
                table = document.getElementById("myTable");
                tr = table.getElementsByTagName("tr");
                for(i = 0; i < tr.length; i++){
                    td = tr[i].getElementsByTagName("td")[1];
                    if(td){
                        txtValue = td.textContent || td.innerText;
                        if(txtValue.toUpperCase().indexOf(filter) >-1){
                            tr[i].style.display="";
                        }else{
                            tr[i].style.display = "none";
                        }
                    }
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
		var modal = document.getElementById('id01');
				window.onclick = function(event) {
					if (event.target == modal) {
						modal.style.display = "none";
					}
				}
		if (navigator.onLine == 'false') {
			document.getElementsByName('body').innerHTML = "Seems you are offline😒."
		}
		function darkmode() {
			var element = document.body;
			element.classList.toggle("dark-mode")
			element.classList.toggle("body");
		}
		function showPatients() {
			const chargeSheet = document.getElementById('charge-sheet');
			chargeSheet.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = chargeSheet;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('labtests').style.display = 'none';
			document.getElementById('seminors').style.display = 'none';
			document.getElementById('imaging').style.display = 'none';
			document.getElementById('reviews').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
			document.getElementById('updates').style.display = 'none';
		}
		function showDoctors() {
			const labTests = document.getElementById('labtests');
			labTests.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = labTests;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('charge-sheet').style.display = 'none';
			document.getElementById('imaging').style.display = 'none';
			document.getElementById('updates').style.display = 'none';
			document.getElementById('reviews').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
		}
		function registerDoc() {
			const images = document.getElementById('imaging');
			images.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = images;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('charge-sheet').style.display = 'none';
			document.getElementById('labtests').style.display = 'none';
			document.getElementById('updates').style.display = 'none';
			document.getElementById('reviews').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
		}
		function viewRecords() {
			const reviews = document.getElementById('reviews');
			reviews.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = reviews;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('charge-sheet').style.display = 'none';
			document.getElementById('labtests').style.display = 'none';
			document.getElementById('imaging').style.display = 'none';
			document.getElementById('updates').style.display = 'none';
			document.getElementById('seminors').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
		}
		function liveSessions() {
			const updates = document.getElementById('updates');
			updates.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = updates;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('labtests').style.display = 'none';
			document.getElementById('imaging').style.display = 'none';
			document.getElementById('reviews').style.display = 'none';
			document.getElementById('seminors').style.display = 'none';
			document.getElementById('charge-sheet').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
			document.getElementById('pharmacy').style.display = 'none';
		}
		function viewBills() {
			const seminors = document.getElementById('seminors');
			seminors.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = seminors;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('labtests').style.display = 'none';
			document.getElementById('imaging').style.display = 'none';
			document.getElementById('reviews').style.display = 'none';
			document.getElementById('charge-sheet').style.display = 'none';
			document.getElementById('updates').style.display = 'none';
			document.getElementById('pharmacy').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
		}
		function pharmacies() {
			const pharmacy = document.getElementById('pharmacy');
			pharmacy.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = pharmacy;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('labtests').style.display = 'none';
			document.getElementById('imaging').style.display = 'none';
			document.getElementById('reviews').style.display = 'none';
			document.getElementById('charge-sheet').style.display = 'none';
			document.getElementById('updates').style.display = 'none';
			document.getElementById('seminors').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
		}
		function showServices() {
			const nurses = document.getElementById('nurses');
			nurses.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = nurses;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('labtests').style.display = 'none';
			document.getElementById('imaging').style.display = 'none';
			document.getElementById('reviews').style.display = 'none';
			document.getElementById('charge-sheet').style.display = 'none';
			document.getElementById('updates').style.display = 'none';
			document.getElementById('seminors').style.display = 'none';
			document.getElementById('pharmacy').style.display = 'none';
		}
</script>
</body>
</html>
<?php } ?>