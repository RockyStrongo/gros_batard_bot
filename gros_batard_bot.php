<?php

//Keys and Access Tokens for Twitter
include ("gitignore/twitter_credentials.php");

//include the Oauth library - https://twitteroauth.com/
require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

//Connection
$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
//increase timeout limit
$connection->setTimeouts(10, 100);
$content = $connection->get("account/verify_credentials");

//search tweets containing "gros batard"
$parameters = ["q" => '"gros batard"', "lang" => "fr", "count" => "100"];

$statuses = $connection->get("search/tweets", $parameters);

print("<pre>".print_r($statuses,true)."</pre>");

//reply to those tweets

/*
	$statues = $connection->post("statuses/update", ["status" => 'hi there @RockyStrongo', "in_reply_to_status_id" => "1200771462682095616"]);

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