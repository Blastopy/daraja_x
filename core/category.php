<?php
class Category{
	// db stuff
	private $conn;
	private $table = 'categories';

	// category properties
	public $id;
	public $name;
	public $create_at;

	// constructor with db connection
	public function __construct($conn)
	{
		$this->conn = $conn;
		}

	// getting posts frm the database
	public function read(){
		// create query
		$query = 'SELECT
		*
		FROM'
		. $this->table;

		//prepare stmt
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		return $stmt;
	}
}