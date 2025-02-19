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
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
<div class="item1"> 
<div class="report-topic-heading"> 
						<h3 class="t-op">S. No</h3> 
						<h3 class="t-op">Agency name.</h3> 
						<h3 class="t-op">Telephone number</h3> 
					</div> 
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
</body>
</html>