<?php 
include 'includes/config.php';
$sql = "SELECT patient_email FROM schedules";
$stmt1 = $conn->prepare($sql);
$stmt1 -> execute();
$result = $stmt1 -> fetchall(PDO::FETCH_COLUMN);
$a = $result;
$q = $_GET["q"];
$hint = "";

if ($q !== ""){
	$q = strtolower($q);
	$len = strlen($q);
	foreach($a as $name) {
		if (stristr($q, substr($name, 0, $len))) {
			if($hint == "") {
				$hint = $name;
			} else {
				$hint .= "<form class='inputLink'><input type='submit' id='patient_email' name='patient_email_select' value=$name></form>";
			}
		}
	}
}
echo $hint === "" ? "<center><span class='inputLinkErr'>Email not found.</span></center>" : $hint;
?>