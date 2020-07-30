<?php
require_once '../Constant.php';

class DbConnection{
	private $con;

	function __construct(){

	}

	function connect(){
		$this->con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if(mysqli_connect_errno()){
			echo "Failed to connect to database : " . mysqli_connect_errno();
		}

		return $this->con;
	}
}

?>