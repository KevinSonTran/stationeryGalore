<?php
	include_once(__DIR__."/../../db.php");
	doDB();
	
	// check for required fields from the form
	if ((!$_POST['orderAddress']) || (!$_POST['orderCity']) || (!$_POST['orderState']) || (!$_POST['orderZip']) || (!$_POST['orderEmail']))
	{
		header('Location: index.php?sect=store&page=seeStore');
		exit;
	}
	
	// create safe values
	$cleanOrderName    = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_POST['orderName']));
	$cleanOrderAddress = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_POST['orderAddress']));
	$cleanOrderCity    = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_POST['orderCity']));
	$cleanOrderState   = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_POST['orderState']));
	$cleanOrderZip     = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_POST['orderZip']));
	$cleanOrderTel     = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_POST['orderTel']));
	$cleanOrderEmail   = htmlspecialchars(mysqli_real_escape_string($mySQLI, $_POST['orderEmail']));
	
	// recount price and total items in case user tries to change url to not pay anything
	$cartRes = mysqli_query
	(
		$mySQLI, 
		"
			SELECT 
				si.itemPrice,
				st.selItemQty
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
	if (mysqli_num_rows($cartRes) > 0)
	{
		$totalItems = 0;
		$totalPriceOfEverything = 0;
		
		while ($cartInfo = mysqli_fetch_array($cartRes)) 
		{
			$itemPrice = $cartInfo['itemPrice'];
			$itemQty = $cartInfo['selItemQty'];
			
			$totalItems = $totalItems + $itemQty;
			
			$totalPrice = sprintf("%.02f", $itemPrice * $itemQty);
			$totalPriceOfEverything =  sprintf("%.02f", $totalPriceOfEverything + $totalPrice);
		}
		
	
		// create and issue the first query
		// TODO: Add authorization
		mysqli_query
		(
			$mySQLI,
<<<end_of_text
			INSERT INTO storeOrders
			(
				orderDate,
				orderName,
				orderAddress,
				orderCity,
				orderState,
				orderZip,
				orderTel,
				orderEmail,
				itemTotal,
				shippingTotal,
				status
			)
			VALUES
			(
				NOW(),
				'$cleanOrderName',
				'$cleanOrderAddress',
				'$cleanOrderCity',
				'$cleanOrderState',
				'$cleanOrderZip',
				'$cleanOrderTel',
				'$cleanOrderEmail',
				'$totalItems',
				'$totalPriceOfEverything',
				'0'
			) 
end_of_text
		) 
		or die
		(
			mysqli_error($mySQLI)
		);
		
		// acquire id of order
		$orderId = mysqli_insert_id($mySQLI);
		
		// add cart items into order items
		$cartRes = mysqli_query
		(
			$mySQLI, 
			"
				SELECT 
					st.id, 
					si.itemPrice,
					st.selItemId,
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
		or die
		(
			mysqli_error($mySQLI)
		);
		while ($cartInfo = mysqli_fetch_array($cartRes)) 
		{
			$id = $cartInfo['id'];
			$itemId = $cartInfo['selItemId'];
			$itemPrice = $cartInfo['itemPrice'];
			$itemQty = $cartInfo['selItemQty'];
			$itemColor = $cartInfo['selItemColor'];
			$itemSize = $cartInfo['selItemSize'];
			
			mysqli_query
			(
				$mySQLI,
<<<end_of_text
				INSERT INTO storeOrdersItems
				(
					orderId,
					selItemId,
					selItemQty,
					selItemSize,
					selItemColor,
					selItemPrice
				)
				VALUES
				(
					'$orderId',
					'$itemId',
					'$itemQty',
					'$itemSize',
					'$itemColor',
					'$itemPrice'
				) 
end_of_text
			) 
			or die
			(
				mysqli_error($mySQLI)
			);
		}
		// empty shopping cart
		mysqli_query
		(
			$mySQLI, 
			"
				DELETE FROM storeShoppingCart
				WHERE sessionId = '".$_COOKIE['PHPSESSID']."'
			"
		)
		or die
		(
			mysqli_error($mySQLI)
		);
	}
	
	// close connection to MySQL
	mysqli_close($mySQLI);
	
	
	header('Location: index.php?sect=store&page=confirmCheckout&orderId='.$orderId.'&orderEmail='.$cleanOrderEmail.'&orderAddress='.$cleanOrderAddress.'&orderCity='.$cleanOrderCity.'&orderState='.$cleanOrderState.'&orderZip='.$cleanOrderZip.'&orderTel='.$cleanOrderTel);
?>