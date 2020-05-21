<?php
session_start();

require_once('Classes/Database.php');

//CHECKING IF FORM FOR UPDATING CREDENTIALS IS SUBMITED
if(isset($_POST['instaCredentials'])) {

	//SANITIZING FORM INPUTS AND ASIGNING THEM TO VARIABLES
	$newAppId2 = filter_var($_POST['newAppId2'], FILTER_SANITIZE_STRING);
	$newAppSecret2 = filter_var($_POST['newAppSecret2'], FILTER_SANITIZE_STRING);
	$newAppToken2 = filter_var($_POST['newAppToken2'], FILTER_SANITIZE_STRING);
	$instagramId2 = filter_var($_POST['instagramId2'], FILTER_SANITIZE_STRING);

	//CONNECTING TO DATABASE AND UPDATING INSTAGRAM CREDENTIALS
	$database = new Database();
	$result = $database->updateInstagramCredentials($newAppId2, $newAppSecret2, $newAppToken2, $instagramId2, 1);
	
	if($result) {
		header('Location: index.php');
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>SM Plugin</title>

	<!--INCLUDING BOOTSTRAP 4 CSS AND JQUERY-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	
	<!--INCLUDING CUSTOM CSS-->
	<link rel="stylesheet" href="assets/css/style.css">
	
</head>
<body>
	<!--SOCIAL MEDIA FORMS CONTAINER-->
	<div class="container facebookContainer">
		<div class="row justify-content-center">
			<div class="col-md-6 col-md-offset-3" align="center">

				<h3 class="space">Enter Your Credentials</h3>

				<!--FORM FOR UPDATING INSTAGRAM CREDENTIALS-->
				<form method="POST" id="instagramForm2">
					<div class="form-group">
						<label for="newAppId2">Facebok App Id</label>
						<input type="password" name="newAppId2" class="form-control" placeholder="Paste here your app-id" required>
					</div>
					<div class="form-group">
						<label for="newAppSecret2">Facebook App Secret</label>
						<input type="password" name="newAppSecret2" class="form-control" placeholder="Paste here your app-secret" required>
					</div>
					<div class="form-group">
						<label for="newAppToken2">Facebook Access Token</label>
						<input type="password" name="newAppToken2" class="form-control" placeholder="Paste here token from your facebook app" required>
					</div>
					<div class="form-group">
						<label for="instagramId2">Instagram Account Id</label>
						<input type="password" name="instagramId2" class="form-control" placeholder="Paste here your instagram account id" required>
					</div>
					<input type="submit" name="instaCredentials" value="Submit" class="btn btn-success">
		        </form>
		    	
			</div>
		</div>
	</div>
	<!--SOCIAL MEDIA FORMS CONTAINER CLOSE-->
</body>
</html>