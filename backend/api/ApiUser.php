<?php
	require_once '../database/DbUserOperation.php';

	$response = array();
	
	if(isset($_GET['apicall']))
	{

		switch ($_GET['apicall']) {
			case 'login':
				login();
				break;
			
			case 'signup':
				signup();
				break;
			
			default:
				$response['success'] = false; 
				$response['message'] = 'Invalid API Call';

				echo json_encode($response);
				break;
		}
	}

	else {
		$response['success'] = false; 
		$response['message'] = 'Invalid API Call';

		echo json_encode($response);
	}

	function login(){
		$db = new DbUserOperation();

		$username = $_POST['username'];
		$password = $_POST['password'];

		$users = $db->getUserByUsername($username);

		if(count($users)){
			$user = $users[0];
			if($user['password'] == md5($password)){
				$response['success'] = true;
				$response['message'] = 'Login Success';
				$response['data'] = $user;
			}
			else {
				$response['success'] = false;
				$response['message'] = 'Login Failed! Incorrect Password.';
			}
		}
		else {
			$response['success'] = false;
			$response['message'] = 'Login Failed! User Not Found.';
		}

		echo json_encode($response);
	}

	function signup(){
		$db = new DbUserOperation();

		$username = $_POST['username'];
		$password = $_POST['password'];
		$nama = explode('@', $username)[0];

		if($db->setUser($username, md5($password), $nama)){
			$response['success'] = true;
			$response['message'] = 'Signup Success';
			$response['data'] = array(
									'email' => $username,
									'password' => $password,
									'name' => $nama
								);
		}
		else {
			$response['success'] = false;
			$response['message'] = 'Signup Failed!';
		}

		echo json_encode($response);
	}
?>