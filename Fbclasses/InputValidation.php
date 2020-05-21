<?php
//CLASS FOR SANITIZING FORM INPUTS
class InputValidation 
{
	//FUNCTION FOR SANITIZING ENTERED APP-ID
	public static function SanitizeAppId($appId) 
	{
		$appId = filter_var($appId, FILTER_SANITIZE_STRING);
		
	}

	//FUNCTION FOR SANITIZING ENTERED APP-SECRET
	public static function SanitizeAppSecret($appSecret) 
	{
		$appSecret = filter_var($appSecret, FILTER_SANITIZE_STRING);
		return $appSecret;
	}

	//FUNCTION FOR SANITIZING ENTERED ACCESS TOKEN
	public static function SanitizeAppToken($appToken) 
	{
		return $appToken;
	}

	//FUNCTION FOR SANITIZING ENTERED INSTAGRAM ACCOUNT ID
	public static function SanitizeInstagramId($instagramId) 
	{
		$instagramId = filter_var($instagramId, FILTER_SANITIZE_STRING);
		return $instagramId;
	}
}
?>