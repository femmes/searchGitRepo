$(document).ready(function(){
	var urlRequest='';
	var q;
	var In;
	var language;
	var stars;
	var queryResult;
	var pageNumber=1;
	var ready=true;
	var totalCount;
	var pageLimit;
	var derp;
	// SUBMIT INITIAL QUERY
	$('#retrieve').on("click", function(){
		$('.resultContainer').empty();
		q=$('#searchQ').val();
		In=$('#IN').val();
		language=$('#language').val();
		stars=$('#numberOfStars').val().trim();
		$('.form').addClass('slideFormUp');
		if(q===''){return 0}
		else{
			pageNumber=1;
			ready=true;
			totalCount=0;
			pageLimit=0;
			urlRequest="https://api.github.com/search/repositories?&q=";
			urlRequest=urlRequest+q;
			if(In!=='')urlRequest=urlRequest+'+in:'+In;
			if(language!=='') urlRequest=urlRequest+'+language:'+language;
			if(stars!=='') urlRequest=urlRequest+'+stars:'+stars;
			urlRequest=urlRequest+'&per_page=20';
			urlRequest=urlRequest+'&page='+pageNumber;
			pageNumber++;
			$.ajax({
				url: urlRequest,
				dataType: 'json',
				success: function(data)
				{
					console.log(data);
					console.log(data.total_count);
					totalCount=data.total_count;
					pageLimit=Math.ceil(totalCount/20);
					// console.log(pageLimit);
					$.each(data.items, function(){
						queryResult=data.items;
					});
					console.log(queryResult);
					$('.totalCount').text("Found "+commaSeparateNumber(totalCount)+" repositories");
					$('.countBlock').show();
					resetInputs();
					if(totalCount===0){
						$('.resultContainer').append('NO RESULTS FOUND');
						return 0;
					}
					loadResult(queryResult);
				},
				error: function(request, status, error){
					console.log('ERROR: '+ request +' failed. status: '+status+'. '+ error);
				}
			});
		}
	});

	//INITIAL LOAD
	function loadResult(dataItems){
		$.get('htmlTemplates/resultBlock.mst', function(template){
			var rendered=Mustache.render(
				template,
				{
					result: dataItems
				}
			);
			$('.resultContainer').append(rendered);
		});
	}

	function moreResults(){
		if(urlRequest==='') return 0;
		urlRequest=urlRequest.replace(/&page=.*/,'&page='+pageNumber);
		if(ready===true && pageNumber<=pageLimit){
			ready=false;
			$.ajax({
				url: urlRequest,
				dataType: 'json',
				success: function(data)
				{
					console.log(data);
					$.each(data.items, function(){
						queryResult=data.items;
					});
					// console.log(queryResult);
					loadResult(queryResult);
					pageNumber++;
					ready=true;
				},
				error: function(request, status, error){
					console.log('ERROR: '+ request +' failed. status: '+status+'. '+ error);
				}
			});
		}
	}

	function loadMore(){
		// debugging
		// console.log(urlRequest);
		// console.log(ready);
		// console.log(pageNumber);
		moreResults();
		$(window).bind('scroll', bindScroll);
	}

	function bindScroll(){
		if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
			$(window).unbind('scroll');
			loadMore();
		}
	}

	//PAGINATION
	$(window).scroll(bindScroll());
	
	function resetInputs(){
		$('#IN').val('');
		$('#language').val('');
		$('#numberOfStars').val('')
	}
	function resetVariables(){
		urlRequest='';
		q='';
		In='';
		language='';
		stars='';
		queryResult='';
		pageNumber=1;
		ready=true;
		totalCount=0;
		pageLimit=0;
	}
	function commaSeparateNumber(val){
		while (/(\d+)(\d{3})/.test(val.toString())){
			val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
		}
		return val;
	}

	// EVENT HANDLING
	$('.advance').on("click", "i", function(){
		if($('.advanceSearch').hasClass('hide')){
			$('.advanceSearch').removeClass('fadeOut');
			$('.advanceSearch').removeClass('hide');
			$('.advanceSearch').addClass('fadeIn');
			$('.queryResults').addClass('moveDown');
		}
		else{
			$('.advanceSearch').removeClass('fadeIn');
			$('.advanceSearch').addClass('fadeOut');
			setTimeout(function(){ $('.advanceSearch').addClass('hide'); $('.queryResults').removeClass('moveDown');}, 500);
		}
	});
	$('#searchQ').keypress(function (e) {
		var key = e.which;
		if(key == 13){
			$('#retrieve').trigger('click');
			return false;  
		}
	});

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