<?php
	include_once(__DIR__."/../../db.php");
	doDB();
	
	displayCartLink($mySQLI);
	
	$displayBlock = 
	"
	";
	
	$safeItemId = mysqli_real_escape_string($mySQLI, $_GET['itemId']);
	$getItemRes = mysqli_query
	(
		$mySQLI,  
<<<end_of_text
			SELECT	
				c.id AS catId, 
				c.catTitle, 
				si.itemTitle,
				si.itemPrice,
				si.itemDesc,
				si.itemImage
			FROM
				storeItems AS si LEFT JOIN storeCategories AS c ON c.id = si.catId
			WHERE
				si.id = '$safeItemId';
end_of_text
	) or die(mysqli_error($mySQLI));
	
	if (mysqli_num_rows($getItemRes) < 1)
	{
		$displayBlock .= "<p></i>No such item exists</i></p>";
	}
	else
	{
		while ($itemInfo = mysqli_fetch_array($getItemRes))
		{
			$catTitle = stripslashes($itemInfo['catTitle']);
			$viewingStoreItem = new storeItem($itemInfo);
		}
	}
	
	/* Breadcrumb trail
	 */
	$displayBlock .=
<<<end_of_text
		<div class="breadcrumbTrail">
			<strong> <a href="index.php?sect=store&page=seeStore">$catTitle</a> &gt; $viewingStoreItem->itemTitle </strong> <br/>
		</div>
end_of_text;

	/* The actual item
     */
	
	$existingColorId = $_GET['qtyColorId'];
	$existingSizeId =  $_GET['qtySizeId'];
	
	$displayBlock .=
<<<end_of_text
		<div class="viewItem">
			<img 
				src="$viewingStoreItem->itemImage" 
				alt="There's supposed to be an image of $viewingStoreItem->itemTitle here"
				
				width="auto" height="256px"
			/>
		</div>
		
		<div class="viewItem" id="itemNo$safeItemId">
			<h2> $viewingStoreItem->itemTitle </h2>
			<p> <i> $viewingStoreItem->itemDesc </i> </p>
			<p> <strong>Price :       </strong> <br/> $ $viewingStoreItem->itemPrice </p>
			
			
			<form method='post' action='index.php?sect=store&page=addToCart&qtySizeId=$existingSizeId&qtyColorId=$existingColorId'>
end_of_text;

	mysqli_free_result($getItemRes);
	
	
	/* Colors
	 */ 
	$getColorsRes = mysqli_query
	(
		$mySQLI, 
<<<end_of_text
			SELECT id, itemColor FROM storeItemColors
			WHERE
				itemId = '$safeItemId'
			ORDER BY itemColor;
end_of_text
	) or die(mysqli_error($mySQLI));
	
	if (mysqli_num_rows($getColorsRes) > 0)
	{
		$displayBlock .= 
<<<end_of_text
			<strong>Available Colors : </strong>
			<select id="selItemColor" name="selItemColor" onchange="displayStoreQty('color', this.value, $existingSizeId, $safeItemId);">
				<option value'-1'>-- CHOOSE ONE --</option>
end_of_text;
		while($colors = mysqli_fetch_array($getColorsRes))
		{
			$colorId = $colors['id'];
			$itemColor = $colors['itemColor'];
			$displayBlock .= "<option value='$colorId' ".($colorId == $existingColorId ? "selected" : "").">$itemColor</option>";
		}
		$displayBlock .= 
<<<end_of_text
			</select>
end_of_text;
	}
	
	mysqli_free_result($getColorsRes);
	
	/* Sizes
	 */
	$getSizesRes = mysqli_query
	(
		$mySQLI,
<<<end_of_text
			SELECT id, itemSize FROM storeItemSizes
			WHERE
				itemId = '$safeItemId'
			ORDER BY itemSize;
end_of_text
	) or die(mysqli_error($mySQLI));
	
	if (mysqli_num_rows($getSizesRes) > 0)
	{
		$displayBlock .= 
<<<end_of_text
		<p>
			<strong>Available Sizes : </strong>
			<select id="selItemSize" name="selItemSize" onchange="displayStoreQty('size', this.value, $existingColorId, $safeItemId);">
				<option value'-1'>-- CHOOSE ONE --</option>
end_of_text;
		while($Sizes = mysqli_fetch_array($getSizesRes))
		{
			$sizeId = $Sizes['id'];
			$itemSize = $Sizes['itemSize'];
			$displayBlock .= "<option value='$sizeId' ".($sizeId == $existingSizeId ? "selected" : "").">$itemSize</option>";
		}
		$displayBlock .= 
<<<end_of_text
			</select>
		</p>
end_of_text;
	}
	
	mysqli_free_result($getSizesRes);
	
	
	/* Quantity
	 * TASK : "Optional: have the quantity of items in drop down list populated from the database."
	 */
	$getQtyRes = mysqli_query
	(
		$mySQLI, 
<<<end_of_text
			SELECT itemQty FROM storeItemQtys
			WHERE itemId = '$safeItemId'
			AND itemSizeId = '$existingSizeId'
			AND itemColorId = '$existingColorId'
end_of_text
	) 
	or die(mysqli_error($mySQLI));
	
	if (mysqli_num_rows($getQtyRes) > 0)
	{
		while ($qtyInfo = mysqli_fetch_array($getQtyRes))
		{
			$finalQty = $qtyInfo['itemQty'];
			
			if ($finalQty <= 0)
			{
				$displayBlock .= "<i>Sorry, this item of these settings is out of stock</i>";
			}
			else
			{
				$displayBlock .= 
<<<end_of_text
				<p>
					<strong>Quantity : </strong>
					<select id="selItemQty" name="selItemQty">
end_of_text;
				// display quantity
				for($i = 1; $i < $finalQty + 1; $i++)
				{
					$displayBlock .= "<option value='$i'>$i</option>";
				}
				$displayBlock .= 
<<<end_of_text
					</select>
				</p>
end_of_text;
	
	
				$displayBlock .= 
<<<end_of_text
					<input type='hidden' name='selItemId' value='$safeItemId' />
					<button type='submit' name='submit' value='submit'>Add to Cart</button>
				</form>
end_of_text;
			}
		}
	}
	else
	{
		$displayBlock .= ($existingSizeId > 0 && $existingColorId > 0 ? "<i>Sorry, this item is out of stock</i>" : "<i>Please choose your settings</i>");
	}
	
	mysqli_free_result($getQtyRes);
	
	
	$displayBlock .=
<<<end_of_text
		</div>
end_of_text;

	mysqli_close($mySQLI);
	
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Showing Item</title>
	</head>
	
	<body>
		<?php echo $displayBlock; ?>
	</body>
</html>