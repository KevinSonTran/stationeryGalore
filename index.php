<?php
	session_start();
	
	if (!count($_GET))
	{
		header('Location: index.php?sect=home&page=home');;
	}
	
	
	include_once('db.php');
	doDB();
?>

<!DOCTYPE html>
<html>

	<head>
		
		<!-- This line for responsive design -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
		<link rel="stylesheet" type='text/css' href="style.css">
		
		<script src="scripts/utilities/utilities.js"></script>
		<script src="scripts/ajax/ajax.js"></script>
		<script src="scripts/forum/forum.js"></script>
		<script src="scripts/store/store.js"></script>
		
	</head>

	<body>
		
		<!--
			TASK : "A functioning multi-category forum with database connection to store previous topics and posts. 
			        Ensure all links to test functionality are working i.e. easily navigate
					between categories, topics and related posts."
		-->
		<nav>
			<a href='index.php?sect=home&page=home'><div id='logo'><h1>Stationery Galore</h1></div></a><a href='index.php?sect=store&page=seeStore'><div><h1>Store</h1></div></a><a href='index.php?sect=forum&page=topicList'><div><h1>Forum</h1></div></a><a href='index.php?sect=home&page=contact'><div><h1>Contact</h1></div></a>
		</nav>
		
		<div id="content"><?php include "php/".$_GET['sect']."/".$_GET['page'].".php";?></div>
		
		
	</body>

	<footer>
	
	<p>Copyright © Stationery Galore 2018</p>
	<p>Some Rights Reserved, All images used in this site are under the <a href="https://creativecommons.org/share-your-work/public-domain/cc0/">Creative Commons License</a></p>
	
	</footer>

</html>