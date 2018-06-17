<?php
	function doDB()
	{
		global $mySQLI;
		
		// connect to server
		$mySQLI = mySQLI_connect('localhost', 'root', '100100101101011011010010');
		
		// if connection fails, stop script execution
		if (mySQLI_connect_errno())
		{
			printf('Connection Failed: '.mySQLI_connect_error());
			exit();
		}
		
		// create the database
		$createDB = 
		"
		CREATE DATABASE IF NOT EXISTS db
		";
		mysqli_query($mySQLI, $createDB) or die(mysqli_error($mySQLI));
		// connect to database
		mysqli_select_db($mySQLI, 'db');
		
		
		/* Topic Forum queries
		 */
		 
		// create forum categories table
		mysqli_query
		(
			$mySQLI, 
			"
			CREATE TABLE IF NOT EXISTS forumCategories
			(
				categoryTitle VARCHAR(150) NOT NULL PRIMARY KEY
			);
			"
		) or die(mysqli_error($mySQLI));
		
		// add some new forum categories
		mysqli_query
		(
			$mySQLI,
			"
			INSERT INTO forumCategories
			(
				categoryTitle
			)
			VALUES
			(
				'Miscellaneous'
			)
			ON DUPLICATE KEY UPDATE categoryTitle = 'Miscellaneous'
			"
		) or die(mysqli_error($mySQLI));
		
		// create forum topics table
		mysqli_query
		(
			$mySQLI, 
			"
			CREATE TABLE IF NOT EXISTS forumTopics
			(
				topicId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				categoryTitle VARCHAR(150) NOT NULL,
				topicTitle VARCHAR(150),
				topicCreateTime DATETIME,
				topicOwner VARCHAR(150)
			);
			"
		) or die(mysqli_error($mySQLI));
		// create table for posts
		mysqli_query
		(
			$mySQLI, 
			"
			CREATE TABLE IF NOT EXISTS forumPosts
			(
				postId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				topicId INT NOT NULL,
				postText TEXT,
				postCreateTime DATETIME,
				postOwner VARCHAR(150)
			);
			"
		) or die(mysqli_error($mySQLI));
		
		
		/* Store queries
		 */
		 
		// store categories
		mysqli_query
		(
			$mySQLI, 
			"
			CREATE TABLE IF NOT EXISTS storeCategories
			(
				id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				catTitle VARCHAR(50) UNIQUE,
				catDesc TEXT
			);
			"
		) or die(mysqli_error($mySQLI));
		// store items
		mysqli_query
		(
			$mySQLI, 
			"
			CREATE TABLE IF NOT EXISTS storeItems
			(
				id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				catId INT NOT NULL,
				itemTitle VARCHAR(75),
				itemPrice FLOAT(16,2),
				itemDesc TEXT,
				itemImage VARCHAR(50)
			);
			"
		) or die(mysqli_error($mySQLI));
		// store item size for shirt sizes etc.
		mysqli_query
		(
			$mySQLI, 
			"
			CREATE TABLE IF NOT EXISTS storeItemSizes
			(
				id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				itemId INT NOT NULL,
				itemSize VARCHAR(25)
			);
			"
		) or die(mysqli_error($mySQLI));
		// store item color for shirt colors etc.
		mysqli_query
		(
			$mySQLI,
			"
			CREATE TABLE IF NOT EXISTS storeItemColors
			(
				id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				itemId INT NOT NULL,
				itemColor VARCHAR(25)
			);
			"
		) or die(mysqli_error($mySQLI));
		// item quantity that uses ids of item, color and size as composite key
		mysqli_query
		(
			$mySQLI,
			"
			CREATE TABLE IF NOT EXISTS storeItemQtys
			(
				itemId INT NOT NULL,
				itemSizeId INT,
				itemColorId INT,
				itemQty INT NOT NULL
			);
			"
		) or die(mysqli_error($mySQLI));
		// shopping cart table
		mysqli_query
		(
			$mySQLI, 
			"
			CREATE TABLE IF NOT EXISTS storeShoppingCart
			(
				id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				sessionId VARCHAR(32),
				selItemId INT,
				selItemQty INT,
				selItemSizeId INT,
				selItemSize VARCHAR(25),
				selItemColorId INT,
				selItemColor VARCHAR(25),
				dateAdded DATETIME
			)
			"
		) 
		or die
		(
			mysqli_error($mySQLI)
		);
		// orders table
		mysqli_query
		(
			$mySQLI, 
			"
			CREATE TABLE IF NOT EXISTS storeOrders
			(
				id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				orderDate DATETIME,
				orderName VARCHAR(100),
				orderAddress VARCHAR(255),
				orderCity VARCHAR(50),
				orderState VARCHAR(3),
				orderZip VARCHAR(10),
				orderTel VARCHAR(100),
				orderEmail VARCHAR(100),
				itemTotal INT,
				shippingTotal FLOAT(16,2),
				authorization VARCHAR(50),
				status ENUM('possessed','pending')
			)
			"
		) 
		or die
		(
			mysqli_error($mySQLI)
		);
		// order items table
		mysqli_query
		(
			$mySQLI, 
			"
			CREATE TABLE IF NOT EXISTS storeOrdersItems
			(
				id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				orderId INT,
				selItemId INT,
				selItemQty INT,
				selItemSize VARCHAR(25),
				selItemColor VARCHAR(25),
				selItemPrice FLOAT(16,2)
			)
			"
		) 
		or die
		(
			mysqli_error($mySQLI)
		);
		
		// create some definite store categories
		mysqli_query($mySQLI, 
		"
		INSERT INTO storeCategories (catTitle, catDesc) VALUES
		('Miscellaneous',  'You want it? We got it.')
		ON DUPLICATE KEY UPDATE 
			catTitle = 'Miscellaneous';
		"
		) or die(mysqli_error($mySQLI));
		mysqli_query($mySQLI, 
		"
		INSERT INTO storeCategories (catTitle, catDesc) VALUES
		('Shirts', 'From t-shirts to sweatshirts to polo shirts and beyond.')
		ON DUPLICATE KEY UPDATE 
			catTitle = 'Shirts';
		"
		) or die(mysqli_error($mySQLI));
		mysqli_query($mySQLI, 
		"
		INSERT INTO storeCategories (catTitle, catDesc) VALUES
		('Books',  'Paperback hardback books for school or play.')
		ON DUPLICATE KEY UPDATE 
			catTitle = 'Books';
		"
		) or die(mysqli_error($mySQLI));
		mysqli_query($mySQLI, 
		"
		INSERT INTO storeCategories (catTitle, catDesc) VALUES
		('Writing',   'Make a mark for yourself.')
		ON DUPLICATE KEY UPDATE 
			catTitle = 'Writing'
		"
		) or die(mysqli_error($mySQLI));
		mysqli_query($mySQLI, 
		"
		INSERT INTO storeCategories (catTitle, catDesc) VALUES
		('Erasing',   'Never fear an error ever again with these items.')
		ON DUPLICATE KEY UPDATE 
			catTitle = 'Erasing'
		"
		) or die(mysqli_error($mySQLI));
	}
	
	/**
	 * storeItem:
	 * TASK : "Ensure that OOP PHP is used somewhere in the site – you do not have to convert all your PHP to OOP. "
	 * - a class that contains clean values from the database
	 */
	class storeItem
	{
		public $itemTitle;
		public $itemPrice;
		public $itemDesc;
		public $itemImage;
		
		function __construct($selectedData)
		{
			$this->itemTitle = stripslashes($selectedData['itemTitle']);
			$this->itemPrice = $selectedData['itemPrice'];
			$this->itemDesc  = stripslashes($selectedData['itemDesc']);
			$this->itemImage = $selectedData['itemImage'];
		}
	}
	
	function displayCartLink($mySQLI)
	{
		$itemCount = 0;
		$itemCountRes = mysqli_query($mySQLI, "SELECT selItemQty FROM storeShoppingCart") or die(mysqli_error($mySQLI));
		if (mysqli_num_rows($itemCountRes) > 0)
		{
			while ($count = mysqli_fetch_array($itemCountRes))
				$itemCount = $itemCount + $count['selItemQty'];
		}
		echo "<div style='padding:10px;text-align:right;'><a href='index.php?sect=store&page=viewShoppingCart'>Items in Cart ($itemCount)</a></div>";
	}
?>