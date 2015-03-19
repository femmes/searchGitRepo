<?php

class User
{
	private $userid;
	private $username;
	private $userpassword;
	private $usercolor;

	private $pdo;

	public function __construct(){
		// echo 'The class "', __CLASS__, '" was initiated!<br />';
	}

	public function addNewUser($user){
		$this->pdo = new Database;
		$this->username=$user['username'];
		$this->userpassword=$user['password'];
		$this->usercolor=$user['color'];
		$stmt = $this->pdo->dbh->prepare("INSERT INTO users (username,password,color)
								VALUES (:newuser,:newpassword,:newcolor)");
		$stmt->bindParam(':newuser',$this->username,PDO::PARAM_STR);
		$stmt->bindParam(':newpassword',$this->userpassword,PDO::PARAM_STR);
		$stmt->bindParam(':newcolor',$this->usercolor,PDO::PARAM_STR);
		try{
			$stmt->execute();
			$result=$this->pdo->dbh->lastInsertId();
			$newSession = new Session;
			$_SESSION["userid"] = $result;
			$info['status']=1;
			$info['personalMsg']='Welcome To The World Of Tomorrow';
			$info['userid']=$_SESSION["userid"];
			$info['sessionID']=session_id();
			return $info;
		}catch(PDOException $e){
			$info['status']=0;
			$info['code']=$e->getCode();
			$info['msg']=$e->getMessage();
			if($info['code']==23000){
				$info['personalMsg']="Username Already Exist";
			}
			else $info['personalMsg']="Could Not Add New User";
			return $info;
		}
	}

	public function loginUser($user){
		$this->pdo = new Database;
		$this->username=$user['username'];
		$this->userpassword=$user['password'];
		$stmt = $this->pdo->dbh->prepare("SELECT * FROM `users` WHERE `username`=:username");
		$stmt->bindParam(':username',$this->username,PDO::PARAM_STR);
		try{
			$stmt->execute();
			$count=$stmt->rowCount();
			if($count===0 || $count===NULL){
				$info['status']=1;
				$info['personalMsg']="User Does Not Exist";
				$info['count']=$count;
				return $info;
			}
			else{
				$result=$stmt->fetch(PDO::FETCH_ASSOC);
				$hash=$result['password'];
				if(password_verify($this->userpassword, $hash)){
					$newSession = new Session;
					$_SESSION["userid"] = $result['id'];
					$info['status']=1;
					$info['personalMsg']="Welcome Back";
					$info['count']=$count;
					$info['sessionID']=session_id();
					$info['sessionData']=$_SESSION["userid"];
					return $info;
				}else{
					$info['status']=1;
					$info['personalMsg']="Wrong Password";
					$info['count']=$count;
					return $info;
				}
			}
		}
		catch(PDOException $e){
			$info['status']=0;
			$info['code']=$e->getCode();
			$info['msg']=$e->getMessage();
			$info['personalMsg']="Could Not Login User";
			return $info;
		}
	}

	public function logoutUser(){
		session_start();
		$newSession= new Session;
		$_SESSION = array();
		// TODO: verify if sess cookies deleted
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		$response=$newSession->_destroy(session_id());
		return $response;
	}

	public function getUserData($userid){
		$this->pdo = new Database;
		$this->userid = $userid;
		$stmt = $this->pdo->dbh->prepare("SELECT * FROM `users` WHERE `id`=:userid");
		$stmt->bindParam(':userid',$this->userid,PDO::PARAM_STR);
		try{
			$stmt->execute();
			$count=$stmt->rowCount();
			if($count===0 || $count===NULL){
				$info['status']=1;
				$info['personalMsg']="User Data Not Found";
				$info['count']=$count;
				return $info;
			}
			else{
				$result=$stmt->fetch(PDO::FETCH_ASSOC);
				$this->username=$result['username'];
				$this->usercolor=$result['color'];
				$info['status']=1;
				$info['personalMsg']="Data Retrieved!";
				$info['username']=$this->username;
				$info['usercolor']=$this->usercolor;
				$info['count']=$count;
				return $info;
			}
		}
		catch(PDOException $e){
			$info['status']=0;
			$info['code']=$e->getCode();
			$info['msg']=$e->getMessage();
			$info['personalMsg']="Could Not Load User Information";
			return $info;
		}
	}
}