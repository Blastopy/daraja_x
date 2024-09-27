<?php 
include 'includes/config.php';
$sql = "SELECT * FROM schedules";
$stmt1 = $conn->prepare($sql);
$stmt1 -> execute();
$result = $stmt1 -> fetch(PDO::FETCH_ASSOC);
$a = $result;
$q = $_GET["q"];
$hint = "";

// Lokup all hints from array if $q is different form
if ($q !== ""){
	$q = strtolower($q);
	$len = strlen($q);
	foreach($a as $name) {
		if (stristr($q, substr($name, 0, $len))) {
			if($hint == "") {
				$hint = $name;
			} else {
				$hint .= ", $name";
			}
		}
	}
}
echo $hint === "" ? "no search" : $hint;
?>