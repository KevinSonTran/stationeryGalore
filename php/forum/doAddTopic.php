<?php
	include_once(__DIR__."/../../db.php");
	doDB();
	
	// check for required fields from the form
	if ((!$_GET['topicOwner']) || (!$_GET['topicTitle']) || (!$_GET['postText']))
	{
		header('Location: index.php?sect=forum&page=topicList');
		exit;
	}
	
	// create safe values for input into the database
	$cleanTopicOwner = mysqli_real_escape_string($mySQLI, $_GET['topicOwner']);
	
	/* Create new category and add it to database,
	 * then add topic to new category
	 */
	if (isset($_GET['newCategory']) && $_GET['newCategory'] !== null && $_GET['newCategory'] !== "")
	{
		$cleanTopicCategory = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_GET['newCategory']));
		$addNewCatergorySQL = 
<<<end_of_text
			INSERT INTO forumCategories
			(
				categoryTitle
			)
			VALUES
			(
				'$cleanTopicCategory'
			)
			ON DUPLICATE KEY UPDATE categoryTitle = '$cleanTopicCategory' 
end_of_text;
		mysqli_query($mySQLI, $addNewCatergorySQL) or die(mysqli_error($mySQLI));
		
		// set category in URL to update page to display topic
		$_GET['category'] = $cleanTopicCategory;
	}
	/* If an existing category is selected, put topic in that category
	 */
	else if ($_GET['category'] !== "addNew")
	{
		$cleanTopicCategory = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_GET['category']));
	}
	/* If no category is set, put topic in "Miscellaneous"
	 */
	else
	{
		$cleanTopicCategory = htmlspecialchars(mysqli_real_escape_string($mySQLI, "Miscellaneous"));
	}
	$cleanTopicTitle = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_GET['topicTitle']));
	$cleanPostText = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_GET['postText']));
	
	// create and issue the first query
	$addTopicSQL =  
<<<end_of_text
		INSERT INTO forumTopics
		(
			categoryTitle,
			topicTitle,
			topicCreateTime,
			topicOwner
		)
		VALUES
		(
			'$cleanTopicCategory',
			'$cleanTopicTitle',
			NOW(),
			'$cleanTopicOwner'
		) 
end_of_text;
	$addTopicRes = mysqli_query($mySQLI, $addTopicSQL) or die(mysqli_error($mySQLI));
	
	// get id of last query
	$topicID = mysqli_insert_id($mySQLI);
	
	// create and issue the second query
	$addPostSQL =  
<<<end_of_text
		INSERT INTO forumPosts
		(
			topicID,
			postText,
			postCreateTime,
			postOwner
		)
		VALUES
		(
			'$topicID',
			'$cleanPostText',
			NOW(),
			'$cleanTopicOwner'
		) 
end_of_text;
	$addPostRes = mysqli_query($mySQLI, $addPostSQL) or die(mysqli_error($mySQLI));
	
	// close connection to MySQL
	mysqli_close($mySQLI);
	
	/* Update page to display new topic
	 */
	include "showTopics.php";
?>