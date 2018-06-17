/**
 * toggleNewForm:
 * - if "ADD NEW" is selected in the categories option, 
 *   the input field will be displayed where the user can
 *   enter the name of new category
 * - if "ADD NEW" is deselected, the input field will disappear
 *   and all content inside the field will be erased
 */
function toggleNewForm(selectElement, displayClass)
{
	var displayCategories = document.getElementsByClassName(displayClass);
	var display = 'none';
	
	/* Display textbox for user to input new category name
	 */
	if (selectElement.value == "addNew")
	{
		display = 'inline';
	}
	
	
	for (var i = 0; i < displayCategories.length; i++)
	{
		displayCategories[i].style.display = display;
	}
	
	/* If textbox no longer displays, erase anything in textbox
	 */
	if (display == 'none')
	{
		for (var i = 0; i < displayCategories.length; i++)
		{
			displayCategories[i].value = null;
		}
	}
}