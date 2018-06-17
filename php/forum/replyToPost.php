<?php
	include_once(__DIR__."/../../db.php");
	doDB();
	
	/*
	// check for required items from form
	if 
	(
		(!$_GET['topicId']) || 
		(!$_GET['postText']) || 
		(!$_GET['postOwner'])
	)
	{
		header('Location: index.php?sect=forum&page=topicList');
		exit;
	}
	*/
	
	// create safe values for use
	$safeTopicId = mysqli_real_escape_string($mySQLI, $_GET['topicId']);
	$safePostText = mysqli_real_escape_string($mySQLI, $_GET['postText']);
	$safePostOwner = mysqli_real_escape_string($mySQLI, $_GET['postOwner']);
	
	// add the post
	$addPostSQL =
<<<end_of_text
	INSERT INTO forumPosts
	(
		topicId,
		postText,
		postCreateTime,
		postOwner
	)
	VALUES
	(
		'$safeTopicId',
		'$safePostText',
		NOW(),
		'$safePostOwner'
	)
end_of_text;
	$addPostRes = mysqli_query($mySQLI, $addPostSQL) or die(mysqli_error($mySQLI));
	
	// close the connections
	mysqli_close($mySQLI);
	
	// refresh the topic posts to display the reply
	include "viewTopic.php";
	
?>