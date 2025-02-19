<?php
$emailErr = $textErr = '';
function test_inputs($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if (isset($_POST['submit'])){
		if(empty($_POST['custormermail'])){
			$emailErr = "Email can't be empty";
		}elseif(!empty($_POST['customermail'])) {
			$feedbackmail = filter_input(INPUT_POST, 'customermail', FILTER_SANITIZE_EMAIL);
			$feedbackmail = test_inputs($_POST['customermail']);
		}
	}
	if (isset($_POST['submit'])){
		if(empty($_POST['feedback'])){
			$textErr = "Feedback can't be empty";
		}elseif(!empty($_POST('feedback'))){
			$feedback = test_inputs($_POST['feedback']);
			$feedback = filter_input(INPUT_POST, 'feedback', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="">
	<link rel="icon" type="images/x-icon" href="includes/images/santi2.png">
	<link rel="stylesheet" type="text/css" href="includes/styles/index.css">
	<link rel="stylesheet" type="text/css" href="includes/styles/responsive_index.css">
	<title>Santi health About</title>
	<style type="text/css">
		* {
			box-sizing: border-box;
		}
		body {
			padding: 0;
			margin: 0;
		}
		p {
			display: inline-block;
			position: relative;
			font-size: 18px;
			font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
			padding: 50px;
			text-align: center;
		}
	</style>
</head>
<body>
	<div class="icon2">
		<img src="includes/images/santi23.png" width="90%">
	</div>
		<div class="slideshow-background" id="slideshow">
			<div class="slide" style="background-image: url('uploads/index4.jpg');"></div>
			<div class="slide" style="background-image: url('uploads/index12.jpg');"></div>
			<div class="slide" style="background-image: url('uploads/index16.jpg');"></div>
			<div class="slide" style="background-image: url('uploads/index18.jpg');"></div>
		</div>
<script>
	let currentSlide = 0;
const slides = document.querySelectorAll(".slide");
const intervalTime = 5000; // 5 seconds

function showNextSlide() {
  slides[currentSlide].classList.remove("active");
  currentSlide = (currentSlide + 1) % slides.length;
  slides[currentSlide].classList.add("active");
}

// Set the initial active slide
slides[currentSlide].classList.add("active");

// Change slide every 5 seconds
setInterval(showNextSlide, intervalTime);
</script>
	<div class="content">
	<p class="par" style="color:black">
		Following the covid-19 pandemic, social distancing became a norm to reduce spread of air-borne infections and regulate
		the number of infections amongst individuals. 
		Three gentlemen in the medical field saw it best that people would get medication and meet with medical proffessionals
		from different fields to ensure they are safe at the comfort of their homes, offices or on transit. Wheather it's a 
		consultation, an urgent prescription, a sample collection for labtest or maybe its a home care for a loved one. We have
		joined efforts and brought together proffessionals from different medical fields with various experience to ensure you get 
		quality healthcare at an affordable price.The commitment from our technical team ensures that services are provided of high
		standard and of good quality that satisfies the needs of our patients across the board. Being a registered organization we 
		abide by the regulations as stipulated by the governing board and the Kenya Medical practitioners and Dentist Union (KMPDU).
		We ensure all our medical staff have their renewed licence and liase with the NCK and PPB to ensure the license of all our staff 
		are legitimate.
	</p>
		<div class="form2">
			<center>
		<form method="post" action="about.php">
			<p>
				For any inquiries, comments or complains please call/text on +254(0)710444964 or leave a a letter to us on the textarea below
				<br>
				<label for="email">Email address:</label>
				<input type="email" placeholder="name@example.com" required name="customermail"><br>
				<label for="comment">Feedback:</label>
				<input type="textarea" required  name="feedback" rows="5" cols="30" placeholder="Feedback goes here">
				<br>
				<button type="submit" value="Submit" name="submit" class="btnn">Submit</button>
			</p>
		</form>
			</center>
		</div>
	</div>
</body>
</html>