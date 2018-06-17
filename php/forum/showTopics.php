<?php
	include_once(__DIR__."/../../db.php");
	doDB();
	
	/* Create categories options form
	 */
	echo
<<<end_of_text
		<h1>Topics</h1>
end_of_text;
	$allCategoriesRes = mysqli_query($mySQLI, "SELECT categoryTitle FROM forumCategories") or die(mysqli_error($mySQLI));
	
	echo 
<<<end_of_text
		<form style='padding:10px' method='post'><label for='forumCategory'>Category: </label>
end_of_text;
	
	echo
<<<end_of_text
		<select name="forumCategory" onchange="displayCategoryTopics(this.value);">
end_of_text;
	
	echo 
<<<end_of_text
		<option value="any">Any</option>
end_of_text;
	
	while ($categoryInfo = mysqli_fetch_array($allCategoriesRes))
	{
		$currentCategoryTitle = $categoryInfo['categoryTitle'];
		echo 
<<<end_of_text
			<option value="$currentCategoryTitle"
end_of_text;
		
		// select the current category
		if (isset($_GET['category']))
		{
			if ($_GET['category'] == $categoryInfo['categoryTitle'])
			{
				echo 
<<<end_of_text
					selected
end_of_text;
			}
		}
		
		echo 
<<<end_of_text
			>$currentCategoryTitle</option>
end_of_text;
	}
	echo 
<<<end_of_text
		</select>
end_of_text;

	echo 
<<<end_of_text
		</form>
end_of_text;
	
	
	/* List current topics
	 */
	 
	// gather the topics
	$getTopicsSQL =
<<<end_of_text
		SELECT 
			topicId, 
			topicTitle, 
			categoryTitle, 
			DATE_FORMAT
			(
				topicCreateTime,
				'%b %e %Y at %r'
			)
			AS fmtTopicCreateTime, 
			topicOwner
		FROM forumTopics
end_of_text;

	// if there is a category set, and its NOT "any", search topics by category
	if (isset($_GET['category']) && $_GET['category'] !== "any")
	{
		$currentCategory = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_GET['category']));
		$getTopicsSQL .= 
<<<end_of_text
			WHERE categoryTitle = '$currentCategory'
end_of_text;
	}
	$getTopicsSQL .= 
<<<end_of_text
		ORDER BY topicCreateTime DESC
end_of_text;

	$getTopicsRes = mysqli_query($mySQLI, $getTopicsSQL) or die(mysqli_error($mySQLI));
		
	// if there are no topics, display as such
	if (mysqli_num_rows($getTopicsRes) < 1)
	{
		$displayBlock = 
<<<end_of_text
			<p><em>No Topics Exist</em></p>
end_of_text;
	}
	// create the display string
	else
	{
		$displayBlock = "";
			
		while ($topicInfo = mysqli_fetch_array($getTopicsRes))
		{
			$topicId = $topicInfo['topicId'];
			$topicTitle = stripslashes($topicInfo['topicTitle']);
			$topicCreateTime = $topicInfo['fmtTopicCreateTime'];
			$topicOwner = stripslashes($topicInfo['topicOwner']);
			$topicCategory = $topicInfo['categoryTitle'];
			
			// get number of posts
			$getNumPostsSQL = 
<<<end_of_text
				SELECT COUNT(postId) AS postCount FROM forumPosts
				WHERE topicId = '$topicId'
end_of_text;
			$getNumPostsRes = mysqli_query($mySQLI, $getNumPostsSQL) or die(mysqli_error($mySQLI));
			while ($postsInfo = mysqli_fetch_array($getNumPostsRes))
			{
				$numPosts = $postsInfo['postCount'];
			}
			
			$htmlTopicTitle = htmlspecialchars($topicTitle);
			// add to display
			$displayBlock .=
<<<end_of_text
				<div style="padding:10px;">
					<a onclick=
					'
						showReplies
						(
							"postsIn$topicId",
							$topicId
						);
					'
					> <h2>$topicTitle</h2> </a> 
					
					<div id="postsIn$topicId"></div>
					
					<hr/>
					<p style="color:#808080;">Created by $topicOwner </p>
					<p style="color:#808080;font-size:12px;">
						$topicCreateTime <br/>
						# of posts: $numPosts <br/>
						Category: $topicCategory
					</p>
				</div>
end_of_text;
		}
		// free results
		mysqli_free_result($getTopicsRes);
		mysqli_free_result($getNumPostsRes);
	}
	
	echo $displayBlock;
?>