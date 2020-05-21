<?php
session_start();

require_once('Facebook/autoload.php');
require_once('Fbclasses/InputValidation.php');
require_once('Instagram/InstagramRequests.php');
require_once('Classes/Database.php');
require_once('Fbclasses/TokenChecker.php');

//CHECKING IF FORM WITH CREDENTIALS IS SUBMITTED
if(isset($_POST['appId2'])) {

	//SANITIZING ENTERED CREDENTIALS AND STORING THEM IN REGULAR VARIABLES.THE OTHER WAY IS STORING THEM IN SESSION VARIABLES
	$appId2 = InputValidation::SanitizeAppId($_POST['appId2']);
	$appSecret2  = InputValidation::SanitizeAppSecret($_POST['appSecret2']);
	$appToken2  = InputValidation::SanitizeAppToken($_POST['appToken2']);
	$instagramId  = InputValidation::SanitizeInstagramId($_POST['instagramId']);

	//CONNECTING TO DATABASE AND INSERTING INSTAGRAM CREDENTIALS
	$database = new Database();
	$database->insertInstagramCredentials($appId2, $appSecret2, $appToken2, $instagramId);

	//SET TO SESSION THAT CREDENTIALS ARE ENTERED
	$_SESSION['checkInstaCredentials'] = 'ok';

	//CHECKING IF INSTAGRAM ACCESS TOKEN EXPIRES IN LESS THEN 14 DAYS AND SEND ALERT TO USER
	$tokenChecker = new TokenChecker($appToken2, '1064158250650390|gDS6TUiVYpUYzY3ykApcPblPp-c');
	echo $tokenChecker->checkExpirationTime();
}


//CHECKING IF THERE IS REQUEST FOR INSTAGRAM POSTS
if(isset($_POST['instaPosts'])) {

	/*//THERE IS 2 POSSIBILITIES FOR ASIGNING CREDENTIALS TO VARIABLES: FROM SESSION OR FROM DATABASE
	$appId = $_SESSION['appId2'];
	$appSecret = $_SESSION['appSecret2'];
	$appToken = $_SESSION['appToken2'];
	$instagramId = $_SESSION['instagramId'];*/

	//CONNECTING TO DATABASE AND GETTING INSTAGRAM CREDENTIALS
	$database = new Database();
	$result = $database->getInstagramCredentials();

	$appId = $result['appId'];
	$appSecret = $result['appSecret'];
	$appToken = $result['appToken'];
	$instagramId= $result['instagramId'];

	//REQUESTING POSTS FROM INSTAGRAM AND PASSING CREDENTIALS.THEN GENERATING HTML FOR RECEIVED POSTS
	$instagramPosts = new InstagramRequests($appId, $appSecret, $appToken, $instagramId);
	$postHtml = $instagramPosts->getInstagramPosts();

	//ECHO POSTS TO HTML, OR ERROR IF THERE IS
	echo $postHtml;
}
?>