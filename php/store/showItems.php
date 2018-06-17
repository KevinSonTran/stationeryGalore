<?php
	include_once(__DIR__."/../../db.php");
	doDB();
	
	echo
<<<end_of_text
		<h1>Store</h1>
end_of_text;

	/* Create categories options
	 */
	$getAllCategoriesSQL = 
<<<end_of_text
	SELECT id, catTitle, catDesc FROM storeCategories
end_of_text;
	$allCategoriesRes = mysqli_query($mySQLI, $getAllCategoriesSQL) or die(mysqli_error($mySQLI));
	
	echo 
<<<end_of_text
		<form style='padding:10px' method='post'><label for='storeCategory'>Category: </label>
end_of_text;
	
	echo
<<<end_of_text
		<select name="storeCategory" onchange="displayStoreItems(this.value);">
end_of_text;
	
	echo 
<<<end_of_text
		<option value="any">Any</option>
end_of_text;
	
	while ($categoryInfo = mysqli_fetch_array($allCategoriesRes))
	{
		$currentCategoryTitle = $categoryInfo['catTitle'];
		$currentCategoryId = $categoryInfo['id'];
		$currentCategoryDesc = stripslashes($categoryInfo['catDesc']);
		echo 
<<<end_of_text
			<option value="$currentCategoryId"
end_of_text;
		
		// select the current category
		if (isset($_GET['catId']))
		{
			if ($_GET['catId'] == $currentCategoryId)
			{
				echo 
<<<end_of_text
					selected
end_of_text;
				$selectedDesc = $currentCategoryDesc;
			}
		}
		
		echo 
<<<end_of_text
			>$currentCategoryTitle</option>
end_of_text;
	}
	
	// if there is no category set, set the category description to default
	if (!isset($selectedDesc))
	{
		$selectedDesc = "Pick any item you want";
	}
	
	echo 
<<<end_of_text
		</select>
end_of_text;

	echo 
<<<end_of_text
		</form>
end_of_text;

	
	
	/* Gather the items
	 */
	 
	$displayBlock = 
<<<end_of_text
		<p>$selectedDesc</p>
end_of_text;
	 
	// gather the items
	$getItemsSQL =
<<<end_of_text
		SELECT id, itemTitle, itemPrice, itemImage FROM storeItems
end_of_text;

	// if there is a category set, and its NOT "any", search items by category
	if (isset($_GET['catId']) && $_GET['catId'] !== "any")
	{
		$currentCategory = $_GET['catId'];
		$getItemsSQL .= 
<<<end_of_text
			WHERE catId = '$currentCategory'
end_of_text;
	}
	$getItemsSQL .= 
<<<end_of_text
	ORDER BY itemTitle
end_of_text;
	$getItemsRes = mysqli_query($mySQLI, $getItemsSQL) or die(mysqli_error($mySQLI));
		
	// if there are no items, display as such
	if (mysqli_num_rows($getItemsRes) < 1)
	{
		$displayBlock .= 
<<<end_of_text
			<p><em>No Items Exist</em></p>
end_of_text;
	}
	// create the display string
	else
	{
		$displayBlock .= "<table>";
			
		while ($itemInfo = mysqli_fetch_array($getItemsRes))
		{
			$itemId = $itemInfo['id'];
			$itemTitle = stripslashes($itemInfo['itemTitle']);
			$itemPrice = $itemInfo['itemPrice'];
			$itemImage = $itemInfo['itemImage'];
			
			// add to display
			$displayBlock .=
<<<end_of_text
				<tr class='storeItem'>
				
				<td class="storeItemThumbnail">
					<a href="index.php?sect=store&page=viewItem&itemId=$itemId&qtySizeId=-1&qtyColorId=-1">
						<img src="$itemImage" alt="$itemTitle"/>
					</a>
				</td>
				
				<td style="vertical-align:top;">
					<a href="index.php?sect=store&page=viewItem&itemId=$itemId&qtySizeId=-1&qtyColorId=-1">
						<h2>$itemTitle</h2>
					</a>
				</td>
				
				<td style="text-align:right;">
					<p><strong>$ $itemPrice</strong></p>
				</td>
				
				</tr>
end_of_text;
		}
		
		$displayBlock .= "</table>";
		
		// free results
		mysqli_free_result($getItemsRes);
	}
	
	echo $displayBlock;
?>