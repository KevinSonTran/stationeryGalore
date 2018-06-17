<?php
	include_once(__DIR__."/../../db.php");
	doDB();
	
	
	
	
	/* Gather the items
	 */
	 
	$getItemsSQL =
<<<end_of_text
		SELECT id, itemTitle, itemPrice, itemImage FROM storeItems
		ORDER BY itemTitle
end_of_text;
	$getItemsRes = mysqli_query($mySQLI, $getItemsSQL) or die(mysqli_error($mySQLI));
	$displayBlock = "";
		
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
		$displayBlock .= "<a href='index.php?sect=store&page=seeStore'><h2>What we have</h2></a> <table>";
		
		$i = 0;
			
		// display top 5 items
		while (($itemInfo = mysqli_fetch_array($getItemsRes)) && $i < 5)
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
						<img src="$itemImage" alt="$itemTitle" />
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

			$i = $i + 1;
		}
		
		$displayBlock .= "</table>";
		
		// free results
		mysqli_free_result($getItemsRes);
	}
	
	$displayBlock .=  '<a href="index.php?sect=store&page=seeStore">Go to store</a>';
	echo $displayBlock;
?>