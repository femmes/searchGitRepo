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
					console.log(pageLimit);
					$.each(data.items, function(){
						queryResult=data.items;
					});
					console.log(queryResult);
					loadResult(queryResult);
					resetInputs();
				},
				error: function(request, status, error){
					console.log('ERROR: '+ request +' failed. status: '+status+'. '+ error);
				}
			});
		}
	});

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
	

	$('.advance').on("click", "i", function(){
		if($('.advanceSearch').hasClass('hide')){
			$('.advanceSearch').removeClass('fadeOut');
			$('.advanceSearch').removeClass('hide');
			$('.advanceSearch').addClass('fadeIn');
		}
		else{
			$('.advanceSearch').removeClass('fadeIn');
			$('.advanceSearch').addClass('fadeOut');
			setTimeout(function(){ $('.advanceSearch').addClass('hide'); }, 500);
		}
	});
	$('#searchQ').keypress(function (e) {
		var key = e.which;
		if(key == 13){
			$('#retrieve').trigger('click');
			return false;  
		}
	});

	function loadMore(){
		console.log(urlRequest);
		console.log(ready);
		console.log(pageNumber);
		// $("body").append("<div>");
		moreResults();
		$(window).bind('scroll', bindScroll);
	}

	function bindScroll(){
		if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
			$(window).unbind('scroll');
			loadMore();
		}
	}

	$(window).scroll(bindScroll());

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
					console.log(queryResult);
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
});