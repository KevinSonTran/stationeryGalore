/**
 * acquireXMLHttpRequest:
 * TASK : "Include at least one AJAX feature."
 * - checks the current browser
 *   and returns appropriate XMLHttpRequest
 * - acquires name of id of HTML display block as input
 *   so that the AJAX results will be displayed onto that block
 */
function acquireXMLHttpRequest(displayBlock)
{
	/* IE7+, Firefox, Chrome, Opera, Safari
	 */
	if (window.XMLHttpRequest)
	{
		xmlhttp = new XMLHttpRequest();
	}
	/* IE6, IE5
	 */
	else
	{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	/* Load response text from XMLHttpRequest onto display block
	 */
	xmlhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200)
		{
			document.getElementById(displayBlock).innerHTML = this.responseText;
		}
	}
	return xmlhttp;
}