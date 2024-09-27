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
	<center>
	<img src="includes/images/santi2.png" width="30%">
	</center>
	<p>
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
	<center>
		<form method="post" action="about.php">
			<p>
				For any inquiries, comments or complains please call/text on +254(0)710444964 or leave a a letter to us on the textarea below
				<br>
				<label for="email">Your email address:</label><br>
				<input type="email" placeholder="name@example.com" required name="customermail"><br>
				<label for="comment">Feedback:</label><br>
				<input type="textarea" required width="450px" name="feedback" height="450px" placeholder="Feedback goes here">
				<br>
				<input type="submit" value="Submit" name="submit">
			</p>
		</form>
	</center>
</body>
</html>