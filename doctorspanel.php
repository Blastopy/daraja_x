<?php session_start();
if (empty($_SESSION['email']) && empty($_COOKIE['fname']) && empty($_COOKIE['sname'])) {
	setcookie('fname', '', time() - 3600);
	setcookie('email', '',time() - 3600);
	setcookie('sname', '', time() - 3600);
	session_destroy();
	header('location:logout.php');
}else {
	include 'schedule.php';
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
if($_SERVER['REQUEST_METHOD'] == "POST"){
		if(isset($_POST['schedule'])){
			echo $_POST['task'];
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
	<link rel="stylesheet" href="includes/styles/dashboard.css" />
	<script src="includes/main.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>Santi Doctor's Dashboard</title>
</head>
<body>
<div class="parent">
	<div class="" id="mySidebar">
	  <button id="closeNav" class="w3-bar-item w3-button w3-large" onclick="w3_close()">&times;</button><br>
	 <ul>
		<l1><h1><a href="doctorspanel.php" style="text-decoration: none;">Santi Health<a></a></h1></l1>
		<li><button onclick="showCalendar()">Your Calendar</button></li>
		<li><button onclick="showDoctors()">Doctors and specialists</button></li>
		<li><button onclick="showCharge()">Charge sheet</button></li>
		<li><button onclick="showTests()">Laboratory services</button></li>
		<li><button onclick="showImages()">X-rays and MRIs</button></li>
		<li><button onclick="showReviews()">Call and ongoing session</button></li>
		<li><button onclick="showUpdates()">Updates</button></li>
		<li><button onclick="showSeminors()">Seminors and education programms</button></li>
		<li><button onclick="pharmacies()">Pharmacy</button></li>
		<li><button onclick="showNurses()">Homecare services</button></li>
</ul>
	</div>
	<div id="main">
	<div id="navbar">
	<button id="openNav" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
	</div>
	<header>
		<img src="includes/images/santi2.png" alt="" width="10%">
		<a href="logout.php">Logout</a>
		<a href="logout.php">Notifications</a>
		<a href=""><b><?php echo $profile ?></b></a>
			<button onclick="darkmode()" class="theme-toggle"></button>
	</header>
		<main id="main_content">
			<div class="page" id="home-page">
			<div class="appointments">
			<div class="box-container"> 
  
  <div class="box box1"> 
	  <div class="text"> 
		  <h2 class="topic-heading">60.5k</h2> 
		  <h2 class="topic">Logged-in patients</h2> 
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
							<th>Patient</th>
							<th>Gender</th>
							<th>Reason</th>
							<th>Bill</th>
						</tr>
						<tr>
							<td>12/06/2024</td>
							<td>Harrison Mogaka</td>
							<td>Male</td>
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
							<th>Patient name</th>
							<th>Doctor</th>
							<th>Test name</th>
							<th>Result</th>
							<th>Bill</th>
						</tr>
						<tr>
							<td>12/06/2024</td>
							<td>Harrison Omollo</td>
							<td>Dr. Haris Mito</td>
							<td>H. pylori</td>
							<td>Verified/pending/not done</td>
							<td>Paid</td>
						</tr>
				</table>
			</div>
			<div class="imaging" id="imaging">
			<h3>Hello <?php echo $fname ?>, no pending image reviews as of now</h3>
			</div>
			<div class="reviews" id="reviews">
			<h3>Hello <?php echo $fname ?>, no calls as of now</h3>
			<center>
			<div class="doctorsliveNode">
				<div class="redDot"></div> Live<br>
				<span class="patientNode">
					<img src="includes/images/avatar.jpeg" alt="avatar.jpg" width="10%" height="10%">
				<p><?php echo $fname ?></p>
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
				<p>Steven Mwaniki</p>
				<img src="includes/images/avatar.jpeg" alt="avatar.jpg" width="10%" height="10%">
			</span>
				<button>Open Session</button>
			</div>
			</center>
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
			<div class="task-schedular">
			<h1>Task Scheduler</h1>
			</div>
			<form method="get" action="doctorspanel.php">
			<div class="task-form">
            <input type="text" id="task" placeholder="Enter task name..." autocomplete="off" name="task">
            <select id="priority">
                <option value="top">Top Priority</option>
                <option value="middle">Middle Priority</option>
                <option value="low">Less Priority</option>
            </select>
            <input type="date" id="deadline">
            <input type="submit" value="Add Task" id="add-task" name="schedule">
        </div>
		</form>
        <div class="task-list" id="task-list">
            <!-- Tasks will be added here dynamically -->
        </div>
		<script>
			
const taskInput = document.getElementById("task");
const priorityInput = document.getElementById("priority");
const deadlineInput = document.getElementById("deadline");
const addTaskButton = document.getElementById("add-task");
const taskList = document.getElementById("task-list");

addTaskButton.addEventListener("click", () => {
	const task = taskInput.value;
	const priority = priorityInput.value;
	const deadline = deadlineInput.value;
	if (task.trim() === "" || deadline === "") {
		alert("Please select an upcoming date for the deadline.")
		return; // Don't add task if task or deadline is empty
	}

	const selectedDate = new Date(deadline);
	const currentDate = new Date();

	if (selectedDate <= currentDate) {
		alert("Please select an upcoming date for the deadline.");
		return; // Don't add task if deadline is not in the future
	}
	const taskItem = document.createElement("div");
	taskItem.classList.add("task");
	taskItem.innerHTML = `
	<p>${task}</p>
	<p>Priority: ${priority}</p>
	<p>Deadline: ${deadline}</p>
	<button class="mark-done" type="submit" name="Delete">Mark Done</button>
`;

	taskList.appendChild(taskItem);

	taskInput.value = "";
	priorityInput.value = "top";
	deadlineInput.value = "";
});

taskList.addEventListener("click", (event) => {
	if (event.target.classList.contains("mark-done")) {
		const taskItem = event.target.parentElement;
		taskItem.style.backgroundColor = "#f2f2f2";
		event.target.disabled = true;
	}
});
		</script>
			</div>
		<div class="doctors-profile" id="doctors-profile">
		<h1>Doctor's profiles</h1>		
		<form class="searchbar">
          <input type="text" name="search" id="searchbox" placeholder="Search for a doctor or specialist" oninput="livesearch()">
		</form>
		<span class="ditch">
			<img src="includes/images/doctor.jpeg" alt="" width="100%">
			<h3>Name: <b>Dr. Mito Harris</b></h3>
			<h3>Specialist: General practirioner</h3>
			<button onclick="document.getElementById('id01').style.display ='block'" style="width:auto">Read more</button>
		</span>
		<div class="modal" id="id01">
				<div class="imgcontainer">
					<span onclick="document.getElementById('id01').style.display = 'none'" class="close" title="close modal">&times;</span>
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
			</div>
				</div>
		</div>
		</div>
</div>
	<script>
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
		
		function showDoctors() {
			const doctorsProfile = document.getElementById('doctors-profile');
			doctorsProfile.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = doctorsProfile;
			document.getElementById('charge-sheet').style.display = 'none';
			document.getElementById('labtests').style.display = 'none';
			document.getElementById('imaging').style.display = 'none';
			document.getElementById('reviews').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
		}
		function showCharge() {
			const chargeSheet = document.getElementById('charge-sheet');
			chargeSheet.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = chargeSheet;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('labtests').style.display = 'none';
			document.getElementById('imaging').style.display = 'none';
			document.getElementById('reviews').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
		}
		function showTests() {
			const labTests = document.getElementById('labtests');
			labTests.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = labTests;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('charge-sheet').style.display = 'none';
			document.getElementById('imaging').style.display = 'none';
			document.getElementById('reviews').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
		}
		function showImages() {
			const images = document.getElementById('imaging');
			images.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = images;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('charge-sheet').style.display = 'none';
			document.getElementById('labtests').style.display = 'none';
			document.getElementById('reviews').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
		}
		function showReviews() {
			const reviews = document.getElementById('reviews');
			reviews.style.display = "block";
			document.getElementById('main_content').style.display = 'none';
			document.getElementById('main_content').cloneNode = reviews;
			document.getElementById('doctors-profile').style.display = 'none';
			document.getElementById('charge-sheet').style.display = 'none';
			document.getElementById('labtests').style.display = 'none';
			document.getElementById('imaging').style.display = 'none';
			document.getElementById('updates').style.display = 'none';
			document.getElementById('nurses').style.display = 'none';
		}
		function showUpdates() {
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
		}
		function showSeminors() {
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
		function showCalendar() {
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