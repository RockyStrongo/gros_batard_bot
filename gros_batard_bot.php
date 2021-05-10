<?php

//Keys and Access Tokens for Twitter
include ("gitignore/twitter_credentials.php");

//include the Oauth library - https://twitteroauth.com/
require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;


$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
//increase timeout limit
$connection->setTimeouts(10, 100);
$content = $connection->get("account/verify_credentials");



/*
	$statues = $connection->post("statuses/update", ["status" => 'hello world']);

	if ($connection->getLastHttpCode() == 200)
	{
		echo "<br>Tweet posted succesfully";
	}
	else
	{
		echo "error posting tweet";
		$errortwitter = $connection->getLastHttpCode();
		echo $errortwitter;
	}
*/

?>