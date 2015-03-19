$(document).ready(function(){
	$('#logoutUser').on("click", function(){
		var whichone="logout";
		$.ajax({
			url: '../ajax/userFunctions.php',
			dataType: 'json',
			type: 'post',
			data: {
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
					}
				}
				else{
					console.log(data.personalMsg);
					alert('going out');
					window.location.replace("http://54.148.32.230");
				}
			},
			error: function(request, status, error){
				console.log('ERROR: '+ request +' failed. status: '+status+'. '+ error);
			}
		});
	});	
});