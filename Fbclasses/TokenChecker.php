<?php

//CLASS FOR CHECKING IS THE FACEBOOOK ACCESS TOKEN VALID
class TokenChecker 
{
	private $fbObject;
	private $tokenDetails;
	private $graphNode;
	private $expireTime;
	private $message;
	private $error;

	//FUNCTION TAKES 2 PARAMETERS: ACCESS TOKEN AND APPLICATION TOKEN
	public function __construct($accessToken, $appToken) 
	{
		//CREATING FACEBOOK OBJECT AND SET PROPERTIES APP-ID AN APP-SECRET FROM REGISTERED FACEBOOK APP ON DEVELOPERS.FACEBOOK.COM
		$this->fbObject = new \Facebook\Facebook([
			'app_id' => '1064158250650390',           //Replace {your-app-id} with your app ID
			'app_secret' => '7aea4eae705cf903a1e2fac62cccbe60',   //Replace {your-app-secret} with your app secret
			'graph_api_version' => 'v6.0',
		]);

		try {
		  	//SENDING GET REQUEST TO FACEBOOK GRAPH API TO GET TOKEN DETAILS AND ASIGNING THEM TO TOKEN DETAILS PROPERTY
			$this->tokenDetails = $this->fbObject->get('/debug_token?input_token='.$accessToken, $appToken);

			$this->graphNode = $this->tokenDetails->getGraphNode();
	
		} catch(\Facebook\Exceptions\FacebookResponseException $e) {
			// RETURNS GRAPH API ERRORS WHEN THEY OCCUR
			$this->error = 'Mistake in checking token. The problem will be fixed soon.';

		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
			// RETURNS SDK ERRORS WHEN VALIDATION FAILS OR OTHER LOCAL ISSUES
			$this->error .= '<p class="error">Facebook SDK returned an error: ' . $e->getMessage() . '</p>';
		}
	}


	//FUNCTION FOR CHECKING TOKEN EXPIRATION TIME AND NOTICING USER IF IT'S SOON
	public function checkExpirationTime() 
	{
		//IF THERE IS SOME ERROR, SEND ALERT TO USER
		if($this->error) {
			return "<script> alert('$this->error'); </script>";
		}

		//CHECKING IF TOKEN IS IN A GOOD FORMAT AND WE HAVE THE EXPIRATION TIME
		if(isset($this->graphNode['data_access_expires_at'])) {

			//GETTING DATA ACCESS EXPIRATION TIME AND ASIGNING IT TO PROPERTY
			$this->expireTime = $this->graphNode['data_access_expires_at'];

			//CREATING CURRENTLY TIMESTAMP
			$date = new DateTime();
			$timestamp = $date->getTimestamp();

			//CALCULATING HOW MUCH TOKEN WILL LAST AND PUT INTO DAY FORMAT
			$tokenRemainTime = intval( ($this->expireTime - $timestamp) / 60 / 60 / 24 ); 

			//CHECKING IF TOKEN EXPIRES IN LESS OF 14 DAYS AND SENDING ALERT TO USER
			if($tokenRemainTime < 140) {

				$this->message = 'Token expires in ' .$tokenRemainTime. ' days. Please contact your support.';
				return "<script> alert('$this->message'); </script>";
			} 

		} else {
			//NOTICING USER THAT TOKEN IS NOT IN A GOOD FORMAT
			$this->message = 'This token is not in a good format. Please update your token.';
			return "<script> alert('$this->message'); </script>";
		}
	}
}
?>