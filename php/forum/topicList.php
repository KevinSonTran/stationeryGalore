<?php
	
	/* The add topic form will be displayed on top
	 * of the forums page
	 */
	include "addTopic.php";
?>

<?php
	echo
<<<end_of_text
		<div id="displayBlock">
end_of_text;

	/* For now, all topics in forum will be displayed in display block
	 */
	include "showTopics.php";
	
	echo
<<<end_of_text
		</div>
end_of_text;

	// close the connection
	mysqli_close($mySQLI);
?>