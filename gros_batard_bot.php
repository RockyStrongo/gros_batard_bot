<?php

//Keys and Access Tokens for Twitter
include "gitignore/twitter_credentials.php";

//include the Oauth library - https://twitteroauth.com/
require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

//Connection
$connection = new TwitterOAuth(
    $consumerKey,
    $consumerSecret,
    $accessToken,
    $accessTokenSecret
);
//increase timeout limit
$connection->setTimeouts(10, 100);
$content = $connection->get("account/verify_credentials");

//get already tweeted replies
//get number of tweets
$parameters = ["screen_name" => "tarbabot"];

$userarray = $connection->get("users/show", $parameters);

$numberoftweets = $userarray->statuses_count;
echo "<br>Number of tweets: " . $numberoftweets;

//limit of twitter API to get tweets is 200
//divide the number of tweets by 200 to know how many times we need to get tweets
$numberoftweetsdivided = $numberoftweets / 200;
echo "<br>Number of tweets divided by 200: " . $numberoftweetsdivided;
//round to down number
$iterationsneeded = floor($numberoftweetsdivided);
echo "<br>Iterations needed: " . $iterationsneeded;

//daily twitter API limit= 3200 - 200 limit for 1 get
$dailyAPIlimit = 3200 / 200;

//Get first 200 tweets
$parameters = ["screen_name" => "tarbabot", "count" => "200"];

$mytweets = $connection->get("statuses/user_timeline", $parameters);

//get last tweet ID
$lastKey = key(array_slice($mytweets, -1, 1, true));

//echo "<br>Last Key: " . $lastKey;
//echo "<br>Last key id: " . $statuses[$lastKey]->id;
$lastkeyID = $mytweets[$lastKey]->id;

//maxid = returns tweets older than this ID
$i = 0;
while ($i < $iterationsneeded && $i < $dailyAPIlimit) {
    echo "<br>while loop to get more tweets";
    $parameters = [
        "screen_name" => "tarbabot",
        "count" => "200",
        "max_id" => $lastkeyID,
    ];

    $mytweets1 = $connection->get("statuses/user_timeline", $parameters);

    foreach ($mytweets1 as $k => $v) {
        array_push($mytweets, $v);
    }
    $lastkeyID = $mytweets1[$lastKey]->id;
    $i++;
}

// get IDs of tweets replied to
$tweetsrepliedto = [];

foreach ($mytweets as $k => $v) {
    array_push($tweetsrepliedto, $v->in_reply_to_status_id);
}

//search tweets containing "gros batard"
$parameters = ["q" => '"gros batard"', "lang" => "fr", "count" => "15"];

$statuses = $connection->get("search/tweets", $parameters);

$tweets = $statuses->statuses;
$search_metadata = $statuses->search_metadata;

//print("<pre>".print_r($tweets,true)."</pre>");

//reply to those tweets
foreach ($tweets as $key => $value) {
    //get tweet ID
    $tweetID = $value->id;
    //print_r($tweetID);
    //echo "<br>";

    //check if already replied
    if (in_array($tweetID, $tweetsrepliedto)) {
        echo "<br>already replied to this tweet, don't tweet";
    }

    //check if it's a RT
    elseif (property_exists($value, 'retweeted_status')) {
        echo "<br>it's a retweet, don't reply";
    } else {
        //tweet the reply
        //get handle
        $handle = $value->user->screen_name;
        $replytext = "@" . $handle . " Avec ceci ?";
        //print_r($replytext);
        //echo "<br>";

        //upload image
        $media = $connection->upload('media/upload', ['media' => "tarba.jpg"]);
        $mediaID = $media->media_id_string;

        //post tweet
        $statues = $connection->post("statuses/update", [
            "status" => $replytext,
            "in_reply_to_status_id" => $tweetID,
            'media_ids' => $mediaID,
        ]);

        if ($connection->getLastHttpCode() == 200) {
            echo "<br>Tweet posted succesfully";
        } else {
            echo "error posting tweet";
            $errortwitter = $connection->getLastHttpCode();
            echo $errortwitter;
        }
    }
}

?>