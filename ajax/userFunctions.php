<?php
require('../include/class.database.inc.php');
require('../include/class.session.inc.php');
require('../include/class.user.inc.php');

if(isset($_POST['type']) && $_POST['type'] !==NULL)
{
	$type = $_POST['type'];
}else $type=NULL;
if(isset($_POST['username']) && $_POST['username'] !==NULL)
{
	$username = $_POST['username'];
}else $username=NULL;
if(isset($_POST['password']) && $_POST['password'] !==NULL)
{
	if($type==="signup"){
		$userpassword = $_POST['password'];
		$userpassword=password_hash($userpassword,PASSWORD_DEFAULT);
	}
	else if($type==="signin"){
		$userpassword = $_POST['password'];
	}
}else $password=NULL;
if(isset($_POST['color']) && $_POST['color'] !==NULL)
{
	$color = $_POST['color'];
}else $color=NULL;

$data['username']=$username;
$data['password']=$userpassword;
$data['color']=$color;
$data['type']=$type;

$newUser = new User;

if($type==="signup"){
	$response=$newUser->addNewUser($data);
	echo json_encode($response);
}else if($type==="signin"){
	$response=$newUser->loginUser($data);
	echo json_encode($response);
}else if($type==="logout"){
	$response=$newUser->logoutUser();
	echo json_encode($response);
}