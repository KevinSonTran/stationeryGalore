/**
 * displayStoreItems:
 * - acquires the category name from the selectable options,
 *   uses it to select any topics with the matching category name
 *   and updates the page with the new topics
 */
function displayStoreItems(catId)
{
	xmlhttp = acquireXMLHttpRequest("displayBlock");
	
	/* Auto update the page to display topics of certain category
	 */
	xmlhttp.open("GET", "php/store/showItems.php?catId=" + catId, true);
	xmlhttp.send();
}
/**
 * displayStoreQty:
 * - acquires the ids of item, color and size to determine appropriate qty,
 *   for that kind of item, then updates page to display that qty
 */
function displayStoreQty(category, value, otherValue, viewingItemId)
{
	xmlhttp = acquireXMLHttpRequest("content");
	
	/* Auto update the page to display topics of certain category
	 */
	if (category == 'size')
	{
		xmlhttp.open("GET", "php/store/viewItem.php?itemId="+viewingItemId+"&qtySizeId="+value+"&qtyColorId="+otherValue, true);
		xmlhttp.send();
	}
	else if (category == 'color')
	{
		xmlhttp.open("GET", "php/store/viewItem.php?itemId="+viewingItemId+"&qtySizeId="+otherValue+"&qtyColorId="+value, true);
		xmlhttp.send();
	}
}