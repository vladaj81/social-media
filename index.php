<?php
session_start();

require_once('Facebook/autoload.php');

//VARIABLES FOR STORING ERRORS AND SESSION INFORMATIONS
$error = '';
$fbCredentials = '';
$instagramCredentials = '';

//CHECKING IF FACEBOOK CREDENTIALS ARE ENTERED
if(isset($_SESSION['checkFbCredentials'])) {
	$fbCredentials = $_SESSION['checkFbCredentials'];
}

//CHECKING IF INSTAGRAM CREDENTIALS ARE ENTERED
if(isset($_SESSION['checkInstaCredentials'])) {
	$instagramCredentials = $_SESSION['checkInstaCredentials'];
}

//CHECKING IF UPDATE FACEBOOK CREDENTIALS BUTTON IS SUBMITED
if(isset($_POST['updateFbCredentials'])) {
	header("Location: facebook.php");
}

//CHECKING IF UPDATE INSTAGRAM CREDENTIALS BUTTON IS SUBMITED
if(isset($_POST['updateInstaCredentials'])) {
	header("Location: instagram.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>SM Plugin</title>

	<!--INCLUDING JQUERY-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 

	<!--INCLUDING BOOTSTRAP 4 CSS AND JAVASCRIPT-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
	
	<!--INCLUDING CUSTOM CSS-->
	<link rel="stylesheet" href="assets/css/style.css">
	
</head>
<body>
	<!--SOCIAL MEDIA FORMS CONTAINER-->
	<div class="container facebookContainer">
		<div class="row justify-content-center">
			<div class="col-md-6 col-md-offset-3" align="center">

				<h3 class="socialButton">Choose Your Social Network</h3>

				<!--BUTTONS FOR SWITCHING SOCIAL MEDIA MODULES-->
				<div class="socialButtons">
					<button class="btn btn-primary" id="facebookButton">Facebook</button>
					<button class="btn btn-primary" id="instagramButton">Instagram</button>
					<button class="btn btn-primary" id="twitterButton">Twitter</button>
				</div>

				<!--IF FACEBOOK CREDENTIALS ENTERED,SHOW FACEBOOK BUTTONS-->
				<?php if($fbCredentials == 'ok') : ?>

					<div class="facebookButtons">
						<button class="btn btn-success" id="getFacebookPosts">Get Facebook posts</button>
						<button class="btn btn-success" id="newFacebookPost" data-toggle="modal"  data-target="#postData">
							Create Facebook post
						</button>
					</div>

				<?php endif; ?>

				<!--IF INSTAGRAM CREDENTIALS ENTERED,SHOW INSTAGRAM BUTTONS-->
				<?php if($instagramCredentials == 'ok') : ?>

					<div class="instagramButtons">
						<button class="btn btn-info" id="getInstagramPosts">Get Instagram posts</button>
					</div>

				<?php endif; ?>

				<!--IF FACEBOOK CREDENTIALS ARE NOT ENTERED,SHOW THE FACEBOOK FORM-->
				<?php if(!$fbCredentials == 'ok') : ?>

					<!--FORM FOR INSERTING FACEBOOK CREDENTIALS-->
					<div>
						<form method="POST" id="facebookForm">
							<div class="form-group">
								<label for="appId">Facebok App Id</label>
								<input type="password" name="appId" class="form-control" placeholder="Paste here your app-id" required>
							</div>
							<div class="form-group">
								<label for="appSecret">Facebook App Secret</label>
								<input type="password" name="appSecret" class="form-control" placeholder="Paste here your app-secret" required>
							</div>
							<div class="form-group">
								<label for="appToken">Facebook Access Token</label>
								<input type="password" name="appToken" class="form-control" placeholder="Paste here token from your facebook app" required>
							</div>
							<div class="form-group">
								<label for="pageId">Facebook Page Id</label>
								<input type="password" name="pageId" class="form-control" placeholder="Paste here your facebook page id" required>
							</div>
								<input type="submit" name="getFacebookData" value="Submit" class="btn btn-success">

							<div id="error"><?php if($error) echo $error; ?></div>
				        </form>
			    	</div>
		    	<?php endif; ?>

		    	<!--IF INSTAGRAM CREDENTIALS ARE NOT ENTERED,SHOW THE INSTAGRAM FORM-->
		    	<?php if(!$instagramCredentials == 'ok') : ?>
			        <!--FORM FOR INSERTING INSTAGRAM CREDENTIALS-->
			        <div>
						<form method="POST" id="instagramForm">
							<div class="form-group">
								<label for="appId2">Facebok App Id</label>
								<input type="password" name="appId2" class="form-control" placeholder="Paste here your app-id" required>
							</div>
							<div class="form-group">
								<label for="appSecret2">Facebook App Secret</label>
								<input type="password" name="appSecret2" class="form-control" placeholder="Paste here your app-secret" required>
							</div>
							<div class="form-group">
								<label for="appToken2">Facebook Access Token</label>
								<input type="password" name="appToken2" class="form-control" placeholder="Paste here token from your facebook app" required>
							</div>
							<div class="form-group">
								<label for="instagramId">Instagram Account Id</label>
								<input type="password" name="instagramId" class="form-control" placeholder="Paste here your instagram account id" required>
							</div>
								<input type="submit" name="getInstagramData" value="Submit" class="btn btn-success">

							<div id="error"><?php if($error) echo $error; ?></div>
				        </form>
				    </div>
			    <?php endif; ?>

			</div>
		</div>

		<!--DIV FOR DISPLAYING FACEBOOK DATA-->
		<div class="row justify-content-center" id="displayFacebookData"></div>

		<!--DIV FOR DISPLAYING INSTAGRAM DATA-->
		<div class="row justify-content-center" id="displayInstagramData"></div>

	</div>
	<!--SOCIAL MEDIA FORMS CONTAINER CLOSE-->


	<!-- MODAL WINDOW FOR ENTERING FACEBOOK POST DETAILS -->
	<div class="modal" id="postData">
		<div class="modal-dialog">
		    <div class="modal-content">

				<!--MODAL HEADER-->
				<div class="modal-header">
					<h4 class="modal-title">Create Facebook Post</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!--MODAL BODY-->
				<div class="modal-body">
					
					<!--FORM FOR ENTERING NEW FACEBOOK POST DETAILS-->
					<form method="POST" id="facebookPost">
						<label>Enter Post text</label> 
						<input type="text" class="form-control" name="message" placeholder="Post text" required/>
						<br /> 

						<label>Enter Post title</label>  
						<input type="text" class="form-control" name="title" placeholder="Post title" required/>
						<br />  

						<label>Enter Post link</label> 
						<input type="url" class="form-control" name="link" placeholder="Link on post click" required/>
						<br />  

						<label>Enter Post description</label> 
						<input type="text" class="form-control" name="description" placeholder="Post description" required/>
						<br />  

						<label>Enter Picture url (only http:\\ and https:\\ formats are valid)</label> 
						<input type="url" class="form-control" name="picture" placeholder="Copy here image address" required/>
						<br /> 

						<input type="submit" name=createPost" id="createPost" value="Create Post" class="btn btn-success" />   
					</form>

				</div>

				<!--MODAL FOOTER-->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>

		    </div>
		</div>
	</div>

	<!--INCLUDING SCRIPT WITH SOCIAL MODULES FUNCIONALITIES-->
	<script src="assets/jquery/socialModules.js"></script>
</body>
</html>