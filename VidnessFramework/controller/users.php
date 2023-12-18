<?php

require_once 'helper/common.php';

class Users extends Controller {
	function __construct() { }
	function index($params = '') {
		$menuItem = 'users';

		if ($_SESSION['userType'] != USER_ADMIN) {
			include "view/index/noaccess.php";
			return;
		}

		$updateUser = new User();
		if ($_GET['action'] == 'admin') {
			$updateUser->setId($_GET['u'])->changeUserType(USER_ADMIN);
		} else if ($_GET['action'] == 'guest') {
			$updateUser->setId($_GET['u'])->changeUserType(USER_GUEST);
		} else if ($_GET['action'] == 'default') {
			$updateUser->setId($_GET['u'])->changeUserType(USER_DEFAULT);
		}

		$user = new User();
		$admins = $user->getAll(USER_ADMIN);
		$guests = $user->getAll(USER_GUEST);
		$defaultUsers = $user->getAll(USER_DEFAULT);

		include "view/user/index.php";
	}

	function login($params = "") {
		$hasError = false;
		if($_POST) {
    		$username = $_POST['email'];
			$password = $_POST['password'];		
			
			$user = new User();
			$user->setEmail($username)
				->setPassword($password)
				->loadByLoginInformation();
			
			if ($user->getId() > 0 && ($user->getUserType() == USER_ADMIN || $user->getUserType() == USER_GUEST)) {
				session_start();
				$_SESSION['id'] = $user->getId();
				$_SESSION['userType'] = $user->getUserType();
				$_SESSION['firstName'] = $user->getFirstname();
				$_SESSION['lastName'] = $user->getLastname();
				$_SESSION['email'] = $user->getEmail();
				header('Location: ' . BASE_URL);
			} else {
				$hasError = true; 
				$errorMessage =  "accountinvalid";
				include 'view/user/login.php';
			}
		}else {
			include 'view/user/login.php';
		}
	}

	function forgotPassword($userId){		
		$hasError = false;
		if ($_POST) {
			if ( filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$user = new User();
				$email = $_POST['email'];
				$user->setEmail($email)->loadByEmail();
				if ($user->getId() > 0) {
					$keyString = 'abcdefghijklmnopqrstuvwxyz0123456789';
					$randomPass = '';
					for ($i = 0; $i < 12; $i++) {
						$randomPass .= substr($keyString, rand(0, strlen($keyString) - 2), 1);
					}

					$user->setPassword($randomPass)->updatePassword();
					
					$subject = "Password reset";
					$emailBody = file_get_contents('view/emails/send_forgot_password.php');
					
					$emailBody = str_replace( '[user_pass]' , $randomPass , $emailBody );

					$headers  = "From: ". DEFAULT_EMAIL_SENDER ."\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
					
					CommonHelper::sendEmail($user->getEmail(), 'Forgot password request', $emailBody , $headers);
					
					$_SESSION['success'] = 'success';

					include 'view/user/forgot.password.php';
				} else {
					$hasError = true;
					include 'view/user/forgot.password.php';
				}
			} else {
				$hasError = true;
				include 'view/user/forgot.password.php';
			}
		} else include 'view/user/forgot.password.php';
	}

	function resetPassword($params = ""){	
		$jsonInput = json_decode(file_get_contents("php://input"));	

		$user = new User();
		$email = $jsonInput->email;
		$user->setEmail($email)->loadByEmail();
		if ($user->getId() > 0) {
			$keyString = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$randomPass = '';
			for ($i = 0; $i < 12; $i++) {
				$randomPass .= substr($keyString, rand(0, strlen($keyString) - 2), 1);
			}

			$user->setPassword($randomPass)->updatePassword();
			
			$subject = "Password reset";
			$emailBody = file_get_contents('view/emails/send_forgot_password.php');
			
			$emailBody = str_replace( '[user_pass]' , $randomPass , $emailBody );

			$headers  = "From: ". DEFAULT_EMAIL_SENDER ."\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
			
			CommonHelper::sendEmail($user->getEmail(), 'Forgot password request', $emailBody , $headers);
		}

		header('Content-Type: application/json');
		echo json_encode(array('message' => 'success'));
	}

	function logout($params = "") {
		include 'view/user/logout.php';
		session_destroy();
	}

	function signup() {
    	include 'view/user/signup.php';
	}

	function signupPostback($params) {
		$jsonInput = json_decode(file_get_contents("php://input"));
		$user = new User();
		$user->setEmail($jsonInput->email)->loadByEmail();

		header('Content-Type: application/json');
		if ($user->getId() > 0) {
			echo json_encode(array('message' => 'invalid_email'));
		} else {
			$user->setPassword(md5($jsonInput->password))
				->setFirstname($jsonInput->firstname)
				->setLastname($jsonInput->lastname)
				->setStatus(1)
				->save();

			// also login the user
			$_SESSION['id'] = $user->getId();
			$_SESSION['firstName'] = $user->getFirstname();
			$_SESSION['lastName'] = $user->getLastname();
			$_SESSION['email'] = $user->getEmail();

			echo json_encode(array('message' => 'success'));
		}
	}

	function requestLogin($params) {
		$user = new User();
		$user->setEmail($_POST['email'])
			->setPassword($_POST['password'])
			->loadByLoginInformation();

		if ($user->getId() > 0) {
			header('Content-Type: application/json');
			echo json_encode(array('message' => 'success', 
				"User" => array(
					'SessionId' => session_id(),   
					'Id' => $user->getId(),
					'Email' =>  $user->getEmail(),
					'Firstname' =>  $user->getFirstname(),
					'Lastname' =>  $user->getLastname()
				)
			));
		}
		else {
			header('Content-Type: application/json');
			echo json_encode(array('message' => 'failure'));
		}
	}

	function requestSignup($params) {
		$user = new User();
		$user->setEmail($_POST['email'])->loadByEmail();

		header('Content-Type: application/json');
		if ($user->getId() > 0) {
			echo json_encode("invalid_email");
		} else {
			$user->setPassword(md5($_POST['password']))
				->setFirstname($_POST['firstName'])
				->setLastname($_POST['lastName'])
				->setStatus(1)
				->save();

			echo json_encode(array('message' => 'success', 
				"User" => array(
					'SessionId' => session_id(),   
					'Id' => $user->getId(),
					'Email' =>  $user->getEmail(),
					'Firstname' =>  $user->getFirstname(),
					'Lastname' =>  $user->getLastname()
				)
			));

			echo json_encode("success");
		}
	}
}