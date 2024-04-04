<?php
// Handle all the requests from the users
class santidata{
	// Fn to check weather the db is connected
	public function connectionStatus(){
		include('includes/config.php');
		
	}
}
// $result = new santidata();
// $result->connectionStatus();
print_r($_SERVER);
?>