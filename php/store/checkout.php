<h1>Checkout</h1>

<?php
	// check for required fields from the form
	if ((!$_GET['id']))
	{
		header('Location: index.php?sect=store&page=seeStore');
		exit;
	}
?>

<?php echo "<form method='post' action='index.php?sect=store&page=doCheckout&id=".$_GET['id']."&totalItems=".$_GET['totalItems']."&totalPrice=".$_GET['totalPrice']."'>"; ?>

	<p>
		<input type='text' id='orderName' name='orderName' size='40' maxLength='150' placeholder="Your name ..."/>
	</p>

	<p>
		<input type='text' id='orderAddress' name='orderAddress' size='40' maxLength='150' placeholder="Address ..."/>
	</p>

	<p>
		<input type='text' id='orderCity' name='orderCity' size='40' maxLength='150' placeholder="City ..." style="width:30%"/>
		<input type='text' id='orderState' name='orderState' size='40' maxLength='3' placeholder="State ..." style="width:30%"/>
		<input type='text' id='orderZip' name='orderZip' size='40' maxLength='10' placeholder="ZIP Code ..." style="width:30%"/>
	</p>

	<p>
		<input type='text' id='orderTel' name='orderTel' size='40' maxLength='150' placeholder="Telephone No. ..."/>
	</p>

	<p>
		<input type='email' id='orderEmail' name='orderEmail' size='40' maxLength='150' required='required' placeholder="Your Email Address ..."/>
	</p>

	<button type='submit'> Checkout </button>

</form>