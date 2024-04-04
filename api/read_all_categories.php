<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


// initializing the API
include_once('../core/initialize.php');


//instatiate post
$post = new Post($conn);

// blog post query
$result = $post->read();

// get the row count
$num = $result->rowCount();
if ($num > 0) {
	$post_arr = array();
	$post_arr['data'] = array();

	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$post_item = array(
			'id' => $id,
			'name' => $name,
			'create_at' => $create_at,
		);
		array_push($post_arr['data'], $post_item);
	}
	//convert to JSON and output
	echo json_encode($post_arr);
}else {
	echo json_encode(array('message' => 'No categories found'));
}
?>