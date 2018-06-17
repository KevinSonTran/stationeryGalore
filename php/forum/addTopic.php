<h1>Add Topic</h1>

<?php
	/* Acquire all available categories and
	 * arrange them in the options form
	 */
	$getAllCategoriesSQL = 
<<<end_of_text
	SELECT categoryTitle FROM forumCategories
end_of_text;
	$allCategoriesRes = mysqli_query($mySQLI, $getAllCategoriesSQL) or die(mysqli_error($mySQLI));
	
	echo 
<<<end_of_text
		<label for="forumCategory">Category : </label>
end_of_text;
	echo 
<<<end_of_text
		<select id="forumCategory" name="forumCategory" onchange="toggleNewForm(this, 'newCategory');">
end_of_text;
	while ($categoryInfo = mysqli_fetch_array($allCategoriesRes))
	{
		$currentCategory = $categoryInfo['categoryTitle'];
		echo
<<<end_of_text
		<option value="$currentCategory">$currentCategory</option>
end_of_text;
	}
	echo 
<<<end_of_text
		<option value="addNew">ADD NEW</option>
end_of_text;
	echo 
<<<end_of_text
		</select>
end_of_text;
?>

<input type='text' id='topicCategory' class='newCategory' style="display:none;width:30%" name='topicCategory' size='40' maxLength='150' placeholder="New Category ..."/>

<p>
	<input type='email' id='topicOwner' name='topicOwner' size='40' maxLength='150' required='required' placeholder="Your Email Address ..."/>
</p>

<p>
	<input type='text' id='topicTitle' name='topicTitle' size='40' maxLength='150' required='required' placeholder="Topic Title ..."/>
</p>

<p>
	<textarea id='postText' name='postText' rows='8' cols='40' placeholder="Post Text ..."></textarea>
</p>

<button onclick=
"
	doPost
	(
		document.getElementById('forumCategory').value,
		document.getElementById('topicCategory').value,
		document.getElementById('topicOwner').value,
		document.getElementById('topicTitle').value,
		document.getElementById('postText').value
	);
"
> Add Topic </button>