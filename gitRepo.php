<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/mustache.min.js"></script>
<script src="js/index.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/index.css">
</head>
<body>
<div class='bodyContainer'>
	<div class='form'>
		<div class="formContent">
			<div class="topForm">
				<input type='text' name='query' autofocus="autofocus" id='searchQ' class="main" placeholder="SEACH GITHUB REPOSITORIES" autocomplete="off" size="35" style="display:inline-block">
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
								<option value=''></option>
								<option value="">all</option>
								<option value="name">name</option>
								<option value="description">description</option>
								<option value="readme">readme</option>
							</select>
						</div>
						<div class="floatLeft formDiv">
							<div class="floatLeft searchText">Language: </div>
							<select id="language" autocomplete="off">
								<option value=''></option>
								<option value="">all</option>
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
		<div class="resultContainer">
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
// $(document).ready(function(){
// 	var urlRequest='';
// 	var q;
// 	var In;
// 	var language;
// 	var stars;
// 	var queryResult;
// 	var pageNumber=1;
// 	var ready=true;
// 	var totalCount;
// 	var pageLimit;
// 	$('#retrieve').on("click", function(){
// 		$('.resultContainer').empty();
// 		q=$('#searchQ').val();
// 		In=$('#IN').val();
// 		language=$('#language').val();
// 		stars=$('#numberOfStars').val().trim();
// 		$('.form').addClass('slideFormUp');
// 		if(q===''){return 0}
// 		else{
// 			pageNumber=1;
// 			ready=true;
// 			totalCount=0;
// 			pageLimit=0;
// 			urlRequest="https://api.github.com/search/repositories?&q=";
// 			urlRequest=urlRequest+q;
// 			if(In!=='')urlRequest=urlRequest+'+in:'+In;
// 			if(language!=='') urlRequest=urlRequest+'+language:'+language;
// 			if(stars!=='') urlRequest=urlRequest+'+stars:'+stars;
// 			urlRequest=urlRequest+'&per_page=20';
// 			urlRequest=urlRequest+'&page='+pageNumber;
// 			pageNumber++;
// 			$.ajax({
// 				url: urlRequest,
// 				dataType: 'json',
// 				success: function(data)
// 				{
// 					console.log(data);
// 					console.log(data.total_count);
// 					totalCount=data.total_count;
// 					pageLimit=Math.ceil(totalCount/20);
// 					console.log(pageLimit);
// 					//data.items.[x].full_name
// 					$.each(data.items, function(){
// 						queryResult=data.items;
// 					});
// 					console.log(queryResult);
// 					loadResult(queryResult);
// 					resetInputs();
// 				},
// 				error: function(request, status, error){
// 					console.log('ERROR: '+ request +' failed. status: '+status+'. '+ error);
// 				}
// 			});
// 		}
// 	});

// 	function loadResult(dataItems){
// 		$.get('htmlTemplates/resultBlock.mst', function(template){
// 			var rendered=Mustache.render(
// 				template,
// 				{
// 					result: dataItems
// 				}
// 			);
// 			$('.resultContainer').append(rendered);
// 		});
// 	}
	

// 	$('.advance').on("click", "i", function(){
// 		if($('.advanceSearch').hasClass('hide')){
// 			$('.advanceSearch').removeClass('fadeOut');
// 			$('.advanceSearch').removeClass('hide');
// 			$('.advanceSearch').addClass('fadeIn');
// 		}
// 		else{
// 			$('.advanceSearch').removeClass('fadeIn');
// 			$('.advanceSearch').addClass('fadeOut');
// 			setTimeout(function(){ $('.advanceSearch').addClass('hide'); }, 500);
// 		}
// 	});
// 	$('#searchQ').keypress(function (e) {
// 		var key = e.which;
// 		if(key == 13){
// 			$('#retrieve').trigger('click');
// 			return false;  
// 		}
// 	});

// 	function loadMore(){
// 		console.log(urlRequest);
// 		console.log(ready);
// 		console.log(pageNumber);
// 		// $("body").append("<div>");
// 		moreResults();
// 		$(window).bind('scroll', bindScroll);
// 	}

// 	function bindScroll(){
// 		if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
// 			$(window).unbind('scroll');
// 			loadMore();
// 		}
// 	}

// 	$(window).scroll(bindScroll());

// 	function moreResults(){
// 		if(urlRequest==='') return 0;
// 		urlRequest=urlRequest.replace(/&page=.*/,'&page='+pageNumber);
// 		if(ready===true && pageNumber<=pageLimit){
// 			ready=false;
// 			$.ajax({
// 				url: urlRequest,
// 				dataType: 'json',
// 				success: function(data)
// 				{
// 					console.log(data);
// 					$.each(data.items, function(){
// 						queryResult=data.items;
// 					});
// 					console.log(queryResult);
// 					loadResult(queryResult);
// 					pageNumber++;
// 					ready=true;
// 				},
// 				error: function(request, status, error){
// 					console.log('ERROR: '+ request +' failed. status: '+status+'. '+ error);
// 				}
// 			});
// 		}
// 	}
	
// 	function resetInputs(){
// 		$('#IN').val('');
// 		$('#language').val('');
// 		$('#numberOfStars').val('')
// 	}
// 	function resetVariables(){
// 		urlRequest='';
// 		q='';
// 		In='';
// 		language='';
// 		stars='';
// 		queryResult='';
// 		pageNumber=1;
// 		ready=true;
// 		totalCount=0;
// 		pageLimit=0;
// 	}
// });
</script>
</html>