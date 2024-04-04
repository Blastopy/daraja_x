<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');


// initializing the API
include_once('../core/initialize.php');


//instatiate post
$post = new Post($conn);

//get raw posted data 
$data = json_decode(file_get_contents("php://input"));

$post->id = $data -> id;

// delete post
if ($post->delete()) {
	echo json_encode(
		array('message'=> 'Post deleted')
	);
}else {
	echo json_encode(
		array('message' => 'Post not deleted')
	);
}
?>