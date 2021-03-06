<?php
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

if(!isset($_SESSION['userid'])){ //if login in session is not set
    header("Location: http://54.148.32.230");
}

$newUser = new User;
$userInfo=$newUser->getUserData($_SESSION['userid']);

// echo json_encode($userInfo);
$username=$userInfo['username'];
$usercolor=$userInfo['usercolor'];
?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/mustache.min.js"></script>
<script src="js/gitRepo.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/gitRepo.css">
</head>
<body>
<div class='bodyContainer'>
	<div class="countBlock hide">
		<div class="totalCount"></div>
	</div>
	<div class="logoutBlock">
		<div class="logoutTxt">
			<div id="logoutUser">
				logout
			</div>
		</div>
	</div>
	<div class='form'>
		<div class="formContent">
			<div class="topForm">
				<input type='text' name='query' autofocus="autofocus" id='searchQ' class="queryPlaceholder <?php echo $usercolor?>" placeholder="SEACH GITHUB REPOSITORIES" autocomplete="off" size="35" style="display:inline-block">
				<div class="icons">
					<div class="pointer advance"><i class="fa fa-angle-down"></i></div><div class="pointer"><i class="fa fa-arrow-right" id="retrieve"></i></div>
				</div>
			</div>
			<div class="advanceSearch animated hide">
				<div class="advanceSearchContent">
					<div class="advanceHeader">ADVANCED SEARCH</div>
					<div>
						<div class="floatLeft formDiv">
							<div class="floatLeft searchText">In: </div>
							<select id="IN" autocomplete="off">
								<option value="" selected="selected">all</option>
								<option value="name">name</option>
								<option value="description">description</option>
								<option value="readme">readme</option>
							</select>
						</div>
						<div class="floatLeft formDiv">
							<div class="floatLeft searchText">Language: </div>
							<select id="language" autocomplete="off">
								<option value="" selected="selected">all</option>
								<option value="PHP">PHP</option>
								<option value="JavaScript">JavaScript</option>
								<option value="CSS">CSS</option>
							</select>
						</div>
						<div class="formDiv floatLeft">
							<div class="floatLeft searchText"># of stars: </div>
							<input class="floatLeft" type="text" name="numStars" id="numberOfStars" placeholder=" 0.. 100,200,>1000" autocomplete="off" size="14">
						</div>
						<div class="clearfloats"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="queryResults">
		<div class="resultContainer <?php echo $usercolor?>">
		</div>
	</div>
</div>
<script type="text/javascript">
	var usercolor=<?php echo json_encode($usercolor);?>;
	$(".icons i").mouseenter(function(){
		$(this).addClass(usercolor);
	}).mouseleave(function() {
		$(this).removeClass(usercolor);
	});
</script>
</body>
</html>