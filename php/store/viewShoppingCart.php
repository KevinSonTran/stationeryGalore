<?php
	//session_start();

	//connect to database 
	$mySQLI = mysqli_connect("localhost", "root", "100100101101011011010010", "db");

	$displayBlock = "<h1>Your Shopping Cart</h1>";

	//check for cart items based on user session id
	$cartRes = mysqli_query
	(
		$mySQLI, 
		"
			SELECT 
				st.id, 
				si.itemImage,
				si.itemTitle, 
				si.itemPrice,
				st.selItemQty, 
				st.selItemSize, 
				st.selItemColor
			FROM
				storeShoppingCart 
			AS 
				st LEFT JOIN storeItems 
			AS
				si ON si.id = st.selItemId 
			WHERE sessionId =
				'".$_COOKIE['PHPSESSID']."'
		"
	)
	or die(mysqli_error($mySQLI));

	if (mysqli_num_rows($cartRes) < 1)
	{
		//print message
		$displayBlock .= 
		"
			<p>
				You have no items in your cart.
				Please <a href='index.php?sect=store&page=seeStore'>continue to shop</a>!
			</p>
		";
	} 
	else
	{
		//get info and build cart display
		$displayBlock .= 
<<<END_OF_TEXT
		<table>
			<tr>
				<th></th>
				<th>Title</th>
				<th>Size</th>
				<th>Color</th>
				<th>Price</th>
				<th>Qty</th>
				<th>Total Price</th>
				<th></th>
			</tr>
END_OF_TEXT;

		$totalItems = 0;
		$totalPriceOfEverything = 0;
		
		while ($cartInfo = mysqli_fetch_array($cartRes)) 
		{
			$id = $cartInfo['id'];
			$itemImage = stripslashes($cartInfo['itemImage']);
			$itemTitle = stripslashes($cartInfo['itemTitle']);
			$itemPrice = $cartInfo['itemPrice'];
			$itemQty = $cartInfo['selItemQty'];
			$itemColor = $cartInfo['selItemColor'];
			$itemSize = $cartInfo['selItemSize'];
			$totalPrice = sprintf("%.02f", $itemPrice * $itemQty);
			
			$totalItems = $totalItems + $itemQty;
			$totalPriceOfEverything =  sprintf("%.02f", $totalPriceOfEverything + $totalPrice);

			$displayBlock .= 
<<<END_OF_TEXT
				<tr>
					<td> <img src="$itemImage" alt="$itemTitle" width=auto height=50 /> <br></td>
					<td>$itemTitle <br></td>
					<td>$itemSize <br></td>
					<td>$itemColor <br></td>
					<td>\$ $itemPrice <br></td>
					<td>$itemQty <br></td>
					<td>\$ $totalPrice</td>
					<td><a href="index.php?sect=store&page=removeFromCart&id=$id">remove</a></td>
				</tr>
END_OF_TEXT;
		}
		

		$displayBlock .= 
<<<END_OF_TEXT
				<tr style="border-top:1px solid black;">
					<td> <br></td>
					<td> <br></td>
					<td> <br></td>
					<td> <br></td>
					<td> <br></td>
					<td> $totalItems <br></td>
					<td>\$ $totalPriceOfEverything</td>
					<td><a href="index.php?sect=store&page=checkout&id=$id&totalItems=$totalItems&totalPrice=$totalPriceOfEverything">Check Out</a></td>
				</tr>
END_OF_TEXT;

		$displayBlock .= 
		"
		</table>
		<a href='index.php?sect=store&page=seeStore'>Go back to Store</a>
		";
	}
	//free result
	mysqli_free_result($cartRes);

	//close connection to MySQL
	mysqli_close($mySQLI);
	
?>


<!DOCTYPE html>
<html>
	<head>
		<title>Shopping Cart</title>
	</head>
	
	<body>
		<?php echo $displayBlock; ?>
	</body>
</html>