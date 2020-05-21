<?php
//CLASS FOR INSERTING, UPDATING AND GETTING DATA FROM DATABASE
class Database 
{
	private $con;
	private $result;

	//CREATE DATABASE CONNECTION
	public function __construct()
	{
		try {
	        //CREATING DATABASE CONNECTION
	    	$this->con = new PDO("mysql:dbname=bachatas_smplugin;host=bachataspirit.com", "bachatas_vlada", "bacha19ta10");

    	} catch (PDOException $e) {
        	exit("Connection failed: " . $e->getMessage());
   		}
	}


	//FUNCTION FOR INSERTING FACEBOOK CREDENTIALS TO DATABASE
	public function insertFacebookCredentials($appId, $appSecret, $appToken, $pageId)
	{
		try {
		$truncate = $this->con->prepare("TRUNCATE TABLE facebook");
		$truncate->execute();

		//INSERTING FACEBOOK CREDENTIALS TO DATABASE
	    $query = $this->con->prepare("INSERT INTO facebook (appId, appSecret, appToken, pageId) 
	    								VALUES (:appId, :appSecret, :appToken, :pageId)");

		$query->bindValue(":appId", $appId);
	    $query->bindValue(":appSecret", $appSecret);
	    $query->bindValue(":appToken", $appToken);
	    $query->bindValue(":pageId", $pageId);

	    $this->result = $query->execute();

		} catch (PDOException $e) {
			//GETTING DATABASE ERROR
			$this->result = "Database error: " . $e->getMessage();
		}
		return $this->result;
	}


	//FUNCTION FOR GETTING FACEBOOK CREDENTIALS FROM DATABASE
	public function getFacebookCredentials()
	{
		try {
			//GETTING FACEBOOK CREDENTIALS FROM DATABASE
		    $query = $this->con->prepare("SELECT * FROM facebook WHERE userId = :userId");

			$query->bindValue(":userId", 1);

		    $query->execute();

		    $this->result = $query->fetch(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			//GETTING DATABASE ERROR
			$this->result = "Database error: " . $e->getMessage();
		}
		return $this->result;
	}


	//FUNCTION FOR UPDATING FACEBOOK CREDENTIALS IN DATABASE
	public function updateFacebookCredentials($newAppId, $newAppSecret, $newAppToken, $newPageId, $userId)
	{
		try {
			//UPDATING FACEBOOK CREDENTIALS IN DATABASE
		    $query = $this->con->prepare("UPDATE facebook SET appId = :newAppId, appSecret = :newAppSecret, appToken = :newAppToken,
		    								pageId = :newPageId WHERE userId = :userId");

			$query->bindValue(":newAppId", $newAppId);
		    $query->bindValue(":newAppSecret", $newAppSecret);
		    $query->bindValue(":newAppToken", $newAppToken);
		    $query->bindValue(":userId", $userId);
		    $query->bindValue(":newPageId", $newPageId);

		    $this->result = $query->execute();

		} catch (PDOException $e) {
			//GETTING DATABASE ERROR
			$this->result = "Database error: " . $e->getMessage();
		}
		return $this->result;
	}


	//FUNCTION FOR INSERTING INSTAGRAM CREDENTIALS TO DATABASE
	public function insertInstagramCredentials($appId, $appSecret, $appToken, $instagramId)
	{
		try {
			//TRUNCATING TABLE INSTAGRAM
			$truncate = $this->con->prepare("TRUNCATE TABLE instagram");
			$truncate->execute();

			//INSERTING INSTAGRAM CREDENTIALS TO DATABASE
		    $query = $this->con->prepare("INSERT INTO instagram (appId, appSecret, appToken, instagramId) 
		    								VALUES (:appId, :appSecret, :appToken, :instagramId)");

			$query->bindValue(":appId", $appId);
		    $query->bindValue(":appSecret", $appSecret);
		    $query->bindValue(":appToken", $appToken);
		    $query->bindValue(":instagramId", $instagramId);

		    $this->result = $query->execute();

		} catch (PDOException $e) {
			//GETTING DATABASE ERROR
			$this->result = "Database error: " . $e->getMessage();
		}
		return $this->result;
	}


	//FUNCTION FOR GETTING INSTAGRAM CREDENTIALS FROM DATABASE
	public function getInstagramCredentials()
	{
		try {
			//GETTING INSTAGRAM CREDENTIALS FROM DATABASE
		    $query = $this->con->prepare("SELECT * FROM instagram WHERE userId = :userId");

			$query->bindValue(":userId", 1);

		    $query->execute();

		    $this->result = $query->fetch(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			//GETTING DATABASE ERROR
			$this->result = "Database error: " . $e->getMessage();
		}
		return $this->result;
	}


	//FUNCTION FOR UPDATING INSTAGRAM CREDENTIALS IN DATABASE
	public function updateInstagramCredentials($newAppId, $newAppSecret, $newAppToken, $newInstagramId, $userId)
	{
		try {
			//UPDATING INSTAGRAM CREDENTIALS IN DATABASE
		    $query = $this->con->prepare("UPDATE instagram SET appId = :newAppId, appSecret = :newAppSecret, appToken = :newAppToken, 
		    								instagramId = :newInstagramId WHERE userId = :userId");

			$query->bindValue(":newAppId", $newAppId);
		    $query->bindValue(":newAppSecret", $newAppSecret);
		    $query->bindValue(":newAppToken", $newAppToken);
		    $query->bindValue(":newInstagramId", $newInstagramId);
		    $query->bindValue(":userId", $userId);

		    $this->result = $query->execute();

		} catch (PDOException $e) {
			//GETTING DATABASE ERROR
			$this->result = "Database error: " . $e->getMessage();
		}
		return $this->result;
	}
}
?>