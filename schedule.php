<?php
include 'includes/config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if (isset($_POST['schedule'])){
		if (!empty($_POST['task'])){
			$task = $_POST['task'];
			echo $task;
		}
	}
}
?>