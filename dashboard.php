<?php session_start();
if (empty($_SESSION['email']) && empty($_COOKIE['fname']) && empty($_COOKIE['sname'])) {
	setcookie('fname', '', time() - 3600);
	setcookie('email', '',time() - 3600);
	setcookie('sname', '', time() - 3600);
	session_destroy();
	header('location:logout.php');
}else {
	setcookie('price', '1500');
	$fname = ucfirst($_COOKIE['fname']);
	$name = $_COOKIE['fname'];
	$sname = ucfirst($_COOKIE['sname']);
	$profile = substr($fname, 0, 1) . substr($sname, 0, 1);
	
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
	<title>Santi Dashboard</title>
</head>
<body>
<div class="parent">
	<div class="" id="mySidebar">
	  <button id="closeNav" class="w3-bar-item w3-button w3-large" onclick="w3_close()">&times;</button><br>
	 <ul>
		<l1><h1><a href="dashboard.php" style="text-decoration: none;">Santi Health<a></a></h1></l1>
		<li><button onclick="showDoctors()">Doctors and specialists</button></li>
		<li><button onclick="showCharge()">Charge sheet</button></li>
		<li><button onclick="showTests()">Laboratory services</button></li>
		<li><button onclick="showImages()">X-rays and MRIs</button></li>
		<li><button onclick="showReviews()">Doctors' reviews and notes</button></li>
		<li><button onclick="showUpdates()">Updates</button></li>
		<li><button onclick="showAppointments()">Scheduled Appointments</button></li>
		<li><button onclick="showSeminors()">Seminors and education programms</button></li>
		<li><button onclick="pharmacies()">Pharmacy</button></li>
		<li><button onclick="showNurses()">Homecare services</button></li>
</ul>
	</div>
	<div id="main">
	<div id="navbar">
	<button id="openNav" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
	</div>
	<header id="myNavbar">
		<img src="includes/images/santi2.png" alt="" width="10%">
		<a href="logout.php">Logout</a>
		<a href="">Notifications</a>
	<select>
		<option selected class="profile-selection" disabled><b><?php echo $profile ?></b></option>
		<option><a href="updateprofile.php">Update profile</a></option>
		<option>Change password</option>
	</select>
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
			<h3>Hello <?php echo $fname ?>, here is your charge sheet</h3>
			<div class="charge-table">
				<table>
						<tr>
							<th>Date</th>
							<th>Doctor</th>
							<th>Reason</th>
							<th>Bill</th>
						</tr>
						<tr>
							<td>12/06/2024</td>
							<td>Dr. Harris Mito</td>
							<td>Malaria</td>
							<td>Paid</td>
						</tr>
				</table>
			</div>
		</div>
		<div class="labtests" id="labtests">
		<h3>Hello <?php echo $fname ?>, here are your lab tests</h3>
				<table>
						<tr>
							<th>Date of test</th>
							<th>Doctor</th>
							<th>Result</th>
							<th>Bill</th>
						</tr>
						<tr>
							<td>12/06/2024</td>
							<td>Dr. Harris Mito</td>
							<td>Positive</td>
							<td>Paid</td>
						</tr>
				</table>
			</div>
			<div class="imaging" id="imaging">
			<h3>Hello <?php echo $fname ?>, no pending image reviews as of now</h3>
			</div>
			<div class="reviews" id="reviews">
			<h3>Hello <?php echo $fname ?>, no reviews as of now</h3>
			</div>
			<div class="updates" id="updates">
			<h3>Hello <?php echo $fname ?>, no updates as of now</h3>
			</div>
			<div class="seminors" id="seminors">
			<h3>Hello <?php echo $fname ?>, Please register for our upcoming seminor event</h3>
			</div>
			<div class="pharmacy" id="pharmacy">
			<h3>Hello <?php echo $fname ?>, Here is a list of our pharmacies that we partner with to give you the best medication</h3>
			</div>
			<div class="nurses" id="nurses">
			<h1>Nurses profiles and our care givers</h1>		
		<form class="searchbar">
          <input type="text" name="search" id="searchbox" placeholder="Search for a nurse/nurse aid/phlebotomist/clinician" oninput="livesearch()">
		</form>
		<span class="ditch">
			<img src="includes/images/doctor.jpeg" alt="" width="100%">
			<h3>Name: <b>Mito Harris</b></h3>
			<h3>Job description: Nurse</h3>
			<button onclick="document.getElementById('id01').style.display ='block'" style="width:auto">Read more</button>
		</span>
		<div class="modal" id="id01">
				<div class="imgcontainer">
					<span onclick="document.getElementById('id01').style.display = 'none'" class="close" title="close modal">&times;</span>
					<img src="includes/images/doctor.jpeg" alt="Avatar" class="avatar" width="50%">
				</div>
				<div class="container">
					<p>
						<h3>Name: Jane Wambui</h3>
						<h3>Speciality: Nurse</h3>
						<h3>Charges: Ksh. 2500</h3>
						<p>
							Nurse Jane Wambui is a qualified nurse registerd with the Nursing council of Kenya, she is experienced in the field which makes her 
							outstanding while shw perfomes her patient-centered duties.
						</p>
					</p>
				<button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbutton">Book appointment</button>
			</div>
				</div>
			</div>
		<div class="doctors-profile" id="doctors-profile">
		<h1>Doctor's profiles</h1>
		<center>		
		<form class="searchbar">
          <input type="text" name="search" id="searchbox" placeholder="Search for a doctor or specialist" oninput="livesearch()">
		</form>
		</center>
		<span class="ditch">
			<img src="includes/images/doctor.jpeg" alt="" width="100%">
			<h3>Name: <b>Dr. Mito Harris</b></h3>
			<h3>Specialist: General practirioner</h3>
			<button onclick="document.getElementById('id02').style.display ='block'" style="width:auto">Read more</button>
		</span>
		<div class="modal" id="id02">
				<div class="imgcontainer">
					<span onclick="document.getElementById('id02').style.display = 'none'" class="close" title="close modal">&times;</span>
					<img src="includes/images/doctor.jpeg" alt="Avatar" class="avatar">
				</div>
				<div class="container">
					<p>
						<h3>Name: Dr. Mito Harris</h3>
						<h3>Speciality: General practitioner</h3>
						<h3>Consultation Fee: Ksh. 1500</h3>
						<p>
							Dr. Mito Harris is a qualified medical practitioner who has both studied and practiced
							medicine in Kenya. He graduated from the Nairobi university school of medicine class of 
							2017 and upon graduation Dr. Mito practiced his medicine at Kenyatta uiversity teaching and 
							referal hospital in Nairobi(KUTRH), Kenyatta national hospital, The Agha Khan University Hospital(Nairobi)
						</p>
					</p>
				<a href="payment.php"><button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbutton">Book appointment</button></a>
			</div>
				</div>
		</div>
		</div>
</div>
	<script>
</script>
</body>
</html>
<?php } ?>