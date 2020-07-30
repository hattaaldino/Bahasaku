<?php

require_once "DbConnection.php";

class DbUserOperation{
	private $con;

	function __construct(){
		$dbcon = new DbConnection();
		$this->con = $dbcon->connect();
	}

	function setUser($email, $password, $name){
		$query = $this->con->prepare("INSERT INTO user (email, password, nama) VALUES (?, ?, ?)");
		$query->bind_param("sss", $email, $password, $name);
		if($query->execute())
			return true; 
		return false;
	}

	function getUser($email, $password){
		$query = $this->con->prepare("SELECT email, nama FROM user WHERE email = ? AND password = ?");
		$query->bind_param("ss", $email, $password);
		$query->execute();
		$query->bind_result($getemail, $nama);

		$users = array();
		
		while($query->fetch()){
			$user = array();
			$user['email'] = $getemail;
			$user['name'] = $nama;

			array_push($users, $user);
		}

		return $users;

	}

	function getUserByUsername($email){
		$query = $this->con->prepare("SELECT * FROM user WHERE email = ?");
		$query->bind_param("s", $email);
		$query->execute();
		$query->bind_result($getemail, $password, $nama);

		$users = array();
		
		while($query->fetch()){
			$user = array();
			$user['email'] = $getemail;
			$user['password'] = $password;
			$user['name'] = $nama;

			array_push($users, $user);
		}

		return $users;
	}

	function getAllUser(){
		$query = $this->con->prepare("SELECT * FROM user");
		$query->execute();
		$query->bind_result($getemail, $password, $nama);

		$users = array();
		
		while($query->fetch()){
			$user = array();
			$user['email'] = $getemail;
			$user['password'] = $password;
			$user['name'] = $nama;

			array_push($users, $user);
		}

		return $users;
	}

	function updateUser($email, $password, $name){
		$query = $this->con->prepare("UPDATE user SET email = ?, password = ?, nama = ? WHERE email = ?");
		$query->bind_param("ssss", $email, $password, $name ,$email);
		if($query->execute())
			return true; 
		return false; 
	}

	function deleteUser($email){
		$query = $this->con->prepare("DELETE FROM user WHERE email = ?");
		$query->bind_param("s", $email);
		if($query->execute())
			return true; 
		return false; 
	}
}
?>