<?php
//session_start();

if (isset($_POST['selItemId'])) 
{
	//connect to database
	$mySQLI = mysqli_connect('localhost', 'root', '100100101101011011010010', 'db');

	//create safe values for use
	$safe_selItemId = mysqli_real_escape_string($mySQLI, $_POST['selItemId']);
	$safe_selItemQty = mysqli_real_escape_string($mySQLI, $_POST['selItemQty']);
	
	$safe_selItemSize = '';
	if (isset($_POST['selItemSize']))
	{
		$safe_selItemSize = mysqli_real_escape_string($mySQLI, $_POST['selItemSize']);
	}
	$safe_selItemColor= '';
	if (isset($_POST['selItemColor']))
	{
		$safe_selItemColor = mysqli_real_escape_string($mySQLI, $_POST['selItemColor']);	
	}

	//validate item and get title and price
	$itemInfoRes = mysqli_query
	(
		$mySQLI, 
		"
		SELECT itemTitle FROM storeItems WHERE
			id = '".$safe_selItemId."'
		"
	)
	or die
	(
		mysqli_error($mySQLI)
	);

	if (mysqli_num_rows($itemInfoRes) < 1) 
	{
		//free result
		mysqli_free_result($itemInfoRes);

		//close connection to MySQL
		mysqli_close($mySQLI);

		//invalid id, send away
		//header("Location seestore.php");
		exit;
	} 
	else 
	{
		//get info
		while ($item_info = mysqli_fetch_array($itemInfoRes)) 
		{
			$itemTitle = stripslashes($item_info['itemTitle']);
		}

		//free result
		mysqli_free_result($itemInfoRes);

		//add info to cart table
		$addtocart_res = mysqli_query
		(
			$mySQLI, 
			"
			INSERT INTO storeShoppingCart
			(
				sessionId, 
				selItemId, 
				selItemQty,
				selItemSizeId,
				selItemSize, 
				selItemColorId,
				selItemColor, 
				dateAdded
			)
			VALUES 
			(
				'".$_COOKIE['PHPSESSID']."',
				'".$safe_selItemId."',
				'".$safe_selItemQty."',
				'".$_GET['qtySizeId']."',
				'".$safe_selItemSize."',
				'".$_GET['qtyColorId']."',
				'".$safe_selItemColor."', 
				now()
			)
			"
		)
		or die
		(
			mysqli_error($mySQLI)
		);
		
		
		// update qtys
	
		$existingColorId = $_GET['qtySizeId'];
		$existingSizeId =  $_GET['qtySizeId'];
	
		/* TASK : "When someone checks out, it should reduce the inventory in the database."
		 * - if item is added to cart, it reduces inventory in database, so when 
		 *   that item is checked out, the inventory is already reduced
		 * - this functionality is also required for:  
		 *   "If they decide to remove an item from their cart, it should replace the stock."
		 */
		mysqli_query
		(
			$mySQLI, 
			"
				UPDATE storeItemQtys
				SET 
					itemQty = itemQty - $safe_selItemQty
				WHERE itemId = $safe_selItemId
				AND	itemSizeId = $existingSizeId
				AND	itemColorId = $existingColorId
			"
		)
		or die
		(
			mysqli_error($mySQLI)
		);


		//close connection to MySQL
		mysqli_close($mySQLI);

		header('Location: index.php?sect=store&page=viewItem&itemId='.$safe_selItemId.'&qtySizeId='.$existingSizeId.'&qtyColorId='.$existingSizeId);
		exit;
	}

} 
else 
{
	//send them somewhere else
	//header("Location seestore.php");
	exit;
}
?>