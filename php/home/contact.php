<?php
	$requiredFilled = true;
	$displayBlock = 
	"
		<h1>Contact</h1>
		If you have any queries you want to discuss in detail, feel free to fill out this form. We'll get back to you as soon as possible. 
		<br />
		<br />
		<form method='post' action='index.php?sect=home&page=contact&visited' id='contactForm'>
	";
	
	$displayBlock .= "<input type='text' name='contactName' placeholder='Name ...' value=''/>";
	if (isset($_GET['visited']) && empty($_POST['contactName']))
	{
		$displayBlock .= "<i style='color:#ff0000'>Please enter your name</i>";
		$requiredFilled = false;
	}
	$displayBlock .= " </br>";
	
	$displayBlock .= "<input type='email' name='contactEmail' placeholder='E-mail ...' />";
	if (isset($_GET['visited']) && empty($_POST['contactEmail']))
	{
		$displayBlock .= "<i style='color:#ff0000'>Please enter your E-mail</i>";
		$requiredFilled = false;
	}
	$displayBlock .= " </br>";
	
	$displayBlock .= "<input type='text' name='contactTopic' placeholder='Topic name ...' />";
	if (isset($_GET['visited']) && empty($_POST['contactTopic']))
	{
		$displayBlock .= "<i style='color:#ff0000'>Please name your topic</i>";
		$requiredFilled = false;
	}
	$displayBlock .= " </br>";
	
	$displayBlock .= "<textarea rows='5' cols='45' name='contactContent' placeholder='Your message ...'></textarea>";
	if (isset($_GET['visited']) && empty($_POST['contactContent']))
	{
		$displayBlock .= "<i style='color:#ff0000'>Please enter your message</i>";
		$requiredFilled = false;
	}
	$displayBlock .= " </br>";
	
	$displayBlock .=
	"
		<input type='submit' value='Send Message'>
	</form>
	";
	
	
	if ($requiredFilled && isset($_GET['visited']))
	{
		$displayBlock .=
		"
			<br />
			<br />
			<strong><i>Your message has been sent. Thank you for your time.</i></strong>
		";
	}
	
	echo $displayBlock;
?>