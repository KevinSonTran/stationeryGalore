/**
 * toggleDisplay:
 * - acquires element supplied by the page, and
 *   toggles its display
 */
function toggleDisplay(displayElement)
{
	if (displayElement.style.display == "none" || displayElement.style.display === undefined)
	{
		displayElement.style.display = "inline";
	}
	else
	{
		displayElement.style.display = "none";
	}
}

/**
 * doPost:
 * - acquires content entered in the form for posting,
 *   adds the post into the database,
 *   then immediately updates the page to display the title
 *   of the new topic
 */
function doPost(category, newCategory, owner, title, content)
{
	xmlhttp = acquireXMLHttpRequest("displayBlock");
	
	/* Add the new topic in the database,
	 * doAddTopic.php will load the showTopics.php
	 * to update the page to display new topic
	 */
	xmlhttp.open("GET", "php/forum/doAddTopic.php?category=" + category + "&newCategory=" + newCategory + "&topicOwner="  + owner + "&topicTitle=" + title + "&postText=" + content, true);
	xmlhttp.send();
}
/**
 * displayCategoryTopics:
 * - acquires the category name from the selectable options,
 *   uses it to select any topics with the matching category name
 *   and updates the page with the new topics
 */
function displayCategoryTopics(displayCategory)
{
	xmlhttp = acquireXMLHttpRequest("displayBlock");
	
	/* Auto update the page to display topics of certain category
	 */
	xmlhttp.open("GET", "php/forum/showTopics.php?category=" + displayCategory, true);
	xmlhttp.send();
}
/**
 * showReplies:
 * - acquires the id of display block inside the topic div
 *   and displays all the posts for that topic inside the div
 * - if the posts are already displayed in the div, these posts
 *   will disappear
 */
function showReplies(displayBlock, topicId)
{
	if 
	(
		document.getElementById(displayBlock).innerHTML === undefined || 
		document.getElementById(displayBlock).innerHTML === ""
	)
	{
		xmlhttp = acquireXMLHttpRequest(displayBlock);
		
		/* Display all replies into the display block,
		 * which would normally be in the topic div
		 */
		xmlhttp.open("GET", "php/forum/viewTopic.php?topicId=" + topicId, true);
		xmlhttp.send();	
	}
	else
	{
		document.getElementById(displayBlock).innerHTML = "";
	}
}
/**
 * doReply:
 * - acquire contents of the reply entered by the user,
 *   add the reply to the database,
 *   then use the topicId to refresh the display table of the 
 *   topic posts to show the new reply
 */
function doReply(topicId, user, reply)
{
	xmlhttp = acquireXMLHttpRequest("postsIn"+topicId);
	
	/* Open reply to post to add new reply to database,
	 * the replyToPost.php will open viewTopic.php to
	 * display all replies including new reply,
	 * using the topicId in the URL
	 */
	xmlhttp.open("GET", "php/forum/replyToPost.php?topicId="+topicId+"&postOwner="+user+"&postText="+reply, true);
	xmlhttp.send();
}