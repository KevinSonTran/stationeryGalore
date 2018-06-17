<?php
	//session_start();

	if (isset($_GET['id'])) 
	{
		//connect to database
		$mySQLI = mysqli_connect("localhost", "root", "100100101101011011010010", "db");

		//create safe values for use
		$safeId = mysqli_real_escape_string($mySQLI, $_GET['id']);
		
		
		// update qtys
		$removingItem = mysqli_query
		(
			$mySQLI, 
			"
				SELECT 
					selItemQty,
					selItemId,
					selItemSizeId,
					selItemColorId
				FROM
					storeShoppingCart
				WHERE
					id = ".$safeId
		)
		or die
		(
			mysqli_error($mySQLI)
		);
		if (mysqli_num_rows($removingItem) > 0) 
		{
			while ($removingItemInfo = mysqli_fetch_array($removingItem)) 
			{
				$itemQty = stripslashes($removingItemInfo['selItemQty']);
				$itemId = $removingItemInfo['selItemId'];
				$existingColorId = $removingItemInfo['selItemSizeId'];
				$existingSizeId =  $removingItemInfo['selItemColorId'];
				
				/* TASK : "If they decide to remove an item from their cart, it should replace the stock."
				 */
				mysqli_query
				(
					$mySQLI, 
					"
						UPDATE storeItemQtys
						SET 
							itemQty = itemQty + $itemQty
						WHERE itemId = $itemId
						AND	itemSizeId = $existingSizeId
						AND	itemColorId = $existingColorId
					"
				)
				or die
				(
					mysqli_error($mySQLI)
				);
			}
		}

		
		// remove the item
		mysqli_query
		(
			$mySQLI, 
			"
				DELETE FROM storeShoppingCart 
				WHERE id = '".$safeId."' 
				AND sessionId ='".$_COOKIE['PHPSESSID']."'
			"
		)
		or die(mysqli_error($mySQLI));

		//close connection to MySQL
		mysqli_close($mySQLI);

		//redirect to showcart page
		header("Location: index.php?sect=store&page=viewShoppingCart");
		exit;
	} 
	else 
	{
		//send them somewhere else
		header("Location: index.php?sect=store&page=viewShoppingCart");
		exit;
	}
?>