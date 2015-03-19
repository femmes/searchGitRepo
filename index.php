<?php
// echo php_ini_loaded_file();
require('include/class.database.inc.php');
require('include/class.session.inc.php');
require('include/class.user.inc.php');

session_start();
$newsess = new Session;
$derp = $newsess->_read(session_id());
// echo "<pre>";
// print_r($derp);
// echo "</pre>";
session_decode($derp['data']);
// print_r($_SESSION);

if(isset($_SESSION['userid'])){ //if login in session is not set
    header("Location: http://54.148.32.230/gitRepo.php");
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/index.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/index.js"></script>
</head>
<body>
	Welcome to the world of tomorrow!
	<br>
	<button id="signUp">Sign up</button>
	<button id="signIn">Sign in</button>
	<form id="userForm" class="hide">
		<div class="usernameContainer">
			<div class="usernameTxt">
				USERNAME:
			</div>
		</div>
		<div class="usernameInput">
			<input autocomplete="off" type="text" name="username" id="username">
		</div>
		<div class="usernameError hide">
			<div class="usernameErrorTxt red"></div>
		</div>
		<br class="clearFloats">
		<div class="passwordContainer">
			<div class="passwordTxt">
				PASSWORD:
			</div>
		</div>
		<div class="passwordInput">
			<input autocomplete="off" type="password" name="password" id="password">
		</div>
		<div class="passwordError hide">
			<div class="passwordErrorTxt red"></div>
		</div>
		<br class="clearFloats">
		<div id="pickColor" class="hide">
			COLOR: 
				<select id="colorValue" autocomplete="off">
					<option value=""></option>
					<option value="orange">Orange</option>
					<option value="purple">Purple</option>
					<option value="red">Red</option>
				</select>
		</div>
		<button type="submit" form="newUser" value="Log Out" id="submitForm">Submit</button>
	</form>
</body>
</html>