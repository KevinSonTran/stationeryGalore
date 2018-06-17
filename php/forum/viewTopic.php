<?php
	include_once(__DIR__."/../../db.php");
	doDB();
	
	// check for required info from the query string
	if (!isset($_GET['topicId']))
	{
		header('Location: index.php?sect=forum&page=topicList');
		exit;
	}
	
	// create safe values for use
	$safeTopicId = mysqli_real_escape_string($mySQLI, $_GET['topicId']);
	
	// verify topic exists
	$verfiyTopicSQL = 
<<<end_of_text
	SELECT topicTitle FROM forumTopics 
	WHERE topicId = '$safeTopicId'
end_of_text;
	$verifyTopicRes = mysqli_query($mySQLI, $verfiyTopicSQL) or die(mysqli_error($mySQLI));
	
	if (mysqli_num_rows($verifyTopicRes) < 1)
	{
		// this topic does not exists
		$displayBlock = 
<<<end_of_text
		<p>
			<em>
				You have selected an invalid topic.
				<br/>
				Please <a href='index.php?sect=forum&page=topicList'> try again </a> .
			</em>
		</p>
end_of_text;
	}
	else
	{
		// get the topic title
		while ($topicInfo = mysqli_fetch_array($verifyTopicRes))
		{
			$topicTitle = stripslashes($topicInfo['topicTitle']);
		}
		
		// gather the posts
		$getPostsSQL = 
<<<end_of_text
		SELECT postId, postText, DATE_FORMAT
		(
			postCreateTime,
			'%b %e %Y</br>%r'
		)
		AS fmtPostCreateTime, postOwner FROM forumPosts
		WHERE topicId = '$safeTopicId'
		ORDER BY postCreateTime ASC
end_of_text;
		$getPostsRes = mysqli_query($mySQLI, $getPostsSQL) or die(mysqli_error($mySQLI));
		
		// create the display string
		$displayBlock =
<<<end_of_text
		<table>
end_of_text;
		
		while ($postsInfo = mysqli_fetch_array($getPostsRes))
		{
			$postId = $postsInfo['postId'];
			$postText = nl2br(stripslashes($postsInfo['postText']));
			$postCreateTime = $postsInfo['fmtPostCreateTime'];
			$postOwner = stripslashes($postsInfo['postOwner']);
			
			// add to display
			$displayBlock .= 
<<<end_of_text
				<tr>
					<td> 
						$postOwner 
						<br/> 
						<br/>
						<p style="font-size:12px;">
							$postCreateTime
						</p>
					</td>
					<td> 
						$postText 
						<br/> 
						<br/>
						<a onclick="toggleDisplay(document.getElementById('replyTo$postId'));">
							<strong style="font-size:12px;"> REPLY TO POST </strong>
						</a>
						
						<div id="replyTo$postId" style="display:none;">
							<p>
								<input type="email"  id="postOwner$postId"  name="postOwner"  size="40" maxLength="150" required="required" placeholder="Your Email Address ..."/>
							</p>
							
							<p>
								<textarea id='postText$postId' name='postText' rows='8' cols='40' placeholder="Post Text ..."></textarea>
							</p>
							
							<button onclick=
							"
								doReply
								(
									$safeTopicId,
									document.getElementById('postOwner$postId').value,
									document.getElementById('postText$postId').value
								);
							">Add Post</button>
							
						</div>
						
					</td>
				</tr>
end_of_text;
		}
		// free results
		mysqli_free_result($getPostsRes);
		mysqli_free_result($verifyTopicRes);
		
		// close teh connection
		mysqli_close($mySQLI);
		
		// close table
		$displayBlock .= 
<<<end_of_text
			</table>
end_of_text;
	}
?>


<?php echo $displayBlock; ?>