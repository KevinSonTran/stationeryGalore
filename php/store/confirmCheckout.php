<?php
	if ((!$_GET['orderEmail']))
	{
		header('Location: index.php?sect=store&page=seeStore');
		exit;
	}
	
	echo '<h1>Order Success</h1>';
	echo '<p> Your order is now pending. We will send you an e-mail at <i>'.$_GET['orderEmail'].'</i> once your order is processed. </p>';
	echo '<p> Below is a receipt of your order. This receipt will also be e-mailed to you at <i>'.$_GET['orderEmail'].'</i>. </p>';
	
	echo '<h2>Order no: '.$_GET['orderId'].'</h2>';
	echo '<p><strong>Mailing Address : </strong> '.$_GET['orderAddress'].' , '.$_GET['orderCity'].' , '.$_GET['orderState'].' , '.$_GET['orderZip'].'</p>';
	echo '<p><strong>Telephone No. : </strong> '.$_GET['orderTel'].'</p>';
	echo '<p><strong>Email : </strong> '.$_GET['orderEmail'].'</p>';
	
	
	// acquire ordered items for receipt
	$orderRes = mysqli_query
	(
		$mySQLI, 
		" 	SELECT
				storeItems.itemTitle,
				storeOrdersItems.selItemQty,
				storeOrdersItems.selItemSize,
				storeOrdersItems.selItemColor,
				storeOrdersItems.selItemPrice
			FROM storeItems INNER JOIN storeOrdersItems
			ON storeItems.id = storeOrdersItems.selItemId
			WHERE storeOrdersItems.orderId = ".$_GET['orderId']
	)
	or die
	(
		mysqli_error($mySQLI)
	);
	
	// display receipt
	$displayBlock = 
<<<END_OF_TEXT
	<table>
		<tr>
			<th>Title</th>
			<th>Size</th>
			<th>Color</th>
			<th>Price</th>
			<th>Qty</th>
			<th>Total Price</th>
		</tr>
END_OF_TEXT;
	
	$totalItems = 0;
	$totalPriceOfEverything = 0;
	
	while ($orderInfo = mysqli_fetch_array($orderRes)) 
	{
		$itemTitle = stripslashes($orderInfo['itemTitle']);
		$itemPrice = $orderInfo['selItemPrice'];
		$itemQty = $orderInfo['selItemQty'];
		$itemColor = $orderInfo['selItemColor'];
		$itemSize = $orderInfo['selItemSize'];
		$totalPrice = sprintf("%.02f", $itemPrice * $itemQty);
		
		$totalItems = $totalItems + $itemQty;
		$totalPriceOfEverything =  sprintf("%.02f", $totalPriceOfEverything + $totalPrice);

		$displayBlock .= 
<<<END_OF_TEXT
			<tr>
				<td>$itemTitle <br></td>
				<td>$itemSize <br></td>
				<td>$itemColor <br></td>
				<td>\$ $itemPrice <br></td>
				<td>$itemQty <br></td>
				<td>\$ $totalPrice</td>
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
				<td> $totalItems <br></td>
				<td>\$ $totalPriceOfEverything</td>
			</tr>
END_OF_TEXT;

	$displayBlock .= 
	"
	</table>
	<p>Thank you for shopping at MAH.com! <a href='index.php?sect=store&page=seeStore'>Go back to Store</a></p>
	
	";
	
	echo $displayBlock;
?>
