var menuicn = document.querySelector(".menuicn");
var nav = document.querySelector(".navcontainer");

menuicn.addEventListener("click", () => {
    nav.classList.toggle("navclose");
});

var online = document.getElementById('main_content').innerHTML;
if (online.navigator.onLine == 'false'){
	document.getElementById('offline').innerHTML = "Seems you're offline😒";
}
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
function showpsd() {
	var x = document.getElementById("psw");
	if (x.type === "password") {
		x.type = "text";
	} else {
		x.type = "password";
	}
}

var modal = document.getElementById('id01');
window.onclick = function(event) {
	if (event.target == modal) {
		modal.style.display = "none";
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

var cookies = document.cookie;

// Function to get a specific cookie by name
function getCookie(name) {
    var cookieArr = document.cookie.split(";");

    for(let i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");

        if(name == cookiePair[0].trim()) {
            return decodeURIComponent(cookiePair[1]);
        }
    }
    return null;
}
var buttons = getCookie("button");
window.onload = function() {
	if(buttons == 'doctors'){
		showDoctors();
	}else if(buttons == 'dashboard'){
		Dashboard();
	}else if(buttons == 'reports'){
		reports();
	}else if (buttons == 'sessions') {
		sessions();
	}else if (buttons == 'schedules') {
		schedules();
	}else if (buttons == 'payment') {
	payment();
}
}
function showDoctors() {
	const doctors = document.getElementById('doctors-profile');
	doctors.style.display = "block";
	document.getElementById('main_content').style.display = 'none';
	document.getElementById('main_content').cloneNode = doctors;
	document.getElementById('reports').style.display = 'none';
	document.getElementById('sessions').style.display = 'none';
	document.getElementById('schedules').style.display = 'none';
	document.getElementById('labreports').style.display = 'none';
	document.getElementById('payment').style.display = 'none';
	document.cookie = "button=doctors";
}
function Dashboard() {
	const dashboard = document.getElementById('main_content');
	dashboard.style.display = "block";
	document.getElementById('doctors-profile').style.display = 'none';
	document.getElementById('main_content').style.cloneNode = dashboard;
	document.getElementById('reports').style.display = 'none';
	document.getElementById('sessions').style.display = 'none';
	document.getElementById('schedules').style.display = 'none';
	document.getElementById('labreports').style.display = 'none';
	document.getElementById('payment').style.display = 'none';
	document.cookie = "button=dashboard";

}

function reports() {
	const reports = document.getElementById('reports');
	reports.style.display = "block";
	document.getElementById('main_content').style.display = 'none';
	document.getElementById('main_content').cloneNode = reports;
	document.getElementById('sessions').style.display = 'none';
	document.getElementById('doctors-profile').style.display = 'none';
	document.getElementById('schedules').style.display = 'none';
	document.getElementById('labreports').style.display = 'none';
	document.getElementById('payment').style.display = 'none';
	document.cookie = "button=reports";

}
function sessions() {
	const sessions = document.getElementById('sessions');
	sessions.style.display = "block";
	document.getElementById('main_content').style.display = 'none';
	document.getElementById('main_content').style.cloneNode = sessions;
	document.getElementById('reports').style.display = 'none';
	document.getElementById('main_content').style.display = 'none';
	document.getElementById('doctors-profile').style.display = 'none';
	document.getElementById('schedules').style.display = 'none';
	document.getElementById('labreports').style.display = 'none';
	document.getElementById('payment').style.display = 'none';
	document.cookie = "button=sessions";
}
function schedules() {
	const schedules = document.getElementById('schedules');
	schedules.style.display = "block";
	document.getElementById('main_content').style.display = 'none';
	document.getElementById('main_content').style.cloneNode = schedules;
	document.getElementById('doctors-profile').style.display = 'none';
	document.getElementById('reports').style.display = 'none';
	document.getElementById('sessions').style.display = 'none';
	document.getElementById('labreports').style.display = 'none';
	document.getElementById('payment').style.display = 'none';
	document.cookie = "button=schedules";
}
function labreports() {
	const labreports = document.getElementById('labreports');
	labreports.style.display = "block";
	document.getElementById('main_content').style.display = 'none';
	document.getElementById('main_content').style.cloneNode = labreports;
	document.getElementById('doctors-profile').style.display = 'none';
	document.getElementById('reports').style.display = 'none';
	document.getElementById('sessions').style.display = 'none';
	document.getElementById('schedules').style.display = 'none';
	document.getElementById('payment').style.display = 'none';
	document.cookie = "button=labreports";

}
function payment() {
	const payment = document.getElementById('payment');
	payment.style.display = "block";
	document.getElementById('main_content').style.display = 'none';
	document.getElementById('main_content').style.cloneNode = payment;
	document.getElementById('doctors-profile').style.display = 'none';
	document.getElementById('reports').style.display = 'none';
	document.getElementById('labreports').style.display = 'none';
	document.getElementById('sessions').style.display = 'none';
	document.getElementById('schedules').style.display = 'none';
	document.cookie = "button=payment";
}
function openForm() {
	document.getElementById('myForm').style.display = 'block';
}
function closeForm() {
	document.getElementById('myForm').style.display = 'none';

}

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

var close = document.getElementsByClassName('closebtn');
var i;

for (i=0;i<close.length;i++){
	close[i].onclick = function(){
		var div = this.parentElement;
		div.style.opacity = '0';
		setTimeout(function(){ div.style.display = "none"}, 600);
	}
}

function searchFunction() {
	var input, filter, table, tr, td, i, txtValue;
	input = document.getElementById('textinput');
	filter = input.value;
	table = document.getElementById('santiTable');
	tr = table.getElementsByTagName('tr');

	for(i=0;i<tr.length;i++){
		td = tr[i].getElementsByTagName('td')[4];
		if (td) {
			txtValue = td.textContent || td.innerText;
			if (txtValue.indexOf(filter) > -1){
				tr[i].style.display = '';
			} else {
				tr[i].style.display = 'none';
			}
		}
	}
}
filterSelection('all')
			function filterSelection(c) {
				var x, i;
				x = document.getElementsByClassName('ditching');
				if (c == 'all') c = "";
				for (i=0;i < x.length;i++){
					w3RemoveClass(x[i], "show");
					if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
				}
			}
			function w3AddClass(element, name){
				var i, arr1, arr2;
				arr1 = element.className.split(" ");
				arr2 = name.split(" ");
				for (i = 0; i < arr2.length; i++){
					if (arr1.indexOf(arr2[i]) == -1){
						element.className += " " + arr2[i];
					}
				}
			}
			function w3RemoveClass(element, name){
				var i, arr1, arr2;
				arr1 = element.className.split(" ");
				arr2 = name.split(" ");
				for (i = 0; i < arr2.length;i++){
					while (arr1.indexOf(arr2[i]) > -1) {
						arr1.splice(arr1.indexOf(arr2[i]), 1);
					}
				}
				element.className = arr1.join(" ");
			}
			var btnContainer = document.getElementById("filter");
			var btns = btnContainer.getElementsByClassName("filterbtn");
			for (var i =0; i < btns.length; i ++){
				btns[i].addEventListener("click", function(){
					var current = document.getElementsByClassName("active");
					current[0].className = current[0].className.replace("active", "");
					this.className += " active";
				});
}