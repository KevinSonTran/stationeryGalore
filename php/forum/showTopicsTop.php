<?php
	include_once(__DIR__."/../../db.php");
	doDB();
	
	
	
	/* List current topics
	 */
	 
	// gather the topics
	$getTopicsRes = mysqli_query
	(
		$mySQLI, 
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
		ORDER BY topicCreateTime DESC
end_of_text
	) 
	or die(mysqli_error($mySQLI));
	$displayBlock = "";
		
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
		$displayBlock = "<a href='index.php?sect=forum&page=topicList'><h2>Recent Topics</h2></a>";
		
		$i = 0;
			
		// display top 5 topics
		while (($topicInfo = mysqli_fetch_array($getTopicsRes)) && $i < 5)
		{
			$topicId = $topicInfo['topicId'];
			$topicTitle = stripslashes($topicInfo['topicTitle']);
			$topicCreateTime = $topicInfo['fmtTopicCreateTime'];
			$topicOwner = stripslashes($topicInfo['topicOwner']);
			$topicCategory = $topicInfo['categoryTitle'];
			
			// get number of posts 
			$getNumPostsRes = mysqli_query
			(
				$mySQLI,
<<<end_of_text
				SELECT COUNT(postId) AS postCount FROM forumPosts
				WHERE topicId = '$topicId'
end_of_text
			) or die(mysqli_error($mySQLI));
			
			while ($postsInfo = mysqli_fetch_array($getNumPostsRes))
			{
				$numPosts = $postsInfo['postCount'];
			}
			
			$htmlTopicTitle = htmlspecialchars($topicTitle);
			// add to display
			$displayBlock .=
<<<end_of_text
				<div style="padding:10px;">
					<a href="index.php?sect=forum&page=viewTopic&topicId=$topicId"><h2>$topicTitle</h2></a>
					
					<hr/>
					<p style="color:#808080;">Created by $topicOwner </p>
					<p style="color:#808080;font-size:12px;">
						$topicCreateTime <br/>
						# of posts: $numPosts <br/>
						Category: $topicCategory
					</p>
				</div>
end_of_text;
			$i = $i + 1;
		}
		// free results
		mysqli_free_result($getTopicsRes);
		mysqli_free_result($getNumPostsRes);
	}
	
	$displayBlock .=  '<a href="index.php?sect=forum&page=topicList">Go to forum</a>';
	echo $displayBlock;
?>