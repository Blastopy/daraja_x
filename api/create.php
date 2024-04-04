<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');


// initializing the API
include_once('../core/initialize.php');


//instatiate post
$post = new Post($conn);

//get raw posted data 
$data = json_decode(file_get_contents("php://input"));

$post->title = $data -> title;
$post->body = $data -> body;
$post->author = $data -> author;
$post->category_id = $data -> category_id;


// create post
if ($post->create()) {
	echo json_encode(
		array('message'=> 'Post created')
	);
}else {
	echo json_encode(
		array('message' => 'Post not created')
	);
}
?>