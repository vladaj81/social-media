<?php
session_start();

require_once('Facebook/autoload.php');
require_once('Fbclasses/InputValidation.php');
require_once('Fbclasses/FacebookRequests.php');
require_once('Classes/Database.php');
require_once('Fbclasses/TokenChecker.php');

//CHECKING IF FORM WITH CREDENTIALS IS SUBMITTED
if(isset($_POST['appId'])) {

	//SANITIZING ENTERED CREDENTIALS AND STORING THEM IN REGULAR VARIABLES.THE OTHER WAY IS STORING THEM IN SESSION VARIABLES
	$appId  = InputValidation::SanitizeAppId($_POST['appId']);
	$appSecret = InputValidation::SanitizeAppSecret($_POST['appSecret']);
	$appToken = InputValidation::SanitizeAppToken($_POST['appToken']);
	$pageId = InputValidation::SanitizeAppToken($_POST['pageId']);

	//CONNECTING TO DATABASE AND INSERTING FACEBOOK CREDENTIALS
	$database = new Database();
	$database->insertFacebookCredentials($appId, $appSecret, $appToken, $pageId);

	//SET TO SESSION THAT FACEBOOK CREDENTIALS ARE ENTERED
	$_SESSION['checkFbCredentials'] = 'ok';

	//CHECKING IF FACEBOOK ACCESS TOKEN EXPIRES IN LESS THEN 14 DAYS AND SEND ALERT TO USER
	$tokenChecker = new TokenChecker($appToken, '1064158250650390|gDS6TUiVYpUYzY3ykApcPblPp-c');
	echo $tokenChecker->checkExpirationTime();
}


//CHECKING IF THERE IS REQUEST FOR FACEBOOK POSTS
if(isset($_POST['getPosts'])) {

	/*//THERE IS 2 POSSIBILITIES FOR ASIGNING CREDENTIALS TO VARIABLES: FROM SESSION OR FROM DATABASE
	$appId = $_SESSION['appId'];
	$appSecret = $_SESSION['appSecret'];
	$appToken = $_SESSION['appToken'];*/

	//CONNECTING TO DATABASE AND GETTING FACEBOOK CREDENTIALS
	$database = new Database();
	$result = $database->getFacebookCredentials();

	$appId = $result['appId'];
	$appSecret = $result['appSecret'];
	$appToken = $result['appToken'];
	$pageId = $result['pageId'];

	//REQUESTING POST FROM FACEBOOK AND PASSING CREDENTIALS.THEN GENERATING HTML FOR RECEIVED POSTS
	$facebookPosts = new FacebookRequests($appId, $appSecret, $appToken);
	$postHtml = $facebookPosts->getFacebookPosts($pageId);

	//ECHO POSTS TO HTML, OR ERROR IF THERE IS
	echo $postHtml;
}


//CHECKING IF CREATE FACEBOOK POST FORM IS SUBMITED
if(isset($_POST['message'])) {

	/*//THERE IS 2 POSSIBILITIES FOR ASIGNING CREDENTIALS TO VARIABLES: FROM SESSION OR FROM DATABASE
	$appId = $_SESSION['appId'];
	$appSecret = $_SESSION['appSecret'];
	$appToken = $_SESSION['appToken'];*/

	//CONNECTING TO DATABASE AND GETTING FACEBOOK CREDENTIALS
	$database = new Database();
	$result = $database->getFacebookCredentials();

	$appId = $result['appId'];
	$appSecret = $result['appSecret'];
	$appToken = $result['appToken'];
	$pageId = $result['pageId'];

    //CREATING ARRAY WITH POST DETAILS
	$attachment = array(
		'message' => $_POST['message'],
		'title' => $_POST['title'],
        'link' => $_POST['link'],
        'description' => $_POST['description'],
        'picture' => $_POST['picture'],
	);
	
	//CREATING NEW FACEBOOK POST AND GETTING CREATION STATUS
	$createNewPost = new FacebookRequests($appId, $appSecret, $appToken);
	$creationStatus = $createNewPost->createFacebookPost($pageId, $attachment);

	//ECHO SUCCESS OR ERROR MESSAGE TO USER
	echo $creationStatus;
}
?>