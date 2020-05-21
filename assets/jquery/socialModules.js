
//WAITING FOR DOCUMENT TO LOAD
$(document).ready(function(){

	//ONCLICK FUNCTION FOR SHOWING FACEBOOK FORM
	$(document).on('click', '#facebookButton', function(){  
		$('#facebookForm').show();
		$('.facebookButtons').show();
		$('.instagramButtons').hide();
		$('#instagramForm').hide();
		$('#displayFacebookData').show();
		$('#displayInstagramData').hide();
	}); 


	//ONCLICK FUNCTION FOR SHOWING INSTAGRAM FORM AND HIDING OTHERS
	$(document).on('click', '#instagramButton', function(){  

		$('#instagramForm').show();
		$('.instagramButtons').show();
		$('#facebookForm').hide();
		$('.facebookButtons').hide();
		$('#displayInstagramData').show();
		$('#displayFacebookData').hide();
	});


	//ONCLICK FUNCTION FOR SHOWING TWITTER FORM AND HIDING OTHERS
	$(document).on('click', '#twitterButton', function(){  

		alert('Twitter module is in development phase');
		$('#facebookForm').hide();
		$('#instagramForm').hide();
		$('#displayFacebookData').hide();
		$('#displayInstagramData').hide();
	});


	//FUNCTION FOR FACEBOOK FORM VALIDATION
	$('#facebookForm').on("submit", function(event){ 
		event.preventDefault();

		$.ajax({  
			url: "getFacebookModule.php",  
			method: "POST",  
			data: $('#facebookForm').serialize(),  

			success:function(data) {  
				$('#facebookForm')[0].reset();  
				$('#displayFacebookData').html(data);
				$('#facebookForm').hide();
				location.reload();
				$('.facebookButtons').show();
			}  
		});
  	});


	//FUNCTION FOR REQUESTING FACEBOOK POSTS
  	$(document).on('click', '#getFacebookPosts', function(){  

  		var getPosts = "fetch";

		$.ajax({  
			url: "getFacebookModule.php",  
			method: "POST",  
			data: {getPosts : getPosts},  

			success:function(data) {  
				$('#displayFacebookData').show();
				$('#displayInstagramData').hide();
				$('#displayFacebookData').html(data);
			}  
		});
	});


	//FUNCTION FOR CREATING NEW FACEBOOK POST
	$('#facebookPost').on("submit", function(event){ 
		event.preventDefault();

		var podaci = $('#facebookPost').serialize();

		$.ajax({  
			url: "getFacebookModule.php",  
			method: "POST",  
			data: $('#facebookPost').serialize(),  

			success:function(data) {  
				$('#facebookPost')[0].reset();
				$('#postData').modal('hide');  
				$('#displayFacebookData').html(data);
			}  
		});
  	}); 


  	//FUNCTION FOR INSTAGRAM FORM VALIDATION
	$('#instagramForm').on("submit", function(event){ 
		event.preventDefault();

		$.ajax({  
			url: "getInstagramModule.php",  
			method: "POST",  
			data: $('#instagramForm').serialize(),  

			success:function(data) {  
				$('#instagramForm')[0].reset();
				$('#displayInstagramData').html(data);
				$('#instagramForm').hide();
				location.reload();
				$('.instagramButtons').show();
			}  
		});
  	}); 


  	//FUNCTION FOR REQUESTING INSTAGRAM POSTS
  	$(document).on('click', '#getInstagramPosts', function(){  

  		var instaPosts = "fetchPosts";

		$.ajax({  
			url: "getInstagramModule.php",  
			method: "POST",  
			data: {instaPosts : instaPosts},  

			success:function(data) {  
				$('#displayInstagramData').show();
				$('#displayFacebookData').hide();
				$('#displayInstagramData').html(data);
			}  
		});
	});
})