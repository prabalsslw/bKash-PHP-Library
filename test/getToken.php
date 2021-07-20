<?php 
	include '../lib/Bkash.php';

	use Bkash\Checkout\Bkash;

	$token = new Bkash();

	echo "<pre>";
	print_r($token->grantToken());

?>