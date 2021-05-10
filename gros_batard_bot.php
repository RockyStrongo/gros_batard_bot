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
$parameters = ["q" => '"gros batard"', "lang" => "fr", "count" => "15"];

$statuses = $connection->get("search/tweets", $parameters);

$tweets = $statuses->statuses;
$search_metadata = $statuses->search_metadata;

//print("<pre>".print_r($tweets,true)."</pre>");

//reply to those tweets

foreach ($tweets as $key => $value) {
	$handle = $value->user->screen_name;
	$replytext = "@".$handle." Avec ceci ?";
	print_r($replytext);
	echo "<br>";


/*
$media = $connection->upload('media/upload', ['media' => "tarba.jpg"]);
$mediaID =  $media->media_id_string;

	
$statues = $connection->post("statuses/update", ["status" => '@RockyStrongo Avec ceci ?', "in_reply_to_status_id" => "1200771462682095616", 'media_ids' => $mediaID]);

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

}


/*
$media = $connection->upload('media/upload', ['media' => "tarba.jpg"]);
$mediaID =  $media->media_id_string;


	$statues = $connection->post("statuses/update", ["status" => '@RockyStrongo Avec ceci ?', "in_reply_to_status_id" => "1200771462682095616", 'media_ids' => $mediaID]);

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