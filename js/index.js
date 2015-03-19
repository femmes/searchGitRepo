$(document).ready(function(){
	$('#submitForm').on("click", function(){
		if($('#username').val()==='' ||
			$('#password').val()===''){
			return false;
		}
		var username=$('#username').val();
		var password=$('#password').val();
		var color=$('#colorValue').val();
		var whichone;
		if($('#signUp').hasClass('active')){
			whichone="signup";
		}
		else{
			whichone="signin";
		}
		$.ajax({
			url: '../ajax/userFunctions.php',
			dataType: 'json',
			type: 'post',
			data: {
				username: username,
				password: password,
				color: color,
				type: whichone
			},
			success: function(data)
			{
				if(data.status===0){
					if(whichone==="signin"){
						console.log('Failed login: '+data.personalMsg);
					}
					else{
						console.log('Failed Signup: '+data.personalMsg);
						$('.usernameError').removeClass('hide');
						$('.passwordError').addClass('hide');
						$('.usernameErrorTxt').text(data.personalMsg);
					}
				}
				else{
					console.log(data.personalMsg);
					if(data.personalMsg==="User Does Not Exist"){
						$('.usernameError').removeClass('hide');
						$('.passwordError').addClass('hide');
						$('.usernameErrorTxt').text(data.personalMsg);
					}else if(data.personalMsg==="Wrong Password"){
						$('.passwordError').removeClass('hide');
						$('.usernameError').addClass('hide');
						$('.passwordErrorTxt').text(data.personalMsg);
					}else if(data.personalMsg==="Welcome Back"){
						alert('going in');
						window.location.replace("http://54.148.32.230/gitRepo.php");
					}
				}
			},
			error: function(request, status, error){
				console.log('ERROR: '+ request +' failed. status: '+status+'. '+ error);
			}
		});
	});
	$('#signUp').on("click", function(){
		$('#signIn').removeClass('active');
		$('#signUp').addClass('active');
		$('#userForm').removeClass('hide');
		$('#pickColor').removeClass('hide');
		$('.usernameError').addClass('hide');
		$('.passwordError').addClass('hide');
	});
	$('#signIn').on("click", function(){
		$('#signUp').removeClass('active');
		$('#signIn').addClass('active');
		$('#pickColor').addClass('hide');
		$('#userForm').removeClass('hide');
	});
});