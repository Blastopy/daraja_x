
function w3_open() {
	document.getElementById("main").style.marginLeft = "21%";
	document.getElementById("mySidebar").style.width = "20%";
	document.getElementById("main").style.transition = "0.5s";
	document.getElementById("mySidebar").style.display = "block";
	document.getElementById("openNav").style.display = 'none';
}
function w3_close() {
	document.getElementById("main").style.marginLeft = "0%";
	document.getElementById("mySidebar").style.display = "none";
	document.getElementById("openNav").style.display = "inline-block";
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
// toggel theme
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
document.getElementById('updates').style.display = 'none';
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
document.getElementById('seminors').style.display = 'none';
document.getElementById('updates').style.display = 'none';
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
document.getElementById('updates').style.display = 'none';
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
document.getElementById('updates').style.display = 'none';
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
document.getElementById('seminors').style.display = 'none';
document.getElementById('pharmacy').style.display = 'none';
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
function showNurses() {
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
// Implementing the task schedular